<?php
/**
*     core class for connecting main plugin file {fancy-slider.php} with other classes.
*     @package     fancy-slider
*     @subpackage  fancy-slider/includes
*     @since       0.1.0
*     @see         class-fancy-slider-admin.php
*                  class-fancy-slider-activate.php
*                  class-fancy-slider-deactivate.php
*                  class-fancy-slider-loader.php
*                  class-fancy-slider-public.php
**/


/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

if (! class_exists('Fancy_Slider') ){
	class Fancy_Slider {

		/**
		 * the handle of the class Fancy_Slider_Loader *
		 *@since 0.1.0
		 *@var class
		 *@access protected
		 *@see class-fancy-slider-loader.php
		**/
		protected $_loader;

		/**
		 * the handle of the class Fancy_Slider_Activate *
		 *@since 0.1.0
		 *@var class
		 *@access protected
		 *@see class-fancy-slider-activate.php
		**/
		protected $_activator;

		/**
		 * the handle of the class Fancy_Slider_Deactivate *
		 *@since 0.1.0
		 *@var class
		 *@access protected
		 *@see class-fancy-slider-deactivate.php
		**/
		protected $_deactivator;

		/**
		 * the handle of the class Fancy_Slider_Admin *
		 *@since 0.1.0
		 *@var class
		 *@access protected
		 *@see class-fancy-slider-admin.php
		**/
		protected $_admin;

		/**
		 * the handle of the class Fancy_Slider_Public *
		 *@since 0.1.0
		 *@var class
		 *@access protected
		 *@see class-fancy-slider-public.php
		**/
		protected $_public;

		/**
		 * the handle of the class Fancy_Slider_Widget *
		 *@since 0.1.0
		 *@var class
		 *@access protected
		 *@see class-fancy-slider-widget.php
		**/
		protected $_widget;
        public $_fn;
		/**
		 * the set of the plugin construct functionality, passing into plugin name and version for identification ,loading dependencies,instantiating classes used for hooking action later *
		 *@since 0.1.0
		 *@var function
		 *@param $name, $version
		 *@return void
		**/
		public function __construct($name,$version){
		    $this->load_dependency();
			$this->_admin   = new Fancy_Slider_Admin( $name ,$version  );
			$this->_public  = new Fancy_Slider_Public( $name ,$version );
			$this->_widget  = new Fancy_Slider_Widget();
			$this->_loader  = new Fancy_Slider_Loader();
		}

		/**
		 *loading a set of  dependencies*
		 *@since 0.1.0
		 *@var function
		 *@access private
		 *@return void
		**/
		private function load_dependency(){
			require_once( plugin_dir_path( __FILE__ ) . '../admin/class-fancy-slider-admin.php'   );
			require_once( plugin_dir_path( __FILE__ ) . '../public/class-fancy-slider-public.php' );
			require_once( plugin_dir_path( __FILE__ ) . 'class-fancy-slider-activate.php'         );
			require_once( plugin_dir_path( __FILE__ ) . 'class-fancy-slider-deactivate.php'       );
			require_once( plugin_dir_path( __FILE__ ) . 'class-fancy-slider-loader.php'           );
			require_once( plugin_dir_path( __FILE__ ) . 'widgets/class-fancy-slider-widget.php'           );
		}

		/**
		 * invoking function within instance of the class Fancy_Slider_Activate,acting activated plugin functionality *
		 *@since 0.1.0
		 *@var function
		 *@return void
		**/        
        public function plugin_activator(){
        	$this->_activator = new Fancy_Slider_Activate( $this->_admin );
        	$this->_activator->activator();
        }

		/**
		 * invoking function within instance of the class Fancy_Slider_Deactivate,acting deactivated plugin functionality *
		 *@since 0.1.0
		 *@var function
		 *@return void
		**/ 
        public function plugin_deactivator(){
        	$this->_deactivator = new Fancy_Slider_Deactivate();
        	$this->_deactivator->deactivator();
        }

		/**
		 * all actions hooked into wordpress *
		 *@since 0.1.0
		 *@var function
		 *@return void
		**/ 
		public function plugin_add_action(){
       
			/* hooked custom post function */
			$this->_loader->action_entry( 'init',$this->_admin->_handle['cpt_init_hook'] );
			
			/* hooked js/css  function */
			$this->_loader->action_entry( 'admin_enqueue_scripts',$this->_admin->_handle['scripts_enqueue_hook']         );
			$this->_loader->action_entry( 'wp_enqueue_scripts'   ,$this->_public->_handle['scripts_enqueue_hook']        );

			/*hooked  menu page*/
			$this->_loader->action_entry( 'admin_menu'           ,$this->_admin->_handle['menu_page_hook'],9               );
			
			/*hooked admin settings*/
			$this->_loader->action_entry( 'admin_init'           ,$this->_admin->_handle['menu_page_settings_init_hook'] );
            
            /*hooked short code ,delay its process till wp has been initialized*/
			$this->_loader->action_entry( 'init'                 ,$this->_admin->_handle['short_code_register_hook']      );

			/* hooked tinymce button */
			$this->_loader->action_entry( 'admin_init'            ,$this->_admin->_handle['tinymce_button_register_hook'] );
		}

 		/**
		 * all filters hooked into wordpress *
		 *@since 0.1.0
		 *@var function
		 *@return void
		**/        
		public function plugin_add_filter(){
			/* fancy_slider_options localize */
		    $this->_loader->filter_entry( 'fancy_slider_localize', $this->_admin->_handle['get_options_hook'] );
		}
        
	}
}