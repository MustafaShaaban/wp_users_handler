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

    use UH\FUNCTIONS\Wp_functions;

    class Wp_users_handler_Activator
    {
        use Wp_functions;

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

            update_option(WP_USERS_HANDLER_PLUGIN_KEY.'_configurations', $configurations);
        }

        public function register_pl_menus()
        {
            add_menu_page(__($this->plugin_title(), 'wp_users_handler'), __($this->plugin_title(), 'wp_users_handler'), 'add_users', $this->plugin_key().'-main-page', array($this, 'page_content'), $this->plugin_url().'admin/img/users-icon.png', 6);
        }

        public function page_content()
        {
            return include $this->plugin_path().'admin/templates/main-page.php';
        }

    }

    new Wp_users_handler_Activator();