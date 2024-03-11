<?php

/**
 * Class TL_Lesson_Post_Type
 * 
 * @author Waqar Muneer
 * @version 1.0
 */

 class TL_Lesson_Post_Type extends TL_Post_Type {
    /**
    * @var null
    */
   protected static $_instance = null;

   /**
    * @var string
    */
   protected $_post_type = TL_LESSON_CPT;

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
    * Register lesson post type.
    */
   public function args_register_post_type() : array {
      
      add_rewrite_tag( '%course%', '([^&]+)', 'post_type='.TL_LESSON_CPT.'&course_title=' );
      add_permastruct('lessons', 'tl/courses/%course%/lessons/%'.'lessons'.'%', false,  ['walk_dirs' => false]);
      add_rewrite_rule('^tl/courses/([^/]+)/lessons/([^/]+)?','index.php?'.TL_LESSON_CPT.'=$matches[2]','top');
      
      add_filter( 'query_vars', function ( $vars ) {
         $vars[] = 'course_title';
         return $vars;
      } );
      
      add_filter('post_type_link', function ( $url, $post ) {

         if ($post->post_type === $this->_post_type) {
            $course_id = get_post_meta($post->ID, 'tl_course_id', true);
            $course_post = get_post($course_id);
            if (intval($course_id)) {
               $url = site_url("tl/courses/" . $course_post->post_name . "/lessons/" . $post->post_name);
            }
         }

         return $url;
      },10, 2);

      $located = locate_template( 'single-tl_lesson.php' );
      if(empty($located)){
         add_filter( 'single_template', function ( $page_template, $type ) {
            global $post;
            if ( $post->post_type == "tl_lesson" ) {
               $page_template = dirname( __FILE__ ) . '/templates/course/single-tl_lesson.php';
         }
         return $page_template;
         },20, 2);
      }

      $labels           = array(
         'name'               => _x( 'LXP Lessons', 'Post Type General Name', 'tinylms' ),
         'singular_name'      => _x( 'Lesson', 'Post Type Singular Name', 'tinylms' ),
         'menu_name'          => __( 'LXP Lessons', 'tinylms' ),
         'parent_item_colon'  => __( 'Parent Item:', 'tinylms' ),
         'all_items'          => __( 'LXP Lessons', 'tinylms' ),
         'view_item'          => __( 'View Lesson', 'tinylms' ),
         'add_new_item'       => __( 'Add New Lesson', 'tinylms' ),
         'add_new'            => __( 'Add New', 'tinylms' ),
         'edit_item'          => __( 'Edit Lesson', 'tinylms' ),
         'update_item'        => __( 'Update Lesson', 'tinylms' ),
         'search_items'       => __( 'Search LXP Lessons', 'tinylms' ),
         'not_found'          => sprintf( __( 'You haven\'t had any lessons yet. Click <a href="%s">Add new</a> to start', 'tinylms' ), admin_url( 'post-new.php?post_type=tl_lesson' ) ),
         'not_found_in_trash' => __( 'No lesson found in Trash', 'tinylms' ),
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
            'slug'       => 'tl/lessons',
            'with_front' => false
         ),
         'show_in_rest'       => true,
         'rest_base'          => 'tl_lesson',
         'capability_type' => 'post',
         'capabilities' => array(
             'edit_post' => 'edit_lxp_lesson',
             'publish_posts' => 'publish_lxp_lessons',
             'read_post' => 'read_lxp_lesson',
             'read_private_posts' => 'read_private_lxp_lessons',
             'delete_post' => 'delete_lxp_lesson',
             'delete_posts' => 'delete_lxp_lessons',
             'create_posts' => 'create_lxp_lessons',
             'create_post' => 'create_lxp_lesson'
         )

      );
      self::register_texonomy();
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
         'rewrite'               => array( 'slug' => 'tl_lesson_tag' ),
         'show_in_rest'          => true,
         'rest_base'             => 'tl_lesson_tag',
         'rest_controller_class' => 'WP_REST_Terms_Controller',
         'capabilities' => array(
            'manage_terms'	=>	'manage_tag_lxp_lesson',
            'edit_terms'	=>	'edit_tag_lxp_lesson',
            'delete_terms'	=>	'delete_tag_lxp_lesson',
            'assign_terms'	=>	'assign_tag_lxp_lesson',
         ),
       );

       register_taxonomy( 
         'tl_lesson_tag', //taxonomy 
         $this->_post_type, //post-type
        $args);
   }

   public function add_meta_boxes() {
      $this->options_metabox();
   }

   public function options_metabox() {
      $this->add_meta_box([
         'lesson-options-class',      // Unique ID
         esc_html__('Lesson Options', 'lesson-options'),    // Title
         array(self::instance(), 'options_metabox_html'),   // Callback function
         $this->_post_type,       // Admin page (or post type)
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

   public function options_metabox_html($post = null)
   {
      $args = array(
         'post_type'=> 'tl_course',
         'orderby'    => 'ID',
         'post_status' => 'publish,draft',
         'order'    => 'DESC',
         'posts_per_page' => -1 
         );
      $courses = get_posts( $args );
      $selectedCourse =  isset($_GET['courseid']) ? $_GET['courseid'] : get_post_meta($post->ID, 'tl_course_id', true);
      $disabled = ( $selectedCourse && $selectedCourse > 0 ) ? 'disabled' : '';
      $output = '  <h4>Select Course</h4>';
      $output .= '<select '.$disabled.' name="tl_course_id" style="margin-top:-10px"> 
               <option disabled selected>Select a course</option>';
      foreach($courses as $course){
         if($selectedCourse == $course->ID){
            $selected = "selected";
           }else{
            $selected = "";
           }
            $output .= '<option value="'.$course->ID .'" '.$selected.' >'. $course->post_title .' </option>';
      }
      $output .= '</select>';
      $output .= ( $selectedCourse && $selectedCourse > 0 ) ? '<input type="hidden" name="tl_course_id" value="'.$selectedCourse.'" />' : '';
      echo $output;
      ?>
      <h4 >Tiny LXP Deep Linking</h4>
      <div style="width: 100%;margin-top:-10px">
         <input type="text" id="lti_tool_url" name="lti_tool_url" value="<?php echo get_post_meta($post->ID, 'lti_tool_url', true)?>" style="width: 100%;" />
         <input type="hidden" id="lti_tool_code" name="lti_tool_code" value="<?php echo get_post_meta($post->ID, 'lti_tool_code', true) ?>" style="width: 100%;" />
         <input type="hidden" id="lti_content_title" name="lti_content_title" value="<?php echo get_post_meta($post->ID, 'lti_content_title', true) ?>" style="width: 100%;" />
         <input type="hidden" id="lti_custom_attr" name="lti_custom_attr" value="<?php echo get_post_meta($post->ID, 'lti_custom_attr', true) ?>" style="width: 100%;" />
         <input type="hidden" id="lti_post_attr_id" name="lti_post_attr_id" value="<?php echo get_post_meta($post->ID, 'lti_post_attr_id', true) ?>" style="width: 100%;" />
      </div>
      <div id="preview_lit_connections" style="width: 100%;display: inline-block;margin-top: 10px;">
         <div class="preview button" href="#">Select Content<span class="screen-reader-text"> (opens in a new tab)</span></div>
      </div>
      <?php
   }

   public function save_tl_post($post_id = null)
   {
       if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_type']) && 'tl_lesson' == $_POST['post_type']) {
              if ( isset($_POST['tl_course_id']) && $_POST['tl_course_id'] > 0 ) {
                $_POST['tl_course_id'] = intval(trim($_POST['tl_course_id']));
                $course_post_sections = get_post_meta($_POST['tl_course_id'], "lxp_sections", true);
                $_POST['lti_content_title'] = ( isset($_POST['lti_content_title']) && $_POST['lti_content_title'] != '' ) ? trim($_POST['lti_content_title']) : "Section";
                if ($course_post_sections) {
                    $course_post_sections = json_decode($course_post_sections);
                    $title_found = array_search($_POST['lti_content_title'], $course_post_sections, true);
                    if (gettype($title_found) === "boolean" && !$title_found) {
                      $course_post_sections[] = $_POST['lti_content_title'];
                      update_post_meta($_POST['tl_course_id'],'lxp_sections', json_encode($course_post_sections));
                    }
                } else {
                  add_post_meta($_POST['tl_course_id'], "lxp_sections", json_encode([$_POST['lti_content_title']]), true);
                }
              }
               update_post_meta($post_id, 'lti_tool_url',$_POST['lti_tool_url']);
               update_post_meta($post_id, 'lti_tool_code', $_POST['lti_tool_code']);
               update_post_meta($post_id, 'lti_content_title', $_POST['lti_content_title']);
               update_post_meta($post_id, 'lti_custom_attr', $_POST['lti_custom_attr']);
               update_post_meta($post_id, 'lti_post_attr_id', $_POST['lti_post_attr_id']);
               if($_POST['tl_course_id'] != get_post_meta($post_id, 'tl_course_id', true)){
                  update_post_meta($post_id,'lti_course_id', "");
               }
               update_post_meta($post_id, 'tl_course_id', $_POST['tl_course_id']);
       }
   }

   public function tl_post_content($more_link_text = null, $strip_teaser = false)
   {
       $post = get_post();
       if (isset($post->post_type) && $post->post_type == "tl_lesson") {
           $content = get_post_meta($post->ID);
           $attrId =  isset($content['lti_post_attr_id'][0]) ? $content['lti_post_attr_id'][0] : "";
           $title =  isset($content['lti_content_title'][0]) ? $content['lti_content_title'][0] : "";
           $toolCode =  isset($content['lti_tool_code'][0]) ?$content['lti_tool_code'][0] : "";
           $customAttr =  isset($content['lti_custom_attr'][0]) ? $content['lti_custom_attr'][0] : "";
           $toolUrl =  isset($content['lti_tool_url'][0]) ? $content['lti_tool_url'][0] : "";
           $plugin_name = Tiny_LXP_Platform::get_plugin_name();
           $content = '<p>' . $post->post_content . '</p>';
           if($attrId){
             $content.= '<p> [' . $plugin_name . ' tool=' . $toolCode . ' id=' . $attrId . ' title=\"' . $title . '\" url=' . $toolUrl . ' custom=' . $customAttr . ']' . "". '[/' . $plugin_name . ']  </p>';
           }
       } else {
           $content = get_the_content($more_link_text, $strip_teaser);
           return  $content;
       }
       return $content;
   }

   public function insert_post_api($post, $request)
   {
      if (isset($request['meta'])){
         $course_id = intval(trim($request['meta']['tl_course_id']));
         $playlist_title = isset($request['meta']['lti_content_title']) ? trim($request['meta']['lti_content_title']) : "Section";
         $course_post_sections = get_post_meta($course_id, "lxp_sections", true);
         
         if ($course_post_sections) {
            $course_post_sections = json_decode($course_post_sections);
            $title_found = array_search($playlist_title, $course_post_sections, true);
            if (gettype($title_found) === "boolean" && !$title_found) {
               $course_post_sections[] = $playlist_title;
               update_post_meta($course_id,'lxp_sections', json_encode($course_post_sections));
            }
         } else {
            add_post_meta($course_id, "lxp_sections", json_encode([$playlist_title]), true);
         }      

         if(isset($request['meta']['lti_content_id'])){
            update_post_meta($post->ID,'lti_content_id', $request['meta']['lti_content_id']);
            update_post_meta($post->ID,'tl_course_id', $request['meta']['tl_course_id']);
            update_post_meta($post->ID,'lti_tool_url', $request['meta']['lti_tool_url']);
            update_post_meta($post->ID,'lti_tool_code', $request['meta']['lti_tool_code']);
            update_post_meta($post->ID,'lti_custom_attr', $request['meta']['lti_custom_attr']);
            update_post_meta($post->ID,'lti_content_title', $request['meta']['lti_content_title']);
            update_post_meta($post->ID,'lti_post_attr_id', $request['meta']['lti_post_attr_id']);
            update_post_meta($post->ID,'lti_course_id', $request['meta']['lti_course_id']);
         }
      }
   }

   function modify_list_row_actions( $actions) {
          return $actions;
   }

   public function register_views() {
   }
 }