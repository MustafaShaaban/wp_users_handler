<?php
	/**
	 * Created by PhpStorm.
	 * User: Mustafa Shaaban
	 * Date: 8/26/2019
	 * Time: 8:33 PM
	 */

	namespace UH\VALIDATIONS;

	trait Wp_validations {
		public function __construct() {

		}

		protected function validate_controller( $type, $data ) {
			$error = new \WP_Error();

			switch ( $type ) {
				case 'login':
					$return = $this->validate_login( $data );
					break;
				case 'register':
					$return = $this->validate_register( $data );
					break;
				default:
					$error->add( 'Invalid_validation_type', __( 'Invalid type', 'wp_users_handler' ) );
					$return = array(
						'status' => false,
						'msg'    => $error->get_error_message()
					);
					break;
			}

			return $return;
		}

		private function validate_login( $data ) {
			$error  = new \WP_Error();
			$return = array(
				'status' => true,
			);

			if ( ! wp_verify_nonce( $data['nonce'], $this->plugin_key() . '_LOGIN_FORM' ) ) {
				$error->add( 'invalid_nonce', __( "Your request can't be sent right now, Please come back later!.", "wp_users_handler" ) );
				wp_send_json( array(
					'success' => false,
					'msg'     => $error->get_error_message(),
				) );
			}

			if ( empty( $this->filterStrings( $data['user_login'] ) ) ) {
				$error->add( 'Invalid_user_login', __( 'The Username/Email field is required!.', 'wp_users_handler' ) );
				$return = array(
					'status' => false,
					'msg'    => $error->get_error_message()
				);

				return $return;
			}

			if ( empty( $this->filterStrings( $data['user_password'] ) ) ) {
				$error->add( 'Invalid_password', __( 'The password field is required!.', 'wp_users_handler' ) );
				$return = array(
					'status' => false,
					'msg'    => $error->get_error_message()
				);

				return $return;
			}

//            if (strlen($this->filterStrings($data['user_login'])) < 5) {
//                $error->add('Invalid_user_login_length', __('You Username/Email is invalid.', 'wp_users_handler'));
//                $return = array(
//                    'status' => false,
//                    'msg' => $error->get_error_message()
//                );
//                return $return;
//            }
//
//            if (strlen($this->filterStrings($data['user_password'])) < 8) {
//                $error->add('Invalid_password_length', __('You password is invalid.', 'wp_users_handler'));
//                $return = array(
//                    'status' => false,
//                    'msg' => $error->get_error_message()
//                );
//                return $return;
//            }

			return $return;
		}

		private function validate_register( $data ) {
			$error  = new \WP_Error();
			$return = array(
				'status' => true,
			);

			if ( ! wp_verify_nonce( $data['nonce'], $this->plugin_key() . '_REGISTER_FORM' ) ) {
				$error->add( 'invalid_nonce', __( "Your request can't be sent right now, Please come back later!.", "wp_users_handler" ) );
				wp_send_json( array(
					'success' => false,
					'msg'     => $error->get_error_message(),
				) );
			}

			if ( empty( $this->filterStrings( $data['first_name'] ) ) ) {
				$error->add( 'invalid_first_name', __( 'The First Name field is required!.', 'wp_users_handler' ) );
				$return = array(
					'status' => false,
					'msg'    => $error->get_error_message()
				);

				return $return;
			}

			if ( empty( $this->filterStrings( $data['last_name'] ) ) ) {
				$error->add( 'invalid_last_name', __( 'The Last Name field is required!.', 'wp_users_handler' ) );
				$return = array(
					'status' => false,
					'msg'    => $error->get_error_message()
				);

				return $return;
			}

			if ( empty( $this->filterStrings( $data['user_login'] ) ) ) {
				$error->add( 'invalid_user_login', __( 'The Username field is required!.', 'wp_users_handler' ) );
				$return = array(
					'status' => false,
					'msg'    => $error->get_error_message()
				);

				return $return;
			}

			if ( empty( $this->filterStrings( $data['user_email'] ) ) ) {
				$error->add( 'invalid_user_email', __( 'The Email Address field is required!.', 'wp_users_handler' ) );
				$return = array(
					'status' => false,
					'msg'    => $error->get_error_message()
				);

				return $return;
			}

			if ( empty( $this->filterStrings( $data['user_email_confirm'] ) ) ) {
				$error->add( 'invalid_user_email', __( 'The Confirm Email Address field is required!.', 'wp_users_handler' ) );
				$return = array(
					'status' => false,
					'msg'    => $error->get_error_message()
				);

				return $return;
			}

			if ( empty( $this->filterStrings( $data['user_password'] ) ) ) {
				$error->add( 'invalid_user_password', __( 'The Password field is required!.', 'wp_users_handler' ) );
				$return = array(
					'status' => false,
					'msg'    => $error->get_error_message()
				);

				return $return;
			}

			if ( empty( $this->filterStrings( $data['user_password_confirm'] ) ) ) {
				$error->add( 'invalid_user_password_confirm', __( 'The Confirm Password field is required!.', 'wp_users_handler' ) );
				$return = array(
					'status' => false,
					'msg'    => $error->get_error_message()
				);

				return $return;
			}

			if ( strlen( $this->filterStrings( $data['user_login'] ) ) < 5 ) {
				$error->add( 'invalid_user_login_length', __( "The Username field can't be less than 5 characters.", 'wp_users_handler' ) );
				$return = array(
					'status' => false,
					'msg'    => $error->get_error_message()
				);

				return $return;
			}

			if ( strlen( $this->filterStrings( $data['user_password'] ) ) < 8 ) {
				$error->add( 'invalid_password_length', __( "The Password field can't be less than 5 characters", 'wp_users_handler' ) );
				$return = array(
					'status' => false,
					'msg'    => $error->get_error_message()
				);

				return $return;
			}

			if ( ! preg_match( '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i', $this->filterStrings( $data['user_email'] ) ) ) {
				$error->add( 'invalid_email_regx', __( "Invalid E-mail Address", 'wp_users_handler' ) );
				$return = array(
					'status' => false,
					'msg'    => $error->get_error_message()
				);

				return $return;
			}

			if ( ! preg_match( "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/", $this->filterStrings( $data['user_password'] ) ) ) {
				$error->add( 'invalid_password_regx', __( "The password should be at least 8 characters long, use upper and lower case letters, numbers, and symbols like ! \" ? $ % ^ &.", 'wp_users_handler' ) );
				$return = array(
					'status' => false,
					'msg'    => $error->get_error_message()
				);

				return $return;
			}


			if ( $this->filterStrings( $data['user_password'] ) !== $this->filterStrings( $data['user_password_confirm'] ) ) {
				$error->add( 'identical_password', __( "The Passwords are not identical.", 'wp_users_handler' ) );
				$return = array(
					'status' => false,
					'msg'    => $error->get_error_message()
				);

				return $return;
			}

			if ( $this->filterStrings( $data['user_email'] ) !== $this->filterStrings( $data['user_email_confirm'] ) ) {
				$error->add( 'identical_email', __( "The Emails are not identical.", 'wp_users_handler' ) );
				$return = array(
					'status' => false,
					'msg'    => $error->get_error_message()
				);

				return $return;
			}

			return $return;
		}


		protected function filterStrings( $string ) {
			return (string) trim( filter_var( filter_var( $string, FILTER_SANITIZE_STRING ), FILTER_SANITIZE_FULL_SPECIAL_CHARS ) );
		}

		protected function sanitizeStrings( $string ) {
			return $this->filterStrings(strtolower( sanitize_text_field( $string ) ));
		}

		protected function convertRegistration($data) {
			$form = new \stdClass();
			$form->first_name = ucfirst($this->sanitizeStrings($data['first_name']));
			$form->last_name = ucfirst($this->sanitizeStrings($data['last_name']));
			$form->nickname = ucfirst($this->sanitizeStrings($data['first_name']));
			$form->display_name = ucfirst($this->sanitizeStrings($data['first_name'])) . " " . ucfirst($this->sanitizeStrings($data['last_name']));
			$form->username = $this->sanitizeStrings($data['user_login']);
			$form->email = $this->sanitizeStrings($data['user_email']);
			$form->password = $this->filterStrings($data['user_password']);
			$form->redirect_url = $this->filterStrings($data['_wp_http_referer']);
			return $form;
		}

	}