<?php

defined('ABSPATH') || exit;

final class Yablonsky_AI_Chatbots_Activator {
    const LEGACY_PLUGIN_BASENAME = 'yablonsky-secure-chatbots/yablonsky-secure-chatbots.php';

    public static function activate($network_wide = false) {
        Yablonsky_AI_Chatbots_Settings::migrate_legacy_option();
        self::deactivate_legacy_predecessor((bool) $network_wide);
    }

    private static function deactivate_legacy_predecessor($network_wide) {
        if (!function_exists('deactivate_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        if (!function_exists('deactivate_plugins')) {
            return;
        }

        if ($network_wide) {
            if (function_exists('is_plugin_active_for_network')
                && is_plugin_active_for_network(self::LEGACY_PLUGIN_BASENAME)) {
                deactivate_plugins(self::LEGACY_PLUGIN_BASENAME, true, true);
            }
            return;
        }

        if (function_exists('is_plugin_active') && is_plugin_active(self::LEGACY_PLUGIN_BASENAME)) {
            deactivate_plugins(self::LEGACY_PLUGIN_BASENAME, true, false);
        }
    }
}
