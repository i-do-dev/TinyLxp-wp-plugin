<?php
	$assignment_id = isset($_GET['assignment_id']) ? intval($_GET['assignment_id']) : 0;
	if (!$assignment_id) {
		echo '<p>' . esc_html__('No assignment selected.', 'text-domain') . '</p>';
		return;
	}

	// Get assignment
	$assignment = lxp_get_assignment($assignment_id);
	if (!$assignment) {
		echo '<p>' . esc_html__('Assignment not found.', 'text-domain') . '</p>';
		return;
	}

	// Get course & lesson
	$course_id = get_post_meta($assignment->ID, 'course_id', true);
	$lxp_lesson_id = get_post_meta($assignment->ID, 'lxp_lesson_id', true);

	$course = get_post($course_id);
	$lxp_lesson_post = get_post($lxp_lesson_id);
	$cache_key = "section_name_{$lxp_lesson_id}";
	$section_name = wp_cache_get($cache_key, 'lxp');
	if (false === $section_name) {
		$section_name = $wpdb->get_var($wpdb->prepare(
			"SELECT s.section_name
			FROM {$wpdb->prefix}learnpress_sections s
			INNER JOIN {$wpdb->prefix}learnpress_section_items si ON s.section_id = si.section_id
			WHERE si.item_id = %d",
			$lxp_lesson_id
		));
		$section_name = $section_name ?: esc_html__('Uncategorized', 'text-domain');
		wp_cache_set($cache_key, $section_name, 'lxp', 3600); // 1 hour
	}

	if (!$course || !$lxp_lesson_post) {
		echo '<p>' . esc_html__('Course or lesson not found.', 'text-domain') . '</p>';
		return;
	}
	$post = $lxp_lesson_post;
	$_GET['post'] = $post->ID;
    $content = get_post_meta($post->ID);
    $attrId =  isset($content['lti_post_attr_id'][0]) ? $content['lti_post_attr_id'][0] : "";
    $title =  isset($content['lti_content_title'][0]) ? $content['lti_content_title'][0] : "";
    $toolCode =  isset($content['lti_tool_code'][0]) ? $content['lti_tool_code'][0] : "";
    $customAttr =  isset($content['lti_custom_attr'][0]) ? $content['lti_custom_attr'][0] : "";
    $toolUrl =  isset($content['lti_tool_url'][0]) ? $content['lti_tool_url'][0] : "";
    $plugin_name = Tiny_LXP_Platform::get_plugin_name();
    $content = '<p>' . $post->post_content . '</p>';
    if ($attrId) {
        $content .= '<p> [' . $plugin_name . ' tool=' . $toolCode . ' id=' . $attrId . ' title=\"' . $title . '\" url=' . $toolUrl . ' custom=' . $customAttr . ']' . "" . '[/' . $plugin_name . ']  </p>';
    }
    
    $queryParam = '';

	// Get submission
	$student_post = lxp_get_student_post(get_current_user_id());
	if (!$student_post) {
		echo '<p>' . esc_html__('Student not found.', 'text-domain') . '</p>';
		return;
	}
	$assignment_submission = lxp_get_assignment_submissions($assignment->ID, $student_post->ID);

	// Sanitize output
	$course_title = esc_html($course->post_title);
	$section_name = esc_html($section_name);
	$assets_src = content_url().'/plugins/TinyLxp-wp-plugin/lms/templates/tinyLxpTheme/treks-src/';

	if ( ! wp_is_block_theme() ) {
		do_action( 'learn-press/template-header' );
	?>
	<style>
		.header-width-fixer {
			display: none !important;
		}
		.date-time {
			margin-left: 10px;
			font-family: "Arial";
			font-style: normal;
			font-weight: 400;
			font-size: 16px;
			padding: 4px 8px;
			line-height: 24px;
			background: rgba(31, 165, 212, 0.16);
			border-radius: 8px;
			color: #0b5d7a;
		}
		.time-date-box {
			display: flex;
		}
		.to-text {
			color: #757575;
			background: none;
		}
		.course_nav_path
		{
			display: flex;
			align-items: center;
			flex-wrap: wrap;
			gap: 10px;
			/* padding-bottom: 24px; */
		}
		.practice_flx {
			display: flex;
			gap: 16px;
			color: #979797
		}
		.practice_flx img {
			width: 23px;
			height: 20px;
		}
		.post-content
		{
			margin: -1% 13% 0 13%;
			position: relative;
		}
	</style>
	<?php
	echo do_shortcode("[etb_template id='14946']");
}
?>
	<div class="post-content">
		<div class="entry-content">
			<div class="course_nav_path">
				<div class="practice_flx">
					<img src="<?= $assets_src; ?>/assets/img/nav_Treks.svg" />
					<p class="practice_text">My Course</p>
				</div>
				<div class="practice_flx">
					<img src="<?= $assets_src; ?>/assets/img/bc_arrow_right.svg" />
					<p class="practice_text"><?= $course_title ?></p>
				</div>
				<div class="practice_flx">
					<img src="<?= $assets_src; ?>/assets/img/bc_arrow_right.svg" />
					<p class="practice_text"><?= $section_name ?></p>
				</div>
				<div class="practice_flx">
					<img src="<?= $assets_src; ?>/assets/img/bc_arrow_right.svg" />
					<p class="practice_text"><?php the_title(); ?></p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="time-date-box">
						<p class="date-time"><span id="assignment_day"><?= date("l", strtotime($assignment->start_date)); ?></span>, <span id="assignment_month"><?= date("M", strtotime($assignment->start_date)); ?></span> <span id="assignment_date"><?= date("d", strtotime($assignment->start_date)); ?>,&nbsp;<?= date("Y", strtotime($assignment->start_date)); ?></span>&nbsp;<?= date("h:i A", strtotime($assignment->start_time)); ?></p>
						<p class="date-time to-text">To</p>
						<p class="date-time"><span id="assignment_day"><?= date("l", strtotime($assignment->end_date)); ?></span>, <span id="assignment_month"><?= date("M", strtotime($assignment->end_date)); ?></span> <span id="assignment_date"><?= date("d", strtotime($assignment->end_date)); ?>,&nbsp;<?= date("Y", strtotime($assignment->end_date)); ?></span>&nbsp;<?= date("h:i A", strtotime($assignment->end_time)); ?></p>
					</div>
				</div>
			</div>
			<div class="mptt-shortcode-wrapper">
				<iframe style="border: none;width: 100%;height: 706px;" ></iframe>
			</div>
		</div>
	</div>
<?php
/**
 * @since 4.0.0
 *
 * @see   LP_Template_General::template_footer()
 */
if ( ! wp_is_block_theme() ) {
	do_action( 'learn-press/template-footer' );
}
