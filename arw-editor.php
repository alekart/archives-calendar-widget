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
	<style id="arwprev">
		<?php echo $custom['arw-theme1'];?>
	</style>

	<div ng-app="calendarEditorApp" ng-controller="editorCtrl">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="arcw-themer" class="tab active-tab">

				<div id="post-body-content">
					<div class="card new">
						<h2>Theme Editor</h2>
						<img id="themer-logo" src="<?php echo plugins_url('', __FILE__); ?>/admin/images/themer.png"
							 alt="ARCW Theme Editor"/>
						Simple UI to create your own theme for Archives Calendar Widget.<br/>
						<strong>No code knowledge needed.</strong>

						<p>
							<a href="http://arcw.alek.be" target="_blank"
							   class="button-primary button-big button-green">
								Open theme editor
							</a>
							<span>&nbsp; &nbsp;</span>
							<button id="import-theme" class="button-primary">Import Theme CSS</button>
							<input class="hidden" type="file" id="files" name="files" accept=".css"/>
							<br/>
							<span class="description">The editor is an external tool and will be opened a new tab/window.</span>
						</p>

					</div>

					<div id="theme-css-editor">
						<input id="tab-index" type="hidden" name="editor-tab" value="0"/>
                        <textarea class="hidden" name="archivesCalendarThemer[arw-theme1]"
								  id="codesource1"><?php echo $custom['arw-theme1']; ?></textarea>
                        <textarea class="hidden" name="archivesCalendarThemer[arw-theme2]"
								  id="codesource2"><?php echo $custom['arw-theme2']; ?></textarea>

						<h2 class="nav-tab-wrapper custom">
							<a href="#theme1" class="nav-tab nav-tab-active"><?php _e('Theme'); ?> 1</a>
							<a href="#theme2" class="nav-tab"><?php _e('Theme'); ?> 2</a>
						</h2>

						<div class="tabs">
							<div id="theme1" class="tab active-tab">

<pre id="editor1">
<?php echo $custom['arw-theme1']; ?>
</pre>

							</div>
							<div id="theme2" class="tab">

<pre id="editor2">
<?php echo $custom['arw-theme2']; ?>
</pre>

							</div>
						</div>
					</div>
				</div>

				<div id="postbox-container-1" class="postbox-container">
					<div class="col-wrap">

						<div class="arcw card">
							<h2><?php _e('Preview', 'arwloc'); ?></h2>
							<?php
							year_preview_html();
							month_preview_html();
							?>
						</div>

						<p>
							<input name="Submit" type="submit" class="button-primary"
								   value="<?php _e('Save Changes'); ?>">
						</p>

					</div>
				</div>
			</div>
		</div>
	</div>

	<?php
}

function archivesCalendar_themer_validate($args)
{
	foreach ($args as $file => $css) {
		arcw_write_css($file, $css);
	}

	$update_message = __('Updated.') . $_POST["editor-tab"] .  '<script>var editor_tab = ' . $_POST["editor-tab"] . ';</script>';
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