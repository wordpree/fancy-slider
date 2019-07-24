<?php 

/**
* Class designed here to fulfill functionality of fancy slider widget
* @package fancy-slider
* @subpackage fancy-slider/includes/widgets
* @var class
* @since  0.1.0
* @author Hai 
**/

class Fancy_Slider_Widget extends WP_Widget {

    /**
	 * register widget *
	 *@since 0.1.0
	 *@var function
	**/   
	public function __construct(){

		parent::__construct('fancy-slider','Fancy Slider',array('description' => 'Slide Your Images'));
		add_action( 'widgets_init', function(){
			register_widget('Fancy_Slider_Widget');
		} );	
	}

    /**
	 * display widget outputs on front-end area *
	 *@since 0.1.0
	 *@var function
	**/
	public function widget($args, $instance){
		
		if ( is_front_page() ){
			
			$fancy_slider_posts = get_posts( array( 'post_type' =>'fancy_slider' ) );
			$url = '<div class="fancy-slider">';
			foreach ( $fancy_slider_posts as $post ){
			    setup_postdata( $post );
			    $feature_img = get_post_thumbnail_id( $post->ID );
			    if ( $feature_img ){
			        $img = wp_get_attachment_image_src( $feature_img ,'full');
			        $url .= "<div> <img src= '$img[0]'> </div>";
			    }          
			}
			$url .= '</div>';
			wp_reset_postdata();
			echo $url;
		}
		
	}

    /**
	 * display on back-end admin area  *
	 *@since 0.1.0
	 *@var function
	**/
	public function form($instance){
    	$title = ! empty ($instance['title']) ? $instance['title'] : esc_html__( 'New Title','fancy-slider' ); ?>
		<p>
		  <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) );?>"><?php echo esc_attr_e('Title','fancy-slider')?></label>
		  <input class='widefat' id="<?php echo esc_attr( $this->get_field_id( 'title' ) );?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type='text' value="<?php echo esc_attr( $title );?>" >
		</p>
	<?php }

    /**
	 * save changes for back-end widget manager *
	 *@since 0.1.0
	 *@var function
	**/
	public function update($new_instance, $old_instance){
	    $instance = $old_instance;
	    $instance['title'] = ( !empty( $new_instance ) ) ? strip_tags( $new_instance['title'] ): '';
	    return $instance;
	}


}
?>