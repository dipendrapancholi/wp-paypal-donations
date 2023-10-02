<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Shortcode Class
 *
 * Handles adding scripts functionality to the admin pages
 * as well as the front pages.
 *
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */
class Wppd_Shortcodes{
	
	public function __construct() {
	
	}
	
	/**
	 * Adding Hooks for shortcode
	 *
	 * Adding hooks for the Set counter and check expire
	 *
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	function wppd_paypal_donation_shortcode( $atts, $content ) {
		
		global $wpdb, $noshortcodes, $wppd_model;
		
		$model		= $wppd_model;
		$post_id	= $atts['id'];
		
		$html	= wppd_paypal_donation_html( $post_id );
		
		return $html;
	}
	
	/**
	 * Adding Hooks
	 * 
	 * Adding hooks for the styles and scripts.
	 * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		//Adding hooks for the Set PayPal Donation
		add_shortcode( 'paypal_donation', array( $this, 'wppd_paypal_donation_shortcode' ) );
	}
}