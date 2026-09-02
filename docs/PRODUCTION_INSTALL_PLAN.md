# Controlled Production Install Plan — 0.1.4

1. Keep the currently working historical 0.1.3 package installed and active; do not delete it before migration.
2. Upload `yablonsky-ai-chatbots-0.1.4.zip` and activate **Yablonsky AI Chatbots**. The canonical bootstrap is collision-free, migrates the historical settings option, and silently deactivates the site-level historical predecessor during activation.
3. Confirm the old **Yablonsky AI Chatbots for WordPress 0.1.3** row is now inactive and **Yablonsky AI Chatbots 0.1.4** is active. Keep the old package installed as rollback insurance.
4. Open **Settings -> AI Chatbots** and verify the existing enabled state, canonical SDK URL, public Chatbot ID, exclusions, consent bridge and frame title survived migration.
5. On the public site verify the same-origin `ysc_adapter_asset` transport, exactly one canonical SDK request, exactly one non-preview embed/iframe, launcher open/close behavior and a real message round-trip.
6. Run the Yablonsky.io ↔ ai.yablonsky.io continuity regression: conversation A on the site -> AI workspace -> message B -> return -> A+B continuity, plus open/closed state behavior.
7. Run official Plugin Check against the final packaged 0.1.4.
8. Only after all gates pass, delete the inactive historical 0.1.3 package and proceed to GitHub release publication.

If any runtime gate fails, deactivate 0.1.4 and reactivate the still-installed historical 0.1.3 package. The historical settings option is kept mirrored on upgraded installations until the old package is deliberately removed.
