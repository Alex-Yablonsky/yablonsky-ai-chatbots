<?php

defined('ABSPATH') || exit;

final class Yablonsky_AI_Chatbots_Sanitizer {
    const SDK_PATH = '/e/yablonsky/secure-chatbots/sdk.js';

    public static function sanitize($input) {
        $defaults = Yablonsky_AI_Chatbots_Settings::defaults();
        $current = get_option(Yablonsky_AI_Chatbots_Settings::OPTION_NAME, $defaults);
        if (!is_array($current)) {
            $current = $defaults;
        }
        if (!is_array($input)) {
            $input = array();
        }

        $output = $defaults;
        $output['enabled'] = empty($input['enabled']) ? 0 : 1;
        $output['consent_bridge'] = empty($input['consent_bridge']) ? 0 : 1;
        $output['excluded_page_ids'] = Yablonsky_AI_Chatbots_Settings::normalise_page_ids(
            isset($input['excluded_page_ids']) ? $input['excluded_page_ids'] : array()
        );

        $title = isset($input['iframe_title']) ? sanitize_text_field($input['iframe_title']) : $defaults['iframe_title'];
        $output['iframe_title'] = self::limit_text($title, 120, $defaults['iframe_title']);

        $sdk_candidate = isset($input['sdk_url']) ? esc_url_raw(trim((string) $input['sdk_url'])) : '';
        if (self::is_valid_sdk_url($sdk_candidate)) {
            $output['sdk_url'] = $sdk_candidate;
        } else {
            $fallback = isset($current['sdk_url']) && self::is_valid_sdk_url($current['sdk_url'])
                ? $current['sdk_url']
                : $defaults['sdk_url'];
            $output['sdk_url'] = $fallback;
            self::settings_error('invalid_sdk_url', __('The SDK endpoint must be the canonical HTTPS Yablonsky AI Chatbots SDK URL on an allowed host.', 'yablonsky-ai-chatbots'));
        }

        $chatbot_id = isset($input['chatbot_id']) ? strtolower(sanitize_text_field($input['chatbot_id'])) : '';
        if ($chatbot_id === '' || self::is_uuid($chatbot_id)) {
            $output['chatbot_id'] = $chatbot_id;
        } else {
            $output['chatbot_id'] = '';
            $output['enabled'] = 0;
            self::settings_error('invalid_chatbot_id', __('The public AI Chatbot reference must be a UUID. The adapter was disabled.', 'yablonsky-ai-chatbots'));
        }

        if ($output['enabled'] && $output['chatbot_id'] === '') {
            $output['enabled'] = 0;
            self::settings_error('missing_chatbot_id', __('Add a public AI Chatbot reference before enabling the adapter.', 'yablonsky-ai-chatbots'));
        }

        return $output;
    }

    public static function is_valid_sdk_url($url) {
        if (!is_string($url) || $url === '') {
            return false;
        }

        $parts = wp_parse_url($url);
        if (!is_array($parts)
            || strtolower(isset($parts['scheme']) ? $parts['scheme'] : '') !== 'https'
            || empty($parts['host'])
            || !empty($parts['user'])
            || !empty($parts['pass'])
            || !empty($parts['query'])
            || !empty($parts['fragment'])) {
            return false;
        }

        $path = isset($parts['path']) ? '/' . ltrim($parts['path'], '/') : '';
        if ($path !== self::SDK_PATH) {
            return false;
        }

        $host = strtolower($parts['host']);
        $allowed_hosts = apply_filters('yablonsky_ai_chatbots_allowed_sdk_hosts', array('ai.yablonsky.io'));
        $allowed_hosts = apply_filters('yablonsky_secure_chatbots_allowed_sdk_hosts', $allowed_hosts);
        if (!is_array($allowed_hosts)) {
            $allowed_hosts = array('ai.yablonsky.io');
        }
        $allowed_hosts = array_map('strtolower', array_map('strval', $allowed_hosts));

        return in_array($host, $allowed_hosts, true);
    }

    public static function is_uuid($value) {
        return is_string($value)
            && (bool) preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/D', $value);
    }

    private static function limit_text($value, $max_length, $fallback) {
        $value = trim((string) $value);
        if ($value === '') {
            return $fallback;
        }

        if (function_exists('mb_substr')) {
            return mb_substr($value, 0, $max_length);
        }

        return substr($value, 0, $max_length);
    }

    private static function settings_error($code, $message) {
        if (function_exists('add_settings_error')) {
            add_settings_error(
                Yablonsky_AI_Chatbots_Settings::OPTION_NAME,
                $code,
                $message,
                'error'
            );
        }
    }
}
