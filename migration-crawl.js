/**
 * Placeholder for: php artisan migration:crawl --dry-run
 * Runs on port 8002. Shows what the crawl pipeline would do once PHP is installed.
 */

const http = require('http');

const PORT = process.env.PORT || 8002;
const HOST = '127.0.0.1';
const start = Date.now();

// Simulated log lines the real command would emit
const dryRunLog = [
  { t: 'INFO',  msg: '[DRY-RUN] migration:crawl — AccuLab extraction pipeline' },
  { t: 'INFO',  msg: 'Fetching sitemap → https://acculab.org/sitemap.xml' },
  { t: 'WARN',  msg: 'PHP binary not found — cannot execute real crawl' },
  { t: 'INFO',  msg: '[DRY-RUN] Would discover URLs from sitemap + BFS crawl' },
  { t: 'INFO',  msg: '[DRY-RUN] Would extract ~48 packages from /product/*' },
  { t: 'INFO',  msg: '[DRY-RUN] Would extract ~15 categories from /product-category/*' },
  { t: 'INFO',  msg: '[DRY-RUN] Would extract branches, FAQs, partners from pages' },
  { t: 'INFO',  msg: '[DRY-RUN] Would write packages.jsonl, categories.jsonl, …' },
  { t: 'INFO',  msg: '[DRY-RUN] No files written — dry-run complete' },
];

const ts = () => new Date().toISOString();

const logRows = dryRunLog.map(({ t, msg }) => {
  const color = t === 'WARN' ? '#f59e0b' : t === 'ERROR' ? '#f87171' : '#4ade80';
  return `<div><span class="ts">[${ts()}]</span> <span style="color:${color}">${t.padEnd(5)}</span> ${msg}</div>`;
}).join('\n');

const html = () => `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <meta http-equiv="refresh" content="10"/>
  <title>Migration Crawl — Dry Run</title>
  <style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;
         background:#0f172a;color:#e2e8f0;min-height:100vh;
         display:flex;align-items:center;justify-content:center;padding:2rem}
    .card{background:#1e293b;border:1px solid #334155;border-radius:1rem;
          padding:2.5rem;max-width:680px;width:100%}
    h1{font-size:1.5rem;font-weight:700;color:#f8fafc;margin-bottom:.5rem}
    .badge{display:inline-flex;align-items:center;gap:.4rem;background:#f59e0b22;
           color:#f59e0b;border:1px solid #f59e0b44;border-radius:9999px;
           font-size:.75rem;font-weight:600;padding:.2rem .75rem;margin-bottom:1.5rem}
    .stats{display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:1.75rem}
    .stat{background:#0f172a;border:1px solid #334155;border-radius:.75rem;
          padding:.65rem 1.1rem;flex:1;min-width:110px}
    .stat-label{font-size:.68rem;font-weight:600;color:#64748b;
                text-transform:uppercase;letter-spacing:.06em;margin-bottom:.25rem}
    .stat-value{font-size:1rem;font-weight:700;color:#f8fafc}
    .log{background:#0f172a;border:1px solid #334155;border-radius:.5rem;
         padding:1rem 1.25rem;font-family:'Courier New',monospace;font-size:.8rem;
         color:#94a3b8;margin-bottom:1.5rem;line-height:1.8;overflow-x:auto}
    .ts{color:#475569}
    pre{background:#0f172a;border:1px solid #334155;border-radius:.5rem;
        padding:1rem 1.25rem;font-size:.84rem;color:#7dd3fc;overflow-x:auto;margin-bottom:.75rem}
    .label{font-size:.72rem;font-weight:600;color:#64748b;text-transform:uppercase;
            letter-spacing:.06em;margin-bottom:.4rem}
    .step{margin-bottom:1.25rem}
    .footer{margin-top:1.75rem;padding-top:1.25rem;border-top:1px solid #334155;
            font-size:.8rem;color:#475569;display:flex;gap:1.5rem;flex-wrap:wrap}
    .ok{color:#4ade80} .warn{color:#f59e0b}
  </style>
</head>
<body>
<div class="card">
  <div class="badge">⚠ PHP not installed</div>
  <h1>Migration Crawl — Dry Run</h1>

  <div class="stats">
    <div class="stat"><div class="stat-label">Mode</div><div class="stat-value warn">Dry Run</div></div>
    <div class="stat"><div class="stat-label">Runtime</div><div class="stat-value warn">Placeholder</div></div>
    <div class="stat"><div class="stat-label">Target</div><div class="stat-value">acculab.org</div></div>
    <div class="stat"><div class="stat-label">Uptime</div><div class="stat-value">${Math.floor((Date.now()-start)/1000)}s</div></div>
  </div>

  <div class="log">${logRows}</div>

  <div class="step">
    <div class="label">Run the real dry-run once PHP is installed</div>
    <pre>winget install PHP.PHP.8.3
winget install Composer.Composer
composer install
php artisan migration:crawl --dry-run</pre>
  </div>

  <div class="step">
    <div class="label">Then run the live crawl + import</div>
    <pre>php artisan migration:crawl --skip-discovery --force
php artisan migration:import --dry-run
php artisan migration:import</pre>
  </div>

  <div class="footer">
    <span class="ok">✓ Node ${process.version}</span>
    <span class="warn">✗ PHP: not found</span>
    <span>Port ${PORT} · auto-refreshes every 10s</span>
  </div>
</div>
</body>
</html>`;

http.createServer((_req, res) => {
  res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
  res.end(html());
}).listen(PORT, HOST, () => {
  console.log(`Migration crawl placeholder running at http://${HOST}:${PORT}`);
  console.log('Replace with: php artisan migration:crawl --dry-run');
});
