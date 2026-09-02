=== Yablonsky AI Chatbots ===
Contributors: alex-yablonsky
Tags: ai-chatbot, chatbot, lead-generation, customer-support, wordpress-plugin
Requires at least: 6.0
Requires PHP: 7.4
Tested up to: 7.1
Stable tag: 0.1.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Connect a Yablonsky AI Chatbot with a lightweight site adapter while conversations, intelligence and security stay on Yablonsky AI.

== Description ==

This plugin owns WordPress placement and loading only. AI Chatbot conversations, intelligence, exact-origin enforcement, bootstrap/session security and AI execution remain on Yablonsky AI.

The adapter is disabled by default and stores only public configuration: canonical SDK endpoint, public AI Chatbot UUID, page exclusions, consent UI bridge toggle and accessible frame title.

It has no GTM runtime dependency and does not replace a consent platform.

Learn about Yablonsky AI Chatbots: https://yablonsky.io/ai-chatbots/

== Installation ==

1. Upload and activate the plugin. If upgrading from 0.1.3 or earlier, leave the historical package installed during activation; 0.1.4 migrates its settings and deactivates the predecessor automatically.
2. Go to Settings -> AI Chatbots.
3. Configure the approved public AI Chatbot UUID and keep the adapter disabled until the server-side production prerequisites are verified.
4. Confirm the AI Chatbot is active and the WordPress site origin is approved by the canonical Yablonsky AI Chatbots service.
5. Enable the adapter and complete browser acceptance.


== External Service ==

This plugin connects to Yablonsky AI at https://ai.yablonsky.io/ to load the canonical chatbot runtime and process chatbot conversations.

When the adapter is enabled, the visitor's browser sends the configured public Chatbot ID, the current website origin, chatbot messages and technical session metadata required for security and conversation continuity to Yablonsky AI. The WordPress plugin does not store AI provider API keys, private chatbot secrets, bearer tokens or session credentials.

Yablonsky AI Terms of Service: https://ai.yablonsky.io/policies/terms
Privacy Policy: https://yablonsky.io/privacy-policy

== Security ==

No AI provider credential, chatbot secret, preview credential or session credential belongs in WordPress. See docs/SECURITY.md for the boundary and CSP notes.

== Changelog ==

= 0.1.4 =
* Canonicalize the WordPress.org package identity to `yablonsky-ai-chatbots`: plugin directory, main file and text domain now match the intended directory slug.
* Use a collision-free canonical PHP bootstrap so 0.1.4 can activate while the historical package is still loaded.
* Migrate the historical settings option into the canonical option, silently deactivate the historical site-level predecessor, and keep its existing option mirrored for rollback until acceptance.
* WordPress.org / Plugin Check compliance pass without changing the Core/session/continuity contract.
* Public plugin display name is Yablonsky AI Chatbots; WordPress remains the integration channel, not part of the directory display name.
* Add Tested up to metadata and a directory-safe short description.
* Sanitize public asset-route input without direct superglobal handling.
* Serve allowlisted bundled adapter assets through the WordPress Filesystem API instead of readfile().
* Keep the normal enqueue path primary and make the optimizer fallback re-enqueue/print through WordPress script APIs instead of emitting a raw script tag.
* Document the external Yablonsky AI service, data flow, Terms of Service and Privacy Policy.

= 0.1.3 =
* Keep the normal WordPress enqueue path as primary.
* Add a late optimizer-resilient footer fallback that prints the same-origin adapter loader only when the registered loader handle was not already printed.
* Preserve fail-closed eligibility, canonical SDK validation, page exclusions, consent independence, single-SDK ownership and iframe adoption.
* No DPU-specific route, optimizer-specific slug or security weakening is introduced.

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
