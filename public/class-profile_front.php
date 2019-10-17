<?php

    /**
     * Class Users_Front
     *
     * Profile Form
     * Profile View
     * Avatar Form
     * Avatar View
     * All Profiles View
     * Search View
     *
     */

    class Profile_Front
    {
        public static $instance;
        private $plugin_key;

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
