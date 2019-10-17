<?php

    /**
     * Class Users_Front
     *
     * Login Form
     * Register Form
     * Reset Password step 1 Form
     * Reset Password step 2 Form
     * Account Form
     *
     */

    class Users_Front
    {
        public static $instance;
        private $plugin_key;

        public function __construct()
        {
            self::$instance = $this;
            $this->plugin_key = 'UH';
            add_shortcode($this->plugin_key.'_login_form', array($this, 'create_login_form'));
            add_shortcode($this->plugin_key.'_registration_form', array($this, 'create_registration_form'));
            add_shortcode($this->plugin_key.'_rp_step1_form', array($this, 'create_rp_step1_form'));
            add_shortcode($this->plugin_key.'_rp_step2_form', array($this, 'create_rp_step2_form'));
            add_shortcode($this->plugin_key.'_account_setting_form', array($this, 'create_account_setting_form'));
        }

        public static function get_instance()
        {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function create_login_form() {
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

        public function create_registration_form() {

        }

        public function create_rp_step1_form() {

        }

        public function create_rp_step2_form() {

        }

        public function create_account_setting_form() {

        }


    }
    new Users_Front();