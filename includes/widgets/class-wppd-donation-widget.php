<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

add_action( 'widgets_init', 'wppd_paypal_donation_widget' );

/**
 * Register the Vendor Widget
 *
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */
function wppd_paypal_donation_widget() {
	
	register_widget( 'Wppd_PayPal_Donation_Widget' );
}

/**
 * Widget Class
 *
 * Handles generic functionailties
 *
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */

class Wppd_PayPal_Donation_Widget extends WP_Widget {
	
	function __construct() {
		$widget_ops	= array(
							'classname'   => 'WPPD', 
							'description' => __( 'Display PayPal Donation Button.', 'wppd' )
						);
		
		parent::__construct( 'WPPD', __( 'PayPal Donations', 'wppd' ), $widget_ops );
	}
	
	function form( $instance ) {
		
		$title 			= isset( $instance['title'] ) 		 	? esc_attr( $instance['title'] ) 		 	: 'PayPal Donation';
		$post_id 		= isset( $instance['post_id'] ) 		? esc_attr( $instance['post_id'] ) 			: '';?>
		
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','wppd'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		
		<label><?php _e( 'Select PayPal Donation:','wppd');?></label><?php
		
		$args = array( 'post_type' => WPPD_POST_TYPE );
		query_posts( $args );?>
		
		<select name="<?php echo $this->get_field_name( 'post_id' ); ?>" id="<?php echo $this->get_field_id( 'post_id' ); ?>"> <?php
		
		while ( have_posts() ) : the_post();
			
			$selected="";
			if( $post_id == get_the_ID() ) $selected="selected='selected'";
			echo '<option value="'.get_the_ID().'"'.$selected.'>'.get_the_title().'</option>';
			
		endwhile;
		
		echo '</select>';
	}
	
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		
		$instance['title'] 			= strip_tags($new_instance['title']);
		$instance['post_id'] 		= $new_instance['post_id'];
		
        return $instance;
	}	
	
	function widget($args, $instance) {
		
		extract( $args );		
		$title		= apply_filters( 'widget_title', empty( $instance['title'] ) ? 'PayPal Donation' : $instance['title'], $instance, $this->id_base);
		$post_id	= $instance['post_id'];
		
		echo $before_widget;
		
		if ( $title ) { // Widget title
			echo $before_title . $title . $after_title;
		}
		
		echo '<div class="payppal_donation_widget">'.do_shortcode("[paypal_donation id=$post_id inwidget='true']").'</div>';
		
		echo $after_widget;	
	}
}