<?php
/**
 * Uninstall Restrict Content Pro
 *
 * Deletes the following plugin data:
 *      - RCP post meta
 *      - RCP term meta
 *      - Pages created and used by RCP
 *      - Clears scheduled RCP cron events
 *      - Options added by RCP
 *      - RCP database tables
 *
 * @package     Restrict Content Pro
 * @subpackage  Uninstall
 * @copyright   Copyright (c) 2017, Restrict Content Pro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.8
 */

// Exit if accessed directly
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

// Load RCP file.
include_once( 'restrict-content-pro.php' );

global $wpdb, $rcp_options;

if( isset( $rcp_options['remove_data_on_uninstall'] ) ) {

	// Delete all post meta.
	$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE meta_key LIKE 'rcp\_%'" );

	// Delete all term meta.
	$wpdb->query( "DELETE FROM $wpdb->termmeta WHERE meta_key = 'rcp_restricted_meta'" );

	// Delete the plugin pages.
	$rcp_pages = array( 'registration_page', 'redirect', 'account_page', 'edit_profile', 'update_card' );
	foreach( $rcp_pages as $page_option ) {
		$page_id = isset( $rcp_options[ $page_option ] ) ? $rcp_options[ $page_option ] : false;
		if( $page_id ) {
			wp_trash_post( $page_id );
		}
	}

	// Clear scheduled cron events.
	wp_clear_scheduled_hook( 'rcp_expired_users_check' );
	wp_clear_scheduled_hook( 'rcp_send_expiring_soon_notice' );
	wp_clear_scheduled_hook( 'rcp_check_member_counts' );

	// Remove all plugin settings.
	$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'rcp\_%'" );

	// Remove all database tables.
	$wpdb->query( $wpdb->prepare( "DROP TABLE IF EXISTS %s", rcp_get_discounts_db_name() ) );
	$wpdb->query( $wpdb->prepare( "DROP TABLE IF EXISTS %s", rcp_get_payments_db_name() ) );
	$wpdb->query( $wpdb->prepare( "DROP TABLE IF EXISTS %s", rcp_get_payment_meta_db_name() ) );
	$wpdb->query( $wpdb->prepare( "DROP TABLE IF EXISTS %s", rcp_get_level_meta_db_name() ) );
	$wpdb->query( $wpdb->prepare( "DROP TABLE IF EXISTS %s", rcp_get_levels_db_name() ) );

	// Remove all options.
	delete_option( 'rcp_version' );
	delete_option( 'rcp_db_version' );
	delete_option( 'rcp_settings' );
	delete_option( 'rcp_is_installed' );
	delete_option( 'rcp_discounts_db_version' );
	delete_option( 'rcp_payments_db_version' );

}