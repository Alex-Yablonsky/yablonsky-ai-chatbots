<?php

defined('ABSPATH') || exit;

final class Yablonsky_Secure_Chatbots_Plugin {
    public function run() {
        if (is_admin()) {
            $admin = new Yablonsky_Secure_Chatbots_Admin();
            $admin->register();
        }

        $frontend = new Yablonsky_Secure_Chatbots_Frontend();
        $frontend->register();
    }
}
