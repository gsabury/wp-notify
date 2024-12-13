<?php
/*
Plugin Name: WordPress Notifiy
Plugin URI: https://yaransoft.com
Description: A plugin to manage notification in WordPress.
Author: Abdul Ghafor Sabury
Version: 1.0.0
Author URI:  https://yaransoft.com
*/

defined('ABSPATH') || exit('NO ACCESS');

define('WPNOT_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define('WPNOT_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('WPNOT_INC', trailingslashit(WPNOT_DIR . 'inc'));
define('WPNOT_LIBS', trailingslashit(WPNOT_INC . 'libs'));
define('WPNOT_TPL', trailingslashit(WPNOT_DIR . 'tpl'));

if (is_admin()) {
    include  WPNOT_INC . 'admin_menu.php';
    include  WPNOT_INC . 'admin_pages.php';
}

include WPNOT_INC . 'functions.php';

define('WPNOTIFY_DB_VERSION', 1);

register_activation_hook(__FILE__, 'wpnot_activate');
register_deactivation_hook(__FILE__, 'wpnot_deactivate');

function wpnot_activate()
{
    include  WPNOT_INC . 'upgrade.php';
}

function wpnot_deactivate() {}
