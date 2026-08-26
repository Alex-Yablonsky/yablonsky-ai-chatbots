=== Yablonsky AI Chatbots for WordPress ===
Contributors: alex-yablonsky
Tags: ai-chatbot, chatbot, lead-generation, customer-support, wordpress-plugin
Requires at least: 6.0
Requires PHP: 7.4
Stable tag: 0.1.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Native WordPress adapter for Yablonsky AI Chatbots. Embed a business AI assistant while conversations, intelligence and security remain on Yablonsky AI.

== Description ==

This plugin owns WordPress placement and loading only. AI Chatbot conversations, intelligence, exact-origin enforcement, bootstrap/session security and AI execution remain on Yablonsky AI.

The adapter is disabled by default and stores only public configuration: canonical SDK endpoint, public AI Chatbot UUID, page exclusions, consent UI bridge toggle and accessible frame title.

It has no GTM runtime dependency and does not replace a consent platform.

Learn about Yablonsky AI Chatbots: https://yablonsky.io/ai-chatbots/

== Installation ==

1. Upload and activate the plugin.
2. Go to Settings -> AI Chatbots.
3. Configure the approved public AI Chatbot UUID and keep the adapter disabled until the server-side production prerequisites are verified.
4. Confirm the AI Chatbot is active and the WordPress site origin is approved by the canonical Yablonsky AI Chatbots service.
5. Enable the adapter and complete browser acceptance.

== Security ==

No AI provider credential, chatbot secret, preview credential or session credential belongs in WordPress. See docs/SECURITY.md for the boundary and CSP notes.

== Changelog ==

= 0.1.2 =
* Rebranded public plugin surfaces as Yablonsky AI Chatbots for WordPress.
* Updated WordPress Admin labels, plugin metadata and public documentation to the AI Chatbots product name.
* Added GitHub-ready license, changelog and public repository documentation.
* Preserved the installed plugin directory, text domain, option keys, class names, SDK path and runtime architecture.
* No Core, session, consent or chatbot identity behavior changed.

= 0.1.1 =
* Removed the fatal runtime dependency on publicly reachable raw /wp-content/plugins asset URLs.
* Serve the local adapter loader and presentation stylesheet through a same-origin WordPress front-controller asset endpoint.
* Preserve canonical SDK validation, single-SDK ownership, iframe adoption, page exclusions and the consent UI bridge.
* No security/obfuscation-plugin-specific path or masked slug is hardcoded.

= 0.1.0 =
* Initial WordPress adapter candidate.
* Safe Settings API configuration and public-only SDK loader.
* Whole-site mode with explicit page exclusions.
* Desktop/mobile structural UX and optional consent event bridge.
* Fail-closed SDK loading and duplicate-SDK guard.
