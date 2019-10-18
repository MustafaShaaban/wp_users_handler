<?php

    /**
     * Class Users_Public
     *
     * Login Form
     * Register Form
     * Reset Password step 1 Form
     * Reset Password step 2 Form
     * Account Form
     *
     */

    namespace UH\FRONT\USERS;

    class Users_Public
    {
        public static $instance;

        public function __construct()
        {
            self::$instance   = $this;
            add_shortcode(PLUGIN_KEY.'_login_form', array($this, 'create_login_form'));
            add_shortcode(PLUGIN_KEY.'_registration_form', array($this, 'create_registration_form'));
            add_shortcode(PLUGIN_KEY.'_rp_step1_form', array($this, 'create_rp_step1_form'));
            add_shortcode(PLUGIN_KEY.'_rp_step2_form', array($this, 'create_rp_step2_form'));
            add_shortcode(PLUGIN_KEY.'_account_setting_form', array($this, 'create_account_setting_form'));
        }

        public static function get_instance()
        {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function create_login_form()
        {
            ob_start();
            ?>
            <form action="">
                <input type="text" class="Hoba">
                <input type="text" class="Hoba">
                <input type="text" class="Hoba">
            </form>
            <?php
            return ob_get_clean();
        }

        public function create_registration_form()
        {

        }

        public function create_rp_step1_form()
        {

        }

        public function create_rp_step2_form()
        {

        }

        public function create_account_setting_form()
        {

        }


    }

    new Users_Public();