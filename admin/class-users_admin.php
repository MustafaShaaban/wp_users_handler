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
        }

        public static function get_instance()
        {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

    }
