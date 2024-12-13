<tr valign="top">
    <th scope="row">
        متن پیامک ثبت نام کاربر جدید : </th>
    <td>
        <textarea name="new_user_message" id="new_user_message" cols="30" rows="10"><?php echo isset($options['message']['new_user_message']) ? $options['message']['new_user_message'] : '' ?></textarea>
        <p>
            <span>نام کاربری :</span>
            <span>{username}</span>
        </p>
    </td>
</tr>
<tr valign="top">
    <th scope="row">متن پیامک انتشار مطلب نویسنده : </th>
    <td>
        <textarea name="new_post_publish" id="new_post_publish" cols="30" rows="10"><?php echo isset($options['message']['new_post_publish']) ? $options['message']['new_post_publish'] : '' ?></textarea>
        <p>
            <span>عنوان مطلب :</span>
            <span>{post_title}</span>
        </p>
    </td>
</tr>