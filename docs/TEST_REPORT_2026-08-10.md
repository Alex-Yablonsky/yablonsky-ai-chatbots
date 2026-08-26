# Technical Test Report — 2026-08-10

Candidate: `Yablonsky Secure Chatbots for WordPress 0.1.0`

## Static / lifecycle

- PHP syntax: PASS for every PHP file under PHP 8.4 CLI.
- JS syntax: PASS under Node 22.
- activation default: disabled, no production chatbot ID — PASS.
- settings sanitization: canonical HTTPS SDK validation, UUID validation, title sanitization, page-ID normalization — PASS.
- malformed config disables/fails safely — PASS.
- eligible page enqueue — PASS.
- explicit page exclusion — PASS.
- public rendered config contains no privileged credential fields — PASS.
- dangerous execution primitive scan — PASS.
- privileged settings field scan — PASS.
- local/session browser credential storage scan — PASS.
- GTM/Clickio runtime dependency scan — PASS.
- inline executable script scan — PASS.

## Isolated SDK/DOM smoke

- canonical SDK appended exactly once — PASS.
- `data-id` / `data-base` public handoff — PASS.
- target iframe exact-origin/path adoption — PASS.
- accessible frame title — PASS.
- `consent:open` hides and `consent:closed` restores — PASS.

## Headless Chromium browser smoke

Browser: Headless Chromium 144 in the isolated build container.

The container carries an enterprise `URLBlocklist: ["*"]`; it was temporarily removed only inside the test container for this smoke and restored immediately after each run (`POLICY_RESTORED=YES`). This did not touch production.

Final results after one mobile CSS defect was found and fixed:

- desktop open frame: 440 x 792 — PASS;
- desktop viewport containment — PASS;
- mobile open frame: near-full-screen with safe-area-aware insets — PASS;
- no horizontal overflow — PASS;
- consent collision bridge — PASS;
- close returns to launcher state — PASS;
- one SDK instance — PASS;
- backend/SDK unavailable: no iframe, host page remains functional — PASS.

## Not claimed yet

These require the controlled production gate and human browser acceptance on the real site:

- exact current WordPress core version on production after the August 6 WordPress 7.0.3 release;
- real Greenshift/theme collision check after installation;
- real canonical SDK/network/CSP behaviour on `https://yablonsky.io`;
- ACTIVE chatbot + exact `https://yablonsky.io` allowlist confirmation;
- non-preview real message round-trip;
- real mobile keyboard/composer behaviour inside the cross-origin chatbot UI;
- final focus/Escape behaviour inside the canonical cross-origin widget.
