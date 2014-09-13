<?php
/*
Archives Calendar Widget SETTINGS
Author URI: http://alek.be
License: GPLv3
*/

/***** SETTINGS ******/
function archivesCalendar_admin_init()
{
	register_setting( 'archivesCalendar_options', 'archivesCalendar', 'archivesCalendar_options_validate' );
	add_settings_section('archivesCalendar_main', '', 'archivesCalendar_options', 'archivesCalendar_plugin');
}
add_action('admin_init', 'archivesCalendar_admin_init');

function ArchivesCalandarSettingsMenu()
{
	global $archivesCalendar_options;
	$arcw_page = add_options_page('Archives Calendar Settings', 'Archives Calendar', 'manage_options', 'archives_calendar', 'archives_calendar_settings');
	
	if($archivesCalendar_options['show_settings'] == 0)
		remove_submenu_page( 'options-general.php', 'archives_calendar' );
	//$arcw_page = add_submenu_page( 'options-general.php', 'Archives Calendar Settings', $menu_name, 'manage_options', 'archives_calendar', 'archives_calendar_settings' );
	add_action('admin_print_scripts-'.$arcw_page, 'arcw_admin_scripts');
}

function arcw_admin_scripts() {
	wp_enqueue_script( 'accordion' );
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_style( 'customize-controls');
	wp_enqueue_style( 'customize-widgets' );
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 
		'arcw-themer',
		plugins_url( '/admin/js/themer.js' , __FILE__ ),
		array( 'jquery' )
	);

	wp_register_style( 'acwr-themer-style', plugins_url('/admin/css/style.css', __FILE__) );
	wp_enqueue_style( 'acwr-themer-style' );
}

function archivesCalendar_options_validate($args)
{
	if(!isset($args['show_settings']))
		$args['show_settings'] = 0;
	else
		$args['show_settings'] = 1;

	if(!isset($args['css']))
		$args['css'] = 0;
	else 
		$args['css'] = 1;

	if(!isset($args['theme']))
		$args['theme'] = "default";

	if(!isset($args['jquery']))
		$args['jquery'] = 0;
	else $args['jquery'] = 1;

	if(!isset($args['js']))
		$args['js'] = 0;
	else
		$args['js'] = 1;

	if(!isset($args['shortcode']))
		$args['shortcode'] = 0;
	else
		$args['shortcode'] = 1;

	if(!isset($args['javascript']) || $args['javascript'] == "" )
		$args['javascript'] = "jQuery(document).ready(function($){\n\t$('.calendar-archives').archivesCW();\n});";

	return $args;
}

function archives_calendar_settings()
{?>
	<script type="text/javascript">
	jQuery(document).ready(function($){
		$(".nav-tab-wrapper a.nav-tab").click(function(e){
			e.preventDefault();
			if($(this).is('.nav-tab-active, .notab'))
				return;
			$(".nav-tab-wrapper").find('a.nav-tab-active').toggleClass('nav-tab-active');
			$(this).toggleClass('nav-tab-active');
			$('#post-body-content').find('.active-tab').hide().removeClass('active-tab');
			$($(this).attr('href')).show().addClass('active-tab');
			$("#ac_preview_css").remove();
			$("head").append('<link id="ac_preview_css" href="<?php echo plugins_url('', __FILE__); ?>/themes/custom.css" type="text/css" rel="stylesheet" />');
		});
	});
	</script>
	<style type="text/css">
		pre{font-size:11px; padding:10px; border:#CCC 1px solid; background:#f1f1f1; overflow:auto;}
		label{font-weight:bold;}
		.alek-links img{opacity: .7;}
		.alek-links:hover img{opacity: 1;}
		body{overflow: auto!important;}
	</style>
	<div class="wrap">
	<div class="icon32"><img src="<?php echo plugins_url('icon32.png', __FILE__);?>" /></div>
	<h2>Archives Calendar Widget</h2>
		<h2 class="nav-tab-wrapper">
			<a href="#arcw-settings" class="nav-tab nav-tab-active"><?php _e("Settings");?></a>
			<!--<a href="#arcw-themer" class="nav-tab notab"><?php _e('Customize');?><sup style="color:red">dev</sup></a>-->
		</h2>
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">
					<form method="post" action="options.php">
						<?php
						settings_fields('archivesCalendar_options');
						do_settings_sections('archivesCalendar_plugin');	
						?>
					</form>
				</div>
			</div>
		</div>
	<?php 
}
add_action('admin_menu', 'ArchivesCalandarSettingsMenu');

function archivesCalendar_options()
{
	global $wp_locale;
	$options = get_option('archivesCalendar');
	$theme = $options['theme'];
	add_thickbox();

	$themes = array(
		'default' => __('Default', 'arwloc'),
		'classiclight' => 'Classic',
		'twentytwelve' => 'Twenty Twelve',
		'twentythirteen' => 'Twenty Thirteen',
		'twentyfourteen' => 'Twenty Fourteen',
		'custom' => __('Custom', 'arwloc')
	);

?>
	<div class="tabs">
		<div id="arcw-settings" class="tab active-tab metabox-holder columns-2">	
			<div id="post-body-content">					
				<div id="ac_preview" style="display:none;">
					<p>
						<strong><?php _e('Theme');?>:</strong>

						<select id="themepreview">
							<?php
							foreach($themes as $key=>$value)
							{
								echo '<option '.selected( $key, $theme ).' value="'.$key.'">'.$value.'</option>';
							}
							?>
						</select> <button class="button-primary ok_theme"><?php _e('OK');?></button> <button class="button cancel_theme"><?php _e('Cancel', 'arwloc');?></button>
						<script type="application/javascript">
							jQuery(document).ready(function($) {
								$('.calendar-archives.preview a').on('click', function(e) {
									e.preventDefault();
								});
								$('#themepreview').change(function(){
									css = $(this).val() + '.css';
									$("#ac_preview_css").remove();
									$("head").append('<link id="ac_preview_css" href="<?php echo plugins_url('', __FILE__); ?>/themes/' + css + '" type="text/css" rel="stylesheet" />');
								});

								$('.button.preview_theme').on('click', function(){
									$('#themepreview option[value='+$('select.theme_select').val()+']').attr('selected', true);
									$("#ac_preview_css").remove();
									$("head").append('<link id="ac_preview_css" href="<?php echo plugins_url('', __FILE__); ?>/themes/' + $('select.theme_select').val() + '.css" type="text/css" rel="stylesheet" />');
								});
								$('.ok_theme').on('click', function(){
									tb_remove();
									$('select.theme_select option[value='+$('#themepreview').val()+']').attr('selected', true);
								});
								$('.cancel_theme').on('click', function(){
									tb_remove();
								});
							});
						</script>
					</p>
					<br>
					<div class="arcw preview zone" style="width:250px; float: left; margin-left:20px; padding-left:20px;">
						<div class="calendar-archives preview">
							<div class="cal-nav">
								<a href="#" class="prev-year"><span>&lt;</span></a>
								<div class="year-nav">
									<a href="#" class="year-title">2013</a>
									<div class="year-select" style="top: 0px;">
										<a href="#" class="year 2013 current selected" rel="0">2013</a>
										<a href="#" class="year 2012" rel="1">2012</a>
									</div>
									<div class="arrow-down" title="<?php _e( 'Select archives year', 'arwloc') ;?>">
										<span>▼</span>
									</div>
								</div>
								<a href="#" class="next-year disabled"><span>&gt;</span></a>
							</div>
							<?php 
							$aloc = 'archives_calendar';
							$mnames[0] = '';

							for($i=1; $i<13; $i++)
							{
								$monthdate = '1970-'. sprintf('%02d', $i) .'-01';		
								$mnames[$i] = $wp_locale->get_month_abbrev( $wp_locale->get_month(intval($i)) );
							}
								
							$years = array(2013 => array( 3 => 4, 6 => 3, 1 => 2 ), 2012 => array( 2 => 4, 7 => 3, 8 => 2 ));

							$cal= '<div class="archives-years">';
							$i = 0;
							
							
							foreach ($years as $year => $months){
								$current = ($i == 0) ? " current" : "";
								$lastyear = ($i == 1) ? " last" : "";
								$cal .= '<div class="year '.$year.$current.$lastyear.'" rel="'.$i.'">';
								for ( $month = 1; $month <= 12; $month++ ) {
									$last = ( $month%4 == 0 ) ? ' last' : '';
										if(isset($months[$month])) $count = $months[$month];
										else $count = '0';
										$posts_text = ($count == 1) ? __('Post', 'arwloc') : __('Posts', 'arwloc');

										$postcount = '<span class="postcount"><span class="count-number">'.$count.'</span> <span class="count-text">'.$posts_text.'</span></span>';
									
									if(isset($months[$month]))
										$cal .= '<div class="month'.$last.'"><a href="#"><span class="month-name">'.$mnames[$month].'</span>'.$postcount.'</a></div>';
									else
										$cal .= '<div class="month'.$last.' empty"><span class="month-name">'.$mnames[$month].'</span>'.$postcount.'</div>';
								}
								$cal .= "</div>\n";
								$i++;
							}

							$cal .= "</div>";
							echo $cal;
							?>
						</div>
					</div>
					<div class="arcw preview zone" style="width:250px; float: left; padding-left: 20px; padding-right: 20px ">
						<div class="calendar-archives" id="arc-Archives-39"><div class="cal-nav months"><a href="#" class="prev-year"><span>&lt;</span></a><div class="year-nav months"><a href="#" class="year-title">december 2011</a><div class="year-select" style="top: 0px; display: none;"><a href="#" class="year 2011 12 current selected" rel="0">december 2011</a><a href="#" class="year 2011 12" rel="0">october 2011</a><a href="#" class="year 2011 12" rel="0">june 2011</a></div><div class="arrow-down" title="Select archives year"><span>▼</span></div></div><a href="#" class="next-year disabled"><span>&gt;</span></a></div><div class="week-row"><span class="day weekday">mon</span><span class="day weekday">thu</span><span class="day weekday">wen</span><span class="day weekday">tue</span><span class="day weekday">fri</span><span class="day weekday">sat</span><span class="day weekday last">sun</span></div><div class="archives-years"><div class="year 12 2011 current" rel="0"><div class="week-row"><span class="day noday">&nbsp;</span><span class="day noday">&nbsp;</span><span class="day noday">&nbsp;</span><span class="month day empty">1</span><span class="month day empty">2</span><span class="month day empty">3</span><span class="month day empty last">4</span></div><div class="week-row"><span class="month day empty">5</span><span class="month day empty">6</span><span class="month day"><a href="#">7</a></span><span class="month day empty">8</span><span class="month day empty">9</span><span class="month day empty">10</span><span class="month day last"><a href="#">11</a></span></div><div class="week-row"><span class="month day empty">12</span><span class="month day empty">13</span><span class="month day"><a href="#">14</a></span><span class="month day"><a href="#">15</a></span><span class="month day empty">16</span><span class="month day empty">17</span><span class="month day last"><a href="#">18</a></span></div><div class="week-row"><span class="month day"><a href="#">19</a></span><span class="month day"><a href="#">20</a></span><span class="month day"><a href="#">21</a></span><span class="month day"><a href="#">22</a></span><span class="month day empty">23</span><span class="month day empty">24</span><span class="month day empty last">25</span></div><div class="week-row"><span class="month day empty">26</span><span class="month day empty">27</span><span class="month day empty">28</span><span class="month day empty">29</span><span class="month day empty">30</span><span class="month day empty">31</span><span class="day noday last">&nbsp;</span></div></div><div class="year 12 2010 last" rel="0"><div class="week-row"><span class="month day empty last">&nbsp;</span></div><div class="week-row"><span class="month day empty last">&nbsp;</span></div><div class="week-row"><span class="month day empty last">&nbsp;</span></div><div class="week-row"><span class="month day empty last">&nbsp;</span></div><div class="week-row"><span class="month day empty last">&nbsp;</span></div></div></div></div>
					</div>

					<script>
					jQuery(document).ready(function($){
						$('.calendar-archives').find('.arrow-down').on('click', function()
						{
							$(this).parent().children('.year-select').show();
						});

						$('.calendar-archives').find('.year-select')
						.mouseleave(function()
						{
							var menu = $(this);
							$('html').data('arctimer', setTimeout(
								function(){
									menu.parent().children('.year-select').hide();
								},
								300
							));
						})
						.mouseenter(function(){
							if($('html').data('arctimer'))
								clearTimeout($('html').data('timer'));
						});
					});
					</script>
					
					<div style="position: absolute; bottom:10px; text-align:center;">
						<span class="description"><?php _e("The theme's CSS file is not included in administration, this preview may be different from the website rendering.", 'arwloc'); ?></span>
					</div>
				</div>
				<p>
					<input type="checkbox" id="css" name="archivesCalendar[css]" <?php ac_checked('css');?> /> <label for="css"><?php _e('Include CSS file', 'arwloc'); ?></label><br />
					<span class="description"><?php _e( 'Include CSS file from the plugin.<br /><strong>It\'s recommended to copy the CSS code to your theme´s <strong>style.css</strong> and uncheck this option.', 'arwloc' ); ?></strong></span>
					<p><strong><?php _e('Theme');?>: </strong>
					<select name="archivesCalendar[theme]" class="theme_select">
						<?php
						foreach($themes as $key=>$value)
						{
							echo '<option '.selected( $key, $theme ).' value="'.$key.'">'.$value.'</option>';
						}
						?>
					</select> <a href="#TB_inline?width=350&height=400&inlineId=ac_preview" class="thickbox button preview_theme"><?php _e('Preview', 'arwloc');?></a><br />
					<?php _e( "<strong>NOTE:</strong> if you have modified any plugin's CSS file it will be restored on next plugin update.", 'arwloc' ); ?></span>
					</p>
				</p>
				<hr/>
				<p>
					<input type="checkbox" id="jquery" name="archivesCalendar[jquery]" <?php ac_checked('jquery');?> /> <label for="jquery"><?php _e('Include jQuery library', 'arwloc');?></label><br />
					<span class="description"><?php _e('Include jQuery library into your theme. Uncheck if your theme already includes jQuery library.<br /><strong>jQuery library is required.</strong>', 'arwloc');?></span>
				</p>
				<p>
					<input type="checkbox" id="js" name="archivesCalendar[js]" <?php ac_checked('js');?> /> <label for="js"><?php _e('Insert JavaScript code into <head>', 'arwloc');?></label><br />
					<span class="description"><?php _e('Insert javascript code into your theme\'s <head>. Uncheck only if you copy this code into your default .js file.', 'arwloc'); ?><br />
					<strong><?php _e('This code is required.', 'arwloc');?></strong></span>
					<div><textarea name="archivesCalendar[javascript]" style="width:500px; height:100px; font-family:'Courier', 'New Courier'; font-size: 12px;"><?php echo $options['javascript']; ?></textarea>
						<br>
						<?php _e('You can set some parameters to change the animation of the calendar.', 'arwloc'); ?>
					</div>
					<p>
						<a href="#TB_inline?width=350&height=500&inlineId=ac_default_code" class="thickbox button preview_theme"><?php _e('Show default parameters', 'arwloc');?></a>
					</p>

					<div id="ac_default_code" style="display:none;">
						<h2 class="title">$.archivesCW default parameters: <a class="button-primary" style="height: 20px; line-height: 16px;" href="<?php echo plugins_url( 'archives-calendar-widget/admin/default.js.txt' , dirname(__FILE__) ); ?>" target="_blank"><?php _e('Open'); ?> .txt</a></h2>
						<pre>
	<?php include 'admin/default.js.txt'; ?>
						</pre>
					</div>
				</p>
				<hr />
				<p>
					<input type="checkbox" id="shortcode" name="archivesCalendar[shortcode]" <?php ac_checked('shortcode');?> /> <label for="shortcode">
						<?php _e('Enable Shortcode support in text widget', 'arwloc');?></label><br />
					<span class="description"><?php _e('Use the shortcode in a text widget to display Archives Calendar.', 'arwloc');?></span>
					<pre>[arcalendar next_text=">" prev_text="<" post_count="true" month_view="false"]</pre>
				</p>
				<hr />
				<p>
					<input type="checkbox" id="soptions" name="archivesCalendar[show_settings]" <?php ac_checked('show_settings');?> /> <label for="soptions">
						<?php _e('Show link to Settings in admin menu', 'arwloc');?></label><br />
					<span class="description"><?php _e('Show link "Archives Calendar" in admin "Settings" menu. If unchecked you can enter settings from "Settings" link in "Plugins" page.', 'arwloc');?></span>
				</p>

				<hr />
				<p>
					<input name="Submit" type="submit" style="margin:20px 0;" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>

			</div>

			<div id="postbox-container-1" class="postbox-container">					
					<div class="postbox">
						<div class="inside" style="padding:15px;">
							<?php 
							$feed_url = 'http://labs.alek.be/category/archives-calendar/feed/';
							if (!$fp = curl_init($feed_url)){
								$feed = (array) simplexml_load_file($feed_url);
								$items = $feed['channel']->item;
								$count = count($items);
							}
							else $count = 0;
							
							if($count > 0):
							?>
							<h2 style="font-size:24px; margin:0;"><?php _e('News/Updates', 'arwloc');?> <span class="description">RSS feed</span></h2>
							<p>
							<?php
								$i=0;									
								foreach($items as $item){
									if($i < 3){
										$date = strtotime($item->pubDate);
										echo '<p style="overflow:hidden; margin-bottom:0; margin-top:4px;">';
										echo '<label class="date" style="display:block; width: 20%; float:left;"><strong>'.date('d/m', $date).'</strong></label>';
										echo '<a style="display:block; width: 80%; float:right;" href="'.$item->guid.'" class="title" target="_blank">'.$item->title.'</a>';
										echo '</p>';
									}
									$i++;
								}	
								if($count > 3){
									$cat_url = 'http://labs.alek.be/category/archives-calendar/';
									echo '<p style="margin-bottom:0; margin-top:6px;">';
									echo '<strong><a href="'. $cat_url .'" class="title" target="_blank">'. __('More') .' ...</a></strong>';
									echo '</p>';	
								}
								if($count <= 0) _e( 'No posts', 'arwloc' );
							?>
							</p>
							<?php
							endif;
							?>
							<h2 style="font-size:24px; margin:0;"><?php _e('More');?></h2>
							<hr>
							<p style="text-align: center">
								<a href="https://github.com/alekart?tab=repositories" target="_blank" class="alek-links"><img src="<?php echo plugins_url('', __FILE__); ?>/admin/images/GitHub.png" alt="alek on GitHub" title="My projects on Github" /></a> 
								<a href="http://profiles.wordpress.org/alekart/" target="_blank" class="alek-links"><img src="<?php echo plugins_url('', __FILE__); ?>/admin/images/wordpress.png" alt="alek´ on WordPress" title="My WordPress projects" /></a>
								<a href="http://labs.alek.be/" target="_blank" class="alek-links"><img src="<?php echo plugins_url('', __FILE__); ?>/admin/images/alabs.png" alt="My blog" /></a>
								<a href="http://alek.be/" target="_blank" class="alek-links"><img src="<?php echo plugins_url('', __FILE__); ?>/admin/images/alek.png" alt="alek´ portfolio" alt="My portfolio" /></a>
							</p>
							<hr>
							<p>
								<?php _e('If you like this plugin please <strong>support my work</strong>, buy me <strong>a beer or a coffee</strong>. Click Donate and specify your amount.', 'arwloc');?>
							</p>
							<p style="text-align:center">
								<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4K6STJNLKBTMU" target="_blank"><img src="https://www.paypalobjects.com/en_US/BE/i/btn/btn_donateCC_LG.gif" alt="Donate" /></a><br>
								<span class="description" style="font-size:10px;"><?php _e('In Belgium 1 coffee or 1 beer costs about 2€', 'arwloc');?></span>
							</p>
						</div>
					</div>
			</div>
		</div>
		<div id="arcw-themer" class="tab metabox-holder columns-2" style="display: none;">
			<?php //include 'arw-editor.php'; ?>				
		</div>
	</div>
<?php
}