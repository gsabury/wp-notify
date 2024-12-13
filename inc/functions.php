<?php
// Custom hook when content of message of new post publish is changed *
add_action('wpnotify_post_publish_data_updated', 'wpnotify_post_publish_update_callback', 1, 3);
function wpnotify_post_publish_update_callback($new_value, $old_value, $user_id)
{
    $data['post_id'] = null;
    $data['user_id'] = $user_id;
    $data['old_value'] = $old_value;
    $data['new_value'] = $new_value;
    $data['msg'] = "متن پیامک انتشار مطلب ویرایش گردید";
    wpnotify_insert_data($data);
}

// Fires immediately after a new user is registered *
add_action('user_register', 'wpnotify_user_register', 10, 1);
function wpnotify_user_register($user_id)
{
    $user_mobile_number = get_user_meta($user_id, 'mobile_number', TRUE);
    $user_email = get_the_author_meta('user_email', $user_id);
    $user_data = new WP_User($user_id);
    $options = get_option('wpnotify_options');

    $new_user_message = isset($options['message']['new_user_message']) && !empty($options['message']['new_user_message']) ? $options['message']['new_user_message'] : "";

    if (empty($new_user_message)) {
        return FALSE;
    }

    $replace_data = array("{username}");
    $raw_data = array($user_data->user_login);

    $final_msg = str_replace($replace_data, $raw_data, $new_user_message);

    // wpnotify_send_sms(array('to' => $user_mobile_number, 'msg' => $final_msg));
    // wpnotify_send_email(array("to" => $user_email, 'subject' => "نشرمطلب جدید", 'message' => $final_msg));

    $data['post_id'] = null;
    $data['user_id'] = $user_id;
    $data['old_value'] = null;
    $data['new_value'] = null;
    $data['msg'] = $final_msg;
    wpnotify_insert_data($data);
}

// Fires when a post is transitioned from one status to another. *
add_action('publish_post', 'wpnotify_publish_post', 10, 2);
function wpnotify_publish_post($ID, $post)
{
    /*
    $post_title = $post->post_title;
    $post_author = $post->post_author;

    $user_mobile_number = get_user_meta($post_author, 'mobile_number', TRUE);
    $user_email = get_the_author_meta('user_email', $post_author);
    $options = get_option('wpnotify_options');
    $post_publish_message = $options['message']['new_post_publish'];

    $replace_data = array("{post_title}");
    $raw_data = array($post_title);

    $final_msg = str_replace($replace_data, $raw_data, $post_publish_message);

    // wpnotify_send_sms(array('to' => $user_mobile_number, 'msg' => $final_msg));
    // wpnotify_send_email(array("to" => $user_email, 'subject' => "نشرمطلب جدید", 'message' => $final_msg));

    $data['post_id'] = $ID;
    $data['user_id'] = $post_author;
    $data['old_value'] = null;
    $data['new_value'] = null;
    $data['msg'] = $final_msg;
    wpnotify_insert_data($data);
    */
}

// Fires immediately after a comment is inserted into the database.
add_action('comment_post', 'wpnotify_comment_post', 10, 2);
function wpnotify_comment_post($comment_ID, $comment_approved)
{
    $comment = get_comment($comment_ID);
    $comment_post_ID = $comment->comment_post_ID;
    $post = get_post($comment_post_ID);

    $post_title = $post->post_title;
    $post_author = $post->post_author;

    $message = "کامنت جدید برای مطلبی با عنوان";
    $message .= ' ' . $post_title . ' ';
    $message .= "ایجاد گردید";

    $data['post_id'] = $comment_post_ID;
    $data['user_id'] = $post_author;
    $data['old_value'] = null;
    $data['new_value'] = null;
    $data['msg'] = $message;
    wpnotify_insert_data($data);
}

// Fires immediately after transitioning a comment’s status from one to another in the database *
add_action('wp_set_comment_status', 'wpnotify_update_comment_status', 10, 2);
function wpnotify_update_comment_status($comment_id, $new_status)
{
    $comment = get_comment($comment_id);
    $author_email = $comment->comment_author_email;
    $comment_post_ID = $comment->comment_post_ID;
    $comment_author_ID  = $comment->user_id;
  
    $post = get_post($comment_post_ID);
    $post_title = $post->post_title;

    if ($new_status === 'approve') {
        $message = "کامنت شما زیر مطلبی با عنوان";
        $message .= ' ' . $post_title . ' ';
        $message .= "توسط ادمین تائید گردید";
    } elseif ($new_status === 'hold') {
        $message = "کامنت شما زیر مطلبی با عنوان";
        $message .= ' ' . $post_title . ' ';
        $message .= "توسط ادمین در حالت انتظار قرار گرفت";
    } elseif ($new_status === 'trash') {
        $message = "کامنت شما زیر مطلبی با عنوان";
        $message .= ' ' . $post_title . ' ';
        $message .= "توسط ادمین حذف گردید";
    } else {
        return;
    }

    $data['post_id'] = $comment_post_ID;
    $data['user_id'] = $comment_author_ID;
    $data['old_value'] = null;
    $data['new_value'] = null;
    $data['msg'] = $message;
    wpnotify_insert_data($data);
}

// Fires when a post is transitioned from one status to another. *
add_action('transition_post_status', 'on_all_status_transitions', 1, 3);
function on_all_status_transitions($new_status, $old_status, $post)
{
    if ($old_status == $new_status || $old_status != 'publish' && $new_status != 'publish')
        return;

    $post_title = $post->post_title;
    $post_author = $post->post_author;
    $post_id = $post->ID;

    $options = get_option('wpnotify_options');
    $post_publish_message = $options['message']['new_post_publish'];

    $replace_data = array("{post_title}");
    $raw_data = array($post_title);

    $final_msg = str_replace($replace_data, $raw_data, $post_publish_message);

    $user_mobile_number = get_user_meta($post_author, 'mobile_number', TRUE);
    $user_email = get_the_author_meta('user_email', $post_author);

    // wpnotify_send_sms(array('to' => $user_mobile_number, 'msg' => $final_msg));
    // wpnotify_send_email(array("to" => $user_email, 'subject' => "نشرمطلب جدید", 'message' => $final_msg));

    $data['post_id'] = $post_id;
    $data['user_id'] = $post_author;
    $data['old_value'] = $old_status;
    $data['new_value'] = $new_status;
    $data['msg'] = $final_msg;
    wpnotify_insert_data($data);
}

// Send Email *
function wpnotify_send_email($params = array())
{
    $headers = array();
    // $headers[] = 'From: yaransoft.com <info@yaransoft.com>';
    // $headers[] = 'Content-Type: text/html; charset=UTF-8';
    wp_mail($params['to'], $params['subject'], $params['message'], $headers);
}

// Send SMS *
function wpnotify_send_sms($params = array())
{
    !class_exists('farapayamak') ? require_once WPNOT_LIBS . 'farapayamak.class.php' : NULL;
    $fp = new farapayamak();
    $fp->user = "5689452";
    $fp->pass = "6546554";
    $fp->from = "100020003000";
    $fp->to = $params['to'];
    $fp->msg = $params['msg'];
    $fp->send_sms();
}

// Handle Data Insertion *
function wpnotify_insert_data($data)
{
    extract($data);
    global $wpdb, $table_prefix;
    $wpdb->insert(
        $table_prefix . 'notify_logs',
        array(
            'post_id'    => $post_id,
            'user_id'    => $user_id,
            'old_status' => $old_value,
            'new_status' => $new_value,
            'date'       => date('Y-m-d H:i:s'),
            'msg'       => $msg,
        ),
        array(
            '%d',
            '%d',
            '%s',
            '%s',
            '%s',
            '%s'
        )
    );
}
