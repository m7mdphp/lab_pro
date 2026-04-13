#!/usr/bin/env bash
set -euo pipefail

echo "========================================"
echo "  Railway pre-deploy: Laravel init"
echo "========================================"

# 1. Clear any stale bootstrap/config cache from previous build
echo "--> Clearing stale optimize cache..."
php artisan optimize:clear

# 2. Run migrations — always force in production (no interactive prompt)
echo "--> Running database migrations..."
php artisan migrate --force --no-interaction

# 3. Create storage symlink (public/storage -> storage/app/public)
#    Volume is already mounted at /app/storage/app/public at this point.
#    Check for both a symlink and a real directory to avoid double-linking.
echo "--> Ensuring storage symlink..."
if [ -L "public/storage" ]; then
  echo "    Symlink already exists — skipping."
elif [ -d "public/storage" ]; then
  echo "    public/storage is a real directory — removing and re-linking..."
  rm -rf public/storage
  php artisan storage:link --relative
else
  php artisan storage:link --relative
fi

# 4. Cache compiled configuration — MUST happen after migrate, since
#    config values are resolved at cache time using env vars.
echo "--> Caching config..."
php artisan config:cache

# 5. Cache routes — needed before healthcheck fires
echo "--> Caching routes..."
php artisan route:cache

# 6. Cache Blade views — warm the view cache for first-request performance
echo "--> Caching views..."
php artisan view:cache

echo "--> Creating admin user if none exists..."
php artisan db:seed --class=AdminSeeder --force --no-interaction

echo "--> Pre-deploy complete."
