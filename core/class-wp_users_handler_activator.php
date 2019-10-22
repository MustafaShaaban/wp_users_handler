<?php

    /**
     * Fired during plugin activation
     *
     * @link       https://www.linkedin.com/in/mustafa-shaaban22/
     * @since      1.0.0
     *
     * @package    Wp_users_handler
     * @subpackage Wp_users_handler/includes
     */

    /**
     * Fired during plugin activation.
     *
     * This class defines all code necessary to run during the plugin's activation.
     *
     * @since      1.0.0
     * @package    Wp_users_handler
     * @subpackage Wp_users_handler/includes
     * @author     Mustafa Shaaban <mustafashaaban22@gmail.com>
     */

    namespace UH\ACTIVATOR;

    class Wp_users_handler_Activator
    {

        public function __construct()
        {
            add_action('admin_menu', array($this, 'register_pl_menus'));
        }

        /**
         * Short Description. (use period)
         *
         * Long Description.
         *
         * @since    1.0.0
         */
        public static function activate()
        {
            $configurations                         = new \stdClass();
            $configurations->email_confirmation     = 'on';
            $configurations->limit_active_login     = 'on';
            $configurations->login_network          = 'on';
            $configurations->admin_approval         = 'on';
            $configurations->check_keyup            = 'off';
            $configurations->block_users            = 'off';
            $configurations->number_of_active_login = 1;

            update_option(PLUGIN_KEY.'_configurations', $configurations);
        }

        public function register_pl_menus()
        {
            add_menu_page(__(PLUGIN_NAME, 'wp_users_handler'), __(PLUGIN_NAME, 'wp_users_handler'), 'add_users', PLUGIN_KEY.'-main-page', array($this, 'page_content'), PLUGIN_URL.'admin/img/users-icon.png', 6);
        }

        public function page_content()
        {
            return include PLUGIN_PATH.'admin/templates/main-page.php';
        }

    }

    new Wp_users_handler_Activator();