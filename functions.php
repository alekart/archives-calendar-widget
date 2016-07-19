<?php
/**
 * @param $month int
 * @param $year int
 *
 * @return array
 */
function build_month_matrix( $year, $month ) {
	$firstWeekday = date( 'w', strtotime( $year . '-' . $month . '-01' ) );
	$daysInMonth  = arcw_month_days( $year, $month );
	$weekStart    = intval( get_option( 'start_of_week' ) );

	$monthMatrix = array();

	$gridCount = 0; // total grid days counter
	$day       = $weekStart;

	// fill empty weekdays before month starts
	while ( $day != $firstWeekday ) {
		array_push( $monthMatrix, 0 );
		$gridCount ++;
		$day ++;
		if ( $day == 7 ) {
			$day = 0;
		}
	}

	// fill the month dates into the grid
	for ( $date = 1; $date <= $daysInMonth; $date ++ ) {
		array_push( $monthMatrix, $date );
		$gridCount ++;
	}

	// and finish filling empty days for the rest of the grid
	while ( $gridCount < 42 ) {
		array_push( $monthMatrix, 0 );
		$gridCount ++;
	}

	return $monthMatrix;
}

function getDaysWithPosts( $year, $month, $post_type, $categories = array(), $cats = array() ) {
	global $wpdb;

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
				AND YEAR(post_date) = " . $year . "
				AND MONTH(post_date) = " . $month . "
				AND post_password=''
				GROUP BY day";

	$days = $wpdb->get_results( $sql, ARRAY_N );

	$dayswithposts = array();
	for ( $j = 0; $j < count( $days ); $j ++ ) {
		$dayswithposts[] = $days[ $j ][0];
	}

return $dayswithposts;
}
