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

        private $register_form;

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
                    'label'        => __('E-mail or username', 'wp_users_handler'),
                    'name'         => 'user_login',
                    'required'     => 'required',
                    'placeholder'  => __('E-mail or username', 'wp_users_handler'),
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
	        $this->register_form = [
		        'first_name'    => [
			        'type'         => 'text',
			        'label'        => __('First Name', 'wp_users_handler'),
			        'name'         => 'first_name',
			        'required'     => 'required',
			        'placeholder'  => __('First Name', 'wp_users_handler'),
			        'autocomplete' => 'off',
			        'order'        => 5
		        ],
		        'last_name'    => [
			        'type'         => 'text',
			        'label'        => __('Last Name', 'wp_users_handler'),
			        'name'         => 'last_name',
			        'required'     => 'required',
			        'placeholder'  => __('Last Name', 'wp_users_handler'),
			        'autocomplete' => 'off',
			        'order'        => 10
		        ],
		        'user_password' => [
			        'type'         => 'password',
			        'label'        => __('Password', 'wp_users_handler'),
			        'name'         => 'user_password',
			        'required'     => 'required',
			        'placeholder'  => __('Password', 'wp_users_handler'),
			        'autocomplete' => 'off',
			        'order'        => 15
		        ],
		        'user_password_confirm' => [
			        'type'         => 'password',
			        'label'        => __('Confirm Password', 'wp_users_handler'),
			        'name'         => 'user_password_confirm',
			        'required'     => 'required',
			        'placeholder'  => __('Confirm Password', 'wp_users_handler'),
			        'autocomplete' => 'off',
			        'order'        => 20
		        ],
		        'user_email'    => [
			        'type'         => 'email',
			        'label'        => __('E-mail Address', 'wp_users_handler'),
			        'name'         => 'user_email',
			        'required'     => 'required',
			        'placeholder'  => __('E-mail Address', 'wp_users_handler'),
			        'autocomplete' => 'off',
			        'order'        => 25
		        ],
		        'user_email_confirm'    => [
			        'type'         => 'email',
			        'label'        => __('Confirm E-mail Address', 'wp_users_handler'),
			        'name'         => 'user_email_confirm',
			        'required'     => 'required',
			        'placeholder'  => __('Confirm E-mail Address', 'wp_users_handler'),
			        'autocomplete' => 'off',
			        'order'        => 30
		        ],
	        ];

	        ob_start();
	        $this->get_public_template('register-form');
	        return ob_get_clean();
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