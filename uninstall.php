<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Explicit retention policy: remove only this adapter's WordPress settings.
// Never touch chatbot/backend data, consent data, GTM, Clickio, theme data, or other plugins.
delete_option('yablonsky_secure_chatbots_settings');
