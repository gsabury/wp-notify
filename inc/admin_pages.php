<?php

function wp_notify_page()
{

    $current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';

    $tabs =  array(
        'general' => 'عمومی',
        'message' => 'اطلاع رسانی',
    );

    $options = get_option('wpnotify_options');

    if (isset($_POST['submit'])) {

        if (!empty($_POST['new_post_publish'])) {

            if (abs(strcmp($options['message']['new_post_publish'], $_POST['new_post_publish']))) {
                $currentUser = wp_get_current_user();
                do_action(
                    'wpnotify_post_publish_data_updated',
                    $_POST['new_post_publish'],
                    $options['message']['new_post_publish'],
                    $currentUser->ID
                );
            }
        }

        isset($_POST['wpnotify_is_active']) ? $options['general']['wpnotify_is_active'] = 1 : $options['general']['wpnotify_is_active'] = 0;
        !empty($_POST['new_user_message']) ? $options['message']['new_user_message'] = sanitize_text_field($_POST['new_user_message']) : null;
        !empty($_POST['new_post_publish']) ? $options['message']['new_post_publish'] = sanitize_text_field($_POST['new_post_publish']) : null;
    }

    update_option('wpnotify_options', $options);

    $options = get_option('wpnotify_options');

    include WPNOT_TPL . 'wp_notity_main.php';
}
