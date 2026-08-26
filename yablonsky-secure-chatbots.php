<?php
/**
 * Plugin Name: Yablonsky AI Chatbots for WordPress
 * Plugin URI: https://yablonsky.io/ai-chatbots/
 * Description: Native WordPress adapter for Yablonsky AI Chatbots. Embed a business AI assistant on WordPress while conversations, intelligence and security remain on Yablonsky AI.
 * Version: 0.1.2
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Yablonsky.io
 * Author URI: https://yablonsky.io/
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: yablonsky-secure-chatbots
 */

defined('ABSPATH') || exit;

define('YSC_WP_VERSION', '0.1.2');
define('YSC_WP_PLUGIN_FILE', __FILE__);
define('YSC_WP_PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once YSC_WP_PLUGIN_DIR . 'includes/class-yablonsky-secure-chatbots-sanitizer.php';
require_once YSC_WP_PLUGIN_DIR . 'includes/class-yablonsky-secure-chatbots-settings.php';
require_once YSC_WP_PLUGIN_DIR . 'includes/class-yablonsky-secure-chatbots-admin.php';
require_once YSC_WP_PLUGIN_DIR . 'includes/class-yablonsky-secure-chatbots-frontend.php';
require_once YSC_WP_PLUGIN_DIR . 'includes/class-yablonsky-secure-chatbots-activator.php';
require_once YSC_WP_PLUGIN_DIR . 'includes/class-yablonsky-secure-chatbots-deactivator.php';
require_once YSC_WP_PLUGIN_DIR . 'includes/class-yablonsky-secure-chatbots-plugin.php';

register_activation_hook(__FILE__, array('Yablonsky_Secure_Chatbots_Activator', 'activate'));
register_deactivation_hook(__FILE__, array('Yablonsky_Secure_Chatbots_Deactivator', 'deactivate'));

function yablonsky_secure_chatbots_run() {
    $plugin = new Yablonsky_Secure_Chatbots_Plugin();
    $plugin->run();
}

yablonsky_secure_chatbots_run();
