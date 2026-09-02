# Optimizer-Resilient Loader Validation Report — 2026-09-02

Candidate: **Yablonsky AI Chatbots for WordPress 0.1.3**

## Scope

Patch release for one proven WordPress boundary failure: an eligible adapter can enqueue the local loader correctly while an optimizer/dequeue layer prevents the registered footer script from reaching final HTML.

The existing same-origin loader endpoint, canonical SDK path, public configuration model and JavaScript runtime are preserved.

## Change

- Normal `wp_enqueue_script()` remains the primary loader path.
- A second `wp_footer` callback is registered at `PHP_INT_MAX`.
- The callback returns unless the adapter already passed the normal eligibility/configuration checks.
- The callback returns when `wp_script_is('yablonsky-secure-chatbots-loader', 'done')` confirms that WordPress already printed the registered loader.
- Only when the adapter is eligible and the registered handle was not printed, one external `<script>` tag is emitted for the existing same-origin loader endpoint.
- The fallback is marked with `data-ysc-wp-fallback="1"` for diagnostics.

## Automated validation

- PHP lint for all PHP files: PASS.
- JavaScript syntax check: PASS.
- Fallback registered at latest footer priority: PASS.
- Normal eligible page still enqueues loader and CSS: PASS.
- Normal printed loader does not receive a duplicate fallback: PASS.
- Simulated optimizer/dequeue state receives exactly one fallback loader tag: PASS.
- Fallback points to the same-origin `ysc_adapter_asset=loader` endpoint: PASS.
- Fallback URL cache-busted with `0.1.3`: PASS.
- Disabled adapter fallback remains fail-closed: PASS.
- Excluded page fallback remains fail-closed: PASS.
- Invalid SDK/UUID fallback remains fail-closed: PASS.
- Canonical SDK path remains `/e/yablonsky/secure-chatbots/sdk.js`: PASS.
- Loader canonical SDK instance smoke: PASS.
- Loader public-config-only smoke: PASS.
- Loader iframe adoption smoke: PASS.
- Loader consent bridge smoke: PASS.
- Dangerous execution static scan: PASS.
- Browser credential storage scan: PASS.
- GTM/Clickio runtime dependency scan: PASS.
- Privileged WordPress settings field scan: PASS.
- Inline executable script scan: PASS (the fallback is an external script tag only).

## Architecture/security invariants

Unchanged:

- no AI provider keys in WordPress;
- no chatbot secrets, preview tokens or session credentials in WordPress;
- no Cookie Consent runtime dependency;
- exact-origin and canonical SDK validation are not weakened;
- loader idempotency guard remains `window.__YSC_WORDPRESS_ADAPTER__`;
- single canonical SDK ownership remains in `public/js/loader.js`;
- iframe adoption still requires exact backend origin, chatbot path and exact `parent_origin`;
- WP Ghost-compatible same-origin adapter asset transport remains unchanged;
- no DPU-specific URL, optimizer slug or masked security-plugin route is hardcoded.

## DPU evidence status

The task package establishes the pre-patch production proof that manual insertion of the existing 0.1.2 loader immediately boots the launcher on DPU. That proves the downstream chain is healthy and isolates the failing boundary to final loader printing.

The 0.1.3 candidate now covers that boundary in code and automated regression tests.

**Live DPU acceptance of 0.1.3 remains pending deployment of this candidate to dpu.lt.** Do not mark browser acceptance complete until a fresh/incognito session confirms automatic loading with no DevTools injection.

Required live checks after deployment:

1. config node exactly once;
2. expected public Chatbot ID;
3. loader automatic;
4. canonical SDK exactly once;
5. iframe exactly once;
6. launcher visible;
7. no console error.

## yablonsky.io regression status

The patch is generic and preserves the primary enqueue path. Automated tests prove that a normally printed loader does not receive a duplicate fallback.

**Live yablonsky.io browser regression acceptance remains pending deployment of the 0.1.3 candidate.**
