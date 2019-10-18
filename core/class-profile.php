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

    namespace UH\PROFILE;

    use UH\CRYPTOR\wp_cryptor;
    use UH\VALIDATIONS\Wp_validations;

    class Profile
    {
        use Wp_validations;

        public static $instance;
        private $defaults = [];

        public function __construct()
        {
            self::$instance = $this;
            $this->defaults = [
                '' => ''
            ];

            add_action('wp_ajax_nopriv_'.PLUGIN_KEY.'_login_ajax', array($this,'login_ajax_callback'));

        }

        public static function get_instance()
        {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

    }

    new Profile();
