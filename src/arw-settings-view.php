<style>
	.imadev {
		visibility: hidden;
		height: 0px;
		overflow: hidden;
		max-width: 100%;
	}

	.imadev:not(.visible) {
		margin: 0;
		padding: 0;
	}

	.imadev.visible {
		visibility: visible !important;
		height: auto !important;
		overflow: auto !important;
	}
</style>
<div id="post-body" class="metabox-holder columns-2">
	<div id="arcw-settings" class="tab active-tab">
		<div id="post-body-content">

			<table class="form-table">
				<tbody>
				<tr>
					<th scope="row">
						<label for="filter">
							<?php _e( 'Archives filter', 'arwloc' ); ?>
						</label>
					</th>
					<td>
						<label>
							<input type="checkbox" id="filter"
							       name="archivesCalendar[filter]" <?php arcw_checked( 'filter' ); ?> /> <?php _e( 'Enable', 'arwloc' ); ?>
						</label>
						<p class="description" id="tagline-description">
							<?php _e( 'This will display only the categories you have selected in the widget on the Archives page.', 'arwloc' ); ?>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="blogname">
							<?php _e( 'Default theme', 'arwloc' ); ?>
						</label>
					</th>
					<td>
						<?php
						arcw_themes_list( $theme, array(
							'name'         => 'archivesCalendar[theme]',
							'class'        => 'theme_select',
							'show_current' => true
						) );
						?>
						<a href="#TB_inline?height=420&amp;width=800&amp;inlineId=ac_preview"
						   class="thickbox button preview_theme">
							<?php _e( 'Preview' ); ?>
						</a>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="soptions">
							<?php _e( 'Settings link in admin menu', 'arwloc' ); ?>
						</label>
					</th>
					<td>
						<label>
							<input type="checkbox" id="soptions" name="archivesCalendar[show_settings]"
								<?php arcw_checked( 'show_settings' ); ?> /> <?php _e( 'Enable', 'arwloc' ); ?>
						</label>
						<p class="description" id="tagline-description">
							<?php _e( 'Show link "Archives Calendar" in admin "Settings" menu. If unchecked you can enter settings from "Settings" link in "Plugins" page.', 'arwloc' ); ?>
						</p>
					</td>
				</tr>
				</tbody>
			</table>

			<hr>
			<div>
				<label>
					<input type="checkbox" class="" id="imadev"/>
					<?php _e( "I'm a developer. I know how to edit a WordPress theme and how to add css/js file or code to a theme properly.", 'arwloc' ); ?>
				</label>
				<div class="imadev card">
					<p class="alert alert-warning">
						<?php _e( "Do not disable the following settings until you have included it manually into your WP theme.", 'arwloc' ); ?>
					</p>
					<table class="form-table">
						<tbody>
						<tr>
							<th scope="row">
								<label for="css">
									<?php _e( 'Enqueue ARCW theme CSS', 'arwloc' ); ?>
								</label>
							</th>
							<td>
								<label>
									<input type="checkbox" id="css"
									       name="archivesCalendar[css]" <?php arcw_checked( 'css' ); ?> />
									<?php _e( 'Enable', 'arwloc' ); ?>
								</label>
								<p class="description" id="tagline-description">
									<?php _e( 'Calendar theme css file will be included automatically in wp_head()', 'arwloc' ); ?>
									<br>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="plugin-init">
									<?php _e( 'Init jQuery ARCW plugin', 'arwloc' ); ?>
								</label>
							</th>
							<td>
								<label>
									<input type="checkbox" id="plugin-init"
									       name="archivesCalendar[plugin-init]" <?php arcw_checked( 'plugin-init' ); ?> />
									<?php _e( 'Enable', 'arwloc' ); ?>
								</label>
								<p class="description" id="tagline-description">
									<?php _e( 'jQuery ARCW plugin will be initialized automatically with default settings', 'arwloc' ); ?>
								</p>
								<hr>
								<p class="description" id="tagline-description">
									<?php _e( "If you want to customize the jQuery plugin you have to create your own JavasScript and include it into your theme.", 'arwloc' ); ?>
								</p>
								<code>
									wp_register_script( 'arcw-init', plugins_url( _YOUR_JS_FILE_PATH_ , __FILE__ ),
									array( "jquery-arcw" ));<br>
									wp_enqueue_script( 'arcw-init' );
								</code>
								<p class="description" id="tagline-description">
									<?php _e( "Do not forget to set 'jquery-arcw' as dependency to load the jquery plugin before initialisation code.", 'arwloc' ); ?>
								</p>

								<p>
									<a class="button-primary"
									   href="<?php echo plugins_url( 'archives-calendar-widget/admin/default.js.txt', dirname( __FILE__ ) ); ?>"
									   target="_blank">
										<?php _e( 'Display default JS', 'arwloc' ); ?>
									</a>
								</p>
							</td>
						</tr>
						</tbody>
					</table>

				</div>
			</div>

			<hr/>
			<div>
				<input name="Submit" type="submit" style="margin:20px 0;" class="button-primary"
				       value="<?php _e( 'Save Changes' ) ?>"/>
			</div>
			<?php
			require 'admin/preview.php';
			preview_block();
			?>
		</div>

		<div id="postbox-container-1" class="postbox-container">
			<div class="postbox">
				<div class="inside" style="padding:15px;">
					<?php sideBox(); ?>
				</div>
			</div>
		</div>
	</div>
</div>


