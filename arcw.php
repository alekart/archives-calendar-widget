<?php
/**
 * Plugin Name:       Archives Calendar 2
 * Description:       Archives Calendar 2.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           2.0.0
 * Author:            alekart
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       arcw
 * Domain Path:       arcw
 *
 * @package           create-block
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function arcw_arcw_block_init() {
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'arcw_arcw_block_init' );
