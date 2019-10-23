<?php
    /**
     * The public-facing functionality of the plugin.
     * Defines the plugin name, version, and two examples hooks for how to
     * enqueue the public-facing stylesheet and JavaScript.
     *
     * @link       https://www.linkedin.com/in/mustafa-shaaban22/
     * @since      1.0.0
     *
     * @package    Wp_users_handler
     * @subpackage Wp_users_handler/public
     * @author     Mustafa Shaaban <mustafashaaban22@gmail.com>
     */

    namespace UH\FRONT\MAIN;

    class Wp_users_handler_Public
    {

        /**
         * The ID of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $plugin_name The ID of this plugin.
         */
        private $plugin_name;

        /**
         * The path of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $plugin_path The path of this plugin.
         */
        private $plugin_path;

        /**
         * The url of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $plugin_url The url of this plugin.
         */
        private $plugin_url;

        /**
         * The convention key of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $plugin_key The convention key of this plugin.
         */
        private $plugin_key;

        /**
         * The version of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $version The current version of this plugin.
         */
        private $version;

        /**
         * The public CSS Folder Path.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $css The CSS folder path.
         */
        private $css;

        /**
         * The public JS Folder Path.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $js The JS folder path.
         */
        private $js;

        /**
         * The public Img Folder Path.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $img The Img folder path.
         */
        private $img;

        /**
         * Initialize the class and set its properties.
         *
         * @since    1.0.0
         *
         * @param      string $plugin_name The name of the plugin.
         * @param      string $plugin_version The version of this plugin.
         * @param      string $plugin_path The path of this plugin.
         * @param      string $plugin_url The url of this plugin.
         * @param      string $plugin_key The convention key of this plugin.
         */
        public function __construct($plugin_name, $plugin_version, $plugin_path, $plugin_url, $plugin_key)
        {
            $this->plugin_name = $plugin_name;
            $this->version     = $plugin_version;
            $this->plugin_path = $plugin_path;
            $this->plugin_url  = $plugin_url;
            $this->plugin_key  = $plugin_key;
            $this->css         = $this->plugin_url.'public/css/';
            $this->js          = $this->plugin_url.'public/js/';
            $this->img         = $this->plugin_url.'public/img/';

            $this->require_files();

        }

        /**
         * This function responsible for include required public facing classes
         */
        private function require_files()
        {
            require_once $this->plugin_path.'public/class-users_public.php';
            require_once $this->plugin_path.'public/class-profile_public.php';
        }

        /**
         * Register the stylesheets for the public-facing side of the site.
         *
         * @since    1.0.0
         */
        public function enqueue_styles()
        {

            /**
             * This function is provided for demonstration purposes only.
             *
             * An instance of this class should be passed to the run() function
             * defined in Wp_users_handler_Loader as all of the hooks are defined
             * in that particular class.
             *
             * The Wp_users_handler_Loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */

            wp_enqueue_style($this->plugin_name.'-bootstrap', $this->css.'bootstrap.min.css', array(), $this->version, 'all');
            wp_enqueue_style($this->plugin_name.'-loading', $this->css.'pl-loading.css', array(), $this->version, 'all');
            wp_enqueue_style($this->plugin_name, $this->css.'wp_users_handler-public.css', array(), $this->version, 'all');
        }

        /**
         * Register the JavaScript for the public-facing side of the site.
         *
         * @since    1.0.0
         */
        public function enqueue_scripts()
        {

            /**
             * This function is provided for demonstration purposes only.
             *
             * An instance of this class should be passed to the run() function
             * defined in Wp_users_handler_Loader as all of the hooks are defined
             * in that particular class.
             *
             * The Wp_users_handler_Loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */

            wp_enqueue_script($this->plugin_name.'-validation', $this->js.'jquery.validate.min.js', array('jquery'), $this->version, true);
            wp_enqueue_script($this->plugin_name.'-validation-methods', $this->js.'additional-methods.min.js', array('jquery', 'validation'), $this->version, true);
            wp_enqueue_script($this->plugin_name.'-functions', $this->js.'pl-functions.js', array('jquery'), $this->version, true);
            wp_enqueue_script($this->plugin_name.'-bootstrap', $this->js.'bootstrap.min.js', array('jquery'), $this->version, true);
            wp_enqueue_script($this->plugin_name, $this->js.'wp_users_handler-public.js', array('jquery'), $this->version, true);
            wp_localize_script($this->plugin_name, 'pl_globals', array(
                'pl_key'  => $this->plugin_key,
                'ajaxUrl' => admin_url('admin-ajax.php')
            ));
            wp_localize_script($this->plugin_name, 'pl_phrases', array(
                'default'     => __("This field is required.", "wp_users_handler"),
                'email'       => __("Please enter a valid email address.", "wp_users_handler"),
                'number'      => __("Please enter a valid number.", "wp_users_handler"),
                'equalTo'     => __("Please enter the same value again.", "wp_users_handler"),
                'maxlength'   => __("Please enter no more than {0} characters.", "wp_users_handler"),
                'minLength'   => __("Please enter at least {0} characters.", "wp_users_handler"),
                'max'         => __("Please enter a value less than or equal to {0}.", "wp_users_handler"),
                'min'         => __("Please enter a value greater than or equal to {0}.", "wp_users_handler"),
                'pass_regex'  => __("Password doesn't complexity.", "wp_users_handler"),
                'phone_regex' => __("Please enter a valid Phone number.", "wp_users_handler"),
                'email_regex' => __("Please enter a valid email address.", "wp_users_handler"),
            ));
        }

    }
