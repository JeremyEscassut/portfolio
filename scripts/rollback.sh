#!/usr/bin/env bash
set -euo pipefail

PROJECT_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$PROJECT_ROOT"

echo "Working directory: $PROJECT_ROOT"

: ${REMOTE_USER:="jeremyescassut"}
: ${REMOTE_HOST:="ssh-jeremyescassut.alwaysdata.net"}
: ${REMOTE_PATH:="/home/jeremyescassut/www/Portfolio/"}

REMOTE_ROOT="${REMOTE_PATH%/}"

if ! command -v ssh >/dev/null 2>&1; then
  echo "ssh is required for rollback." >&2
  exit 2
fi

BACKUP_DIR="$(ssh "${REMOTE_USER}@${REMOTE_HOST}" bash -s -- <<SSH_EOF
set -e
remote_root='${REMOTE_ROOT}'
if [ ! -d "\$remote_root/backups" ]; then
  exit 0
fi
find "\$remote_root/backups" -mindepth 1 -maxdepth 1 -type d 2>/dev/null | sort | tail -n 1
SSH_EOF
)"

if [ -z "$BACKUP_DIR" ]; then
  echo "No backup directory found under ${REMOTE_ROOT}/backups" >&2
  echo "Nothing to restore." >&2
  exit 1
fi

echo "Restoring from backup: $BACKUP_DIR"

ssh "${REMOTE_USER}@${REMOTE_HOST}" bash -s -- <<SSH_EOF
set -e
remote_root='${REMOTE_ROOT}'
backup_dir='${BACKUP_DIR}'
mkdir -p "\$remote_root"
rsync -a --delete --exclude ".env" --exclude "node_modules" --exclude "vendor" --exclude "bootstrap/cache" --exclude "public/hot" "\$backup_dir/" "\$remote_root/"
cd "\$remote_root"
php artisan view:clear || true
php artisan config:clear || true
php artisan cache:clear || true
php artisan config:cache || true
php artisan route:cache || true
SSH_EOF

echo "Rollback completed."
echo "Check the site manually: homepage, CV, contact form."
