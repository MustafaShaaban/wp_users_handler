<?php


	// TODO:: Revamp login ajax function
	// TODO:: apply login ajax in admin panel.


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
	 * get user account data (ID, user_login, user_email, +defaults)
	 *
	 * 'email_confirmation' => 'active',
	 * 'block_status'       => 'blocked,
	 * 'approval_status'     => 'pending',
	 *
	 */

	namespace UH\USERS;

	use UH\CRYPTOR\Wp_cryptor;
	use UH\FUNCTIONS\Wp_functions;
	use UH\VALIDATIONS\Wp_validations;

	class Wp_users {
		use Wp_validations;
		use Wp_functions;

		public static $instance;
		private $defaults = [];
		private $user_meta = [];
		private $configurations;
		private $user_id;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {
			self::$instance       = $this;
			$this->configurations = get_option( $this->plugin_key() . '_configurations', true );
			$this->defaults       = [
				'email_confirmation' => $this->configurations->email_confirmation === 'on' ? 'pending' : 'active',
				'block_status'       => '',
				'approval_status'    => $this->configurations->admin_approval === 'on' ? 'pending' : 'approved',
				'ip_address'         => $_SERVER['SERVER_ADDR'],
				'login_tokens'       => '',
			];
			$this->user_meta      = [
				'nickname',
				'first_name',
				'last_name'
			];

			add_action( 'wp_ajax_nopriv_' . $this->plugin_key() . '_login_ajax', array( $this, 'login_ajax_callback' ) );
			add_action( 'wp_ajax_nopriv_' . $this->plugin_key() . '_register_ajax', array( $this, 'register_ajax_callback' ) );
			add_action( 'wp_ajax_nopriv_' . $this->plugin_key() . '_check_email_ajax', array( $this, 'check_email_ajax_callback' ) );
			add_action( 'wp_ajax_nopriv_' . $this->plugin_key() . '_check_username_ajax', array( $this, 'check_username_ajax_callback' ) );
			add_action( 'wp_ajax_nopriv_' . $this->plugin_key() . '_rp_send_email_ajax', array( $this, 'rp_send_email_ajax_callback' ) );
			add_action( 'wp_ajax_nopriv_' . $this->plugin_key() . '_rp_change_password_ajax', array( $this, 'rp_change_password_ajax_callback' ) );
			add_action( 'wp_ajax_nopriv_' . $this->plugin_key() . '_activate_account_ajax', array( $this, 'activate_account_ajax_callback' ) );
			add_action( 'wp_ajax_nopriv_' . $this->plugin_key() . '_resend_activation_mail_ajax', array( $this, 'resend_activation_mail_ajax_callback' ) );
			add_action( 'wp_ajax_' . $this->plugin_key() . '_delete_account_ajax', array( $this, 'delete_account_ajax_callback' ) );
			add_action( 'wp_ajax_' . $this->plugin_key() . '_update_account_ajax', array( $this, 'update_account_ajax_callback' ) );
			add_action( 'set_logged_in_cookie', array( $this, 'add_login_tokens' ), 10, 6 );
		}

		public static function get_instance() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}

			return self::$instance;
		}


		/* -------------- Start Of Ajax Functions  -------------- */

		public function login_ajax_callback() {
			$error         = new \WP_Error();
			$form_data     = $_POST['data'];
			$validations   = $this->validate_controller( 'login', $form_data );
			$user_login    = $this->filterStrings( $form_data['user_login'] );
			$user_password = $this->filterStrings( $form_data['user_password'] );

			if ( is_user_logged_in() ) {
				wp_send_json( array(
					'success' => false,
					'msg'     => __( 'You are already logged In!.', 'wp_users_handler' ),
				) );
			}

			if ( ! $validations['status'] ) {
				wp_send_json( array(
					'success' => false,
					'msg'     => $validations['msg'],
				) );
			}

			$user = get_user_by( 'login', $user_login );
			if ( empty( $user ) ) {
				$user = get_user_by( 'email', $user_login );
				if ( empty( $user ) ) {
					$error->add( 'invalid_user_login', __( "Your username or Email address is invalid.", "wp_users_handler" ) );
					wp_send_json( array(
						'success' => false,
						'msg'     => $error->get_error_message(),
					) );
				}
			}

			if ( ! empty( $user ) ) {

				$check_pwd = wp_check_password( $user_password, $user->user_pass, $user->ID );
				if ( ! $check_pwd ) {
					$error->add( 'invalid_password', __( "Your password is invalid.", "wp_users_handler" ) );
					wp_send_json( array(
						'success' => false,
						'msg'     => $error->get_error_message(),
					) );
				}

				// set user ID
				$this->user_id = $user->ID;
				$user          = $this->convert( $user );

				/* Email confirmation option */
				if ( $this->configurations->email_confirmation === 'on' && ! $this->is_confirm( $user ) ) {
					$error->add( 'email_confirmation', __( "Your account is pending!, Please check your E-mail to activate your account.", "wp_users_handler" ) );
					wp_send_json( array(
						'success' => false,
						'msg'     => $error->get_error_message(),
					) );
				}

				/* Admin approval option */
				if ( $this->configurations->admin_approval === 'on' && $this->is_pending( $user ) ) {
					$error->add( 'admin_approval', __( "Your account is pending!, Your account needs an approval from admin first.", "wp_users_handler" ) );
					wp_send_json( array(
						'success' => false,
						'msg'     => $error->get_error_message(),
					) );
				}

				/* Blocking users option */
				if ( $this->is_blocked( $user ) ) {
					$error->add( 'account_blocked', __( "Your account is blocked!, You have been blocked by the admin.", "wp_users_handler" ) );
					wp_send_json( array(
						'success' => false,
						'msg'     => $error->get_error_message(),
					) );
				}

				/* Limit login by network ip option */
				if ( $this->configurations->login_network === 'on' && ! $this->is_network( $user ) ) {
					$error->add( 'invalid_ip', __( "You can't login from this network.", "wp_users_handler" ) );
					wp_send_json( array(
						'success' => false,
						'msg'     => $error->get_error_message(),
					) );
				}

				/* Limit login by active accounts option */
				if ( $this->configurations->limit_active_login === 'on' && $this->is_max_active_login() ) {

					// Sessions and token data
					$user_sessions = get_user_meta( $user->ID, 'session_tokens', true );
					$sessions      = \WP_Session_Tokens::get_instance( $user->ID );
					$login_tokens  = get_user_meta( $user->ID, $this->plugin_key() . '_login_tokens', true );

					// If first time after activate the option ? logout the user from all sessions.
					if ( count( $user_sessions ) > count( $login_tokens ) ) {
						$sessions->destroy_all();
						update_user_meta( $user->ID, $this->plugin_key() . '_login_tokens', '' );
						$login_tokens = [];
					}

					if ( ! empty( $login_tokens ) ) {
						$tokens = [];
						// filter session tokens tokens
						foreach ( $login_tokens as $token ) {
							$data = explode( '|', $token );
							if ( $sessions->verify( $data[2] ) ) {
								$tokens[] = [
									'user' => $data[0],
									'date' => $data[1],
									'tk'   => $data[2],
									'key'  => $data[3],
								];
							}
						}

						// calculate available active accounts to be excluded
						$subtractions = count( $tokens ) - (int) $this->configurations->number_of_active_login;

						// destroy exceeded sessions
						if ( $subtractions == 0 ) {
							$session = array_slice( $tokens, 0, 1 );
							$sessions->destroy( $session[0]['tk'] );
							$tokens = []; // empty tokens
						} elseif ( $subtractions > 0 ) {
							$sessions_arr = array_slice( $tokens, 0, ( $subtractions + 1 ) );
							foreach ( $sessions_arr as $key => $value ) {
								$sessions->destroy( $value['tk'] );
								unset( $tokens[ $key ] );
							}
						}

						// reset
						$reset = array_map( function ( $data ) {
							return implode( '|', $data );
						}, $tokens );

						update_user_meta( $user->ID, $this->plugin_key() . '_login_tokens', $reset );
					}

				}

				do_action( $this->plugin_key() . '_before_login', $user->ID, $form_data );

				$form_data = apply_filters( $this->plugin_key() . '_filter_login_form_data', $form_data );

				$login = $this->login( $form_data );

				if ( ! $login['success'] ) {
					wp_send_json( $login );
				}

				do_action( $this->plugin_key() . '_after_login', $user->ID );

				$url = apply_filters( $this->plugin_key() . '_change_login_redirection', $form_data['_wp_http_referer'] );

				wp_send_json( array(
					'success'      => true,
					'msg'          => __( 'You have logged in successfully!. Redirecting...', 'wp_users_handler' ),
					'redirect_url' => $url,
				) );

			}

			wp_send_json( array(
				'success' => false,
				'msg'     => __( 'Something went wrong!, Come back later.', 'wp_users_handler' ),
			) );

		}

		public function register_ajax_callback() {
			$error       = new \WP_Error();
			$form_data   = $_POST['data'];
			$validations = $this->validate_controller( 'register', $form_data );

			if ( is_user_logged_in() ) {
				wp_send_json( array(
					'success' => false,
					'msg'     => __( 'You are already have an account!.', 'wp_users_handler' ),
				) );
			}

			if ( ! $validations['status'] ) {
				wp_send_json( array(
					'success' => false,
					'msg'     => $validations['msg'],
				) );
			}

			$form = $this->convertRegistration( $form_data );

			if ( email_exists( $form->email ) ) {
				$error->add( 'user_email_exists', __( "This Email is already taken, Try another.", "wp_users_handler" ) );
				wp_send_json( array(
					'success' => false,
					'msg'     => $error->get_error_message(),
				) );
			}

			if ( ! validate_username( $form->username ) ) {
				$error->add( 'invalid_user_login', __( "Your username is invalid.", "wp_users_handler" ) );
				wp_send_json( array(
					'success' => false,
					'msg'     => $error->get_error_message(),
				) );
			}

			if ( username_exists( $form->username ) ) {
				$error->add( 'user_login_exist', __( "This username is already taken, Try another.", "wp_users_handler" ) );
				wp_send_json( array(
					'success' => false,
					'msg'     => $error->get_error_message(),
				) );
			}

			do_action( $this->plugin_key() . '_before_register', $form );

			$register = $this->register( $form );

			if ( ! $register['success'] ) {
				wp_send_json( $register );
			}

			wp_send_json( $register );


		}

		public function check_email_ajax_callback() {
			if ( $this->configurations->check_keyup === 'on' ) {
				$email = $this->filterStrings( $_POST['user_email'] );
				if ( ! email_exists( $email ) ) {
					echo json_encode( "true" );
					die();
				} else {
					echo json_encode( __( 'That Email is taken, Try another', 'wp_users_handler' ) );
					die();
				}
			}
			echo json_encode( __( 'true', 'wp_users_handler' ) );
			die();
		}

		public function check_username_ajax_callback() {
			// TODO:: Check excluded usernames.
			if ( $this->configurations->check_keyup === 'on' ) {
				$username = $this->filterStrings( $_POST['user_login'] );
				if ( ! username_exists( $username ) ) {
					echo json_encode( __( 'true', 'wp_users_handler' ) );
					die();
				} else {
					echo json_encode( __( 'That username is taken, Try another', 'wp_users_handler' ) );
					die();
				}
			}
			echo json_encode( __( 'true', 'wp_users_handler' ) );
			die();
		}

		public function rp_send_email_ajax_callback() {
		}

		public function rp_change_password_ajax_callback() {
		}

		public function activate_account_ajax_callback() {
		}

		public function resend_activation_mail_ajax_callback() {
		}

		public function delete_account_ajax_callback() {
		}

		public function update_account_ajax_callback() {
			do_action( $this->plugin_key() . '_before_update_profile' );
			$this->update();
			do_action( $this->plugin_key() . '_after_update_profile' );
		}


		/* -------------- Start Of Ajax dependencies Functions  -------------- */

		private function login( $form_data ) {
			$user_login    = $this->filterStrings( $form_data['user_login'] );
			$user_password = $this->filterStrings( $form_data['user_password'] );

			$cred                  = array();
			$cred['user_login']    = $user_login;
			$cred['user_password'] = $user_password;
			if ( isset( $form_data['rememberme'] ) && ! empty( $this->filterStrings( $form_data['rememberme'] ) ) ) {
				$cred['remember'] = $this->filterStrings( $form_data['rememberme'] );
			}

			$login = wp_signon( $cred );

			if ( is_wp_error( $login ) ) {
				$return = array(
					'success' => false,
					'msg'     => $login->get_error_message(),
				);

				return $return;
			}

			return array( 'success' => true );
		}

		private function register( $form_data ) {

			$user_id = wp_create_user( $form_data->username, $form_data->password, $form_data->email );

			if ( is_wp_error( $user_id ) ) {
				$return = array(
					'success' => false,
					'msg'     => $user_id->get_error_message(),
				);

				return $return;
			}

			$this->user_id = $user_id;
			$user          = get_user_by( 'id', $this->user_id );
			$user->set_role( 'subscriber' );

			wp_update_user( array( 'ID' => $this->user_id, 'display_name' => $form_data->display_name ) );

			$after_register = $this->after_register( $form_data );

			return $after_register;

		}

		private function update() {
		}

		private function set_user_defaults() {
			foreach ( $this->defaults as $meta_key => $meta_value ) {
				update_user_meta( $this->user_id, $this->plugin_key() . '_' . $meta_key, $meta_value );
			}
		}

		private function set_user_meta( $form_data ) {
			foreach ( $this->user_meta as $meta_key ) {
				if ( property_exists( $form_data, $meta_key ) ) {
					update_user_meta( $this->user_id, $meta_key, $form_data->{$meta_key} );
				}
			}
		}

		private function after_register( $form_data ) {
			$this->set_user_defaults();

			$this->set_user_meta( $form_data );

			do_action( $this->plugin_key() . '_after_register', $this->user_id, $form_data );

			if ( $this->is_can_login() ) {
				$cred = array(
					'user_login'    => $form_data->username,
					'user_password' => $form_data->password
				);

				do_action( $this->plugin_key() . '_before_reg_login', $this->user_id, $cred );

				$login = wp_signon( $cred );

				if ( is_wp_error( $login ) ) {
					return array(
						'success' => false,
						'msg'     => $login->get_error_message(),
					);
				}

				do_action( $this->plugin_key() . '_after_reg_login', $this->user_id );

				$url = apply_filters( $this->plugin_key() . '_change_reg_login_redirection', $form_data->redirect_url );

				return array(
					'success'      => true,
					'msg'          => __( 'You have logged in successfully!. Redirecting...', 'wp_users_handler' ),
					'redirect_url' => $url,
				);
			}


			// TODO:: send emails.

			if ( $this->configurations->email_confirmation === 'on' ) {
				// send email to client

				$url = apply_filters( $this->plugin_key() . '_change_registration_redirection', $form_data->redirect_url );

				return array(
					'success'      => true,
					'msg'          => __( 'You have registered successfully!. Please check your email to activate your account!.', 'wp_users_handler' ),
					'redirect_url' => $url,
				);
			}

			if ( $this->configurations->admin_approval === 'on' ) {
				// send email to admin

				$url = apply_filters( $this->plugin_key() . '_change_registration_redirection', $form_data->redirect_url );

				return array(
					'success'      => true,
					'msg'          => __( 'You have registered successfully!. Your account is pending for admin approval.', 'wp_users_handler' ),
					'redirect_url' => $url,
				);
			}

			return array(
				'success' => false,
				'msg'     => __( 'Something went wrong!. Please try again later.', 'wp_users_handler' )
			);
		}

		private function is_confirm( $user ) {
			if ( empty( $user->email_confirmation ) || $user->email_confirmation !== 'active' ) {
				return false;
			}

			return true;
		}

		private function is_blocked( $user ) {
			if ( empty( $user->block_status ) || $user->block_status !== 'blocked' ) {
				return false;
			}

			return true;
		}

		private function is_pending( $user ) {
			if ( empty( $user->approval_status ) || $user->approval_status !== 'approved' ) {
				return true;
			}

			return false;

		}

		private function is_network( $user ) {
			if ( ! empty( $user->ip_address ) && $user->ip_address === $_SERVER['SERVER_ADDR'] ) {
				return true;
			}

			return false;

		}

		private function is_max_active_login() {
			$user_sessions    = get_user_meta( $this->user_id, 'session_tokens', true );
			$max_active_login = get_option( $this->plugin_key() . '_number_of_active_login', true );
			if ( count( $user_sessions ) >= $max_active_login ) {
				return true;
			}

			return false;
		}

		private function is_can_login() {
			if ( $this->configurations->email_confirmation === 'off' && $this->configurations->admin_approval === 'off' ) {
				return true;
			}

			return false;
		}

		/* -------------- Start Of Action Functions  -------------- */

		public function add_login_tokens( $logged_in_cookie, $expire, $expiration, $user_id, $logged_in_text, $token ) {
			$tokens = get_user_meta( $user_id, $this->plugin_key() . '_login_tokens', true );
			if ( empty( $tokens ) ) {
				$tokens = [ $logged_in_cookie ];
			} else {
				$tokens[] = $logged_in_cookie;
			}
			update_user_meta( $user_id, $this->plugin_key() . '_login_tokens', $tokens );
		}

		/* -------------- Start Of Class Public Functions  -------------- */

		public function get_account_data() {
			global $user_ID;
			$user    = get_userdata( $user_ID );
			$convert = $this->convert( $user );

			return $convert;
		}

		public function convert( $user ) {
			$object        = new \stdClass();
			$object->ID    = $user->ID;
			$object->login = $user->data->user_login;
			$object->email = $user->data->user_email;
			$object->name  = $user->data->display_name;
			$object->role  = self::get_user_role( $user->data->ID );
			foreach ( $this->defaults as $meta_key => $meta_value ) {
				$object->{$meta_key} = get_user_meta( $user->data->ID, $this->plugin_key() . '_' . $meta_key, true );
			}

			return $object;
		}

		public static function get_user_avatar() {
		}

		public static function get_users_by_role() {
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
		public static function get_user_role( $id = 0, $single = true ) {
			global $user_ID;
			$ID   = ( $id !== 0 && is_numeric( $id ) ) ? $id : $user_ID;
			$role = [];
			if ( ! empty( $ID ) && is_numeric( $ID ) ) {
				$user_meta = get_userdata( $ID );

				return $role = ( $single ) ? $user_meta->roles[0] : $user_meta->roles;
			}

			return $role;
		}

	}

	new Wp_users();
