<div class="wrap">
    <h2>مدیریت اعلانات وردپرس</h2>
    <h2 class="nav-tab-wrapper">
        <?php foreach ($tabs as $name => $title): ?>
            <?php $class = $name == $current_tab ? 'nav-tab-active' : '';  ?>
            <a class="nav-tab <?php echo $class; ?>" href="<?php echo  add_query_arg(array('tab' => $name)); ?>"><?php echo $title; ?></a>
        <?php endforeach;  ?>
    </h2>
    <form action="" method="POST">
        <table class="form-table">
            <?php
            if (in_array($current_tab, array_keys($tabs)))
                include WPNOT_TPL . $current_tab . '.php';
            ?>
        </table>
        <?php submit_button('ذخیره کردن تغییرات'); ?>

    </form>
</div>