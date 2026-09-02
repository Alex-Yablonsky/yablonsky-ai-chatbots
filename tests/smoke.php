<?php

require __DIR__ . '/wp-stubs.php';
require dirname(__DIR__) . '/yablonsky-ai-chatbots.php';

function ysc_assert($condition, $message) {
    if (!$condition) {
        fwrite(STDERR, "FAIL: {$message}\n");
        exit(1);
    }
    echo "PASS: {$message}\n";
}

$footer_actions = isset($GLOBALS['ysc_test_actions']['wp_footer']) ? $GLOBALS['ysc_test_actions']['wp_footer'] : array();
$fallback_registered_late = false;
foreach ($footer_actions as $action) {
    if (is_array($action['callback'])
        && isset($action['callback'][1])
        && $action['callback'][1] === 'render_loader_fallback'
        && $action['priority'] === PHP_INT_MAX) {
        $fallback_registered_late = true;
        break;
    }
}
ysc_assert($fallback_registered_late, 'optimizer-resilient fallback is registered at the latest footer priority');

$settings_mirror_actions = isset($GLOBALS['ysc_test_actions']['update_option_yablonsky_ai_chatbots_settings'])
    ? $GLOBALS['ysc_test_actions']['update_option_yablonsky_ai_chatbots_settings']
    : array();
ysc_assert(!empty($settings_mirror_actions), 'canonical settings updates register a legacy rollback mirror');

$GLOBALS['ysc_test_options'][Yablonsky_AI_Chatbots_Settings::LEGACY_OPTION_NAME] = array(
    'enabled' => 1,
    'sdk_url' => 'https://ai.yablonsky.io/e/yablonsky/secure-chatbots/sdk.js',
    'chatbot_id' => '00000000-0000-4000-8000-000000000001',
    'excluded_page_ids' => array(44),
    'consent_bridge' => 1,
    'iframe_title' => 'Legacy assistant',
);

Yablonsky_AI_Chatbots_Activator::activate();
$migrated = get_option(Yablonsky_AI_Chatbots_Settings::OPTION_NAME);
ysc_assert(is_array($migrated) && $migrated['enabled'] === 1, 'activation migrates the legacy settings option');
ysc_assert($migrated['chatbot_id'] === '00000000-0000-4000-8000-000000000001', 'legacy public Chatbot ID survives package-slug migration');

$mirrored = $migrated;
$mirrored['iframe_title'] = 'Canonical assistant';
update_option(Yablonsky_AI_Chatbots_Settings::OPTION_NAME, $mirrored, false);
$legacy_mirror = get_option(Yablonsky_AI_Chatbots_Settings::LEGACY_OPTION_NAME);
ysc_assert(is_array($legacy_mirror) && $legacy_mirror['iframe_title'] === 'Canonical assistant', 'canonical settings mirror to the legacy option for rollback');

$valid = Yablonsky_AI_Chatbots_Sanitizer::sanitize(array(
    'enabled' => 1,
    'sdk_url' => 'https://ai.yablonsky.io/e/yablonsky/secure-chatbots/sdk.js',
    'chatbot_id' => '00000000-0000-4000-8000-000000000001',
    'excluded_page_ids' => '34, 12, 12, nope, 0',
    'consent_bridge' => 1,
    'iframe_title' => '<b>Assistant</b>',
));
ysc_assert($valid['enabled'] === 1, 'valid public config can be enabled');
ysc_assert($valid['excluded_page_ids'] === array(12, 34), 'page exclusions are normalized');
ysc_assert($valid['iframe_title'] === 'Assistant', 'frame title is sanitized');

$invalid = Yablonsky_AI_Chatbots_Sanitizer::sanitize(array(
    'enabled' => 1,
    'sdk_url' => 'http://evil.example/sdk.js?x=1',
    'chatbot_id' => 'not-a-uuid',
));
ysc_assert($invalid['enabled'] === 0, 'invalid chatbot config disables adapter');
ysc_assert($invalid['chatbot_id'] === '', 'invalid chatbot reference is not stored');
ysc_assert(count($GLOBALS['ysc_test_errors']) >= 2, 'invalid values produce settings errors');

$GLOBALS['ysc_test_options'][Yablonsky_AI_Chatbots_Settings::OPTION_NAME] = $valid;
$frontend = new Yablonsky_AI_Chatbots_Frontend();
$frontend->enqueue_assets();
ysc_assert(isset($GLOBALS['ysc_test_enqueued_scripts']['yablonsky-secure-chatbots-loader']), 'eligible page enqueues local loader');
ysc_assert(isset($GLOBALS['ysc_test_enqueued_styles']['yablonsky-secure-chatbots']), 'eligible page enqueues structural CSS');
ysc_assert(strpos($GLOBALS['ysc_test_enqueued_scripts']['yablonsky-secure-chatbots-loader'], 'ysc_adapter_asset=loader') !== false, 'loader uses same-origin adapter asset transport');
ysc_assert(strpos($GLOBALS['ysc_test_enqueued_styles']['yablonsky-secure-chatbots'], 'ysc_adapter_asset=frontend') !== false, 'CSS uses same-origin adapter asset transport');
ysc_assert(strpos($GLOBALS['ysc_test_enqueued_scripts']['yablonsky-secure-chatbots-loader'], '/wp-content/plugins/') === false, 'loader has no raw plugin path dependency');
ysc_assert(strpos($GLOBALS['ysc_test_enqueued_scripts']['yablonsky-secure-chatbots-loader'], 'ysc_adapter_version=0.1.4') !== false, 'loader transport is cache-busted with 0.1.4');

$GLOBALS['ysc_test_page_id'] = 12;
$GLOBALS['ysc_test_enqueued_scripts'] = array();
$GLOBALS['ysc_test_enqueued_styles'] = array();
$frontend_excluded = new Yablonsky_AI_Chatbots_Frontend();
$frontend_excluded->enqueue_assets();
ysc_assert(empty($GLOBALS['ysc_test_enqueued_scripts']), 'excluded page does not enqueue loader');
ysc_assert(empty($GLOBALS['ysc_test_enqueued_styles']), 'excluded page does not enqueue CSS');

$GLOBALS['ysc_test_page_id'] = 0;
ob_start();
$frontend_config = new Yablonsky_AI_Chatbots_Frontend();
$frontend_config->enqueue_assets();
$frontend_config->render_public_config();
$html = ob_get_clean();
ysc_assert(strpos($html, 'data-sdk-url="https://ai.yablonsky.io/e/yablonsky/secure-chatbots/sdk.js"') !== false, 'public config contains canonical SDK URL');
ysc_assert(strpos($html, 'data-chatbot-id="00000000-0000-4000-8000-000000000001"') !== false, 'public config contains only public chatbot reference');
ysc_assert(stripos($html, 'preview_token') === false && stripos($html, 'session_token') === false && stripos($html, 'host_assertion') === false, 'public config contains no privileged credential fields');

// Normal WordPress path: once the registered loader handle has been printed, the late fallback is silent.
$GLOBALS['ysc_test_script_done']['yablonsky-secure-chatbots-loader'] = true;
ob_start();
$frontend_config->render_loader_fallback();
$normal_fallback = ob_get_clean();
ysc_assert($normal_fallback === '', 'normal printed loader is not duplicated by fallback');

// Optimizer/dequeue simulation: the adapter remains eligible, but the loader handle never reaches done.
$GLOBALS['ysc_test_script_done']['yablonsky-secure-chatbots-loader'] = false;
unset($GLOBALS['ysc_test_enqueued_scripts']['yablonsky-secure-chatbots-loader']);
ob_start();
$frontend_config->render_loader_fallback();
$optimizer_fallback = ob_get_clean();
ysc_assert(strpos($optimizer_fallback, 'ysc_adapter_asset=loader') !== false, 'optimizer-resilient fallback prints the re-enqueued same-origin loader');
ysc_assert(strpos($optimizer_fallback, 'ysc_adapter_version=0.1.4') !== false, 'optimizer-resilient fallback is cache-busted with 0.1.4');
ysc_assert(substr_count($optimizer_fallback, '<script') === 1, 'optimizer-resilient fallback prints one external loader through WordPress script APIs');
ysc_assert(!empty($GLOBALS['ysc_test_script_done']['yablonsky-secure-chatbots-loader']), 'optimizer-resilient fallback marks the loader handle done after WordPress printing');

// Excluded pages cannot be revived by the fallback.
$GLOBALS['ysc_test_page_id'] = 12;
$frontend_excluded_fallback = new Yablonsky_AI_Chatbots_Frontend();
$frontend_excluded_fallback->enqueue_assets();
ob_start();
$frontend_excluded_fallback->render_loader_fallback();
$excluded_fallback = ob_get_clean();
ysc_assert($excluded_fallback === '', 'excluded page fallback remains fail-closed');

// Disabled/invalid configuration cannot be revived by the fallback.
$GLOBALS['ysc_test_page_id'] = 0;
$GLOBALS['ysc_test_options'][Yablonsky_AI_Chatbots_Settings::OPTION_NAME] = Yablonsky_AI_Chatbots_Settings::defaults();
$frontend_disabled_fallback = new Yablonsky_AI_Chatbots_Frontend();
$frontend_disabled_fallback->enqueue_assets();
ob_start();
$frontend_disabled_fallback->render_loader_fallback();
$disabled_fallback = ob_get_clean();
ysc_assert($disabled_fallback === '', 'disabled adapter fallback remains fail-closed');

$GLOBALS['ysc_test_options'][Yablonsky_AI_Chatbots_Settings::OPTION_NAME] = array(
    'enabled' => 1,
    'sdk_url' => 'https://evil.example/sdk.js',
    'chatbot_id' => 'not-a-uuid',
);
$frontend_invalid_fallback = new Yablonsky_AI_Chatbots_Frontend();
$frontend_invalid_fallback->enqueue_assets();
ob_start();
$frontend_invalid_fallback->render_loader_fallback();
$invalid_fallback = ob_get_clean();
ysc_assert($invalid_fallback === '', 'invalid config fallback remains fail-closed');

ysc_assert(Yablonsky_AI_Chatbots_Settings::OPTION_NAME === 'yablonsky_ai_chatbots_settings', 'canonical settings option key is active');
ysc_assert(Yablonsky_AI_Chatbots_Settings::LEGACY_OPTION_NAME === 'yablonsky_secure_chatbots_settings', 'legacy settings option key is preserved as a migration bridge');
ysc_assert(Yablonsky_AI_Chatbots_Settings::PAGE_SLUG === 'yablonsky-ai-chatbots', 'settings page slug is canonical');
ysc_assert(Yablonsky_AI_Chatbots_Sanitizer::SDK_PATH === '/e/yablonsky/secure-chatbots/sdk.js', 'canonical SDK path preserved');

echo "WORDPRESS_ADAPTER_SMOKE=PASS\n";
