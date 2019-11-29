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
    use UH\FUNCTIONS\Wp_functions;
    use UH\VALIDATIONS\Wp_validations;

    class Wp_profile
    {
        use Wp_validations;
        use Wp_functions;

        public static $instance;
        private $defaults = [];

        /**
         * Initialize the class and set its properties.
         *
         * @since    1.0.0
         */
        public function __construct()
        {
            self::$instance = $this;
            add_action('init', array($this, 'profile_post_type'));
            add_action('wp_ajax_'.$this->plugin_key().'_update_profile_ajax', array($this, 'update_profile_ajax_callback'));
            add_action('wp_ajax_'.$this->plugin_key().'_update_avatar_ajax', array($this, 'update_avatar_ajax_callback'));
            add_action('wp_ajax_'.$this->plugin_key().'_profile_search_ajax', array($this, 'profile_search_ajax_callback'));
            add_action('wp_ajax_nopriv_'.$this->plugin_key().'_profile_search_ajax', array($this, 'profile_search_ajax_callback'));
        }

        public static function get_instance()
        {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function profile_post_type()
        {

            $labels = array(
                "name"          => __("Profiles", 'wp_users_handler'),
                "singular_name" => __("Profile", 'wp_users_handler'),
            );

            $args = array(
                "label"                 => __("Profiles", 'wp_users_handler'),
                "labels"                => $labels,
                "description"           => "",
                "public"                => true,
                "publicly_queryable"    => true,
                "show_ui"               => true,
                "delete_with_user"      => false,
                "show_in_rest"          => true,
                "rest_base"             => "",
                "rest_controller_class" => "WP_REST_Posts_Controller",
                "has_archive"           => true,
                "show_in_menu"          => true,
                "show_in_nav_menus"     => true,
                "exclude_from_search"   => false,
                "capability_type"       => "post",
                "map_meta_cap"          => true,
                "hierarchical"          => true,
                "rewrite"               => array("slug" => "profile", "with_front" => true),
                "query_var"             => true,
                "supports"              => array("title", "editor", "thumbnail", "excerpt", "trackbacks", "custom-fields", "comments", "revisions", "author", "page-attributes", "post-formats"),
            );

            register_post_type("profile", $args);

        }

        public function update_profile_ajax_callback()
        {

        }

        public function update_avatar_ajax_callback()
        {

        }

        public function profile_search_ajax_callback()
        {

        }

        public function convert()
        {

        }
    }

    new Wp_profile();
