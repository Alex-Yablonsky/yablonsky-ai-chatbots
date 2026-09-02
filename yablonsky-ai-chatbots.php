<?php
/**
 * Plugin Name: Yablonsky AI Chatbots
 * Plugin URI: https://yablonsky.io/ai-chatbots/
 * Description: Native WordPress adapter for Yablonsky AI Chatbots. Embed a business AI assistant on WordPress while conversations, intelligence and security remain on Yablonsky AI.
 * Version: 0.1.4
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Yablonsky.io
 * Author URI: https://yablonsky.io/
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: yablonsky-ai-chatbots
 */

defined('ABSPATH') || exit;

define('YAI_CHATBOTS_VERSION', '0.1.4');
define('YAI_CHATBOTS_PLUGIN_FILE', __FILE__);
define('YAI_CHATBOTS_PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once YAI_CHATBOTS_PLUGIN_DIR . 'includes/class-yablonsky-ai-chatbots-sanitizer.php';
require_once YAI_CHATBOTS_PLUGIN_DIR . 'includes/class-yablonsky-ai-chatbots-settings.php';
require_once YAI_CHATBOTS_PLUGIN_DIR . 'includes/class-yablonsky-ai-chatbots-admin.php';
require_once YAI_CHATBOTS_PLUGIN_DIR . 'includes/class-yablonsky-ai-chatbots-frontend.php';
require_once YAI_CHATBOTS_PLUGIN_DIR . 'includes/class-yablonsky-ai-chatbots-activator.php';
require_once YAI_CHATBOTS_PLUGIN_DIR . 'includes/class-yablonsky-ai-chatbots-deactivator.php';
require_once YAI_CHATBOTS_PLUGIN_DIR . 'includes/class-yablonsky-ai-chatbots-plugin.php';

register_activation_hook(__FILE__, array('Yablonsky_AI_Chatbots_Activator', 'activate'));
register_deactivation_hook(__FILE__, array('Yablonsky_AI_Chatbots_Deactivator', 'deactivate'));

function yablonsky_ai_chatbots_run() {
    $plugin = new Yablonsky_AI_Chatbots_Plugin();
    $plugin->run();
}

yablonsky_ai_chatbots_run();
