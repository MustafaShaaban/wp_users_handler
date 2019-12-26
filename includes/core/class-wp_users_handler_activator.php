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
	 * @subpackage Wp_users_handler/includes/core
	 * @author     Mustafa Shaaban <mustafashaaban22@gmail.com>
	 */

	namespace UH\ACTIVATOR;

	use UH\FUNCTIONS\Wp_functions;

	class Wp_users_handler_Activator {
		use Wp_functions;

		public function __construct() {
			add_action( 'admin_menu', array( $this, 'register_pl_menus' ) );
			add_filter( 'display_post_states', array( $this, 'add_post_state' ), 10, 2 );
		}

		/**
		 * Short Description. (use period)
		 *
		 * Long Description.
		 *
		 * @since    1.0.0
		 */
		public static function activate() {

			self::setup_configurations();
			self::setup_pages();

			flush_rewrite_rules();
		}

		public static function setup_configurations() {
			$configurations = get_option( WP_USERS_HANDLER_PLUGIN_KEY . '_configurations' );

			if ( empty( $configurations ) ) {
				$configurations                         = new \stdClass();
				$configurations->email_confirmation     = 'on';
				$configurations->limit_active_login     = 'on';
				$configurations->login_network          = 'on';
				$configurations->admin_approval         = 'on';
				$configurations->check_keyup            = 'off';
				$configurations->block_users            = 'off';
				$configurations->number_of_active_login = 1;

				update_option( WP_USERS_HANDLER_PLUGIN_KEY . '_configurations', $configurations );
			}
		}

		public static function setup_pages() {
			global $user_ID;

			if ( ! get_page_by_path( 'uh-account' ) ) {
				$account = wp_insert_post( array(
					'post_status' => 'publish',
					'post_type'   => 'page',
					'post_title'  => 'Account',
					'post_name'   => 'uh-account',
					'post_author' => $user_ID
				) );
			} else {
				$page    = get_page_by_path( 'uh-account', OBJECT );
				$account = $page->ID;
			}

			if ( ! get_page_by_path( 'uh-my-account' ) ) {
				$my_account = wp_insert_post( array(
					'post_status' => 'publish',
					'post_type'   => 'page',
					'post_title'  => 'My Account',
					'post_name'   => 'uh-my-account',
					'post_author' => $user_ID
				) );
			} else {
				$page       = get_page_by_path( 'uh-my-account', OBJECT );
				$my_account = $page->ID;
			}

			if ( $account ) {
				$login    = wp_insert_post( array(
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_title'   => 'Login',
					'post_content' => '[' . WP_USERS_HANDLER_PLUGIN_KEY . '_login_form]',
					'post_name'    => 'login',
					'post_author'  => $user_ID,
					'post_parent'  => $account
				) );
				$register = wp_insert_post( array(
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_title'   => 'SignUp',
					'post_content' => '[' . WP_USERS_HANDLER_PLUGIN_KEY . '_registration_form]',
					'post_name'    => 'register',
					'post_author'  => $user_ID,
					'post_parent'  => $account
				) );
				$forgot   = wp_insert_post( array(
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_title'   => 'Forgot Password',
					'post_content' => '[' . WP_USERS_HANDLER_PLUGIN_KEY . '_rp_step1_form]',
					'post_name'    => 'forgot-password',
					'post_author'  => $user_ID,
					'post_parent'  => $account
				) );
			}

			if ( $my_account ) {
				$profile = wp_insert_post( array(
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_title'   => 'Profile',
					'post_content' => '[' . WP_USERS_HANDLER_PLUGIN_KEY . '_profile]',
					'post_name'    => 'profile',
					'post_author'  => $user_ID,
					'post_parent'  => $my_account
				) );
			}
		}

		public function add_post_state( $post_states, $post ) {
			switch ( $post->post_name ) {
				case 'uh-account':
					$post_states[] = WP_USERS_HANDLER_PLUGIN_KEY . ' Account Page';
					break;
				case 'login':
					$post_states[] = WP_USERS_HANDLER_PLUGIN_KEY . ' Login Page';
					break;
				case 'register':
					$post_states[] = WP_USERS_HANDLER_PLUGIN_KEY . ' SignUp Page';
					break;
				case 'forgot-password':
					$post_states[] = WP_USERS_HANDLER_PLUGIN_KEY . ' Forgot Password Page';
					break;
				case 'uh-my-account':
					$post_states[] = WP_USERS_HANDLER_PLUGIN_KEY . ' My Account Page';
					break;
				case 'profile':
					$post_states[] = WP_USERS_HANDLER_PLUGIN_KEY . ' Profile Page';
					break;
				default:
					break;
			}

			return $post_states;
		}

		public function register_pl_menus() {
			add_menu_page( __( $this->plugin_title(), 'wp_users_handler' ), __( $this->plugin_title(), 'wp_users_handler' ), 'add_users', $this->plugin_key() . '-main-page', array( $this, 'page_content' ), $this->plugin_url() . 'admin/assets/img/users-icon.png', 6 );
		}

		public function page_content() {
			return include $this->plugin_path() . 'admin/templates/main-page.php';
		}

	}

	new Wp_users_handler_Activator();