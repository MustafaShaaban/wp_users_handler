<?php
    /**
     * Created by PhpStorm.
     * User: Mustafa Shaaban
     * Date: 10/20/2019
     * Time: 11:39 PM
     */

    namespace UH\FUNCTIONS;


    trait Wp_functions
    {
        public function __construct()
        {

        }

        public function get_admin_template($name){
            return include PLUGIN_PATH.'admin/templates/'.$name.'.php';
        }

        public function get_public_template($name){
            return include PLUGIN_PATH.'public/templates/'.$name.'.php';
        }
    }