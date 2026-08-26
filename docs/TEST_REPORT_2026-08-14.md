# Technical Repair Report — 2026-08-14

Candidate: `Yablonsky Secure Chatbots for WordPress 0.1.1`

## Confirmed root cause

The live 0.1.0 adapter enqueued its local CSS and loader from the raw plugin directory. On production, the security/obfuscation layer intentionally hides raw `/wp-content/plugins/...` paths. Real Firefox evidence showed the loader request returning HTTP 404 before the adapter could execute, so the canonical Secure Chatbots SDK was never requested.

## Repair design

0.1.1 keeps the existing local loader and CSS files byte-identical, but exposes them through a same-origin WordPress front-controller endpoint using `?ysc_adapter_asset=frontend|loader&ysc_adapter_version=0.1.1`. This removes the fatal dependency on public raw plugin paths without hardcoding any WP Ghost path or masked slug.

The endpoint:

- serves only the fixed `frontend` and `loader` assets;
- accepts GET/HEAD only;
- returns explicit CSS/JavaScript content types and `nosniff`;
- uses versioned same-origin URLs;
- leaves canonical SDK/bootstrap/session ownership unchanged.

## Automated validation

- exact live 0.1.0 tarball used as baseline: PASS;
- PHP syntax for all plugin PHP files: PASS;
- local `loader.js` byte parity with live 0.1.0: PASS;
- local `frontend.css` byte parity with live 0.1.0: PASS;
- generated candidate asset URLs contain no `/wp-content/plugins/`: PASS;
- generated candidate asset URLs contain no `/core/modules/` or WP Ghost slug: PASS;
- endpoint loader body equals packaged `public/js/loader.js`: PASS;
- endpoint CSS body equals packaged `public/css/frontend.css`: PASS;
- settings option/schema unchanged: PASS;
- canonical SDK validation logic unchanged: PASS;
- chatbot UUID validation unchanged: PASS;
- page eligibility/exclusions unchanged: PASS;
- consent bridge loader logic unchanged: PASS;
- duplicate SDK guard / iframe adoption loader logic unchanged: PASS.

## Not claimed by isolated validation

Production browser acceptance remains required after one controlled upgrade:

- adapter loader request HTTP 200 in Chrome/Firefox/Safari;
- canonical SDK request HTTP 200;
- exactly one SDK and one iframe;
- launcher visible/open-close;
- consent bridge behavior;
- real message round-trip;
- Mac Safari and real iPhone Safari acceptance.
