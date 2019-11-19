<?php


    // TODO:: Revamp login ajax function

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
    use UH\FUNCTIONS\Wp_functions;
    use UH\VALIDATIONS\Wp_validations;

    class Wp_users
    {
        use Wp_validations;
        use Wp_functions;

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
            $this->configurations = get_option($this->plugin_key().'_configurations', true);
            $this->defaults       = [
                'email_confirmation' => 'pending',
                'block_status'       => '',
                'approval_status'    => 'approved',
                'ip_address'         => $_SERVER['SERVER_ADDR'],
                'default_language'   => 'en'
            ];

            add_action('wp_ajax_nopriv_'.$this->plugin_key().'_login_ajax', array($this, 'login_ajax_callback'));
            add_action('wp_ajax_nopriv_'.$this->plugin_key().'_register_ajax', array($this, 'register_ajax_callback'));
            add_action('wp_ajax_nopriv_'.$this->plugin_key().'_rp_send_email_ajax', array($this, 'rp_send_email_ajax_callback'));
            add_action('wp_ajax_nopriv_'.$this->plugin_key().'_rp_change_password_ajax', array($this, 'rp_change_password_ajax_callback'));
            add_action('wp_ajax_nopriv_'.$this->plugin_key().'_activate_account_ajax', array($this, 'activate_account_ajax_callback'));
            add_action('wp_ajax_nopriv_'.$this->plugin_key().'_resend_activation_mail_ajax', array($this, 'resend_activation_mail_ajax_callback'));
            add_action('wp_ajax_'.$this->plugin_key().'_delete_account_ajax', array($this, 'delete_account_ajax_callback'));
            add_action('wp_ajax_'.$this->plugin_key().'_update_account_ajax', array($this, 'update_account_ajax_callback'));
            add_action('set_logged_in_cookie', array($this, 'add_login_tokens'), 10, 6);
        }

        public static function get_instance()
        {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }


        /* -------------- Start Of Ajax Functions  -------------- */

        public function login_ajax_callback()
        {
            $error         = new \WP_Error();
            $form_data     = $_POST['data'];
            $validations   = $this->validate_controller('login', $form_data);
            $user_login    = $this->filterStrings($form_data['user_login']);
            $user_password = $this->filterStrings($form_data['user_password']);

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

                /* Email confirmation option */
                if ($this->configurations->email_confirmation === 'on' && !$this->is_confirm($user)) {
                    $error->add('email_confirmation', __("Your account is pending!, Please check your E-mail to activate your account.", "wp_users_handler"));
                    wp_send_json(array(
                        'success' => false,
                        'msg'     => $error->get_error_message(),
                    ));
                }

                /* Admin approval option */
                if ($this->configurations->admin_approval === 'on' && $this->is_pending($user)) {
                    $error->add('admin_approval', __("Your account is pending!, Your account needs an approval from admin first.", "wp_users_handler"));
                    wp_send_json(array(
                        'success' => false,
                        'msg'     => $error->get_error_message(),
                    ));
                }

                /* Blocking users option */
                if ($this->is_blocked($user)) {
                    $error->add('account_blocked', __("Your account is blocked!, You have been blocked by the admin.", "wp_users_handler"));
                    wp_send_json(array(
                        'success' => false,
                        'msg'     => $error->get_error_message(),
                    ));
                }

                /* Limit login by network ip option */
                if ($this->configurations->login_network === 'on' && !$this->is_network($user)) {
                    $error->add('invalid_ip', __("You can't login from this network.", "wp_users_handler"));
                    wp_send_json(array(
                        'success' => false,
                        'msg'     => $error->get_error_message(),
                    ));
                }

                /* Limit login by active accounts option */
                if ($this->configurations->limit_active_login === 'on' && $this->is_max_active_login()) {

                    // Sessions and token data
                    $user_sessions = get_user_meta($user->ID, 'session_tokens', true);
                    $sessions      = \WP_Session_Tokens::get_instance($user->ID);
                    $login_tokens  = get_user_meta($user->ID, 'login_tokens', true);

                    // If first time after activate the option ? logout the user from all sessions.
                    if (count($user_sessions) > count($login_tokens)) {
                        $sessions->destroy_all();
                        update_user_meta($user->ID, 'login_tokens', '');
                        $login_tokens = [];
                    }

                    if (!empty($login_tokens)) {
                        $tokens = [];
                        // filter session tokens tokens
                        foreach ($login_tokens as $token) {
                            $data = explode('|', $token);
                            if ($sessions->verify($data[2])) {
                                $tokens[] = [
                                    'user' => $data[0],
                                    'date' => $data[1],
                                    'tk'   => $data[2],
                                    'key'  => $data[3],
                                ];
                            }
                        }

                        // calculate available active accounts to be excluded
                        $subtractions     = count($tokens) - (int)$this->configurations->number_of_active_login;

                        // destroy exceeded sessions
                        if ($subtractions == 0) {
                            $session = array_slice($tokens, 0, 1);
                            $sessions->destroy($session[0]['tk']);
                            $tokens = []; // empty tokens
                        } elseif ($subtractions > 0) {
                            $sessions_arr = array_slice($tokens, 0, ($subtractions + 1));
                            foreach ($sessions_arr as $key => $value) {
                                $sessions->destroy($value['tk']);
                                unset($tokens[$key]);
                            }
                        }

                        // reset
                        $reset = array_map(function ($data) {
                            return implode('|', $data);
                        }, $tokens);

                        update_user_meta($user->ID, 'login_tokens', $reset);
                    }

                }

                do_action($this->plugin_key().'_before_login');

                $login = $this->login($form_data);

                if ($login['success']) {
                    do_action($this->plugin_key().'_after_login');
                    wp_send_json(array(
                        'success'      => true,
                        'msg'          => __('You have logged in successfully!. Redirecting...', 'wp_users_handler'),
                        'redirect_url' => $form_data['_wp_http_referer'],
                    ));
                } else {
                    wp_send_json($login);
                }

            }

            wp_send_json(array(
                'success' => false,
                'msg'     => __('Something went wrong!, Come back later.', 'wp_users_handler'),
            ));

        }

        public function register_ajax_callback()
        {
            do_action($this->plugin_key().'_before_register');
            $this->register();
            do_action($this->plugin_key().'_after_register');
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
            do_action($this->plugin_key().'_before_update_profile');
            $this->update();
            do_action($this->plugin_key().'_after_update_profile');
        }


        /* -------------- Start Of Ajax dependencies Functions  -------------- */

        private function login($form_data)
        {
            $user_login    = $this->filterStrings($form_data['user_login']);
            $user_password = $this->filterStrings($form_data['user_password']);

            $cred                  = array();
            $cred['user_login']    = $user_login;
            $cred['user_password'] = $user_password;
            if (isset($form_data['rememberme']) && !empty($this->filterStrings($form_data['rememberme']))) {
                $cred['remember'] = $this->filterStrings($form_data['rememberme']);
            }

            $login = wp_signon($cred);

            if (is_wp_error($login)) {
                $return = array(
                    'success' => false,
                    'msg'     => $login->get_error_message(),
                );
                return $return;
            }

            return array('success' => true);
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
                update_user_meta($this->user_id, $this->plugin_key().'_'.$meta_key, $meta_value);
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
            $max_active_login = get_option($this->plugin_key().'_number_of_active_login', true);
            if (count($user_sessions) >= $max_active_login) {
                return true;
            }

            return false;
        }


        /* -------------- Start Of Action Functions  -------------- */

        public function add_login_tokens($logged_in_cookie, $expire, $expiration, $user_id, $logged_in_text, $token) {
            $tokens = get_user_meta($user_id, 'login_tokens', true);
            if (empty($tokens)) {
                $tokens = [$logged_in_cookie];
            } else {
                $tokens[] = $logged_in_cookie;
            }
            update_user_meta($user_id, 'login_tokens', $tokens);
        }

        /* -------------- Start Of Class Public Functions  -------------- */

        public function get_account_data()
        {
            global $user_ID;
            $user    = get_userdata($user_ID);
            $convert = $this->convert($user);
            return $convert;
        }

        public function convert($user)
        {
            $object               = new \stdClass();
            $object->ID           = $user->ID;
            $object->login        = $user->data->user_login;
            $object->email        = $user->data->user_email;
            $object->name         = $user->data->display_name;
            $object->role         = self::get_user_role($user->data->ID);
            $object->login_tokens = get_user_meta($user->ID, 'login_tokens', true);
            foreach ($this->defaults as $meta_key => $meta_value) {
                $object->{$meta_key} = get_user_meta($user->data->ID, $this->plugin_key().'_'.$meta_key, true);
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

    new Wp_users();
