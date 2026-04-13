/**
 * Placeholder queue worker dashboard.
 * Runs on port 8001 until PHP is installed and `php artisan queue:work` can take over.
 */

const http = require('http');

const PORT = process.env.PORT || 8001;
const HOST = '127.0.0.1';

const startTime = new Date();

function uptime() {
  const secs = Math.floor((Date.now() - startTime) / 1000);
  const m = Math.floor(secs / 60);
  const s = secs % 60;
  return m > 0 ? `${m}m ${s}s` : `${s}s`;
}

const html = () => `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="refresh" content="5" />
  <title>Queue Worker — Setup Required</title>
  <style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;
         background:#0f172a;color:#e2e8f0;min-height:100vh;
         display:flex;align-items:center;justify-content:center;padding:2rem}
    .card{background:#1e293b;border:1px solid #334155;border-radius:1rem;
          padding:2.5rem;max-width:640px;width:100%}
    h1{font-size:1.5rem;font-weight:700;color:#f8fafc;margin-bottom:.5rem}
    .badge{display:inline-flex;align-items:center;gap:.4rem;background:#f59e0b22;
           color:#f59e0b;border:1px solid #f59e0b44;border-radius:9999px;
           font-size:.75rem;font-weight:600;padding:.2rem .75rem;margin-bottom:1.5rem}
    .status-row{display:flex;gap:1rem;margin-bottom:1.75rem;flex-wrap:wrap}
    .stat{background:#0f172a;border:1px solid #334155;border-radius:.75rem;
          padding:.75rem 1.25rem;flex:1;min-width:120px}
    .stat-label{font-size:.7rem;font-weight:600;color:#64748b;
                text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem}
    .stat-value{font-size:1.1rem;font-weight:700;color:#f8fafc}
    .log{background:#0f172a;border:1px solid #334155;border-radius:.5rem;
         padding:1rem 1.25rem;font-family:'Courier New',monospace;font-size:.82rem;
         color:#94a3b8;margin-bottom:1.5rem;line-height:1.7}
    .log span.ts{color:#475569}
    .log span.warn{color:#f59e0b}
    .log span.ok{color:#4ade80}
    pre{background:#0f172a;border:1px solid #334155;border-radius:.5rem;
        padding:1rem 1.25rem;font-size:.85rem;color:#7dd3fc;
        overflow-x:auto;margin-bottom:.75rem}
    .label{font-size:.75rem;font-weight:600;color:#64748b;text-transform:uppercase;
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
    <h1>Laravel Queue Worker</h1>

    <div class="status-row">
      <div class="stat">
        <div class="stat-label">Status</div>
        <div class="stat-value warn">Placeholder</div>
      </div>
      <div class="stat">
        <div class="stat-label">Uptime</div>
        <div class="stat-value">${uptime()}</div>
      </div>
      <div class="stat">
        <div class="stat-label">Jobs processed</div>
        <div class="stat-value">—</div>
      </div>
      <div class="stat">
        <div class="stat-label">Queue</div>
        <div class="stat-value">default</div>
      </div>
    </div>

    <div class="log">
      <div><span class="ts">[${new Date().toISOString()}]</span> <span class="warn">WARN</span>  PHP binary not found on PATH.</div>
      <div><span class="ts">[${new Date().toISOString()}]</span> <span class="warn">WARN</span>  Node.js placeholder active — no jobs will be processed.</div>
      <div><span class="ts">[${new Date().toISOString()}]</span> INFO   Page auto-refreshes every 5 seconds.</div>
    </div>

    <div class="step">
      <div class="label">Install PHP &amp; start the real worker</div>
      <pre>winget install PHP.PHP.8.3
winget install Composer.Composer
composer install
php artisan queue:work --tries=3 --timeout=90</pre>
    </div>

    <div class="footer">
      <span class="ok">✓ Node ${process.version}</span>
      <span class="warn">✗ PHP: not found</span>
      <span>Port ${PORT}</span>
    </div>
  </div>
</body>
</html>`;

const server = http.createServer((_req, res) => {
  res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
  res.end(html());
});

server.listen(PORT, HOST, () => {
  console.log(`Queue worker placeholder running at http://${HOST}:${PORT}`);
  console.log('Replace with: php artisan queue:work --tries=3 --timeout=90');
});
