<?php
/*
Archives Calendar Widget THEME EDITOR
Author URI: http://alek.be
License: GPLv3
*/

function archivesCalendar_themer() {
	include 'admin/preview.php';

	$custom = get_option( 'archivesCalendarThemer' );
	?>
	<script src="<?php echo plugins_url('/admin/js/ace-edit/ace.js', __FILE__);?>" type="text/javascript" charset="utf-8"></script>
	<style type="text/css" media="screen">
		#poststuff #post-body.columns-2{
			margin-right: 500px;
		}
		#post-body.columns-2 #postbox-container-1 {
			margin-right: -500px;
			width: 400px;
		}
	</style>
	<style id="arwprev">
		<?php echo $custom['arw-theme1'];?>
	</style>
	<div>
		<textarea class="hidden" name="archivesCalendarThemer[arw-theme1]" id="codesource1"><?php echo $custom['arw-theme1'];?></textarea>
		<textarea class="hidden" name="archivesCalendarThemer[arw-theme2]" id="codesource2"><?php echo $custom['arw-theme2'];?></textarea>

		<div class="updated warning below-h2">
			<p class=""description">
			This is a beta/temporary version of Themer for the widget. It is fully functional but in future I'd like to make it (if I have enough time) more accessible for people who have difficulties with CSS.<br/>
			This is a new feature and <strong>these custom themes will remain even after the plugin update.</strong><br/>
			You can use the editor below or your favorite IDE (LESS is easier) and then copy/paste your CSS in the editor. (this editor do not convert LESS to CSS)
			</p>
		</div>
		<input name="tab" id="current_tab" type="hidden" value="0">

		<h2 class="nav-tab-wrapper custom">
			<a href="#theme1" class="nav-tab nav-tab-active"><?php _e('Theme');?> 1</a>
			<a href="#theme2" class="nav-tab"><?php _e('Theme');?> 2</a>
		</h2>
		<div class="tabs">
			<div id="theme1" class="tab active-tab">
<pre id="editor1">
<?php echo $custom['arw-theme1'];?>
</pre>
			</div>
			<div id="theme2" class="tab">
<pre id="editor2">
<?php echo $custom['arw-theme2'];?>
</pre>
			</div>
		</div>

		<script>
			var editor1 = ace.edit("editor1");
			editor1.setTheme("ace/theme/monokai");
			editor1.getSession().setMode("ace/mode/css")

			var editor2 = ace.edit("editor2");
			editor2.setTheme("ace/theme/monokai");
			editor2.getSession().setMode("ace/mode/css");

			jQuery(function($){
				editor1.on("change", function(e){
					$('#codesource1').html(editor1.getValue());
					$('#arwprev').html(editor1.getValue());
				});
				editor2.on("change", function(e){
					$('#codesource2').html(editor2.getValue());
					$('#arwprev').html(editor2.getValue());
				});
			});
		</script>

		<hr/>
		<p>
			<input name="Submit" type="submit" style="margin:20px 0;" class="button-primary" value="<?php _e('Save Changes');?>">
		</p>
		<!--<input name="Submit" type="button" style="margin:20px 0;" class="button"
		       value="<?php _e( 'Reset', 'arwloc' ); ?>"/>-->
	</div>
	</div> <!-- end of column one, opened in general settings file -->
	<div id="postbox-container-1" class="postbox-container">
		<div class="postbox">
			<div class="inside" style="padding:15px;">
				<h2 style="font-size:24px; margin:0;"><?php _e('Preview');?></h2>
				<div id="preview">
				<?php
					year_preview_html();
					month_preview_html();
				?>
				</div>
				<div class="updated warning below-h2">
					<p class=""description">
					Please note that you have to add the calss "arw-theme1" to the .calendar-archives for the Theme 1 and "arw-theme2" for the Theme 2. Without it the theme will be applied to all widgets on the page.
					(e.g.: .calendar-archives.arw-theme1{} )
					</p>
				</div>
			</div>
		</div>
<?php
}

function archivesCalendar_themer_validate($args)
{
	foreach($args as $file => $css){
		arcw_write_css($file, $css);
	}

	$update_message = __('Updated.').'<script>var themer_tab = '. $_POST["tab"] .';</script>';
	add_settings_error( 'themer', 'ok', $update_message, 'updated' );
	return $args;
}

function arcw_write_css($file, $css) {
	global $wpdb;
	if ( $css ) {
		if ( isMU() ) {
			$old_blog = $wpdb->blogid;
			$blogids  = $wpdb->get_results( "SELECT blog_id FROM $wpdb->blogs" );
			foreach ( $blogids as $blogid ) {
				$blog_id = $blogid->blog_id;
				switch_to_blog( $blog_id );
				$filename = '../wp-content/plugins/' . dirname( plugin_basename( __FILE__ ) ) . '/themes/' . $file . '-' . $wpdb->blogid . '.css';
				$themefile = fopen( $filename, "w" ) or die( "Unable to open file!" );
				fwrite( $themefile, $css );
				fclose( $themefile );
			}
			switch_to_blog( $old_blog );
		} else {
			$filename = '../wp-content/plugins/' . dirname( plugin_basename( __FILE__ ) ) . '/themes/' . $file . '.css';
			$themefile = fopen( $filename, "w" ) or die( "Unable to open file!" );
			fwrite( $themefile, $css );
			fclose( $themefile );
		}
	}
}