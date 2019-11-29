<?php

    /**
     * The plugin bootstrap file
     *
     * This file is read by WordPress to generate the plugin information in the plugin
     * admin area. This file also includes all of the dependencies used by the plugin,
     * registers the activation and deactivation functions, and defines a function
     * that starts the plugin.
     *
     * @link              https://www.linkedin.com/in/mustafa-shaaban22/
     * @since             1.0.0
     * @package           Wp_users_handler
     *
     * @wordpress-plugin
     * Plugin Name:       WP Users Handler
     * Plugin URI:        https://github.com/MustafaShaaban/wp_users_handler
     * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
     * Version:           1.0.0
     * Author:            Mustafa Shaaban
     * Author URI:        https://www.linkedin.com/in/mustafa-shaaban22/
     * License:           GPL-2.0+
     * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
     * Text Domain:       wp_users_handler
     * Domain Path:       /languages
     */

    namespace UH;

    use UH\ACTIVATOR\Wp_users_handler_Activator;
    use UH\DEACTIVATOR\Wp_users_handler_Deactivator;
    use UH\HANDLER\Wp_users_handler;

    // If this file is called directly, abort.
    if (!defined('WPINC')) {
        die;
    }


    /**
     * Defining the plugin Title.
     */
    define('WP_USERS_HANDLER_PLUGIN_TITLE', 'WP Users Handler');

    /**
     * Defining the plugin name.
     */
    define('WP_USERS_HANDLER_PLUGIN_NAME', 'wp_users_handler');

    /**
     * Define the plugins' main directory path
     */
    define('WP_USERS_HANDLER_PLUGIN_PATH', plugin_dir_path(__FILE__));

    /**
     * Define the plugins' main URL path
     */
    define('WP_USERS_HANDLER_PLUGIN_URL', plugin_dir_url(__FILE__));

    /**
     * Defining the plugin convention key
     */
    define('WP_USERS_HANDLER_PLUGIN_KEY', 'UH');

    /**
     * Currently plugin version.
     * Start at version 1.0.0 and use SemVer - https://semver.org
     * Rename this for your plugin and update it as you release new versions.
     */
    define('WP_USERS_HANDLER_VERSION', '1.0.0');

    class Wp_users_handler_basic
    {
        /**
         * Define the basic functionality of the plugin.
         *
         * instantiate the activator, deactivator classes and include the core class.
         *
         * @since    1.0.0
         */
        public function __construct()
        {
            $this->require_files();
            register_activation_hook(__FILE__, array($this, 'activate_wp_users_handler'));
            register_deactivation_hook(__FILE__, array($this, 'deactivate_wp_users_handler'));
            $this->run_wp_users_handler();
        }

        /**
         * The function responsible for include core class files
         */
        private function require_files()
        {
            /**
             * The class responsible for global functions
             */
            require_once WP_USERS_HANDLER_PLUGIN_PATH.'includes/class-wp_functions.php';

            /**
             * The class responsible for defining all code necessary
             * to run during the plugin's activation.
             */
            require_once WP_USERS_HANDLER_PLUGIN_PATH.'includes/core/class-wp_users_handler_activator.php';

            /**
             * The class responsible for defining all code necessary
             * to run during the plugin's deactivation.
             */
            require_once WP_USERS_HANDLER_PLUGIN_PATH.'includes/core/class-wp_users_handler_deactivator.php';

            /**
             * The core plugin class that is used to define internationalization,
             * admin-specific hooks, and public-facing site hooks.
             */
            require WP_USERS_HANDLER_PLUGIN_PATH.'includes/core/class-wp_users_handler.php';
        }

        /**
         * The code that runs during plugin activation.
         * This action is documented in core/class-wp_users_handler_activator.php
         */
        public function activate_wp_users_handler()
        {
            Wp_users_handler_Activator::activate();
        }

        /**
         * The code that runs during plugin deactivation.
         * This action is documented in core/class-wp_users_handler_deactivator.php
         */
        public function deactivate_wp_users_handler()
        {
            Wp_users_handler_Deactivator::deactivate();
        }

        /**
         * Begins execution of the plugin.
         *
         * Since everything within the plugin is registered via hooks,
         * then kicking off the plugin from this point in the file does
         * not affect the page life cycle.
         *
         * @since    1.0.0
         */
        private function run_wp_users_handler()
        {
            $plugin = new Wp_users_handler();
            $plugin->run();
        }
    }

    new Wp_users_handler_basic();