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

    use UH\HANDLER\Wp_users_handler;

    class Users_Public
    {
        public static $instance;

        public function __construct()
        {
            self::$instance   = $this;
            $loader = Wp_users_handler::get_instance()->get_loader();

            add_shortcode(PLUGIN_KEY.'_login_form', array($this, 'create_login_form'));
            add_shortcode(PLUGIN_KEY.'_registration_form', array($this, 'create_registration_form'));
            add_shortcode(PLUGIN_KEY.'_rp_step1_form', array($this, 'create_rp_step1_form'));
            add_shortcode(PLUGIN_KEY.'_rp_step2_form', array($this, 'create_rp_step2_form'));
            add_shortcode(PLUGIN_KEY.'_account_setting_form', array($this, 'create_account_setting_form'));
            $loader->add_action(PLUGIN_KEY.'_create_hidden_inputs', $this, 'create_hidden_inputs', 10, 1);
            $loader->add_action(PLUGIN_KEY.'_create_nonce', $this,  'create_none', 10, 2);
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
            do_action('UH_create_nonce', 'nonce', PLUGIN_KEY."_LOGIN_FORM");
            ?>
            <form action="">
                <input type="text" name="user_login">
                <input type="password" name="password">
                <input type="password" name="password">
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

        public function create_hidden_inputs($args = [])
        {
            ob_start();
            $inputs = '';
            if (!empty($args)) {
                foreach ($args as $input) {
                    $name   = $input['name'];
                    $value  = $input['value'];
                    $inputs .= "<input type='hidden' name='$name' value='$value'/>";
                }
                $inputs .= ob_get_clean();
            }
            echo $inputs;
        }

        public function create_none($name, $value)
        {
            return wp_nonce_field($value, $name);
        }
    }

    new Users_Public();