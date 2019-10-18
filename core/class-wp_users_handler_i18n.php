<?php

    /**
     * Define the internationalization functionality
     *
     * Loads and defines the internationalization files for this plugin
     * so that it is ready for translation.
     *
     * @link       https://www.linkedin.com/in/mustafa-shaaban22/
     * @since      1.0.0
     *
     * @package    Wp_users_handler
     * @subpackage Wp_users_handler/includes
     */

    /**
     * Define the internationalization functionality.
     *
     * Loads and defines the internationalization files for this plugin
     * so that it is ready for translation.
     *
     * @since      1.0.0
     * @package    Wp_users_handler
     * @subpackage Wp_users_handler/includes
     * @author     Mustafa Shaaban <mustafashaaban22@gmail.com>
     */

    namespace UH\TEXT_DOMAIN;

    class Wp_users_handler_i18n
    {


        /**
         * Load the plugin text domain for translation.
         *
         * @since    1.0.0
         */
        public function load_plugin_textdomain()
        {

            load_plugin_textdomain(
                'wp_users_handler',
                false,
                dirname(dirname(plugin_basename(__FILE__))).'/languages/'
            );

        }


    }
