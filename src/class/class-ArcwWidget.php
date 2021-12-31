<?php

/***** WIDGET CLASS *****/
class ArcwWidget extends WP_Widget {
	private array $pluginOptions;
	private int $weekStartDay;

	public function __construct() {
		parent::__construct(
			'archives_calendar',
			'Archives Calendar',
			array( 'description' => __( 'Show archives as calendar', 'archives-calendar-widget' ), )
		);

		$this->pluginOptions = ARCW::getOptions();
		$this->weekStartDay = intval( get_option( 'start_of_week' ) );
	}

	static array $defaultOptions = [
		'title'              => '',
		'next_text'          => '>',
		'prev_text'          => '<',
		'post_count'         => true,
		'month_view'         => false,
		'month_select'       => 'default',
		'disable_title_link' => false,
		// TODO: unify different_theme and theme into 'theme'
		//  it should display different theme if theme is defined
		'different_theme'    => false,
		'theme'              => null,
		'categories'         => null,
		// TODO: v1 configuration contains a string and needs to be converted into an array
		'post_type'          => [ 'post' ],
		// TODO: rename into 'highlight-today'
		'show_today'         => false,
	];

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		echo self::archivesCalendar( $instance );
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$defaults = array(
			'title'              => __( 'Archives' ),
			'next_text'          => '>',
			'prev_text'          => '<',
			'post_count'         => true,
			'month_view'         => false,
			'month_select'       => 'default',
			'disable_title_link' => false,
			// deprecated use theme value as use different
			'different_theme'    => false,
			'theme'              => null,
			'categories'         => null,
			'post_type'          => array( 'post' ),
			'show_today'         => false,
		);
		$instance = wp_parse_args( $instance, $defaults );

		$title = $instance['title'];
		$prev = $instance['prev_text'];
		$next = $instance['next_text'];
		$count = $instance['post_count'];
		$month_view = $instance['month_view'];
		$month_select = $instance['month_select'];
		$disable_title_link = $instance['disable_title_link'];
		$different_theme = $instance['different_theme'];
		$arw_theme = $instance['theme'];
		$cats = $instance['categories'];
		$post_type = $instance['post_type'];

		if ( is_array( $post_type ) && empty( $post_type ) || (! $post_type || $post_type == '') ) {
			$post_type = array( 'post' );
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['next_text'] = htmlspecialchars( $new_instance['next_text'] );
		$instance['prev_text'] = htmlspecialchars( $new_instance['prev_text'] );

		if ( $instance['next_text'] == htmlspecialchars( '>' ) ) {
			$instance['prev_text'] = htmlspecialchars( '<' );
		}

		$instance['post_count'] = ($new_instance['post_count']) ? $new_instance['post_count'] : 0;
		$instance['month_view'] = $new_instance['month_view'];
		$instance['month_select'] = $new_instance['month_select'];
		$instance['disable_title_link'] = ($new_instance['disable_title_link']) ? $new_instance['disable_title_link'] : 0;
		$instance['different_theme'] = ($new_instance['different_theme']) ? $new_instance['different_theme'] : 0;
		$instance['theme'] = $new_instance['theme'];
		$instance['categories'] = $new_instance['categories'];
		$instance['post_type'] = ($new_instance['post_type']) ? $new_instance['post_type'] : array( 'post' );

		return $instance;
	}

	private function getWeekDaysTamplate(): string {
		global $wp_locale;
		/**
		 * WP configured start of week day
		 */
		for ( $dayIndex = 0; $dayIndex <= 6; $dayIndex++ ) {
			$weekDays[] = $wp_locale->get_weekday( ($dayIndex + $this->weekStartDay) % 7 );
		}
		$daysTemplate = '';
		foreach ( $weekDays as $wd ) {
			$day_name = $wp_locale->get_weekday_abbrev( $wd );
			$daysTemplate .= '<span class="arcw-weekday">' . $day_name . '</span>';
		}
		return $daysTemplate;
	}

	private function getMonth(): array {
		global $wp_locale;
		for ( $index = 1; $index <= 12; $index++ ) {
			$month = $wp_locale->get_month( $index );
			$months[] = [
				'full'  => $month,
				'short' => $wp_locale->get_month_abbrev( $month ),
			];
		}
		return $months;
	}

	public function archivesCalendar( array $instance ) {
		global $wp_locale;
		var_dump( $instance );

		$options = wp_parse_args( $instance, ArcwWidget::$defaultOptions );
		$mode = $options['month_view'] ? 'month' : 'year';
		$theme = $instance['theme'] ?: $this->pluginOptions['theme'];

		// if the theme uses a different theme we need to enqueue the theme's stylesheet
		if ( $theme !== $this->pluginOptions['theme'] ) {
			wp_register_style( 'arcw-theme-' . $theme, plugins_url( 'themes/' . $theme . '.css', __FILE__ ), array(), ARCWVersion );
			wp_enqueue_style( 'arcw-theme-' . $theme );
		}

		$configJson = [
			'post-type'   => $options['post_type'],
			'categories'  => $options['categories'],
			'titleLink'   => ! $options['disable_title_link'],
			'mode'        => $mode,
			'monthSelect' => $options['month_select'],
			'postCount'   => $options['post_count'],
			'months'      => $this->getMonth(),
			'weekStarts'  => $this->weekStartDay,
		];

		$template = "<div class='archives-calendar arcw-theme-$theme' data-configuration='" . json_encode( $configJson ) . "'>
			   		<div class='arcw-menu'>
			   			<div class='arcw-menu__nav arcw-menu__nav--prev'></div>
			   			<div class='arcw-menu__list'></div>
			   			<div class='arcw-menu__nav arcw-menu__nav--next'></div>
						</div>
						<div class='arcw-view arcw-view--$mode'>";
		if ( $mode === 'month' ) {
			$template .= "<div class='arcw-view__weekdays'>" . $this->getWeekDaysTamplate() . "</div>";
		}
		$template .= "<div class='arcw-view__grid arcw-view__grid--$mode'></div>
						</div>
					</div>";
		return $template;
	}
}
