<?php

class TL_LearnPress_Lesson_Extension {
	private $lti_metadata_repository = null;
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
		$args = array(
			'post_type' => TL_COURSE_CPT,
			'orderby' => 'ID',
			'post_status' => 'publish,draft',
			'order' => 'DESC',
			'posts_per_page' => -1,
		);
		$courses = get_posts($args);
		$selectedCourse = isset($_GET['courseid']) ? $_GET['courseid'] : get_post_meta($post->ID, 'lp_course_id', true);
		$disabled = ($selectedCourse && $selectedCourse > 0) ? 'disabled' : '';
		$output = '  <h4>Select Course</h4>';
		$output .= '<select '.$disabled.' name="tl_course_id" style="margin-top:-10px"> 
               <option disabled selected>Select a course</option>';
		foreach ($courses as $course) {
			if ($selectedCourse == $course->ID) {
				$selected = 'selected';
			} else {
				$selected = '';
			}
			$output .= '<option value="'.$course->ID .'" '.$selected.' >'. $course->post_title .' </option>';
		}
		$output .= '</select>';
		$output .= ($selectedCourse && $selectedCourse > 0) ? '<input type="hidden" name="tl_course_id" value="'.$selectedCourse.'" />' : '';
		echo $output;
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

	public function save_tl_post($post_id = null) {
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_type']) && TL_LESSON_CPT == $_POST['post_type']) {
			$posted_course_id = isset($_POST['tl_course_id']) ? intval(trim(wp_unslash($_POST['tl_course_id']))) : 0;
			$metadata_values = array(
				'lti_tool_url' => isset($_POST['lti_tool_url']) ? wp_unslash($_POST['lti_tool_url']) : '',
				'lti_tool_code' => isset($_POST['lti_tool_code']) ? wp_unslash($_POST['lti_tool_code']) : '',
				'lti_content_title' => (isset($_POST['lti_content_title']) && $_POST['lti_content_title'] != '') ? trim(wp_unslash($_POST['lti_content_title'])) : 'Section',
				'lti_custom_attr' => isset($_POST['lti_custom_attr']) ? wp_unslash($_POST['lti_custom_attr']) : '',
				'lti_post_attr_id' => isset($_POST['lti_post_attr_id']) ? wp_unslash($_POST['lti_post_attr_id']) : '',
			);
			$this->metadata_repository()->update_from_array($post_id, $metadata_values);
			if ($posted_course_id != get_post_meta($post_id, 'tl_course_id', true)) {
				$this->metadata_repository()->update_from_array($post_id, array('lti_course_id' => ''));
			}
			update_post_meta($post_id, 'tl_course_id', $posted_course_id);
		}
	}

	public function insert_post_api($post, $request) {
		if (isset($request['meta'])) {
			if (isset($request['meta']['lti_content_id'])) {
				$course_id = isset($request['meta']['tl_course_id']) ? intval(trim($request['meta']['tl_course_id'])) : 0;
				update_post_meta($post->ID, 'tl_course_id', $course_id);
				$this->metadata_repository()->update_from_array($post->ID, array(
					'lti_content_id' => isset($request['meta']['lti_content_id']) ? $request['meta']['lti_content_id'] : '',
					'lti_tool_url' => isset($request['meta']['lti_tool_url']) ? $request['meta']['lti_tool_url'] : '',
					'lti_tool_code' => isset($request['meta']['lti_tool_code']) ? $request['meta']['lti_tool_code'] : '',
					'lti_custom_attr' => isset($request['meta']['lti_custom_attr']) ? $request['meta']['lti_custom_attr'] : '',
					'lti_content_title' => isset($request['meta']['lti_content_title']) ? $request['meta']['lti_content_title'] : '',
					'lti_post_attr_id' => isset($request['meta']['lti_post_attr_id']) ? $request['meta']['lti_post_attr_id'] : '',
					'lti_course_id' => isset($request['meta']['lti_course_id']) ? $request['meta']['lti_course_id'] : '',
				));
			}
		}
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