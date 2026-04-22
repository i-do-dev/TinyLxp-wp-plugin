<?php

namespace Edudeme\Elementor;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * LXP Lesson HTML Widget
 *
 * An Elementor HTML widget that resolves {{lp_lesson_*}} tokens against the
 * LP_Lesson and LP_Course objects for the lesson currently being viewed.
 * Designed for use in Elementor Theme Builder single-lesson templates.
 *
 * Supported tokens:
 *  {{lp_lesson_title}}          — Lesson post title
 *  {{lp_lesson_tagline}}        — Custom tagline (lxp_lesson_tagline post meta)
 *  {{lp_lesson_duration}}       — Human-readable duration, e.g. "30 Minutes"
 *  {{lp_lesson_number}}         — 1-based position of this lesson in the course
 *  {{lp_lesson_total}}          — Total number of lessons in the course
 *  {{lp_lesson_module_label}}   — Pre-composed "Lesson X of Y"
 *  {{lp_lesson_section_name}}   — Name of the LP section this lesson belongs to
 *  {{lp_lesson_section_number}} — 1-based ordinal of that section in the course
 *  {{lp_course_title}}          — Parent course title
 *  {{lp_course_image_url}}      — Parent course featured image URL (full size)
 */
class LXP_Lesson_HTML_Widget extends Widget_Base {

	public function get_name() {
		return 'lxp-lesson-html';
	}

	public function get_title() {
		return esc_html__( 'Lesson HTML', 'tiny-lxp-platform' );
	}

	public function get_icon() {
		return 'eicon-code';
	}

	public function get_categories() {
		return [ 'edudeme-addons' ];
	}

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
					'  {{lp_lesson_title}}          — Lesson title',
					'  {{lp_lesson_tagline}}        — Lesson tagline',
					'  {{lp_lesson_duration}}       — Lesson duration',
					'  {{lp_lesson_number}}         — Lesson number in course',
					'  {{lp_lesson_total}}          — Total lessons in course',
					'  {{lp_lesson_module_label}}   — "Lesson X of Y"',
					'  {{lp_lesson_section_name}}   — Section name',
					'  {{lp_lesson_section_number}} — Section number',
					'  {{lp_course_title}}          — Course title',
					'  {{lp_course_image_url}}      — Course image URL',
				] ),
				'dynamic'     => [ 'active' => false ],
			]
		);

		$this->add_control(
			'editor_message',
			[
				'label'       => esc_html__( 'Editor Preview Message', 'tiny-lxp-platform' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Lesson HTML — preview visible on the frontend lesson page.', 'tiny-lxp-platform' ),
				'description' => esc_html__( 'Shown in the Elementor editor instead of live lesson data.', 'tiny-lxp-platform' ),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Resolve the lesson post and parent LP_Course for the page being viewed.
	 *
	 * LP4 renders lessons inside the course URL context (/{course}/lessons/{lesson}/),
	 * so get_queried_object_id() returns the course ID, not the lesson ID.
	 * LP_Global::course_item() is the authoritative source set by LP during its
	 * own request routing.
	 *
	 * Resolution order:
	 *  1. LP_Global::course_item() — set by LP when processing lesson pages.
	 *  2. get_queried_object_id()  — works on true singular lp_lesson pages.
	 *  3. get_the_ID()             — last resort via global $post.
	 *
	 * @return array{0:\WP_Post,1:\LP_Course}|null
	 */
	private function get_current_lesson_and_course() {
		if ( ! function_exists( 'learn_press_get_course' ) ) {
			return null;
		}

		$lesson_id = 0;
		$course_id = 0;

		// Strategy 1: LP_Global — authoritative inside LP lesson template rendering.
		if ( class_exists( 'LP_Global' ) && method_exists( 'LP_Global', 'course_item' ) ) {
			try {
				$lp_item = LP_Global::course_item();
				error_log( '[LXP_Lesson_Widget] LP_Global::course_item() type=' . ( $lp_item && method_exists( $lp_item, 'get_type' ) ? $lp_item->get_type() : 'null' ) );
				if (
					$lp_item
					&& method_exists( $lp_item, 'get_id' )
					&& method_exists( $lp_item, 'get_type' )
					&& defined( 'LP_LESSON_CPT' )
					&& $lp_item->get_type() === LP_LESSON_CPT
				) {
					$lesson_id = absint( $lp_item->get_id() );
				}
				// Grab course from LP_Global at the same time.
				if ( $lesson_id > 0 && method_exists( 'LP_Global', 'course' ) ) {
					$lp_course_global = LP_Global::course();
					if ( $lp_course_global && method_exists( $lp_course_global, 'get_id' ) ) {
						$course_id = absint( $lp_course_global->get_id() );
					}
				}
			} catch ( \Throwable $e ) {
				error_log( '[LXP_Lesson_Widget] LP_Global strategy failed: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine() );
			}
		}

		// Strategy 2: queried object (true singular lp_lesson page).
		if ( $lesson_id <= 0 && defined( 'LP_LESSON_CPT' ) ) {
			$qo_id = absint( get_queried_object_id() );
			error_log( '[LXP_Lesson_Widget] Strategy2: qo_id=' . $qo_id . ' type=' . get_post_type( $qo_id ) );
			if ( $qo_id > 0 && get_post_type( $qo_id ) === LP_LESSON_CPT ) {
				$lesson_id = $qo_id;
			}
		}

		// Strategy 3: global $post.
		if ( $lesson_id <= 0 && defined( 'LP_LESSON_CPT' ) ) {
			$post_id = absint( get_the_ID() );
			error_log( '[LXP_Lesson_Widget] Strategy3: post_id=' . $post_id . ' type=' . get_post_type( $post_id ) );
			if ( $post_id > 0 && get_post_type( $post_id ) === LP_LESSON_CPT ) {
				$lesson_id = $post_id;
			}
		}

		error_log( '[LXP_Lesson_Widget] final: lesson_id=' . $lesson_id . ' course_id=' . $course_id );

		if ( $lesson_id <= 0 ) {
			return null;
		}

		$lesson_post = get_post( $lesson_id );
		if ( ! $lesson_post ) {
			return null;
		}

		// Resolve course ID if LP_Global didn't provide it.
		if ( $course_id <= 0 ) {
			$course_id = absint( get_post_meta( $lesson_id, 'tl_course_id', true ) );
		}

		if ( $course_id <= 0 ) {
			global $wpdb;
			$sections_table      = $wpdb->prefix . 'learnpress_sections';
			$section_items_table = $wpdb->prefix . 'learnpress_section_items';
			$course_id           = absint( $wpdb->get_var(
				$wpdb->prepare(
					"SELECT s.section_course_id
					 FROM {$sections_table} s
					 INNER JOIN {$section_items_table} si ON s.section_id = si.section_id
					 WHERE si.item_id = %d
					 LIMIT 1",
					$lesson_id
				)
			) );
		}

		if ( $course_id <= 0 ) {
			return null;
		}

		$course = learn_press_get_course( $course_id );
		if ( ! $course ) {
			return null;
		}

		return [ $lesson_post, $course ];
	}

	/**
	 * Calculate the 1-based position of a lesson across the entire course.
	 *
	 * Counts every lesson-type item that appears in an earlier section, or in
	 * the same section at a lower item_order, then adds 1.
	 *
	 * @param int $lesson_id  Lesson post ID.
	 * @param int $course_id  Parent course post ID.
	 * @return int  1-based lesson ordinal, or 0 on failure.
	 */
	private function get_lesson_number( $lesson_id, $course_id ) {
		global $wpdb;

		$lesson_id = absint( $lesson_id );
		$course_id = absint( $course_id );

		if ( $lesson_id <= 0 || $course_id <= 0 ) {
			return 0;
		}

		$sections_table      = $wpdb->prefix . 'learnpress_sections';
		$section_items_table = $wpdb->prefix . 'learnpress_section_items';

		// Get the section_order and item_order for the target lesson.
		$lesson_pos = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT s.section_id, s.section_order, si.item_order
				 FROM {$sections_table} s
				 INNER JOIN {$section_items_table} si ON s.section_id = si.section_id
				 WHERE si.item_id = %d AND s.section_course_id = %d
				 LIMIT 1",
				$lesson_id,
				$course_id
			)
		);

		if ( ! $lesson_pos ) {
			return 0;
		}

		// Count all items in this course that come before the lesson.
		$count = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*)
				 FROM {$sections_table} s
				 INNER JOIN {$section_items_table} si ON s.section_id = si.section_id
				 WHERE s.section_course_id = %d
				   AND (
				       s.section_order < %d
				       OR ( s.section_order = %d AND si.item_order < %d )
				   )",
				$course_id,
				(int) $lesson_pos->section_order,
				(int) $lesson_pos->section_order,
				(int) $lesson_pos->item_order
			)
		);

		return $count + 1;
	}

	/**
	 * Fetch the section row (section_id, section_name, section_order) for a lesson.
	 *
	 * @param int $lesson_id  Lesson post ID.
	 * @param int $course_id  Parent course post ID.
	 * @return object|null
	 */
	private function get_lesson_section( $lesson_id, $course_id ) {
		global $wpdb;

		$lesson_id = absint( $lesson_id );
		$course_id = absint( $course_id );

		if ( $lesson_id <= 0 || $course_id <= 0 ) {
			return null;
		}

		$sections_table      = $wpdb->prefix . 'learnpress_sections';
		$section_items_table = $wpdb->prefix . 'learnpress_section_items';

		return $wpdb->get_row(
			$wpdb->prepare(
				"SELECT s.section_id, s.section_name, s.section_order
				 FROM {$sections_table} s
				 INNER JOIN {$section_items_table} si ON s.section_id = si.section_id
				 WHERE si.item_id = %d AND s.section_course_id = %d
				 LIMIT 1",
				$lesson_id,
				$course_id
			)
		);
	}

	/**
	 * Build the token → value map for the current lesson.
	 *
	 * @param \WP_Post  $lesson_post
	 * @param \LP_Course $course
	 * @return array<string,string>
	 */
	private function build_lesson_token_map( $lesson_post, $course ) {
		$lesson_id = absint( $lesson_post->ID );
		$course_id = absint( $course->get_id() );

		// --- Duration ---
		$raw_duration = get_post_meta( $lesson_id, '_lp_duration', true );
		$duration     = '';
		if ( $raw_duration ) {
			$parts  = explode( ' ', trim( $raw_duration ) );
			$number = floatval( $parts[0] ?? 0 );
			$type   = $parts[1] ?? '';
			if ( $number && $type && class_exists( '\\LP_Datetime' ) && method_exists( '\\LP_Datetime', 'get_string_plural_duration' ) ) {
				$duration = \LP_Datetime::get_string_plural_duration( $number, $type );
			} else {
				$duration = $raw_duration;
			}
		}

		// --- Lesson position ---
		$lesson_number = $this->get_lesson_number( $lesson_id, $course_id );
		$lesson_total  = absint( $course->count_items( LP_LESSON_CPT ) );
		$module_label  = sprintf(
			/* translators: 1: lesson number, 2: total lessons */
			esc_html__( 'Lesson %1$d of %2$d', 'tiny-lxp-platform' ),
			$lesson_number > 0 ? $lesson_number : 1,
			$lesson_total
		);

		// --- Section ---
		$section         = $this->get_lesson_section( $lesson_id, $course_id );
		$section_name    = $section ? esc_html( $section->section_name ) : '';
		$section_number  = $section ? absint( $section->section_order ) : 0;

		// --- Course image ---
		$course_image_url = esc_url( $course->get_image_url( 'full' ) );

		return [
			'{{lp_lesson_title}}'          => esc_html( $lesson_post->post_title ),
			'{{lp_lesson_tagline}}'        => esc_html( get_post_meta( $lesson_id, 'lxp_lesson_tagline', true ) ?: '' ),
			'{{lp_lesson_duration}}'       => esc_html( $duration ),
			'{{lp_lesson_number}}'         => $lesson_number > 0 ? (string) $lesson_number : '',
			'{{lp_lesson_total}}'          => $lesson_total > 0 ? (string) $lesson_total : '',
			'{{lp_lesson_module_label}}'   => $module_label,
			'{{lp_lesson_section_name}}'   => $section_name,
			'{{lp_lesson_section_number}}' => $section_number > 0 ? (string) $section_number : '',
			'{{lp_course_title}}'          => esc_html( $course->get_title() ),
			'{{lp_course_image_url}}'      => $course_image_url,
		];
	}

	protected function render() {
		try {
			$this->render_lesson_html();
		} catch ( \Throwable $e ) {
			error_log( '[LXP_Lesson_Widget] render() fatal: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine() . "\n" . $e->getTraceAsString() );
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				echo '<p style="color:red;border:1px solid red;padding:8px;"><strong>LXP Lesson Widget error:</strong> ' . esc_html( $e->getMessage() ) . ' in ' . esc_html( basename( $e->getFile() ) ) . ':' . esc_html( $e->getLine() ) . '</p>';
			}
		}
	}

	private function render_lesson_html() {
		$settings = $this->get_settings_for_display();
		$html     = $settings['html_content'] ?? '';

		if ( empty( trim( $html ) ) ) {
			return;
		}

		$context = $this->get_current_lesson_and_course();

		if ( ! $context ) {
			echo '<p style="color:#888;font-style:italic;">' . esc_html( $settings['editor_message'] ) . '</p>';
			return;
		}

		[ $lesson_post, $course ] = $context;

		$map    = $this->build_lesson_token_map( $lesson_post, $course );
		$output = str_replace( array_keys( $map ), array_values( $map ), $html );

		$allowed_html = wp_kses_allowed_html( 'post' );
		$allowed_html['style']   = [ 'type' => true, 'media' => true ];
		$allowed_html['section'] = [ 'id' => true, 'class' => true, 'style' => true ];
		$allowed_html['hr']      = [ 'class' => true, 'style' => true ];

		echo wp_kses( $output, $allowed_html );
	}
}
