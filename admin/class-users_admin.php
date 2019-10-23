<?php

    /**
     * Class Users_Admin
     *
     * block/unblock user (with expire date) Admin Area
     * Security (Number of active Accounts, Number of failed login, IP Address for login) Admin Area
     *
     */

    namespace UH\ADMIN\USERS;

    use UH\FUNCTIONS\Wp_functions;
    use UH\HANDLER\Wp_users_handler;

    class Users_Admin
    {
        use Wp_functions;

        public static $instance;

        /**
         * Initialize the class and set its properties.
         *
         * @since    1.0.0
         */
        public function __construct()
        {
            self::$instance = $this;
            $loader = Wp_users_handler::get_instance()->get_loader();

            $loader->add_action('wp_ajax_'.$this->plugin_key().'_switch_settings', $this, 'switch_settings_ajax_callback');
        }

        public static function get_instance()
        {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function switch_settings_ajax_callback() {
            $option_name = $_POST['option_name'];
            $option_value = $_POST['option_value'];
            $options = get_option($this->plugin_key().'_configurations', true);
            $options->{$option_name} = $option_value;

            update_option($this->plugin_key().'_configurations', $options);

            wp_send_json(array(
                'success' => true,
                'msg' => __("Settings has benn updated successfully", "wp_users_handler")
            ));
        }

    }

    new Users_Admin();
