<?php
/*
Archives Calendar Widget THEME EDITOR
Author URI: http://alek.be
License: GPLv3
*/

function archivesCalendar_themer()
{
    include 'admin/preview.php';
    $custom = get_option('archivesCalendarThemer');


    ?>
    <style id="style"></style>

    <div ng-app="calendarEditorApp" ng-controller="editorCtrl">
        <div id="arcw-themer" class="tab active-tab">
            <div id="col-container">

                <div id="col-right">
                    <div class="col-wrap">

                        <div class="arcw preview-zone">
                            <?php
                            year_preview_html();
                            month_preview_html();
                            ?>
                        </div>

                    </div>
                </div>

                <div id="col-left">
                    <div class="col-wrap">

                        <div class="accordion-container "
                             style="border-right: 1px solid #ddd; border-top: 1px solid #ddd; border-left: 1px solid #ddd" tabindex="-1">


                            <div id="customize-theme-controls">
                                <ul>
                                    <li class="control-section-title control-section control-section-themes" style="display: list-item;">
                                        <h3 class="accordion-section-title">
                                            Navigation
                                        </h3>
                                    </li>

                                    <li id="accordion-section-colors" class="control-section accordion-section">
                                        <h3 class="accordion-section-title" tabindex="0">Couleurs de la barre de
                                            navigation</h3>
                                        <ul class="accordion-section-content">

                                            <li id="customize-control"
                                                class="customize-control customize-control-color">
                                                <label>
                                                    <span class="customize-control-title">Couleur de fond</span>
                                                    <input type='text' data-default-color="{{ navigation.background }}"
                                                           class='color-field' ng-model="navigation.background">
                                                </label>
                                                {{ navigation.background }}


                                            </li>

                                            <li id="customize-control"
                                                class="x@customize-control customize-control-color">
                                                <label>
                                                    <span class="customize-control-title">Couleur du texte</span>
                                                    <input type='text' class='color-field' ng-model="navigation.color">
                                                </label>
                                            </li>


                                            <li id="customize-control"
                                                class="customize-control customize-control-color">
                                                <label>
                                                    <span class="customize-control-title">Border color</span>
                                                    <input type='text' class='color-field'
                                                           ng-model="navigation.borderColor">
                                                </label>
                                            </li>

                                            <li>
                                                <span
                                                    class="customize-control-title">Border: {{ navigation.border }}px</span>

                                                <div slider ng-model="navigation.border" data-min="0"
                                                     data-max="10"></div>
                                            </li>

                                            <li>
                                                <label>
                                                    <span class="customize-control-title">Border top: {{ navigation.borderTop }}px</span>

                                                    <div slider ng-model="navigation.borderTop" data-min="0"
                                                         data-max="10"></div>
                                                </label>
                                                <label>
                                                    <span class="customize-control-title">Border right: {{ navigation.borderRight }}px</span>

                                                    <div slider ng-model="navigation.borderRight" data-min="0"
                                                         data-max="10"></div>
                                                </label>
                                                <label>
                                                    <span class="customize-control-title">Border bottom: {{ navigation.borderBottom }}px</span>

                                                    <div slider ng-model="navigation.borderBottom" data-min="0"
                                                         data-max="10"></div>
                                                </label>
                                                <label>
                                                    <span class="customize-control-title">Border left: {{ navigation.borderLeft }}px</span>

                                                    <div slider ng-model="navigation.borderLeft" data-min="0"
                                                         data-max="10"></div>
                                                </label>
                                            </li>

                                        </ul>
                                    </li>

                                    <li id="accordion-section-colors" class="control-section accordion-section">
                                        <h3 class="accordion-section-title" tabindex="0">Couleurs des fleches</h3>
                                        <ul class="accordion-section-content">

                                            <li id="customize-control-header_textcolor"
                                                class="customize-control customize-control-color">
                                                <label>
                                                    <span class="customize-control-title">Active</span>

                                                    <div class="customize-control-content">
                                                        <input id="cal-nav a.prev-year__color" type='text'
                                                               class='color-field cal'>
                                                    </div>
                                                </label>
                                            </li>

                                            <li id="customize-control-header_textcolor"
                                                class="customize-control customize-control-color">
                                                <label>
                                                    <span class="customize-control-title">Survolé</span>

                                                    <div class="customize-control-content">
                                                        <input type='text' class='color-field'>
                                                    </div>
                                                </label>
                                            </li>

                                            <li id="customize-control-background_color"
                                                class="customize-control customize-control-color">
                                                <label>
                                                    <span class="customize-control-title">Désactivé</span>

                                                    <div class="customize-control-content">
                                                        <input type='text' class='color-field'>
                                                    </div>
                                                </label>
                                            </li>

                                        </ul>
                                    </li>

                                    <li id="accordion-section-colors" class="control-section accordion-section">
                                        <h3 class="accordion-section-title" tabindex="0">Couleurs</h3>
                                        <ul class="accordion-section-content">

                                            <li id="customize-control-header_textcolor"
                                                class="customize-control customize-control-color">
                                                <label>
                                                    <span
                                                        class="customize-control-title">Couleur de la navigation</span>

                                                    <div class="customize-control-content">
                                                        <input type='text' class='color-field'>
                                                    </div>
                                                </label>
                                            </li>

                                            <li id="customize-control-header_textcolor"
                                                class="customize-control customize-control-color">
                                                <label>
                                                    <span class="customize-control-title">Couleur du mois/jour</span>

                                                    <div class="customize-control-content">
                                                        <input type='text' class='color-field'>
                                                    </div>
                                                </label>
                                            </li>

                                            <li id="customize-control-background_color"
                                                class="customize-control customize-control-color">
                                                <label>
                                                    <span
                                                        class="customize-control-preview-bg">Couleur du mois/jour vide</span>

                                                    <div class="customize-control-content">
                                                        <input type='text' class='color-field'>
                                                    </div>
                                                </label>
                                            </li>

                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

                <br class="clear" />



                <div id="post-body">
                <textarea class="hidden" name="archivesCalendarThemer[arw-theme1]"
                          id="codesource1"><?php echo $custom['arw-theme1']; ?></textarea>
                <textarea class="hidden" name="archivesCalendarThemer[arw-theme2]"
                          id="codesource2"><?php echo $custom['arw-theme2']; ?></textarea>

                    <input name="tab" id="current_tab" type="hidden" value="0">

                    <h2 class="nav-tab-wrapper custom">
                        <a href="#theme1" class="nav-tab nav-tab-active"><?php _e('Theme'); ?> 1</a>
                        <a href="#theme2" class="nav-tab"><?php _e('Theme'); ?> 2</a>
                    </h2>

                    <div class="tabs">
                        <div id="theme1" class="tab active-tab">
<div id="editor1">
<?php echo $custom['arw-theme1']; ?>
</div>
                        </div>
                        <div id="theme2" class="tab">
<div id="editor2">
<?php echo $custom['arw-theme2']; ?>
</div>
                        </div>
                    </div>

                    <hr/>
                    <p>
                        <input name="Submit" type="submit" style="margin:20px 0;" class="button-primary"
                               value="<?php _e('Save Changes'); ?>">
                    </p>
                    <!--<input name="Submit" type="button" style="margin:20px 0;" class="button"
		       value="<?php _e('Reset', 'arwloc'); ?>"/>-->
                </div>
            </div>

        </div>
<pre id="css">

.calendar-archives {
    position: relative;
    width: 100%;
}

.calendar-archives * {
    box-sizing: border-box !important;
}

.calendar-archives a,
.calendar-archives a:focus,
.calendar-archives a:active {
    outline: none !important;
}

.calendar-archives > .calendar-navigation {
    position: relative;
    display: table;
    width: 100%;
}

.calendar-archives > .calendar-navigation > .prev-year,
.calendar-archives > .calendar-navigation > .next-year,
.calendar-archives > .calendar-navigation > .menu-container {
    display: table-cell;
    height: 100%;
    vertical-align: middle;
}

.calendar-archives > .archives-years {
    position: relative;
    overflow: hidden;
}

.calendar-archives > .archives-years > .year {
    position: absolute;
    top: 0;
    left: 0;
    margin-left: -100%;
    width: 100%;
    z-index: 0;
}

.calendar-archives > .archives-years > .year .year-link {
    display: none;
}

.calendar-archives > .archives-years > .year.last {
    position: relative;
}

.calendar-archives > .archives-years > .year.current {
    margin-left: 0;
    z-index: 1;
}

.settings_page_Arrchives_Calendar_Widget #TB_ajaxContent {
    background-color: {{ common.preview }};
}

.calendar-archives.twentythirteen a {
    text-decoration: none;
}

.calendar-archives.twentythirteen > .calendar-navigation {
    height: {{ navigation.height }}px !important;
    margin: {{ navigation.marginTop }}px {{ navigation.marginRight }}px {{ navigation.marginBottom }}px {{ navigation.marginLeft }}px;

    color: {{ navigation.color }};

    border-top: {{ navigation.borderTop }}px;
    border-right: {{ navigation.borderRight }}px;
    border-bottom: {{ navigation.borderBottom }}px;
    border-left: {{ navigation.borderLeft }}px;
    border-style: solid;
    border-color: {{ navigation.borderColor }};

    border-radius: {{ navigation.borderRadius }}px;

	background-color: {{ navigation.background }};
}

.calendar-archives.twentythirteen > .calendar-navigation > .prev-year,
.calendar-archives.twentythirteen > .calendar-navigation > .next-year {
    width: {{ navigation.buttonWidth }}px;
    border-radius: {{ navigation.borderRadius }}px;
    font-size: {{ navigation.fontSize }}px;
    text-align: center;
    color: {{ navigation.color }};
}

.calendar-archives.twentythirteen > .calendar-navigation > .prev-year:hover,
.calendar-archives.twentythirteen > .calendar-navigation > .next-year:hover {
    background-color: {{ navigation.button.hover.background }};
}

.calendar-archives.twentythirteen > .calendar-navigation > .prev-year.disabled,
.calendar-archives.twentythirteen > .calendar-navigation > .next-year.disabled {
    opacity: {{ navigation.button.disabled.opacity / 100 }};
    cursor: default;
}

.calendar-archives.twentythirteen > .calendar-navigation > .prev-year.disabled:hover,
.calendar-archives.twentythirteen > .calendar-navigation > .next-year.disabled:hover {
    background: none;
}

.calendar-archives.twentythirteen > .calendar-navigation > .prev-year {
    border-right: {{ navigation.button.borderWidth }}px {{ navigation.borderColor }} solid;
    border-bottom-right-radius: 0;
    border-top-right-radius: 0;
}

.calendar-archives.twentythirteen > .calendar-navigation > .next-year {
    border-left: {{ navigation.button.borderWidth }}px {{ navigation.button.borderColor }} solid;
    border-bottom-left-radius: 0;
    border-top-left-radius: 0;
}

.calendar-archives.twentythirteen > .calendar-navigation > .menu-container {
    position: relative;
    height: {{ navigation.height }}px;
    padding: 0;
    text-align: center;
    text-transform: capitalize;
}

.calendar-archives.twentythirteen > .calendar-navigation > .menu-container > a.title {
    display: block;
    height: {{ navigation.height }}px;
    line-height: {{ navigation.height }}px;
    color: {{ navigation.color }};
    vertical-align: middle;
}

.calendar-archives.twentythirteen > .calendar-navigation > .menu-container > ul,
.calendar-archives.twentythirteen > .calendar-navigation > .menu-container > ul > li {
    margin: 0;
    padding: 0;
}

.calendar-archives.twentythirteen > .calendar-navigation > .menu-container > ul.menu {
    position: absolute;
    display: none;
    width: 100%;
    top: 0;
    overflow: hidden;
    border-radius: {{ navigation.borderRadius }};
    box-shadow: #000 0 0 10px;
    background: {{ navigation.menu.background }};
    z-index: 99;
}

.calendar-archives.twentythirteen > .calendar-navigation > .menu-container li {
    display: block;
}

.calendar-archives.twentythirteen > .calendar-navigation > .menu-container li > a {
    display: block;
    height: {{ navigation.height }}px;
    line-height: {{ navigation.height }}px;
    color: {{ navigation.color }};
}

.calendar-archives.twentythirteen > .calendar-navigation > .menu-container li > a:hover {
    cursor: pointer;
    color: {{ navigation.menu.hover.color }};
    background: {{ navigation.menu.hover.background }};
}

.calendar-archives.twentythirteen > .calendar-navigation > .menu-container li > a.selected {
    color: {{ navigation.menu.selected.color }};
    background: {{ navigation.menu.selected.background }};
}

.calendar-archives.twentythirteen > .calendar-navigation > .menu-container li > a.selected:hover {
    text-decoration: none;
    cursor: default;
    color: {{ navigation.menu.selected.color }};
    background: {{ navigation.menu.selected.background }};
}

.calendar-archives.twentythirteen > .calendar-navigation > .menu-container > .arrow-down {
    position: absolute;
    width: 24px;
    height: {{ navigation.height }}px;
    line-height: {{ navigation.height }}px;
    top: 0;
    right: 0;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 9px;
    color: {{ navigation.button.color }};
    cursor: pointer;
}

.calendar-archives.twentythirteen > .calendar-navigation > .menu-container > .arrow-down:hover {
    background-color: {{ navigation.button.hover.background }}
}

.calendar-archives.twentythirteen > .calendar-navigation > .menu-container:hover > .arrow-down {
    border-left: {{ navigation.button.borderWidth }}px {{ navigation.borderColor }} solid;
}

.calendar-archives.twentythirteen > .archives-years {
    background-color: #220E10;
}

.calendar-archives.twentythirteen .month,
.calendar-archives.twentythirteen .day {
    position: relative;
    display: block;
    overflow: hidden;
    float: left;
    color: #e6402a;
    background: #3a1c1f;
    border-bottom: 1px #220E10 solid;
    border-right: 1px #220E10 solid;
    border-radius: 3px;
}

.calendar-archives.twentythirteen .month.has-posts a,
.calendar-archives.twentythirteen .day.has-posts a {
    display: block;
    width: 100%;
    height: 100%;
    border-bottom: 3px solid #b93207;
    border-radius: 3px;
    color: #FFF;
    background-image: -webkit-linear-gradient(top, #e05d22 0%, #d94412 100%);
    background-image: -o-linear-gradient(top, #e05d22 0%, #d94412 100%);
    background-image: linear-gradient(to bottom, #e05d22 0%, #d94412 100%);
    background-repeat: repeat-x;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFE05D22', endColorstr='#FFD94412', GradientType=0);
}

.calendar-archives.twentythirteen .month.has-posts a:hover,
.calendar-archives.twentythirteen .day.has-posts a:hover {
    background-image: -webkit-linear-gradient(top, #ed6a31 0%, #e55627 100%);
    background-image: -o-linear-gradient(top, #ed6a31 0%, #e55627 100%);
    background-image: linear-gradient(to bottom, #ed6a31 0%, #e55627 100%);
    background-repeat: repeat-x;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFED6A31', endColorstr='#FFE55627', GradientType=0);
}

.calendar-archives.twentythirteen .month.last,
.calendar-archives.twentythirteen .day.last {
    margin-right: 0 !important;
}

.calendar-archives.twentythirteen .month {
    width: 25%;
    height: 50px;
}

.calendar-archives.twentythirteen .month .month-name {
    text-transform: capitalize;
    font-size: 16px;
    font-weight: 400;
    display: block;
    position: absolute;
    top: 6px;
    left: 8px;
}

.calendar-archives.twentythirteen .month .postcount {
    display: block;
    position: absolute;
    right: 6px;
    bottom: 6px;
}

.calendar-archives.twentythirteen .month .postcount .count-text {
    font-size: 9px;
}

.calendar-archives.twentythirteen .day {
    width: 14.285% !important;
    height: 25px;
    padding: 5px 0;
    text-align: center;
    line-height: 100%;
}

.calendar-archives.twentythirteen .day.has-posts {
    padding: 0;
}

.calendar-archives.twentythirteen .day.has-posts a {
    padding: 5px 0 !important;
    text-decoration: none;
}

.calendar-archives.twentythirteen .day.noday {
    border: none;
    box-shadow: none;
    background: none !important;
}

.calendar-archives.twentythirteen .day.weekday {
    display: inline-block;
    border: none;
    font-size: 85%;
    color: #FFF;
    text-transform: uppercase;
    box-shadow: none;
    background: none !important;
}

.calendar-archives.twentythirteen .week-row {
    margin: 0;
    padding: 0;
    overflow: hidden;
    background: #220E10;
}

.calendar-archives.twentythirteen .week-row.weekdays {
    margin-bottom: 3px;
}
</pre>
    </div>


    <?php
}

function archivesCalendar_themer_validate($args)
{
    foreach ($args as $file => $css) {
        arcw_write_css($file, $css);
    }

    $update_message = __('Updated.') . '<script>var themer_tab = ' . $_POST["tab"] . ';</script>';
    add_settings_error('themer', 'ok', $update_message, 'updated');
    return $args;
}

function arcw_write_css($file, $css)
{
    global $wpdb;
    if ($css) {
        if (isMU()) {
            $old_blog = $wpdb->blogid;
            $blogids = $wpdb->get_results("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blogids as $blogid) {
                $blog_id = $blogid->blog_id;
                switch_to_blog($blog_id);
                $filename = '../wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/themes/' . $file . '-' . $wpdb->blogid . '.css';
                $themefile = fopen($filename, "w") or die("Unable to open file!");
                fwrite($themefile, $css);
                fclose($themefile);
            }
            switch_to_blog($old_blog);
        } else {
            $filename = '../wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/themes/' . $file . '.css';
            $themefile = fopen($filename, "w") or die("Unable to open file!");
            fwrite($themefile, $css);
            fclose($themefile);
        }
    }
}