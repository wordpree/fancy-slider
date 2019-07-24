<?php 

/**
* Class designed here to fulfill functionality after plugin activating
* @package fancy-slider
* @subpackage fancy-slider/includes
* @since  0.1.0
* @author Hai 
**/

/* accessed directly ,exit anyway */
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

if ( ! class_exists( 'Fancy_Slider_Activate' ) ){

	class Fancy_Slider_Activate {
		
        /**
		 * class Fancy_Slider_Admin handle *
		 *@since 0.1.0
		 *@var class
		**/     
		public $_admin;
		
        /**
		 * built-in construct function for passing into class handle*
		 *@since 0.1.0
		 *@var class
		 *@param Fancy_Slider_Admin class handle 
		**/  
        public function __construct( $admin ){
		    $this->_admin = $admin;
        }

        /**
		 * called wordpress custom post type init and get permalinks worked *
		 *@since 0.1.0
		 *@var function
		 *@return void
		**/ 
        public function activator(){
	        $this->_admin->_handle['cpt_init_hook'];
	        flush_rewrite_rules();
	        $this->_admin->create_cap();
        }

		
	}


}
