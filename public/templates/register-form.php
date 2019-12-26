<?php


    if (is_user_logged_in()) {
	    $this->page_404();
    }

    use UH\FORMS\Forms_controller;

    $form_obj = Forms_controller::get_instance();
?>

<div class="<?= $this->plugin_key() ?>-register-form">

    <?= do_action($this->plugin_key().'_before_register_form_start'); ?>

    <?php $form_obj->form_start(['id' => $this->plugin_key().'_register_form']); ?>

    <?= do_action($this->plugin_key().'_before_register_form_fields'); ?>

    <?php $form_obj->create_form($this->register_form); ?>

    <?= do_action($this->plugin_key().'_after_register_form_fields'); ?>

    <?php $form_obj->create_none('nonce', $this->plugin_key()."_LOGIN_FORM"); ?>

    <?php $form_obj->form_submit_button(['value' => 'Sign Up', 'class' => 'btn-block']); ?>

    <div class="form-bottom">
        <p class="text-center">
            <?= sprintf(__("Already have an account? <a href='%s'>Login</a>", 'wp_users_handler'), $this->get_page_url('uh-account/login') ) ?>
        </p>
    </div>

    <?= do_action($this->plugin_key().'_before_register_form_end'); ?>

    <?php $form_obj->form_end(); ?>

    <?= do_action($this->plugin_key().'_after_register_form_end'); ?>

</div>