<?php

defined('ABSPATH') || exit;

final class Yablonsky_Secure_Chatbots_Activator {
    public static function activate() {
        if (get_option(Yablonsky_Secure_Chatbots_Settings::OPTION_NAME, null) === null) {
            add_option(
                Yablonsky_Secure_Chatbots_Settings::OPTION_NAME,
                Yablonsky_Secure_Chatbots_Settings::defaults(),
                '',
                false
            );
        }
    }
}
