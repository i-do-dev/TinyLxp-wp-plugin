<?php

class TL_LearnPress_Section_Repository implements TL_Section_Repository_Interface {

	private $wpdb;
	private $sections_table;
	private $section_items_table;

	public function __construct($wpdb_instance = null) {
		global $wpdb;
		$this->wpdb = $wpdb_instance ? $wpdb_instance : $wpdb;
		$this->sections_table = $this->wpdb->prefix . 'learnpress_sections';
		$this->section_items_table = $this->wpdb->prefix . 'learnpress_section_items';
	}

	public function get_sections_by_section_course_id($course_id) {
		$query = $this->wpdb->prepare(
			"SELECT section_id, section_name FROM {$this->sections_table} WHERE section_course_id = %d",
			intval($course_id)
		);

		return $this->wpdb->get_results($query);
	}

	public function get_lessons_by_section_id($section_id) {
		$query = $this->wpdb->prepare(
			"SELECT p.ID, p.post_title
			 FROM {$this->wpdb->prefix}posts AS p
			 INNER JOIN {$this->section_items_table} AS si ON p.ID = si.item_id
			 WHERE si.section_id = %d",
			intval($section_id)
		);

		return $this->wpdb->get_results($query);
	}

	public function get_sections_by_course_id($course_id) {
		$query = $this->wpdb->prepare(
			"SELECT * FROM {$this->sections_table} WHERE course_id = %d",
			intval($course_id)
		);

		return $this->wpdb->get_results($query);
	}

	public function get_section_by_id($section_id) {
		$query = $this->wpdb->prepare(
			"SELECT * FROM {$this->sections_table} WHERE id = %d",
			intval($section_id)
		);

		$records = $this->wpdb->get_results($query);
		if (is_array($records) && !empty($records) && isset($records[0]->content)) {
			$records[0]->content = stripslashes($records[0]->content);
		}

		return $records;
	}

	public function update_section($section_id, $title, $content, $sort) {
		return $this->wpdb->update(
			$this->sections_table,
			array(
				'content' => $content,
				'title' => $title,
				'sort' => intval($sort),
			),
			array('id' => intval($section_id)),
			array('%s', '%s', '%d'),
			array('%d')
		);
	}

	public function create_section($course_id, $title, $content, $sort) {
		$this->wpdb->insert(
			$this->sections_table,
			array(
				'course_id' => intval($course_id),
				'title' => $title,
				'type' => 'content',
				'content' => $content,
				'sort' => intval($sort),
			),
			array('%d', '%s', '%s', '%s', '%d')
		);

		return $this->wpdb->insert_id;
	}

	public function delete_section($section_id) {
		return $this->wpdb->delete(
			$this->sections_table,
			array('id' => intval($section_id)),
			array('%d')
		);
	}

	public function get_section_name_by_item_id($item_id) {
		$query = $this->wpdb->prepare(
			"SELECT s.section_name
			 FROM {$this->sections_table} s
			 INNER JOIN {$this->section_items_table} si ON s.section_id = si.section_id
			 WHERE si.item_id = %d",
			intval($item_id)
		);

		return $this->wpdb->get_var($query);
	}
}
