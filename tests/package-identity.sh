#!/usr/bin/env bash
set -euo pipefail
PLUGIN="$(cd "$(dirname "$0")/.." && pwd)"

fail(){ echo "PACKAGE_IDENTITY=FAIL"; echo "REASON=$1"; exit 1; }

[ "$(basename "$PLUGIN")" = "yablonsky-ai-chatbots" ] || fail "plugin folder must be yablonsky-ai-chatbots"
[ -f "$PLUGIN/yablonsky-ai-chatbots.php" ] || fail "main plugin file missing"
[ ! -f "$PLUGIN/yablonsky-secure-chatbots.php" ] || fail "legacy main plugin file must not be packaged"
grep -q '^ \* Plugin Name: Yablonsky AI Chatbots$' "$PLUGIN/yablonsky-ai-chatbots.php" || fail "Plugin Name mismatch"
grep -q '^ \* Text Domain: yablonsky-ai-chatbots$' "$PLUGIN/yablonsky-ai-chatbots.php" || fail "Text Domain mismatch"
grep -q '^=== Yablonsky AI Chatbots ===$' "$PLUGIN/readme.txt" || fail "readme title mismatch"
if grep -Eq '^ \* Plugin Name: .*WordPress|^=== .*WordPress.* ===$' "$PLUGIN/yablonsky-ai-chatbots.php" "$PLUGIN/readme.txt"; then
  fail "WordPress must not be part of current plugin identity"
fi

echo "PACKAGE_FOLDER=yablonsky-ai-chatbots"
echo "MAIN_FILE=yablonsky-ai-chatbots.php"
echo "PLUGIN_NAME=Yablonsky AI Chatbots"
echo "TEXT_DOMAIN=yablonsky-ai-chatbots"
echo "PACKAGE_IDENTITY=PASS"
