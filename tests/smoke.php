<?php

require __DIR__ . '/wp-stubs.php';
require dirname(__DIR__) . '/yablonsky-secure-chatbots.php';

function ysc_assert($condition, $message) {
    if (!$condition) {
        fwrite(STDERR, "FAIL: {$message}\n");
        exit(1);
    }
    echo "PASS: {$message}\n";
}

Yablonsky_Secure_Chatbots_Activator::activate();
$defaults = get_option(Yablonsky_Secure_Chatbots_Settings::OPTION_NAME);
ysc_assert(is_array($defaults) && $defaults['enabled'] === 0, 'activation is fail-closed');
ysc_assert($defaults['chatbot_id'] === '', 'activation stores no production chatbot ID');

$valid = Yablonsky_Secure_Chatbots_Sanitizer::sanitize(array(
    'enabled' => 1,
    'sdk_url' => 'https://ai.yablonsky.io/e/yablonsky/secure-chatbots/sdk.js',
    'chatbot_id' => '019fe824-e26d-72bd-8623-6d6f27cbac5e',
    'excluded_page_ids' => '34, 12, 12, nope, 0',
    'consent_bridge' => 1,
    'iframe_title' => '<b>Assistant</b>',
));
ysc_assert($valid['enabled'] === 1, 'valid public config can be enabled');
ysc_assert($valid['excluded_page_ids'] === array(12, 34), 'page exclusions are normalized');
ysc_assert($valid['iframe_title'] === 'Assistant', 'frame title is sanitized');

$invalid = Yablonsky_Secure_Chatbots_Sanitizer::sanitize(array(
    'enabled' => 1,
    'sdk_url' => 'http://evil.example/sdk.js?x=1',
    'chatbot_id' => 'not-a-uuid',
));
ysc_assert($invalid['enabled'] === 0, 'invalid chatbot config disables adapter');
ysc_assert($invalid['chatbot_id'] === '', 'invalid chatbot reference is not stored');
ysc_assert(count($GLOBALS['ysc_test_errors']) >= 2, 'invalid values produce settings errors');

$GLOBALS['ysc_test_options'][Yablonsky_Secure_Chatbots_Settings::OPTION_NAME] = $valid;
$frontend = new Yablonsky_Secure_Chatbots_Frontend();
$frontend->enqueue_assets();
ysc_assert(isset($GLOBALS['ysc_test_enqueued_scripts']['yablonsky-secure-chatbots-loader']), 'eligible page enqueues local loader');
ysc_assert(isset($GLOBALS['ysc_test_enqueued_styles']['yablonsky-secure-chatbots']), 'eligible page enqueues structural CSS');
ysc_assert(strpos($GLOBALS['ysc_test_enqueued_scripts']['yablonsky-secure-chatbots-loader'], 'ysc_adapter_asset=loader') !== false, 'loader uses same-origin adapter asset transport');
ysc_assert(strpos($GLOBALS['ysc_test_enqueued_styles']['yablonsky-secure-chatbots'], 'ysc_adapter_asset=frontend') !== false, 'CSS uses same-origin adapter asset transport');
ysc_assert(strpos($GLOBALS['ysc_test_enqueued_scripts']['yablonsky-secure-chatbots-loader'], '/wp-content/plugins/') === false, 'loader has no raw plugin path dependency');
ysc_assert(strpos($GLOBALS['ysc_test_enqueued_scripts']['yablonsky-secure-chatbots-loader'], 'ysc_adapter_version=0.1.2') !== false, 'loader transport is cache-busted with 0.1.2');

$GLOBALS['ysc_test_page_id'] = 12;
$GLOBALS['ysc_test_enqueued_scripts'] = array();
$GLOBALS['ysc_test_enqueued_styles'] = array();
$frontend_excluded = new Yablonsky_Secure_Chatbots_Frontend();
$frontend_excluded->enqueue_assets();
ysc_assert(empty($GLOBALS['ysc_test_enqueued_scripts']), 'excluded page does not enqueue loader');
ysc_assert(empty($GLOBALS['ysc_test_enqueued_styles']), 'excluded page does not enqueue CSS');

$GLOBALS['ysc_test_page_id'] = 0;
ob_start();
$frontend_config = new Yablonsky_Secure_Chatbots_Frontend();
$frontend_config->enqueue_assets();
$frontend_config->render_public_config();
$html = ob_get_clean();
ysc_assert(strpos($html, 'data-sdk-url="https://ai.yablonsky.io/e/yablonsky/secure-chatbots/sdk.js"') !== false, 'public config contains canonical SDK URL');
ysc_assert(strpos($html, 'data-chatbot-id="019fe824-e26d-72bd-8623-6d6f27cbac5e"') !== false, 'public config contains only public chatbot reference');
ysc_assert(stripos($html, 'preview_token') === false && stripos($html, 'session_token') === false && stripos($html, 'host_assertion') === false, 'public config contains no privileged credential fields');

ysc_assert(Yablonsky_Secure_Chatbots_Settings::OPTION_NAME === 'yablonsky_secure_chatbots_settings', 'settings option key preserved');
ysc_assert(Yablonsky_Secure_Chatbots_Settings::PAGE_SLUG === 'yablonsky-secure-chatbots', 'settings page slug preserved');
ysc_assert(Yablonsky_Secure_Chatbots_Sanitizer::SDK_PATH === '/e/yablonsky/secure-chatbots/sdk.js', 'canonical SDK path preserved');

echo "WORDPRESS_ADAPTER_SMOKE=PASS\n";
