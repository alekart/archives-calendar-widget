<?php
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
if($options = get_option( 'archivesCalendar' ) && $options.count() > 0)
$options = array(
"css" => 1,
"theme" => "calendrier",
"jquery" => 0,
"js" => 1,
"show_settings" => 1,
"shortcode" => 0,
"javascript" => "jQuery(document).ready(function($){\n\t$('.calendar-archives').archivesCW();\n});"
);
else
$options = get_option( 'archivesCalendar' );

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
register_activation_hook(__FILE__, 'archivesCalendar_activation');

function archivesCalendar_new_blog($blog_id)
{
global $wpdb;
if (is_plugin_active_for_network('archives-calendar/archives-calendar.php'))
{
$old_blog = $wpdb->blogid;
switch_to_blog($blog_id);
_archivesCalendar_activate();
switch_to_blog($old_blog);
}
}
add_action( 'wpmu_new_blog', 'archivesCalendar_new_blog', 10, 6); // in case of creation of a new site in WPMU


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
global $wpdb;
if (isMU())
delete_blog_option($wpdb->blogid, 'archivesCalendar');
else
delete_option('archivesCalendar');
}
register_uninstall_hook(__FILE__, 'archivesCalendar_uninstall');
