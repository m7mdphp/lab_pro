#!/usr/bin/env bash
set -euo pipefail

echo "========================================"
echo "  Railway: Laravel scheduler loop"
echo "========================================"

# Laravel's scheduler requires a running process calling schedule:run
# once per minute. Railway has no native cron trigger, so we loop manually.
# Stderr output from schedule:run is forwarded to Railway's log stream.

while true; do
  echo "[$(date -u +%Y-%m-%dT%H:%M:%SZ)] Running schedule:run..."
  php artisan schedule:run --no-interaction 2>&1
  sleep 60
done
