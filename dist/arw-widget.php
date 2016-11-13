<?php
/*
Archives Calendar Widget
Author URI: http://alek.be
License: GPLv3
*/

/***** WIDGET CLASS *****/
class Archives_Calendar extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'archives_calendar',
			'Archives Calendar',
			array( 'description' => __( 'Show archives as calendar', 'arwloc' ), )
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}
		$instance['function'] = 'no';
		echo archive_calendar( $instance );
		echo $after_widget;
	}


	public function form( $instance ) {
		$defaults = array(
			'title'              => __( 'Archives' ),
			'next_text'          => '>',
			'prev_text'          => '<',
			'post_count'         => 1,
			'month_view'         => 0,
			'month_select'       => 'default',
			'disable_title_link' => 0,
			'different_theme'    => 0,
			'theme'              => null,
			'categories'         => null,
			'post_type'          => array('post'),
			'show_today'         => 0
		);
		$instance = wp_parse_args( $instance, $defaults );

		$title              = $instance['title'];
		$prev               = $instance['prev_text'];
		$next               = $instance['next_text'];
		$count              = $instance['post_count'];
		$month_view         = $instance['month_view'];
		$month_select       = $instance['month_select'];
		$disable_title_link = $instance['disable_title_link'];
		$different_theme    = $instance['different_theme'];
		$arw_theme          = $instance['theme'];
		$cats               = $instance['categories'];
		$post_type          = $instance['post_type'];


		if ( is_array( $post_type ) && empty( $post_type ) || ( ! $post_type || $post_type == '' ) ) {
			$post_type = array( 'post' );
		}

		// Widget Settings form is in external file
		include 'arw-widget-settings.php';
	}

	public function update( $new_instance, $old_instance ) {
		$instance              = $old_instance;
		$instance['title']     = strip_tags( $new_instance['title'] );
		$instance['next_text'] = htmlspecialchars( $new_instance['next_text'] );
		$instance['prev_text'] = htmlspecialchars( $new_instance['prev_text'] );

		if ( $instance['next_text'] == htmlspecialchars( '>' ) ) {
			$instance['prev_text'] = htmlspecialchars( '<' );
		}

		$instance['post_count']         = ( $new_instance['post_count'] ) ? $new_instance['post_count'] : 0;
		$instance['month_view']         = $new_instance['month_view'];
		$instance['month_select']       = $new_instance['month_select'];
		$instance['disable_title_link'] = ( $new_instance['disable_title_link'] ) ? $new_instance['disable_title_link'] : 0;
		$instance['different_theme']    = ( $new_instance['different_theme'] ) ? $new_instance['different_theme'] : 0;
		$instance['theme']              = $new_instance['theme'];
		$instance['categories']         = $new_instance['categories'];
		$instance['post_type']          = ( $new_instance['post_type'] ) ? $new_instance['post_type'] : array('post');

		return $instance;
	}
}

// Register and load the widget
function archives_calendar_load_widget() {
	register_widget( 'Archives_Calendar' );
}

add_action( 'widgets_init', 'archives_calendar_load_widget' );

/***** WIDGET CONSTRUCTION FUNCTION *****/
/* can be called directly archive_calendar($args) */
function archive_calendar( $args = array() ) {
	global $archivesCalendar_options;

	$defaults = array(
		'title'              => '',
		'next_text'          => '>',
		'prev_text'          => '<',
		'post_count'         => 1,
		'month_view'         => 0,
		'month_select'       => 'default',
		'disable_title_link' => 0,
		'different_theme'    => 0,
		'theme'              => null,
		'categories'         => null,
		'post_type'          => array('post'),
		'show_today'         => 0
	);
	$args     = wp_parse_args( $args, $defaults );

	if ( ! $args['different_theme'] ) {
		$args['theme'] = $archivesCalendar_options['theme'];
	}

	if ( $args['theme'] != $archivesCalendar_options['theme'] ) {
		wp_register_style( 'archives-cal-' . $args['theme'], plugins_url( 'themes/' . $args['theme'] . '.css', __FILE__ ), array(), ARCWV );
		wp_enqueue_style( 'archives-cal-' . $args['theme'] );
	}

	if ( is_array( $args['post_type'] ) && count( $args['post_type'] ) > 0 ) {
		$args['post_type'] = implode( ",", $args['post_type'] );
	}

	$cal = archives_view( $args );

	if ( isset( $function ) && $function == "no" ) {
		return $cal;
	}
	echo $cal;
}

function archives_view( $args ) {
	global $wpdb;
	extract( $args );

	if ( ! empty( $categories ) && is_array( $categories ) ) {
		$args['cats'] = implode( ', ', $categories );
	} else {
		$args['cats'] = "";
	}

	$sql = "SELECT DISTINCT YEAR(post_date) AS year, MONTH(post_date) AS month
		FROM $wpdb->posts wpposts ";

	if ( count( $categories ) ) {
		$sql .= "JOIN $wpdb->term_relationships tr ON wpposts.ID = tr.object_id
				 JOIN $wpdb->term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id ";
		$sql .= "AND tt.term_id IN(" . $args['cats'] . ") ";
		$sql .= "AND tt.taxonomy = 'category') ";
	}

	$sql .= "WHERE post_type IN ('" . implode( "','", explode( ',', $post_type ) ) . "')
			AND post_status IN ('publish')
			AND post_password=''
			ORDER BY year DESC, month DESC";

	$cal = ( $args['month_view'] == false ) ? archives_year_view( $args, $sql ) : archives_month_view( $args, $sql );
	$cal .= '<!-- END - Archives Calendar Widget by Aleksei Polechin - alek´ - http://alek.be -->';

	return $cal;
}

/***** YEAR DISPLAY MODE *****/
function archives_year_view( $args, $sql ) {
	global $wpdb, $wp_locale, $post;
	extract( $args );

	$results = $wpdb->get_results( $sql );

	$years = array();
	foreach ( $results as $date ) {
		if ( $post_count ) // if set to show post count
		{
			$sql = "SELECT COUNT(DISTINCT(ID)) AS count FROM $wpdb->posts wpposts ";

			if ( count( $categories ) ) {
				$sql .= "JOIN $wpdb->term_relationships tr ON ( wpposts.ID = tr.object_id )
					JOIN $wpdb->term_taxonomy tt ON ( tr.term_taxonomy_id = tt.term_taxonomy_id
					AND tt.term_taxonomy_id IN(" . $cats . ") ) ";
			}
			$sql .= "WHERE post_type IN ('" . implode( "','", explode( ',', $post_type ) ) . "')
					AND post_status IN ('publish')
					AND post_password=''
					AND YEAR(post_date) = $date->year
					AND MONTH(post_date) = $date->month";

			$postcount = $wpdb->get_results( $sql );
			$count     = $postcount[0]->count;
		} else {
			$count = 0;
		}
		$years[ $date->year ][ $date->month ] = $count;
	}

	$totalyears = count( $years );

	if ( $totalyears == 0 ) {
		$totalyears           = 1;
		$years[ date( 'Y' ) ] = array();
	}

	$yearNb = array();
	foreach ( $years as $year => $months ) {
		$yearNb[] = $year;
	}

	if ( is_archive() ) {
		$archiveYear = date( 'Y', strtotime( $post->post_date ) ); // year to be visible

		if ( ! array_key_exists( $archiveYear, $years ) ) {
			$archiveYear = $yearNb[0];
		}
	} else {
		$archiveYear = $yearNb[0];
	} // if no current year -> show the more recent

	$cal = get_calendar_header( $view = 'years', $yearNb, '', $archiveYear, $args );
	$cal .= '<div class="archives-years">';

	$i = 0;

	foreach ( $years as $year => $months ) {
		$lastyear = ( $i == $totalyears - 1 ) ? " last" : "";
		$current  = ( $archiveYear == $year ) ? " current" : "";

		$cal .= '<div class="year ' . $year . $lastyear . $current . '" rel="' . $i . '">';
		for ( $month = 1; $month <= 12; $month ++ ) {
			$last = ( $month % 4 == 0 ) ? ' last' : '';
			if ( $post_count ) {
				if ( isset( $months[ $month ] ) ) {
					$count = $months[ $month ];
				} else {
					$count = 0;
				}
				$posts_text = ( $count == 1 ) ? __( 'Post', 'arwloc' ) : __( 'Posts', 'arwloc' );

				$postcount = '<span class="postcount"><span class="count-number">' . $count . '</span> <span class="count-text">' . $posts_text . '</span></span>';
			} else {
				$postcount = "";
			}
			if ( isset( $months[ $month ] ) ) {
				$href  = make_arcw_link( get_month_link( $year, $month ), $post_type, $cats );
				$title = $post_count
					? sprintf( '%s - %s %s', date_i18n( 'F, Y', strtotime( $year . '-' . $month ) ), $count, $posts_text )
					: date_i18n( 'F, Y', strtotime( $year . '-' . $month ) );
				$cal .= '<div class="month' . $last . ' has-posts">'
				        . '<a href="' . $href . '" title="' . $title . '" data-date="' . $year . '-' . $month . '">'
				        . '<span class="month-name">' . $wp_locale->get_month_abbrev( $wp_locale->get_month( $month ) ) . '</span>' . $postcount
				        . '</a></div>';
			} else {
				$cal .= '<div class="month' . $last . '"><span class="month-name">' . $wp_locale->get_month_abbrev( $wp_locale->get_month( $month ) ) . '</span>' . $postcount . '</div>';
			}
		}
		$cal .= "</div>\n";
		$i ++;
	}
	$cal .= "</div></div>";

	return $cal;
}

/***** MONTH DISPLAY MODE *****/
function archives_month_view( $args, $sql ) {
	global $wpdb, $wp_locale, $post;
	extract( $args );

	// Select all months where are posts
	$months = $wpdb->get_results( $sql );

	if ( count( $months ) == 0 ) {
		$month_select = 'empty';
	}

	// Get current time of the website (timezone)
	$today = current_time( 'timestamp' );

	$todayDay   = intval( date( 'd', $today ) );
	$todayMonth = intval( date( 'm', $today ) );
	$todayYear  = intval( date( 'Y', $today ) );

	if ( is_archive() ) {
		$archiveYear  = intval( date( 'Y', strtotime( $post->post_date ) ) );
		$archiveMonth = intval( date( 'm', strtotime( $post->post_date ) ) );

		if ( is_category() ) {
			$archiveYear  = intval( date( 'Y' ) );
			$archiveMonth = intval( date( 'm' ) );
		}
	} else {
		$archiveYear  = intval( date( 'Y' ) );
		$archiveMonth = intval( date( 'm' ) );
	}

	switch ( $month_select ) {
		case 'prev':
			if ( $archiveMonth == 1 ) {
				$archiveMonth = 12;
				$archiveYear --;
			} else {
				$archiveMonth --;
			}
			if ( arcw_findMonth( $archiveYear, $archiveMonth, $months ) < 0 ) {
				$months[] = (object) array( 'year' => $archiveYear, 'month' => $archiveMonth );
				arcw_sortMonths( $months );
			}
			break;
		case 'current':
			if ( arcw_findMonth( $archiveYear, $archiveMonth, $months ) < 0 ) {
				$months[] = (object) array( 'year' => $archiveYear, 'month' => $archiveMonth );
				arcw_sortMonths( $months );
			}
			break;
		case 'next':
			if ( $archiveMonth == 12 ) {
				$archiveMonth = 1;
				$archiveYear ++;
			} else {
				$archiveMonth ++;
			}
			if ( arcw_findMonth( $archiveYear, $archiveMonth, $months ) < 0 ) {
				$months[] = (object) array( 'year' => $archiveYear, 'month' => $archiveMonth );
				arcw_sortMonths( $months );
			}
			break;

		default:
			if ( is_archive() ) {
				if ( arcw_findMonth( $archiveYear, $archiveMonth, $months ) < 0 ) {
					$archiveYear  = $months[0]->year;
					$archiveMonth = $months[0]->month;
				}
			} else {
				$archiveYear  = $months[0]->year;
				$archiveMonth = $months[0]->month;
			}
	}

	// to show today's date, add the month if is not present in the calendar
	if ( $show_today && arcw_findMonth( $todayYear, $todayMonth, $months ) < 0 ) {
		$months[] = (object) array( 'year' => $todayYear, 'month' => $todayMonth );
		arcw_sortMonths( $months );
	}

	$totalmonths = count( $months );
	if ( ! $totalmonths ) {
		$totalmonths      = 1;
		$months[0]        = new StdClass();
		$months[0]->year  = ( is_archive() ) ? $archiveYear : date( 'Y' );
		$months[0]->month = ( is_archive() ) ? $archiveMonth : date( 'm' );
	}

	$cal = get_calendar_header( $view = 'months', $months, $archiveMonth, $archiveYear, $args );

	// Display week days names
	$week_begins = intval( get_option( 'start_of_week' ) );
	for ( $wdcount = 0; $wdcount <= 6; $wdcount ++ ) {
		$myweek[] = $wp_locale->get_weekday( ( $wdcount + $week_begins ) % 7 );
	}
	$i = 1;
	$cal .= '<div class="week-row weekdays">';
	foreach ( $myweek as $wd ) {
		$day_name = $wp_locale->get_weekday_abbrev( $wd );
		$last     = ( $i % 7 == 0 ) ? " last" : "";
		$cal .= '<span class="day weekday' . $last . '">' . $day_name . '</span>';
		$i ++;
	}

	$cal .= '</div><div class="archives-years">';

	// for each month
	for ( $i = 0; $i < $totalmonths; $i ++ ) {
		$lastyear = ( $i == $totalmonths - 1 ) ? " last" : "";
		$current  = ( $archiveYear == $months[ $i ]->year && $archiveMonth == $months[ $i ]->month ) ? " current" : "";

		// select days with posts
		$sql = "SELECT DAY(post_date) AS day
			FROM $wpdb->posts wpposts ";

		if ( count( $categories ) ) {
			$sql .= "JOIN $wpdb->term_relationships tr ON ( wpposts.ID = tr.object_id )
					JOIN $wpdb->term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id
					AND tt.term_id IN(" . $cats . ")
					AND tt.taxonomy = 'category') ";
		}
		$sql .= "WHERE post_type IN ('" . implode( "','", explode( ',', $post_type ) ) . "')
				AND post_status IN ('publish')
				AND YEAR(post_date) = " . $months[ $i ]->year . "
				AND MONTH(post_date) = " . $months[ $i ]->month . "
				AND post_password=''
				GROUP BY day";

		$days = $wpdb->get_results( $sql, ARRAY_N );

		$dayswithposts = array();
		for ( $j = 0; $j < count( $days ); $j ++ ) {
			$dayswithposts[] = $days[ $j ][0];
		}

		$cal .= '<div class="year ' . $months[ $i ]->month . ' ' . $months[ $i ]->year . $lastyear . $current . '" rel="' . $i . '">';
		// 1st of month date
		$firstofmonth = $months[ $i ]->year . '-' . intval( $months[ $i ]->month ) . '-01';
		// first weekday of the month
		$firstweekday = date( 'w', strtotime( "$firstofmonth" ) );

		$cal .= '<div class="week-row">';

		$k = 0; // total grid days counter
		$j = $week_begins;

		while ( $j != $firstweekday ) {
			$k ++;
			$last = ( $k % 7 == 0 ) ? " last" : "";
			$cal .= '<span class="day noday' . $last . '">&nbsp;</span>';
			$j ++;
			if ( $j == 7 ) {
				$j = 0;
			}
		}

		$monthdays = arcw_month_days( $months[ $i ]->year, $months[ $i ]->month );

		for ( $j = 1; $j <= $monthdays; $j ++ ) {
			$k ++;
			$last = ( $k % 7 == 0 ) ? " last" : "";

			if ( $j == $todayDay && $months[ $i ]->year == $todayYear && $months[ $i ]->month == $todayMonth ) {
				$todayClass = " today";
			} else {
				$todayClass = "";
			}

			if ( in_array( $j, $dayswithposts ) ) {
				$date_str = $months[ $i ]->year . '-' . $months[ $i ]->month . '-' . $j;
				$href  = make_arcw_link( get_day_link( $months[ $i ]->year, $months[ $i ]->month, $j ), $post_type, $cats );
				$title = date_i18n( get_option( 'date_format' ), strtotime( $date_str ) );
				$cal .= '<span class="day' . $last . $todayClass . ' has-posts">'
				        . '<a href="' . $href . '" title="' . $title . '" data-date="'. $date_str .'">' . $j . '</a>'
				        . '</span>';
			} else {
				$cal .= '<span class="day' . $last . $todayClass . '">' . $j . '</span>';
			}

			if ( $k % 7 == 0 ) {
				$cal .= "</div>\n<div class=\"week-row\">\n";
			}
		}

		while ( $k < 42 ) {
			$k ++;
			$last = ( $k % 7 == 0 ) ? " last" : "";
			$cal .= '<span class="day noday' . $last . '">&nbsp;</span>';
			if ( $k % 7 == 0 ) {
				$cal .= "</div>\n<div class=\"week-row\">\n";
			}
		}
		$cal .= "</div>\n";
		$cal .= "</div>\n";
	}

	$cal .= "</div></div>";

	return $cal;
}

function get_calendar_header( $view = 'months', $pages, $archiveMonth = null, $archiveYear, $args ) {
	global $wp_locale;
	extract( $args );
	$cal = "\n<!-- Archives Calendar Widget by Aleksei Polechin - alek´ - http://alek.be -->\n";
	$cal .= '<div class="calendar-archives ' . $theme . '" id="arc-' . $title . '-' . mt_rand( 10, 100 ) . '">';
	$cal .= '<div class="calendar-navigation">';

	if ( count( $pages ) > 1 ) {
		$cal .= '<a href="" class="prev-year"><span>' . html_entity_decode( $prev_text ) . '</span></a>';
	}

	$cal .= '<div class="menu-container ' . $view . '">';

	if ( $view == "months" ) {
		$title_text = $wp_locale->get_month( intval( $archiveMonth ) ) . " " . $archiveYear;
		$title_url  = get_month_link( intval( $archiveYear ), intval( $archiveMonth ) );
	} else {
		$title_text = $archiveYear;
		$title_url  = get_year_link( $archiveYear );
	}

	$title_url = $disable_title_link ? '#' : make_arcw_link( $title_url, $post_type, $cats );
	$cal .= '<a href="' . $title_url . '" class="title">' . $title_text . '</a>';

	$cal .= '<ul class="menu">';

	$i = 0;
	foreach ( $pages as $page ) {
		if ( $view == "months" ) {
			$archivelink = make_arcw_link( get_month_link( intval( $page->year ), intval( $page->month ) ), $post_type, $cats );
			$linkclass   = $page->year . ' ' . $page->month;
			$linktext    = $wp_locale->get_month( intval( $page->month ) ) . ' ' . $page->year;
		} else {
			$archivelink = make_arcw_link( get_year_link( $page ), $post_type, $cats );
			$linkclass   = $page;
			$linktext    = $page;
		}
		$current = ( ( $view == 'months' && $archiveYear == $page->year && $archiveMonth == $page->month ) || ( $view == "years" && $archiveYear == $page ) ) ? ' current' : '';
		$cal .= '<li><a href="' . $archivelink . '" class="' . $linkclass . $current . '" rel="' . $i . '" >' . $linktext . '</a></li>';
		$i ++;
	}
	$cal .= '</ul>';

	if ( count( $pages ) > 1 ) {
		$cal .= '<div class="arrow-down"><span>&#x25bc;</span></div>';
	}

	$cal .= '</div>';

	if ( count( $pages ) > 1 ) {
		$cal .= '<a href="" class="next-year"><span>' . html_entity_decode( $next_text ) . '</span></a>';
	}

	$cal .= '</div>';

	return $cal;
}


/***** FIND NUMBER OF DAYS IN A MONTH *****/
function arcw_month_days( $year, $month ) {
	switch ( intval( $month ) ) {
		case 4:
		case 6:
		case 9:
		case 11: // april, june, september, november
			return 30; // 30 days
		case 2: //february
			if ( $year % 400 == 0 || ( $year % 100 != 00 && $year % 4 == 0 ) ) // intercalary year check
			{
				return 29;
			} // 29 days or
			return 28; // 28 days
		default: // other months
			return 31; // 31 days
	}
}


/***** MONTH SORT / SEARCH *****/
function arcw_findMonth( $year, $month, $months ) {
	if ( count( $months ) == 0 ) {
		return - 1;
	}

	$i = 0;
	while ( $i < count( $months ) - 1 && intval( $months[ $i ]->year ) > $year ) {
		$i ++;
	}

	if ( $months[ $i ]->year == $year ) {
		while ( $i < count( $months ) - 1 && intval( $months[ $i ]->month ) > $month ) {
			$i ++;
		}

		if ( $months[ $i ]->month == $month ) {
			return $i;
		} // find on position $i
		return - 1; // not found
	} else {
		return - 1;
	} // not found
}

function arcw_sortMonths( &$data ) {
	usort( $data, "arcw_compare_months" );
}

function arcw_compare_months( $a, $b ) {
	if ( $a->year == $b->year ) {
		return $a->month < $b->month ? 1 : - 1;
	}

	return $a->year < $b->year ? 1 : - 1;
}