/**
 * Placeholder app server.
 * - Local: runs on port 8000 until PHP is installed.
 * - Railway: runs until composer.json is pushed and Railpack detects PHP.
 */

const http = require('http');
const os   = require('os');

const PORT     = process.env.PORT || 8000;
const HOST     = '0.0.0.0';
const ON_RAILWAY = !!process.env.RAILWAY_ENVIRONMENT_NAME || !!process.env.RAILWAY_PROJECT_ID;

const localSteps = `
  <div class="step">
    <div class="label">Step 1 — Install PHP 8.3 &amp; Composer</div>
    <pre>winget install PHP.PHP.8.3
winget install Composer.Composer</pre>
  </div>
  <div class="step">
    <div class="label">Step 2 — Scaffold Laravel</div>
    <pre>composer create-project laravel/laravel . --prefer-dist
npm install</pre>
  </div>
  <div class="step">
    <div class="label">Step 3 — Start real dev servers</div>
    <pre>php artisan serve   # port 8000
npm run dev         # port 5173 (Vite)</pre>
  </div>`;

const railwaySteps = `
  <div class="step">
    <div class="label">Step 1 — Scaffold Laravel locally</div>
    <pre>composer create-project laravel/laravel . --prefer-dist
npm install</pre>
  </div>
  <div class="step">
    <div class="label">Step 2 — Commit &amp; push to GitHub</div>
    <pre>git add composer.json composer.lock package.json
git commit -m "scaffold Laravel"
git push</pre>
  </div>
  <div class="step">
    <div class="label">Step 3 — Railway auto-deploys with PHP</div>
    <pre># Railpack detects composer.json → selects PHP 8.3 provider
# Pre-deploy runs: php artisan migrate --force
# App starts:      php artisan serve --host=0.0.0.0 --port=$PORT</pre>
  </div>`;

const html = () => `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laravel — Setup Required</title>
  <style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;
         background:#0f172a;color:#e2e8f0;min-height:100vh;
         display:flex;align-items:center;justify-content:center;padding:2rem}
    .card{background:#1e293b;border:1px solid #334155;border-radius:1rem;
          padding:2.5rem;max-width:640px;width:100%}
    h1{font-size:1.5rem;font-weight:700;color:#f8fafc;margin-bottom:.5rem}
    .badge{display:inline-flex;align-items:center;gap:.4rem;border-radius:9999px;
           font-size:.75rem;font-weight:600;padding:.2rem .75rem;margin-bottom:1.5rem;
           background:#f59e0b22;color:#f59e0b;border:1px solid #f59e0b44}
    .env-badge{display:inline-flex;align-items:center;gap:.4rem;border-radius:9999px;
               font-size:.75rem;font-weight:600;padding:.2rem .75rem;margin-bottom:1.5rem;
               margin-left:.5rem;background:#7c3aed22;color:#a78bfa;border:1px solid #7c3aed44}
    p{color:#94a3b8;line-height:1.6;margin-bottom:1.25rem;font-size:.925rem}
    pre{background:#0f172a;border:1px solid #334155;border-radius:.5rem;
        padding:1rem 1.25rem;font-size:.85rem;color:#7dd3fc;overflow-x:auto;margin-bottom:.75rem}
    .label{font-size:.75rem;font-weight:600;color:#64748b;text-transform:uppercase;
           letter-spacing:.06em;margin-bottom:.4rem}
    .step{margin-bottom:1.5rem}
    .ok{color:#4ade80} .warn{color:#f59e0b}
    .footer{margin-top:2rem;padding-top:1.5rem;border-top:1px solid #334155;
            font-size:.8rem;color:#475569;display:flex;gap:1.5rem;flex-wrap:wrap}
  </style>
</head>
<body>
  <div class="card">
    <div>
      <span class="badge">⚠ PHP not installed</span>
      <span class="env-badge">${ON_RAILWAY ? '⬆ Railway' : '⬡ Local'}</span>
    </div>
    <h1>Laravel App Server</h1>
    <p>Node.js placeholder is running on <strong>port ${PORT}</strong>.
       ${ON_RAILWAY
         ? 'Push <code>composer.json</code> to trigger Railpack\'s PHP provider and replace this placeholder automatically.'
         : 'Install PHP 8.3 to start the real <code>php artisan serve</code> server.'
       }</p>

    ${ON_RAILWAY ? railwaySteps : localSteps}

    <div class="footer">
      <span class="ok">✓ Node ${process.version}</span>
      <span class="warn">✗ PHP: not found</span>
      ${ON_RAILWAY
        ? `<span class="ok">⬆ Railway · port ${PORT}</span>`
        : `<span>Port ${PORT}</span>`
      }
    </div>
  </div>
</body>
</html>`;

const server = http.createServer((_req, res) => {
  res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
  res.end(html());
});

server.listen(PORT, HOST, () => {
  console.log(`Laravel placeholder running at http://${HOST}:${PORT}`);
  console.log(`Environment: ${ON_RAILWAY ? 'Railway' : 'local'}`);
  console.log('Node', process.version, '|', os.platform(), os.arch());
});
