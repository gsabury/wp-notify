<?php

defined( 'ABSPATH' ) || exit( 'NO ACCESS' );

add_action('admin_menu','wpnot_admin_menu_init');

function wpnot_admin_menu_init(){
    add_menu_page(
            'اعلانات',
            'اعلانات وردپرس',
            'manage_options',
            'wp_notify',
            'wp_notify_page'
        );
}
