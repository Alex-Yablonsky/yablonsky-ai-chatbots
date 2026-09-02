<?php

defined('ABSPATH') || exit;

final class Yablonsky_AI_Chatbots_Frontend {
    const ASSET_QUERY_KEY = 'ysc_adapter_asset';
    const ASSET_VERSION_QUERY_KEY = 'ysc_adapter_version';

    private $eligible = false;
    private $settings = array();

    public function register() {
        add_action('template_redirect', array($this, 'serve_adapter_asset'), 0);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'), 20);
        add_action('wp_footer', array($this, 'render_public_config'), 5);
        add_action('wp_footer', array($this, 'render_loader_fallback'), PHP_INT_MAX);
    }

    public function serve_adapter_asset() {
        $asset_value = filter_input(INPUT_GET, self::ASSET_QUERY_KEY, FILTER_UNSAFE_RAW);
        if ($asset_value === null || $asset_value === false) {
            return;
        }

        if (!is_string($asset_value)) {
            status_header(404);
            exit;
        }

        $asset = sanitize_key($asset_value);
        $assets = array(
            'frontend' => array(
                'path' => YAI_CHATBOTS_PLUGIN_DIR . 'public/css/frontend.css',
                'type' => 'text/css; charset=UTF-8',
            ),
            'loader' => array(
                'path' => YAI_CHATBOTS_PLUGIN_DIR . 'public/js/loader.js',
                'type' => 'application/javascript; charset=UTF-8',
            ),
        );

        if (!isset($assets[$asset])) {
            status_header(404);
            exit;
        }

        $request_method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_UNSAFE_RAW);
        $method = is_string($request_method) ? strtoupper(sanitize_text_field($request_method)) : 'GET';
        if ($method !== 'GET' && $method !== 'HEAD') {
            status_header(405);
            header('Allow: GET, HEAD');
            exit;
        }

        $path = $assets[$asset]['path'];
        $filesystem = $this->filesystem();
        if (!$filesystem->is_file($path) || !$filesystem->is_readable($path)) {
            status_header(404);
            exit;
        }

        status_header(200);
        header('Content-Type: ' . $assets[$asset]['type']);
        header('Cache-Control: public, max-age=604800');
        header('X-Content-Type-Options: nosniff');

        $size = $filesystem->size($path);
        if ($size !== false) {
            header('Content-Length: ' . (string) $size);
        }

        if ($method === 'HEAD') {
            exit;
        }

        $contents = $filesystem->get_contents($path);
        if ($contents === false) {
            status_header(404);
            exit;
        }

        echo $contents; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed allowlisted bundled JS/CSS must be emitted byte-for-byte.
        exit;
    }

    public function enqueue_assets() {
        $this->settings = Yablonsky_AI_Chatbots_Settings::get_all();
        $this->eligible = $this->should_load($this->settings);
        if (!$this->eligible) {
            return;
        }

        wp_enqueue_style(
            'yablonsky-secure-chatbots',
            $this->asset_url('frontend'),
            array(),
            YAI_CHATBOTS_VERSION
        );

        wp_enqueue_script(
            'yablonsky-secure-chatbots-loader',
            $this->asset_url('loader'),
            array(),
            YAI_CHATBOTS_VERSION,
            true
        );
    }

    public function render_loader_fallback() {
        if (!$this->eligible) {
            return;
        }

        if (wp_script_is('yablonsky-secure-chatbots-loader', 'done')) {
            return;
        }

        wp_enqueue_script(
            'yablonsky-secure-chatbots-loader',
            $this->asset_url('loader'),
            array(),
            YAI_CHATBOTS_VERSION,
            true
        );

        print_footer_scripts();
    }

    public function render_public_config() {
        if (!$this->eligible) {
            return;
        }

        $sdk_url = (string) $this->settings['sdk_url'];
        $base = Yablonsky_AI_Chatbots_Settings::backend_base_from_sdk($sdk_url);
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


    /**
     * Read bundled public assets through the WordPress filesystem abstraction.
     * Direct transport avoids credential prompts for read-only files already readable by PHP.
     */
    private function filesystem() {
        if (!class_exists('WP_Filesystem_Base')) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
        }
        if (!class_exists('WP_Filesystem_Direct')) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
        }

        return new WP_Filesystem_Direct(null);
    }

    private function asset_url($asset) {
        return add_query_arg(
            array(
                self::ASSET_QUERY_KEY => $asset,
                self::ASSET_VERSION_QUERY_KEY => YAI_CHATBOTS_VERSION,
            ),
            home_url('/')
        );
    }

    private function should_load($settings) {
        if (is_admin() || empty($settings['enabled'])) {
            return false;
        }

        if (!Yablonsky_AI_Chatbots_Sanitizer::is_valid_sdk_url($settings['sdk_url'])
            || !Yablonsky_AI_Chatbots_Sanitizer::is_uuid($settings['chatbot_id'])) {
            return false;
        }

        if ((function_exists('is_feed') && is_feed())
            || (function_exists('is_robots') && is_robots())
            || (function_exists('is_trackback') && is_trackback())
            || (function_exists('is_404') && is_404())) {
            return false;
        }

        $excluded = Yablonsky_AI_Chatbots_Settings::normalise_page_ids($settings['excluded_page_ids']);
        if ($excluded && function_exists('is_page') && is_page($excluded)) {
            return false;
        }

        return true;
    }
}
