# Changelog

All notable changes to the **Yablonsky AI Chatbots WordPress adapter** are documented here.

## 0.1.4 — 2026-09-03

### Canonical package identity

- Canonical WordPress.org slug/folder: `yablonsky-ai-chatbots`.
- Main plugin file: `yablonsky-ai-chatbots.php`.
- Text domain: `yablonsky-ai-chatbots`.
- Public installer: `yablonsky-ai-chatbots-0.1.4.zip`.
- Canonical PHP bootstrap constants, function and internal class names are collision-free from the historical `yablonsky-secure-chatbots` package.
- Existing installations migrate `yablonsky_secure_chatbots_settings` to `yablonsky_ai_chatbots_settings`.
- Upgraded installations keep the existing historical option mirrored for rollback; fresh installs do not create legacy state.
- Activating the canonical package silently deactivates the historical site-level predecessor after settings migration, while leaving its files installed until acceptance.

### WordPress.org compliance

- Public plugin display name is **Yablonsky AI Chatbots**; WordPress remains the integration channel, not part of the canonical product name.
- Added `Tested up to: 7.1` metadata and a <=150-character WordPress.org short description.
- Reworked public asset request parsing to avoid direct unsanitized superglobal handling.
- Replaced direct `readfile()` asset output with the WordPress Filesystem API.
- Reworked the optimizer-resilient loader fallback to re-enqueue and print through WordPress script APIs instead of emitting a raw `<script>` tag.
- Added explicit Yablonsky AI external-service, data-flow, Terms and Privacy disclosure.

### Runtime compatibility

- `loader.js` is unchanged from 0.1.3.
- Stable frontend script/style handles remain compatibility-preserving internals.
- Canonical SDK path remains `/e/yablonsky/secure-chatbots/sdk.js`.
- Core-owned exact-origin sessions and conversation continuity are unchanged.
- Single-SDK ownership, iframe adoption, fail-closed eligibility and consent UI coexistence are unchanged.

## 0.1.3 — 2026-09-02

### Fixed
- Kept the normal `wp_enqueue_script()` loader path as the primary WordPress integration path.
- Added a late `wp_footer` fallback that emits the same-origin adapter loader only when the registered loader handle was not already printed.
- Makes the adapter resilient to optimizer/dequeue layers that remove the footer loader tag after the adapter has already passed its eligibility and configuration checks.

### Safety and compatibility
- Fallback is generic and not DPU-specific.
- Disabled, excluded and invalid configurations remain fail-closed.
- Canonical SDK URL/path validation is unchanged.
- No API keys, preview credentials, session credentials or origin assertions are added to WordPress HTML.
- No Cookie Consent dependency is introduced.
- Existing loader idempotency, single-SDK ownership, iframe adoption and WP Ghost-compatible same-origin asset transport are preserved.

## 0.1.2 — 2026-08-26

### Changed
- Rebranded customer-facing and administrator-facing surfaces to **Yablonsky AI Chatbots for WordPress** / **AI Chatbots**.
- Author metadata uses **Yablonsky.io** as the founding trust / publisher identity.
- Updated the plugin header, WordPress Admin labels, README, WordPress readme and current operational/security documentation.
- Added GitHub-ready public metadata documentation, GPL license file and repository ignore rules.
- Updated the loader fallback frame title from the legacy product wording to `AI Chatbot`.

### Compatibility
- Installed plugin directory remains `yablonsky-secure-chatbots`.
- Text domain remains `yablonsky-secure-chatbots`.
- WordPress option keys, PHP classes, filter names and query keys remain unchanged.
- Canonical SDK path remains `/e/yablonsky/secure-chatbots/sdk.js`.
- Same-origin adapter asset transport from 0.1.1 remains unchanged.
- Consent bridge, page exclusions, single-SDK guard and iframe adoption remain unchanged.

## 0.1.1 — 2026-08-14

- Removed the fatal dependency on public raw `/wp-content/plugins/...` adapter asset URLs.
- Added same-origin WordPress front-controller delivery for the local loader and stylesheet.
- Preserved canonical SDK validation, duplicate protection, iframe adoption, page exclusions and consent UI coexistence.

## 0.1.0 — 2026-08-10

- Initial WordPress adapter candidate.
- Added safe Settings API configuration, public-only SDK loading, page exclusions, desktop/mobile presentation and optional consent events.
