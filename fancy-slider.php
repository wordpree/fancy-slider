<?php 
/**
 * Plugin Name: fancy slider
 * Plugin URI:  https://yijiang.com.au
 * Description: Easily slides your fonder
 * Version:     0.1.0
 * Author:      Hai
 * Author URI:  https://www.webite.me
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: fancy-slider
 * Domain Path: /languages
 * License:     GPL version 3
        * * * * * * * * * * * * * * * * * * * * *
    fancy slider is a wordpress plugin to slide your images
    Copyright (C) <2018>  <Hai Jun Wang>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
**/

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}
require_once plugin_dir_path( __FILE__ )   . 'includes/class-fancy-slider.php';
require_once plugin_dir_path( __FILE__ )   . 'includes/widgets/class-fancy-slider-widget.php';

define ('FANCY_SLIDER_VERSION','0.1.0');
define ('FANCY_SLIDER_NAME','fancy-slider');

$fancy_slider = new Fancy_Slider(FANCY_SLIDER_NAME,FANCY_SLIDER_VERSION);

/* activate or deactivate plugin */

register_activation_hook(   __FILE__, array( $fancy_slider ,'plugin_activator' ) );
register_deactivation_hook( __FILE__, array( $fancy_slider ,'plugin_deactivate') );

/* Hooks a function on to a specific action or filter action */
$fancy_slider ->plugin_add_action();
$fancy_slider ->plugin_add_filter();

add_action( 'admin_footer', 'my_action_javascript' ); // Write our JS below here

function my_action_javascript() { ?>
    <script type="text/javascript" >
    jQuery(document).ready(function($) {

        var data = {
            'action': 'my_action',
            'whatever': 1234
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajaxurl, data, function(response) {
            // alert('Got this from the server: ' + response);
        });
    });
    </script> <?php
}


add_action( 'wp_ajax_my_action', 'my_action' );

function my_action() {

    $whatever = intval( $_POST['whatever'] );

    $whatever += 10;

        echo $whatever;

}