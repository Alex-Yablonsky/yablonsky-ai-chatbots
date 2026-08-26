# Changelog

All notable changes to **Yablonsky AI Chatbots for WordPress** are documented here.

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
