<?php

require __DIR__ . '/wp-stubs.php';

function migration_assert($condition, $message) {
    if (!$condition) {
        fwrite(STDERR, "FAIL: {$message}\n");
        exit(1);
    }
    echo "PASS: {$message}\n";
}

// Simulate the public PHP identity already loaded by 0.1.3.
define('YSC_WP_VERSION', '0.1.3');
define('YSC_WP_PLUGIN_FILE', '/example/yablonsky-secure-chatbots/yablonsky-secure-chatbots.php');
define('YSC_WP_PLUGIN_DIR', '/example/yablonsky-secure-chatbots/');
final class Yablonsky_Secure_Chatbots_Settings {}
final class Yablonsky_Secure_Chatbots_Sanitizer {}
final class Yablonsky_Secure_Chatbots_Admin {}
final class Yablonsky_Secure_Chatbots_Frontend {}
final class Yablonsky_Secure_Chatbots_Activator {}
final class Yablonsky_Secure_Chatbots_Deactivator {}
final class Yablonsky_Secure_Chatbots_Plugin {}
function yablonsky_secure_chatbots_run() {}

$GLOBALS['ysc_test_options']['active_plugins'] = array(
    'yablonsky-secure-chatbots/yablonsky-secure-chatbots.php',
);
$GLOBALS['ysc_test_options']['yablonsky_secure_chatbots_settings'] = array(
    'enabled' => 1,
    'sdk_url' => 'https://ai.yablonsky.io/e/yablonsky/secure-chatbots/sdk.js',
    'chatbot_id' => '00000000-0000-0000-0000-000000000001',
    'excluded_page_ids' => array(12, 34),
    'consent_bridge' => 1,
    'iframe_title' => 'Legacy assistant',
);

require dirname(__DIR__) . '/yablonsky-ai-chatbots.php';

echo "COEXISTENCE_INCLUDE=PASS\n";

Yablonsky_AI_Chatbots_Activator::activate(false);

$new = get_option(Yablonsky_AI_Chatbots_Settings::OPTION_NAME, null);
$legacy = get_option(Yablonsky_AI_Chatbots_Settings::LEGACY_OPTION_NAME, null);
migration_assert(is_array($new) && $new === $legacy, 'legacy settings migrate byte-for-byte at activation');
migration_assert(!is_plugin_active('yablonsky-secure-chatbots/yablonsky-secure-chatbots.php'), 'historical site-level predecessor is deactivated after migration');

$updated = $new;
$updated['iframe_title'] = 'Canonical assistant';
Yablonsky_AI_Chatbots_Settings::mirror_legacy_option($new, $updated);
migration_assert(get_option(Yablonsky_AI_Chatbots_Settings::LEGACY_OPTION_NAME)['iframe_title'] === 'Canonical assistant', 'upgraded installation keeps rollback mirror current');

unset($GLOBALS['ysc_test_options'][Yablonsky_AI_Chatbots_Settings::LEGACY_OPTION_NAME]);
Yablonsky_AI_Chatbots_Settings::mirror_legacy_option($updated, $new);
migration_assert(get_option(Yablonsky_AI_Chatbots_Settings::LEGACY_OPTION_NAME, null) === null, 'fresh canonical state does not recreate a legacy option');

echo "FATAL_COLLISION_REGRESSION=PASS\n";
