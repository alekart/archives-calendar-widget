<?php

class ARCW {
	private array $options;

	public function __construct() {
		$this->options = self::getOptions();
	}

	static array $defaultOptions = [
		// should automatically include css files into the wp theme
		'css'            => true,
		// should automatically add js scripts files into the wp theme
		'plugin-init'    => true,
		// applied calendar theme
		'theme'          => 'calendrier',
		// should display Archives calendar settings menu in the wp admin menu
		'show_settings'  => true,
		// should activate archives filter when opening from calendar to apply correct categories
		'filter'         => false,
		// Display password protected posts in widgets and in archives listing
		'show_protected' => false,
	];

	/**
	 * Get Archives Calendar saved option or if it's not defined return defaults
	 * @return array
	 */
	static function getOptions(): array {
		$options = get_option( 'archivesCalendar' );
		if ( ! $options ) {
			return self::$defaultOptions;
		}
		return $options;
	}

	/**
	 * Update plugin options from defaults keep only the settings
	 * that still exists in defaults unused ones should be deleted
	 * @param array $oldOptions
	 * @return array
	 */
	static function upgradeOptions( array $oldOptions ): array {
		return $oldOptions;
		// TODO:
		//  copy from $options only the key=>value that exists in the default settings
		//  to keep the users settings and remove all unused old settings
		//		$updated = array();
		//		foreach ( $options as $key => $value ) {
		//			if ( array_key_exists( $key, $options ) ) {
		//				$updated[ $key ] = $value;
		//			}
		//		}
		//		// merge current options with the default options to get the new settings with the default value
		//		return array_merge( $default_options, $updated );
	}

	/**
	 * Prepare the sql query to get dates, titles and protected status for all
	 * posts that matches the provided post types and categories.
	 * Protected posts will be marked as protected and can be filtered from the results.
	 * @param string $postTypes
	 * @param string $categories
	 * @return string
	 */
	static function prepareSqlRequest( string $postTypes, string $categories = '' ): string {
		global $wpdb;
		/**
		 * Name of the posts table
		 */
		$postsTable = $wpdb->posts;
		/**
		 * String of comma separated categories if provided value is a non-empty list otherwise null
		 * @var string
		 */
		$categories = $categories !== '' ? $categories : null;

		$sql = "SELECT ID AS id, post_date AS date, post_title AS title, IF(post_password='', FALSE, TRUE) as protected FROM $postsTable";

		if ( $categories ) {
			$sql .= " JOIN $wpdb->term_relationships tr ON $postsTable.ID = tr.object_id";
			$sql .= " JOIN $wpdb->term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id";
			$sql .= " AND tt.term_id IN('$categories')";
			$sql .= " AND tt.taxonomy = 'category')";
		}

		$sql .= " WHERE post_type IN ('$postTypes')";
		// TODO: the status may be configurable
		$sql .= " AND post_status IN ('publish')";

		return $sql;
	}

	/**
	 * Get posts matching the prepared SQL query and return a list with `id`, `date` and `title`.
	 * If plugin is configured to show protected posts it will include them.
	 * @param string $sql
	 * @return array simplified list of posts
	 */
	static function getPosts( string $sql ): array {
		global $wpdb;

		$dateFormat = get_option( 'date_format' );
		$options = ARCW::getOptions();
		$results = $wpdb->get_results( $sql );
		$posts = [];

		foreach ( $results as $row ) {
			$protected = (bool) $row->protected;

			if ( ! $protected || ($protected === true && $options['show_protected']) ) {
				try {
					$date = new DateTime( $row->date );
					$timestamp = $date->getTimestamp();
					$wpDate = wp_date( 'Y-m-d', $timestamp );
				} catch ( Exception $ex ) {
					$wpDate = $row->date;
				}
				$year = intval(substr($wpDate, 0, 4));
				$month = intval(substr($wpDate, 5, 2));
				$day = intval(substr($wpDate, 8, 2));
				$posts[] = [
					'id'    => $row->id,
					'date'  => $wpDate,
					'title' => $row->title,
					'link'  => get_permalink( $row->id ),
					'dayLink' => get_day_link($year, $month, $day),
					'monthLink' => get_month_link($year, $month),
					'yearLink' => get_year_link($year),
				];
			}
		}

		return $posts;
	}
}
