<?php

/**
 * Class TL_Course_Post_Type
 * 
 * @author Waqar Muneer
 * @version 1.0
 */

 class TL_Course_Post_Type extends TL_Post_Type {
    /**
    * @var null
    */
   protected static $_instance = null;

   /**
    * @var string
    */
   protected $_post_type = TL_COURSE_CPT;

   /**
    * Get Instance
    */
   public static function instance() {
      if (is_null(self::$_instance)) {
         self::$_instance = new self();
      }

      return self::$_instance;
   }

   /**
    * Constructor
    */
   public function __construct() {
      parent::__construct();
   }

   /**
    * Register course post type.
    */
   public function args_register_post_type() : array {

      $located = locate_template( 'single-tl_course.php' );
      if(empty($located)){
         add_filter( 'single_template', function ( $page_template, $type ) {
            global $post;
            if ( $post->post_type == "tl_course" ) {
               $page_template = dirname( __FILE__ ) . '/templates/course/single-tl_course.php';
         }
         return $page_template;
         },20, 2);
      }
		global $wpdb;
		$wpdb->query("CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."tiny_lms_grades(
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			lesson_id bigint(20) default NULL,
         score FLOAT default NULL,
			user_id bigint(20) default NULL,
			PRIMARY KEY (id)
		)");

      $labels           =     array(
         'name'               => _x( 'LXP Courses', 'Post Type General Name', 'tinylms' ),
         'singular_name'      => _x( 'Course', 'Post Type Singular Name', 'tinylms' ),
         'menu_name'          => __( 'LXP Courses', 'tinylms' ),
         'parent_item_colon'  => __( 'Parent Item:', 'tinylms' ),
         'all_items'          => __( 'LXP Courses', 'tinylms' ),
         'view_item'          => __( 'View Course', 'tinylms' ),
         'add_new_item'       => __( 'Add New Course', 'tinylms' ),
         'add_new'            => __( 'Add New', 'tinylms' ),
         'edit_item'          => __( 'Edit Course', 'tinylms' ),
         'update_item'        => __( 'Update Course', 'tinylms' ),
         'search_items'       => __( 'Search LXP Courses', 'tinylms' ),
         'not_found'          => sprintf( __( 'You haven\'t had any courses yet. Click <a href="%s">Add new</a> to start', 'tinylms' ), admin_url( 'post-new.php?post_type=tl_course' ) ),
         'not_found_in_trash' => __( 'No course found in Trash', 'tinylms' ),
      );
      
      $args = array(
         'labels'             => $labels,
         'public'             => true,
         'query_var'          => true,
         'publicly_queryable' => true,
         'show_ui'            => true,
         'has_archive'        => true,
         'show_in_menu'       => true,
         'show_in_admin_bar'  => true,
         'show_in_nav_menus'  => true,
         'rewrite'            => array(
            'slug'       => 'tl/courses',
            'with_front' => false
         ),
         'show_in_rest'       => true,
         'rest_base'          => 'tl_courses',
         'supports' => array('title', 'editor', 'author', 'thumbnail'),
         'capability_type' => 'post',
         'capabilities' => array(
             'edit_post' => 'edit_lxp_course',
             'publish_posts' => 'publish_lxp_courses',
             'read_post' => 'read_lxp_course',
             'read_private_posts' => 'read_private_lxp_courses',
             'delete_post' => 'delete_lxp_course',
             'delete_posts' => 'delete_lxp_courses',
             'create_posts' => 'create_lxp_courses',
             'create_post' => 'create_lxp_course'
         )
      );

      $this->register_texonomy();
      add_theme_support('post-thumbnails');
      return $args;
   }
   
   public function register_texonomy(){
      $labels = array(
         'name'              => _x( 'Tags', 'taxonomy general name' ),
         'singular_name'     => _x( 'Tag', 'taxonomy singular name' ),
         'search_items'      => __( 'Search Tags' ),
         'all_items'         => __( 'All Tags' ),
         'edit_item'         => __( 'Edit Tag' ),
         'update_item'       => __( 'Update Tag' ),
         'add_new_item'      => __( 'Add New Tag' ),
         'new_item_name'     => __( 'New Tag Name' ),
         'menu_name'         => __( 'Tag' ),
       );
     
       $args = array(
         'hierarchical'          => false,
         'labels'                => $labels,
         'show_ui'               => true,
         'show_admin_column'     => true,
         'query_var'             => true,
         'rewrite'               => array( 'slug' => 'tl_course_tag' ),
         'show_in_rest'          => true,
         'rest_base'             => 'tl_course_tag',
         'rest_controller_class' => 'WP_REST_Terms_Controller',
         'capabilities' => array(
            'manage_terms'	=>	'manage_tag_lxp_course',
            'edit_terms'	=>	'edit_tag_lxp_course',
            'delete_terms'	=>	'delete_tag_lxp_course',
            'assign_terms'	=>	'assign_tag_lxp_course',
         ),
       );

       register_taxonomy( 
         'tl_course_tag', //taxonomy 
         $this->_post_type, //post-type
        $args);

        register_taxonomy( 'tl_course_category', $this->_post_type, array(
            "hierarchical" => true,
            "label" => "Categories",
            "singular_label" => "Category",
            'query_var' => true,
            'public' => true,
            'has_archive' => true,
            'show_ui' => true,
            '_builtin' => true,
            'show_in_nav_menus' => true,
            'show_admin_column'     => true,
            'rewrite' => array( 'slug' => 'tl_course_category', 'with_front' => false ),
            'show_in_rest'          => true,
            'rest_base'             => 'tl_course_category',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
            'menu_icon'             => 'dashicons-location',
            'capabilities' => array(
               'manage_terms'	=>	'manage_category_lxp_course',
               'edit_terms'	=>	'edit_category_lxp_course',
               'delete_terms'	=>	'delete_category_lxp_course',
               'assign_terms'	=>	'assign_category_lxp_course',
            ),
          ));
   }

   public function add_meta_boxes() {
      $this->options_metabox();
   }

   public function options_metabox() {
      $this->add_meta_box([
         'course-options-class',      // Unique ID
         esc_html__( 'Course Options', 'course-options' ),    // Title
         array(self::instance(), 'options_metabox_html'),   // Callback function
         $this->_post_type,         // Admin page (or post type)
         'side',         // Context
         'default',         // Priority
         'show_in_rest' => true,
      ]);
   }

   function post_meta_request_params( $args, $request )
	{
      $args += array(
			'meta_key'   => $request['meta_key'],
			'meta_value' => $request['meta_value'],
			'meta_query' => $request['meta_query'],
		);
	    return $args;
	}

   public function options_metabox_html($post = null) {
      $args = array(
         'posts_per_page'   => -1,
         'post_type'        => 'tl_lesson',
         'meta_query' => array(
            array(
               'key'   => 'tl_course_id',
               'value' =>  $post->ID
            )
         )
      );
      $result = get_posts( $args );
      ?>
      <a href="<?php echo admin_url().'post-new.php?post_type='.TL_LESSON_CPT.'&courseid='. get_the_ID() ?>" class="button "><span class="dashicons dashicons-plus" style="margin-top: 6px"></span>&nbsp;Add New Lessons</a>
      <h3>Lessons</h3>
      <input type="hidden" name="course_removed_lessons" id="course_removed_lessons">
      <?php 
      foreach($result as $result){
         echo '<p><a href="'.get_permalink($result->ID).'" target="blank">'.$result->post_title.'</a>
          &nbsp;
          <a style="color:inherit;" class="dashicons dashicons-edit course_remove_lesson" href="'. get_edit_post_link($result->ID).'" target="blank"></a>
          &nbsp;
          <span class="dashicons dashicons-trash course_remove_lesson" lesson_id="'.$result->ID.'"></span>
          
          </p>';
      }
   }

   public function save_tl_post($post_id = null)
   {
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_type']) && 'tl_course' == $_POST['post_type']) {  
         $postIds = preg_split('@,@', $_POST['course_removed_lessons'], -1, PREG_SPLIT_NO_EMPTY);
         if($postIds){
            foreach($postIds as $postId){
               wp_trash_post( $postId );
            }
         }

      }
   }

   public function insert_post_api($post, $request)
   {
      if(isset($request['meta']['lti_content_id'])){
         update_post_meta($post->ID,'lti_content_id', $request['meta']['lti_content_id']);
      }
   }
   
   function modify_list_row_actions( $actions, $post ) {
      if ($post->post_type=='tl_course' && current_user_can( 'grades_lxp_course' ))
          {
              $actions['duplicate'] = '<a href="'. site_url().'/wp-admin/admin.php?page=grades&course_id='.$post->ID.'" title="" rel="permalink">GradeBook</a>';
          }
          return $actions;
   }

   public  function grade_view() {
      require_once plugin_dir_path(dirname(__FILE__)) . 'lms/templates/course/grades.php';
   }

   public  function grade_book_view() {
      require_once plugin_dir_path(dirname(__FILE__)) . 'lms/templates/course/grade_book.php';
   }

   public function register_views() {
      add_menu_page('Customer Request View', 'Customer Requests', 'manage_options',  'grades',  array($this, 'grade_view' ), 'dashicons-tag', 6  );
      add_menu_page('Customer Request View', 'Customer Requests', 'manage_options',  'gradebook',  array($this, 'grade_book_view' ), 'dashicons-tag', 6  );
      remove_menu_page('grades');
      remove_menu_page('gradebook');
     }
}