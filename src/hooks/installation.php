<?php
/**
 * Activate the plugin for single instance or for the whole network
 * @param $network_wide {boolean}
 */
function arcw_activate( $network_wide )
{
	echo $network_wide;
	if ( $network_wide ) {
		$site_ids = get_sites( array( 'fields' => 'ids' ) );
		foreach ( $site_ids as $site_id ) {
			switch_to_blog( $site_id );
			arcw_activate_install_site( $network_wide );
			restore_current_blog();
		}
		return;
	}

	arcw_activate_install_site( $network_wide );
}

function arcw_activate_install_site( $network_wide, $site_id = null )
{
}

function arcw_uninstall( $network_wide )
{
	if ( $network_wide ) {
		$site_ids = get_sites( array( 'fields' => 'ids' ) );
		foreach ( $site_ids as $site_id ) {
			switch_to_blog( $site_id );
			arcw_uninstall_site( $network_wide );
			restore_current_blog();
		}
		return;
	}
	arcw_uninstall_site( $network_wide );
}

function arcw_uninstall_site( $network_wide )
{
	if ( $network_wide ) {
		delete_site_option( 'archivesCalendar' );
		delete_site_option( 'widget_archives_calendar' );
	} else {
		delete_option( 'archivesCalendar' );
		delete_option( 'widget_archives_calendar' );
	}
}
