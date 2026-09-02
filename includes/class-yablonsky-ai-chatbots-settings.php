<?php

defined('ABSPATH') || exit;

final class Yablonsky_AI_Chatbots_Settings {
    const OPTION_NAME = 'yablonsky_ai_chatbots_settings';
    const LEGACY_OPTION_NAME = 'yablonsky_secure_chatbots_settings';
    const PAGE_SLUG = 'yablonsky-ai-chatbots';

    public static function defaults() {
        return array(
            'enabled'           => 0,
            'sdk_url'           => 'https://ai.yablonsky.io/e/yablonsky/secure-chatbots/sdk.js',
            'chatbot_id'        => '',
            'excluded_page_ids' => array(),
            'consent_bridge'    => 1,
            'iframe_title'      => 'Yablonsky.io AI assistant',
        );
    }

    public static function get_all() {
        $stored = get_option(self::OPTION_NAME, null);
        if ($stored === null) {
            $stored = get_option(self::LEGACY_OPTION_NAME, array());
        }
        if (!is_array($stored)) {
            $stored = array();
        }

        $settings = wp_parse_args($stored, self::defaults());
        $settings['excluded_page_ids'] = self::normalise_page_ids($settings['excluded_page_ids']);

        return $settings;
    }


    public static function migrate_legacy_option() {
        if (get_option(self::OPTION_NAME, null) !== null) {
            return;
        }

        $legacy = get_option(self::LEGACY_OPTION_NAME, null);
        $initial = is_array($legacy) ? $legacy : self::defaults();

        add_option(self::OPTION_NAME, $initial, '', false);
    }

    public static function mirror_legacy_option($old_value, $value) {
        if (!is_array($value)) {
            return;
        }

        // Mirror only upgraded installations that already have the historical option.
        // Fresh canonical installs must not create legacy state.
        if (get_option(self::LEGACY_OPTION_NAME, null) === null) {
            return;
        }

        update_option(self::LEGACY_OPTION_NAME, $value, false);
    }

    public static function get($key, $fallback = null) {
        $settings = self::get_all();
        return array_key_exists($key, $settings) ? $settings[$key] : $fallback;
    }

    public static function backend_base_from_sdk($sdk_url) {
        $parts = wp_parse_url($sdk_url);
        if (!is_array($parts) || empty($parts['scheme']) || empty($parts['host'])) {
            return '';
        }

        $base = strtolower($parts['scheme']) . '://' . strtolower($parts['host']);
        if (!empty($parts['port'])) {
            $base .= ':' . absint($parts['port']);
        }

        return $base;
    }

    public static function normalise_page_ids($value) {
        if (is_string($value)) {
            $value = preg_split('/[\s,]+/', $value, -1, PREG_SPLIT_NO_EMPTY);
        }

        if (!is_array($value)) {
            return array();
        }

        $ids = array();
        foreach ($value as $item) {
            $id = absint($item);
            if ($id > 0) {
                $ids[$id] = $id;
            }
        }

        ksort($ids, SORT_NUMERIC);
        return array_values($ids);
    }
}
