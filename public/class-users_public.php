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

    use UH\FUNCTIONS\Wp_functions;

    class Users_Public
    {
        use Wp_functions;

        private $login_form;

        public static $instance;

        /**
         * Initialize the class and set its properties.
         *
         * @since    1.0.0
         *
         */
        public function __construct()
        {
            self::$instance   = $this;

            add_shortcode($this->plugin_key().'_login_form', array($this, 'create_login_form'));
            add_shortcode($this->plugin_key().'_registration_form', array($this, 'create_registration_form'));
            add_shortcode($this->plugin_key().'_rp_step1_form', array($this, 'create_rp_step1_form'));
            add_shortcode($this->plugin_key().'_rp_step2_form', array($this, 'create_rp_step2_form'));
            add_shortcode($this->plugin_key().'_account_setting_form', array($this, 'create_account_setting_form'));

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
            $this->login_form = [
                'user_login'    => [
                    'type'         => 'text',
                    'label'        => __('Email or username', 'wp_users_handler'),
                    'name'         => 'user_login',
                    'required'     => 'required',
                    'placeholder'  => __('Email or username', 'wp_users_handler'),
                    'autocomplete' => 'off',
                    'hint'         => __('You can login by your username or email', 'wp_users_handler'),
                    'order'        => 5
                ],
                'user_password' => [
                    'type'         => 'password',
                    'label'        => __('Password', 'wp_users_handler'),
                    'name'         => 'user_password',
                    'required'     => 'required',
                    'placeholder'  => __('Password', 'wp_users_handler'),
                    'autocomplete' => 'off',
                    'order'        => 10
                ],
                'remember_me'   => [
                    'type'    => 'checkbox',
                    'choices' => [
                        '0' => [
                            'label' => __('Remember me', 'wp_users_handler'),
                            'name'  => 'rememberme[]',
                            'value' => 'forever',
                            'order'        => 5
                        ]
                    ],
                    'order'        => 15
                ]
            ];

            ob_start();
            $this->get_public_template('login-form');
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