<?php
/*
Plugin Name: Archives Calendar Widget
Plugin URI: http://labs.alek.be/
Description: Archives widget that makes your monthly/daily archives look like a calendar.
Version: 0.9.94
Author: Aleksei Polechin (alek´)
Author URI: http://alek.be
License: GPLv3

/***** LICENSE *****

	Archives Calendar Widget for Wordpress
	Copyright (C) 2013 Aleksei Polechin (http://alek.be)

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program. If not, see <http://www.gnu.org/licenses/>.
	
****/

define ('ARCWV', '0.9.94'); // current version of the plugin

$themes = array(
	'calendrier' => 'Calendrier',
	'pastel' => 'Pastel',
	'classiclight' => 'Classic',
	'classicdark' => 'Classic Dark',
	'twentytwelve' => 'Twenty Twelve',
	'twentythirteen' => 'Twenty Thirteen',
	'twentyfourteen' => 'Twenty Fourteen',
	'twentyfourteenlight' => 'Twenty Fourteen Light',
);

// ACTIVATION
require('arw-install.php');
require('arw-settings.php');
require('arw-widget.php');
register_activation_hook(__FILE__, 'archivesCalendar_activation');
register_uninstall_hook(__FILE__, 'archivesCalendar_uninstall');
add_action( 'wpmu_new_blog', 'archivesCalendar_new_blog', 10, 6); // in case of creation of a new site in WPMU

// LOCALISATION
add_action('init', 'archivesCalendar_init');
// ADD setting action link
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'arcw_plugin_action_links' );
// Register and enqueue Archives Calendar Widjet jQuery plugin
add_action( 'wp_enqueue_scripts', 'archivesCalendar_jquery_plugin' );
// Scripts to be included on Widget configuration page
add_action( 'admin_print_scripts-widgets.php', 'arcw_admin_widgets_scripts' );

if($archivesCalendar_options['css'] == 1)
	// Archives Calendar Widget Themes CSS
	add_action('wp_enqueue_scripts', 'archives_calendar_styles');
if($archivesCalendar_options['js'] == 1)
	// Archives calendar Javascript (placed in <head>)
	add_action('wp_head', 'archivesCalendar_js');

// WIDGET INITIALISATION
add_action( 'widgets_init', create_function( '', 'register_widget( "Archives_Calendar" );' ) );

/**** INIT/ENQUEUE FUNCTIONS ****/
function archivesCalendar_init()
{
	load_plugin_textdomain('arwloc', false, dirname(plugin_basename(__FILE__)).'/languages');
}

function arcw_plugin_action_links( $links ) {
	$links[] = '<a href="'. get_admin_url(null, 'options-general.php?page=Arrchives_Calendar_Widget') .'">'.__('Settings').'</a>';
	return $links;
}

function archivesCalendar_jquery_plugin()
{
	wp_register_script( 'archivesCW', plugins_url('/jquery.archivesCW.min.js', __FILE__), array("jquery"), ARCWV );
	wp_enqueue_script( 'archivesCW');
}

function archivesCalendar_js()
{
	global $archivesCalendar_options;
	echo '<script type="text/javascript">'."\n\t".$archivesCalendar_options['javascript']."\n".'</script>';
}

function archives_calendar_styles()
{
	$archivesCalendar_options = get_option('archivesCalendar');
	wp_register_style( 'archives-cal-'.$archivesCalendar_options['theme'], plugins_url('themes/'.$archivesCalendar_options['theme'].'.css', __FILE__), array(), ARCWV );
	wp_enqueue_style('archives-cal-'.$archivesCalendar_options['theme'] );
}

function arcw_admin_widgets_scripts()
{
	wp_register_script( 'arcwpWidgetsPage', plugins_url('/admin/js/widgets-page.min.js', __FILE__), array(), ARCWV );
	wp_enqueue_script( 'arcwpWidgetsPage' );
}

/***** CHECK MULTISITE NETWORK *****/
if (!function_exists('isMU'))
{
	function isMU()
	{
		if (function_exists('is_multisite') && is_multisite())
			return true;
		return false;
	}
}