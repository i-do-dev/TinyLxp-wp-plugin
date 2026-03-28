<?php

class TL_LearnPress_Course_Extension {
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