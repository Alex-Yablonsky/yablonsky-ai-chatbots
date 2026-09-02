# Yablonsky AI Chatbots 0.1.4 — Validation Report

Date: 2026-09-03

## Purpose

Validate the canonical `yablonsky-ai-chatbots` package after the WordPress.org compliance pass and the legacy-package activation collision remediation.

## Key regression

0.1.3 used the historical PHP bootstrap identity (`YSC_WP_*`, `Yablonsky_Secure_Chatbots_*`, `yablonsky_secure_chatbots_run`). The first canonical 0.1.4 packaging attempt reused those PHP identifiers, so activating it while 0.1.3 was still active could fatally collide before migration ran.

0.1.4 now has a collision-free canonical PHP bootstrap (`YAI_CHATBOTS_*`, `Yablonsky_AI_Chatbots_*`, `yablonsky_ai_chatbots_run`). The historical option remains only as an explicit migration/rollback bridge.

## Automated gates

- PHP lint: PASS
- JavaScript syntax: PASS
- Package identity: PASS
- Static security scan: PASS
- Legacy-loaded coexistence include: PASS
- Historical settings migration: PASS
- Historical site-level predecessor deactivation: PASS
- Rollback option mirror for upgraded installs: PASS
- Fresh-install legacy-option non-creation: PASS
- Optimizer-resilient fallback regression: PASS
- Fail-closed disabled/excluded/invalid paths: PASS
- `loader.js` byte parity with 0.1.3: PASS

## Runtime boundary

No change was made to the canonical SDK, single-SDK ownership, iframe adoption, exact `parent_origin`, consent bridge events, Core session authority or Core-owned conversation continuity.

## Live gates still required

- Activate 0.1.4 while 0.1.3 is still installed and active on the target WordPress site.
- Confirm 0.1.4 activates without fatal and 0.1.3 becomes inactive.
- Confirm settings are preserved.
- Confirm launcher/chat runtime.
- Confirm Yablonsky.io ↔ AI.Yablonsky.io continuity regression before deleting the historical package.
