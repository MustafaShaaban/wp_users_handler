<?php

    /**
     * Class Users_Admin
     *
     * block/unblock user (with expire date) Admin Area
     * Security (Number of active Accounts, Number of failed login, IP Address for login) Admin Area
     *
     */

    namespace UH\ADMIN\USERS;

    class Users_Admin
    {
        public static $instance;

        /**
         * Initialize the class and set its properties.
         *
         * @since    1.0.0
         */
        public function __construct()
        {
            self::$instance = $this;

            $configurations = new \stdClass();
            $configurations->email_confirmation = 'on';
            $configurations->limit_active_login = 'on';
            $configurations->login_network = 'on';
            $configurations->admin_approval = 'on';

            add_option(PLUGIN_KEY.'_configurations', $configurations);
            add_option(PLUGIN_KEY.'_number_of_active_login', 1);
        }

        public static function get_instance()
        {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

    }

    new Users_Admin();
