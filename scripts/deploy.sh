#!/usr/bin/env bash
set -euo pipefail

# Ensure we run from the project root (the `Portfolio` folder) even when this
# script is invoked from a different working directory. This makes `npm ci`,
# `npm run build` and `rsync` operate against the intended files.
PROJECT_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$PROJECT_ROOT"
echo "Working directory: $PROJECT_ROOT"

# deploy.sh - déploiement sécurisé par rsync vers Alwaysdata
# Usage:
#   ./scripts/deploy.sh [--dry-run] [--no-build]
# Variables (override by export or env on invocation):
#   REMOTE_USER (default: jeremyescassut)
#   REMOTE_HOST (default: ssh1)
#   REMOTE_PATH (default: /home/jeremyescassut/www/Portfolio/)

: ${REMOTE_USER:="jeremyescassut"}
: ${REMOTE_HOST:="alwaysdata"}
: ${REMOTE_PATH:="/home/jeremyescassut/www/Portfolio/"}

DRY_RUN=0
NO_BUILD=0

for arg in "$@"; do
  case "$arg" in
    --dry-run) DRY_RUN=1 ;;
    --no-build) NO_BUILD=1 ;;
    -h|--help)
      sed -n '1,120p' "$0"
      exit 0 ;;
    *) echo "Unknown arg: $arg"; exit 2 ;;
  esac
done

# Excludes: do not overwrite server .env, bootstrap caches, or local dev deps
RSYNC_EXCLUDES=(
  "node_modules"
  "vendor"
  ".git"
  ".env"
  "bootstrap/cache"
  "public/hot"
  ".github"
)

EXCLUDE_ARGS=()
for e in "${RSYNC_EXCLUDES[@]}"; do
  EXCLUDE_ARGS+=(--exclude "$e")
done

echo "Deploy config:"
echo "  REMOTE: ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_PATH}"
echo "  Dry run: ${DRY_RUN}"
echo "  Build step skipped: ${NO_BUILD}"

timestamp() { date +"%Y-%m-%d_%H%M%S"; }

# Quick connectivity check: avoid starting a build if the remote host is wrong/unreachable.
# Skip this check during a dry-run so the script can still show planned actions.
if [ "$DRY_RUN" -eq 0 ]; then
  echo "==> Checking SSH connectivity to ${REMOTE_HOST}..."
  if ! ssh -o BatchMode=yes -o ConnectTimeout=5 "${REMOTE_USER}@${REMOTE_HOST}" true 2>/dev/null; then
    echo "ERROR: Cannot reach ${REMOTE_USER}@${REMOTE_HOST}." >&2
    echo "Check that REMOTE_HOST is correct (see Alwaysdata control panel) and that your network/DNS can resolve it." >&2
    echo "If you don't know the host, try the panel's SSH/Access section or add an SSH alias in ~/.ssh/config." >&2
    exit 4
  fi
fi

# Build locally unless requested not to
if [ "$NO_BUILD" -eq 0 ]; then
  echo "==> Building frontend assets (npm ci && npm run build)"
  if [ $DRY_RUN -eq 1 ]; then
    echo "DRY RUN: would run: npm ci && npm run build"
  else
    if command -v npm >/dev/null 2>&1; then
      npm ci
      npm run build
    else
      echo "npm not found in PATH — skipping build. Export NO_BUILD=1 or install node/npm." >&2
      exit 3
    fi
  fi
fi

# rsync from current directory to remote
RSYNC_CMD=(rsync -az --delete --compress --human-readable --progress)
RSYNC_CMD+=("${EXCLUDE_ARGS[@]}")
RSYNC_CMD+=(./ "${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_PATH}")

if [ $DRY_RUN -eq 1 ]; then
  echo "DRY RUN: rsync command: ${RSYNC_CMD[*]}"
else
  echo "==> Running rsync to ${REMOTE_HOST}"
  "${RSYNC_CMD[@]}"
fi

## Post-deploy commands run directly on the remote server using an ssh heredoc
if [ $DRY_RUN -eq 1 ]; then
  echo "DRY RUN: remote commands to run:" 
  cat <<'REMOTE_EOF'
set -e
cd "${REMOTE_PATH}"
# remove any stale bootstrap caches that can cause provider/autoload mismatches
rm -f bootstrap/cache/*.php || true
# Run composer if vendor is absent or to regenerate autoload (optional)
if [ -f composer.json ] && [ ! -d vendor ]; then
  composer install --no-dev --optimize-autoloader --prefer-dist
elif [ -f composer.json ]; then
  # regenerate autoload (safe)
  composer dump-autoload -o || true
fi
# Clear and recache Laravel artefacts
php artisan view:clear || true
php artisan config:clear || true
php artisan cache:clear || true
# Recreate optimized caches
php artisan config:cache || true
php artisan route:cache || true
REMOTE_EOF
else
  echo "==> Running post-deploy commands on remote"
# Expand ${REMOTE_PATH} locally so the remote `cd` uses an explicit path
  ssh "${REMOTE_USER}@${REMOTE_HOST}" bash -s -- <<SSH_EOF
set -e
cd "${REMOTE_PATH}"
# remove any stale bootstrap caches that can cause provider/autoload mismatches
rm -f bootstrap/cache/*.php || true
# Run composer if vendor is absent or to regenerate autoload (optional)
if [ -f composer.json ] && [ ! -d vendor ]; then
  composer install --no-dev --optimize-autoloader --prefer-dist
elif [ -f composer.json ]; then
  # regenerate autoload (safe)
  composer dump-autoload -o || true
fi
# Clear and recache Laravel artefacts
php artisan view:clear || true
php artisan config:clear || true
php artisan cache:clear || true
# Recreate optimized caches
php artisan config:cache || true
php artisan route:cache || true
SSH_EOF
fi

echo "Deploy finished at $(timestamp)"

# Helpful next steps
if [ $DRY_RUN -eq 1 ]; then
  echo "Note: This was a dry run. Re-run without --dry-run to perform the deploy."
fi
