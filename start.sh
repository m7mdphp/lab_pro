#!/usr/bin/env bash
# Railpack Shell-provider entry point.
#
# Railpack selects the Shell provider when it cannot detect a language —
# this happens until composer.json exists (Laravel not yet scaffolded).
# Once you run `composer create-project laravel/laravel . --prefer-dist`,
# Railpack will detect PHP automatically and this file becomes unused;
# `railway/app.railway.json` startCommand takes over.
#
# Until then, this serves the Node.js placeholder on the expected PORT.
set -euo pipefail
exec node server.js
