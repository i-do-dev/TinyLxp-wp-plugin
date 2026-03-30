<?php

class TL_LearnPress_Lesson_Extension {
	private $lti_metadata_repository = null;
	private $section_repository = null;
	private $lesson_template_path = '';
	private $learner_assignment_template_path = '';

	public function __construct() {
		$this->lesson_template_path = dirname(__FILE__) . '/templates/tinyLxpTheme/single-tl_lesson.php';
		$this->learner_assignment_template_path = dirname(__FILE__) . '/templates/tinyLxpTheme/page-learner-lesson.php';
	}

	private function metadata_repository() {
		if (is_null($this->lti_metadata_repository)) {
			$this->lti_metadata_repository = new TL_LTI_Metadata_Repository();
		}

		return $this->lti_metadata_repository;
	}

	private function section_repository() {
		if (is_null($this->section_repository)) {
			$this->section_repository = new TL_LearnPress_Section_Repository();
		}

		return $this->section_repository;
	}

	private function resolve_course_id_for_lesson($lesson_id = 0, $fallback_course_id = 0) {
		$lesson_id = absint($lesson_id);
		$fallback_course_id = absint($fallback_course_id);

		if ($lesson_id > 0) {
			$course_id = $this->section_repository()->get_course_id_by_item_id($lesson_id);
			if ($course_id > 0) {
				return $course_id;
			}
		}

		return $fallback_course_id;
	}

	private function add_meta_box($args = array()) {
		if (is_array($args) && !empty($args)) {
			call_user_func_array('add_meta_box', $args);
		}
	}

	public function add_meta_boxes() {
		$this->options_metabox();
	}

	public function options_metabox() {
		$this->add_meta_box(array(
			'lesson-options-class',
			esc_html__('Lesson Options', 'lesson-options'),
			array($this, 'options_metabox_html'),
			TL_LESSON_CPT,
			'side',
			'default',
		));
	}

	public function post_meta_request_params($args, $request) {
		$args += array(
			'meta_key' => $request['meta_key'],
			'meta_value' => $request['meta_value'],
			'meta_query' => $request['meta_query'],
		);

		return $args;
	}

	public function options_metabox_html($post = null) {
		$metadata = $this->metadata_repository()->get($post->ID);
		$fallback_course_id = isset($_GET['courseid']) ? absint(wp_unslash($_GET['courseid'])) : absint(get_post_meta($post->ID, 'tl_course_id', true));
		$resolved_course_id = $this->resolve_course_id_for_lesson($post->ID, $fallback_course_id);
		$resolved_course = $resolved_course_id > 0 ? get_post($resolved_course_id) : null;

		echo '<h4>Course</h4>';
		if (!empty($resolved_course) && isset($resolved_course->post_title)) {
			echo '<p>' . esc_html($resolved_course->post_title) . '</p>';
		} else {
			echo '<p>' . esc_html__('No linked course found', 'lesson-options') . '</p>';
		}
		echo '<input type="hidden" name="tl_course_id" value="' . esc_attr($resolved_course_id) . '" />';
		wp_nonce_field( 'save_lesson_lti_options', 'lesson_lti_nonce' );
		?>
		<h4>Tiny LXP Deep Linking</h4>
		<div style="width: 100%;margin-top:-10px">
		 <input type="text" id="lti_tool_url" name="lti_tool_url" value="<?php echo esc_attr($metadata->lti_tool_url); ?>" style="width: 100%;" />
		 <input type="hidden" id="lti_tool_code" name="lti_tool_code" value="<?php echo esc_attr($metadata->lti_tool_code); ?>" style="width: 100%;" />
		 <input type="hidden" id="lti_content_title" name="lti_content_title" value="<?php echo esc_attr($metadata->lti_content_title); ?>" style="width: 100%;" />
		 <input type="hidden" id="lti_custom_attr" name="lti_custom_attr" value="<?php echo esc_attr($metadata->lti_custom_attr); ?>" style="width: 100%;" />
		 <input type="hidden" id="lti_post_attr_id" name="lti_post_attr_id" value="<?php echo esc_attr($metadata->lti_post_attr_id); ?>" style="width: 100%;" />
		</div>
		<div id="preview_lit_connections" style="width: 100%;display: inline-block;margin-top: 10px;">
			<div class="preview button" href="#">Select Content<span class="screen-reader-text"> (opens in a new tab)</span></div>
		</div>
		<?php
	}

	public function save_tl_post( $post_id = null, $post = null ) {
		$post_id = absint( $post_id );
		if ( $post_id <= 0 ) {
			return;
		}
		if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}
		if ( empty( $post ) || ! isset( $post->post_type ) || $post->post_type !== TL_LESSON_CPT ) {
			return;
		}
		if ( ! isset( $_POST['lesson_lti_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['lesson_lti_nonce'] ) ), 'save_lesson_lti_options' ) ) {
			return;
		}
		$posted_course_id = isset( $_POST['tl_course_id'] ) ? absint( wp_unslash( $_POST['tl_course_id'] ) ) : 0;
		$resolved_course_id = $this->resolve_course_id_for_lesson( $post_id, $posted_course_id );
		$metadata_values = array(
			'lti_tool_url'      => isset( $_POST['lti_tool_url'] )      ? sanitize_text_field( wp_unslash( $_POST['lti_tool_url'] ) )      : '',
			'lti_tool_code'     => isset( $_POST['lti_tool_code'] )     ? sanitize_text_field( wp_unslash( $_POST['lti_tool_code'] ) )     : '',
			'lti_content_title' => ( isset( $_POST['lti_content_title'] ) && $_POST['lti_content_title'] !== '' ) ? sanitize_text_field( trim( wp_unslash( $_POST['lti_content_title'] ) ) ) : 'Section',
			'lti_custom_attr'   => isset( $_POST['lti_custom_attr'] )   ? sanitize_text_field( wp_unslash( $_POST['lti_custom_attr'] ) )   : '',
			'lti_post_attr_id'  => isset( $_POST['lti_post_attr_id'] )  ? sanitize_text_field( wp_unslash( $_POST['lti_post_attr_id'] ) )  : '',
		);
		$this->metadata_repository()->update_from_array( $post_id, $metadata_values );
		if ( $resolved_course_id != get_post_meta( $post_id, 'tl_course_id', true ) ) {
			$this->metadata_repository()->update_from_array( $post_id, array( 'lti_course_id' => '' ) );
		}
		update_post_meta( $post_id, 'tl_course_id', $resolved_course_id );
	}

	public function insert_post_api( $post, $request ) {
		if ( ! isset( $request['meta'] ) || ! is_array( $request['meta'] ) ) {
			return;
		}
		$meta = $request['meta'];
		$requested_course_id = isset( $meta['tl_course_id'] ) ? absint( $meta['tl_course_id'] ) : 0;
		$course_id = $this->resolve_course_id_for_lesson( $post->ID, $requested_course_id );
		update_post_meta( $post->ID, 'tl_course_id', $course_id );
		$this->metadata_repository()->update_from_array( $post->ID, array(
			'lti_content_id'    => isset( $meta['lti_content_id'] )    ? sanitize_text_field( $meta['lti_content_id'] )    : '',
			'lti_tool_url'      => isset( $meta['lti_tool_url'] )      ? sanitize_text_field( $meta['lti_tool_url'] )      : '',
			'lti_tool_code'     => isset( $meta['lti_tool_code'] )     ? sanitize_text_field( $meta['lti_tool_code'] )     : '',
			'lti_custom_attr'   => isset( $meta['lti_custom_attr'] )   ? sanitize_text_field( $meta['lti_custom_attr'] )   : '',
			'lti_content_title' => isset( $meta['lti_content_title'] ) ? sanitize_text_field( $meta['lti_content_title'] ) : '',
			'lti_post_attr_id'  => isset( $meta['lti_post_attr_id'] )  ? sanitize_text_field( $meta['lti_post_attr_id'] )  : '',
			'lti_course_id'     => isset( $meta['lti_course_id'] )     ? sanitize_text_field( $meta['lti_course_id'] )     : '',
		) );
	}

	public function provide_lti_launch_metadata($launch_metadata, $post, $deeplink, $ok, $reason) {
		if ($deeplink || $ok || empty($post) || !isset($post->post_type) || $post->post_type !== LP_LESSON_CPT) {
			return $launch_metadata;
		}

		$metadata = $this->metadata_repository()->get($post->ID);
		if (empty($metadata->lti_tool_code) || empty($metadata->lti_post_attr_id)) {
			return $launch_metadata;
		}

		return array_merge(array(
			'tool' => $metadata->lti_tool_code,
			'title' => $metadata->lti_content_title,
			'url' => $metadata->lti_tool_url,
			'custom' => $metadata->lti_custom_attr,
			'id' => $metadata->lti_post_attr_id,
			'target' => 'embed',
		), is_array($launch_metadata) ? $launch_metadata : array());
	}

	public function filter_template_include($template, $request_uri, $has_assignment, $userdata) {
		$user_roles = isset($userdata->roles) && is_array($userdata->roles) ? $userdata->roles : array();
		$is_student = in_array('lxp_student', $user_roles, true);
		$handles_lesson_request = strpos($request_uri, '/lessons/') !== false || strpos($request_uri, '/quizzes/') !== false;

		if ($handles_lesson_request && $has_assignment && $is_student) {
			return $this->learner_assignment_template_path;
		}

		return $template;
	}

	public function render_lti_lesson_embed() {
		if (!is_singular(LP_LESSON_CPT) || isset($_GET['assignment_id'])) {
			return;
		}

		$post = get_post();
		if (empty($post) || !isset($post->ID)) {
			return;
		}

		$metadata = $this->metadata_repository()->get($post->ID);
		if (empty($metadata->lti_post_attr_id)) {
			return;
		}

		$launch_url = add_query_arg(
			array(
				Tiny_LXP_Platform::get_plugin_name() => '',
				'post' => $post->ID,
				'id' => $metadata->lti_post_attr_id,
			),
			site_url()
		);

		echo '<div class="tinylxp-lp-lesson-embed" style="margin-top:16px;">';
		echo '<iframe style="border:none;width:100%;height:706px;" src="' . esc_url($launch_url) . '" allowfullscreen></iframe>';
		echo '</div>';
	}
}