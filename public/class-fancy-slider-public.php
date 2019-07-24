<?php 

/**
 * Class  Fancy_Slider_Public,all related to wordpress none-admin page functionalities *
 *@since 0.1.0
 *@var class
 *@package fancy-slider
 *@subpackage fancy-slider/public
 *@author Hai
**/

if (! class_exists( 'Fancy_Slider_Public' ) ) {

	class Fancy_Slider_Public {
        /**
        * name identifier particpating into wordpress actions *
        *@since 0.1.0
        *@var string
        *@access protected
        **/		
	    protected static $_name;

        /**
        * name identifier particpating into wordpress actions *
        *@since 0.1.0
        *@var string
        *@access protected
        **/	    
	    protected static $_version;

        /**
        * prefix of name identifier  *
        *@since 0.1.0
        *@var string
        *@access protected
        **/         
        protected static $_vendor = array('slick');

        /**
        * function reference array handle * 
        *@since 0.1.0
        *@var array
        **/
        public $_handle;

        /**
        * construct function for obtaining name and version identifiers *
        *@since 0.1.0
        *@var function
        *@return void
        *@param $name,$version
        **/
	    public function __construct($name ,$version){
			self::$_name = $name;
			self::$_version = $version;
            $this->public_entry();
	    }

        /**
        * function to enqueue new styles  *
        *@since 0.1.0
        *@var function
        *@return void
        *@access private
        **/
	    private  function styles_enqueue(){

	    	wp_enqueue_style( self::$_name, plugin_dir_url( __FILE__ ) . 'css/fancy-slider-public.css', array(), self::$_version, 'all' );
            wp_enqueue_style( self::$_vendor[0] . '-' . self::$_name , plugin_dir_url( __FILE__ ) . 'css/vendor/slick.css', array(), self::$_version, 'all' );
	    }

        /**
        * function to enqueue new scripts  *
        *@since 0.1.0
        *@var function
        *@return void
        *@access private
        **/
	    private  function scripts_enqueue(){
	    	wp_enqueue_script( self::$_name, plugin_dir_url( __FILE__ ) . 'js/fancy-slider-public.js', array('jquery'), self::$_version, true);
            wp_enqueue_script( self::$_vendor[0] . '-' . self::$_name , plugin_dir_url( __FILE__ ) . 'js/vendor/slick.min.js', array('jquery'), self::$_version, true);
	    }
	    

         /**
        * variable function to be used as callable name hooked onto wp actions  *
        *@since 0.1.0
        *@var function
        *@return void      
        *@access protected
        **/
        protected function public_entry(){
            $this->_handle = array(
                'scripts_enqueue_hook' => function(){
                    $this->styles_enqueue();
                    $this->scripts_enqueue(); 
                }
            );
        
        }


	}
}