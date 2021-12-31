<?php
/**
 * Activate the plugin for single instance or for the whole network
 * @param bool $networkWide
 */
function arcwActivate( bool $networkWide ) {
	echo $networkWide;
	if ( $networkWide ) {
		$siteIds = get_sites( array( 'fields' => 'ids' ) );
		foreach ( $siteIds as $siteId ) {
			switch_to_blog( $siteId );
			arcwActivateInstallSite( $siteId );
			restore_current_blog();
		}
		return;
	}
	arcwActivateInstallSite();
}

function arcwActivateInstallSite( int $siteId = null ) {
	$options = ARCW::getOptions();
	if ( $options ) {
		$options = ARCW::upgradeOptions( $options );
	}

	if ( $siteId ) {
		update_blog_option( $siteId, 'archivesCalendar', $options );
	} else {
		update_option( 'archivesCalendar', $options );
	}
}

function arcwUninstall( bool $networkWide ) {
	if ( $networkWide ) {
		$siteIds = get_sites( array( 'fields' => 'ids' ) );
		foreach ( $siteIds as $siteId ) {
			switch_to_blog( $siteId );
			arcwUninstallSite( $networkWide );
			restore_current_blog();
		}
		return;
	}
	arcwUninstallSite( $networkWide );
}

function arcwUninstallSite( bool $networkWide ) {
	if ( $networkWide ) {
		delete_site_option( 'archivesCalendar' );
		delete_site_option( 'widget_archives_calendar' );
	} else {
		delete_option( 'archivesCalendar' );
		delete_option( 'widget_archives_calendar' );
	}
}

/**
 * When adding a new Site in the Multisite network check if the plugin is activated for the nework
 * and activate it for the newly added site.
 * @param int $blogId
 */
function arcwAddNewSite( int $blogId ) {
	global $wpdb;
	if ( is_plugin_active_for_network( dirname( plugin_basename( __FILE__ ) ) . '/archives-calendar.php' ) ) {
		$oldBlog = $wpdb->blogid;
		switch_to_blog( $blogId );
		arcwActivateInstallSite( $blogId );
		switch_to_blog( $oldBlog );
	}
}

/**
 * Adds a "Settings" actions link into the wp Plugins settings list
 * @param array $links
 * @return array
 */
function arcwPluginActionLinks( array $links ): array {
	$settingUrl = get_admin_url( null, 'options-general.php?page=Archives_Calendar_Widget' );
	$label = __( 'Settings' );
	$links[] = "<a href=\"$settingUrl\">$label</a>";

	return $links;
}
