# Yablonsky AI Chatbots for WordPress

**Yablonsky AI Chatbots for WordPress** is the native WordPress adapter for **Yablonsky AI Chatbots**.

It connects a WordPress website to the canonical Yablonsky AI Chatbots runtime while WordPress keeps only local integration settings, page eligibility and safe loading. AI conversations, intelligence, security and conversation continuity remain on Yablonsky AI.

Current release: **v0.1.2**

Developed and maintained by [Yablonsky.io](https://yablonsky.io/).

Product page: [Yablonsky AI Chatbots](https://yablonsky.io/ai-chatbots/)

Yablonsky AI workspace: [ai.yablonsky.io](https://ai.yablonsky.io/)

## Download

### Latest WordPress installer

Use this stable link on your website or documentation when you want visitors to download the latest published WordPress package:

https://github.com/Alex-Yablonsky/yablonsky-ai-chatbots-wordpress/releases/latest/download/yablonsky-ai-chatbots-wordpress.zip

Current version-specific installer:

https://github.com/Alex-Yablonsky/yablonsky-ai-chatbots-wordpress/releases/download/v0.1.2/yablonsky-ai-chatbots-wordpress-0.1.2.zip

Current installer SHA-256:

```text
91a24756cda6526e92816de6088a26cf5970231980a5a892606f6a8808a8449c
```

Latest release page:

https://github.com/Alex-Yablonsky/yablonsky-ai-chatbots-wordpress/releases/latest

> Use the installable release ZIP above for WordPress. GitHub's generic **Code → Download ZIP** archive is a source-code archive and should not be treated as the canonical WordPress installer.

## What you need before installation

Before installing the WordPress adapter, make sure you have:

- a Yablonsky AI account;
- an AI Chatbot created in Yablonsky AI;
- the public **Chatbot ID** for that chatbot;
- your website origin allowed by the chatbot security configuration when origin approval is required;
- administrator access to your WordPress website.

The WordPress plugin does **not** require a private API key.

The value entered into the plugin is the chatbot's public UUID, called the **Chatbot ID** in this guide. It is a public integration identifier, not a bearer token, API secret or session credential.

## 1. Create a Yablonsky AI account

Open:

https://ai.yablonsky.io/signup

Create your account with your first name, last name, email address and password, then complete the registration flow.

If you already have an account, sign in at:

https://ai.yablonsky.io/login

You can also start from the public Yablonsky AI website:

https://ai.yablonsky.io/

AI Chatbots availability depends on the capabilities included in your active Yablonsky AI plan.

## 2. Create an AI Chatbot

After signing in:

1. Open **AI Chatbots**:
   https://ai.yablonsky.io/app/secure-chatbots
2. Click **Add new**.
3. Enter the AI Chatbot name.
4. Click **Create**.
5. Open the chatbot.
6. Configure the sections you need, including:
   - **General**
   - **Agent**
   - **Train**
   - **Content**
   - **Launcher Studio**
   - **Security**
7. Save your changes.

Your AI Chatbot can then be prepared for the WordPress website where it will be used.

## 3. Allow your website

Open the chatbot's **Security** section and make sure the website where the chatbot will be embedded is allowed by the chatbot's current security/origin policy.

For this WordPress adapter, the browser origin is the actual WordPress website origin, for example:

```text
https://example.com
```

Do not weaken origin restrictions globally just to make the widget load. Use the intended website origin.

## 4. Get your Chatbot ID

Open the chatbot and select **Deploy**.

The **Install** section shows the canonical embed snippet.

Example:

```html
<script>
(function(w,d,i,b,n){if(!w[n]||w[n]("getState")!=="initialized"){var q=function(){(q.q=q.q||[]).push(arguments)};w[n]=new Proxy(q,{get:function(t,p){if(p==="q")return t.q;return function(){t.apply(null,[p].concat(Array.from(arguments)))}}})}const l=function(){const script=d.createElement("script");script.src=new URL("/e/yablonsky/secure-chatbots/sdk.js?v=0.1.66",b).toString();script.type="text/javascript";script.dataset.id=i;script.dataset.base=b;d.body.appendChild(script)};d.readyState==="complete"?l():w.addEventListener("load",l)})
(window,document,"XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX","https://ai.yablonsky.io","chat");
</script>
```

The value in this position:

```text
XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX
```

is your **Chatbot ID**.

You can also see the same UUID in the chatbot URL, for example:

```text
https://ai.yablonsky.io/app/secure-chatbots/XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX/deploy
```

### Important

Copy **only the UUID** into the WordPress plugin's **Chatbot ID** field.

Do not paste the complete `<script>` snippet into the WordPress settings.

If you were looking for a "plugin key", this Chatbot ID is the value the WordPress adapter needs. It is intentionally public and is not a private API key.

## 5. Install the WordPress plugin

1. Download the current installable ZIP from the **Download** section above.
2. In WordPress Admin, open **Plugins → Add New → Upload Plugin**.
3. Upload the installable ZIP.
4. Install and activate **Yablonsky AI Chatbots for WordPress**.
5. Open **Settings → AI Chatbots**.

When upgrading an existing installation, WordPress may show the existing technical plugin directory and offer to replace the installed version. This is expected because the compatibility-preserving plugin directory remains `yablonsky-secure-chatbots`.

## 6. Configure the WordPress adapter

In **Settings → AI Chatbots**:

1. Enable the adapter when you are ready to publish the chatbot.
2. Keep the canonical SDK URL configured as:
   `https://ai.yablonsky.io/e/yablonsky/secure-chatbots/sdk.js`
3. Paste your public **Chatbot ID**.
4. Configure page exclusions if the chatbot should not appear on specific WordPress pages.
5. Keep the consent UI bridge enabled when your site uses compatible consent UI events and you want the chatbot to avoid overlapping the consent interface.
6. Save settings.

The adapter allows the canonical SDK to determine the current WordPress `parent_origin` through the normal runtime contract. It does not create a second authentication or conversation layer.

## 7. Verify the installation

After saving settings, open the public website in a real browser.

Verify that:

- the AI Chatbot launcher appears on an eligible page;
- the launcher opens correctly;
- only one chatbot iframe is present;
- a message can be sent;
- an AI response is received;
- excluded pages do not load the chatbot;
- the website remains usable if the Yablonsky AI service is temporarily unavailable.

For production acceptance, test at least Chrome, Firefox and Safari. Mobile Safari should also be tested for customer-facing websites.

## Architecture boundary

```text
WordPress
  → native settings and page eligibility
  → same-origin adapter asset transport
  → canonical Yablonsky AI Chatbots SDK on AI.Yablonsky.io
  → Core-owned conversation, intelligence and security runtime
```

This package is **not** a chatbot backend and does not copy the canonical SDK.

The adapter places the chatbot. Yablonsky AI owns the chatbot runtime, conversation intelligence, session security and conversation continuity.

## Features

- Embed an AI Chatbot on eligible WordPress pages.
- Use a public chatbot reference without storing AI provider secrets in WordPress.
- Whole-site enablement with explicit page exclusions.
- Desktop and mobile-friendly canonical widget delivery.
- Same-origin local adapter asset transport without requiring the raw plugin directory to be publicly reachable.
- Single canonical SDK ownership and iframe adoption.
- Consent UI coexistence bridge for `consent:open`, `consent:closed` and `consent:changed`.
- Graceful failure if the canonical AI service is unavailable.
- Customer-defined chatbot identity and multilingual conversations provided by the Yablonsky AI Chatbots runtime.

## Configuration stored in WordPress

The adapter stores only public integration and presentation settings:

- enabled/disabled state;
- canonical SDK URL;
- public AI Chatbot UUID;
- page exclusions;
- consent UI bridge toggle;
- accessible AI Chatbot frame title.

It does not store:

- AI provider API keys;
- chatbot secrets;
- preview credentials;
- session credentials;
- cross-domain identity credentials;
- conversation databases.

## Page inclusion and exclusions

The current adapter uses whole-site placement on eligible public WordPress pages.

Add explicit WordPress page IDs to the exclusion list when the AI Chatbot must not load on a page.

Feeds, robots, trackbacks, 404 responses and WordPress admin surfaces fail closed and do not load the adapter.

## Same-origin adapter asset transport

The package-local loader and presentation stylesheet are exposed through a same-origin WordPress front-controller endpoint.

The runtime does not require public access to the raw `/wp-content/plugins/...` directory and does not hardcode security or obfuscation plugin routes.

This design preserves compatibility with WordPress installations that hide or rewrite plugin paths.

## Canonical SDK ownership

The canonical SDK is served from:

```text
https://ai.yablonsky.io/e/yablonsky/secure-chatbots/sdk.js
```

The WordPress adapter does not fork, copy or bundle the canonical SDK.

The version query shown in a Deploy snippet may change over time as the canonical service evolves. The WordPress plugin should continue to use the approved canonical SDK endpoint configured in its settings.

## Security boundary

The WordPress adapter does not store AI provider keys, preview credentials, session credentials or continuity credentials.

Protected conversation/session logic remains server-side on Yablonsky AI.

The canonical service remains responsible for:

- chatbot publication state;
- exact-origin allowlisting;
- bootstrap/session scope;
- object ownership;
- conversation continuity;
- abuse controls;
- revocation.

See [`docs/SECURITY.md`](docs/SECURITY.md) for details.

## Conversation continuity

Conversation continuity is owned by Yablonsky AI Core, not by the WordPress adapter.

A new website origin receives its own origin-scoped authorization/session. The adapter does not transfer session or bearer credentials between origins and does not use browser storage to create cross-domain identity.

The practical rule is simple:

```text
Adapter places the chatbot.
Yablonsky AI owns continuity.
```

## CSP

The host page must permit the canonical Yablonsky AI origin where required by its Content Security Policy.

At minimum, deployments using a restrictive CSP should review:

- `script-src` / `script-src-elem`;
- `connect-src`;
- `frame-src`;
- `style-src` if the site uses strict nonce/hash-only style rules.

For the current canonical service, `https://ai.yablonsky.io` must be permitted where the SDK/frame/runtime requires it.

Do not use wildcard CSP exceptions as a shortcut when an exact origin is sufficient.

## Consent boundary

The optional consent bridge is presentation-only.

It may react to:

```text
consent:open
consent:closed
consent:changed
```

The consent bridge does not provide authentication, session authorization or legal consent decisions, and it is not a Google Tag Manager dependency.

When a compatible consent UI emits `consent:open`, the adopted AI Chatbot frame can be hidden to avoid UI overlap; `consent:closed` restores it.

## Compatibility

- WordPress 6.0+
- PHP 7.4+
- Canonical Yablonsky AI Chatbots SDK at the configured approved host

## Upgrade compatibility

The public product name is **Yablonsky AI Chatbots for WordPress**, but several existing technical identifiers intentionally retain the previous compatibility namespace.

Do not rename these only for branding:

- installed plugin directory: `yablonsky-secure-chatbots`;
- WordPress text domain: `yablonsky-secure-chatbots`;
- existing WordPress option keys;
- existing PHP class names;
- stable internal routes and query keys;
- canonical SDK path: `/e/yablonsky/secure-chatbots/sdk.js`.

These identifiers are compatibility contracts and are not the public product name.

## Support and product information

Product information:

https://yablonsky.io/ai-chatbots/

Yablonsky.io:

https://yablonsky.io/

Yablonsky AI workspace:

https://ai.yablonsky.io/

## Author

Developed and maintained by [Yablonsky.io](https://yablonsky.io/).

For Yablonsky AI Chatbots, WordPress integration and related product information, visit [Yablonsky AI Chatbots](https://yablonsky.io/ai-chatbots/).

AI workspace and account access: [ai.yablonsky.io](https://ai.yablonsky.io/)

## Disclaimer

Yablonsky AI Chatbots for WordPress is an independent open-source project and is not affiliated with, endorsed by, or sponsored by the WordPress Foundation, Automattic, or related trademark owners.

WordPress and related product names are trademarks of their respective owners. They are used here only to describe compatibility.

Yablonsky AI Chatbots is a hosted Yablonsky.io product. Availability of AI models, tools, quotas and plan capabilities may change over time according to the active service configuration and subscription.

## License

GPL-2.0-or-later. See [`LICENSE`](LICENSE).

