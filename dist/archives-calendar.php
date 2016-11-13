<?php
/*
Plugin Name: Archives Calendar Widget
Plugin URI: https://wordpress.org/plugins/archives-calendar-widget/
Description: Archives widget that makes your monthly/daily archives look like a calendar.
Version: 1.0.12
Author: Aleksei Polechin (alek´)
Author URI: http://alek.be
License: GPLv3
*/

/***** GPLv3 LICENSE *****
 *
 * Archives Calendar Widget for Wordpress
 * Copyright (C) 2013-2016 Aleksei Polechin (http://alek.be)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 ****/

define( 'ARCWV', '1.0.12' ); // current version of the plugin
define( 'ARCW_DEBUG', false ); // enable or disable debug (for dev instead of echo or print_r use debug() function)

$themes = array(
	'calendrier'          => 'Calendrier',
	'pastel'              => 'Pastel',
	'classiclight'        => 'Classic',
	'classicdark'         => 'Classic Dark',
	'twentytwelve'        => 'Twenty Twelve',
	'twentythirteen'      => 'Twenty Thirteen',
	'twentyfourteen'      => 'Twenty Fourteen',
	'twentyfourteenlight' => 'Twenty Fourteen Light',
);

// ACTIVATION
require( 'arw-install.php' );
require( 'arw-settings.php' );
require( 'arw-widget.php' );
register_activation_hook( __FILE__, 'archivesCalendar_activation' );
register_uninstall_hook( __FILE__, 'archivesCalendar_uninstall' );
add_action( 'wpmu_new_blog', 'archivesCalendar_new_blog', 10, 6 ); // in case of creation of a new site in WPMU

// LOCALISATION
add_action( 'init', 'archivesCalendar_init' );
// ADD setting action link
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'arcw_plugin_action_links' );
// Register and enqueue Archives Calendar Widjet jQuery plugin
add_action( 'wp_enqueue_scripts', 'archivesCalendar_jquery_plugin' );
// Scripts to be included on Widget configuration page
add_action( 'admin_print_scripts-widgets.php', 'arcw_admin_widgets_scripts' );
add_action( 'admin_print_scripts-customize.php', 'arcw_admin_widgets_scripts' );

if ( $archivesCalendar_options['css'] == 1 ) // Archives Calendar Widget Themes CSS
{
	add_action( 'wp_enqueue_scripts', 'archives_calendar_styles' );
}

// WIDGET INITIALISATION
add_action( 'widgets_init', create_function( '', 'register_widget( "Archives_Calendar" );' ) );

/**** INIT/ENQUEUE FUNCTIONS ****/
function archivesCalendar_init() {
	load_plugin_textdomain( 'arwloc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

function arcw_plugin_action_links( $links ) {
	$links[] = '<a href="' . get_admin_url( null, 'options-general.php?page=Archives_Calendar_Widget' ) . '">' . __( 'Settings' ) . '</a>';

	return $links;
}

function archivesCalendar_jquery_plugin() {
	global $archivesCalendar_options;
	$jQarcw = $archivesCalendar_options['plugin-init'] == 1 ? '/admin/js/jquery.arcw-init.js' : '/admin/js/jquery.arcw.js';
	wp_register_script( 'jquery-arcw', plugins_url( $jQarcw, __FILE__ ), array( "jquery" ), ARCWV );
	wp_enqueue_script( 'jquery-arcw' );
}

function archives_calendar_styles() {
	$archivesCalendar_options = get_option( 'archivesCalendar' );
	wp_register_style( 'archives-cal-' . $archivesCalendar_options['theme'], plugins_url( 'themes/' . $archivesCalendar_options['theme'] . '.css', __FILE__ ), array(), ARCWV );
	wp_enqueue_style( 'archives-cal-' . $archivesCalendar_options['theme'] );
}

function arcw_admin_widgets_scripts() {
	wp_register_style( 'arcw-widget-settings', plugins_url( '/admin/css/widget-settings.css', __FILE__ ), array(), ARCWV );
	wp_register_script( 'arcwpWidgetsPage', plugins_url( '/admin/js/widgets-page.js', __FILE__ ), array(), ARCWV );
	wp_enqueue_script( 'arcwpWidgetsPage' );
	wp_enqueue_style( 'arcw-widget-settings' );
}

function update_url_params( $url, $addparams = array() ) {
	$url_parts = parse_url( $url );
	$params    = array();

	if ( isset( $url_parts['query'] ) ) {
		parse_str( $url_parts['query'], $params );
	}

	$params = array_merge( $params, $addparams );

	$url_parts['query'] = urldecode( http_build_query( $params ) );

	return $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'] . '?' . $url_parts['query'];
}

function make_arcw_link( $url, $type = null, $cats = null ) {
	global $archivesCalendar_options;

	$enabled = $archivesCalendar_options['filter'];

	if ( ! $enabled || ( $enabled && ( ! $type || $type == "post" ) && ! $cats ) ) {
		return $url;
	}

	$params = array( 'arcf' => '' );
	$attr   = &$params['arcf'];
	if ( ! empty( $type ) && count( $type ) && $type != 'post' ) {
		$attr = 'post:' . str_replace( ',', '+', $type );
	}
	if ( ! empty( $cats ) ) {
		$attr .= $attr != '' ? ':' : '';
		$attr .= 'cat:' . str_replace( ', ', '+', $cats );
	}

	return update_url_params( $url, $params );
}


// Activate filter in archives page
if ( isset( $archivesCalendar_options['filter'] ) && $archivesCalendar_options['filter'] == 1 ) {
	add_action( 'pre_get_posts', 'arcw_filter' );
}

function arcw_filter( $query ) {
	if ( $query->is_main_query() && $query->is_archive() && ! is_admin() ) {
		if ( isset( $_GET ) && isset( $_GET['arcf'] ) ) {
			_arcw_add_filter_query( $query );
		}
	}

	return $query;
}

function _arcw_get_filter_params( $str = '' ) {
	$re = "/(\\w+):([^:]+)/";
	preg_match_all( $re, $str, $matches );

	$post_types = null;
	$cats       = null;

	foreach ( $matches[1] as $key => $value ) {
		if ( $value == 'post' ) {
			$post_types = explode( ' ', $matches[2][ $key ] );
		}
		if ( $value == 'cat' ) {
			$cats = explode( ' ', $matches[2][ $key ] );
		}
	}

	return array( "posts" => $post_types, "cats" => $cats );
}

function _arcw_add_filter_query( $query ) {
	$str        = ( $_GET['arcf'] );
	$params     = _arcw_get_filter_params( $str );
	$post_types = $params['posts'];
	$cats       = $params['cats'];

	if ( isset( $post_types ) ) {
		$query->set( 'post_type', $post_types );

		if ( isset( $cats ) ) {
			$query->set( 'tax_query', array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'category',
						'field'    => 'term_id',
						'terms'    => $cats,
						'operator' => 'IN',
					),
				)
			);
		}
	} elseif ( isset ( $cats ) ) {
		$query->set( 'cat', implode( ',', $cats ) );
	}

	return $query;
}

/***** CHECK MULTISITE NETWORK *****/
if ( ! function_exists( 'isMU' ) ) {
	function isMU() {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			return true;
		}

		return false;
	}
}

/** DEBUG FUNCTION **/
if ( ! function_exists( 'debug' ) ) {
	function debug( $context = "" ) {
		if ( ARCW_DEBUG === true ) {
			print_r( $context );
		}
	}
}
