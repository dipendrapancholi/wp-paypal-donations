<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Class
 *
 * Handles adding scripts functionality to the admin pages
 * as well as the front pages.
 *
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */
class Wppd_Admin {
	
	public $model, $scripts, $render;
	
	public function __construct(){
	
		global	$wppd_model, $wppd_scripts;
		
		$this->model	= $wppd_model;
		$this->scripts	= $wppd_scripts;
	}
	
	/**
	 *  Register All need admin menu page
	 * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	public function wppd_admin_menu_pages() {
		
		add_menu_page( __( 'PayPal Donations', 'wppd' ), __( 'PayPal Donations','wppd' ), 'manage_options', 'wppd_paypal_donations', array( $this,'wppd_donations_list' ), WPPD_URL . 'assets/images/wppd-donation-icon.png' );
		$wppd_add_edit_page = add_submenu_page( 'wppd_paypal_donations', __( 'Donations', 'wppd' ), __( 'Add New', 'wppd' ), 'manage_options' , 'wppd_add_edit_donation', array($this, 'wppd_add_edit_donation_page') );
		
		// Setting page
		//$wppd_setting = add_submenu_page( 'wppd_paypal_donations', __( 'Settings', 'wppd' ), __( 'Settings', 'wppd' ), 'manage_options' , 'wppd_settings', array( $this, 'wppd_donation_settings' ) );
		
		//loads javascript needed for add page for toggle metaboxes
		add_action( "admin_head-$wppd_add_edit_page", array( $this->scripts, 'wppd_donation_page_load_scripts' ) );
		
		//add_action( "admin_head-$wppd_setting", array( $this->scripts, 'wppd_donation_page_load_scripts' ) );
	}
 
	/**
	 * Include for list table file
	 * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	public function wppd_donations_list() {
		include_once( WPPD_ADMIN_DIR.'/forms/wppd-donation-list.php' );
	}

	/**
	 * Add action admin init
	 * 
	 * Handles add and edit functionality of donation button
	 * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	public function wppd_save_donation() {
		include_once( WPPD_ADMIN_DIR . '/forms/wppd-donation-save.php' );
	}
	
	/**
	 * Adding Admin Sub Menu Page
	 *
	 * Handles Function to adding add data form
	 * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	public function wppd_add_edit_donation_page() {
		include_once( WPPD_ADMIN_DIR . '/forms/wppd-donation-add-edit.php' );
	}
	
	/**
	 * Adding Settings Submebu Page
	 * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	public function wppd_donation_settings() {
		include_once( WPPD_ADMIN_DIR . '/forms/wppd-settings.php' );
	}
	
	/**
	 * Register PayPal Donation options
	 * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	public function wppd_register_settings() {
		register_setting( 'wppd_plugin_options', 'wppd_options', array( $this, 'wppd_validate_options' ) );
	}
	
	/**
	 * Validate register settings
	 * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	public function wppd_validate_options( $input ) {
		return $input;
	}
	
	/**
	 * Bulk Delete
	 * 
	 * Handles bulk delete functinalities of product
	 * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	 public function wppd_donation_button_bulk_action() {
		
	 	// Get redirect url
		$redirect_url = add_query_arg( array( 'page' => 'wppd_paypal_donations' ), admin_url( 'admin.php' ) );
		
		// Check if not page than return
	 	if( !isset($_GET['page']) || $_GET['page'] != 'wppd_paypal_donations' ) {
	 		return;
	 	}
	 	
	 	// Manage delete functionalily
		if( (isset( $_GET['action'] ) && $_GET['action'] == 'delete' ) || 
			(isset( $_GET['action2'] ) && $_GET['action2'] == 'delete' ) ) {
		
			$action_on_id = isset( $_GET['donation'] ) ? $_GET['donation'] : array();
			
			// if no record selected
			if( empty( $action_on_id ) ) {
				
				//if there is no checboxes are checked then redirect to listing page
				wp_redirect( $redirect_url ); 
				exit;
			}
			
			// Check if array
			if( is_array($action_on_id) ) {
				
				//if there is multiple checkboxes are checked then call delete in loop
				foreach ( $action_on_id as $donation_id ) {
				
					// Delete PayPal donation
					$this->model->wppd_donation_delete( $donation_id );
				}
				
				$redirect_url = add_query_arg( array( 'message' => '3' ), $redirect_url );
			} else {
				
				// Delete PayPal donation
				$this->model->wppd_donation_delete( $action_on_id );
				
				$redirect_url = add_query_arg( array( 'message' => '3' ), $redirect_url );
			}
			
			//if bulk delete is performed successfully then redirect 
			wp_redirect( $redirect_url ); 
			exit;
		}
			
		if( (isset( $_GET['action'] ) && $_GET['action'] == 'duplicate' ) ||
			(isset( $_GET['action2'] ) && $_GET['action2'] == 'duplicate' ) ) { //check action and page
	
			$action_on_id = isset($_GET['donation']) ? $_GET['donation'] : array();
			
			if( empty( $action_on_id ) ) return;
			
			$post_title	= get_the_title( $action_on_id );
			
			$post_meta	= get_post_custom( $action_on_id );
			
			//parameters for delete function
			$args = array ( 'post_title' => $post_title . __( ' - copy', 'wppd' ) );
			
			//call delete function from model class to delete records
			$post_id = $this->model->wppd_manage_donations( null, $args );
			
			foreach( $post_meta as $key => $value ) {
				if( is_array( $value ) && count( $value ) > 0 ) {
					update_post_meta( $post_id, $key, $value[0] );
				} else {
					update_post_meta( $post_id, $key, $value );
				}
			}
			
			$redirect_url = add_query_arg( array( 'message' => '5' ), $redirect_url );
			
			//if bulk delete is performed successfully then redirect 
			wp_redirect( $redirect_url ); 
		}
	}
	
	/**
	 * Editor Pop Up Script
	 * 
	 * Adding the needed script for the pop up on the editor
	 * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	public function wppd_shortcode_editor_button_script( $plugin_array ) {
		
		wp_enqueue_script( 'tinymce' );
		$plugin_array['wppdshortcodes'] = WPPD_URL . 'assets/js/wppd-button.js?ver=' . WPPD_VERSION;
		
		return $plugin_array;
	}
	
	/**
	 * Register Buttons
	 * 
	 * Register the different content locker buttons for the editor
	 * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	public function wppd_shortcode_editor_register_button( $buttons ) {
	 	array_push( $buttons, "|", "wppdshortcodes" );
	 	return $buttons;
	}
	
	/**
	 * Shortcode Button
	 * 
	 * Adds the shortcode button above the WordPress editor.
	 * 
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	public function wppd_shortcode_button() {
		
		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
			return;
		}
	 
		if ( get_user_option( 'rich_editing' ) == 'true' ) {
			
			add_filter( 'mce_external_plugins', array( $this, 'wppd_shortcode_editor_button_script' ) );
			add_filter( 'mce_buttons', array( $this, 'wppd_shortcode_editor_register_button' ) );	     
		}
	}
	
	/**
	 * Pop Up On Editor
	 *
	 * Includes the pop up on the WordPress editor
	 *
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	public function wppd_shortcode_popup_markup() {
		include_once( WPPD_ADMIN_DIR . '/forms/wppd-shortcodes-popup.php' );
	}
	
	/**
	 * Adding Hooks
	 *
	 * @package WordPress PayPal Donations
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		//add admin menu pages
		add_action( 'admin_menu',  array($this,'wppd_admin_menu_pages') );
		
		//Handles add and edit functionality of product
		add_action( 'admin_init', array($this, 'wppd_save_donation') );
		
		// shortcode button
		add_action( 'init', array( $this, 'wppd_shortcode_button' ) );
		
		//add register plugin settings
		add_action ( 'admin_init', array($this,'wppd_register_settings') );
		
		//add admin init for bulk delete functionality
		add_action( 'admin_init' , array($this,'wppd_donation_button_bulk_action'));
		
		// mark up for popup
		add_action( 'admin_footer-post.php', array( $this,'wppd_shortcode_popup_markup') );
		
		//Includes the pop up on the WordPress editor
		add_action( 'admin_footer-post-new.php', array( $this,'wppd_shortcode_popup_markup') );
	}
}