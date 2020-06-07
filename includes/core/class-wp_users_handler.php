<?php
    /**
     * The file that defines the core plugin class
     *
     * A class definition that includes attributes and functions used across both the
     * public-facing side of the site and the admin area.
     *
     * @link       https://www.linkedin.com/in/mustafa-shaaban22/
     * @since      1.0.0
     *
     * @package    Wp_users_handler
     * @subpackage Wp_users_handler/includes
     */

    /**
     * The core plugin class.
     *
     * This is used to define internationalization, admin-specific hooks, and
     * public-facing site hooks.
     *
     * Also maintains the unique identifier of this plugin as well as the current
     * version of the plugin.
     *
     * @since      1.0.0
     * @package    Wp_users_handler
     * @subpackage Wp_users_handler/includes
     * @author     Mustafa Shaaban <mustafashaaban22@gmail.com>
     */

    namespace UH\HANDLER;

    use UH\LOADER\Wp_users_handler_Loader;
    use UH\TEXT_DOMAIN\Wp_users_handler_i18n;
    use UH\ADMIN\MAIN\Wp_users_handler_Admin;
    use UH\FRONT\MAIN\Wp_users_handler_Public;
    use UH\FUNCTIONS\Wp_functions;

    class Wp_users_handler
    {
        use Wp_functions;

        /**
         * The loader that's responsible for maintaining and registering all hooks that power
         * the plugin.
         *
         * @since    1.0.0
         * @access   protected
         * @var      Wp_users_handler_Loader $loader Maintains and registers all hooks for the plugin.
         */
        protected $loader;

        public static $instance;

        /**
         * Define the core functionality of the plugin.
         *
         * Set the plugin name and the plugin version that can be used throughout the plugin.
         * Load the dependencies, define the locale, and set the hooks for the admin area and
         * the public-facing side of the site.
         *
         * @since    1.0.0
         */
        public function __construct()
        {
            self::$instance = $this;

            $this->load_dependencies();
            $this->set_locale();
            $this->define_admin_hooks();
            $this->define_public_hooks();
            $this->loader->add_action('template_redirect', $this, 'restrict_pages');
        }

        public static function get_instance()
        {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Load the required dependencies for this plugin.
         *
         * Include the core files that make up the plugin:
         *
         * - Wp_users_handler_Loader. Orchestrates the hooks of the plugin.
         * - Wp_users_handler_i18n. Defines internationalization functionality.
         * - Wp_users_handler_Admin. Defines all hooks for the admin area.
         * - Wp_users_handler_Public. Defines all hooks for the public side of the site.
         *
         *
         * Include the helper files:
         *
         * - wp_cryptor
         * - wp_validations
         *
         *
         * Include the modules files:
         *
         * - forms_controller
         * - wp_users
         * - wp_profile
         *
         * Create an instance of the loader which will be used to register the hooks
         * with WordPress.
         *
         * @since    1.0.0
         * @access   private
         */
        private function load_dependencies()
        {

            /**
             * The class responsible for orchestrating the actions and filters of the
             * core plugin.
             */
            require_once $this->plugin_path().'includes/core/class-wp_users_handler_loader.php';

            /**
             * The class responsible for defining internationalization functionality
             * of the plugin.
             */
            require_once $this->plugin_path().'includes/core/class-wp_users_handler_i18n.php';

            /**
             * The class responsible for crypt and decrypt
             * strings, urls and password.
             */
            require_once $this->plugin_path().'includes/helpers/class-wp_cryptor.php';

            require_once $this->plugin_path().'includes/class-wp_mailer.php';

            /**
             * The class responsible for crypt and decrypt
             * strings, urls and password.
             */
            require_once $this->plugin_path().'includes/helpers/class-wp_validations.php';

            /**
             * The class responsible for manage the plugin forms.
             */
            require_once $this->plugin_path().'includes/class-forms_controller.php';

            /**
             * The class responsible for all core users operations.
             */
            require_once $this->plugin_path().'includes/class-wp_users.php';

            /**
             * The class responsible for all core profile operations.
             */
            require_once $this->plugin_path().'includes/class-wp_profile.php';

            /**
             * The class responsible for defining all actions that occur in the admin area.
             */
            require_once $this->plugin_path().'admin/class-wp_users_handler_admin.php';

            /**
             * The class responsible for defining all actions that occur in the public-facing
             * side of the site.
             */
            require_once $this->plugin_path().'public/class-wp_users_handler_public.php';


            $this->loader = new Wp_users_handler_Loader();
        }

        /**
         * Define the locale for this plugin for internationalization.
         *
         * Uses the Wp_users_handler_i18n class in order to set the domain and to register the hook
         * with WordPress.
         *
         * @since    1.0.0
         * @access   private
         */
        private function set_locale()
        {

            $plugin_i18n = new Wp_users_handler_i18n();

            $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

        }

        /**
         * Register all of the hooks related to the admin area functionality
         * of the plugin.
         *
         * @since    1.0.0
         * @access   private
         */
        private function define_admin_hooks()
        {

            $plugin_admin = new Wp_users_handler_Admin($this->plugin_name(), $this->plugin_version(), $this->plugin_path(), $this->plugin_url(), $this->plugin_key());

            $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
            $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        }

        /**
         * Register all of the hooks related to the public-facing functionality
         * of the plugin.
         *
         * @since    1.0.0
         * @access   private
         */
        private function define_public_hooks()
        {

            $plugin_public = new Wp_users_handler_Public($this->plugin_name(), $this->plugin_version(), $this->plugin_path(), $this->plugin_url(), $this->plugin_key());

            $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
            $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        }

        /**
         * Run the loader to execute all of the hooks with WordPress.
         *
         * @since    1.0.0
         */
        public function run()
        {
            $this->loader->run();
        }

        /**
         * The reference to the class that orchestrates the hooks with the plugin.
         *
         * @since     1.0.0
         * @return    Wp_users_handler_Loader    Orchestrates the hooks of the plugin.
         */
        public function get_loader()
        {
            return $this->loader;
        }

        public function restrict_pages(){
        	if(is_user_logged_in() &&
	           (is_page('uh-account') ||
	            is_page('uh-account/login') ||
	            is_page('uh-account/register') ||
	            is_page('uh-account/forgot-password'))) {
		        wp_redirect(home_url());
		        exit();
	        }

        	if(!is_user_logged_in() &&
	           (is_page('uh-my-account') ||
	            is_page('uh-my-account/profile') ||
	            is_page('uh-my-account/register') ||
	            is_page('uh-my-account/forgot-password'))) {
		        wp_redirect(home_url());
		        exit();
	        }

        }
    }