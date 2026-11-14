#!/usr/bin/env bash
set -e

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
TMP_DIR="/tmp/tfa7-deploy-$$"
OUTPUT_ZIP="$DIR/tfa7-infinityfree.zip"

echo "==> Building frontend assets for production..."
cd "$DIR"
if [ -d "$DIR/node_modules" ] && [ -f "$DIR/package.json" ]; then
    npm run build
fi

echo "==> Packaging lean production build for InfinityFree (tfa7.freehosting.dev)..."
rm -rf "$TMP_DIR" "$OUTPUT_ZIP"
mkdir -p "$TMP_DIR"

rsync -av \
  --exclude=".*" \
  --exclude="vendor" \
  --exclude="node_modules" \
  --exclude="tests" \
  --exclude="*.zip" \
  --exclude="build-infinityfree-zip.sh" \
  --exclude="deploy-infinityfree.sh" \
  --exclude="scripts" \
  --exclude="env.production.example" \
  --exclude="public/storage" \
  "$DIR/" "$TMP_DIR/" > /dev/null

[ -f "$DIR/.htaccess" ] && cp "$DIR/.htaccess" "$TMP_DIR/.htaccess"
[ -f "$DIR/index.php" ] && cp "$DIR/index.php" "$TMP_DIR/index.php"
[ -f "$DIR/public/.htaccess" ] && cp "$DIR/public/.htaccess" "$TMP_DIR/public/.htaccess"

if [ -d "$DIR/public/assets" ]; then
    mkdir -p "$TMP_DIR/assets"
    cp -r "$DIR/public/assets/"* "$TMP_DIR/assets/"
fi

if [ -d "$DIR/public/build" ]; then
    mkdir -p "$TMP_DIR/build"
    cp -r "$DIR/public/build/"* "$TMP_DIR/build/"
fi

[ -f "$DIR/public/favicon.svg" ] && cp "$DIR/public/favicon.svg" "$TMP_DIR/favicon.svg"
[ -f "$DIR/public/favicon.ico" ] && cp "$DIR/public/favicon.ico" "$TMP_DIR/favicon.ico"

if [ -f "$DIR/.env.production" ]; then
    cp "$DIR/.env.production" "$TMP_DIR/.env"
elif [ -f "$DIR/env.production.example" ]; then
    cp "$DIR/env.production.example" "$TMP_DIR/.env"
fi

if [ -f "$DIR/database/database.sqlite" ]; then
    mkdir -p "$TMP_DIR/database"
    cp "$DIR/database/database.sqlite" "$TMP_DIR/database/database.sqlite"
fi

if [ -f "$DIR/database/schema_export.sql" ]; then
    mkdir -p "$TMP_DIR/database"
    cp "$DIR/database/schema_export.sql" "$TMP_DIR/database/schema_export.sql"
fi

mkdir -p "$TMP_DIR/storage/app/public/avatars" \
         "$TMP_DIR/storage/framework/cache/data" \
         "$TMP_DIR/storage/framework/sessions" \
         "$TMP_DIR/storage/framework/views" \
         "$TMP_DIR/storage/logs" \
         "$TMP_DIR/bootstrap/cache"

cd "$TMP_DIR"
composer install --no-dev --optimize-autoloader --no-interaction --quiet

zip -r "$OUTPUT_ZIP" . -x "*.DS_Store" > /dev/null
rm -rf "$TMP_DIR"

echo "==> Verifying package contents..."
for req in "public/index.php" "index.php" ".htaccess" "public/.htaccess" "artisan" "vendor/autoload.php" "public/assets/css/style.css" "public/assets/js/app.js" "public/assets/images/avatar-placeholder.svg" "assets/css/style.css" ".env" "database/database.sqlite" "database/schema_export.sql"; do
    if ! unzip -l "$OUTPUT_ZIP" | grep -q "$req"; then
        echo "Error: Required file $req is missing from $OUTPUT_ZIP" >&2
        exit 1
    fi
done

echo "==> Verification passed! Build successful: $OUTPUT_ZIP"
ls -lh "$OUTPUT_ZIP"
