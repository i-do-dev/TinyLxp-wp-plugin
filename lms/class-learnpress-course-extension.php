<?php

class TL_LearnPress_Course_Extension {
	public function enqueue_student_course_styles() {
		if (!is_singular(LP_COURSE_CPT) || !is_user_logged_in()) {
			return;
		}

		$userdata = get_userdata(get_current_user_id());
		if (!$userdata || !in_array('lxp_student', (array) $userdata->roles, true)) {
			return;
		}

		$style_url = plugin_dir_url(dirname(__FILE__) . '/../TinyLxp-wp-plugin.php') . 'public/css/lxp-student-course.css';
		wp_enqueue_style('tinylxp-student-course', $style_url, array(), null);
	}

	public static function create_grades_table() {
		global $wpdb;

		$wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tiny_lms_grades(
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			lesson_id bigint(20) default NULL,
			score FLOAT default NULL,
			user_id bigint(20) default NULL,
			PRIMARY KEY (id)
		)");
	}

	public function modify_list_row_actions($actions, $post) {
		if ($post->post_type == TL_COURSE_CPT && current_user_can('grades_lxp_course')) {
			$actions['duplicate'] = '<a href="' . site_url() . '/wp-admin/admin.php?page=grades&course_id=' . $post->ID . '" title="" rel="permalink">GradeBook</a>';
		}

		return $actions;
	}

	/**
	 * Register per-field LP course shortcodes for use in Elementor HTML widgets.
	 * LearnPress v4 removed these shortcodes; we re-implement them using LP v4 APIs.
	 * These only resolve correctly on a single lp_course page where $post is set.
	 */
	public function register_course_shortcodes() {
		add_shortcode( 'lp_course_title', function() {
			return esc_html( get_the_title() );
		} );

		add_shortcode( 'lp_course_excerpt', function() {
			return wp_kses_post( get_the_excerpt() );
		} );

		add_shortcode( 'lp_course_featured_image_url', function() {
			return esc_url( (string) get_the_post_thumbnail_url( get_the_ID(), 'full' ) );
		} );

		add_shortcode( 'lp_course_level', function() {
			$level = get_post_meta( get_the_ID(), '_lp_level', true );
			return esc_html( $level ?: '' );
		} );

		add_shortcode( 'lp_course_duration', function() {
			$raw = get_post_meta( get_the_ID(), '_lp_duration', true );
			if ( ! $raw ) {
				return '';
			}
			// LP stores duration as e.g. "4 week"; use LP_Datetime to pluralize if available.
			$parts  = explode( ' ', trim( $raw ) );
			$number = floatval( $parts[0] ?? 0 );
			$type   = $parts[1] ?? '';
			if ( $number && $type && class_exists( 'LP_Datetime' ) && method_exists( 'LP_Datetime', 'get_string_plural_duration' ) ) {
				return esc_html( LP_Datetime::get_string_plural_duration( $number, $type ) );
			}
			return esc_html( $raw );
		} );

		add_shortcode( 'lp_course_students', function() {
			$count = (int) get_post_meta( get_the_ID(), '_lp_enrolled', true );
			return $count > 0 ? $count : 0;
		} );

		add_shortcode( 'lp_course_lessons', function() {
			if ( ! function_exists( 'learn_press_get_course' ) ) {
				return '';
			}
			$course = learn_press_get_course( get_the_ID() );
			if ( ! $course ) {
				return '';
			}
			$count = $course->count_items( LP_LESSON_CPT );
			return absint( $count );
		} );

		add_shortcode( 'learn_press_button_course', function() {
			ob_start();
			do_action( 'learn-press/course/buttons' );
			return ob_get_clean();
		} );
	}

	/**
	 * Process WordPress shortcodes inside Elementor's HTML widget content.
	 * Elementor does not call do_shortcode() on HTML widget output by default.
	 *
	 * @param string                    $content Widget rendered HTML.
	 * @param \Elementor\Widget_Base    $widget  The widget instance.
	 * @return string
	 */
	public function process_html_widget_shortcodes( $content, $widget ) {
		if ( $widget->get_name() === 'html' ) {
			return do_shortcode( $content );
		}
		return $content;
	}
}