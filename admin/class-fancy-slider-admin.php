<?php  

/**
 * class  Fancy_Slider_Admin,all related to wordpress admin page functionalities *
 *@since 0.1.0
 *@var class
 *@package fancy-slider
 *@subpackage fancy-slider/admin
 *@author Hai
**/
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
require_once plugin_dir_path( __FILE__ ) . 'view/fancy-slider-settings.php' ;
if( ! class_exists( 'Fancy_Slider_Admin' )) {

    class Fancy_Slider_Admin  {

        /**
        * name identifier particpating into wordpress actions *
        *@since 0.1.0
        *@var string
        *@access protected
        **/
        protected static $_name; 

        /**
        * version identifier particpating into wordpress actions *
        *@since 0.1.0
        *@var string
        *@access protected
        **/
        protected static $_version;

        /**
        * function reference array handle * 
        *@since 0.1.0
        *@var string
        *@access protected
        **/
        public $_handle;
        
        /**
        * construct function for obtaining name ,version identifiers and handle init *
        *@since 0.1.0
        *@var function
        *@return void
        *@param $name,$version
        **/
        public function __construct($name,$version){

            self::$_name    = $name;
            self::$_version = $version;
            $this->admin_entry();
            $this->admin_dependency();
            $this->create_cap();
        } 

        /**
        * function to load dependency connected to admin  *
        *@since 0.1.0
        *@var function
        *@return void
        *@access private
        **/
        private  function admin_dependency(){
            require_once ( plugin_dir_path( __FILE__ ) . 'class-fancy-slider-admin-ajax.php' );
        }

        /**
        * function to register a new custom post type  *
        *@since 0.1.0
        *@var function
        *@return void
        *@access private
        **/
        private  function custom_post_type_init(){
            $labels =array(
                'name'          => __('Sliders','fancy-slider'),
                'singular_name' => __('Slider','fancy-slider'),
                'menu_name'     => __('Fancy Sliders','fancy-slider'),
                'add_new'       => __('Create Slider','fancy-slider'),
                'add_new_item'  => __('Creat New Slider','fancy-slider'),
                'new_item'      => __('New Slider','fancy-slider'),
                'edit_item'     => __('Edit slider','fancy-slider'),
                'view_item'     => __('View Slider','fancy-slider'),
                'all_items'     => __('All Sliders','fancy-slider'),
                'search_items'  => __('Search Sliders','fancy-slider'),         
                );    
            $args =array( 'labels'          => $labels,
            		  'public'             => false,
            		  'publicly_queryable' => true,
            		  'show_ui'            => true,
            		  'show_in_menu'       => true,
            		  'query_var'          => true,
            		  'rewrite'            => array( 'slug' => 'slider' ),
            		  'capability_type'    => 'post',
            		  'has_archive'        => true,
            		  'hierarchical'       => false,
            		  'menu_icon'     => 'dashicons-format-gallery',
            		  'supports'      => array('title'),
                      'menu_position' => 80,
            		);         
            register_post_type('fancy_slider',$args);
        }

        /**
        * function to act as shortcoade callback *
        *@since 0.1.0
        *@var function
        *@return html string
        *@access public
        *set to private will cause warning : Attempting to parse a shortcode without a valid callback: fancy_slider_short 
        **/
        public function short_code_callback($atts){
                $pairs = array('class' => 'fancy-slider-sc');
                $atts = shortcode_atts( $pairs, $atts );
                $fancy_slider_posts = get_posts( array( 'post_type' =>'fancy_slider' ) );
                $url = '<div class='.$pairs['class'].'>';
                foreach ( $fancy_slider_posts as $post ){
                    setup_postdata( $post );
                    $feature_img = get_post_thumbnail_id( $post->ID );
                    if ( $feature_img ){
                        $img = wp_get_attachment_image_src( $feature_img ,'full');
                        $url .= "<div> <img src=" . esc_attr( $img[0] ) . "> </div>";
                    }          
                }
                $url .= '</div>';
                wp_reset_postdata();
                return $url;
        }

        /**
        * function to register shortcode  *
        *@since 0.1.0
        *@var function
        *@return void
        *@access private
        **/
        private function short_code_register(){
            add_shortcode( 'fancy_slider_short', array( $this,'short_code_callback' ) );
        }

        /**
        * function to init new button  *
        *@since 0.1.0
        *@var function
        *@return array
        *@access private
        **/
        public function mce_button_init($button){
            array_push($button, 'fancy_slider_button');
            return $button;
        }

        /**
        * function to load js file  *
        *@since 0.1.0
        *@var function
        *@return array
        *@access private
        **/
        public function mce_button_js_init($plugin_array){
            $plugin_array['fancySlider'] = plugins_url( 'js/tinymce/fancy-slider-button.js', __FILE__ );
            return $plugin_array;
        }

        /**
        * function to register tinymce  *
        *@since 0.1.0
        *@var function
        *@return void
        *@access private
        **/
        private function tinymce_button_register(){
            if ( current_user_can( 'edit_pages' ) && current_user_can( 'edit_pages' ) ) {
                add_filter( 'mce_buttons', array($this,'mce_button_init') );
                add_filter( 'mce_external_plugins',array($this,'mce_button_js_init') );
            }
            
        }

         /**
        * function to create custom cap in wp role  *
        *@since 0.1.0
        *@var function
        *@return void
        *@access public
        **/
        public function create_cap(){
           $admin_role  = get_role( 'administrator' );
           $editor_role = get_role( 'editor' );
           $admin_role->add_cap('access_fancyslider');
           $editor_role->add_cap('create_fancyslider');         
           $editor_role->add_cap('delete_fancyslider');
           $editor_role->add_cap('publish_fancyslider');
        } 
        
        /**
        * function to enqueue new scripts  *
        *@since 0.1.0
        *@var function
        *@return void
        *@access private
        **/
        private  function scripts_enqueue(){
            wp_enqueue_script( self::$_name, plugin_dir_url( __FILE__ ) . 'js/fancy-slider-admin.js', array( 'jquery' ),self::$_version , true );
            wp_enqueue_script( 'jquery-ui-dialog' );
        }

        /**
        * function to enqueue new styles  *
        *@since 0.1.0
        *@var function
        *@return void
        *@access private
        **/
        private  function styles_enqueue(){
            wp_enqueue_style( self::$_name, plugin_dir_url( __FILE__ ) . 'css/fancy-slider-admin.css', array(), self:: $_version, 'all' );
            wp_enqueue_style( self::$_name . 'jq-ui', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css', array(), self:: $_version, 'all' );
        }
        

        /**
        * function to enqueue media scripts  *
        *@since 0.1.0
        *@var function
        *@return void
        *@access private
        **/
        private function media_enqueue(){
           wp_enqueue_media();
        } 

        /**
        * function to creat new menu page within dash panel  *
        *@since 0.1.0
        *@var function
        *@return void
        *@param add_menu_page ($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, null )
        *@access private
        **/
        private function menu_page(){
            add_menu_page( 'Fancy Slider', 'Fancy Slider', 'manage_options', 'fancy-slider', 'fs_menu_page_cb', 'dashicons-format-gallery', null );
        } 

        /**
        * function to creat new submenu page within dash panel  *
        *@since 0.1.0
        *@var function
        *@return void
        *@param $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $callable
        *@access private
        **/
        private function submenu_page_all(){
            add_submenu_page( 'fancy-slider','All Sliders', 'All Sliders', 'manage_options', 'fancy-slider', '' );
        } 

        /**
        * function to creat new submenu page within dash panel  *
        *@since 0.1.0
        *@var function
        *@return void
        *@param $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $callable
        *@access private
        **/
        private function submenu_page_create(){
            add_submenu_page( 'fancy-slider','Create Slider', 'Create Slider', 'manage_options', 'fancy-slider-create', 'submenu_page_cb' );
        } 

        /**
        * function to add settings section and field,register setting *
        *@since 0.1.0
        *@var function
        *@return void
        *@param 
        *@access private
        **/
        private function menu_page_settings_init(){
            $settings = fs_settings_field();
            if ( is_array( $settings  ) ){
                foreach ($settings  as $section => $data) {

                    /* add_settings_section($id, $title, $callback, $page) */
                    add_settings_section( $section, $data['title'], 'fs_section_callback_' . $section, $data['page']);
                    $option_group = $data['option_group'];
                    foreach ( $data['fields'] as $field) {
                        $option_name =  $field['id'];                       
                        /* register_setting($option_group_name,$option_name,$sanitize_callback) */
                        register_setting( $option_group ,$option_name ,array('sanitize_callback' => $field['scb']) );
                        /*  add_settings_field( $id, $title, $callback, $page, $section, $args) */
                        add_settings_field( $field['id'],$field['sub_title'],$data['fcb'],$data['page'],$section,array( 'field'=>$field,'section'=>$section ) );
                    }
                }

            }
        }

        /**
        * function to be used as callable name hooked onto add filter *
        *@since 0.1.0
        *@var function
        *@return array      
        *@access private
        **/
        private function get_options(){
            $fancy_slider_options;
            $options = array(
                'wpfs_standard',
                'wpfs_lazyload',
                'wpfs_centre',
                'wpfs_autoplay',
                'wpfs_animation',
                'wpfs_format',
                'wpfs_sync',
                'wpfs_bp_ac',
                'wpfs_bp_xl',
                'wpfs_bp_l',
                'wpfs_bp_m',
                'wpfs_bp_s',              
            );
            foreach ( $options as $value ) {
                $key = str_replace('wpfs_', '', $value);
                $fancy_slider_options[$key] = get_option( $value );                
            }
            return $fancy_slider_options;
        }

         /**
        * variable function to be used as callable name hooked onto wp actions  *
        *@since 0.1.0
        *@var function
        *@return void      
        *@access protected
        **/
        protected function admin_entry(){
            $this->_handle = array(

                'cpt_init_hook' => function(){
                    $this->custom_post_type_init();
                },
                'scripts_enqueue_hook' => function($hook){
                    if( $hook === 'toplevel_page_fancy-slider' || $hook === 'fancy-slider_page_fancy-slider-create') {
                        $this->styles_enqueue();
                        $this->scripts_enqueue(); 
                        $this->media_enqueue();
                    }                  
                },
                'menu_page_hook' => function(){
                    $this->menu_page();
                    $this->submenu_page_all();
                    $this->submenu_page_create();
                },
                'sub_menu_page_hook' => function(){
                    $this->sub_menu_page();
                },
                'menu_page_settings_init_hook' => function(){
                    $this->menu_page_settings_init();
                },
                'get_options_hook' => function(){
                    return $this->get_options();
                },
                'short_code_register_hook' => function(){
                    $this->short_code_register();
                },
                'tinymce_button_register_hook' => function(){
                    $this->tinymce_button_register();
                }
            );
        
        }

       
    }
}