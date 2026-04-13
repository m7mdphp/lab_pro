#!/usr/bin/env bash
set -euo pipefail

echo "========================================"
echo "  Railway: Laravel queue worker"
echo "========================================"

# --queue priority order: highest first.
# 'high' reserved for future urgent jobs.
# 'imports' and 'media' are the main pipelines.
# 'default' catches all untagged dispatches.
#
# --max-time=3600  Worker exits cleanly after 1 hour; Railway restart
#                  policy brings it back — prevents memory leaks.
# --memory=512     Kill worker if PHP RAM exceeds 512MB (media jobs can spike).
# --timeout=90     Per-job execution timeout before SIGKILL.
# --tries=3        Retry a failed job up to 3 times before moving to failed_jobs.
# --sleep=3        Polling interval (seconds) when all queues are empty.
# --backoff=10     Wait 10 seconds before retrying a failed job.

exec php artisan queue:work \
  --connection=database \
  --queue=high,default,imports,media \
  --tries=3 \
  --backoff=10 \
  --timeout=90 \
  --sleep=3 \
  --memory=512 \
  --max-time=3600 \
  --verbose
