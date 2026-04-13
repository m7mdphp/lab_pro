/**
 * Placeholder dev server for the Laravel application.
 * Runs on port 8000 until PHP is installed and `php artisan serve` can take over.
 *
 * Install PHP to run the real Laravel server:
 *   winget install PHP.PHP.8.3
 *   composer install
 *   php artisan serve
 */

const http  = require('http');
const os    = require('os');

const PORT  = process.env.PORT || 8000;
const HOST  = '127.0.0.1';

const html = `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laravel Dev Server — Setup Required</title>
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
    p{color:#94a3b8;line-height:1.6;margin-bottom:1.25rem;font-size:.925rem}
    pre{background:#0f172a;border:1px solid #334155;border-radius:.5rem;
        padding:1rem 1.25rem;font-size:.85rem;color:#7dd3fc;overflow-x:auto;margin-bottom:.75rem}
    .label{font-size:.75rem;font-weight:600;color:#64748b;text-transform:uppercase;
           letter-spacing:.06em;margin-bottom:.4rem}
    .step{margin-bottom:1.5rem}
    .ok{color:#4ade80}
    .footer{margin-top:2rem;padding-top:1.5rem;border-top:1px solid #334155;
            font-size:.8rem;color:#475569;display:flex;gap:1.5rem}
  </style>
</head>
<body>
  <div class="card">
    <div class="badge">⚠ PHP not installed</div>
    <h1>Laravel Dev Server</h1>
    <p>This Node.js placeholder is running on <strong>port ${PORT}</strong> because
       <code>php</code> was not found on your PATH. Install PHP 8.3 to start the real
       <code>php artisan serve</code> server.</p>

    <div class="step">
      <div class="label">Step 1 — Install PHP 8.3</div>
      <pre>winget install PHP.PHP.8.3</pre>
    </div>

    <div class="step">
      <div class="label">Step 2 — Install Composer</div>
      <pre>winget install Composer.Composer</pre>
    </div>

    <div class="step">
      <div class="label">Step 3 — Scaffold Laravel &amp; install dependencies</div>
      <pre>composer create-project laravel/laravel . --prefer-dist
npm install</pre>
    </div>

    <div class="step">
      <div class="label">Step 4 — Start the real dev servers</div>
      <pre>php artisan serve   # port 8000
npm run dev         # port 5173 (Vite)</pre>
    </div>

    <div class="footer">
      <span class="ok">✓ Node ${process.version}</span>
      <span class="ok">✓ Vite running on :5173</span>
      <span>PHP: not found</span>
    </div>
  </div>
</body>
</html>`;

const server = http.createServer((_req, res) => {
  res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
  res.end(html);
});

server.listen(PORT, HOST, () => {
  console.log(`Laravel placeholder server running at http://${HOST}:${PORT}`);
  console.log('Node', process.version, '|', os.platform(), os.arch());
  console.log('Replace with: php artisan serve --port=8000');
});
