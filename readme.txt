=== Archives Calendar Widget ===
Contributors: alekart
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4K6STJNLKBTMU
Tags: archives, calendar, widget, sidebar, view, plugin, monthly, daily
Requires at least: 3.6
Tested up to: 4.3
Stable tag: 1.0.5
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Archives widget that makes your monthly/daily archives look like a calendar on the sidebar.

== Description ==

Archives widget that make your monthly/daily archives look like a calendar on the sidebar. If you have a lot of archives that takes a lot of space on your sidebar this widget is for you. Display your archives as a compact calendar, entirely customizable with CSS.

= **PLEASE FEEDBACK** =
I'm alone to test this plugin before release and I can't test everything with particular configurations or on different versions of WordpPress, **your feedback is precious.**

= Features =

* **New** Simple Theme Editor (external tool)
* Displays monthly archives as a compact year calendar
* Displays daily archives as a compact month calendar
* Show/hide monthly post count
* 8 themes included (with LESS files)
* 2 Custom themes that keep your CSS styles even after the plugin update
* Different theme for each widget
* Show widget with previous/current/next or last available month
* Category select. Show post only from selected categories
* Filter Archives page by categories you set in the widget
* Custom post_type support (partial*)
* Entirely customizable with CSS
* .PO/.MO Localisation (Fançais, Deutsch, Español, Portugues, Simplified Chinese, Serbo-Croatian)
* jQuery animated with possibility to use your own JS code.

**Not just a widget**, if your theme does not support widgets, you can use this calendar by calling its **function**:

`archive_calendar();`

you can also configure it:
`$defaults = array(
    'next_text' => '˃', //text showing on the next year button, can be empty or HTML to use with Font Awesome for example.
    'prev_text' => '˂', //just like next_text but for previous year button.
    'post_count' => true, //show the number of posts for each month
    'month_view' => true, //show months instead of years archives, false by default.
    'month_select' => 'default', // shows the last month available with at least one post. Also `prev`, `next` or `current`.
    'different_theme' => 0, // set 1 (true) if you want to set a different theme for this widget
    'theme' => null, // theme 'name' if 'different_theme' == true
    'categories' => null, // array() -> list of categories to show
    'post_type' => null // array() -> list of post types to show
);
archive_calendar($args);`

`* Custom taxonomies are not supported. If your custom post_type post has no common category with post it will not be shown in the archives`

= ADDON =
**Popover Addon for Archives Calendar Widget**
[ARCW Popover Addon](https://wordpress.org/plugins/arcw-popover-addon/)

= ARCW THEME EDITOR =
[Create your own theme for the calendar](http://arcw.alek.be/)

= Notes =
Please use the Support section to report issues.


== Installation ==

1. Upload `archives-calendar-widget` folder in `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Configure the plugin through "Settings > Archives Calendar" menu in WordPress.
4. Activate and configure the widget in "Appearance > Widgets" menu in WordPress

== Screenshots ==

1. "Calendrier" theme preview in settings
2. Widget settings
3. Plugin settings
4. Widgets with different themes on the same page

== Changelog ==

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

= AFTER UPDATE TO THE 1.0.X YOU MAY NEED TO UPDATE YOUR WIDGET SETTINGS:=
Just open the settings of the widget, check if everything is ok and press "Save"
= ------ =
= SHORTCODE SUPPORT IS DROPPED =
I consider this feature as obsolete.
= ------ =
= IF UPDATING FROM v.0.4.7 =
Update to an older version: 0.9.91, 0.9.92 or 0.9.93 before updating to the latest version

== Frequently asked questions ==

= Custom texonomies are not supported. =
NO. Currently only default categories are supported.
Custom post_type that do not have common categories with post will not be displayed in the calendar if categories filter is different of "ALL".
Don't ask me if it is possible, i'm thinking about it... need time...


= Can I show a popover with list of posts? =

Yes, with my Popover Addon.
You can also do it with ajax request on day/month mouse over.
I don't want to make my plugin do everything like some softwares do (and that only 10% are used/usefull).