# Rollback — Yablonsky AI Chatbots 0.1.4

Do not delete the historical 0.1.3 package until 0.1.4 has passed production acceptance.

## Immediate rollback

1. Deactivate **Yablonsky AI Chatbots 0.1.4**.
2. Reactivate the still-installed **Yablonsky AI Chatbots for WordPress 0.1.3** package.
3. Verify **Settings -> AI Chatbots** still contains the public Chatbot ID, SDK URL, exclusions, consent bridge and frame title.
4. Run launcher/chat acceptance again.

Upgraded 0.1.4 installations mirror canonical settings back to the existing historical option specifically to preserve this rollback path. Fresh 0.1.4 installs do not create legacy state.

Rollback does not change Yablonsky AI Core sessions, conversation continuity, origin allowlists, chatbot identity or backend conversation data.
