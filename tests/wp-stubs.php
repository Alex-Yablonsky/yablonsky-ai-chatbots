<?php

$GLOBALS['ysc_test_options'] = array();
$GLOBALS['ysc_test_errors'] = array();
$GLOBALS['ysc_test_actions'] = array();
$GLOBALS['ysc_test_filters'] = array();
$GLOBALS['ysc_test_enqueued_scripts'] = array();
$GLOBALS['ysc_test_enqueued_styles'] = array();
$GLOBALS['ysc_test_script_done'] = array();
$GLOBALS['ysc_test_page_id'] = 0;
$GLOBALS['ysc_test_is_admin'] = false;

define('ABSPATH', __DIR__ . '/');

define('WP_UNINSTALL_PLUGIN', true);

function plugin_dir_path($file) { return rtrim(dirname($file), '/') . '/'; }
function plugin_dir_url($file) { return 'https://example.test/wp-content/plugins/yablonsky-ai-chatbots/'; }
function plugin_basename($file) { return basename(dirname($file)) . '/' . basename($file); }
function register_activation_hook($file, $callback) { $GLOBALS['ysc_activation'] = $callback; }
function register_deactivation_hook($file, $callback) { $GLOBALS['ysc_deactivation'] = $callback; }
function add_action($hook, $callback, $priority = 10, $args = 1) { $GLOBALS['ysc_test_actions'][$hook][] = array('callback' => $callback, 'priority' => $priority, 'args' => $args); }
function add_filter($hook, $callback, $priority = 10, $args = 1) { $GLOBALS['ysc_test_filters'][$hook][] = $callback; }
function apply_filters($hook, $value) { return $value; }
function get_option($name, $default = false) { return array_key_exists($name, $GLOBALS['ysc_test_options']) ? $GLOBALS['ysc_test_options'][$name] : $default; }
function add_option($name, $value, $deprecated = '', $autoload = null) { if (!array_key_exists($name, $GLOBALS['ysc_test_options'])) { $GLOBALS['ysc_test_options'][$name] = $value; return true; } return false; }
function update_option($name, $value, $autoload = null) {
    $old = array_key_exists($name, $GLOBALS['ysc_test_options']) ? $GLOBALS['ysc_test_options'][$name] : false;
    $GLOBALS['ysc_test_options'][$name] = $value;
    $hook = 'update_option_' . $name;
    if (!empty($GLOBALS['ysc_test_actions'][$hook])) {
        foreach ($GLOBALS['ysc_test_actions'][$hook] as $action) {
            call_user_func($action['callback'], $old, $value);
        }
    }
    return true;
}
function delete_option($name) { unset($GLOBALS['ysc_test_options'][$name]); return true; }
function wp_parse_args($args, $defaults = array()) { return array_merge($defaults, is_array($args) ? $args : array()); }
function wp_parse_url($url) { return parse_url($url); }
function absint($value) { return abs((int) $value); }
function sanitize_text_field($value) { return trim(strip_tags((string) $value)); }
function esc_url_raw($value) { return filter_var($value, FILTER_SANITIZE_URL); }
function esc_url($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function esc_attr($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function esc_html($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function esc_html__($value, $domain = null) { return esc_html($value); }
function __($value, $domain = null) { return $value; }
function checked($checked, $current = true, $echo = true) { $out = ((string) $checked === (string) $current) ? 'checked="checked"' : ''; if ($echo) echo $out; return $out; }
function add_settings_error($setting, $code, $message, $type = 'error') { $GLOBALS['ysc_test_errors'][] = array($setting, $code, $message, $type); }
function is_admin() { return $GLOBALS['ysc_test_is_admin']; }
function is_feed() { return false; }
function is_robots() { return false; }
function is_trackback() { return false; }
function is_404() { return false; }
function is_page($ids = null) { return in_array($GLOBALS['ysc_test_page_id'], (array) $ids, true); }
function wp_enqueue_style($handle, $src, $deps = array(), $version = false) { $GLOBALS['ysc_test_enqueued_styles'][$handle] = $src; }
function wp_enqueue_script($handle, $src, $deps = array(), $version = false, $footer = false) { $GLOBALS['ysc_test_enqueued_scripts'][$handle] = $src; }
function wp_script_is($handle, $status = 'enqueued') {
    if ($status === 'done') {
        return !empty($GLOBALS['ysc_test_script_done'][$handle]);
    }
    return isset($GLOBALS['ysc_test_enqueued_scripts'][$handle]);
}
function print_footer_scripts() {
    foreach ($GLOBALS['ysc_test_enqueued_scripts'] as $handle => $src) {
        if (!empty($GLOBALS['ysc_test_script_done'][$handle])) {
            continue;
        }
        echo '<script src="' . esc_url($src) . '"></script>' . "\n";
        $GLOBALS['ysc_test_script_done'][$handle] = true;
    }
    return array_keys(array_filter($GLOBALS['ysc_test_script_done']));
}
function current_user_can($cap) { return true; }
function wp_die($message) { throw new RuntimeException($message); }
function settings_errors($setting = '') {}
function settings_fields($group) {}
function do_settings_sections($page) {}
function submit_button() {}
function register_setting($group, $name, $args = array()) {}
function add_settings_section($id, $title, $callback, $page) {}
function add_settings_field($id, $title, $callback, $page, $section = 'default') {}
function add_options_page($page_title, $menu_title, $capability, $menu_slug, $callback) {}
function admin_url($path = '') { return 'https://example.test/wp-admin/' . ltrim($path, '/'); }
function home_url($path = '') { return 'https://example.test/' . ltrim($path, '/'); }
function add_query_arg($args, $url) { $sep = (strpos($url, '?') === false) ? '?' : '&'; return $url . $sep . http_build_query($args); }
function wp_unslash($value) { return $value; }
function sanitize_key($value) { return strtolower(preg_replace('/[^a-z0-9_\-]/i', '', (string) $value)); }
function status_header($code) { $GLOBALS['ysc_test_status'] = (int) $code; }
function is_multisite() { return false; }
function get_site_option($name, $default = false) { return $default; }
function is_plugin_active($basename) { return in_array($basename, get_option('active_plugins', array()), true); }
function is_plugin_active_for_network($basename) { return false; }
function deactivate_plugins($plugins, $silent = false, $network_wide = null) {
    $plugins = (array) $plugins;
    $active = get_option('active_plugins', array());
    $GLOBALS['ysc_test_options']['active_plugins'] = array_values(array_diff($active, $plugins));
}
