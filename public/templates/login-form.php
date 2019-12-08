<?php


    if (is_user_logged_in()) {
        return;
    }

    use UH\FORMS\Forms_controller;

    $form_obj = Forms_controller::get_instance();
?>

<div class="<?= $this->plugin_key() ?>-login-form">

    <?= do_action($this->plugin_key().'_before_login_form_start'); ?>

    <?php $form_obj->form_start(['id' => $this->plugin_key().'_login_form']); ?>

    <?= do_action($this->plugin_key().'_before_login_form_fields'); ?>

    <?php $form_obj->create_form($this->login_form); ?>

    <?= do_action($this->plugin_key().'_after_login_form_fields'); ?>

    <?php $form_obj->create_none('nonce', $this->plugin_key()."_LOGIN_FORM"); ?>

    <?php $form_obj->form_submit_button(['value' => 'Log in', 'class' => 'btn-block']); ?>

    <div class="form-bottom">
        <p class="text-center">
            <a href="#" ><?= __('Forgot Password?', 'wp_users_handler') ?></a>
        </p>
        <p class="text-center">
            <?= sprintf(__("Don't have an account? <a href='%s'>Sign up here!</a>", 'wp_users_handler'), '#') ?>
        </p>
    </div>

    <?= do_action($this->plugin_key().'_before_login_form_end'); ?>

    <?php $form_obj->form_end(); ?>

    <?= do_action($this->plugin_key().'_after_login_form_end'); ?>

</div>