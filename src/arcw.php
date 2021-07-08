<?php
/**
 * Plugin Name: Archives Calendar Widget 2
 * Plugin URI: https://wordpress.org/plugins/archives-calendar-widget/
 * Description: Archives widget that makes your monthly/daily archives look like a calendar.
 * Version: 2.0.0-snapshot
 * Text Domain: archives-calendar-widget
 * Author: Aleksei Polechin (alek)
 * Author URI: https://github.com/alekart
 * License: GPLv3
 */

/**
 * ## GPLv3 LICENSE
 * Archives Calendar Widget for WordPress
 * Copyright (C) 2013-2021 Aleksei Polechin (https://github.com/alekart)
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

define( 'ARCWVersion', '2-dev-' . time() ); // current version of the plugin

require_once(dirname( __FILE__ ) . '/class/class-ARCW.php');
require_once(dirname( __FILE__ ) . '/class/class-ArcwWidget.php');
require_once(dirname( __FILE__ ) . '/hooks/installation.php');

register_activation_hook( __FILE__, 'arcwActivate' );
register_uninstall_hook( __FILE__, 'arcwUninstall' );

// Wen a news site is created in Multisite mode
add_action( 'wp_initialize_site', 'arcwAddNewSite', 900 );
// Add "settings" action link in the Plugins settings list
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'arcwPluginActionLinks' );

// Register and load the widget
add_action( 'widgets_init', 'archivesCalendarLoadWidget' );
function archivesCalendarLoadWidget() {
	register_widget( 'ArcwWidget' );
}

function arcwAssets() {
	// Load JS
	wp_enqueue_script( 'arcw', plugins_url( 'arcw.js', __FILE__ ), [], ARCWVersion, true );
	wp_localize_script( 'arcw', 'ajaxurl', [ admin_url( 'admin-ajax.php' ) ] );
}

add_action( 'wp_enqueue_scripts', 'arcwAssets' );

/**
 * Add ajax actions to retrieve post archives in JSON format
 */
add_action( 'wp_ajax_arcwGetPosts', 'arcwGetPostsAjax' );
add_action( 'wp_ajax_nopriv_arcwGetPosts', 'arcwGetPostsAjax' );

/**
 * Query posts
 * @throws Exception
 */
function arcwGetPostsAjax() {
	$sql = ARCW::prepareSqlRequest( $_POST['post-type'], $_POST['categories'] );
	echo json_encode( ARCW::getPosts( $sql ) );
	wp_die();
}
