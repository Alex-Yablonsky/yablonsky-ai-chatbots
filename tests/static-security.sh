#!/usr/bin/env bash
set -Eeuo pipefail
PLUGIN="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

mapfile -d '' FILES < <(find "$PLUGIN" -type f \( -name '*.php' -o -name '*.js' \) ! -path '*/tests/*' -print0)

if grep -Ein '\b(eval|exec|shell_exec|passthru|proc_open|popen)\s*\(' "${FILES[@]}"; then
  echo 'STATIC_DANGEROUS_EXECUTION=FAIL'
  exit 1
fi
echo 'STATIC_DANGEROUS_EXECUTION=PASS'

if grep -Ein 'name=[^>]*(preview|session|secret|provider|host[_-]?assertion)' "$PLUGIN"/*.php "$PLUGIN"/includes/*.php 2>/dev/null; then
  echo 'STATIC_PRIVILEGED_SETTINGS_FIELD=FAIL'
  exit 1
fi
echo 'STATIC_PRIVILEGED_SETTINGS_FIELD=PASS'

if grep -Ein '(localStorage|sessionStorage|dataset\.preview|previewToken\s*=|sessionToken\s*=|host_assertion\s*:)' "$PLUGIN/public/js/loader.js"; then
  echo 'STATIC_BROWSER_CREDENTIAL_STORAGE=FAIL'
  exit 1
fi
echo 'STATIC_BROWSER_CREDENTIAL_STORAGE=PASS'

if grep -Ein '(googletagmanager|dataLayer|clickio)' "$PLUGIN/public/js/loader.js" "$PLUGIN/includes/class-yablonsky-secure-chatbots-frontend.php"; then
  echo 'STATIC_GTM_CLICKIO_DEPENDENCY=FAIL'
  exit 1
fi
echo 'STATIC_GTM_CLICKIO_DEPENDENCY=PASS'

if grep -Ein '<script[^>]*>[^<]+' "$PLUGIN"/*.php "$PLUGIN"/includes/*.php 2>/dev/null; then
  echo 'STATIC_INLINE_EXECUTABLE_SCRIPT=FAIL'
  exit 1
fi
echo 'STATIC_INLINE_EXECUTABLE_SCRIPT=PASS'

echo 'STATIC_SECURITY_PASS=PASS'
