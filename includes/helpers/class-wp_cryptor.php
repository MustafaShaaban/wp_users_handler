<?php

    /**
     * Class Wp_cryptor
     */

    namespace UH\CRYPTOR;

    class Wp_cryptor
    {
        // DECLARE THE REQUIRED VARIABLES
        public static $ENC_METHOD = "AES-256-CBC"; // THE ENCRYPTION METHOD.
        public static $ENC_KEY = "SOME_RANDOM_KEY"; // ENCRYPTION KEY
        public static $ENC_IV = "SOME_RANDOM_IV"; // ENCRYPTION IV.
        public static $ENC_SALT = "xS$"; // THE SALT FOR PASSWORD ENCRYPTION ONLY.

        // DECLARE  REQUIRED VARIABLES TO CLASS CONSTRUCTOR
        function __construct($METHOD = NULL, $KEY = NULL, $IV = NULL, $SALT = NULL)
        {
            try {
                // Setting up the Encryption Method when needed.
                self::$ENC_METHOD = (isset($METHOD) && !empty($METHOD) && $METHOD != NULL) ?
                    $METHOD : self::$ENC_METHOD;
                // Setting up the Encryption Key when needed.
                self::$ENC_KEY = (isset($KEY) && !empty($KEY) && $KEY != NULL) ?
                    $KEY : self::$ENC_KEY;
                // Setting up the Encryption IV when needed.
                self::$ENC_IV = (isset($IV) && !empty($IV) && $IV != NULL) ?
                    $IV : self::$ENC_IV;
                // Setting up the Encryption IV when needed.
                self::$ENC_SALT = (isset($SALT) && !empty($SALT) && $SALT != NULL) ?
                    $SALT : self::$ENC_SALT;
            } catch (\Exception $e) {
                return "Caught exception: ".$e->getMessage();
            }

        }

        // THIS FUNCTION WILL ENCRYPT THE PASSED STRING
        public static function Encrypt($string)
        {
            try {
                $output = false;
                $key    = hash('sha256', self::$ENC_KEY);
                // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
                $iv     = substr(hash('sha256', self::$ENC_IV), 0, 16);
                $output = openssl_encrypt($string, self::$ENC_METHOD, $key, 0, $iv);
                $output = base64_encode($output);
                return $output;
            } catch (\Exception $e) {
                return "Caught exception: ".$e->getMessage();
            }
        }

        // THIS FUNCTION WILL DECRYPT THE ENCRYPTED STRING.
        public static function Decrypt($string)
        {
            try {
                $output = false;
                // hash
                $key = hash('sha256', self::$ENC_KEY);
                // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
                $iv     = substr(hash('sha256', self::$ENC_IV), 0, 16);
                $output = openssl_decrypt(base64_decode($string), self::$ENC_METHOD, $key, 0, $iv);
                return $output;
            } catch (\Exception $e) {
                return "Caught exception: ".$e->getMessage();
            }
        }

        // THIS FUNCTION FOR URL ONLY.
        public static function DecryptURL($url)
        {
            try {
                if (empty($url)) {
                    return array();
                }
                $decrypted_url = self::Decrypt($url);
                parse_str($decrypted_url, $url_data);
                return $url_data;
            } catch (\Exception $e) {
                return "Caught exception: ".$e->getMessage();
            }
        }

        // THIS FUNCTION FOR PASSWORDS ONLY, BECAUSE IT CANNOT BE DECRYPTED IN FUTURE.
        public static function EncryptPassword($Input)
        {
            try {
                if (!isset($Input) || $Input == null || empty($Input)) {
                    return false;
                }
                // GENERATE AN ENCRYPTED PASSWORD SALT
                $SALT = self::Encrypt(self::$ENC_SALT);
                $SALT = md5($SALT);
                // PERFORM MD5 ENCRYPTION ON PASSWORD SALT.
                // ENCRYPT PASSWORD
                $Input = md5(self::Encrypt(md5($Input)));
                $Input = self::Encrypt($Input);
                $Input = md5($Input);
                // PERFORM ANOTHER ENCRYPTION FOR THE ENCRYPTED PASSWORD + SALT.
                $Encrypted = self::Encrypt($SALT).self::Encrypt($Input);
                $Encrypted = sha1($Encrypted.$SALT);
                // RETURN THE ENCRYPTED PASSWORD AS MD5
                return md5($Encrypted);
            } catch (\Exception $e) {
                return "Caught exception: ".$e->getMessage();
            }
        }
    }

    new Wp_cryptor();