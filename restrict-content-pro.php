<?php
/*
Plugin Name: Restrict Content Pro
Plugin URL: http://pippinsplugins.com/restrict-content-pro-premium-content-plugin
Description: Setup a complete subscription system for your WordPress site and deliver premium content to your subscribers. Unlimited subscription packages, membership management, discount codes, registration / login forms, and more.
Version: 1.3.2.3
Author: Pippin Williamson
Author URI: http://pippinsplugins.com
Contributors: mordauk
*/

if ( !defined( 'RCP_PLUGIN_DIR' ) ) {
	define( 'RCP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( !defined( 'RCP_PLUGIN_URL' ) ) {
	define( 'RCP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( !defined( 'RCP_PLUGIN_FILE' ) ) {
	define( 'RCP_PLUGIN_FILE', __FILE__ );
}
if ( !defined( 'RCP_PLUGIN_VERSION' ) ) {
	define( 'RCP_PLUGIN_VERSION', '1.3.2.3' );
}


/*******************************************
* setup DB names
*******************************************/

if ( ! function_exists( 'is_plugin_active_for_network' ) )
	require_once ABSPATH . '/wp-admin/includes/plugin.php';

function rcp_get_levels_db_name() {
	global $wpdb;

	$prefix = is_plugin_active_for_network( 'restrict-content-pro/restrict-content-pro.php' ) ? '' : $wpdb->prefix;

	return apply_filters( 'rcp_levels_db_name', $prefix . 'restrict_content_pro' );
}

function rcp_get_discounts_db_name() {
	global $wpdb;

	$prefix = is_plugin_active_for_network( 'restrict-content-pro/restrict-content-pro.php' ) ? '' : $wpdb->prefix;

	return apply_filters( 'rcp_discounts_db_name', $prefix . 'rcp_discounts' );
}

function rcp_get_payments_db_name() {
	global $wpdb;

	$prefix = is_plugin_active_for_network( 'restrict-content-pro/restrict-content-pro.php' ) ? '' : $wpdb->prefix;

	return apply_filters( 'rcp_payments_db_name', $prefix . 'rcp_payments' );
}


/*******************************************
* global variables
*******************************************/
global $wpdb;

// the plugin base directory
global $rcp_base_dir; // not used any more, but just in case someone else is
$rcp_base_dir = dirname( __FILE__ );

// load the plugin options
$rcp_options = get_option( 'rcp_settings' );

global $rcp_db_name;
$rcp_db_name = rcp_get_levels_db_name();

global $rcp_db_version;
$rcp_db_version = 1.2;

global $rcp_discounts_db_name;
$rcp_discounts_db_name = rcp_get_discounts_db_name();

global $rcp_discounts_db_version;
$rcp_discounts_db_version = 1.1;

global $rcp_payments_db_name;
$rcp_payments_db_name = rcp_get_payments_db_name();

global $rcp_payments_db_version;
$rcp_payments_db_version = 1.1;

/* settings page globals */
global $rcp_members_page;
global $rcp_subscriptions_page;
global $rcp_discounts_page;
global $rcp_payments_page;
global $rcp_settings_page;
global $rcp_export_page;
global $rcp_help_page;

/*******************************************
* plugin text domain for translations
*******************************************/

function rcp_load_textdomain() {
	load_plugin_textdomain( 'rcp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'rcp_load_textdomain' );


/*******************************************
* file includes
*******************************************/


// global includes

include( RCP_PLUGIN_DIR . 'includes/gateways/paypal/paypal.php' );
include( RCP_PLUGIN_DIR . 'includes/misc-functions.php' );
include( RCP_PLUGIN_DIR . 'includes/scripts.php' );
include( RCP_PLUGIN_DIR . 'includes/registration-functions.php' );
include( RCP_PLUGIN_DIR . 'includes/member-functions.php' );
include( RCP_PLUGIN_DIR . 'includes/discount-functions.php' );
include( RCP_PLUGIN_DIR . 'includes/subscription-functions.php' );
include( RCP_PLUGIN_DIR . 'includes/email-functions.php' );
include( RCP_PLUGIN_DIR . 'includes/payment-tracking-functions.php' );
include( RCP_PLUGIN_DIR . 'includes/handle-registration-login.php' );
include( RCP_PLUGIN_DIR . 'includes/gateway-functions.php' );
include( RCP_PLUGIN_DIR . 'includes/cron-functions.php' );
include( RCP_PLUGIN_DIR . 'includes/ajax-actions.php' );

// admin only includes
if( is_admin() ) {

	if( !class_exists( 'Custom_Plugin_Updater' ) ) {
		include_once( RCP_PLUGIN_DIR . 'class-custom-plugin-updater.php' );
	}
	require( RCP_PLUGIN_DIR . 'includes/install.php' );
	include( RCP_PLUGIN_DIR . 'includes/upgrades.php' );
	include( RCP_PLUGIN_DIR . 'includes/admin-pages.php' );
	include( RCP_PLUGIN_DIR . 'includes/admin-pages/screen-options.php' );
	include( RCP_PLUGIN_DIR . 'includes/admin-pages/members-page.php' );
	include( RCP_PLUGIN_DIR . 'includes/admin-pages/settings.php' );
	include( RCP_PLUGIN_DIR . 'includes/admin-pages/subscription-levels.php' );
	include( RCP_PLUGIN_DIR . 'includes/admin-pages/discount-codes.php' );
	include( RCP_PLUGIN_DIR . 'includes/admin-pages/help-menus.php' );
	include( RCP_PLUGIN_DIR . 'includes/admin-pages/payments-page.php' );
	include( RCP_PLUGIN_DIR . 'includes/admin-pages/export.php' );
	include( RCP_PLUGIN_DIR . 'includes/admin-pages/help-page.php' );
	include( RCP_PLUGIN_DIR . 'includes/user-page-columns.php' );
	include( RCP_PLUGIN_DIR . 'includes/metabox.php' );
	include( RCP_PLUGIN_DIR . 'includes/process-data.php' );
	include( RCP_PLUGIN_DIR . 'includes/export-functions.php' );
	include( RCP_PLUGIN_DIR . 'includes/admin-notices.php' );	
	include( RCP_PLUGIN_DIR . 'includes/admin-ajax-actions.php' );	
	
	// setup the plugin updater
	$rcp_updater = new Custom_Plugin_Updater( 'http://pippinsplugins.com/updater/api/', RCP_PLUGIN_FILE, array( 'version' => RCP_PLUGIN_VERSION ) );

} else {

	include( RCP_PLUGIN_DIR . 'includes/error-tracking.php' );
	include( RCP_PLUGIN_DIR . 'includes/shortcodes.php' );
	include( RCP_PLUGIN_DIR . 'includes/member-forms.php' );
	include( RCP_PLUGIN_DIR . 'includes/content-filters.php' );
	include( RCP_PLUGIN_DIR . 'includes/feed-functions.php' );
	if( isset( $rcp_options['enable_recaptcha'] ) ) {
		require_once( RCP_PLUGIN_DIR . 'includes/captcha-functions.php' );		
	}
	include( RCP_PLUGIN_DIR . 'includes/user-checks.php' );
	include( RCP_PLUGIN_DIR . 'includes/query-filters.php' );
	include( RCP_PLUGIN_DIR . 'includes/redirects.php' );
}
