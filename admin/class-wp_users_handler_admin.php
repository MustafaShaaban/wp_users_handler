<?php

    /**
     * The admin-specific functionality of the plugin.
     *
     * @link       https://www.linkedin.com/in/mustafa-shaaban22/
     * @since      1.0.0
     *
     * @package    Wp_users_handler
     * @subpackage Wp_users_handler/admin
     */

    /**
     * The admin-specific functionality of the plugin.
     *
     * Defines the plugin name, version, and two examples hooks for how to
     * enqueue the admin-specific stylesheet and JavaScript.
     *
     * @package    Wp_users_handler
     * @subpackage Wp_users_handler/admin
     * @author     Mustafa Shaaban <mustafashaaban22@gmail.com>
     */

    namespace UH\ADMIN\MAIN;

    use UH\FUNCTIONS\Wp_functions;

    class Wp_users_handler_Admin
    {
        use Wp_functions;

        /**
         * The ID of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $plugin_name The ID of this plugin.
         */
        private $plugin_name;

        /**
         * The version of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $version The current version of this plugin.
         */
        private $version;

        /**
         * The admin CSS Folder Path.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $css The CSS folder path.
         */
        private $css;

        /**
         * The admin JS Folder Path.
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
         * @param      string $plugin_name The name of this plugin.
         * @param      string $version The version of this plugin.
         */
        public function __construct($plugin_name, $version)
        {

            $this->plugin_name = $plugin_name;
            $this->version     = $version;
            $this->css         = PLUGIN_URL.'admin/css/';
            $this->js          = PLUGIN_URL.'admin/js/';
            $this->img         = PLUGIN_URL.'admin/img/';

            require_once PLUGIN_PATH.'admin/class-users_admin.php';

        }

        /**
         * Register the stylesheets for the admin area.
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
            if (isset($_GET['page']) && $_GET['page'] === PLUGIN_KEY.'-main-page') {
                wp_enqueue_style($this->plugin_name.'-bootstrap', $this->css.'bootstrap.min.css', array(), $this->version, 'all');
                wp_enqueue_style($this->plugin_name.'-loading', $this->css.'pl-loading.css', array(), $this->version, 'all');
                wp_enqueue_style($this->plugin_name, $this->css.'wp_users_handler-admin.css', array(), $this->version, 'all');
            }

        }

        /**
         * Register the JavaScript for the admin area.
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

            if (isset($_GET['page']) && $_GET['page'] === PLUGIN_KEY.'-main-page') {
                wp_enqueue_script($this->plugin_name.'-bootstrap', $this->js.'bootstrap.min.js', array('jquery'), $this->version, false);
            }
            wp_enqueue_script($this->plugin_name, $this->js.'wp_users_handler-admin.js', array('jquery'), $this->version, false);
            wp_localize_script($this->plugin_name, 'pl_globals', array(
                'ajaxUrl'    => admin_url('admin-ajax.php'),
                'plugin_key' => PLUGIN_KEY
            ));
        }

    }