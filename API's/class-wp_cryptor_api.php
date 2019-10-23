<?php

    class Ms_api_cryptor
    {
        use \UH\FUNCTIONS\Wp_functions;
        
        protected $method = 'aes-128-ctr'; // default cipher method if none supplied
        private $key;

        protected function iv_bytes()
        {
            return openssl_cipher_iv_length($this->method);
        }

        public function __construct($key = FALSE, $method = FALSE)
        {
            if (!$key) {
                $key = md5('Emk!On&HSGdMtN)S1%Jx*O4j'); // default encryption key if none supplied
            }
            if (ctype_print($key)) {
                // convert ASCII keys to binary format
                $this->key = openssl_digest($key, 'SHA256', TRUE);
            } else {
                $this->key = $key;
            }
            if ($method) {
                if (in_array(strtolower($method), openssl_get_cipher_methods())) {
                    $this->method = $method;
                } else {
                    die(__METHOD__.": unrecognised cipher method: {$method}");
                }
            }
            add_action('rest_api_init', array($this, 'MS_API_cryptor'));
        }

        public function MS_API_cryptor($request)
        {
            /**
             * Handle encryption request.
             */
            register_rest_route('MSAPI', 'cryptor/(?P<type>\S+)', array(
                'methods'  => 'POST',
                'callback' => array($this, 'MS_API_handle_request'),
            ));
        }

        public function MS_API_handle_request($request = null)
        {
            $error       = new WP_Error();
            $response    = array();
            $url_params  = $request->get_url_params();
            $body_params = $request->get_body_params();
            if (empty($url_params['type'])) {
                $error->add(400, "Please provide the correct type!", array('status' => 200));
                return $error;
            }
            if (empty($body_params['data'])) {
                $error->add(400, "Data can't be empty!", array('status' => 200));
                return $error;
            }
            switch ($url_params['type']) {
                case 'enc':
                    $data     = $this->encrypt($body_params['data']);
                    $response = [
                        'code' => 200,
                        'data' => $data,
                        'msg'  => 'Data has been encrypted successfully',
                    ];
                    break;
                case 'dec':
                    $data     = $this->decrypt($body_params['data']);
                    $response = [
                        'code' => 200,
                        'data' => $data,
                        'msg'  => 'Data has been decrypted successfully',
                    ];
                    break;
                default:
                    $error->add(400, "Please provide the correct type!", array('status' => 200));
                    return $error;
            }
            return new WP_REST_Response($response, 123);
        }


        // encrypt encrypted string
        public function encrypt($data)
        {
            $iv = openssl_random_pseudo_bytes($this->iv_bytes());
            if (is_array($data)) {
                $data = serialize($data);
            }
            $enc = bin2hex($iv).openssl_encrypt($data, $this->method, $this->key, 0, $iv);
            return $enc;
        }

        // decrypt encrypted string
        public function decrypt($data)
        {
            $iv_strlen = 2 * $this->iv_bytes();
            if (preg_match("/^(.{".$iv_strlen."})(.+)$/", $data, $regs)) {
                list(, $iv, $crypted_string) = $regs;
                if (ctype_xdigit($iv) && strlen($iv) % 2 == 0) {
                    $dec = openssl_decrypt($crypted_string, $this->method, $this->key, 0, hex2bin($iv));
                    return unserialize($dec);
                }
            }
            return FALSE; // failed to decrypt
        }

        public function isJson($string)
        {
            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        }
    }

    class Wp_cryptor_api
    {
        // DECLARE THE REQUIRED VARIABLES
        private $ENC_METHOD = "AES-256-CBC"; // THE ENCRYPTION METHOD.
        private $ENC_KEY = "SOME_RANDOM_KEY"; // ENCRYPTION KEY
        private $ENC_IV = "SOME_RANDOM_IV"; // ENCRYPTION IV.
        private $ENC_SALT = "xS$"; // THE SALT FOR PASSWORD ENCRYPTION ONLY.

        // DECLARE  REQUIRED VARIABLES TO CLASS CONSTRUCTOR
        function __construct($METHOD = NULL, $KEY = NULL, $IV = NULL, $SALT = NULL)
        {
            add_action('rest_api_init', array($this, 'wp_cryptor'));

            try {
                // Setting up the Encryption Method when needed.
                $this->ENC_METHOD = (isset($METHOD) && !empty($METHOD) && $METHOD != NULL) ?
                    $METHOD : $this->ENC_METHOD;
                // Setting up the Encryption Key when needed.
                $this->ENC_KEY = (isset($KEY) && !empty($KEY) && $KEY != NULL) ?
                    $KEY : $this->ENC_KEY;
                // Setting up the Encryption IV when needed.
                $this->ENC_IV = (isset($IV) && !empty($IV) && $IV != NULL) ?
                    $IV : $this->ENC_IV;
                // Setting up the Encryption IV when needed.
                $this->ENC_SALT = (isset($SALT) && !empty($SALT) && $SALT != NULL) ?
                    $SALT : $this->ENC_SALT;
            } catch (\Exception $e) {
                $error = new WP_Error();
                $error->add(400, "Caught exception: ".$e->getMessage(), array('status' => 200));
                return new WP_REST_Response($error, 123);
            }

        }

        public function wp_cryptor($request)
        {
            /**
             * Handle encryption request.
             */
            register_rest_route($this->plugin_key(), 'cryptor/(?P<type>\S+)', array(
                'methods'  => 'POST',
                'callback' => array($this, 'handle_request'),
            ));
        }

        public function handle_request($request = null)
        {
            $error       = new WP_Error();
            $response    = array();
            $url_params  = $request->get_url_params();
            $body_params = $request->get_body_params();


            if (empty($url_params['type'])) {
                $error->add(400, "Please provide the correct type!", array('status' => 200));
                return $error;
            }
            if (empty($body_params['data'])) {
                $error->add(400, "Data can't be empty!", array('type_empty' => 200));
                return $error;
            }



            switch ($url_params['type']) {
                case 'enc':
                    $data     = $this->Encrypt($body_params['data']);
                    $response = [
                        'code' => 200,
                        'data' => $data,
                        'msg'  => 'Data has been encrypted successfully',
                    ];
                    break;
                case 'enc_pass':
                    $data     = $this->EncryptPassword($body_params['data']);
                    $response = [
                        'code' => 200,
                        'data' => $data,
                        'msg'  => 'Password has been encrypted successfully',
                    ];
                    break;
                case 'dec':
                    $data     = $this->Decrypt($body_params['data']);
                    $response = [
                        'code' => 200,
                        'data' => $data,
                        'msg'  => 'Data has been decrypted successfully',
                    ];
                    break;
                case 'dec_url':
                    $data     = $this->DecryptURL($body_params['data']);
                    $response = [
                        'code' => 200,
                        'data' => $data,
                        'msg'  => 'URL has been decrypted successfully',
                    ];
                    break;
                default:
                    $error->add(400, "Please provide a correct type!", array('status' => 200));
                    return $error;
            }
            return new WP_REST_Response($response, 123);
        }

        // THIS FUNCTION WILL ENCRYPT THE PASSED STRING
        private function Encrypt($string)
        {
            try {
                $output = false;
                $key    = hash('sha256', $this->ENC_KEY);
                // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
                $iv     = substr(hash('sha256', $this->ENC_IV), 0, 16);
                $output = openssl_encrypt($string, $this->ENC_METHOD, $key, 0, $iv);
                $output = base64_encode($output);
                return $output;
            } catch (\Exception $e) {
                return "Caught exception: ".$e->getMessage();
            }
        }

        // THIS FUNCTION FOR PASSWORDS ONLY, BECAUSE IT CANNOT BE DECRYPTED IN FUTURE.
        private function EncryptPassword($Input)
        {
            try {
                if (!isset($Input) || $Input == null || empty($Input)) {
                    return false;
                }
                // GENERATE AN ENCRYPTED PASSWORD SALT
                $SALT = $this->Encrypt($this->ENC_SALT);
                $SALT = md5($SALT);
                // PERFORM MD5 ENCRYPTION ON PASSWORD SALT.
                // ENCRYPT PASSWORD
                $Input = md5($this->Encrypt(md5($Input)));
                $Input = $this->Encrypt($Input);
                $Input = md5($Input);
                // PERFORM ANOTHER ENCRYPTION FOR THE ENCRYPTED PASSWORD + SALT.
                $Encrypted = $this->Encrypt($SALT).$this->Encrypt($Input);
                $Encrypted = sha1($Encrypted.$SALT);
                // RETURN THE ENCRYPTED PASSWORD AS MD5
                return md5($Encrypted);
            } catch (\Exception $e) {
                return "Caught exception: ".$e->getMessage();
            }
        }

        // THIS FUNCTION WILL DECRYPT THE ENCRYPTED STRING.
        private function Decrypt($string)
        {
            try {
                $output = false;
                // hash
                $key = hash('sha256', $this->ENC_KEY);
                // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
                $iv     = substr(hash('sha256', $this->ENC_IV), 0, 16);
                $output = openssl_decrypt(base64_decode($string), $this->ENC_METHOD, $key, 0, $iv);
                return $output;
            } catch (\Exception $e) {
                return "Caught exception: ".$e->getMessage();
            }
        }

        // THIS FUNCTION FOR URL ONLY.
        private function DecryptURL($url)
        {
            try {
                if (empty($url)) {
                    return array();
                }
                $decrypted_url = $this->Decrypt($url);
                parse_str($decrypted_url, $url_data);
                return $url_data;
            } catch (\Exception $e) {
                return "Caught exception: ".$e->getMessage();
            }
        }


    }

    new Wp_cryptor_api();