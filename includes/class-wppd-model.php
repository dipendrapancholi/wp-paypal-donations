<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Model Class
 *
 * Handles adding scripts functionality to the admin pages
 * as well as the front pages.
 *
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */
class Wppd_Model{
	
	public function __construct(){
		
	}
	
	/**
	 * Escape Tags & Slashes
	 *
	 * Handles escapping the slashes and tags
	 *
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	public function wppd_escape_attr( $data ) {
		return esc_attr( stripslashes($data) );
	}
	
	/**
	 * Strip Slashes From Array
	 *
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	public function wppd_escape_slashes_deep( $data = array(), $flag=false, $limited = false ) {
		
		if( $flag != true ) {
			$data = $this->wppd_nohtml_kses($data);
		} else {
			if( $limited == true ) {
				$data = wp_kses_post( $data );
			}
		}
		
		$data = stripslashes_deep($data);
		return $data;
	}
	
	/**
	 * Strip Html Tags 
	 * 
	 * It will sanitize text input (strip html tags, and escape characters)
	 * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	public function wppd_nohtml_kses($data = array()) {
		
		if ( is_array($data) ) {
			$data = array_map(array($this,'wppd_nohtml_kses'), $data);
		} elseif ( is_string( $data ) ) {
			$data = wp_filter_nohtml_kses($data);
		}
		return $data;
	}
	
	/**
	 * Bulk Deletion
	 *
	 * Does handle deleting coupons from the
	 * database table.
	 *
	 * @package WordPress PayPal Donations
 	 * @since 1.0.0
	 */
	public function wppd_donation_delete( $donation_id ) { 
   
		// Check if empty
   		if( empty( $donation_id ) ) return;
		
   		// Delete scaecity
		wp_delete_post( $donation_id );
	}
	
	/**
	 * Convert Object To Array
	 *
	 * Converting Object Type Data To Array Type
	 * 
	 * @package WordPress PayPal Donations
 	 * @since 1.0.0
	 */
	public function wppd_object_to_array($result) {
		$array = array();
	    foreach ($result as $key=>$value)
	    {	
	        if (is_object($value))
	        {
	            $array[$key] = $this->wppd_object_to_array($value);
	        } else {
	        	$array[$key] = $value;
	        }
	    }
	    return $array;
	}
	
	/**
	 * Get Coupons Data
	 * 
	 * Handles get all coupons from database
	 * 
	 * @package WordPress PayPal Donations
 	 * @since 1.0.0
	 */
	public function wppd_get_paypal_donations( $args = array() ) {
		
		$prefix 	= WPPD_META_PREFIX;
		$data_res	= array();
		
		// Default argument
		$queryargs = array(
							'post_type' => WPPD_POST_TYPE,
							'post_status' => 'publish'
						);
		
		$queryargs = wp_parse_args( $args, $queryargs );
		
		// Fire query in to table for retriving data
		$result = new WP_Query( $queryargs );
		
		//retrived data is in object format so assign that data to array for listing
		$postslist = $this->wppd_object_to_array($result->posts);
		
		$data_res['data'] 	= $postslist;
		
		//To get total count of post using "found_posts" and for users "total_users" parameter
		$data_res['total']	= isset( $result->found_posts ) ? $result->found_posts : '';
		
		return $data_res;
	}
	
	/**
	 * Bulk Duplicate
	 *
	 * Does handle deleting coupons from the
	 * database table.
	 *
	 * @package WordPress PayPal Donations
 	 * @since 1.0.0
	 */
	public function wppd_manage_donations( $post_id = '', $args = array() ) { 
		
		global $wpdb, $user_ID;
   		
   		// Get post title
   		$post_title	= !empty( $args['post_title'] ) ? $args['post_title'] : $args['wppd_title'];
   		
   		// Get post post status
   		$post_status	= !empty( $args['post_status'] ) ? $args['post_status'] : 'publish';
   		
   		// Get author
   		$post_author	= !empty( $args['post_author'] ) ? $args['post_author'] : $user_ID;
   		
   		// Get author
   		$post_parent	= !empty( $args['post_parent'] ) ? $args['post_parent'] : 0;
   		
		$post_args = array(	
							'post_title' 	=> $post_title,
							'post_status'   => $post_status,
				  			'post_author'   => $post_author,
				  			'post_type'     => WPPD_POST_TYPE,
				  			'post_parent'	=> $post_parent
						);
		
		// Insert or update PayPal donation
		if( !empty( $post_id ) || $post_id != null ) {
			
			// Get post id
			$post_args['ID'] = $post_id;
			
			// add filter
			$post_args	= apply_filters( 'wppd_before_update_paypal_donation_args', $post_args );
			
			// update post
			$new_postID	= wp_update_post( $post_args );
			
		} else {
			
			// add filter
			$post_args	= apply_filters( 'wppd_before_insert_paypal_donation_args', $post_args );
			
			// Insert new scrcity
			$new_postID	= wp_insert_post( $post_args , true );
		}
		
		// update post meta
		if( !empty( $new_postID ) ) {
			
			if( !empty( $args['wppd_open_newtab'] ) ) {
				update_post_meta( $new_postID, 'wppd_open_newtab', $args['wppd_open_newtab'] );
			} else {
				update_post_meta( $new_postID, 'wppd_open_newtab', 0 );
			}
			if( !empty( $args['wppd_enable_sandbox'] ) ) {
				update_post_meta( $new_postID, 'wppd_enable_sandbox', $args['wppd_enable_sandbox'] );
			} else {
				update_post_meta( $new_postID, 'wppd_enable_sandbox', 0 );
			}
			if( !empty( $args['wppd_paypal_account'] ) ) {
				update_post_meta( $new_postID, 'wppd_paypal_account', $args['wppd_paypal_account'] );
			}
			if( !empty( $args['wppd_currency'] ) ) {
				update_post_meta( $new_postID, 'wppd_currency', $args['wppd_currency'] );
			}
			if( !empty( $args['wppd_return_url'] ) ) {
				update_post_meta( $new_postID, 'wppd_return_url', $args['wppd_return_url'] );
			}
			if( !empty( $args['wppd_default_amount'] ) ) {
				update_post_meta( $new_postID, 'wppd_default_amount', $args['wppd_default_amount'] );
			}
			if( !empty( $args['wppd_default_purpose'] ) ) {
				update_post_meta( $new_postID, 'wppd_default_purpose', $args['wppd_default_purpose'] );
			}
			if( !empty( $args['wppd_default_reference'] ) ) {
				update_post_meta( $new_postID, 'wppd_default_reference', $args['wppd_default_reference'] );
			}
			if( !empty( $args['wppd_button_type'] ) ) {
				update_post_meta( $new_postID, 'wppd_button_type', $args['wppd_button_type'] );
			}
			if( !empty( $args['wppd_custom_button'] ) ) {
				update_post_meta( $new_postID, 'wppd_custom_button', $args['wppd_custom_button'] );
			}
			if( !empty( $args['wppd_button_localized'] ) ) {
				update_post_meta( $new_postID, 'wppd_button_localized', $args['wppd_button_localized'] );
			}
		}
		
		// Add action for update or insert
		if( !empty( $post_id ) || $post_id != null ) {
			do_action( 'wppd_after_insert_paypal_donation', $new_postID );
		} else {
			do_action( 'wppd_after_update_paypal_donation', $new_postID );
		}
		
		return $new_postID;
	}
} // close of model class