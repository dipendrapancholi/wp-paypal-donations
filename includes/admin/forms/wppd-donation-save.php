<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Save PayPal Donation CPT
 *
 * Handle paypal donation save and edit paypal donations
 * 
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */

	global $errmsg, $wpdb, $user_ID, $error, $wppd_model, $post, $postid;

	$model = $wppd_model;
	
	// save for paypal donation data
	if( isset( $_POST['wppd_donation_save'] ) && !empty( $_POST['wppd_donation_save'] ) ) { //check submit button click
		
		$error = '';
		
		if( isset( $_POST['wppd_title'] ) && empty( $_POST['wppd_title'] ) ) { //check donation title
			
			$errmsg['wppd_title']	= __( 'Please Enter Donation Title.', 'wppd' );
			$error	= true;
		}
		
		if( isset($_POST['wppd_paypal_account'] ) && empty( $_POST['wppd_paypal_account'] ) ) { //check donation paypal account
			
			$errmsg['wppd_paypal_account']	= __( 'Please Enter PayPal Account.', 'wppd' );
			$error	= true;
		}
		
		if( isset( $_POST['wppd_currency'] ) && empty( $_POST['wppd_currency'] ) ) { //check donation currency
			
			$errmsg['wppd_currency']	= __( 'Please Select Currency.', 'wppd' );
			$error = true;
		}
		
		if( $_POST['wppd_button_type'] == 'custom' && empty( $_POST['wppd_custom_button'] ) ) { //check custom donation
			
			$errmsg['wppd_custom_button']	= __( 'Please Enter Custom PayPal Button URL.', 'wppd' );
			$error = true;
		}
		
		if( isset( $_GET['donation_id'] ) && !empty( $_GET['donation_id'] ) && $error != true ) { //check no error and donation id is set in url
			
			$postid	= $_GET['donation_id'];
			
			$result = $model->wppd_manage_donations( $postid, $_POST );
				
			if( $result ) {
				
				$redirect_url = add_query_arg( array( 'page' => 'wppd_paypal_donations', 'message' => '2' ), admin_url( 'admin.php' ) );
				wp_redirect( $redirect_url );
				exit;
			}
			
		} else {
			
			if( $error != true ) { //check there is no error then insert data in to the table
				
				// Insert the post into the database
				$result	= $model->wppd_manage_donations( $postid, $_POST );
				
				if( $result ) { //check inserted product id
					
					// Get redirect url
					$redirect_url = add_query_arg( array( 'page' => 'wppd_paypal_donations', 'message' => '1' ), admin_url( 'admin.php' ) );
					
					wp_redirect( $redirect_url );
					exit;
					
				}
			}
		}
	}
?>