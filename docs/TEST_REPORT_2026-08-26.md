# Brand Sync Validation Report — 2026-08-26

Candidate: **Yablonsky AI Chatbots for WordPress 0.1.2**

## Baseline

The candidate was built from the frozen production WordPress adapter `0.1.1` installable ZIP.

Baseline ZIP SHA-256: `61bc941a0b6e81f9b67f8e98a2a6e5579e59f8d7a2439f7c735ac5b9aa113311`

## Scope

This is a branding/documentation compatibility release. It does not redesign the adapter, Core runtime, conversation/session handling, consent semantics or chatbot identity.

## Public brand changes

- Plugin name: **Yablonsky AI Chatbots for WordPress**.
- WordPress Settings label: **AI Chatbots**.
- Admin H1: **Yablonsky AI Chatbots**.
- Public object wording: **AI Chatbot / AI Chatbots**.
- Product link: `https://yablonsky.io/ai-chatbots/`.
- Author metadata: **Yablonsky.io**.
- README, WordPress readme, current security/install docs and GitHub metadata updated.

## Compatibility contracts preserved

- Installed plugin directory: `yablonsky-secure-chatbots`.
- Text domain: `yablonsky-secure-chatbots`.
- Option key: `yablonsky_secure_chatbots_settings`.
- Page slug: `yablonsky-secure-chatbots`.
- PHP classes/functions/filter names: unchanged.
- Canonical SDK path: `/e/yablonsky/secure-chatbots/sdk.js`.
- Same-origin adapter asset transport: unchanged.
- Page eligibility/exclusions: unchanged.
- Consent bridge: unchanged.
- Single-SDK guard and iframe adoption logic: unchanged.

## Runtime parity evidence

Unchanged from 0.1.1:

- frontend transport/eligibility class SHA-256: `ef74b6e069c141d2df6853d2f9e1c768ec45cbd928e3f133ebf99f26e51332c1`
- settings class SHA-256: `1acdf585ed86188a32c68ecf741571d1fef4924f754d2c97b26c7b8c664dd8d0`
- frontend CSS SHA-256: `872d4f23b44ac25d42f894fbd0c0c42e53e2e7e14b9e0f17ef2258eb5ab23bce`

Loader JavaScript SHA-256 in 0.1.2: `11313a4add7b35d9b271847d3b311789e231a696878be173d5cfc6d8e28e4863`.
The only loader source change is public fallback wording from the legacy product label to `AI Chatbot`; SDK validation, duplicate protection, iframe adoption and consent logic are unchanged.

## Automated validation

- PHP lint for all plugin/test PHP files: PASS.
- JavaScript syntax check: PASS.
- Activation fail-closed smoke: PASS.
- No production chatbot UUID stored by activation: PASS.
- Sanitization and page exclusion smoke: PASS.
- Same-origin adapter asset URL smoke: PASS.
- No raw `/wp-content/plugins/` bootstrap dependency: PASS.
- Existing settings option key preserved: PASS.
- Settings page slug preserved: PASS.
- Canonical SDK path preserved: PASS.
- Canonical SDK inserted exactly once in isolated loader smoke: PASS.
- Public config contains no privileged credential fields: PASS.
- Iframe adoption: PASS.
- Consent open/closed bridge: PASS.
- Dangerous execution static scan: PASS.
- Browser credential storage scan: PASS.
- GTM/Clickio runtime dependency scan: PASS.
- Current public surfaces contain no legacy public `Secure Chatbots` branding: PASS.
- Historical 0.1.0/0.1.1 test reports retain historical naming by design.
- Confidential underlying platform name, production UUID, host IPs and private host paths in public source: NOT FOUND.

## Browser acceptance still required

After upgrading production WordPress from 0.1.1 to 0.1.2, verify:

1. Existing adapter settings remain populated and the configured chatbot reference is unchanged.
2. **Settings -> AI Chatbots** shows the new public branding.
3. The launcher appears on an allowed page and does not appear on an excluded page.
4. Exactly one canonical SDK request and one eligible iframe are present.
5. Chrome, Firefox and Safari launcher/open-close behavior remains PASS.
6. Consent UI coexistence remains PASS.
7. A real message round-trip succeeds.
8. WP Ghost remains enabled and its frontend check remains PASS; no Nginx/security-routing mutation is part of this release.
