<?php


    /**
     * Class Users
     *
     * Login (Remember me)
     * Register (FirstName, LastName, Username, Password, Email)
     * reset password step 1 (send email)
     * reset password step 2 (validate and reset)
     * activate account
     * resend activation mail
     * delete account
     * Edit Account (Username, Password, Email) needed password confirm
     *
     *
     * block/unblock user (with expire date) Admin Area
     * Security (Number of active Accounts, Number of failed login, IP Address for login) Admin Area
     *
     *
     * get user role
     * get users by role
     * get user avatar
     * get user account data (ID, user_login, user_email, user_email_status, user_account_status, user_IP_Address)
     *
     */

    namespace UH\USERS;

    use UH\CRYPTOR\Wp_cryptor;
    use UH\VALIDATIONS\Wp_validations;

    class Users
    {
        use Wp_validations;

        public static $instance;
        private $defaults = [];

        public function __construct()
        {
            self::$instance   = $this;
            $this->defaults   = [
                'email_confirmation' => 'pending',
                'account_status'     => 'active'
            ];

            add_action('wp_ajax_nopriv_'.PLUGIN_KEY.'_login_ajax', array($this, 'login_ajax_callback'));
            add_action('wp_ajax_nopriv_'.PLUGIN_KEY.'_register_ajax', array($this, 'register_ajax_callback'));
            add_action('wp_ajax_nopriv_'.PLUGIN_KEY.'_rp_send_email_ajax', array($this, 'rp_send_email_ajax_callback'));
            add_action('wp_ajax_nopriv_'.PLUGIN_KEY.'_rp_change_password_ajax', array($this, 'rp_change_password_ajax_callback'));
            add_action('wp_ajax_nopriv_'.PLUGIN_KEY.'_activate_account_ajax', array($this, 'activate_account_ajax_callback'));
            add_action('wp_ajax_nopriv_'.PLUGIN_KEY.'_resend_activation_mail_ajax', array($this, 'resend_activation_mail_ajax_callback'));
            add_action('wp_ajax_'.PLUGIN_KEY.'_delete_account_ajax', array($this, 'delete_account_ajax_callback'));
            add_action('wp_ajax_'.PLUGIN_KEY.'_update_account_ajax', array($this, 'update_account_ajax_callback'));

        }

        public static function get_instance()
        {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function login_ajax_callback()
        {
            $cred = array();
            do_action(PLUGIN_KEY.'_before_login');
            $this->login($cred);
            do_action(PLUGIN_KEY.'_after_login');
        }

        public function register_ajax_callback()
        {
            do_action(PLUGIN_KEY.'_before_register');
            $this->register();
            do_action(PLUGIN_KEY.'_after_register');
        }

        public function rp_send_email_ajax_callback()
        {
        }

        public function rp_change_password_ajax_callback()
        {
        }

        public function activate_account_ajax_callback()
        {
        }

        public function resend_activation_mail_ajax_callback()
        {
        }

        public function delete_account_ajax_callback()
        {
        }

        public function update_account_ajax_callback()
        {
            do_action(PLUGIN_KEY.'_before_update_profile');
            $this->update();
            do_action(PLUGIN_KEY.'_after_update_profile');
        }

        private function login($cred)
        {
        }

        private function register()
        {
        }

        private function update()
        {
        }

        private function is_confirm()
        {
        }

        private function is_blocked()
        {
        }

        public static function get_account_data()
        {
        }

        public static function get_user_avatar()
        {
        }

        public static function get_users_by_role()
        {
        }

        /**
         * Function to get the user role by ID
         *
         * @param int  $id : The User ID
         * @param bool $single true to get the first rule, False to get all Roles
         *
         * @return array of rules if $single : return the first role only
         * @author Mustafa Shaaban
         * @version 1.0.0 V
         */
        public static function get_user_role($id = 0, $single = true)
        {
            global $user_ID;
            $ID   = ($id !== 0 && is_numeric($id)) ? $id : $user_ID;
            $role = [];
            if (!empty($ID) && is_numeric($ID)) {
                $user_meta = get_userdata($ID);
                return $role = ($single) ? $user_meta->roles[0] : $user_meta->roles;
            }
            return $role;
        }

    }

    new Users();
