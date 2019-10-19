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
     * Admin Approval
     *
     * get user role
     * get users by role
     * get user avatar
     * get user account data (ID, user_login, user_email, user_email_status, user_account_status, user_IP_Address)
     *
     * 'email_confirmation' => 'active',
     * 'block_status'       => ''blocked,
     * 'approval_status'     => 'pending',
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
        private $configurations;
        private $user_id;

        /**
         * Initialize the class and set its properties.
         *
         * @since    1.0.0
         */
        public function __construct()
        {
            self::$instance       = $this;
            $this->configurations = get_option(PLUGIN_KEY.'_configurations', true);
            $this->defaults       = [
                'email_confirmation' => 'pending',
                'block_status'       => '',
                'approval_status'    => 'approved',
                'ip_address'         => $_SERVER['SERVER_ADDR'],
                'default_language'   => 'en'
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
            $form_data   = $_POST['data'];
            $validations = $this->validate_controller('login', $form_data);

            if (is_user_logged_in()) {
                wp_send_json(array(
                    'success' => false,
                    'msg'     => __('You are already logged In!.', 'wp_users_handler'),
                ));
            }

            if (!$validations['status']) {
                wp_send_json(array(
                    'success' => false,
                    'msg'     => $validations['msg'],
                ));
            }


            do_action(PLUGIN_KEY.'_before_login');
            $this->login($form_data);
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

        private function login($form_data)
        {
            $error         = new \WP_Error();
            $user_login    = $this->filterStrings($form_data['user_login']);
            $user_password = $this->filterStrings($form_data['user_password']);

            $user = get_user_by('login', $user_login);
            if (empty($user)) {
                $user = get_user_by('email', $user_login);
                if (empty($user)) {
                    $error->add('invalid_user_login', __("Your username or Email address is invalid.", "wp_users_handler"));
                    wp_send_json(array(
                        'success' => false,
                        'msg'     => $error->get_error_message(),
                    ));
                }
            }

            if (!empty($user)) {

                $check_pwd = wp_check_password($user_password, $user->user_pass, $user->ID);
                if (!$check_pwd) {
                    $error->add('invalid_password', __("Your password is invalid.", "wp_users_handler"));
                    wp_send_json(array(
                        'success' => false,
                        'msg'     => $error->get_error_message(),
                    ));
                }

                // set user ID
                $this->user_id = $user->ID;
                $user          = $this->convert($user);

                if ($this->configurations->email_confirmation === 'on' && !$this->is_confirm($user)) {
                    $error->add('email_confirmation', __("Your account is pending!, Please check your E-mail to activate your account.", "wp_users_handler"));
                    wp_send_json(array(
                        'success' => false,
                        'msg'     => $error->get_error_message(),
                    ));
                }

                if ($this->configurations->admin_approval === 'on' && $this->is_pending($user)) {
                    $error->add('admin_approval', __("Your account is pending!, Your account needs an approval from admin first.", "wp_users_handler"));
                    wp_send_json(array(
                        'success' => false,
                        'msg'     => $error->get_error_message(),
                    ));
                }

                if ($this->is_blocked($user)) {
                    $error->add('account_blocked', __("Your account is blocked!, You have been blocked by the admin.", "wp_users_handler"));
                    wp_send_json(array(
                        'success' => false,
                        'msg'     => $error->get_error_message(),
                    ));
                }

                if ($this->configurations->login_network === 'on' && !$this->is_network($user)) {
                    $error->add('invalid_ip', __("You can't login from this network.", "wp_users_handler"));
                    wp_send_json(array(
                        'success' => false,
                        'msg'     => $error->get_error_message(),
                    ));
                }

                if ($this->configurations->limit_active_login === 'on' && $this->is_max_active_login()) {
                    $user_sessions    = get_user_meta($user->ID, 'session_tokens', true);
                    $sessions         = \WP_Session_Tokens::get_instance($user->ID);
                    $max_active_login = get_option(PLUGIN_KEY.'_number_of_active_login', true);
                    $subtractions     = count($user_sessions) - (int)$max_active_login;

                    if ($subtractions == 0) {
                        $session = array_slice($user_sessions, 0, 1);
                        $sessions->destroy(key($session));
                    } elseif ($subtractions > 0) {
                        $sessions_arr = array_slice($user_sessions, 0, ($subtractions + 1));
                        foreach ($sessions_arr as $session_key => $session_value) {
                            $sessions->destroy($session_key);
                        }
                    }

                }

                $cred                  = array();
                $cred['user_login']    = $user_login;
                $cred['user_password'] = $user_password;
                if (isset($form_data['rememberme']) && !empty($this->filterStrings($form_data['rememberme']))) {
                    $cred['remember'] = $this->filterStrings($form_data['rememberme']);
                }

                $login = wp_signon($cred);

                if (is_wp_error($login)) {
                    wp_send_json(array(
                        'success' => false,
                        'msg'     => $login->get_error_message(),
                    ));
                }

                wp_send_json(array(
                    'success'      => true,
                    'msg'          => __('You have logged in successfully!. Redirecting...', 'wp_users_handler'),
                    'redirect_url' => $form_data['_wp_http_referer'],
                ));

            }

            return false;
        }

        private function register()
        {
        }

        private function update()
        {
        }

        private function set_user_defaults()
        {
            foreach ($this->defaults as $meta_key => $meta_value) {
                update_user_meta($this->user_id, PLUGIN_KEY.'_'.$meta_key, $meta_value);
            }
        }

        private function is_confirm($user)
        {
            if (empty($user->email_confirmation) || $user->email_confirmation !== 'active') {
                return false;
            }

            return true;
        }

        private function is_blocked($user)
        {
            if (empty($user->block_status) || $user->block_status !== 'blocked') {
                return false;
            }

            return true;
        }

        private function is_pending($user)
        {
            if (empty($user->approval_status) || $user->approval_status !== 'active') {
                return true;
            }
            return false;

        }

        private function is_network($user)
        {
            if (!empty($user->ip_address) && $user->ip_address === $_SERVER['SERVER_ADDR']) {
                return true;
            }
            return false;

        }

        private function is_max_active_login()
        {
            $user_sessions    = get_user_meta($this->user_id, 'session_tokens', true);
            $max_active_login = get_option(PLUGIN_KEY.'_number_of_active_login', true);
            if (count($user_sessions) >= $max_active_login) {
                return true;
            }

            return false;
        }

        public function get_account_data()
        {
            global $user_ID;
            $user    = get_userdata($user_ID);
            $convert = $this->convert($user);
            return $convert;
        }

        public function convert($user)
        {
            $object        = new \stdClass();
            $object->ID    = $user->ID;
            $object->login = $user->data->user_login;
            $object->email = $user->data->user_email;
            $object->name  = $user->data->display_name;
            $object->role  = self::get_user_role($user->data->ID);
            foreach ($this->defaults as $meta_key) {
                $object->{$meta_key} = get_user_meta($user->data->ID, PLUGIN_KEY.'_'.$meta_key, true);
            }
            return $object;
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
         * @since    1.0.0
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
