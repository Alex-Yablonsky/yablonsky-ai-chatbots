# Yablonsky AI Chatbots for WordPress

A native WordPress adapter for **Yablonsky AI Chatbots**.

The plugin connects a WordPress site to the canonical Yablonsky AI Chatbots runtime. WordPress owns local settings, page eligibility and safe loading; AI conversations, intelligence, security and business logic remain on Yablonsky AI.

Learn about Yablonsky AI Chatbots: https://yablonsky.io/ai-chatbots/

## Architecture boundary

```text
WordPress
  -> native settings and page eligibility
  -> same-origin adapter asset transport
  -> canonical Yablonsky AI Chatbots SDK on AI.Yablonsky.io
  -> Core-owned conversation, intelligence and security runtime
```

This package is **not** a chatbot backend and does not copy the canonical SDK. It contains no AI provider key, chatbot secret, preview credential or session credential.

## Features

- Embed an AI Chatbot on eligible WordPress pages.
- Use a public chatbot reference without storing AI provider secrets in WordPress.
- Whole-site enablement with explicit page exclusions.
- Desktop and mobile-friendly canonical widget delivery.
- Same-origin local adapter asset transport, without requiring the raw plugin directory to be public.
- Single canonical SDK ownership and iframe adoption.
- Consent UI coexistence bridge for `consent:open`, `consent:closed` and `consent:changed`.
- Graceful failure if the canonical AI service is unavailable.
- Customer-defined chatbot identity and multilingual conversations provided by the Yablonsky AI Chatbots runtime.

## Installation

1. Upload the installable ZIP in **Plugins -> Add New -> Upload Plugin**.
2. Activate **Yablonsky AI Chatbots for WordPress**.
3. Open **Settings -> AI Chatbots**.
4. Configure the canonical SDK URL and approved public AI Chatbot UUID.
5. Keep the adapter disabled until the selected chatbot is active and the site origin is approved by the canonical service.
6. Enable the adapter and complete browser acceptance.

## Configuration

The adapter stores only public integration settings:

- enabled/disabled state;
- canonical SDK URL;
- public AI Chatbot UUID;
- page exclusions;
- consent UI bridge toggle;
- accessible AI Chatbot frame title.

The installed plugin directory, option keys, class names, text domain and canonical SDK path intentionally retain their existing technical identifiers for upgrade compatibility.

## Page inclusion and exclusions

The current adapter uses whole-site placement on eligible public WordPress pages. Add explicit WordPress page IDs to the exclusion list when the AI Chatbot must not load on a page.

Feeds, robots, trackbacks, 404 responses and WordPress admin surfaces fail closed and do not load the adapter.

## Same-origin adapter asset transport

The package-local loader and presentation stylesheet are exposed through a same-origin WordPress front-controller endpoint. The runtime does not require public access to the raw `/wp-content/plugins/...` directory and does not hardcode security/obfuscation plugin routes.

## Security boundary

The WordPress adapter does not store AI provider keys, preview credentials or session credentials. Protected conversation/session logic remains server-side on Yablonsky AI.

The canonical service remains responsible for publication state, exact-origin allowlisting, bootstrap/session scope, object ownership, abuse controls and revocation.

See `docs/SECURITY.md` for details.

## CSP

The host page must permit the canonical SDK origin in its script policy and `https://ai.yablonsky.io` in `frame-src`. The canonical SDK may inject structural styles, so sites with strict nonce/hash-only style policies require an explicit compatibility review before production.

## Consent boundary

The optional consent bridge is presentation-only. It does not provide authentication, session authorization or legal consent decisions, and it is not a GTM dependency. When a consent UI emits `consent:open`, the adopted AI Chatbot frame is hidden; `consent:closed` restores it.

## Compatibility

- WordPress 6.0+
- PHP 7.4+
- Canonical Yablonsky AI Chatbots SDK at the configured approved host

## License

GPL-2.0-or-later. See `LICENSE`.
