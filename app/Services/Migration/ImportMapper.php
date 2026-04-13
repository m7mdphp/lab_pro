<?php

namespace App\Services\Migration;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Maps normalised extraction records to database rows.
 *
 * All write operations go through a $dryRun guard: when true, every write
 * is logged but no SQL is executed. The caller receives a positive fake ID
 * (0) to indicate success without a real record.
 *
 * All entity writes use updateOrCreate on the slug so the mapper is fully
 * idempotent — safe to re-run after partial failures.
 *
 * Preload slug→id maps once per import run to avoid N+1 FK resolution.
 */
class ImportMapper
{
    /** @var array<string, int> category slug → categories.id */
    private array $categoryIds = [];

    /** @var array<string, int> media source_url → media.id */
    private array $mediaIds    = [];

    private array $cfg;

    public function __construct(private readonly ContentNormalizer $normalizer)
    {
        $this->cfg = config('migration');
    }

    // ── Bootstrap ──────────────────────────────────────────────────────────────

    /**
     * Preload slug/URL → ID maps from the database.
     * Call this once before processing a batch to avoid repeated queries.
     */
    public function preload(): void
    {
        $this->categoryIds = DB::table('test_categories')->pluck('id', 'slug')->all();
        $this->mediaIds    = DB::table('media')->whereNotNull('source_url')->pluck('id', 'source_url')->all();

        Log::channel('migration')->info('ImportMapper::preload', [
            'categories' => count($this->categoryIds),
            'media'      => count($this->mediaIds),
        ]);
    }

    // ── Categories ─────────────────────────────────────────────────────────────

    public function importCategory(array $data, bool $dryRun = false): ?int
    {
        $slug = $data['slug'] ?? '';

        if (!$this->isValidSlug($slug)) {
            Log::channel('migration')->debug('Category skipped — invalid/blocked slug', ['slug' => $slug]);
            return null;
        }

        if ($dryRun) {
            Log::channel('migration')->info('[DRY-RUN] importCategory', [
                'slug'    => $slug,
                'name_en' => $data['name_en'] ?? '',
            ]);
            return 0;
        }

        $category = DB::table('test_categories')->updateOrInsert(
            ['slug' => $slug],
            ['is_active' => 1, 'sort_order' => 0, 'updated_at' => now(), 'created_at' => now()],
        );

        $categoryId = DB::table('test_categories')->where('slug', $slug)->value('id');

        $this->upsertTranslation('test_category_translations', 'test_category_id', $categoryId, 'en', [
            'name'             => $data['name_en'] ?? Str::title(str_replace('-', ' ', $slug)),
            'description'      => $data['description_en'] ?? null,
            'meta_title'       => $data['meta_title_en'] ?? null,
            'meta_description' => $data['meta_description_en'] ?? null,
        ]);

        $this->upsertTranslation('test_category_translations', 'test_category_id', $categoryId, 'ar', [
            'name'             => $data['name_ar'] ?? null,
            'description'      => $data['description_ar'] ?? null,
            'meta_title'       => $data['meta_title_ar'] ?? null,
            'meta_description' => $data['meta_description_ar'] ?? null,
        ]);

        $this->upsertSeoMeta('category', $slug, $data, "/tests/category/{$slug}");

        // Register in local map for FK resolution during this run
        $this->categoryIds[$slug] = $categoryId;

        Log::channel('migration')->info('Category imported', ['slug' => $slug, 'id' => $categoryId]);

        return $categoryId;
    }

    // ── Packages ───────────────────────────────────────────────────────────────

    public function importPackage(array $data, bool $dryRun = false): ?int
    {
        $slug = $data['slug'] ?? '';

        if (!$this->isValidSlug($slug)) {
            Log::channel('migration')->debug('Package skipped — invalid/blocked slug', ['slug' => $slug]);
            return null;
        }

        $hasPrice  = isset($data['price_piastres']) && $data['price_piastres'] !== null;
        $isActive  = $hasPrice ? 1 : 0;

        // Resolve thumbnail
        $thumbnailId = null;
        if (!empty($data['images'][0])) {
            $thumbnailId = $this->mediaIds[$data['images'][0]] ?? null;
        }

        if ($dryRun) {
            Log::channel('migration')->info('[DRY-RUN] importPackage', [
                'slug'         => $slug,
                'name_en'      => $data['name_en'] ?? '',
                'price'        => $data['price_piastres'],
                'is_active'    => $isActive,
                'categories'   => $data['category_slugs'] ?? [],
                'thumbnail_id' => $thumbnailId,
            ]);
            return 0;
        }

        DB::table('packages')->updateOrInsert(
            ['slug' => $slug],
            [
                'price'              => $data['price_piastres'],
                'original_price'     => $data['original_price_piastres'] ?? null,
                'is_active'          => $isActive,
                'is_featured'        => 0,
                'is_kit'             => $data['is_kit'] ? 1 : 0,
                'sample_type'        => $data['sample_type'] ?? null,
                'sort_order'         => 0,
                'thumbnail_media_id' => $thumbnailId,
                'source_url'         => $data['_meta']['source_url'] ?? null,
                'quality_score'      => null,
                'quality_grade'      => null,
                'updated_at'         => now(),
                'created_at'         => now(),
            ],
        );

        $packageId = DB::table('packages')->where('slug', $slug)->value('id');

        $this->upsertTranslation('package_translations', 'package_id', $packageId, 'en', [
            'name'              => $data['name_en'] ?? Str::title(str_replace('-', ' ', $slug)),
            'description'       => $data['description_en'] ?? null,
            'short_description' => $data['short_description_en'] ?? null,
            'meta_title'        => $data['meta_title_en'] ?? null,
            'meta_description'  => $data['meta_description_en'] ?? null,
        ]);

        $this->upsertTranslation('package_translations', 'package_id', $packageId, 'ar', [
            'name'              => $data['name_ar'] ?? null,
            'description'       => $data['description_ar'] ?? null,
            'short_description' => $data['short_description_ar'] ?? null,
            'meta_title'        => $data['meta_title_ar'] ?? null,
            'meta_description'  => $data['meta_description_ar'] ?? null,
        ]);

        $this->upsertSeoMeta('package', $slug, $data, "/tests/{$slug}");

        // Sync category pivot
        $categoryIds = [];
        foreach ($data['category_slugs'] ?? [] as $catSlug) {
            if (isset($this->categoryIds[$catSlug])) {
                $categoryIds[] = $this->categoryIds[$catSlug];
            } else {
                Log::channel('migration')->warning('Category slug not resolved', [
                    'package'  => $slug,
                    'category' => $catSlug,
                ]);
            }
        }

        if (!empty($categoryIds)) {
            // Delete existing pivot rows then re-insert (simple sync without Eloquent)
            DB::table('package_category')->where('package_id', $packageId)->delete();
            $pivotRows = array_map(fn($cid) => [
                'package_id'  => $packageId,
                'category_id' => $cid,
            ], $categoryIds);
            DB::table('package_category')->insert($pivotRows);
        }

        Log::channel('migration')->info('Package imported', [
            'slug'      => $slug,
            'id'        => $packageId,
            'is_active' => $isActive,
        ]);

        return $packageId;
    }

    // ── Media ──────────────────────────────────────────────────────────────────

    public function importMedia(array $mediaData, bool $dryRun = false): ?int
    {
        if ($dryRun) {
            Log::channel('migration')->info('[DRY-RUN] importMedia', [
                'source_url' => $mediaData['source_url'],
                'path'       => $mediaData['path'],
            ]);
            return 0;
        }

        DB::table('media')->updateOrInsert(
            ['source_url' => $mediaData['source_url']],
            [
                'disk'                  => $mediaData['disk'],
                'path'                  => $mediaData['path'],
                'filename'              => $mediaData['filename'],
                'mime_type'             => $mediaData['mime_type'],
                'file_size_bytes'       => $mediaData['file_size'] ?? null,
                'width'                 => $mediaData['width'] ?? null,
                'height'                => $mediaData['height'] ?? null,
                'content_hash'          => $mediaData['content_hash'],
                'referenced_by_entity'  => $mediaData['entity_type'],
                'referenced_by_slug'    => $mediaData['entity_slug'],
                'referenced_by_field'   => $mediaData['field'],
                'alt_en'                => null,
                'alt_ar'                => null,
                'is_active'             => 1,
                'is_optimized_jpeg'     => 0,
                'is_optimized_webp'     => 0,
                'updated_at'            => now(),
                'created_at'            => now(),
            ],
        );

        $id = DB::table('media')->where('source_url', $mediaData['source_url'])->value('id');

        // Register in local map so packages imported in the same run can resolve FKs
        if ($id) {
            $this->mediaIds[$mediaData['source_url']] = $id;
        }

        return $id;
    }

    // ── FAQs ───────────────────────────────────────────────────────────────────

    public function importFaqs(array $faqItems, bool $dryRun = false): int
    {
        $imported = 0;

        foreach (array_values($faqItems) as $i => $item) {
            $question = $item['question'] ?? '';
            if (empty(trim($question))) continue;

            if ($dryRun) {
                Log::channel('migration')->info('[DRY-RUN] importFaq', ['question' => $question]);
                $imported++;
                continue;
            }

            $contentHash = $this->normalizer->contentHash([$question]);

            // Check if a FAQ with this question already exists
            $existing = DB::table('faq_translations')
                ->where('locale', 'en')
                ->where('question', $question)
                ->value('faq_id');

            if ($existing) {
                $faqId = $existing;
            } else {
                $faqId = DB::table('faqs')->insertGetId([
                    'sort_order' => $i + 1,
                    'is_active'  => 1,
                    'category'   => 'general',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $this->upsertTranslation('faq_translations', 'faq_id', $faqId, 'en', [
                'question' => $question,
                'answer'   => $item['answer'] ?? '',
            ]);

            $this->upsertTranslation('faq_translations', 'faq_id', $faqId, 'ar', [
                'question' => $item['question_ar'] ?? null,
                'answer'   => $item['answer_ar'] ?? null,
            ]);

            $imported++;
        }

        return $imported;
    }

    // ── Partners ───────────────────────────────────────────────────────────────

    public function importPartners(array $partners, bool $dryRun = false): int
    {
        $imported = 0;

        foreach (array_values($partners) as $i => $partner) {
            $name = $partner['name'] ?? '';
            if (empty(trim($name))) continue;

            $slug = Str::slug($name);

            if ($dryRun) {
                Log::channel('migration')->info('[DRY-RUN] importPartner', ['name' => $name, 'slug' => $slug]);
                $imported++;
                continue;
            }

            DB::table('partners')->updateOrInsert(
                ['slug' => $slug],
                [
                    'is_active'   => 1,
                    'sort_order'  => $i,
                    'website_url' => $partner['website'] ?? null,
                    'updated_at'  => now(),
                    'created_at'  => now(),
                ],
            );

            $partnerId = DB::table('partners')->where('slug', $slug)->value('id');

            DB::table('partner_translations')->updateOrInsert(
                ['partner_id' => $partnerId, 'locale' => 'en'],
                ['name' => $name, 'updated_at' => now(), 'created_at' => now()],
            );

            $imported++;
        }

        return $imported;
    }

    // ── Internal ───────────────────────────────────────────────────────────────

    private function upsertTranslation(
        string $table,
        string $fkColumn,
        int    $fkId,
        string $locale,
        array  $fields,
    ): void {
        DB::table($table)->updateOrInsert(
            [$fkColumn => $fkId, 'locale' => $locale],
            array_merge($fields, ['updated_at' => now(), 'created_at' => now()]),
        );
    }

    private function upsertSeoMeta(
        string $entityType,
        string $entitySlug,
        array  $data,
        string $canonicalPath,
    ): void {
        DB::table('seo_meta')->updateOrInsert(
            ['entity_type' => $entityType, 'entity_slug' => $entitySlug],
            [
                'meta_title_en'       => $data['meta_title_en'] ?? null,
                'meta_title_ar'       => $data['meta_title_ar'] ?? null,
                'meta_description_en' => $data['meta_description_en'] ?? null,
                'meta_description_ar' => $data['meta_description_ar'] ?? null,
                'canonical_url'       => url($canonicalPath),
                'noindex_en'          => 0,
                'noindex_ar'          => 1,  // AR is noindex until translated
                'updated_at'          => now(),
                'created_at'          => now(),
            ],
        );
    }

    private function isValidSlug(string $slug): bool
    {
        if ($slug === '' || strlen($slug) < $this->cfg['extraction']['min_name_chars']) {
            return false;
        }

        return !in_array($slug, $this->cfg['extraction']['slug_blocklist'], true);
    }
}
