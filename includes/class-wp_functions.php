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
        /**
         * The unique identifier of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $plugin_name The string used to uniquely identify this plugin.
         */
        private $plugin_name;
        private $plugin_key;
        private $plugin_path;
        private $plugin_url;
        private $plugin_title;

        /**
         * The current version of the plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $version The current version of the plugin.
         */
        private $plugin_version;

        public function get_admin_template($name)
        {
            return include WP_USERS_HANDLER_PLUGIN_PATH.'admin/templates/'.$name.'.php';
        }

        public function get_public_template($name)
        {
            return include WP_USERS_HANDLER_PLUGIN_PATH.'public/templates/'.$name.'.php';
        }

        /**
         * The name of the plugin used to uniquely identify it within the context of
         * WordPress and to define internationalization functionality.
         *
         * @since     1.0.0
         * @return    string    The name of the plugin.
         */
        public function plugin_name(){
            $this->plugin_name  = WP_USERS_HANDLER_PLUGIN_NAME;
            return $this->plugin_name;
        }
        public function plugin_key(){
            $this->plugin_key  = WP_USERS_HANDLER_PLUGIN_KEY;
            return $this->plugin_key;
        }
        public function plugin_path(){
            $this->plugin_path  = WP_USERS_HANDLER_PLUGIN_PATH;
            return $this->plugin_path;
        }
        public function plugin_url(){
            $this->plugin_url  = WP_USERS_HANDLER_PLUGIN_URL;
            return $this->plugin_url;
        }
        public function plugin_title(){
            $this->plugin_title  = WP_USERS_HANDLER_PLUGIN_TITLE;
            return $this->plugin_title;
        }

        /**
         * Retrieve the version number of the plugin.
         *
         * @since     1.0.0
         * @return    string    The version number of the plugin.
         */
        public function plugin_version(){
            $this->plugin_version  = WP_USERS_HANDLER_VERSION;
            return $this->plugin_version;
        }

	    public function page_404() {
		    global $wp_query;
		    $wp_query->set_404();
		    status_header( 404 );
		    get_template_part( 404 );
		    exit();
	    }

	    public function get_page_url($name, $type='slug') {
		    $link = '';
        	if (!empty($name)) {
        		switch ($type) {
			        case 'slug':
			        	$link = get_permalink( get_page_by_path( $name ) );
			        	break;
			        case 'title':
			        	$link = get_permalink( get_page_by_title( $name ) );
				        break;
			        case 'ID':
			        	$link = get_permalink( $name );
				        break;
			        default:
				        break;
		        }
	        }
		    return $link;
	    }
    }