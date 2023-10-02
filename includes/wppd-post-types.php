<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Post Type Functions
 * 
 * Handles all custom post types
 * 
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */

/**
 * Setup Donation CPT
 * 
 * Registers the Donation CPT
 * 
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */
function wppd_register_post_type() {
	
	//array for all required labels
	$wppd_labels =  array(
								'name' 				=> sprintf( '%s', wppd_paypal_donation_lable(true) ),
								'singular_name' 	=> sprintf( '%s', wppd_paypal_donation_lable() ),
								'add_new' 			=> sprintf( __('Add New %s', 'wppd'), wppd_paypal_donation_lable()),
								'add_new_item' 		=> sprintf( __('Add New %s', 'wppd'),wppd_paypal_donation_lable(true)),
								'edit_item' 		=> sprintf( __('Edit %s ', 'wppd'),wppd_paypal_donation_lable() ),
								'new_item' 			=> sprintf( __('New %s', 'wppd'),wppd_paypal_donation_lable() ),
								'all_items' 		=> sprintf( __('All %s', 'wppd'),wppd_paypal_donation_lable(true) ),
								'view_item' 		=> sprintf( __('View ', 'wppd'),wppd_paypal_donation_lable() ),
								'search_items' 		=> sprintf( __('Search ', 'wppd'),wppd_paypal_donation_lable() ),
								'not_found' 		=> sprintf( __('No %s found', 'wppd'),wppd_paypal_donation_lable() ),
								'not_found_in_trash'=> sprintf( __('No %s found in Trash', 'wppd'),wppd_paypal_donation_lable(true) ),
								'parent_item_colon' => '',
								'menu_name' 		=> sprintf( __('%s', 'wppd'),wppd_paypal_donation_lable(true) ),
							);
	
	//array for all required labels
	$wppd_args = array(
							'labels' 			=> $wppd_labels,
							'public' 			=> false,
							'publicly_queryable'=> false,
							'show_ui' 			=> false,
							'map_meta_cap'      => true,
							'show_in_menu' 		=> false,
							'query_var' 		=> true,
							'rewrite' 			=> array( 'slug' => WPPD_POST_TYPE ),
							'capability_type' 	=> WPPD_POST_TYPE, //'post',
							'has_archive' 		=> true,
							'supports' 			=> apply_filters( 'wppd_post_type_supports', array( 'title' ) ),
						);
	
	//Add filter to modify PayPal donation register post type arguments
	$wppd_args	= apply_filters( 'wppd_register_post_type_paypal_donations', $wppd_args );
	
	//register PayPal donation post type
	register_post_type( WPPD_POST_TYPE, $wppd_args );
}

add_action( 'init', 'wppd_register_post_type' );