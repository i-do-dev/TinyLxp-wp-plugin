<?php

require_once( LMS__PLUGIN_DIR . 'lms-rest-apis/districts.php' );
require_once( LMS__PLUGIN_DIR . 'lms-rest-apis/teachers.php' );
require_once( LMS__PLUGIN_DIR . 'lms-rest-apis/students.php' );
require_once( LMS__PLUGIN_DIR . 'lms-rest-apis/schools.php' );
require_once( LMS__PLUGIN_DIR . 'lms-rest-apis/classes.php' );
require_once( LMS__PLUGIN_DIR . 'lms-rest-apis/groups.php' );
require_once( LMS__PLUGIN_DIR . 'lms-rest-apis/assignments.php' );
require_once( LMS__PLUGIN_DIR . 'lms-rest-apis/assignment-submissions.php' );
require_once( LMS__PLUGIN_DIR . 'lms-rest-apis/courses.php' );
require_once( LMS__PLUGIN_DIR . 'lms-rest-apis/edlink-apis.php' );

class LMS_REST_API
{

	/**
	 * Register the REST API routes.
	 */
	public static function init()
	{
		if (!function_exists('register_rest_route')) {
			// The REST API wasn't integrated into core until 4.4, and we support 4.0+ (for now).
			return false;
		}
		
		Rest_Lxp_District::init();
		Rest_Lxp_Teacher::init();
		Rest_Lxp_Student::init();
		Rest_Lxp_School::init();
		Rest_Lxp_Class::init();
		Rest_Lxp_Group::init();
		Rest_Lxp_Assignment::init();
		Rest_Lxp_Assignment_Submission::init();
		Rest_Lxp_Course::init();
		Rest_Lxp_Edlink_API::init();

		register_rest_route('lms/v1', '/scores', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'store_grade'),
				'permission_callback' => '__return_true',
			),
		));
		register_rest_route('lms/v1', '/token', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'return_token'),
				'permission_callback' => '__return_true',
			),
		));
		register_rest_route('lms/v1', '/get/playlists', array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array('LMS_REST_API', 'get_playlists'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/store/trek/section', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'store_course_section'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/get/trek/section', array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array('LMS_REST_API', 'get_course_section'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/delete/trek/section', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'delete_course_section'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/get/all/treks', array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array('LMS_REST_API', 'get_all_courses'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/get/all/trek/sections', array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array('LMS_REST_API', 'get_all_course_sections'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/store/trek/event', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'store_course_event'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/get/all/trek/events', array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array('LMS_REST_API', 'get_all_course_events'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/update/trek/event', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'update_course_event'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/get/trek/event', array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array('LMS_REST_API', 'get_course_event'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/delete/trek/event', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'delete_course_event'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/delete/school/lxp/user', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'delete_school_lxp_user'),
				'permission_callback' => '__return_true',
			),
		));
		register_rest_route('lms/v1', '/trek/assigned/students', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'course_assigned_students'),
				'permission_callback' => '__return_true',
			),
		));
		register_rest_route('lms/v1', '/trek/section/assigned/students', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'course_section_assigned_students'),
				'permission_callback' => '__return_true',
			),
		));
		register_rest_route('lms/v1', '/trek/section/unassign/student', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'course_unassign_student'),
				'permission_callback' => '__return_true',
			),
		));
		register_rest_route('lms/v1', '/trek/section/unassigned/students', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'course_get_unassigned_students'),
				'permission_callback' => '__return_true',
			),
		));
		register_rest_route('lms/v1', '/trek/section/assigned/students/store', array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array('LMS_REST_API', 'course_section_assigned_students_store'),
				'permission_callback' => '__return_true',
			),
		));

		register_rest_route('lms/v1', '/trek/search', array(
			array(
				'methods' => WP_REST_Server::ALLMETHODS,
				'callback' => array('LMS_REST_API', 'course_search'),
				'permission_callback' => '__return_true',
			),
		));
	}

	public static function course_search($request) {
		$keyword = $request->get_param('search');
		$user_post_id = $request->get_param('user_post_id');
		$user_role = $request->get_param('user_role');
		
		$args = array('headers' => array(
			'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIzNDMiLCJqdGkiOiI5MDcwOTk0YmIxMDA3NGJiMjAyNjJiYjFkMzZlZmIzMjk4MGZmNTBlZjg2MjQyYWVjMGU1MmU5OTYzYTM5ZDgwODU4MDlhNTEyNTcyZDZkNyIsImlhdCI6MTY4NDA3MzQ3Ny4xNzAyODUsIm5iZiI6MTY4NDA3MzQ3Ny4xNzAyOSwiZXhwIjoxNzE1Njk1ODc3LjE2MDYxNiwic3ViIjoiMiIsInNjb3BlcyI6W119.Lvu-Ar22TFuDbCg0X1yg2dXtdUBo-3F4gXvZx_U2I4z1yEYyIbi81BVMV_KhMJhlZ77_W7oSJYFfTP6LXpMUdESoNL8rqb0POqSv4mOh2whAARfOvev34KGHijbpxXP2qgup8BIoh5yZWwKhYEP1yqrk1MdGdYlo6jEwXXn0PnpeXLdC5f-OCqCFfwJGMjhoTQENrvW50-WoQEpA5ziSAw98D1Jy6Q-KqN-PqIcTZYZ6QGOIfxyoJrSDhky8TbF_aT_QA124Q8b382VvcltOTX0m9TYBge-vQdHn3anE-J0czLTa7is6EHHOmX6DM2eobj96FtffiIsRi_DZ11EIMzbXMA1t2PgUMjybqWSPh441CSwiawSe321r4vB8bVbJXYjiBHEgHquYCmREeMpId5sgGn4ddKC8LinqVazmsIPgE6_ifW09Udp_XEPdB4bevUXtCI1KZV349a7DeI6UPj1IDA0rkxtMPzRvT-G9bghDsWjoTZU0SNDIsIdJGRvCn6KjIKu3PgA_s8T5s5tsU0VWDUO1UrKFl0_A9EsW8z2icC39qobFp-J_kFagJKihefmsMZQd3adVNjukG5XjJjL8qnGg6uYzAV7_RBdDjLjXe2Z30O1Ly576T-WqIWoof5cFAkLcRF96l7Wywg46fwkDWksw8jgiE6_-JF3uRkI'
		));
		$response = wp_remote_get(CURRIKI_STUDIO_HOST . 'api/api/v1/activities/search-h5p-keywords?size=100&keyword='.$keyword, $args);
		$code = wp_remote_retrieve_response_code($response);
		if ($code === 200 && strlen($keyword) > 0) {
			$data = json_decode(wp_remote_retrieve_body($response));
			$courses_record = self::courses_record();
			$results = array();
			foreach ($data->activities->data as $activity) {
				$activity_id = $activity->id;
				$course_data_arr = array_filter($courses_record, function($course_record) use ($activity_id) {
					return array_filter($course_record['lessons_ids'], function($lesson) use ($activity_id) { return $lesson['activity_id'] == $activity_id; });
				});

				if ($course_data_arr) {
					$course_id = array_keys($course_data_arr)[0];
					$course_post = get_post($course_id);
					// $course_data_arr["lessons_ids"]
					$lesson_record = array_filter($course_data_arr[$course_id]['lessons_ids'], function($lesson) use ($activity_id) { return $lesson['activity_id'] == $activity_id; });
					$lesson_id = array_keys($lesson_record)[0];
					$lesson_post = get_post($lesson_id);
					array_push($results, array(
						"course_id" => $course_post->ID,
						"course_title" => $course_post->post_title,
						"course_link" => get_permalink($course_post->ID),
						"lesson_id" => $lesson_post->ID,
						"lesson_title" => $lesson_post->post_title,
						"lesson_link" => get_permalink($lesson_post->ID),
						"slides" => $activity->h5p_content->matching_slides
					));
				}
			}
			
			if ($user_post_id && $user_role && $user_role === 'student') {
				$assignment_query = new WP_Query( array( 'post_type' => TL_ASSIGNMENT_CPT, 'post_status' => array( 'publish' ), 'posts_per_page'   => -1, 'meta_query' => array( array('key' => 'lxp_student_ids', 'value' => $user_post_id, 'compare' => 'IN') ) ) );
				$assignments = $assignment_query->get_posts();
				$assignments_data = array_map(function($assignment) { 
					global $wpdb;
					$course_id = get_post_meta($assignment->ID, 'course_id', true);
					$course_section_id = get_post_meta($assignment->ID, 'course_section_id', true);
					$course_section = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}learnpress_sections WHERE id={$course_section_id}");
					return array('course_id' => $course_id, 'course_section_id' => $course_section_id, 'section_title' => $course_section->title, 'assignment_id' => $assignment->ID ); 
				}, $assignments);

				$results = array_filter($results, function($result) use ($assignments_data) {
					$val = array_filter($assignments_data, function($assignment_data) use ($result) {
						return ($assignment_data['course_id'] == $result['course_id']) && ($assignment_data['section_title'] == $result['lesson_title']);
					});
					return count($val) > 0;
				});

				$results = array_map(function($result) use ($assignments_data) {
					$asg = array_filter($assignments_data, function($assignment_data) use ($result) {
						return ($assignment_data['course_id'] == $result['course_id']) && ($assignment_data['section_title'] == $result['lesson_title']);
					});
					if (count($asg) > 0) {
						$result['assignment_id'] = $asg[array_keys($asg)[0]]['assignment_id'];
					}
					return $result;
				}, $results);
			}

			return wp_send_json_success($results);
		} else {
			return wp_send_json_error("error on search request.");
		}
	}

	public static function courses_record() {
		$courses = get_posts(array('posts_per_page' => -1, 'post_type' => TL_COURSE_CPT));
		$courses_record = array();
		foreach ($courses as $single_course) {
			$courseId = get_post_meta($single_course->ID, 'tl_course_id', true);
			$lesson_query = new WP_Query( array( 
				'post_type' => TL_LESSON_CPT, 
				'post_status' => array( 'publish' ),
				'posts_per_page'   => -1,        
				'meta_query' => array(
					array('key' => 'tl_course_id', 'value' => $courseId, 'compare' => '=')
				)
			) );
			$lessons_ids = array();
			foreach ($lesson_query->get_posts() as $lesson) {
				$tool_url_parts = parse_url(get_post_meta($lesson->ID, 'lti_tool_url', true));
				$activity_id = 0;
				if (isset($tool_url_parts['query'])) {
					$q = [];
					parse_str($tool_url_parts['query'], $q);
					$activity_id = isset($q['activity']) ? $q['activity'] : 0;
				}
				$lessons_ids[$lesson->ID] = array('activity_id' => $activity_id);
			}
			$courses_record[$single_course->ID] = array('course_id' => $courseId, 'lessons_ids' => $lessons_ids);
		}
		return $courses_record;
	}

	public static function course_section_assigned_students_store($request = null) {
		$event_store_response = self::store_course_event($request);
		$event_store_response['id'];
		
		$student_ids = $request->get_param('student_ids');
		foreach ($student_ids as  $student_id) {
			global $wpdb;
			$table = $wpdb->prefix.'student_assignments';
			$data = array('student_id' => $student_id, 'assignment_id' => $event_store_response['id']);
			$format = array('%d','%d');
			$wpdb->insert($table,$data,$format);
		}

		return self::course_section_assigned_students($request);
	}

	public static function course_get_unassigned_students($request = null) {
		$assigned_users = self::course_section_assigned_students($request);
		global $wpdb;
		$user_ids = array();
		foreach ($assigned_users as $user) {
			array_push($user_ids, $user->id);
		}
		$sql = 'SELECT * FROM ' . $wpdb->prefix . 'students';
		if (count($user_ids) > 0) {
			$sql .= ' WHERE id NOT IN (' . implode(', ', $user_ids) . ')';
		}
		return $wpdb->get_results($sql);
	}

	public static function course_unassign_student($request = null) {
		$student_assignment_id = $request->get_param('student_assignment_id');
		
		global $wpdb;
		$wpdb->delete(
			$wpdb->prefix . 'student_assignments', 		// table name with dynamic prefix
			['id' => $student_assignment_id], 						// which id need to delete
			['%d'], 							// make sure the id format
		);
		return self::course_section_assigned_students($request);
	}

	public static function course_section_assigned_students($request = null) {
		$course_section_id = $request->get_param('trek_section_id');
		$teacher_id = $request->get_param('teacher_id');
		global $wpdb;
		$query = "SELECT {$wpdb->prefix}students.*, {$wpdb->prefix}student_assignments.id as student_assignment_id FROM {$wpdb->prefix}students
			  JOIN {$wpdb->prefix}student_assignments ON {$wpdb->prefix}student_assignments.student_id = {$wpdb->prefix}students.id
			  JOIN {$wpdb->prefix}trek_events ON {$wpdb->prefix}trek_events.id = {$wpdb->prefix}student_assignments.assignment_id
			  WHERE {$wpdb->prefix}trek_events.trek_section_id = \"{$course_section_id}\" AND {$wpdb->prefix}trek_events.user_id = \"{$teacher_id}\"
			";
		return $wpdb->get_results($query);
	}

	public static function course_assigned_students($request = null) {
		$event_id = $request->get_param('event_id');
		global $wpdb;
		$query = "SELECT {$wpdb->prefix}students.* FROM {$wpdb->prefix}students
					JOIN {$wpdb->prefix}student_assignments ON {$wpdb->prefix}student_assignments.student_id = {$wpdb->prefix}students.id
					JOIN {$wpdb->prefix}trek_events ON {$wpdb->prefix}trek_events.id = {$wpdb->prefix}student_assignments.assignment_id
					WHERE {$wpdb->prefix}trek_events.id = {$event_id}
				";
		return $wpdb->get_results($query);
	}

	public static function return_token($request = null)
	{
		return [
			"access_token" => "12312u3hufbvfb29rb932b192e",
			"token_type" => "Bearer",
			"expires_in" => 12312312312,
			"scope" => array()
		];
	}

	public static function store_grade($request = null)
	{
		global $wpdb;
		$json = file_get_contents('php://input');
		$data = json_decode($json);
		parse_str($_SERVER['QUERY_STRING'], $queries);

		$respones = $wpdb->get_results("SELECT id FROM " . $wpdb->prefix . "tiny_lms_grades WHERE user_id = " . $data->userId . "
		AND lesson_id= " . $queries['lesson']);
		if ($respones) {
			$wpdb->query("UPDATE " . $wpdb->prefix . "tiny_lms_grades SET score = " . $data->scoreGiven . " where id=" . $respones[0]->id);
		} else {
			$wpdb->insert($wpdb->prefix . 'tiny_lms_grades', array(
				'lesson_id' => $queries['lesson'],
				'user_id' => $data->userId,
				'score' => $data->scoreGiven,
			));
		}
	}

	public static function get_playlists($request = null)
	{
		global $wpdb;
		$playlists = get_post_meta($_GET['course_id'], "lxp_sections", true);
		$playlists = json_decode($playlists);
		if (!is_array($playlists)) {
			$playlists = array();
		}

		if (!boolval(count($playlists))) {
			$playlists = ["Overview", "Recall", "Practice A", "Practice B", "Apply"];
		}

		$records = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "learnpress_sections WHERE course_id = " .  $_GET['post_id']);
		foreach ($records as $record) {
			foreach ($playlists as $key => $playlist) {
				if (trim($record->title) == trim($playlist)) {
					unset($playlists[$key]);
				}
			}
		}
		
		return array_values($playlists);
	}

	public static function store_course_section($request = null)
	{
		$post = get_post($_POST['post_id']);
		if ($post->post_status == "auto-draft") {
			return 0;
		}
		global $wpdb;
		if ($_POST['section_id'] != 0) {
			$wpdb->query("UPDATE " . $wpdb->prefix . "learnpress_sections SET content = '" . $_POST['content'] . "', title='" . $_POST['title'] . "', sort=". intval($_POST['sort']) ." where id=" . $_POST['section_id']);
			$recordId = $_POST['section_id'];   //update using wpdb->update
		} else {
			$wpdb->insert($wpdb->prefix . 'learnpress_sections', array(
				'course_id' => $_POST['post_id'],
				'title' => $_POST['title'],
				'type' => 'content',
				'content' => $_POST['content'],
				'sort' => intval($_POST['sort'])
			));
			$recordId = $wpdb->insert_id;
		}
		return $recordId;
	}

	public static function get_course_section($request = null)
	{
		global $wpdb;
		$respones = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "learnpress_sections WHERE id = " . $_GET['section_id']);
		$respones[0]->content = stripslashes($respones[0]->content);
		return $respones;
	}

	public static function delete_course_section($request = null)
	{
		global $wpdb;
		$wpdb->query("DELETE FROM " . $wpdb->prefix . "learnpress_sections WHERE id =" . $_POST['section_id']);
		return [];
	}

	public static function get_all_courses($request = null)
	{
		$args = array(
			'post_type' => TL_COURSE_CPT,
			'orderby'    => 'ID',
			'post_status' => 'publish',
			'order'    => 'DESC',
			'posts_per_page' => -1
		);
		$districts = get_posts($args);
		return $districts;
	}

	public static function get_all_course_sections($request = null)
	{
		global $wpdb;
		$respones = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "learnpress_sections WHERE course_id = " . $_GET['trek_post_id']);
		return $respones;
	}

	public static function store_course_event($request = null)
	{
		$start = $request->get_param('start');
		$end = $request->get_param('end');
		$course_section_id = $request->get_param('trek_section_id');
		$user_id = $request->get_param('user_id');
		if (intval($user_id) == 0) {
			$user_id = 1;
		}

		global $wpdb;
		$wpdb->insert($wpdb->prefix . 'trek_events', array(
			'trek_section_id' => $course_section_id,
			'start' =>  $start,
			'end' =>  $end,
			'user_id' => $user_id
		));

		$data = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "learnpress_sections WHERE id = " . $course_section_id);
		$data[0]->title;
		$data[0]->course_id;
		$coursePost = get_post($data[0]->course_id);
		$response['title'] = $data[0]->title . " - " .  $coursePost->post_title;
		$response['start'] = explode(' ', $start)[0];
		$response['end'] = explode(' ', $end)[0];
		$response['id'] = $wpdb->insert_id;;
		$response['textColor'] = 'white';
		if (strtolower(trim($data[0]->title)) == 'recall') {
			$response['color'] = '#ca2738';
		} elseif (strtolower(trim($data[0]->title)) == 'apply') {
			$response['color'] = '#9fc33b;';
		} elseif (strtolower(trim($data[0]->title)) == 'overview') {
			$response['color'] = '#979797;';
		} else {
			$response['color'] = '#1fa5d4;';
		}
		return $response;
	}

	public static function get_all_course_events($request = null)
	{
		
		return [
			array(
				"start" => "2023-03-19T03:00:00+05:00",
				"end" => "2023-03-19T04:00:00+05:00",
				"title"	=> "Recall",
				"segment" => "recall",
				"trek" => "5.6A Physical Properties of Matter"
			)/* ,
			array(
				"start" => "2023-03-19T03:00:00+05:00",
				"end" => "2023-03-19T04:00:00+05:00",
				"title"	=> "Overview",
				"segment" => "overview",
				"trek" => "5.6A Physical Properties of Matter"
			),
			array(
				"start" => "2023-03-19T03:00:00+05:00",
				"end" => "2023-03-19T04:00:00+05:00",
				"title"	=> "Practice A",
				"segment" => "practice-a",
				"trek" => "5.6A Physical Properties of Matter"
			),
			array(
				"start" => "2023-03-19T03:00:00+05:00",
				"end" => "2023-03-19T04:00:00+05:00",
				"title"	=> "Practice B",
				"segment" => "practice-b",
				"trek" => "5.6A Physical Properties of Matter"
			),
			array(
				"start" => "2023-03-19T03:00:00+05:00",
				"end" => "2023-03-19T04:00:00+05:00",
				"title"	=> "Apply",
				"segment" => "apply",
				"trek" => "5.6A Physical Properties of Matter"
			), */
			
		];
		
		global $wpdb;
		$result = array();
		$response = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "trek_events where user_id=" . $_GET['user_id']);
		foreach ($response as $key => $row) {
			$data = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "learnpress_sections WHERE id = " . $row->trek_section_id);
			if (isset($data[0])) {
				$coursePost = get_post($data[0]->course_id);
				// $response[$key]->title =  $data[0]->title . " - " . $coursePost->post_title;
				// $response[$key]->start = explode(' ', $row->start)[0];
				// $response[$key]->end = explode(' ', $row->end)[0];
				//$response[$key]->id = $row->id;
				//$response[$key]->textColor = 'white';
				//$response[$key]->allDay = false;
				/*
				if (strtolower(trim($data[0]->title)) == 'recall') {
					$response[$key]->color = '#ca2738';
				} elseif (strtolower(trim($data[0]->title)) == 'apply') {
					$response[$key]->color = '#9fc33b;';
				} elseif (strtolower(trim($data[0]->title)) == 'overview') {
					$response[$key]->color = '#979797;';
				} else {
					$response[$key]->color = '#1fa5d4;';
				}
				*/

				$obj = new \stdClass();
				$obj->id = $row->id;
				$obj->textColor = 'white';
				$obj->title = $data[0]->title . " - " . $coursePost->post_title;
				$obj->start = explode(' ', $row->start)[0];
				$obj->end = explode(' ', $row->end)[0]; //2023-02-28T01:30:00
				$obj->allDay=false;	
				if (strtolower(trim($data[0]->title)) == 'recall') {
					$obj->color = '#ca2738';
				} elseif (strtolower(trim($data[0]->title)) == 'apply') {
					$obj->color = '#9fc33b';
				} elseif (strtolower(trim($data[0]->title)) == 'overview') {
					$obj->color = '#979797';
				} else {
					$obj->color = '#1fa5d4';
				}	
				
				$obj->trekTitle = $coursePost->post_title;
				$obj->trekSectionId = $row->trek_section_id;
				$obj->trekSectionTitle = $data[0]->title;
				array_push($result, $obj);
			}
		}

		/* 
		$obj = new \stdClass();
		$obj->title ='event3';
		$obj->start ='2023-02-27T15:30:00';
		$obj->end ='2023-02-27T16:45:00'; //2023-02-28T01:30:00
		$obj->allDay=false;
		array_push($result, $obj);
		 */
		return $result;
	}


	public static function update_course_event($request = null)
	{
		global $wpdb;
		if (isset($_POST['trek_section_id'])) {
			$wpdb->query("UPDATE " . $wpdb->prefix . "trek_events SET start = " . $_POST['start'] . ", end=" . $_POST['end'] . ", trek_section_id=" . $_POST['trek_section_id'] . " where id=" . $_POST['id']);
			$data = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "learnpress_sections WHERE id = " . $_POST['trek_section_id']);
			$coursePost = get_post($data[0]->course_id);
			$response['title'] = $data[0]->title . " - " .  $coursePost->post_title;
			$response['textColor'] = 'white';
			if (strtolower(trim($data[0]->title)) == 'recall') {
				$response['color'] = '#ca2738';
			} elseif (strtolower(trim($data[0]->title)) == 'apply') {
				$response['color'] = '#9fc33b;';
			} elseif (strtolower(trim($data[0]->title)) == 'overview') {
				$response['color'] = '#979797;';
			} else {
				$response['color'] = '#1fa5d4;';
			}
			return $response;
		} else {
			$wpdb->query("UPDATE " . $wpdb->prefix . "trek_events SET start = " . $_POST['start'] . ", end=" . $_POST['end']  . " where id=" . $_POST['id']);
			return;
		}
	}

	public static function get_course_event($request = null)
	{
		global $wpdb;
		$event = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "trek_events WHERE id = " . $_GET['id']);
		$data = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "learnpress_sections WHERE id = " . $event[0]->trek_section_id);
		// $data->title;
		$coursePost = get_post($data[0]->course_id);
		// $response['title'] = $data[0]->title ." - ".  $coursePost->post_title ;
		$response['trek_section_id'] = $event[0]->trek_section_id;
		$response['course_id'] = $data[0]->course_id;
		return $response;
	}

	public static function delete_course_event($request = null)
	{
		global $wpdb;
		$wpdb->query("DELETE FROM " . $wpdb->prefix . "trek_events WHERE id =" . $_POST['id']);
		return [];
	}

	public static function delete_school_lxp_user($request = null)
	{
		delete_user_meta($_POST['user_id'], 'lxp_school_id');
		return [];
	}
}


?>
