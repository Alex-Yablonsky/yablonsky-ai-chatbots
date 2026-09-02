# Rollback — Yablonsky AI Chatbots for WordPress 0.1.3

If 0.1.3 causes an unexpected production regression, roll back to the previously accepted **0.1.2** package.

## WordPress Admin rollback

1. Keep the existing adapter settings unchanged.
2. Go to **Plugins → Add New → Upload Plugin**.
3. Upload the accepted `yablonsky-ai-chatbots-wordpress-0.1.2.zip` installer.
4. Choose **Replace current with uploaded** when WordPress detects the existing plugin directory.
5. Confirm the plugin remains activated.
6. Open **Settings → AI Chatbots** and verify the existing public Chatbot ID, SDK URL, exclusions, consent bridge and frame title remain populated.
7. Run browser acceptance again.

## What rollback changes

Rollback removes only the 0.1.3 late loader fallback and returns frontend behavior to 0.1.2.

It does not require changing:

- Secure Chatbots Core;
- the public Chatbot ID;
- canonical SDK URL;
- origin allowlists;
- Cookie Consent;
- WP Ghost/security-plugin routes;
- conversation/session data.

## Acceptance after rollback

Confirm the previously accepted 0.1.2 behavior on the target site. On DPU, the original optimizer/dequeue symptom may return because that is the defect 0.1.3 is designed to repair.
