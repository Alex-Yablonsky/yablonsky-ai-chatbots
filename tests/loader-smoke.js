const fs = require('fs');
const vm = require('vm');

const source = fs.readFileSync(require('path').join(__dirname, '..', 'public', 'js', 'loader.js'), 'utf8');

class ClassList {
  constructor() { this.values = new Set(); }
  add(value) { this.values.add(value); }
  toggle(value, force) { if (force) this.values.add(value); else this.values.delete(value); }
  contains(value) { return this.values.has(value); }
}

const listeners = {};
const appended = [];
const configNode = {
  dataset: {
    sdkUrl: 'https://ai.yablonsky.io/e/yablonsky/secure-chatbots/sdk.js',
    base: 'https://ai.yablonsky.io',
    chatbotId: '00000000-0000-4000-8000-000000000001',
    consentBridge: '1',
    frameTitle: 'Yablonsky.io AI assistant'
  }
};

const frame = {
  nodeType: 1,
  tagName: 'IFRAME',
  src: 'https://ai.yablonsky.io/secure-chatbots/00000000-0000-4000-8000-000000000001/embed?parent_origin=https%3A%2F%2Fyablonsky.io',
  classList: new ClassList(),
  attrs: {},
  setAttribute(k, v) { this.attrs[k] = v; }
};

const document = {
  documentElement: {},
  body: { appendChild(node) { appended.push(node); if (node.tagName === 'SCRIPT' && node._listeners.load) node._listeners.load(); } },
  getElementById(id) { return id === 'ysc-wp-adapter-config' ? configNode : null; },
  querySelectorAll(selector) {
    if (selector === 'iframe') return [];
    if (selector === 'script[src]') return [];
    return [];
  },
  createElement(tag) {
    const node = {
      tagName: tag.toUpperCase(), dataset: {}, _listeners: {}, parentNode: null,
      addEventListener(type, callback) { this._listeners[type] = callback; }
    };
    return node;
  },
  addEventListener(type, callback) { listeners['document:' + type] = callback; }
};

class MutationObserver {
  constructor(callback) { this.callback = callback; }
  observe() {}
  disconnect() {}
}

const windowObj = {
  location: { href: 'https://yablonsky.io/', origin: 'https://yablonsky.io' },
  addEventListener(type, callback) { listeners['window:' + type] = callback; },
  setTimeout(callback) { /* Do not run long-stop timer in isolated smoke. */ return 1; }
};

const context = { window: windowObj, document, URL, MutationObserver, Object, String };
vm.createContext(context);
vm.runInContext(source, context);

if (appended.length !== 1 || appended[0].tagName !== 'SCRIPT') throw new Error('canonical SDK was not appended exactly once');
if (appended[0].src !== 'https://ai.yablonsky.io/e/yablonsky/secure-chatbots/sdk.js') throw new Error('wrong SDK URL');
if (appended[0].dataset.id !== configNode.dataset.chatbotId) throw new Error('public chatbot id not passed as data-id');
if (appended[0].dataset.base !== 'https://ai.yablonsky.io') throw new Error('backend base not passed as data-base');
if (!windowObj.chatConfig || windowObj.chatConfig.preview || windowObj.chatConfig.previewToken || windowObj.chatConfig.host_assertion) throw new Error('unsafe presentation config');

windowObj.__YSC_WORDPRESS_ADAPTER__.observer.callback([{ addedNodes: [frame] }]);
if (!frame.classList.contains('ysc-secure-chatbots-frame')) throw new Error('target iframe was not adopted');
if (frame.attrs.title !== 'Yablonsky.io AI assistant') throw new Error('accessible frame title not applied');

listeners['document:consent:open']();
if (!frame.classList.contains('ysc-is-consent-hidden')) throw new Error('consent open did not hide frame');
listeners['document:consent:closed']();
if (frame.classList.contains('ysc-is-consent-hidden')) throw new Error('consent close did not restore frame');

console.log('LOADER_SDK_INSTANCE=PASS');
console.log('LOADER_PUBLIC_CONFIG_ONLY=PASS');
console.log('LOADER_CONSENT_BRIDGE=PASS');
console.log('LOADER_FRAME_ADOPTION=PASS');
console.log('LOADER_SMOKE=PASS');
