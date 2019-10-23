<?php


    if (is_user_logged_in()) {
        return;
    }

    use UH\FORMS\Forms_controller;

    $form_obj = Forms_controller::get_instance();
?>

<div class="pl-login-form">

    <?= do_action(PLUGIN_KEY.'_before_login_form_start'); ?>

    <?php $form_obj->form_start(['id' => PLUGIN_KEY.'_login_form']); ?>

    <?= do_action(PLUGIN_KEY.'_before_login_form_fields'); ?>

    <?php $form_obj->create_form($this->login_form); ?>

    <?= do_action(PLUGIN_KEY.'_after_login_form_fields'); ?>

    <?php $form_obj->create_none('nonce', PLUGIN_KEY."_LOGIN_FORM"); ?>

    <?php $form_obj->form_submit_button(['value' => 'Log in', 'class' => 'btn-block']); ?>

    <p class="text-center">
        <a href="#" class="pull-right"><?= __('Forgot Password?', 'wp_users_handler') ?></a>
    </p>
    <p class="text-center">
        <?= sprintf(__("Don't have an account? <a href='%s'>Sign up here!</a>", 'wp_users_handler'), '#') ?>
    </p>

    <?= do_action(PLUGIN_KEY.'_before_login_form_end'); ?>

    <?php $form_obj->form_end(); ?>

    <?= do_action(PLUGIN_KEY.'_after_login_form_end'); ?>

</div>