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

        protected function filterStrings($string)
        {
            return (string)trim(filter_var(filter_var($string, FILTER_SANITIZE_STRING), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        }

    }