<?php

    /**
     * Fired during plugin deactivation
     *
     * @link       https://www.linkedin.com/in/mustafa-shaaban22/
     * @since      1.0.0
     *
     * @package    Wp_users_handler
     * @subpackage Wp_users_handler/includes
     */

    /**
     * Fired during plugin deactivation.
     *
     * This class defines all code necessary to run during the plugin's deactivation.
     *
     * @since      1.0.0
     * @package    Wp_users_handler
     * @subpackage Wp_users_handler/includes/core
     * @author     Mustafa Shaaban <mustafashaaban22@gmail.com>
     */

    namespace UH\DEACTIVATOR;

    class Wp_users_handler_Deactivator
    {

        /**
         * Short Description. (use period)
         *
         * Long Description.
         *
         * @since    1.0.0
         */
        public static function deactivate()
        {
	        flush_rewrite_rules();

        }

    }
