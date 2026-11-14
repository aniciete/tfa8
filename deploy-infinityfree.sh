#!/usr/bin/env bash
# ====================================================================
# IT0049 POS TFA8 - Automated FTP Deployment to InfinityFree
# Loads FTP credentials from environment variables or gitignored .deploy.env
# ====================================================================

set -e

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$DIR"

if [ -f "$DIR/.deploy.env" ]; then
    set -a
    # shellcheck disable=SC1091
    source "$DIR/.deploy.env"
    set +a
fi

FTP_HOST="${FTP_HOST:-ftpupload.net}"
FTP_PORT="${FTP_PORT:-21}"
FTP_USER="${FTP_USER:-}"
FTP_PASS="${FTP_PASS:-}"
REMOTE_HTDOCS="htdocs"
ZIP_NAME="tfa8-infinityfree.zip"
ZIP_PATH="$DIR/$ZIP_NAME"
EXTRACT_PHP="$DIR/extract.php"
APP_URL="${APP_URL:-https://tfa8.infinityfreeapp.com}"

if [ -z "$FTP_USER" ] || [ -z "$FTP_PASS" ]; then
    echo "Error: Set FTP_USER and FTP_PASS via environment or .deploy.env before running." >&2
    exit 1
fi

echo "=========================================================="
echo " Starting InfinityFree FTP Deployment for TFA8"
echo " Target Domain : $APP_URL"
echo " FTP Host      : $FTP_HOST:$FTP_PORT"
echo " FTP User      : $FTP_USER"
echo "=========================================================="

if [ ! -f "$ZIP_PATH" ]; then
    chmod +x "$DIR/build-infinityfree-zip.sh"
    "$DIR/build-infinityfree-zip.sh"
fi

if [ ! -f "$EXTRACT_PHP" ]; then
    echo "Error: $EXTRACT_PHP missing." >&2
    exit 1
fi

echo "==> Uploading $ZIP_NAME via FTP..."
curl --connect-timeout 30 --max-time 600 --progress-bar --user "$FTP_USER:$FTP_PASS" -T "$ZIP_PATH" "ftp://$FTP_HOST/$REMOTE_HTDOCS/$ZIP_NAME"

echo "==> Uploading extract.php..."
curl --connect-timeout 20 --max-time 60 --progress-bar --user "$FTP_USER:$FTP_PASS" -T "$EXTRACT_PHP" "ftp://$FTP_HOST/$REMOTE_HTDOCS/extract.php"

echo "==> Removing placeholder index2.html if present..."
curl --connect-timeout 20 --max-time 30 -s --user "$FTP_USER:$FTP_PASS" -Q "DELE htdocs/index2.html" "ftp://$FTP_HOST/" > /dev/null || true

echo "==> FTP upload finished!"
echo "==> Open in browser to extract & import schema: $APP_URL/extract.php"
