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

	private $plugin_options_key = 'Archives_Calendar_Widget';
	private $general_settings_key = 'settings';
	private $advanced_settings_key = 'themer';
	private $tabs = array();

	function __construct() {
		add_action( 'admin_init', array( &$this, 'register_general_settings' ) );
		add_action( 'admin_init', array( &$this, 'register_advanced_settings' ) );
		add_action( 'admin_menu', array( &$this, 'add_admin_menus' ) );
	}

	function register_general_settings() {
		$this->tabs[$this->general_settings_key] = __('Settings', 'archives-calendar-widget');

		register_setting( $this->general_settings_key, 'archivesCalendar', 'archivesCalendar_options_validate' );
		add_settings_section( 'section_general', '', 'archivesCalendar_options', $this->general_settings_key );
	}

	function register_advanced_settings() {
		$this->tabs[$this->advanced_settings_key] = __('Customize', 'archives-calendar-widget');

		register_setting( $this->advanced_settings_key, 'archivesCalendarThemer', 'archivesCalendar_themer_validate' );
		add_settings_section( 'section_themer', '', 'archivesCalendar_themer', $this->advanced_settings_key );
	}

	function add_admin_menus() {
		global $archivesCalendar_options;
		$arcw_page = add_options_page('Archives Calendar Settings', 'Archives Calendar', 'manage_options', $this->plugin_options_key, array( &$this, 'archives_calendar_options_page' ));
		remove_submenu_page( 'options-general.php', 'archives_calendar_editor' );
		if($archivesCalendar_options['show_settings'] == 0)
			remove_submenu_page( 'options-general.php', 'Archives_Calendar_Widget' );

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

add_action( 'plugins_loaded', function(){
	$settings_api_tabs_demo_plugin = new Archives_Calendar_Widget_Settings;
});

function arcw_admin_scripts() {

    wp_enqueue_script(
        'arcw-admin',
        plugins_url( '/admin/js/admin.js' , __FILE__ ),
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
			plugins_url( '/admin/js/themer.js' , __FILE__ ),
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

	if(!isset($args['plugin-init']))
		$args['plugin-init'] = 0;
	else
		$args['plugin-init'] = 1;

	if(!isset($args['filter']))
		$args['filter'] = 0;
	else
		$args['filter'] = 1;

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
    <?php include 'arw-settings-view.php'; ?>
<?php
}

function sideBox() {
    ?>
    <p class="alek-links">
        <a href="https://github.com/alekart?tab=repositories" target="_blank" title="alek on GitHub">
					<svg class="alek-links__github-logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 33.14 32.5"><path fill="currentColor" d="M16.85 0a16.29 16.29 0 00-5.14 31.75c.81.15 1.11-.36 1.11-.79v-2.77C8.26 29.18 7.31 26 7.31 26a4.36 4.36 0 00-1.81-2.38c-1.48-1 .11-1 .11-1a3.44 3.44 0 012.5 1.68 3.48 3.48 0 004.74 1.36 3.51 3.51 0 011-2.18c-3.62-.41-7.42-1.81-7.42-8a6.3 6.3 0 011.68-4.37 5.83 5.83 0 01.19-4.35s1.37-.44 4.48 1.67a15.37 15.37 0 018.15 0c3.07-2.11 4.48-1.67 4.48-1.67a5.83 5.83 0 01.16 4.31 6.26 6.26 0 011.68 4.37c0 6.26-3.81 7.63-7.44 8a3.88 3.88 0 011.1 3v4.47c0 .53.3.94 1.12.78A16.29 16.29 0 0016.85 0z" fill-rule="evenodd"/></svg>
				</a>
        <a href="http://profiles.wordpress.org/alekart/" target="_blank" title="alek on WordPress">
					<svg class="alek-links__wordpress-logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 49.3 49.3"><path fill="currentColor" d="M3.5 24.6c0 8.4 4.9 15.6 11.9 19L5.3 16c-1.1 2.7-1.8 5.6-1.8 8.6zm35.4-1c0-2.6-.9-4.4-1.7-5.8-1.1-1.7-2.1-3.2-2.1-5 0-1.9 1.5-3.8 3.5-3.8h.3c-3.8-3.4-8.8-5.5-14.3-5.5C17.3 3.5 10.8 7.3 7 13h1.4c2.2 0 5.6-.3 5.6-.3 1.1-.1 1.3 1.6.1 1.7 0 0-1.1.1-2.4.2l7.7 22.9L24 23.8l-3.3-9-2.2-.2c-1.1-.1-1-1.8.1-1.7 0 0 3.5.3 5.6.3 2.2 0 5.6-.3 5.6-.3 1.1-.1 1.3 1.6.1 1.7 0 0-1.1.1-2.4.2l7.6 22.7 2.1-7c1-3 1.7-5.1 1.7-6.9z"/><path fill="currentColor" d="M25 26.5l-6.3 18.4c1.9.6 3.9.9 6 .9 2.5 0 4.8-.4 7-1.2-.1-.1-.1-.2-.2-.3L25 26.5zm18.2-12c.1.7.1 1.4.1 2.2 0 2.1-.4 4.6-1.6 7.6L35.2 43c6.3-3.7 10.5-10.5 10.5-18.3.1-3.7-.9-7.2-2.5-10.2z"/><path d="M24.6 0C11.1 0 0 11.1 0 24.6c0 13.6 11.1 24.6 24.6 24.6 13.6 0 24.6-11.1 24.6-24.6C49.3 11.1 38.2 0 24.6 0zm0 48.2c-13 0-23.5-10.5-23.5-23.5S11.6 1.2 24.6 1.2s23.5 10.5 23.5 23.5c.1 12.9-10.5 23.5-23.5 23.5z"/></svg>
				</a>
        <a href="http://alek.be/" target="_blank" title="alek's website">
					<svg class="alek-links__alek-logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50.98 51.02"><path fill="currentColor" d="M34.36 42.3zM26.26 0C9-.48-4.5 16.12 1.41 34.11A24.09 24.09 0 0016.3 49.4a26 26 0 0020.41-1 .25.25 0 00.13-.32 17.47 17.47 0 01-2.44-5.74c-.13-.55-.49-2.19-.49-2.19a2.54 2.54 0 00-3-1.92 2.4 2.4 0 00-.38.12 14.19 14.19 0 01-10.26-.06 12.64 12.64 0 01-7.33-7.09 13.8 13.8 0 1126.38-5.35v12.42A12.63 12.63 0 0050.67 51a.29.29 0 00.31-.27V26.05C51 12.18 40.13.41 26.26 0z"/></svg>
				</a>
    </p>
    <hr>
    <p>
        <?php _e('If you like this plugin please <strong>support my work</strong>, pay me <strong>a beer or a coffee</strong>.<br> Click Donate and specify your amount.', 'archives-calendar-widget');?>
    </p>
    <p style="text-align:center">
        <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4K6STJNLKBTMU" target="_blank"><img src="https://www.paypalobjects.com/en_US/BE/i/btn/btn_donateCC_LG.gif" alt="Donate" /></a><br>
        <span class="description" style="font-size:10px;"><?php _e('In Belgium 1 coffee or 1 beer costs about 2€', 'archives-calendar-widget');?></span>
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
				echo '<option ' . selected( $filename, $selected ) . ' value="' . $filename . '">' . __( 'Custom', 'archives-calendar-widget' ) . ' ' . $i . '</option>';
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
