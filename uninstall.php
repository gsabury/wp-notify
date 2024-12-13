<?php

// If uninstall is not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

global $wpdb,$table_prefix;

// Delete tables from database
$wpdb->query('DROP TABLE IF EXISTS `'.$table_prefix.'notify_post_logs`');

// Delete Options
delete_option('wpnotify_db_version');
delete_option('wpnotify_options');