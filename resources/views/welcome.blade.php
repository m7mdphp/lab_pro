<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'AccuLab') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-200 min-h-screen flex items-center justify-center font-sans">

    <div class="max-w-lg w-full mx-auto px-6 py-12 text-center">
        <div class="inline-flex items-center gap-2 bg-green-500/10 text-green-400 border border-green-500/30
                    rounded-full text-xs font-semibold px-3 py-1 mb-6">
            ✓ Laravel {{ app()->version() }} · PHP {{ PHP_VERSION }}
        </div>

        <h1 class="text-3xl font-bold text-white mb-3">AccuLab</h1>
        <p class="text-slate-400 text-sm mb-8">
            Medical laboratory platform — application is running.
        </p>

        <div class="grid grid-cols-2 gap-3 text-left text-xs">
            <div class="bg-slate-900 border border-slate-800 rounded-lg p-4">
                <div class="text-slate-500 uppercase tracking-wide font-semibold mb-1">Environment</div>
                <div class="text-slate-200 font-mono">{{ config('app.env') }}</div>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-lg p-4">
                <div class="text-slate-500 uppercase tracking-wide font-semibold mb-1">Database</div>
                <div class="text-slate-200 font-mono">{{ config('database.default') }}</div>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-lg p-4">
                <div class="text-slate-500 uppercase tracking-wide font-semibold mb-1">Cache</div>
                <div class="text-slate-200 font-mono">{{ config('cache.default') }}</div>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-lg p-4">
                <div class="text-slate-500 uppercase tracking-wide font-semibold mb-1">Queue</div>
                <div class="text-slate-200 font-mono">{{ config('queue.default') }}</div>
            </div>
        </div>
    </div>

</body>
</html>
