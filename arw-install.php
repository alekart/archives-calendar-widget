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
		"css"           => 1,
		"theme"         => "calendrier",
		"js"            => 1,
		"show_settings" => 1,
		"filter"        => 0,
		"javascript"    => "jQuery(document).ready(function($){\n\t$('.calendar-archives').archivesCW();\n});"
	);

	$default_custom_css = file_get_contents( plugins_url( '/admin/default_custom.css' , __FILE__ ) );

	$default_themer_options = array(
		"arw-theme1" => $default_custom_css,
		"arw-theme2" => ''
	);

    if( !( $options = get_option( 'archivesCalendar' ) ) ){
        // if new installation copy default options into the DB
        $options = $default_options;
    }
    else {
        // if reactivation or after update: merge existing settings with the defaults in case if new options were added in the update
        array_merge($default_options, $options);
    }

    if( !( $themer_options = get_option( 'archivesCalendarThemer' ) ) )
		$themer_options = $default_themer_options;

	foreach($themer_options as $ctheme => $css){
		if($css) {
            if(is_writable( '../wp-content/plugins/' . dirname( plugin_basename( __FILE__ ) ) . '/themes/' )) {
                if (isMU()) {
                    $old_blog = $wpdb->blogid;
                    $blogids = $wpdb->get_results("SELECT blog_id FROM $wpdb->blogs");
                    foreach ($blogids as $blogid) {
                        $blog_id = $blogid->blog_id;
                        switch_to_blog($blog_id);
                        $filename = '../wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/themes/' . $ctheme . '-' . $wpdb->blogid . '.css';
                        $themefile = fopen($filename, "w") or die("Unable to open file!");
                        fwrite($themefile, $css);
                        fclose($themefile);
                    }
                    switch_to_blog($old_blog);
                } else {
                    $filename = '../wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/themes/' . $ctheme . '.css';
                    $themefile = fopen($filename, "w") or die("Unable to open file!");
                    fwrite($themefile, $css);
                    fclose($themefile);
                }
            }
            else{
                echo "<p>Can't write in `/wp-content/plugins/" . dirname(plugin_basename(__FILE__)) . "/themes/" . " </p>";
            }
		}
	}

	if(isMU())
	{
		update_blog_option($wpdb -> blogid, 'archivesCalendar', $options);
		add_blog_option($wpdb -> blogid, 'archivesCalendar', $options);
		add_blog_option($wpdb -> blogid, 'archivesCalendarThemer', $themer_options);
	}
	else
	{
		update_option('archivesCalendar', $options);
		add_option('archivesCalendar', $options);
		add_option('archivesCalendarThemer', $themer_options);
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
	if (isMU()){
		delete_site_option('archivesCalendar');
		delete_site_option('widget_archives_calendar');
		delete_site_option('archiveCalandarThemer');
	}
	else {
		delete_option( 'archivesCalendar' );
		delete_option('widget_archives_calendar');
		delete_option('archiveCalandarThemer');
	}
}