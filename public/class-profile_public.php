<?php

    /**
     * Class Profile_Public
     *
     * Profile Form
     * Profile View
     * Avatar Form
     * Avatar View
     * All Profiles View
     * Search View
     *
     */
    namespace UH\FRONT\PROFILE;

    class Profile_Public
    {
        public static $instance;

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
    new Profile_Public();
    
