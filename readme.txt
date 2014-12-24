=== Archives Calendar Widget ===
Contributors: alekart
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4K6STJNLKBTMU
Tags: archives, calendar, widget, sidebar, view, plugin, monthly, daily
Requires at least: 3.6
Tested up to: 4.1
Stable tag: 0.9.94
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Archives widget that makes your monthly/daily archives look like a calendar on the sidebar.

== Description ==

Archives widget that make your monthly/daily archives look like a calendar on the sidebar. If you have a lot of archives that takes a lot of space on your sidebar this widget is for you. Display your archives as a compact calendar, entirely customizable with CSS.

= **PLEASE FEEDBACK** =
I'm alone to test this plugin before release and I can't test everything with particular configurations or on different versions of WordpPress, **your feedback is precious.**

= Features =

* Displays monthly archives as a compact year calendar
* Displays daily archives as a compact month calendar
* Show/hide monthly post count
* 8 themes included (with .less files)
* 2 Custom themes taht keep your CSS styles even after the plugin update
* Choose a different theme for each widget
* Show widget with previous/current/next or last available month
* Category select. Show post only from selected categories
* Custom post_type support
* Entirely customizable with CSS
* .PO/.MO Localisation (Fançais, Deutsch, Español, Portugues, Simplified Chinese, Serbo-Croatian)
* Shortcode support
* jQuery animated with possibility to use your own JS code.

**Not just a widget**, if your theme does not support widgets, you can use this calendar by calling its **function**:

`archive_calendar();`

you can also configure it:
`$defaults = array(
    'next_text' => '˃',
    'prev_text' => '˂',
    'post_count' => true,
    'month_view' => true,
    'month_select' => 'default',
    'different_theme' => 0, // set 1 (true) if you want to set a different theme for this widget
    'theme' => null, // theme 'name' if 'different_theme' == true
    'categories' => null, // array() -> list of categories to show
    'post_type' => null // array() -> list of post types to show
);
archive_calendar($args);`

**next_text:** text showing on the next year button, can be empty or HTML to use with Font Awesome for example.

**prev_text:** just like `next_text` but for previous year button.

**post_count:** `true` to show the number of posts for each month, `false` to hide it. If you hide post count with CSS, set to false to avoid counting posts uselessly.

**month_view:** `true` to show months instead of years archives, false by default.

**month_select:** `default` shows the last month available with at least one post. Also `prev`, `next` or `current`.

**SHORTCODE SUPPORT**
Use the shortcode to show Archives Calendar in the text widget or in a page:
`[arcalendar next_text=>'>' prev_text=>'<' post_count=>"true" month_view=>"true" "categories"=>"category1, category2", post_type=>"post, forum"]`

*In some cases the support of shortcodes in the text widget has to be activated in the plugin settings*


= Notes =
Please use the Support section to report issues.

= Links =
[Project's page](http://labs.alek.be/projects/archives-calendar-widget/)
[Other projects](http://labs.alek.be/projects/)
[Portfolio](http://alek.be)

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
* [fix] if there's no posts in actual year, the widget does not disapear any more
* [edit] **HTML and CSS structure changes** in year navigation
* [edit] Total rewrite of year navigation jQuery script

= 0.2.4 =
* Fixed bad css style declaration for 3.6

= 0.2.3 =
* Fixed missing function that checks if MultiSite is activated.

== Upgrade notice ==

= IF UPDATING FROM v.0.4.7 =
Update to an older version: 0.9.91, 0.9.92 or 0.9.93.

== Frequently asked questions ==