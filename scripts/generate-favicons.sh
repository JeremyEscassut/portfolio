#!/usr/bin/env bash
set -euo pipefail

# Génère les favicons à partir de resources/svg/icon-dev-bad.svg
#  - archive les anciens fichiers dans backups/unused-icons-<timestamp>
#  - génère public/favicon.svg, public/favicon-16.png, public/favicon-32.png,
#    public/favicon-48.png, public/apple-touch-icon.png et public/favicon.ico

ROOT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
SRC="$ROOT_DIR/resources/svg/icon-dev-bad.svg"
PUBLIC_DIR="$ROOT_DIR/public"
BACKUP_DIR="$ROOT_DIR/backups/unused-icons-$(date +%Y%m%d-%H%M)"

echo "Working from: $ROOT_DIR"

if [ ! -f "$SRC" ]; then
  echo "Source SVG not found: $SRC"
  echo "Place your SVG at resources/svg/icon-dev-bad.svg and re-run this script."
  exit 1
fi

mkdir -p "$BACKUP_DIR"
echo "Archiving existing favicon files (if present) to $BACKUP_DIR"
for f in favicon.svg favicon-16.png favicon-32.png favicon-48.png apple-touch-icon.png favicon.png favicon.ico favicon.ico.bak; do
  if [ -f "$PUBLIC_DIR/$f" ]; then
    mv "$PUBLIC_DIR/$f" "$BACKUP_DIR/"
    echo "  moved $f"
  fi
done

echo "Copying source SVG to public/favicon.svg"
cp "$SRC" "$PUBLIC_DIR/favicon.svg"

# Prefer ImageMagick (magick) if present, otherwise try rsvg-convert
if command -v magick >/dev/null 2>&1; then
  IMAGEMAGICK="$(command -v magick)"
  echo "Using ImageMagick at: $IMAGEMAGICK"
  "$IMAGEMAGICK" -background none "$SRC" -resize 16x16 "$PUBLIC_DIR/favicon-16.png"
  "$IMAGEMAGICK" -background none "$SRC" -resize 32x32 "$PUBLIC_DIR/favicon-32.png"
  "$IMAGEMAGICK" -background none "$SRC" -resize 48x48 "$PUBLIC_DIR/favicon-48.png"
  "$IMAGEMAGICK" -background none "$SRC" -resize 180x180 "$PUBLIC_DIR/apple-touch-icon.png"
  # create multi-resolution ICO (16/32/48)
  "$IMAGEMAGICK" "$PUBLIC_DIR/favicon-16.png" "$PUBLIC_DIR/favicon-32.png" "$PUBLIC_DIR/favicon-48.png" "$PUBLIC_DIR/favicon.ico"
elif command -v rsvg-convert >/dev/null 2>&1; then
  echo "Using rsvg-convert"
  rsvg-convert -w 16 -h 16 -o "$PUBLIC_DIR/favicon-16.png" "$SRC"
  rsvg-convert -w 32 -h 32 -o "$PUBLIC_DIR/favicon-32.png" "$SRC"
  rsvg-convert -w 48 -h 48 -o "$PUBLIC_DIR/favicon-48.png" "$SRC"
  rsvg-convert -w 180 -h 180 -o "$PUBLIC_DIR/apple-touch-icon.png" "$SRC"
  if command -v convert >/dev/null 2>&1; then
    convert "$PUBLIC_DIR/favicon-16.png" "$PUBLIC_DIR/favicon-32.png" "$PUBLIC_DIR/favicon-48.png" "$PUBLIC_DIR/favicon.ico"
  else
    echo "Warning: convert not found, favicon.ico not created. Install ImageMagick or "rsvg-convert + convert" to build ICO."
  fi
else
  echo "No suitable image conversion tool found. Install ImageMagick (magick) or librsvg (rsvg-convert)."
  exit 1
fi

echo "Generation complete. New files written to $PUBLIC_DIR :"
ls -la "$PUBLIC_DIR" | sed -n '/favicon\|apple-touch-icon/p'

cat <<EOF

Next steps:
 - Clear browser cache (Safari: Develop > Empty Caches or remove site data), then reload the page.
 - If you deploy to a remote server, remember to upload the new public/* files (public/favicon.*) and clear any CDN.

EOF

exit 0
