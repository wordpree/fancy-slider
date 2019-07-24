<?php

/**
* Class designed here to fulfill functionality after plugin deactivating
* @package fancy-slider
* @subpackage fancy-slider/includes
* @since  0.1.0
* @author Hai
**/

if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

if ( ! class_exists('Fancy_Slider_Deactivate') ) {

	class Fancy_Slider_Deactivate {

        /**
		 * actions after plugin deactivated -- unregister custom post type and refresh permalinks *
		 *@since 0.1.0
		 *@var function
		 *@return void
		**/   
		public  function deactivator(){
			unregister_post_type('fancy_slider');
			flush_rewrite_rules();
		}
	}
}
