<?php

defined('ABSPATH') || exit;

final class Yablonsky_Secure_Chatbots_Deactivator {
    public static function deactivate() {
        // Intentionally no remote calls and no data deletion. Deactivation only stops WordPress hooks.
    }
}
