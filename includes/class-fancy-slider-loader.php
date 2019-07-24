<?php

/**
* Class designed here to fulfill functionality of hooking a function on to a specific filter action or action 
* @package fancy-slider
* @subpackage fancy-slider/includes
* @since  0.1.0
* @author Hai
**/

if ( ! class_exists('Fancy_Slider_Loader') ) {

	class Fancy_Slider_Loader {
        
        /**
		 * hooked action *
		 *@since 0.1.0
		 *@var function
		 *@param $tag -- string, $object -- class, $function_to_add -- function, $priority -- int, $args -- int
		 *@return void
		**/  
		public  function action_entry( $tag, $function_to_add, $priority = 10, $args = 1  ) {
		    add_action( $tag, $function_to_add , $priority, $args);
		}

        /**
		 * hooked filter action *
		 *@since 0.1.0
		 *@var function
		 *@param $tag -- string, $object -- class, $function_to_add -- function, $priority -- int, $args -- int
		 *@return void
		**/  
		public  function filter_entry( $tag, $function_to_add, $priority = 10, $args = 1  ) {
			add_filter( $tag, $function_to_add, $priority, $args);
		}
	}
}