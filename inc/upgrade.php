<?php

global $wpdb, $table_prefix;

$wpnotify_log_table = 'CREATE TABLE IF NOT EXISTS `' . $table_prefix . 'notify_logs` (
    `ID` bigint(20) AUTO_INCREMENT PRIMARY KEY,
    `user_id` bigint(20),
    `post_id` bigint(20),
    `msg` text,
    `old_status` varchar(64),
    `new_status` varchar(64),
    `date` datetime NOT NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_post_id (post_id),
    INDEX idx_date (date)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

$wpnotify_db_version = get_option('wpnotify_db_version');

if (intval($wpnotify_db_version) != WPNOTIFY_DB_VERSION) {

  dbDelta($wpnotify_log_table);
  update_option('wpnotify_db_version', WPNOTIFY_DB_VERSION);

  $options['general']['wpnotify_is_active'] = 0;
  $options['message']['new_user_message'] = null;
  $options['message']['new_post_publish'] = null;

  update_option('wpnotify_options', $options);
}
