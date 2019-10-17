<?php

    /**
     * Class Profile
     *
     * Update Profile (First name, Last Name, Phone Number, Address, Bio, Email Notifications, App Notifications, Default Language)
     * update avatar (Profile Picture)
     * search
     * Convert
     * Register post_type
     *
     */

    class Profile
    {
        public static $instance;
        private $plugin_key;
        private $defaults = [];

        public function __construct()
        {
            self::$instance = $this;
            $this->plugin_key = 'APP';
            $this->defaults = [
                '' => ''
            ];

            add_action('wp_ajax_nopriv_'.$this->plugin_key.'_login_ajax', array($this,'login_ajax_callback'));

        }

        public static function get_instance()
        {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

    }
