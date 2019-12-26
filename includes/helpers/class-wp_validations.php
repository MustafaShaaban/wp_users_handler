<?php
    /**
     * Created by PhpStorm.
     * User: Mustafa Shaaban
     * Date: 8/26/2019
     * Time: 8:33 PM
     */

    namespace UH\VALIDATIONS;

    trait Wp_validations
    {
        public function __construct()
        {

        }

        protected function validate_controller($type, $data) {
            $error = new \WP_Error();

            switch ($type) {
                case 'login':
                    $return = $this->validate_login($data);
                    break;
                case 'register':
                    break;
                default:
                    $error->add('Invalid_validation_type', __('Invalid type', 'wp_users_handler'));
                    $return = array(
                        'status' => false,
                        'msg' => $error->get_error_message()
                    );
                    break;
            }

            return $return;
        }

        private function validate_login($data) {
            $error = new \WP_Error();
            $return = array(
                'status' => true,
            );

            if (!wp_verify_nonce($data['nonce'], $this->plugin_key().'_LOGIN_FORM')) {
                $error->add('invalid_nonce' , __("Your request can't be sent right now, Please come back later!.", "wp_users_handler"));
                wp_send_json(array(
                    'success' => false,
                    'msg' => $error->get_error_message(),
                ));
            }

            if (empty($this->filterStrings($data['user_login']))) {
                $error->add('Invalid_user_login', __('The Username/Email field is required!.', 'wp_users_handler'));
                $return = array(
                    'status' => false,
                    'msg' => $error->get_error_message()
                );
                return $return;
            }

            if (empty($this->filterStrings($data['user_password']))) {
                $error->add('Invalid_password', __('The password field is required!.', 'wp_users_handler'));
                $return = array(
                    'status' => false,
                    'msg' => $error->get_error_message()
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

        protected function filterStrings($string)
        {
            return (string)trim(filter_var(filter_var($string, FILTER_SANITIZE_STRING), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        }

    }