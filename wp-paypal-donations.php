<?php
/**
 * Plugin Name: WordPress PayPal Donations
 * Plugin URI:  https://dharmisoft.com/
 * Description: WordPress PayPal Donations allows you easy and simple setup and insertion of PayPal donate buttons using a shortcode or through a Widget. You can create multiple donation button.
 * Version: 1.0.0
 * Author: Serveonetech
 * Author URI: https://profiles.wordpress.org/dipendrapancholi/
 * Text Domain: wppd
 * Domain Path: languages
 * 
 * @package WordPress PayPal Donations
 * @category Core
 * @author Serveonetech
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Basic plugin definitions
 * 
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */

if( !defined( 'WPPD_VERSION' ) ) {
	define( 'WPPD_VERSION', '1.0.0' ); //libraray version of js and css
}
if( !defined( 'WPPD_DIR' ) ) {
	define( 'WPPD_DIR', dirname( __FILE__ ) ); // plugin dir
}
if( !defined( 'WPPD_URL' ) ) {
	define( 'WPPD_URL', plugin_dir_url( __FILE__ ) ); // plugin url
}
if( !defined( 'WPPD_BASENAME' ) ) {
	define( 'WPPD_BASENAME', basename( WPPD_DIR ) ); // base name
}
if( !defined( 'WPPD_POST_TYPE' ) ) {
	define( 'WPPD_POST_TYPE', 'wppddonation' ); // donation CPT
}
if( !defined( 'WPPD_ADMIN_DIR' ) ) {
	define( 'WPPD_ADMIN_DIR', WPPD_DIR . '/includes/admin' ); // Admin Dir
}
if( !defined( 'WPPD_META_PREFIX' ) ) {
	define( 'WPPD_META_PREFIX', '_wppd_' ); // Admin Dir
}

//Include functions file
require_once( WPPD_DIR . '/includes/wppd-misc-functions.php' );

//Include CPT registration file
require_once( WPPD_DIR . '/includes/wppd-post-types.php' );

/**
 * Load Text Domain
 * 
 * This gets the plugin ready for translation.
 * 
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */
function wppd_load_textdomain() {
	
	// Set filter for plugin's languages directory
	$wppd_lang_dir	= dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$wppd_lang_dir	= apply_filters( 'wppd_languages_directory', $wppd_lang_dir );
	
	// Traditional WordPress plugin locale filter
	$locale	= apply_filters( 'plugin_locale',  get_locale(), 'wppd' );
	$mofile	= sprintf( '%1$s-%2$s.mo', 'wppd', $locale );
	
	// Setup paths to current locale file
	$mofile_local	= $wppd_lang_dir . $mofile;
	$mofile_global	= WP_LANG_DIR . '/' . WPPD_BASENAME . '/' . $mofile;
	
	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/wp-paypal-donations folder
		load_textdomain( 'wppd', $mofile_global );
	} elseif ( file_exists( $mofile_local ) ) { // Look in local /wp-content/plugins/wp-paypal-donations/languages/ folder
		load_textdomain( 'wppd', $mofile_local );
	} else { // Load the default language files
		load_plugin_textdomain( 'wppd', false, $wppd_lang_dir );
	}
}
add_action( 'plugins_loaded', 'wppd_load_textdomain' );

/**
 * Add plugin action links
 *
 * Adds a Settings, Support and Docs link to the plugin list.
 *
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */
function wppd_add_plugin_links( $links ) {
	
	$plugin_links = array(
		'<a href="admin.php?page=wc-settings&tab=wppd">' . __( 'Settings', 'wppd' ) . '</a>',
		'<a target="_blank" href="http://support.serveonetech.com/">' . __( 'Support', 'wppd' ) . '</a>',
		'<a target="_blank" href="http://serveonetech.com/documents/wp-paypal-donations/">' . __( 'Docs', 'wppd' ) . '</a>'
	);
	
	return array_merge( $plugin_links, $links );
}
//add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'woo_os_add_plugin_links' );


/**
 * Activation Hook
 *
 * Register plugin activation hook.
 *
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'wppd_install' );

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * stest default values for the plugin options.
 * 
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */
function wppd_install() {
	
	global $wpdb;
	
	//get option for when plugin is activating first time
	$wppd_set_option = get_option( 'wppd_set_option' );
	
	if( empty( $wppd_set_option ) ) { //check plugin version option
		
		//update plugin version to option
		update_option( 'wppd_set_option', '1.0' );
		
	}
	
	// register post type
	wppd_register_post_type();
	
	//IMP Call of Function
	//Need to call when custom post type is being used in plugin
	flush_rewrite_rules();
	
}

/**
 * Deactivation Hook
 * 
 * Register plugin deactivation hook.
 * 
 * @package  WordPress PayPal Donations
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'wppd_uninstall');

/**
 * Plugin Setup (On Deactivation)
 * 
 * Delete plugin options.
 * 
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */
function wppd_uninstall() {
	global $wpdb;
}

/**
 * Global Variables
 * 
 * Declaration of some needed global varibals for plugin
 *
 * @package WordPress PayPal Donations
 * @since 1.0.0
 */
global $wppd_admin, $wppd_public, $wppd_model,
		$wppd_options, $wppd_shortcode, $wppd_scripts;

// Manage model functions
include_once( WPPD_DIR . '/includes/class-wppd-model.php' );
$wppd_model = new Wppd_Model();

// Handle script functionality at admin as well as public side
include_once( WPPD_DIR . '/includes/class-wppd-scripts.php' );
$wppd_scripts = new Wppd_Scripts();
$wppd_scripts->add_hooks();

// Manage plugin shortcodes functinality
include_once( WPPD_DIR . '/includes/class-wppd-shortcodes.php' );
$wppd_shortcode = new Wppd_Shortcodes();
$wppd_shortcode->add_hooks();

// includes widget file
require_once ( WPPD_DIR . '/includes/widgets/class-wppd-donation-widget.php' );

// Handles admin side functionalities
include_once( WPPD_ADMIN_DIR . '/class-wppd-admin.php' );
$wppd_admin = new Wppd_Admin();
$wppd_admin->add_hooks();