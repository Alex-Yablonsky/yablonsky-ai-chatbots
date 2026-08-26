<?php

defined('ABSPATH') || exit;

final class Yablonsky_Secure_Chatbots_Frontend {
    const ASSET_QUERY_KEY = 'ysc_adapter_asset';
    const ASSET_VERSION_QUERY_KEY = 'ysc_adapter_version';

    private $eligible = false;
    private $settings = array();

    public function register() {
        add_action('template_redirect', array($this, 'serve_adapter_asset'), 0);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'), 20);
        add_action('wp_footer', array($this, 'render_public_config'), 5);
    }

    public function serve_adapter_asset() {
        if (!isset($_GET[self::ASSET_QUERY_KEY])) {
            return;
        }

        $asset_value = wp_unslash($_GET[self::ASSET_QUERY_KEY]);
        if (!is_string($asset_value)) {
            status_header(404);
            exit;
        }

        $asset = sanitize_key($asset_value);
        $assets = array(
            'frontend' => array(
                'path' => YSC_WP_PLUGIN_DIR . 'public/css/frontend.css',
                'type' => 'text/css; charset=UTF-8',
            ),
            'loader' => array(
                'path' => YSC_WP_PLUGIN_DIR . 'public/js/loader.js',
                'type' => 'application/javascript; charset=UTF-8',
            ),
        );

        if (!isset($assets[$asset])) {
            status_header(404);
            exit;
        }

        $method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper((string) $_SERVER['REQUEST_METHOD']) : 'GET';
        if ($method !== 'GET' && $method !== 'HEAD') {
            status_header(405);
            header('Allow: GET, HEAD');
            exit;
        }

        $path = $assets[$asset]['path'];
        if (!is_file($path) || !is_readable($path)) {
            status_header(404);
            exit;
        }

        status_header(200);
        header('Content-Type: ' . $assets[$asset]['type']);
        header('Cache-Control: public, max-age=604800');
        header('X-Content-Type-Options: nosniff');

        $size = filesize($path);
        if ($size !== false) {
            header('Content-Length: ' . (string) $size);
        }

        if ($method === 'HEAD') {
            exit;
        }

        readfile($path);
        exit;
    }

    public function enqueue_assets() {
        $this->settings = Yablonsky_Secure_Chatbots_Settings::get_all();
        $this->eligible = $this->should_load($this->settings);
        if (!$this->eligible) {
            return;
        }

        wp_enqueue_style(
            'yablonsky-secure-chatbots',
            $this->asset_url('frontend'),
            array(),
            YSC_WP_VERSION
        );

        wp_enqueue_script(
            'yablonsky-secure-chatbots-loader',
            $this->asset_url('loader'),
            array(),
            YSC_WP_VERSION,
            true
        );
    }

    public function render_public_config() {
        if (!$this->eligible) {
            return;
        }

        $sdk_url = (string) $this->settings['sdk_url'];
        $base = Yablonsky_Secure_Chatbots_Settings::backend_base_from_sdk($sdk_url);
        if ($base === '') {
            return;
        }

        printf(
            '<div id="ysc-wp-adapter-config" hidden data-sdk-url="%1$s" data-base="%2$s" data-chatbot-id="%3$s" data-consent-bridge="%4$s" data-frame-title="%5$s"></div>' . "\n",
            esc_url($sdk_url),
            esc_url($base),
            esc_attr($this->settings['chatbot_id']),
            $this->settings['consent_bridge'] ? '1' : '0',
            esc_attr($this->settings['iframe_title'])
        );
    }

    private function asset_url($asset) {
        return add_query_arg(
            array(
                self::ASSET_QUERY_KEY => $asset,
                self::ASSET_VERSION_QUERY_KEY => YSC_WP_VERSION,
            ),
            home_url('/')
        );
    }

    private function should_load($settings) {
        if (is_admin() || empty($settings['enabled'])) {
            return false;
        }

        if (!Yablonsky_Secure_Chatbots_Sanitizer::is_valid_sdk_url($settings['sdk_url'])
            || !Yablonsky_Secure_Chatbots_Sanitizer::is_uuid($settings['chatbot_id'])) {
            return false;
        }

        if ((function_exists('is_feed') && is_feed())
            || (function_exists('is_robots') && is_robots())
            || (function_exists('is_trackback') && is_trackback())
            || (function_exists('is_404') && is_404())) {
            return false;
        }

        $excluded = Yablonsky_Secure_Chatbots_Settings::normalise_page_ids($settings['excluded_page_ids']);
        if ($excluded && function_exists('is_page') && is_page($excluded)) {
            return false;
        }

        return true;
    }
}
