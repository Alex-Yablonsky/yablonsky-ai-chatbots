<?php

defined('ABSPATH') || exit;

final class Yablonsky_AI_Chatbots_Admin {
    public function register() {
        add_action('admin_menu', array($this, 'add_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_filter('plugin_action_links_' . plugin_basename(YAI_CHATBOTS_PLUGIN_FILE), array($this, 'action_links'));
    }

    public function add_menu() {
        add_options_page(
            __('AI Chatbots', 'yablonsky-ai-chatbots'),
            __('AI Chatbots', 'yablonsky-ai-chatbots'),
            'manage_options',
            Yablonsky_AI_Chatbots_Settings::PAGE_SLUG,
            array($this, 'render_page')
        );
    }

    public function register_settings() {
        register_setting(
            'yablonsky_ai_chatbots_group',
            Yablonsky_AI_Chatbots_Settings::OPTION_NAME,
            array(
                'type'              => 'array',
                'sanitize_callback' => array('Yablonsky_AI_Chatbots_Sanitizer', 'sanitize'),
                'default'           => Yablonsky_AI_Chatbots_Settings::defaults(),
            )
        );

        add_settings_section(
            'ysc_connection',
            __('AI Chatbots connection', 'yablonsky-ai-chatbots'),
            array($this, 'render_connection_help'),
            Yablonsky_AI_Chatbots_Settings::PAGE_SLUG
        );

        $this->add_field('enabled', __('Enable AI Chatbot', 'yablonsky-ai-chatbots'), 'render_enabled');
        $this->add_field('sdk_url', __('AI Chatbot SDK URL', 'yablonsky-ai-chatbots'), 'render_sdk_url');
        $this->add_field('chatbot_id', __('Public AI Chatbot reference', 'yablonsky-ai-chatbots'), 'render_chatbot_id');
        $this->add_field('excluded_page_ids', __('Excluded page IDs', 'yablonsky-ai-chatbots'), 'render_excluded_pages');
        $this->add_field('consent_bridge', __('Consent UI bridge', 'yablonsky-ai-chatbots'), 'render_consent_bridge');
        $this->add_field('iframe_title', __('AI Chatbot frame title', 'yablonsky-ai-chatbots'), 'render_iframe_title');
    }

    private function add_field($id, $title, $method) {
        add_settings_field(
            'ysc_' . $id,
            $title,
            array($this, $method),
            Yablonsky_AI_Chatbots_Settings::PAGE_SLUG,
            'ysc_connection'
        );
    }

    public function render_connection_help() {
        echo '<p>' . esc_html__('This plugin loads the canonical Yablonsky AI Chatbots SDK. WordPress owns placement and loading only; conversations, intelligence, security and session logic remain on Yablonsky AI.', 'yablonsky-ai-chatbots') . '</p>';
    }

    public function render_enabled() {
        $value = (int) Yablonsky_AI_Chatbots_Settings::get('enabled', 0);
        printf(
            '<label><input type="checkbox" name="%1$s[enabled]" value="1" %2$s> %3$s</label>',
            esc_attr(Yablonsky_AI_Chatbots_Settings::OPTION_NAME),
            checked(1, $value, false),
            esc_html__('Load the AI Chatbot on eligible public pages.', 'yablonsky-ai-chatbots')
        );
    }

    public function render_sdk_url() {
        $value = (string) Yablonsky_AI_Chatbots_Settings::get('sdk_url');
        printf(
            '<input class="regular-text code" type="url" spellcheck="false" autocomplete="off" name="%1$s[sdk_url]" value="%2$s" required> <p class="description">%3$s</p>',
            esc_attr(Yablonsky_AI_Chatbots_Settings::OPTION_NAME),
            esc_attr($value),
            esc_html__('HTTPS only. The path must remain /e/yablonsky/secure-chatbots/sdk.js on an allowed canonical host.', 'yablonsky-ai-chatbots')
        );
    }

    public function render_chatbot_id() {
        $value = (string) Yablonsky_AI_Chatbots_Settings::get('chatbot_id');
        printf(
            '<input class="regular-text code" type="text" inputmode="text" autocomplete="off" name="%1$s[chatbot_id]" value="%2$s" placeholder="00000000-0000-0000-0000-000000000000"> <p class="description">%3$s</p>',
            esc_attr(Yablonsky_AI_Chatbots_Settings::OPTION_NAME),
            esc_attr($value),
            esc_html__('Public AI Chatbot UUID only. No chatbot secret, preview credential or session credential belongs in WordPress.', 'yablonsky-ai-chatbots')
        );
    }

    public function render_excluded_pages() {
        $ids = Yablonsky_AI_Chatbots_Settings::get('excluded_page_ids', array());
        $value = implode(', ', Yablonsky_AI_Chatbots_Settings::normalise_page_ids($ids));
        printf(
            '<input class="regular-text" type="text" inputmode="numeric" autocomplete="off" name="%1$s[excluded_page_ids]" value="%2$s" placeholder="12, 34, 56"> <p class="description">%3$s</p>',
            esc_attr(Yablonsky_AI_Chatbots_Settings::OPTION_NAME),
            esc_attr($value),
            esc_html__('Whole-site mode is the default. List WordPress page IDs that must not load the AI Chatbot.', 'yablonsky-ai-chatbots')
        );
    }

    public function render_consent_bridge() {
        $value = (int) Yablonsky_AI_Chatbots_Settings::get('consent_bridge', 1);
        printf(
            '<label><input type="checkbox" name="%1$s[consent_bridge]" value="1" %2$s> %3$s</label><p class="description">%4$s</p>',
            esc_attr(Yablonsky_AI_Chatbots_Settings::OPTION_NAME),
            checked(1, $value, false),
            esc_html__('Hide the launcher while a consent UI announces that it is open.', 'yablonsky-ai-chatbots'),
            esc_html__('Listens only for consent:open, consent:closed and consent:changed. It does not depend on Clickio internals and does not make legal consent decisions.', 'yablonsky-ai-chatbots')
        );
    }

    public function render_iframe_title() {
        $value = (string) Yablonsky_AI_Chatbots_Settings::get('iframe_title');
        printf(
            '<input class="regular-text" type="text" maxlength="120" name="%1$s[iframe_title]" value="%2$s">',
            esc_attr(Yablonsky_AI_Chatbots_Settings::OPTION_NAME),
            esc_attr($value)
        );
    }

    public function render_page() {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to manage AI Chatbots.', 'yablonsky-ai-chatbots'));
        }

        $settings = Yablonsky_AI_Chatbots_Settings::get_all();
        $base = Yablonsky_AI_Chatbots_Settings::backend_base_from_sdk($settings['sdk_url']);
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('Yablonsky AI Chatbots', 'yablonsky-ai-chatbots'); ?></h1>
            <p><?php echo esc_html__('WordPress adapter only: placement and loading here; conversations, intelligence, security and business logic remain on Yablonsky AI.', 'yablonsky-ai-chatbots'); ?></p>
            <?php settings_errors(Yablonsky_AI_Chatbots_Settings::OPTION_NAME); ?>
            <form action="options.php" method="post">
                <?php
                settings_fields('yablonsky_ai_chatbots_group');
                do_settings_sections(Yablonsky_AI_Chatbots_Settings::PAGE_SLUG);
                submit_button();
                ?>
            </form>
            <hr>
            <h2><?php echo esc_html__('Safe diagnostics', 'yablonsky-ai-chatbots'); ?></h2>
            <table class="widefat striped" style="max-width:760px">
                <tbody>
                    <tr><td><?php echo esc_html__('Adapter version', 'yablonsky-ai-chatbots'); ?></td><td><code><?php echo esc_html(YAI_CHATBOTS_VERSION); ?></code></td></tr>
                    <tr><td><?php echo esc_html__('Enabled', 'yablonsky-ai-chatbots'); ?></td><td><?php echo $settings['enabled'] ? esc_html__('Yes', 'yablonsky-ai-chatbots') : esc_html__('No', 'yablonsky-ai-chatbots'); ?></td></tr>
                    <tr><td><?php echo esc_html__('SDK origin', 'yablonsky-ai-chatbots'); ?></td><td><code><?php echo esc_html($base); ?></code></td></tr>
                    <tr><td><?php echo esc_html__('Public AI Chatbot reference configured', 'yablonsky-ai-chatbots'); ?></td><td><?php echo $settings['chatbot_id'] ? esc_html__('Yes', 'yablonsky-ai-chatbots') : esc_html__('No', 'yablonsky-ai-chatbots'); ?></td></tr>
                    <tr><td><?php echo esc_html__('Excluded pages', 'yablonsky-ai-chatbots'); ?></td><td><?php echo esc_html((string) count($settings['excluded_page_ids'])); ?></td></tr>
                </tbody>
            </table>
        </div>
        <?php
    }

    public function action_links($links) {
        $url = admin_url('options-general.php?page=' . Yablonsky_AI_Chatbots_Settings::PAGE_SLUG);
        array_unshift($links, '<a href="' . esc_url($url) . '">' . esc_html__('Settings', 'yablonsky-ai-chatbots') . '</a>');
        return $links;
    }
}
