# Controlled Production Install Plan

1. Create the normal pre-install backup/snapshot and retain the currently working WordPress state.
2. In WordPress Admin, upload the installable ZIP and activate **Yablonsky AI Chatbots for WordPress**. Leave the adapter disabled if this is a fresh installation.
3. Open **Settings -> AI Chatbots** and configure only the canonical SDK URL and the approved public AI Chatbot UUID. Keep preview/session/provider data out of WordPress.
4. On the canonical Yablonsky AI Chatbots service, verify the selected AI Chatbot is active and the exact WordPress site origin is approved.
5. Enable the adapter, then verify the generated CSS/loader URLs use the same-origin `ysc_adapter_asset` endpoint, both return HTTP 200, and exactly one canonical SDK request plus one non-preview embed appear on an allowed page; verify an excluded page has neither.
6. Browser acceptance: desktop open/close, mobile near-full-screen + safe areas + keyboard/composer, consent overlay coexistence, console/network review, accessibility keyboard/focus and real message round-trip.
7. If any gate fails, disable the adapter first. If needed, deactivate/remove this plugin and restore the known-good WordPress snapshot/backup. No backend/chatbot data rollback is performed by this plugin.
