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
}