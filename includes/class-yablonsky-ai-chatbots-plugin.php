<?php

defined('ABSPATH') || exit;

final class Yablonsky_AI_Chatbots_Plugin {
    const LEGACY_PLUGIN_BASENAME = 'yablonsky-secure-chatbots/yablonsky-secure-chatbots.php';

    public function run() {
        // During the one activation request where the historical predecessor is still loaded,
        // stay passive. The activation hook migrates settings and deactivates the predecessor.
        // On the next request only this canonical plugin registers runtime hooks.
        if ($this->legacy_predecessor_is_active()) {
            return;
        }

        add_action(
            'update_option_' . Yablonsky_AI_Chatbots_Settings::OPTION_NAME,
            array('Yablonsky_AI_Chatbots_Settings', 'mirror_legacy_option'),
            10,
            2
        );

        if (is_admin()) {
            $admin = new Yablonsky_AI_Chatbots_Admin();
            $admin->register();
        }

        $frontend = new Yablonsky_AI_Chatbots_Frontend();
        $frontend->register();
    }

    private function legacy_predecessor_is_active() {
        $active_plugins = get_option('active_plugins', array());
        if (is_array($active_plugins)
            && in_array(self::LEGACY_PLUGIN_BASENAME, $active_plugins, true)) {
            return true;
        }

        if (is_multisite()) {
            $network_plugins = get_site_option('active_sitewide_plugins', array());
            if (is_array($network_plugins)
                && isset($network_plugins[self::LEGACY_PLUGIN_BASENAME])) {
                return true;
            }
        }

        return false;
    }
}
