# Changelog

## 1.0.12
* [fix] fix the today date timezone: use `current_time` wp method

## 1.0.11
* [fix] fix the post count SQL request

## 1.0.10
* [fix] title of the calendar (year/month) not changing when navigating if the "Disable title link" option is enabled

## 1.0.9
* [fix] BIG BUG due to bad usage of the `pre_get_posts` query alteration, it was altering all the queries causing unwanted behavior
* [revert] back to previous filter link format (was a little bit prettier)

## 1.0.8
* [fix] yet one fix for archives filter url, seems that the GoodLayers themes has some issues with get parameters

## 1.0.7
* [fix] one more fix for archives filter url
* [fix] removed forgotten debug outputs
* [upd] set minimum required version of WordPress to 4.0
* [add] widget option to disable the header/title link. Clicking on the calendar title will open the month/year menu
* [fix] the header/title link filter if archives filter is enabled
* [fix] widget was broken if no post_type where selected in the widget options
 
## 1.0.6
* [upd] refactor plugin settings
* [upd] change default settings
* [upd] change jQuery plugin initialisation method
* [upd] change archives filter url to something more sexy
* [upd] add title attribute on days/month with posts
* [fix] `today` date based on timezone
* [upd] all themes converted to SCSS format (no more LESS)
* [fix] plugin settings were not updated with the new ones on plugin activation after an update
* [fix] issue with the widget settings in "Customizer", could not select any category.

## 1.0.5
* [fix] archives filter improvement and fixes

## 1.0.4
* [fix] roled back sql query to 1.0.2
* [fix] some bugs/errors

## 1.0.3
* [upd] Some query optimisations
* [fix] post count request simplified
* [fix] archives filter link

## 1.0.2
* [fix] forgotten debug text removed

## 1.0.1
* Day one fix
* [fix] update options after plugin update

## 1.0.0
* [new] Theme Editor (external)
* [new] added archives filter by category (have to be activated in options)
* [add] some themes are now available in SCSS
* [update] styling of the dropdown menu display in SCSS files
* [fix] fixed custom post_type errors that sometimes could occur (at least some bugs fixed)
* [del] shortcode feature *removed* (obsolete)

## 0.9.94
* [new] added "today" class for the current day in month view (if present).
* [fix] fixed a compatibility bug with "Jetpack by WordPress" plugin
* [fix] fixed some other little bugs

## 0.9.93
* [fix] "categories" bug

## 0.9.92
* [edit] PHP 5.4 is no more required
* [fix] some compatibility issues

## 0.9.91
* [NEW] Multi-theme Support. Set a different theme for each widget
* [NEW] Added two "Custom" themes. You can now modify the appearance without loosing your changes on every update.
* [new] New options for month view. Show previous, current or next month in first even if there is no posts (or the last month with at least one post)
* [new] Theme editor for custom themes. (for now only a code editor)
* [new] Shortcode now supports post_type and categories parameters
* [new] Serbo-Croatian language by Borisa Djuraskovic from WebHostingHub
* [edit] **IMPORTANT**: **HTML and CSS structure changes** (again)
* [fix] Fixed lots of bugs

## 0.9.9
* [FAIL] GHOST RELEASE
* [say] Happy Halloween!

## 0.4.7
* [fix] post count fix for specified categories

## 0.4.6
* [fix] post_type fix (it was considering only the first post_type that was defined)

## 0.4.5
* [new] Category select, show only selected categories' posts on the calendar
* [new] Custom post_type support
* [fix] Jquery is now included by dependencies ("not a function" error fix)
* [fix] Jquery is included by default for new installations

## 0.4.1
* [edit] German translation update

## 0.4.0
* [new] Month view for daily archives
* [new] "Classic" theme 
* [new] German translation by Jan Stelling
* [new] Simplified Chinese (zh_CN) by Qingqing Mao
* [new] Portuguese translation by Bruno
* [new] new jQuery code to support multiple calendar widgets and easier animation customisation
* [fix] in some cases the last year was disappearing while navigating with next/prev buttons
* [edit] now uses the wordpress locales to display month/weekdays names

## 0.3.2
* [new] Twenty Fourteen theme

## 0.3.1
* [new] SPANISH translation by Andrew Kurtis from WebHostingHub

## 0.3.0
* [new] select archive year from a list menu in year navigation
* [new] 3 themes with .less files for easier customization
* [new] shortcode [arcalendar]
* [new] the current archives' year is shown in the widget instead of the actual year
* [fix] if there's no posts in actual year, the widget does not disappear any more
* [edit] **HTML and CSS structure changes** in year navigation
* [edit] Total rewrite of year navigation jQuery script

## 0.2.4
* Fixed bad css style declaration for 3.6

## 0.2.3
* Fixed missing function that checks if MultiSite is activated.