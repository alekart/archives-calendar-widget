=== Archives Calendar Widget ===
Contributors: alekart
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4K6STJNLKBTMU
Tags: archives, calendar, widget, sidebar, view, plugin, monthly, daily
Requires at least: 4.0
Tested up to: 4.6.1
Stable tag: 1.0.12
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Archives widget that makes your monthly/daily archives look like a calendar on the sidebar.

== Description ==

Archives widget that make your monthly/daily archives look like a calendar on the sidebar. If you have a lot of archives that takes a lot of space on your sidebar this widget is for you. Display your archives as a compact calendar, entirely customizable with CSS.

= **PLEASE FEEDBACK** =
I'm alone to test this plugin before release and I can't test everything with particular configurations or on different versions of WordpPress, **your feedback is precious.**

= Features =

* Theme Editor GUI (external tool)
* Displays monthly archives as a compact year calendar
* Displays daily archives as a compact month calendar
* Show/hide monthly post count
* 8 themes included (with SCSS files)
* 2 Custom themes that keep your CSS style even after the plugin update
* Different theme for each widget
* Show widget with previous/current/next or last available month
* Category select. Show post only from selected categories
* Filter Archives page by categories you set in the widget
* Custom post_type partial* support
* Entirely customizable with CSS
* .PO/.MO Localisation English/Français + OUTDATED: [Deutsch, Español, Portugues, Simplified Chinese, Serbo-Croatian]
* jQuery animated with possibility to use your own JS code.

**Not just a widget**, if your theme does not support widgets, you can use this calendar by calling its **function**:

`archive_calendar();`

you can also configure it:

`
$defaults = array(
    'next_text' => '˃', //text showing on the next year button, can be empty or HTML to use with Font Awesome for example.
    'prev_text' => '˂', //just like next_text but for previous year button.
    'post_count' => true, //show the number of posts for each month
    'month_view' => true, //show months instead of years archives, false by default.
    'month_select' => 'default', // shows the last month available with at least one post. ('prev', 'next', 'current').
    'different_theme' => 0, // set 1 (true) if you want to set a different theme for this widget
    'theme' => null, // theme 'name' if 'different_theme' == true
    'categories' => null, // array() -> list of categories IDs to show. array(1,2,34)
    'post_type' => null // array() -> list of post types to show. array('post', 'movies')
);
archive_calendar($args);
`

**Custom taxonomies are not supported. If your custom post_type post has no common category with post it will not be shown in the archives**

= ADDON =
**Popover Addon for Archives Calendar Widget**
[ARCW Popover Addon](https://wordpress.org/plugins/arcw-popover-addon/)

= ARCW THEME EDITOR =
[Create your own theme for the calendar](http://arcw.alek.be/)

= Notes =
Please use the Support section to report issues. **No support will be provided via email.**


== Installation ==

1. Upload `archives-calendar-widget` folder in `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Configure the plugin through "Settings > Archives Calendar" menu in WordPress.
4. Activate and configure the widget in "Appearance > Widgets" menu in WordPress

== Screenshots ==

1. Plugin settings
2. Calendar Theme selector with preview
3. Widget settings
4. Widgets with different themes on the same page

== Changelog ==

= 1.0.12 =
* [fix] fix the today date timezone: use `current_time` wp method

= 1.0.11 =
* [fix] fix the post count SQL request

= 1.0.10 =
* [fix] title of the calendar (year/month) not changing when navigating if the "Disable title link" option is enabled

= 1.0.9 =
* [fix] BIG BUG due to bad usage of the `pre_get_posts` query alteration, it was altering all the queries causing unwanted behavior
* [revert] back to previous filter link format (was a little bit prettier)

= 1.0.8 =
* [fix] yet one fix for archives filter url, seems that the GoodLayers themes has some issues with get parameters

= 1.0.7 =
* [fix] one more fix for archives filter url
* [fix] removed forgotten debug outputs
* [upd] set minimum required version of WordPress to 4.0
* [add] widget option to disable the header/title link. Clicking on the calendar title will open the month/year menu
* [fix] the header/title link filter if archives filter is enabled
* [fix] widget was broken if no post_type where selected in the widget options
 
= 1.0.6 =
* [upd] refactor plugin settings
* [upd] change default settings
* [upd] change jQuery plugin initialisation method
* [upd] change archives filter url to something more sexy
* [upd] add title attribute on days/month with posts
* [fix] `today` date based on timezone
* [upd] all themes converted to SCSS format (no more LESS)
* [fix] plugin settings were not updated with the new ones on plugin activation after an update
* [fix] issue with the widget settings in "Customizer", could not select any category.

= 1.0.5 =
* [fix] archives filter improvement and fixes

= 1.0.4 =
* [fix] roled back sql query to 1.0.2
* [fix] some bugs/errors

= 1.0.3 =
* [upd] Some query optimisations
* [fix] post count request simplified
* [fix] archives filter link

= 1.0.2 =
* [fix] forgotten debug text removed

= 1.0.1 =
* Day one fix
* [fix] update options after plugin update

= 1.0.0 =
* [new] Theme Editor (external)
* [new] added archives filter by category (have to be activated in options)
* [add] some themes are now available in SCSS
* [update] styling of the dropdown menu display in SCSS files
* [fix] fixed custom post_type errors that sometimes could occur (at least some bugs fixed)
* [del] shortcode feature *removed* (obsolete)

= 0.9.94 =
* [new] added "today" class for the current day in month view (if present).
* [fix] fixed a compatibility bug with "Jetpack by WordPress" plugin
* [fix] fixed some other little bugs

= 0.9.93 =
* [fix] "categories" bug

= 0.9.92 =
* [edit] PHP 5.4 is no more required
* [fix] some compatibility issues

= 0.9.91 =
* [NEW] Multi-theme Support. Set a different theme for each widget
* [NEW] Added two "Custom" themes. You can now modify the appearance without loosing your changes on every update.
* [new] New options for month view. Show previous, current or next month in first even if there is no posts (or the last month with at least one post)
* [new] Theme editor for custom themes. (for now only a code editor)
* [new] Shortcode now supports post_type and categories parameters
* [new] Serbo-Croatian language by Borisa Djuraskovic from WebHostingHub
* [edit] **IMPORTANT**: **HTML and CSS structure changes** (again)
* [fix] Fixed lots of bugs

= 0.9.9 =
* [FAIL] GHOST RELEASE
* [say] Happy Halloween!

= 0.4.7 =
* [fix] post count fix for specified categories

= 0.4.6 =
* [fix] post_type fix (it was considering only the first post_type that was defined)

= 0.4.5 =
* [new] Category select, show only selected categories' posts on the calendar
* [new] Custom post_type support
* [fix] Jquery is now included by dependencies ("not a function" error fix)
* [fix] Jquery is included by default for new installations

= 0.4.1 =
* [edit] German translation update

= 0.4.0 =
* [new] Month view for daily archives
* [new] "Classic" theme 
* [new] German translation by Jan Stelling
* [new] Simplified Chinese (zh_CN) by Qingqing Mao
* [new] Portuguese translation by Bruno
* [new] new jQuery code to support multiple calendar widgets and easier animation customisation
* [fix] in some cases the last year was disappearing while navigating with next/prev buttons
* [edit] now uses the wordpress locales to display month/weekdays names

= 0.3.2 =
* [new] Twenty Fourteen theme

= 0.3.1 =
* [new] SPANISH translation by Andrew Kurtis from WebHostingHub

= 0.3.0 =
* [new] select archive year from a list menu in year navigation
* [new] 3 themes with .less files for easier customization
* [new] shortcode [arcalendar]
* [new] the current archives' year is shown in the widget instead of the actual year
* [fix] if there's no posts in actual year, the widget does not disappear any more
* [edit] **HTML and CSS structure changes** in year navigation
* [edit] Total rewrite of year navigation jQuery script

= 0.2.4 =
* Fixed bad css style declaration for 3.6

= 0.2.3 =
* Fixed missing function that checks if MultiSite is activated.

== Upgrade notice ==

= AFTER UPDATE FROM 0.9.XX TO THE 1.0.X YOU MAY NEED TO UPDATE YOUR WIDGET SETTINGS:=
Just open the settings of the widget, check if everything is ok and press "Save"

== Frequently asked questions ==

= Custom texonomies are not supported. =
NO. Currently only default categories are supported.
Custom post_type that do not have common categories with post will not be displayed in the calendar if categories filter is different of "ALL".
Don't ask me if it is possible, i'm thinking about it... need time...


= Can I show a popover with list of posts? =

Yes, with my Popover Addon.
You can also do it with ajax request on day/month mouse over.
I don't want to make my plugin do everything like some softwares do (and that only 10% are used/usefull).