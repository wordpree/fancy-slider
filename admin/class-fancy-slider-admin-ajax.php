<?php

/**
 * class  Fancy_Slider_Admin_Ajax, admin panel ajax response functionalities *
 *@since 0.1.0
 *@var class
 *@package fancy-slider
 *@subpackage fancy-slider/admin
 *@author Hai
**/

if ( ! defined('ABSPATH') ) {
	exit;
}
if ( ! class_exists( 'Fancy_Slider_Admin_Ajax' ) ) {
	class Fancy_Slider_Admin_Ajax {

	    public function __construct(){
		    add_action( 'wp_ajax_fs_newslider_create', array($this,'create_new_slider') );
	    }

	    public function create_new_slider(){
	    	$res = '';
	    	if ( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'fs-slider-panel' ) ) {
			    $res = json_encode( array( 'success' => false,'message' => __('Authorization Failed','fancy-slider' ) ) );
	    	}

	    	if ( !current_user_can( 'access_fancyslider' ) && !current_user_can( 'create_fancyslider' ) ) {
	    		$res = json_encode( array( 'success' => false,'message' => __('Advanced permission needed','fancy-slider' ) ) );
	    	}
	    	echo $res;
	    	wp_die();
	    }
	}
}

new Fancy_Slider_Admin_Ajax();