<?php
  global $userdata;
  global $treks_src;
  global $livePath;

  $teacher_post = lxp_get_teacher_post($userdata->data->ID);
  $course_id = $post->ID;
  $lxp_sections = get_post_meta($post->ID, "lxp_sections", true);
  $lxp_sections = $lxp_sections ? json_decode($lxp_sections) : [];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php the_title(); ?></title>
    <link href="<?php echo $treks_src; ?>style/common.css" rel="stylesheet" />
    <link href="<?php echo $treks_src; ?>style/treksstyle.css" rel="stylesheet" />
    <link href="<?php echo $treks_src; ?>style/style-trek-section.css" rel="stylesheet" />
    <link href="<?php echo $treks_src; ?>style/trek-section.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo $treks_src; ?>style/header-section.css" />
    
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
      crossorigin="anonymous"
    />

    <style type="text/css">
      .header-notification-user .copy-anchor
      {
        display: none;
      }
      
      .trek-section-hide {
        display: none;
      }
      .trek-section-nav-anchor {
        text-decoration: none;
      }
      .trek-main-heading {
        font-size: 1.5rem;
      }

      .digital-student-journal-section {
        justify-content: end;
      }
      .digital-student-journal-btn {
        width: 110% !important;
      }
      .trek-main-heading-wrapper {
        display: flex;
        width: 100%;
        justify-content: space-between;
        margin-bottom: 10px;
      }
      .trek-main-heading-top-link {
        margin-left: auto;
        background-color: #eaedf1;
        color: #979797;
        border: 1.5px solid #979797;
        padding: 6px;
        text-decoration: auto;
        font-size: 0.85rem;
      }
      .central-cncpt-section h1 {
        font-size: 1.6rem;
      }
      /* .central-cncpt-section h2 {
        font-size: 1.4rem;
      } */
      .central-cncpt-section h3 {
        font-size: 1.3rem;
      }
      .copy-anchor-icon-img {
        margin-left: 5px;
      }
      
      a:target {
        background-color: yellow !important;
      }
      
      a {
        color: #434343 !important;
      }
      
      ul {
        padding-left: 2rem !important;
      }
      table tr td {
        padding-top: 0.8rem !important;
        padding-left: 0.5rem !important;
      }

      /* overview active style */
      .tags-body.overview-poly-body-active {
        background: #979797;
      }
      .overview-poly-body-active .tags-body-detail span {
        color: #fff !important;
      }
      .overview-poly-body-active .tags-body-polygon {
        background: #fff !important;
      }
      .overview-poly-body-active .trek-section-character-overview {
        color: #979797 !important;
      }
      .tags-body.overview-poly-body-hover {
        background: #979797;
      }
      .overview-poly-body-hover .tags-body-detail span {
        color: #fff !important;
      }
      .overview-poly-body-hover .tags-body-polygon {
        background: #fff !important;
      }
      .overview-poly-body-hover .trek-section-character-overview {
        color: #979797 !important;
      }

      /* recall active style */
      .tags-body.recall-poly-body-active {
        background: #ca2738;
      }
      .recall-poly-body-active .tags-body-detail span {
        color: #fff !important;
      }
      .recall-poly-body-active .tags-body-polygon {
        background: #fff !important;
      }
      .recall-poly-body-active .trek-section-character-recall {
        color: #ca2738 !important;
      }
      .tags-body.recall-poly-body-hover {
        background: #ca2738;
      }
      .recall-poly-body-hover .tags-body-detail span {
        color: #fff !important;
      }
      .recall-poly-body-hover .tags-body-polygon {
        background: #fff !important;
      }
      .recall-poly-body-hover .trek-section-character-recall {
        color: #ca2738 !important;
      }

      /* practice a active style */
      .tags-body.pa-poly-body-active {
        background: #1fa5d4;
      }
      .pa-poly-body-active .tags-body-detail span {
        color: #fff !important;
      }
      .pa-poly-body-active .tags-body-polygon {
        background: #fff !important;
      }
      .pa-poly-body-active .trek-section-character-pa {
        color: #1fa5d4 !important;
      }
      .tags-body.pa-poly-body-hover {
        background: #1fa5d4;
      }
      .pa-poly-body-hover .tags-body-detail span {
        color: #fff !important;
      }
      .pa-poly-body-hover .tags-body-polygon {
        background: #fff !important;
      }
      .pa-poly-body-hover .trek-section-character-pa {
        color: #1fa5d4 !important;
      }

      /* practice b active style */
      .tags-body.pb-poly-body-active {
        background: #1fa5d4;
      }
      .pb-poly-body-active .tags-body-detail span {
        color: #fff !important;
      }
      .pb-poly-body-active .tags-body-polygon {
        background: #fff !important;
      }
      .pb-poly-body-active .trek-section-character-pb {
        color: #1fa5d4 !important;
      }
      .tags-body.pb-poly-body-hover {
        background: #1fa5d4;
      }
      .pb-poly-body-hover .tags-body-detail span {
        color: #fff !important;
      }
      .pb-poly-body-hover .tags-body-polygon {
        background: #fff !important;
      }
      .pb-poly-body-hover .trek-section-character-pb {
        color: #1fa5d4 !important;
      }

      /* apply active style */
      .tags-body.apply-poly-body-active {
        background: #9fc33b;
      }
      .apply-poly-body-active .tags-body-detail span {
        color: #fff !important;
      }
      .apply-poly-body-active .tags-body-polygon {
        background: #fff !important;
      }
      .apply-poly-body-active .trek-section-character-apply {
        color: #9fc33b !important;
      }
      .tags-body.apply-poly-body-hover {
        background: #9fc33b;
      }
      .apply-poly-body-hover .tags-body-detail span {
        color: #fff !important;
      }
      .apply-poly-body-hover .tags-body-polygon {
        background: #fff !important;
      }
      .apply-poly-body-hover .trek-section-character-apply {
        color: #9fc33b !important;
      }

      .fc-timegrid-event-harness:hover {
        cursor: pointer;
      }

      #course-save-button {
        border: 0px;
      }

      .my-trk-detail-prep {
        width: 100% !important;
      }

      .lesson-link {
        text-decoration: none !important;
      }
    </style>
  </head>
  <body>
    
    <!-- Calendar Modal  -->
    <?php include $livePath.'/trek/parts/assign-calendar.php'; ?>
    <!-- Student Modal -->
    <div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="studentModalLabel">Assigned Students</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-primary" role="alert">
              Loading Students ....
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Menu -->
    <nav class="navbar navbar-expand-lg treks-nav">
      <div class="container-fluid">
        <?php include $livePath.'/trek/header-logo.php'; ?>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <div class="navbar-nav me-auto mb-2 mb-lg-0">
            <div class="header-logo-search">
              <!-- searching input -->
              <div class="header-search">
                <img src="<?php echo $treks_src; ?>/assets/img/header_search.svg" alt="svg" />
                <input placeholder="Search" />
              </div>
            </div>
          </div>
          <div class="d-flex" role="search">
            <div class="header-notification-user">
              <?php include $livePath.'/trek/user-profile-block.php'; ?>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Basic Container -->
    <section class="main-container">
      <!-- Nav Section -->
      <nav class="nav-section">
        <?php include $livePath.'/trek/navigation.php'; ?>
      </nav>

      <!-- My Courses breadcrumbs -->
      <section class="my-trk-bc-section">
        <div class="my-trk-bc-section-div">
          <!-- breadcrumbs -->
          <img class="bc-img-1" src="<?php echo $treks_src; ?>/assets/img/bc_img.svg" />
          <p>My Courses</p>
          <img class="bc-img-2" src="<?php echo $treks_src; ?>/assets/img/bc_arrow_right.svg" />
          <p><?php the_title(); ?></p>
        </div>
      </section>
      <!-- My Courses Detail -->
      <section class="my-trk-detail-section">
        <div class="my-trk-detail-section-div">
          <!-- TREKs image  -->
          <div class="my-trk-detail-img">
            <?php
              if ( has_post_thumbnail( $post->ID ) ) {
                  the_post_thumbnail('medium', array( 'class' => 'rounded' ));
              } else {
            ?>
              <img width="300" height="180" src="<?php echo $treks_src; ?>/assets/img/tr_main.jpg" class="rounded wp-post-image" alt="" decoding="async">
            <?php        
              }
            ?>
          </div>
          <!-- Courses detail -->
          <div class="my-trk-detail-prep">
            <!-- Title -->
            <div class="row">
              <div class="col col-10">
                <div class="detail-prep-title">
                  <h2><?php the_title(); ?></h2>
                </div>
              </div>
              <div class="col col-2 text-end">
                <?php
                  $courses_saved = get_post_meta($teacher_post->ID, 'courses_saved');
                  $is_saved = in_array($post->ID, $courses_saved);
                ?>
                <button id="course-save-button" onclick="set_course_saved(<?php echo !$is_saved; ?>)">
                  <?php
                    if ($is_saved) {
                  ?>
                    <img width="35" height="35" src="<?php echo $treks_src; ?>/assets/img/trek-save-filled-icon.svg" alt="svg" />
                  <?php } else { ?>
                    <img width="35" height="35" src="<?php echo $treks_src; ?>/assets/img/trek-save-icon.svg" alt="svg" />
                  <?php } ?>
                </button>
              </div>
            </div>
            <!-- Description -->
            <div class="detail-prep-desc">
              <p><?php echo $post->post_content; ?></p>
            </div>

        </div>
      </section>
  
      <section class="central-cncpt-section">
        <section class="trk-assign-section">
          <div class="trk-assign-section-div">
              <h1 class="trek-main-heading">Sections</h1>            
          </div>
        </section>
      </section>
      <div class="accordion" id="accordionExample">
        <?php
          if ( is_array($lxp_sections) ) {
            $i = 1;
            foreach ($lxp_sections as $lxp_section) {
              $lesson_query = new WP_Query( array(
                    'posts_per_page'   => -1,
                    'orderby' => 'ID',
                    'order'   => 'ASC',
                    'post_type' => TL_LESSON_CPT,
                    'meta_query' => [
                        [
                          'key' => 'lti_content_title', 
                          'value' => $lxp_section
                        ],
                        [
                          'key' => 'tl_course_id', 
                          'value' => $course_id,
                          'compare' => '='
                        ]
                      ]
                  ) );
        ?>
              <div class="accordion-item">
                  <h2 class="accordion-header" id="heading-<?php echo $i; ?>">
                      <button class="accordion-button<?php echo $i == 1 ? '' : ' collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $i; ?>" aria-expanded="<?php echo $i == 1 ? 'true' : 'false'; ?>" aria-controls="collapse-<?php echo $i; ?>">
                        <?php
                            echo $lxp_section;
                        ?>
                      </button>
                  </h2>
                  <div id="collapse-<?php echo $i; ?>" class="accordion-collapse collapse<?php echo $i == 1 ? ' show' : ''; ?>" aria-labelledby="heading-<?php echo $i; ?>" data-bs-parent="#accordionExample">
                      <div class="accordion-body">
                        <section class="trk-assign-section">
                          <div class="trk-assign-section-div">
                            <h5>Lessons</h5>
                          </div>
                          <div class="">
                            <button onclick="assign(<?php echo $course_id; ?>, '<?php echo $lxp_section; ?>')" class="primary-btn lx-space">Assign</button>
                          </div>
                        </section>
                        <?php
                            if (($lesson_query->have_posts())) {
                              echo "<ul>";
                              while ($lesson_query->have_posts()) {
                                $lesson_query->the_post();
                          ?>
                            <li><a class="lesson-link" target="_blank" href="<?php echo get_permalink(get_the_ID()); ?>"><?php echo get_the_title(); ?></a></li>
                            
                          <?php
                          
                            }
                            echo "</ul>";
                          }
                        ?>
                      </div>
                  </div>
              </div>
        <?php    
              $i++;
            }
          }
        ?>
      </div>

    </section>

    <script
      src="https://code.jquery.com/jquery-3.6.3.js"
      integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM="
      crossorigin="anonymous"
    ></script>
    <script src="<?php echo $treks_src; ?>/js/Animated-Circular-Progress-Bar-with-jQuery-Canvas-Circle-Progress/dist/circle-progress.js"></script>
    <script src="<?php echo $treks_src; ?>/js/custom.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
      crossorigin="anonymous"
    ></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js'></script>

    <script type="text/javascript">
      function assign(course_id, lxp_section) {
        window.location = "<?php echo site_url("assignment"); ?>" + "?course=" + course_id + "&section=" + lxp_section;
      }
    </script>

    <script type="text/javascript">

      jQuery(document).ready(function() {
        
        const segment_classes = ["overview-poly-body", "recall-poly-body", "pa-poly-body", "pb-poly-body", "apply-poly-body"];
        const segment_active_classes = ["overview-poly-body-active", "recall-poly-body-active", "pa-poly-body-active", "pb-poly-body-active", "apply-poly-body-active"];
        
        var trekUrl = "<?php echo get_permalink($post->ID); ?>";

        // set copy icons to links
        jQuery("a").each(function() {
          if ($(this).attr("id")) {
            // prevent link action from navigation if it is #
            if ($(this).attr("href") === "#") {
              $(this).on('click', function(e) {
                e.preventDefault();
              });
            }

            $( '<a class="copy-anchor" href="#' + $(this).attr("id") + '" id="' + $(this).attr("id") + '_Copy_Link"><img class="copy-anchor-icon-img" src="<?php echo $treks_src; ?>/assets/img/link_icon.png" width="20" height="20" /></a>' ).insertAfter( $(this) );
            jQuery('#' + jQuery(this).attr("id") + '_Copy_Link').on('click', function(e) {
              e.preventDefault();

              var targetLinkArr = jQuery(this).attr("href").split('#');
              targetLinkId = targetLinkArr.length === 2 ? targetLinkArr[1] : null;
              if (targetLinkId) {
                var trekTargetUrl = trekUrl + "#" + targetLinkId;
                console.log("copy -- ", trekTargetUrl);

                document.addEventListener('copy', function(e) {
                    e.clipboardData.setData('text/plain', trekTargetUrl);
                    e.preventDefault();
                }, true);

                document.execCommand('copy');  
              }
            })
          }
        });

        // ************ Navigation Tabs ****************
        jQuery('a.trek-section-nav-anchor').on('mouseover', function(e) {
          const has_active_class = jQuery(this).find('.tags-body').attr('class').split(/\s+/).filter(className => segment_active_classes.includes(className));
          const has_segment_class = jQuery(this).find('.tags-body').attr('class').split(/\s+/).filter(className => segment_classes.includes(className));
          if (!has_active_class.length) {
            if (has_segment_class.length) {
              const sectionHoverSelector = has_segment_class[0] + '-hover';
              if (!jQuery(this).find('.tags-body').hasClass(sectionHoverSelector)) {
                jQuery(this).find('.tags-body').addClass(sectionHoverSelector);
              }
            } else {
              console.log("other segment ....");
            }
          }
        });

        jQuery('a.trek-section-nav-anchor').on('mouseleave', function(e) {
          const has_segment_class = jQuery(this).find('.tags-body').attr('class').split(/\s+/).filter(className => segment_classes.includes(className));
          if (has_segment_class.length > 0) {
            const sectionHoverSelector = has_segment_class[0] + '-hover';
            jQuery(this).find('.tags-body').removeClass(sectionHoverSelector);
          }
        });

        // on trek section click
        jQuery('a.trek-section-nav-anchor').on('click', function(e) {
          if (!jQuery(this).find('.digital-student-journal-btn').length) {
            e.preventDefault();
            const navHref = jQuery(this).attr('href').split('#');
            if (navHref.length > 1) {
              jQuery('.central-cncpt-section').addClass('trek-section-hide');
              jQuery('.trek-section-' + navHref[1]).removeClass('trek-section-hide');
            }

            const has_segment_class = jQuery(this).find('.tags-body').attr('class').split(/\s+/).filter(className => segment_classes.includes(className));
            if (has_segment_class.length > 0) {
              segment_classes.forEach(segment_class => {
                jQuery('a.trek-section-nav-anchor .' + segment_class).removeClass(segment_class + '-active');
              });
              const sectionHoverSelector = has_segment_class[0] + '-active';
              jQuery(this).find('.tags-body').addClass(sectionHoverSelector);
            }
          }
        });

        // ********* Execute the bookmark link ****************
        if (location.hash && jQuery('a' + location.hash).length > 0) {
          jQuery('section.central-cncpt-section').get().forEach(function(section) {            
            if (!jQuery(section).hasClass('trek-section-hide')) {
              jQuery(section).addClass('trek-section-hide');
            }
          });
          jQuery('a' + location.hash).parents('section.central-cncpt-section').removeClass('trek-section-hide');
          
          // set menu link active
          const buttonStyle =  jQuery('a' + location.hash).parents('section.central-cncpt-section').find('input[name="buttonStyle"]').val();
          console.log('buttonStyle ========>>> ', buttonStyle);
          segment_classes.forEach(segment_class => {
            jQuery('a.trek-section-nav-anchor .' + segment_class).removeClass(segment_class + '-active');
          });
          const sectionSelector = buttonStyle;
          
          
          jQuery('a.trek-section-nav-anchor .' + sectionSelector).get().forEach(element => {
            // console.log("1**************** ", jQuery(element).attr('class'));
            // console.log("2**************** ", !jQuery(element).hasClass('digital-student-journal-btn'));
            if (!jQuery(element).hasClass('digital-student-journal-btn')) {
              // jQuery(element).find('.' + sectionSelector).addClass(sectionSelector + '-active');
              jQuery(element).addClass(sectionSelector + '-active');
            }  
          });
          
          // book mark scrolling
          const bookmarkPosition = jQuery('a' + location.hash).position();
          if (bookmarkPosition) {
            window.scrollTo(bookmarkPosition.left, bookmarkPosition.top);
          }
        }
      });

      // **************** Student Modal ************************************************
      var studentModal = document.getElementById('studentModal');
      studentModalObj = new bootstrap.Modal(studentModal);
      studentModal.addEventListener('show.bs.modal', function (event) {
        var modalBody = studentModal.querySelector('.modal-body');
        let apiUrl = window.location.origin + '/wp-json/lms/v1/';
        $.ajax({
          method: "POST",
          url: apiUrl + "trek/section/assigned/students",
          data: { trek_section_id: window.trek_section_id, teacher_id: window.teacher_id }
        }).done(function( students ) {
          if (students.length > 0 ) {
            jQuery(modalBody).html(studentModelHTML(students, window.trek_section_id, window.teacher_id));
          } else {
            jQuery(modalBody).html('<div class="alert alert-warning" role="alert">No students assigned!</div>');
          }
        });
      });

      studentModal.addEventListener('hidden.bs.modal', function (event) {
        var modalBody = studentModal.querySelector('.modal-body');
        jQuery(modalBody).html('<div class="alert alert-primary" role="alert">Loading Students ....</div>');
      });

      function studentModelHTML(students, trek_section_id, teacher_id) {
        html = '<ul class="list-group">'
          students.forEach(student => {
            html +=  '<li class="list-group-item d-flex justify-content-between align-items-start">'
                  +'    <div class="ms-2 me-auto">'
                  +'      <div class="fw-regular">'+ student.name +'</div>'
                  +'      <em><small>5th grade</small></em>'
                  +'    </div>'
                  +'    <button type="button" class="btn btn-primary btn-sm">Report</button> &nbsp;'
                  +'    <button type="button" class="btn btn-danger btn-sm" onclick="unassign_student(' + student.student_assignment_id + ',' + trek_section_id + ',' + teacher_id + ')">Unassign</button>'
                +'    </li>';
          });

          html += '</ul>';
        return html;
      }

      function unassign_student(student_assignment_id, trek_section_id, teacher_id) {
        let apiUrl = window.location.origin + '/wp-json/lms/v1/';
        $.ajax({
          method: "POST",
          url: apiUrl + "trek/section/unassign/student",
          data: { student_assignment_id, trek_section_id, teacher_id}
        }).done(function( students ) {
          jQuery(".central-cncpt-section").get().forEach(el => {
            if(!jQuery(el).hasClass('trek-section-hide')) {
                jQuery(el).find('span.segment-student-count').text(students.length);
            }
          });
          studentModalObj.hide();
        });
      }

      // **************** Calendar Modal ************************************************
      var calendarModal = document.getElementById('calendarModal');
      calendarModalObj = new bootstrap.Modal(calendarModal);
      calendarModal.addEventListener('show.bs.modal', function (event) {
        jQuery('.trek-title-label').text(window.trekTitle);
        jQuery('.trek-segment-label').text(window.trekSectionTitle);
        jQuery('.trek-segment-label-character').text(window.trekSectionTitle[0]);
        var segmentColor = "#ca2738";
        if (window.trekSectionTitle === 'Overview') {
          segmentColor = "#979797";
        } else if (window.trekSectionTitle === 'Recall') {
          segmentColor = "#ca2738";
        } else if (window.trekSectionTitle === 'Practice A') {
          segmentColor = "#1fa5d4";
        } else if (window.trekSectionTitle === 'Practice B') {
          segmentColor = "#1fa5d4";
        } else if (window.trekSectionTitle === 'Apply') {
          segmentColor = "#9fc33b";
        }
        jQuery('.students-modal .modal-content .modal-body .students-breadcrumb .interdependence-tab .inter-tab-polygon, .assignment-modal .modal-content .modal-body .assignment-modal-left .recall-user .inter-tab-polygon').css('background-color', segmentColor);
        jQuery('.students-modal .modal-content .modal-body .students-breadcrumb .interdependence-tab .inter-tab-polygon-name, .assignment-modal .modal-content .modal-body .assignment-modal-left .recall-user .inter-user-name').css('color', segmentColor);
        jQuery('.assignment-modal .modal-content .modal-body .assignment-modal-left .recall-user').css('background-color', '#eaedf1');
        
        window.selectedCalendarEvent = null;
        let host = window.location.hostname === 'localhost' ? window.location.origin + '<?php echo WORDPRESS_HOST; ?>' : window.location.origin;
        let apiUrl = host + '/wp-json/lms/v1/';
        var apiUrlLoadAssignments = host + '/wp-json/lms/v1/get/all/trek/events?user_id=<?php echo get_current_user_id() ?>';
        var calendarEl = document.getElementById('assignmentCalendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          selectable: true,
          initialView: 'timeGridWeek',
          events: apiUrlLoadAssignments ,
          select: function( selectionInfo ) {
              const start = selectionInfo.startStr;
              const end =  selectionInfo.endStr;
              const allDay = selectionInfo.allDay;
              window.selectedCalendarEvent = {start, end, allDay};
          }
        });
        calendar.render();
        window.calendarObj = calendar;
        // load users
        window.dataForCalendar;
        $.ajax({
          method: "POST",
          url: apiUrl + "trek/section/unassigned/students",
          data: { trek_section_id: window.dataForCalendar.trek_section_id, teacher_id: window.dataForCalendar.user_id }
        }).done(function( students ) {
          studentsHtml = "";
          students.forEach(student => {
            studentsHtml += '<div class="assignment-search-result">'
                            +'   <div class="search-user">'
                            +'       <input class="form-check-input checked-students" type="checkbox" value="' + student.id + '">'
                            +'       <img src="<?php echo $treks_src; ?>/assets/img/profile-icon.png" alt="user" />'
                            +'       <div class="user-name-detail">'
                            +'           <h5>' + student.name + '</h5>'
                            +'           <p>5th grade</p>'
                            +'       </div>'
                            +'   </div>'
                            +'</div>';
          });
          jQuery('.assignment-search').html(studentsHtml);
        });

        // var modalBody = calendarModal.querySelector('.modal-body');
        // let apiUrl = window.location.origin + '/wp-json/lms/v1/';
        
        /* $.ajax({
          method: "POST",
          url: apiUrl + "trek/section/assigned/students",
          data: { trek_section_id: window.trek_section_id, teacher_id: window.teacher_id }
        }).done(function( students ) {
          if (students.length > 0 ) {
            jQuery(modalBody).html(studentModelHTML(students, window.trek_section_id, window.teacher_id));
          } else {
            jQuery(modalBody).html('<div class="alert alert-warning" role="alert">No students assigned!</div>');
          }
        }); */
      });

      calendarModal.addEventListener('hidden.bs.modal', function (event) {
        // var modalBody = calendarModal.querySelector('.modal-body');
        // jQuery(modalBody).html('<div class="alert alert-primary" role="alert">Loading Students ....</div>');
      });

      function fetch_trek_assignments_calendar(trek_section_id, teacher_id) {
        window.trekTitle = jQuery('.detail-prep-title').text().replace(/\r?\n|\r/g, "").trim();
        jQuery(".central-cncpt-section").get().forEach(el => {
          if(!jQuery(el).hasClass('trek-section-hide')) {
            window.trekSectionTitle =  jQuery(el).find('h1.trek-main-heading').text().replace(/\r?\n|\r/g, "").trim();
          }
        });
        
        // window.trek_section_id = trek_section_id;
        // window.teacher_id = teacher_id;
        window.dataForCalendar = {trek_section_id, user_id: teacher_id};
        calendarModalObj.show();
      }
      /* 
      function studentModelHTML(students, trek_section_id, teacher_id) {
        html = '<ul class="list-group">'
          students.forEach(student => {
            html +=  '<li class="list-group-item d-flex justify-content-between align-items-start">'
                  +'    <div class="ms-2 me-auto">'
                  +'      <div class="fw-regular">'+ student.name +'</div>'
                  +'      <em><small>5th grade</small></em>'
                  +'    </div>'
                  +'    <button type="button" class="btn btn-primary btn-sm">Report</button> &nbsp;'
                  +'    <button type="button" class="btn btn-danger btn-sm" onclick="unassign_student(' + student.student_assignment_id + ',' + trek_section_id + ',' + teacher_id + ')">Unassign</button>'
                +'    </li>';
          });

          html += '</ul>';
        return html;
      }
      */

      function test_unassign_student(student_assignment_id, trek_section_id, teacher_id) {
        let apiUrl = window.location.origin + '/wp-json/lms/v1/';
        /* $.ajax({
          method: "POST",
          url: apiUrl + "trek/section/unassign/student",
          data: { student_assignment_id, trek_section_id, teacher_id}
        }).done(function( students ) {
          jQuery(".central-cncpt-section").get().forEach(el => {
            if(!jQuery(el).hasClass('trek-section-hide')) {
                jQuery(el).find('span.segment-student-count').text(students.length);
            }
          });
          calendarModalObj.hide();
        }); */
      }

      function trek_section_create_assignment() {
        let selected_students = [];
        jQuery('.checked-students:checked').each((i, el) => {
          selected_students.push(jQuery(el).val());
        });

        let ok = true;
        let message = "";
        if (selected_students.length === 0) {
          ok = false;
          message += "Please select Student(s). ";
        }
        if (window.selectedCalendarEvent === null) {
          ok = false;
          message += "Please select date time on Calendar";
        }

        // window.dataForCalendar
        if (ok) {
          let data = {...window.selectedCalendarEvent, ...window.dataForCalendar, teacher_id: window.dataForCalendar.user_id, student_ids: selected_students };
          let apiUrl = window.location.origin + '/wp-json/lms/v1/';
          $.ajax({
            url: apiUrl + "trek/section/assigned/students/store",
            data,
            type: "POST",
            async: false,
            success: function(students) {
                jQuery(".central-cncpt-section").get().forEach(el => {
                  if(!jQuery(el).hasClass('trek-section-hide')) {
                      jQuery(el).find('span.segment-student-count').text(students.length);
                  }
                });
            }
        }).always(function(){
            calendarObj.refetchEvents();
            bootstrap.Modal.getInstance(document.getElementById('calendarModal')).hide();
        });
        } else {
          alert(message);
        }
      }

      function assign_modal_close() {
        bootstrap.Modal.getInstance(document.getElementById('calendarModal')).hide();
      }
    </script>

    <?php include $livePath.'/lxp/assignment-stats-modal.php'; ?>

    <script type="text/javascript">
      function fetch_students(students_ids, trek_title, trek_segment) {
        jQuery("#student-modal-loader").show();
        jQuery("#student-modal-table").hide();
        format_modal_elements(trek_title, trek_segment);
        window.assignmentStatsModalObj.show();

        let host = window.location.hostname === 'localhost' ? window.location.origin + '<?php echo WORDPRESS_HOST; ?>' : window.location.origin;
        let apiUrl = host + '/wp-json/lms/v1/';
        jQuery.ajax({
          method: "POST",
          url: apiUrl + "students/list",
          data: { students_ids }
        }).done(function( response ) {
          jQuery("#student-modal-table tbody").html( response.data.map(student => student_assignment_stat_row_html(student)).join('\n') );
          jQuery("#student-modal-loader").hide();
          jQuery("#student-modal-table").show();
        });
      }

      function format_modal_elements(trek_title, trek_segment) {
        jQuery('#student-progress-trek-title').text(trek_title);
        jQuery('#student-progress-trek-segment').text(trek_segment);
        jQuery('#student-progress-trek-segment-char').text(trek_segment[0]);
        switch (trek_segment) {
            case 'Overview':
                segmentColor = "#979797";
                break;
            case 'Recall':
                segmentColor = "#ca2738";
                break;
            case 'Practice A':
                segmentColor = "#1fa5d4";
                break;
            case 'Practice B':
                segmentColor = "#1fa5d4";
                break;
            case 'Apply':
                segmentColor = "#9fc33b";
                break;
            default:
                segmentColor = "#ca2738";
                break;
        }
        jQuery('.students-modal .modal-content .modal-body .students-breadcrumb .interdependence-tab .inter-tab-polygon, .assignment-modal .modal-content .modal-body .assignment-modal-left .recall-user .inter-tab-polygon').css('background-color', segmentColor);
        jQuery('.students-modal .modal-content .modal-body .students-breadcrumb .interdependence-tab .inter-tab-polygon-name, .assignment-modal .modal-content .modal-body .assignment-modal-left .recall-user .inter-user-name').css('color', segmentColor);
      }

      function student_assignment_stat_row_html(student) {
          return `
              <tr>
                  <td>
                  <div class="table-user">
                      <img src="<?php echo $treks_src; ?>/assets/img/profile-icon.png" alt="user" />
                      <div class="user-about">
                      <h5>` + student.name + `</h5>
                      <p>` +  (student.grades && student.grades.length > 0 ? JSON.parse(student.grades).join(', ') : ``) + `</p>
                      </div>
                  </div>
                  </td>
                  <td>
                  <div class="table-status">` + student.status + `</div>
                  </td>
                  <td>` + student.progress + `</td>
                  <td>` + student.score + `</td>
                  <td><a href='<?php echo site_url("calendar"); ?>' target="_blank"><img src="<?php echo $treks_src; ?>/assets/img/review-icon.svg" alt="svg" width="30" /></a></td>
              </tr>
          `;
      }

      // teacher course set 'is_saved' to true or false
      function set_course_saved(is_saved) {
        const is_saved_val = is_saved ? 1 : 0;
        let course_id = <?php echo $course_id; ?>;
        let teacher_post_id = <?php echo $teacher_post->ID; ?>;
        let host = window.location.hostname === 'localhost' ? window.location.origin + '<?php echo WORDPRESS_HOST; ?>' : window.location.origin;
        let apiUrl = host + '/wp-json/lms/v1/';
        
        $.ajax({
          method: "POST",
          url: apiUrl + "teacher/courses/saved",
          data: { course_id, is_saved: is_saved_val, teacher_post_id }
        }).done(function( response ) {
          if (response.success) {
            window.location.reload();
          }
        });
      }
    </script>
  </body>
</html>
<?php 
$lxp_visited_courses = get_post_meta($teacher_post->ID, 'lxp_visited_courses', $course_id);
if (!$lxp_visited_courses) {
  add_post_meta($teacher_post->ID, 'lxp_visited_courses', $course_id);
} else {
  // delete 'lxp_visited_courses' meta key and add it again with new value
  delete_post_meta($teacher_post->ID, 'lxp_visited_courses', $course_id);
  add_post_meta($teacher_post->ID, 'lxp_visited_courses', $course_id); 
}
?>