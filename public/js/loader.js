(function (window, document) {
    'use strict';

    if (window.__YSC_WORDPRESS_ADAPTER__) {
        return;
    }

    var configNode = document.getElementById('ysc-wp-adapter-config');
    if (!configNode) {
        return;
    }

    var sdkUrl = String(configNode.dataset.sdkUrl || '');
    var base = String(configNode.dataset.base || '');
    var chatbotId = String(configNode.dataset.chatbotId || '').toLowerCase();
    var consentBridge = configNode.dataset.consentBridge === '1';
    var frameTitle = String(configNode.dataset.frameTitle || 'AI Chatbot').slice(0, 120);
    var uuidPattern = /^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/;
    var canonicalPath = '/e/yablonsky/secure-chatbots/sdk.js';
    var sdk;
    var backend;

    try {
        sdk = new URL(sdkUrl, window.location.href);
        backend = new URL(base, window.location.href);
    } catch (error) {
        return;
    }

    if (sdk.protocol !== 'https:'
        || backend.protocol !== 'https:'
        || sdk.origin !== backend.origin
        || sdk.pathname !== canonicalPath
        || sdk.search !== ''
        || sdk.hash !== ''
        || !uuidPattern.test(chatbotId)) {
        return;
    }

    var state = {
        frame: null,
        consentOpen: false,
        observer: null,
        stopped: false
    };
    window.__YSC_WORDPRESS_ADAPTER__ = state;

    function isTargetFrame(frame) {
        if (!frame || frame.tagName !== 'IFRAME' || !frame.src) {
            return false;
        }

        try {
            var url = new URL(frame.src, window.location.href);
            return url.origin === backend.origin
                && url.pathname === '/secure-chatbots/' + encodeURIComponent(chatbotId) + '/embed'
                && url.searchParams.get('parent_origin') === window.location.origin;
        } catch (error) {
            return false;
        }
    }

    function applyConsentVisibility() {
        if (!state.frame) {
            return;
        }
        state.frame.classList.toggle('ysc-is-consent-hidden', state.consentOpen);
        state.frame.setAttribute('aria-hidden', state.consentOpen ? 'true' : 'false');
    }

    function adoptFrame(frame) {
        if (!isTargetFrame(frame)) {
            return false;
        }

        state.frame = frame;
        frame.classList.add('ysc-secure-chatbots-frame');
        if (frameTitle) {
            frame.setAttribute('title', frameTitle);
        }
        applyConsentVisibility();
        return true;
    }

    function findFrame() {
        var frames = document.querySelectorAll('iframe');
        for (var i = 0; i < frames.length; i += 1) {
            if (adoptFrame(frames[i])) {
                return true;
            }
        }
        return false;
    }

    function onConsentOpen() {
        state.consentOpen = true;
        applyConsentVisibility();
    }

    function onConsentClosed() {
        state.consentOpen = false;
        applyConsentVisibility();
    }

    function onConsentChanged() {
        findFrame();
    }

    function listenConsent(target) {
        if (!consentBridge || !target || !target.addEventListener) {
            return;
        }
        target.addEventListener('consent:open', onConsentOpen, false);
        target.addEventListener('consent:closed', onConsentClosed, false);
        target.addEventListener('consent:changed', onConsentChanged, false);
    }

    listenConsent(document);
    if (window !== document) {
        listenConsent(window);
    }

    if (findFrame()) {
        return;
    }

    state.observer = new MutationObserver(function (mutations) {
        if (state.stopped || state.frame) {
            return;
        }
        for (var i = 0; i < mutations.length; i += 1) {
            for (var j = 0; j < mutations[i].addedNodes.length; j += 1) {
                var node = mutations[i].addedNodes[j];
                if (node.nodeType === 1) {
                    if (adoptFrame(node)) {
                        return;
                    }
                    if (node.querySelectorAll) {
                        var nested = node.querySelectorAll('iframe');
                        for (var k = 0; k < nested.length; k += 1) {
                            if (adoptFrame(nested[k])) {
                                return;
                            }
                        }
                    }
                }
            }
        }
    });
    state.observer.observe(document.documentElement, { childList: true, subtree: true });

    var existingScripts = document.querySelectorAll('script[src]');
    for (var s = 0; s < existingScripts.length; s += 1) {
        try {
            if (new URL(existingScripts[s].src, window.location.href).href === sdk.href) {
                // Another integration already owns this SDK instance. Fail closed rather than duplicate it.
                window.setTimeout(findFrame, 0);
                window.setTimeout(function () {
                    if (!state.frame && state.observer) {
                        state.observer.disconnect();
                        state.stopped = true;
                    }
                }, 15000);
                return;
            }
        } catch (error) {
            // Ignore unrelated malformed script URLs.
        }
    }

    // Exact public presentation config only. Never pass preview/session/provider credentials from WordPress.
    window.chatConfig = Object.freeze({
        alignment: 'end',
        title: frameTitle || 'AI Chatbot'
    });

    var script = document.createElement('script');
    script.src = sdk.href;
    script.type = 'text/javascript';
    script.async = true;
    script.dataset.id = chatbotId;
    script.dataset.base = backend.origin;
    script.dataset.yscCanonicalSdk = '1';

    script.addEventListener('load', function () {
        findFrame();
    }, { once: true });

    script.addEventListener('error', function () {
        state.stopped = true;
        if (state.observer) {
            state.observer.disconnect();
        }
        if (script.parentNode) {
            script.parentNode.removeChild(script);
        }
    }, { once: true });

    document.body.appendChild(script);

    window.setTimeout(function () {
        if (!state.frame && state.observer) {
            state.observer.disconnect();
            state.stopped = true;
        }
    }, 15000);
}(window, document));
