<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * script Class
 * 
 * Handles adding scripts functionality to the admin pages
 * as well as the front pages.
 * 
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */
class Wppd_Scripts {
	
	public function __construct(){
		
	}
	
	/**
	 * Loading Additional Java Script
	 * 
	 * Loads the JavaScript required for toggling the meta boxes on the theme settings page
	 * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	public function wppd_donation_page_load_scripts() { ?>
		
		<script>
			//<![CDATA[
			jQuery(document).ready( function($) {
				$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
				postboxes.add_postbox_toggles( 'toplevel_page_wppd_paypal_donations' );
			});
			//]]>
		</script><?php
	}
	
	/**
	 * 
	 * Short code popup page style
	 * 
	 * at public side
	 * 
	 *  @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	 public function wppd_admin_styles( $hook_suffix ) {
		
		$pages_hook_suffix	= array( 
									'post.php',
									'post-new.php',
									'paypal-donations_page_wppd_add_edit_donation',
									'toplevel_page_wppd_paypal_donations',
									'paypal-donations_page_wppd_settings'
								);
		
		//Check pages when you needed
		if( in_array( $hook_suffix, $pages_hook_suffix ) ) {
		
			// loads the required styles for the Short code popup page 
			wp_register_style( 'wppd-admin', WPPD_URL . 'assets/css/wppd-admin.css', array(), WPPD_VERSION );
			wp_enqueue_style( 'wppd-admin' );
			
		}
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
		
		//Short code popup page style
		add_action( 'admin_enqueue_scripts', array( $this,'wppd_admin_styles' ) );
	}
}