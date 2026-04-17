<?php

namespace Edudeme\Elementor;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * LXP Course HTML Widget
 *
 * An Elementor HTML widget that resolves {{lp_*}} tokens against the LP_Course
 * object for the course currently being viewed. Designed for use in Elementor
 * Theme Builder single-course templates.
 *
 * Why not shortcodes? Elementor's element caching and page cache plugins can
 * cache already-resolved shortcode output, causing Course A's data to appear
 * on Course B. This widget's render() runs server-side on every request
 * (Elementor default: dynamic content = no element caching) and resolves tokens
 * directly from the LP_Course object using get_queried_object_id() — immune to
 * Elementor setting a different global $post for its template.
 *
 * Supported tokens:
 *  {{lp_title}}         — Course title
 *  {{lp_excerpt}}       — Course excerpt / short description
 *  {{lp_image_url}}     — Featured image URL (full size) or LP placeholder
 *  {{lp_level}}         — Course level (Beginner / Intermediate / Advanced)
 *  {{lp_duration}}      — Human-readable duration, e.g. "4 Weeks"
 *  {{lp_lesson_count}}  — Number of lessons in the course
 *  {{lp_student_count}} — Number of enrolled students (real + fake)
 *  {{lp_enroll_button}} — LearnPress enroll / purchase button HTML
 */
class LXP_Course_HTML_Widget extends Widget_Base {

	public function get_name() {
		return 'lxp-course-html';
	}

	public function get_title() {
		return esc_html__( 'Course HTML', 'tiny-lxp-platform' );
	}

	public function get_icon() {
		return 'eicon-code';
	}

	public function get_categories() {
		return [ 'edudeme-addons' ];
	}

	/**
	 * Do NOT declare is_dynamic_content() — Elementor's default is dynamic (true),
	 * meaning it will never cache this widget's output in its element cache.
	 * This is intentional: each course page must render fresh.
	 */

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'tiny-lxp-platform' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'html_content',
			[
				'label'       => esc_html__( 'HTML', 'tiny-lxp-platform' ),
				'type'        => Controls_Manager::CODE,
				'language'    => 'html',
				'rows'        => 20,
				'default'     => '',
				'placeholder' => implode( "\n", [
					'Available tokens:',
					'  {{lp_title}}         — Course title',
					'  {{lp_excerpt}}       — Course excerpt',
					'  {{lp_image_url}}     — Featured image URL',
					'  {{lp_level}}         — Course level',
					'  {{lp_duration}}      — Course duration',
					'  {{lp_lesson_count}}  — Number of lessons',
					'  {{lp_student_count}} — Enrolled students',
					'  {{lp_enroll_button}} — Enroll / purchase button',
				] ),
				'dynamic'     => [ 'active' => false ],
			]
		);

		$this->add_control(
			'editor_message',
			[
				'label'       => esc_html__( 'Editor Preview Message', 'tiny-lxp-platform' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Course HTML — preview visible on the frontend course page.', 'tiny-lxp-platform' ),
				'description' => esc_html__( 'Shown in the Elementor editor instead of live course data.', 'tiny-lxp-platform' ),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Resolve the LP_Course object for the course currently being viewed.
	 *
	 * get_queried_object_id() reads WP_Query::$queried_object_id — set at
	 * query time from the URL. Elementor cannot change this when rendering a
	 * Theme Builder template, unlike global $post which Elementor replaces with
	 * the template post.
	 *
	 * @return \LP_Course|null
	 */
	private function get_current_course() {
		$id = absint( get_queried_object_id() );
		if ( $id > 0 && get_post_type( $id ) === LP_COURSE_CPT && function_exists( 'learn_press_get_course' ) ) {
			return learn_press_get_course( $id ) ?: null;
		}
		return null;
	}

	/**
	 * Build the token → value map for the current course.
	 *
	 * @param \LP_Course $course
	 * @return array<string,string>
	 */
	private function build_token_map( $course ) {
		$course_id = $course->get_id();

		// Duration: LP_Course::get_duration() returns seconds; raw meta is the display string.
		$raw_duration = get_post_meta( $course_id, '_lp_duration', true );
		$duration     = '';
		if ( $raw_duration ) {
			$parts  = explode( ' ', trim( $raw_duration ) );
			$number = floatval( $parts[0] ?? 0 );
			$type   = $parts[1] ?? '';
			if ( $number && $type && class_exists( 'LP_Datetime' ) && method_exists( 'LP_Datetime', 'get_string_plural_duration' ) ) {
				$duration = LP_Datetime::get_string_plural_duration( $number, $type );
			} else {
				$duration = $raw_duration;
			}
		}

		// Enroll / purchase button.
		ob_start();
		do_action( 'learn-press/course/buttons' );
		$button_html = ob_get_clean();

		return [
			'{{lp_title}}'         => esc_html( $course->get_title() ),
			'{{lp_excerpt}}'       => wp_kses_post( get_the_excerpt( $course_id ) ),
			'{{lp_image_url}}'     => esc_url( $course->get_image_url( 'full' ) ),
			'{{lp_level}}'         => esc_html( get_post_meta( $course_id, '_lp_level', true ) ?: '' ),
			'{{lp_duration}}'      => esc_html( $duration ),
			'{{lp_lesson_count}}'  => absint( $course->count_items( LP_LESSON_CPT ) ),
			'{{lp_student_count}}' => absint( $course->count_students() ),
			'{{lp_enroll_button}}' => $button_html,
		];
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$html     = $settings['html_content'] ?? '';

		if ( empty( trim( $html ) ) ) {
			return;
		}

		$course = $this->get_current_course();

		if ( ! $course ) {
			// In Elementor editor or on a non-course page, show the placeholder message.
			echo '<p style="color:#888;font-style:italic;">' . esc_html( $settings['editor_message'] ) . '</p>';
			return;
		}

		$map    = $this->build_token_map( $course );
		$output = str_replace( array_keys( $map ), array_values( $map ), $html );

		// wp_kses_post strips unsafe tags; enroll button HTML passes because it
		// uses standard anchor/form/button elements already escaped by LP.
		echo wp_kses_post( $output );
	}
}
