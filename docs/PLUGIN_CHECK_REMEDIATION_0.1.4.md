# Plugin Check remediation — 0.1.4

Source report: `yablonsky-secure-chatbots-yablonsky-secure-chatbots-php-20260903-000101.json`

## Baseline findings

0.1.3 report: **3 ERROR + 8 WARNING**.

## Mapping

| 0.1.3 finding | 0.1.4 treatment |
|---|---|
| `NonceVerification.Recommended` on public asset query | Direct superglobal access removed; public read-only query is parsed through `filter_input()` and allowlisted. No fake nonce is added to a cacheable public asset URL. |
| `InputNotSanitized` for asset query | `sanitize_key()` after typed `filter_input()`. |
| `MissingUnslash` / `InputNotSanitized` for request method | Direct `$_SERVER` access removed; method parsed through `filter_input()` and `sanitize_text_field()`. |
| `file_system_operations_readfile` ERROR | Direct `readfile()` removed; bundled allowlisted assets are read through `WP_Filesystem_Direct`. |
| `NonEnqueuedScript` ERROR | Raw fallback `<script>` output removed. Late fallback re-enqueues the normal loader handle and invokes WordPress footer script printing. |
| missing `Tested up to` ERROR | Added `Tested up to: 7.1` for the 7.1 acceptance target. |
| short description >150 WARNING | Replaced with a 131-character short description. |
| restricted `WordPress` term in plugin display name | Directory display name changed to `Yablonsky AI Chatbots`. WordPress remains the integration channel descriptor. |

## Canonical identity and compatibility invariants

The canonical 0.1.4 package uses:

- installed directory / intended WordPress.org slug: `yablonsky-ai-chatbots`;
- main plugin file: `yablonsky-ai-chatbots.php`;
- text domain: `yablonsky-ai-chatbots`;
- settings option: `yablonsky_ai_chatbots_settings`;
- settings page slug: `yablonsky-ai-chatbots`;
- collision-free canonical PHP bootstrap identifiers.

The following runtime contracts are intentionally unchanged:

- historical settings option remains migration/rollback-only compatibility state;
- stable frontend script/style handles;
- canonical SDK path `/e/yablonsky/secure-chatbots/sdk.js`;
- `loader.js` bytes;
- Core exact-origin/session/continuity ownership.

## Manual WordPress.org readiness addition

The readme now explicitly discloses Yablonsky AI as the external SaaS runtime, what browser data is sent when enabled, and links Terms of Service and Privacy Policy. This addresses the Plugin Directory SaaS/serviceware review boundary beyond the automated checker.

## Gates before stable

1. Run official Plugin Check on packaged 0.1.4.
2. DPU live optimizer regression.
3. Yablonsky.io WordPress 7.1 regression.
4. Yablonsky.io ↔ ai.yablonsky.io continuity regression.
