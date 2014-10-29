<?php
/*
Archives Calendar Widget INSTALLATION HOOKS
Author URI: http://alek.be
License: GPLv3
*/

// ACTIVATION
function archivesCalendar_activation($network_wide)
{
	global $wpdb;

	if (isMU())
	{
		// isMU verifyes id the site is in Multisite mode
		// check if it is a network activation - if so, run the activation function for each blog id
		if (isset($_GET['networkwide']) && ($_GET['networkwide'] == 1))
		{
			$old_blog = $wpdb->blogid;
			// Get all blog ids
			$blogids =  $wpdb->get_results("SELECT blog_id FROM $wpdb->blogs");
			foreach ($blogids as $blogid)
			{
				$blog_id = $blogid->blog_id;
				switch_to_blog($blog_id);
				_archivesCalendar_activate();
			}
			switch_to_blog($old_blog);
			return;
		}
	}
	_archivesCalendar_activate();
}
function _archivesCalendar_activate()
{
	global $wpdb;

	$default_options = array(
		"css" => 1,
		"theme" => "calendrier",
		"jquery" => 1,
		"js" => 1,
		"show_settings" => 1,
		"shortcode" => 0,
		"javascript" => "jQuery(document).ready(function($){\n\t$('.calendar-archives').archivesCW();\n});"
	);

	if( !( $options = get_option( 'archivesCalendar' ) ) )
		$options = $default_options;

	if(isMU())
	{
		update_blog_option($wpdb -> blogid, 'archivesCalendar', $options);
		add_blog_option($wpdb -> blogid, 'archivesCalendar', $options);
	}
	else
	{
		update_option('archivesCalendar', $options);
		add_option('archivesCalendar', $options);
	}
}

function archivesCalendar_new_blog($blog_id)
{
	global $wpdb;
	if (is_plugin_active_for_network(dirname(plugin_basename(__FILE__)).'/archives-calendar.php'))
	{
		$old_blog = $wpdb->blogid;
		switch_to_blog($blog_id);
		_archivesCalendar_activate();
		switch_to_blog($old_blog);
	}
}

// UNINSTALL
function archivesCalendar_uninstall()
{
	global $wpdb;
	if (isMU())
	{
		$old_blog = $wpdb->blogid;
		$blogids =  $wpdb->get_results("SELECT blog_id FROM $wpdb->blogs");
		foreach ($blogids as $blogid)
		{
			$blog_id = $blogid->blog_id;
			switch_to_blog($blog_id);
			_archivesCalendar_uninstall();
		}
		switch_to_blog($old_blog);
		return;
	}
	_archivesCalendar_uninstall();
}
function _archivesCalendar_uninstall()
{
	if (isMU())
		delete_site_option('archivesCalendar');
	else
		delete_option('archivesCalendar');
}