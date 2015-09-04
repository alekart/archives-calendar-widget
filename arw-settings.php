<?php
/*
Archives Calendar Widget SETTINGS
Author URI: http://alek.be
License: GPLv3
*/
$archivesCalendar_options = get_option('archivesCalendar');
require 'arw-editor.php';

/***** SETTINGS ******/

class Archives_Calendar_Widget_Settings {

	private $plugin_options_key = 'Arrchives_Calendar_Widget';
	private $general_settings_key = 'settings';
	private $advanced_settings_key = 'themer';
	private $tabs = array();

	function __construct() {
		add_action( 'admin_init', array( &$this, 'register_general_settings' ) );
		add_action( 'admin_init', array( &$this, 'register_advanced_settings' ) );
		add_action( 'admin_menu', array( &$this, 'add_admin_menus' ) );
	}

	function register_general_settings() {
		$this->tabs[$this->general_settings_key] = __('Settings');

		register_setting( $this->general_settings_key, 'archivesCalendar', 'archivesCalendar_options_validate' );
		add_settings_section( 'section_general', '', 'archivesCalendar_options', $this->general_settings_key );
	}

	function register_advanced_settings() {
		$this->tabs[$this->advanced_settings_key] = __('Customize');

		register_setting( $this->advanced_settings_key, 'archivesCalendarThemer', 'archivesCalendar_themer_validate' );
		add_settings_section( 'section_themer', '', 'archivesCalendar_themer', $this->advanced_settings_key );
	}

	function add_admin_menus() {
		global $archivesCalendar_options;
		$arcw_page = add_options_page('Archives Calendar Settings', 'Archives Calendar', 'manage_options', $this->plugin_options_key, array( &$this, 'archives_calendar_options_page' ));
		remove_submenu_page( 'options-general.php', 'archives_calendar_editor' );
		if($archivesCalendar_options['show_settings'] == 0)
			remove_submenu_page( 'options-general.php', 'archives_calendar' );

		add_action('admin_print_scripts-'.$arcw_page, 'arcw_admin_scripts');
	}

	function archives_calendar_options_page() {
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->general_settings_key;
		?>
		<div class="wrap">
			<h2>Archives Calendar Widget</h2>
			<?php $this->tabs(); ?>
            <form method="post" action="options.php">
                <div id="poststuff">
                    <?php
                        wp_nonce_field( 'update-options' );
                        settings_fields( $tab );
                        do_settings_sections( $tab );
                    ?>
                </div>
            </form>
		</div>
	<?php
	}
	function tabs() {
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->general_settings_key;

		echo '<h2 class="nav-tab-wrapper">';
		foreach ( $this->tabs as $tab_key => $tab_caption ) {
			$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->plugin_options_key . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
		}
		echo '</h2>';
	}
};
add_action( 'plugins_loaded', create_function( '', '$settings_api_tabs_demo_plugin = new Archives_Calendar_Widget_Settings;' ) );

function arcw_admin_scripts() {

    wp_enqueue_script(
        'arcw-admin',
        plugins_url( '/admin/js/admin.min.js' , __FILE__ ),
        array( 'jquery' ),
        ARCWV
    );
    wp_register_style( 'acwr-themer-style', plugins_url('/admin/css/style.css', __FILE__), array(), ARCWV );
    wp_enqueue_style( 'acwr-themer-style' );

	if(isset($_GET['tab']) && $_GET['tab'] == 'themer') {
		wp_enqueue_script(
            'arcw-aceedit',
            plugins_url( '/admin/js/lib/ace-edit/ace.js' , __FILE__ ),
            array( 'jquery' ),
            ARCWV
        );

		wp_enqueue_script(
			'arcw-themer',
			plugins_url( '/admin/js/themer.min.js' , __FILE__ ),
			array( 'jquery' ),
			ARCWV
		);
    }
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

	if(!isset($args['filter']))
		$args['filter'] = 0;
	else
		$args['filter'] = 1;

	if(!isset($args['javascript']) || $args['javascript'] == "" )
		$args['javascript'] = "jQuery(document).ready(function($){\n\t$('.calendar-archives').archivesCW();\n});";

	return $args;
}

function archivesCalendar_options()
{
	global $archivesCalendar_options;
	$options = $archivesCalendar_options;
	$theme = $options['theme'];
	add_thickbox();
?>
	<script type="text/javascript">
		ARCWPATH = '<?php echo plugins_url('', __FILE__); ?>';
	</script>
    <div id="post-body" class="metabox-holder columns-2">
		<div id="arcw-settings" class="tab active-tab">
			<div id="post-body-content">
				<p>
					<input type="checkbox" id="filter" name="archivesCalendar[filter]" <?php arcw_checked('filter');?> /> <label for="filter">
						<?php _e('Enable archives filter', 'arwloc');?></label><br />
                    <span class="description">
                        <?php _e('This will display only the categories you have selected in the widget on the Archives page.', 'arwloc');?>
                    </span>
				</p>
				<hr />
				<div>
					<input type="checkbox" id="css" name="archivesCalendar[css]" <?php arcw_checked('css');?> /> <label for="css"><?php _e('Include CSS file', 'arwloc'); ?></label><br />
					<span class="description"><?php _e( 'Include CSS file from the plugin.<br /><strong>It\'s recommended to copy the CSS code to your theme´s <strong>style.css</strong> and uncheck this option.', 'arwloc' ); ?></strong></span>
					<p><strong><?php _e('Theme');?>: </strong>
	                    <?php
	                    arcw_themes_list($theme, array('name' => 'archivesCalendar[theme]', 'class' => 'theme_select', 'show_current' => true) );
	                    ?>
						 <a href="#TB_inline?height=420&amp;width=800&amp;inlineId=ac_preview" class="thickbox button preview_theme"><?php _e('Preview');?></a><br />
						<?php _e( "<strong>NOTE:</strong> if you have modified any plugin's CSS file it will be restored on next plugin update.", 'arwloc' ); ?></span>
					</p>
				</div>
				<hr/>
				<div>
					<input type="checkbox" id="js" name="archivesCalendar[js]" <?php arcw_checked('js');?> /> <label for="js"><?php _e('Insert JavaScript code into &lt;head&gt;', 'arwloc');?></label><br />
					<span class="description"><?php _e('Insert javascript code into your theme\'s &lt;head&gt;. Uncheck only if you copy this code into your default .js file.', 'arwloc'); ?><br />
					<strong><?php _e('This code is required.', 'arwloc');?></strong></span>
					<p>
						<textarea name="archivesCalendar[javascript]" style="width:500px; height:100px; font-family:'Courier', 'New Courier'; font-size: 12px;"><?php echo $options['javascript']; ?></textarea>
						<br>
						<?php _e('You can set some parameters to change the animation of the calendar.', 'arwloc'); ?>
					</p>
					<p>
						<a href="#TB_inline?width=350&height=500&inlineId=ac_default_code" class="thickbox button preview_theme"><?php _e('Show default parameters', 'arwloc');?></a>
					</p>

					<div id="ac_default_code" style="display:none;">
						<h2 class="title">$.archivesCW default parameters: <a class="button-primary" style="height: 20px; line-height: 16px;" href="<?php echo plugins_url( 'archives-calendar-widget/admin/default.js.txt' , dirname(__FILE__) ); ?>" target="_blank"><?php _e('Open', 'arwloc'); ?> .txt</a></h2>
						<pre>
	<?php include 'admin/default.js.txt'; ?>
						</pre>
					</div>
				</div>
				<hr />
				<p>
					<input type="checkbox" id="soptions" name="archivesCalendar[show_settings]" <?php arcw_checked('show_settings');?> /> <label for="soptions">
						<?php _e('Show link to Settings in admin menu', 'arwloc');?></label><br />
					<span class="description"><?php _e('Show link "Archives Calendar" in admin "Settings" menu. If unchecked you can enter settings from "Settings" link in "Plugins" page.', 'arwloc');?></span>
				</p>

				<hr />
				<p>
					<input name="Submit" type="submit" style="margin:20px 0;" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
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
<?php
}

function sideBox() {
    $feed_url = 'http://labs.alek.be/category/archives-calendar/feed/';
    $feed = (array)simplexml_load_file($feed_url) ? (array)simplexml_load_file($feed_url) : null;
    $items = $feed ? $feed['channel']->item : array();
    $count = count($items);

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
<?php
}

/* theme list select */
function arcw_themes_list($selected = 0, $args)
{
	global $wpdb, $themes, $archivesCalendar_options;

	$defaults = array(
		'name' => null,
		'id' => null,
		'class' => null,
		'show_current' => false
	);
	$args = wp_parse_args( (array) $args, $defaults );
	extract($args);
	echo '<select';
	if($name)
		echo ' name="'.$name.'"';
	if($id)
		echo ' id="'.$id.'"';
	if($class)
		echo ' class="'.$class.'"';
	echo '>';

	foreach($themes as $key=>$value)
	{
		if($archivesCalendar_options['theme'] != $key || $show_current)
			echo '<option '.selected( $key, $selected ).' value="'.$key.'">'.$value.'</option>';
	}

	if( $custom = get_option( 'archivesCalendarThemer' )) {
		$i = 1;
		foreach ( $custom as $filename => $css ) {
			if ( $css ) {
				echo '<option ' . selected( $filename, $selected ) . ' value="' . $filename . '">' . __( 'Custom', 'arwloc' ) . ' ' . $i . '</option>';
				$i ++;
			}
		}
	}

	echo '</select>';
}

/***** Walker for categories checkboxes *****/
class acw_Walker_Category_Checklist extends Walker
{
    var $tree_type = 'category';
    var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

    var $conf;
    function __construct($conf)
    {
        $this->conf = $conf;
    }

    function start_lvl( &$output, $depth = 0, $args = array() )
    {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent<ul class='children' style='margin-left: 18px;'>\n";
    }

    function end_lvl( &$output, $depth = 0, $args = array() )
    {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 )
    {
        extract($args);
        $conf = $this->conf;

        if ( empty($taxonomy) )
            $taxonomy = 'category';

        if ( $taxonomy == 'category' )
            $name = 'post_category';
        else
            $name = 'tax_input['.$taxonomy.']';

        $class = in_array( $category->term_id, $popular_cats ) ? ' class="popular-category"' : '';

        /** This filter is documented in wp-includes/category-template.php */
        $output .= "\n<li id='{$taxonomy}-{$category->term_id}'$class>" . '<label class="selectit"><input value="' . $category->term_id . '"
		type="checkbox"
		id="'.$conf['field_id'].'-'.$category->slug.'"
		name="'.$conf['field_name'].'['.$category->term_id.']"' . checked( in_array( $category->term_id, $selected_cats ), true, false ) . disabled( empty( $args['disabled'] ), false, false ) . ' /> ' . esc_html( apply_filters( 'the_category', $category->name ) ) . '</label>';
    }

    function end_el( &$output, $category, $depth = 0, $args = array() )
    {
        $output .= "</li>\n";
    }
}

/***** CHECKBOXES CHECK *****/
function arcw_checked($option, $value = 1)
{
	$options = get_option('archivesCalendar');
	if($options[$option] == $value)
		echo 'checked="checked"';
}