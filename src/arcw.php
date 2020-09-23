<?php
/**
 * Plugin Name: Archives Calendar Widget
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

require_once(dirname( __FILE__ ) . '/hooks/installation.php');

register_activation_hook( __FILE__, 'arcw_activate' );
register_uninstall_hook( __FILE__, 'arcw_uninstall' );
