# Logging and Dry-Run Reference

## Log Channel

All pipeline services write to a dedicated `migration` log channel.
Add this to `config/logging.php` under `channels`:

```php
'migration' => [
    'driver' => 'daily',
    'path'   => storage_path('logs/migration/migration.log'),
    'level'  => env('MIGRATION_LOG_LEVEL', 'info'),
    'days'   => 14,
],
```

Set `MIGRATION_LOG_LEVEL=debug` in `.env` to enable verbose per-URL output.

---

## Log Levels Used

| Level   | Where                                           | Example message                                    |
|---------|-------------------------------------------------|----------------------------------------------------|
| `debug` | HttpFetcher throttle, UrlDiscovery BFS steps    | `"Visiting" url=... visited=12 queue=40`           |
| `debug` | Skipped duplicate URLs / hashes                 | `"Skipping duplicate URL" url=...`                 |
| `info`  | Sitemap seed counts, crawl finish totals        | `"BFS crawl finished" discovered=86 visited=91`    |
| `info`  | Successful extraction                           | `"Extracted" url=... name="CBC" price=35000`       |
| `info`  | Successful media download                       | `"Media downloaded" path=... size=84320`           |
| `info`  | Successful DB write                             | `"Category imported" slug=blood-tests id=3`        |
| `info`  | Dry-run intended actions                        | `"[DRY-RUN] importPackage" slug=... price=35000`   |
| `warning`| HTTP 404/403/410, MIME rejection, size cap     | `"Disallowed MIME type" url=... mime=text/html`    |
| `warning`| Unresolvable category FK                       | `"Category slug not resolved" package=... cat=...` |
| `error` | HTTP exhausted retries                          | `"HTTP fetch exhausted retries" url=...`           |
| `error` | Storage write failure                           | `"Storage::put failed" path=...`                   |
| `error` | Uncaught exception in extraction or import      | `"Extraction exception" url=... error=...`         |

---

## Dry-Run Mode

Both commands support `--dry-run`. The flag propagates through the full stack:

```
migration:crawl --dry-run
  → fetches HTML and extracts records
  → does NOT write any JSONL files
  → logs every record that would have been written

migration:import --dry-run
  → reads existing JSONL files
  → calls ImportMapper with dryRun=true
  → ImportMapper logs "[DRY-RUN] importPackage ..." for every record
  → executes NO SQL (no INSERT, no UPDATE, no DELETE)
  → returns fake ID=0 to indicate "would succeed"
```

### What dry-run does NOT skip

- HTTP requests to the source site (crawl still fetches pages)
- File system reads (JSONL files are read normally)
- `ImportMapper::preload()` (reads DB slug maps — read-only, safe)

### What dry-run skips

- `Storage::disk()->put(...)` — no media files written
- All `DB::table()->updateOrInsert(...)` calls
- All `DB::table()->insert(...)` calls
- All `DB::table()->delete(...)` calls (pivot sync)

---

## Output Files (JSONL)

Each line in a `.jsonl` file is one complete JSON object.
Files are written in append mode by default; use `--force` to overwrite.

```
storage/app/migration/extracted/
├── packages.jsonl       # one package record per line
├── categories.jsonl     # one category record per line
├── branches.jsonl       # one branch/page record per line
├── faqs.jsonl           # one FAQ page record per line (contains faq_items array)
├── partners.jsonl       # one partners page record per line (contains partners array)
├── pages.jsonl          # generic page records
├── media.jsonl          # one media download result per line
├── discovered_urls.json # full URL map (JSON, not JSONL)
└── failed.jsonl         # records that failed extraction or import
```

### Reading a JSONL file manually

```bash
# Count records
wc -l storage/app/migration/extracted/packages.jsonl

# Pretty-print first record
head -1 storage/app/migration/extracted/packages.jsonl | python3 -m json.tool

# Find a specific slug
grep '"slug":"complete-blood-count"' storage/app/migration/extracted/packages.jsonl | python3 -m json.tool

# List all slugs
cat storage/app/migration/extracted/packages.jsonl | python3 -c "
import sys, json
for line in sys.stdin:
    r = json.loads(line)
    print(r.get('slug','?'), '|', r.get('name_en','?'), '|', r.get('price_piastres','?'))
"
```

---

## Recommended Run Sequence

```bash
# Step 1 — Discover all URLs and write discovered_urls.json
php artisan migration:crawl --type=all

# Step 2 — Preview extraction output without writing files
php artisan migration:crawl --skip-discovery --dry-run

# Step 3 — Full extraction to JSONL (re-uses discovered URLs)
php artisan migration:crawl --skip-discovery --force

# Step 4 — Preview import without touching DB
php artisan migration:import --dry-run

# Step 5 — Import categories first (packages depend on FK)
php artisan migration:import --only=categories

# Step 6 — Import packages (with media download)
php artisan migration:import --only=packages --with-media

# Step 7 — Import remaining entities
php artisan migration:import --only=faqs
php artisan migration:import --only=partners
php artisan migration:import --only=branches

# Step 8 — Re-run entire import (idempotent — safe to repeat)
php artisan migration:import
```

---

## Duplicate Detection

Three layers prevent duplicate records:

| Layer        | Where                | Mechanism                                              |
|--------------|----------------------|--------------------------------------------------------|
| URL dedup    | MediaDownloader      | `seenUrls[]` map keyed by normalised source URL        |
| Hash dedup   | MediaDownloader      | `seenHashes[]` map keyed by MD5 of raw file bytes      |
| DB dedup     | ImportMapper         | `updateOrCreate` on slug for all entity types          |

Content hashes in `_meta.content_hash` are also written to `migration_records`
so cross-run duplicates can be detected by comparing hashes before importing.
