<?php

namespace App\Console\Commands;

use App\Services\Migration\ImportMapper;
use App\Services\Migration\MediaDownloader;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Read the JSONL export files produced by migration:crawl and import their
 * records into the application database.
 *
 * Usage examples
 * ──────────────
 *   # Dry-run: log every intended write but touch nothing
 *   php artisan migration:import --dry-run
 *
 *   # Import only categories (must run before packages)
 *   php artisan migration:import --only=categories
 *
 *   # Import everything in correct dependency order
 *   php artisan migration:import
 *
 *   # Import packages and also download their media
 *   php artisan migration:import --only=packages --with-media
 *
 *   # Re-run against existing DB (idempotent via updateOrCreate)
 *   php artisan migration:import --force
 */
class ImportAcculabContent extends Command
{
    protected $signature = 'migration:import
                            {--dry-run       : Log all intended writes without executing them}
                            {--only=         : Import a single entity type: categories|packages|branches|faqs|partners|pages}
                            {--with-media    : Download and import media for packages}
                            {--limit=        : Cap records per entity type}
                            {--force         : Skip "already imported today" guard}
                            {--verbose-log   : Emit debug-level log lines}';

    protected $description = 'Import extracted JSONL files into the database';

    /** Counters per entity type. */
    private array $totals = [];

    private array $cfg;

    public function __construct(
        private readonly ImportMapper     $mapper,
        private readonly MediaDownloader  $mediaDownloader,
    ) {
        parent::__construct();
        $this->cfg = config('migration');
    }

    // ── Entry point ────────────────────────────────────────────────────────────

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $this->printBanner($dryRun);

        // Preload slug→ID maps for FK resolution (skipped in dry-run: DB read only)
        $this->mapper->preload();

        // Determine which entity types to process
        $order = $this->cfg['import']['order'];
        if ($only = $this->option('only')) {
            if (!in_array($only, $order, true)) {
                $this->error("Unknown entity type: {$only}. Valid values: " . implode(', ', $order));
                return self::FAILURE;
            }
            $order = [$only];
        }

        // ── Process each entity type ───────────────────────────────────────────
        foreach ($order as $entityType) {
            $path = $this->cfg['storage']['exports'][$entityType] ?? null;

            if (!$path || !file_exists($path)) {
                $this->warn("  No export file for {$entityType} — skipping. ({$path})");
                continue;
            }

            $this->line("\n  <options=bold>Importing {$entityType}</> from: {$path}");
            $this->importFile($path, $entityType, $dryRun);
        }

        // ── Summary ────────────────────────────────────────────────────────────
        $this->printSummary($dryRun);

        return self::SUCCESS;
    }

    // ── Per-file processing ────────────────────────────────────────────────────

    private function importFile(string $path, string $entityType, bool $dryRun): void
    {
        $limit  = (int) ($this->option('limit') ?: PHP_INT_MAX);
        $counts = ['ok' => 0, 'skip' => 0, 'fail' => 0];

        $handle = fopen($path, 'r');
        if (!$handle) {
            $this->error("  Cannot open {$path}");
            return;
        }

        $bar = $this->output->createProgressBar($this->countLines($path));
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%%');
        $bar->start();

        $processed = 0;

        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            if ($line === '') continue;

            $data = json_decode($line, true);
            if (!is_array($data)) {
                $counts['fail']++;
                $bar->advance();
                continue;
            }

            try {
                $id = $this->dispatchImport($data, $entityType, $dryRun);

                if ($id !== null) {
                    $counts['ok']++;

                    // Optionally download media for packages
                    if ($entityType === 'packages' && $this->option('with-media')) {
                        $this->importMediaForPackage($data, $dryRun);
                    }
                } else {
                    $counts['skip']++;
                }

            } catch (\Throwable $e) {
                $counts['fail']++;
                Log::channel('migration')->error('Import record exception', [
                    'entity' => $entityType,
                    'slug'   => $data['slug'] ?? '?',
                    'error'  => $e->getMessage(),
                ]);

                if ($this->option('verbose-log')) {
                    $this->warn("    ✗ {$e->getMessage()}");
                }
            }

            $bar->advance();
            $processed++;
            if ($processed >= $limit) break;
        }

        fclose($handle);
        $bar->finish();

        $this->newLine();
        $this->line("    ok: {$counts['ok']}  skip: {$counts['skip']}  fail: {$counts['fail']}");

        $this->totals[$entityType] = $counts;

        Log::channel('migration')->info("Imported {$entityType}", $counts);
    }

    // ── Entity dispatch ────────────────────────────────────────────────────────

    private function dispatchImport(array $data, string $entityType, bool $dryRun): ?int
    {
        return match ($entityType) {
            'categories' => $this->mapper->importCategory($data, $dryRun),
            'packages'   => $this->mapper->importPackage($data, $dryRun),
            'faqs'       => $this->importFaqsFromRecord($data, $dryRun),
            'partners'   => $this->importPartnersFromRecord($data, $dryRun),
            'branches',
            'pages'      => $this->importGenericPage($data, $dryRun),
            default      => null,
        };
    }

    // ── Specialised record handlers ────────────────────────────────────────────

    /**
     * FAQ records store an array of items inside the page record.
     */
    private function importFaqsFromRecord(array $data, bool $dryRun): ?int
    {
        $items = $data['faq_items'] ?? [];

        if (empty($items)) {
            // The record itself might be a bare FAQ item (entity_type = page/faq)
            // Try treating the record as a single item
            if (!empty($data['question'] ?? '')) {
                $items = [['question' => $data['question'], 'answer' => $data['answer'] ?? '']];
            }
        }

        if (empty($items)) return null;

        $count = $this->mapper->importFaqs($items, $dryRun);
        return $count > 0 ? 1 : null;
    }

    /**
     * Partner records are embedded in page records extracted from the
     * accreditation / partners page.
     */
    private function importPartnersFromRecord(array $data, bool $dryRun): ?int
    {
        $partners = $data['partners'] ?? [];
        if (empty($partners)) return null;

        $count = $this->mapper->importPartners($partners, $dryRun);
        return $count > 0 ? 1 : null;
    }

    /**
     * Branch and other page records — store raw data and dispatch sub-imports.
     */
    private function importGenericPage(array $data, bool $dryRun): ?int
    {
        $pageType = $data['page_type'] ?? 'generic';

        if ($pageType === 'faq' && !empty($data['faq_items'])) {
            return $this->importFaqsFromRecord($data, $dryRun);
        }

        if ($pageType === 'partners' && !empty($data['partners'])) {
            return $this->importPartnersFromRecord($data, $dryRun);
        }

        if ($pageType === 'branch' && !empty($data['branch_data'])) {
            return $this->importBranchData($data, $dryRun);
        }

        // For generic pages with no special handling, just log
        if ($dryRun) {
            Log::channel('migration')->info('[DRY-RUN] Generic page skipped', [
                'slug'      => $data['slug'] ?? '',
                'page_type' => $pageType,
            ]);
        }

        return null;
    }

    private function importBranchData(array $data, bool $dryRun): ?int
    {
        $slug   = $data['slug'] ?? 'branch';
        $branch = $data['branch_data'] ?? [];

        if ($dryRun) {
            Log::channel('migration')->info('[DRY-RUN] importBranch', [
                'slug'   => $slug,
                'phones' => $branch['phones'] ?? [],
            ]);
            return 0;
        }

        DB::table('branches')->updateOrInsert(
            ['slug' => $slug],
            [
                'is_active'     => 1,
                'phone_primary' => $branch['phones'][0] ?? null,
                'phone_secondary' => $branch['phones'][1] ?? null,
                'email'         => $branch['email'] ?? null,
                'address_line'  => $branch['address_raw'] ?? null,
                'city'          => null,  // requires manual mapping
                'hours'         => json_encode([]),
                'source_url'    => $data['_meta']['source_url'] ?? null,
                'updated_at'    => now(),
                'created_at'    => now(),
            ],
        );

        $branchId = DB::table('branches')->where('slug', $slug)->value('id');

        DB::table('branch_translations')->updateOrInsert(
            ['branch_id' => $branchId, 'locale' => 'en'],
            [
                'name'       => $data['title_en'] ?? $slug,
                'updated_at' => now(),
                'created_at' => now(),
            ],
        );

        return $branchId;
    }

    // ── Media ──────────────────────────────────────────────────────────────────

    private function importMediaForPackage(array $packageData, bool $dryRun): void
    {
        $images = $packageData['images'] ?? [];
        $slug   = $packageData['slug'] ?? 'unknown';

        foreach (array_values($images) as $i => $imageUrl) {
            $field  = $i === 0 ? 'thumbnail' : "image_{$i}";
            $result = $this->mediaDownloader->download($imageUrl, 'package', $slug, $field);

            if ($result) {
                $this->mapper->importMedia($result, $dryRun);
            }
        }
    }

    // ── Utilities ──────────────────────────────────────────────────────────────

    private function countLines(string $path): int
    {
        $count  = 0;
        $handle = fopen($path, 'r');
        if (!$handle) return 0;
        while (($line = fgets($handle)) !== false) {
            if (trim($line) !== '') $count++;
        }
        fclose($handle);
        return $count;
    }

    private function printBanner(bool $dryRun): void
    {
        $this->newLine();
        $this->line('┌──────────────────────────────────────────────────────┐');
        $this->line('│  migration:import — AccuLab DB import pipeline        │');
        $this->line('└──────────────────────────────────────────────────────┘');

        if ($dryRun) {
            $this->warn('  DRY-RUN mode — no database writes will be executed.');
        }

        $this->newLine();
    }

    private function printSummary(bool $dryRun): void
    {
        $this->newLine();
        $this->line('<options=bold>Import Summary</>');

        $rows = [];
        foreach ($this->totals as $type => $counts) {
            $rows[] = [$type, $counts['ok'], $counts['skip'], $counts['fail']];
        }

        if (!empty($rows)) {
            $this->table(['Entity type', 'Imported', 'Skipped', 'Failed'], $rows);
        } else {
            $this->line('  (nothing processed)');
        }

        $mode = $dryRun ? ' <comment>[DRY-RUN]</comment>' : '';
        $this->newLine();
        $this->line("Import complete.{$mode}");

        Log::channel('migration')->info('migration:import complete', [
            'dry_run' => $dryRun,
            'totals'  => $this->totals,
        ]);
    }
}
