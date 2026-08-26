# Security Model

## WordPress stores only public adapter data

Allowed settings:
- enabled/disabled state;
- canonical SDK URL;
- public AI Chatbot UUID;
- page exclusions;
- accessible frame title;
- consent UI bridge toggle.

There are intentionally no settings for AI provider credentials, chatbot secret keys, preview grants, session credentials, workspace secrets, internal files/units or private admin URLs.

## Canonical service remains authority

The adapter does not reproduce Yablonsky AI Chatbots origin/session logic. The canonical service remains responsible for publication status, exact-origin allowlisting, signed embed bootstrap, ephemeral session scope, object ownership, streaming projection, abuse controls and revocation.

## Local adapter asset transport

The package-local loader and presentation stylesheet are served through a same-origin WordPress front-controller endpoint. The adapter does not require public access to its raw `/wp-content/plugins/...` directory and does not know or hardcode any security/obfuscation plugin path. Only the two fixed package assets can be requested through this endpoint.

## Single SDK ownership

The adapter validates the configured canonical SDK URL and loads it at most once. If another integration already owns the same canonical SDK instance, the WordPress adapter does not inject a duplicate and instead attempts to adopt the eligible iframe.

## Failure mode

If canonical SDK loading fails, the script element is removed, no retry loop runs, and the WordPress host page continues normally.

## Admin mutations

The plugin uses the WordPress Settings API under `manage_options`. WordPress `options.php` supplies the settings nonce/capability gate; the sanitize callback validates every stored value, and rendered output is escaped.

## Consent boundary

The optional consent bridge is presentation-only. It does not provide authentication, session authorization or legal consent decisions. It listens only for `consent:open`, `consent:closed` and `consent:changed` to avoid UI overlap.

## CSP notes

Expected host allowances:
- script policy: canonical `https://ai.yablonsky.io` SDK origin;
- `frame-src`: `https://ai.yablonsky.io`;
- canonical SDK structural style injection must be compatible with the host style policy.

No GTM runtime dependency is required.
