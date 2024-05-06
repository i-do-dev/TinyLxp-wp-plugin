<?php
while (have_posts()) : the_post();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php the_title(); ?></title>
    <link href="<?php echo $treks_src; ?>/style/studentSingleCourse.css" rel="stylesheet" />
    <link href="<?php echo $treks_src; ?>/style/treksstyle.css" rel="stylesheet" />
    <!-- <link href="<?php //echo $treks_src; ?>/style/style-trek-section.css" rel="stylesheet" />
    <link href="<?php //echo $treks_src; ?>/style/trek-section.css" rel="stylesheet" /> -->
    <link rel="stylesheet" href="<?php echo $treks_src; ?>/style/header-section.css" />
    <link rel="stylesheet" href="<?php echo $treks_src; ?>/style/studentTreksOverview.css" />
    
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
      crossorigin="anonymous"
    />

    <style type="text/css">
      .student-assignment-block {
        text-decoration: none !important;
      }
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
      .central-cncpt-section{
        padding-top: 10px;
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

      .overview-poly-body .tags-body-polygon {
        width: 38px !important;
        height: 32px !important;
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

      a { text-decoration: none; }
      .student-over-tab-content{
        margin-top: 0px;
      }
    </style>
  </head>
  <body>

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
                <form action="<?php echo site_url("search"); ?>">
                  <input placeholder="Search" id="q" name="q" value="<?php echo isset($_GET["q"]) ? $_GET["q"]:''; ?>" />
                </form>
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
        <?php include $livePath.'/trek/navigation-student.php'; ?>
      </nav>

      <!-- My Courses breadcrumbs -->
      <section class="my-trk-bc-section">
        <div class="my-trk-bc-section-div">
          <!-- breadcrumbs -->
          <img class="bc-img-1" src="<?php echo $treks_src; ?>/assets/img/bc_img.svg" />
          <p>My Course</p>
          <img class="bc-img-2" src="<?php echo $treks_src; ?>/assets/img/bc_arrow_right.svg" />
          <p><?php the_title(); ?></p>
        </div>
      </section>
      <!-- My Courses Detail -->
      <section class="my-trk-detail-section">
        <div class="my-trk-detail-section-div">
          <!-- Courses image  -->
          <div class="my-trk-detail-img">
          <?php
              if ( has_post_thumbnail( $post->ID ) ) {
                  echo get_the_post_thumbnail($post->ID, "thumbnail", array( 'class' => 'rounded' )); 
              } else {
              ?>
              <img width="300" height="180" src="<?php echo $treks_src; ?>/assets/img/tr_main.jpg" class="rounded wp-post-image" />
              <?php        
              }
          ?>            
          </div>
          <!-- Course detail -->
          <div class="my-trk-detail-prep">
            <!-- Title -->
            <div class="detail-prep-title">
              <span class='course-label'><?php the_title(); ?></span>
              <img src="<?php echo $treks_src; ?>/assets/img/bc_arrow_right.svg" />
              <span class='section-label' id='course-section'></span>
              <img src="<?php echo $treks_src; ?>/assets/img/bc_arrow_right.svg" />
              <span id='section-lesson'></span> <br>
              <div class="time-date-box">
                <span class="date-time" id="student-progress-trek-start-time"></span>
                <span style="padding: 4px 8px; font-weight:bold;" >To</span>
                <span class="date-time" id="student-progress-trek-end-time"></span>
              </div>
            </div>
            <!-- Description -->
            <div class="detail-prep-desc">
				      <p><?php echo $post->post_content; ?></p>
            </div>
          </div>
        </div>
      </section>
      <!-- <div class="course-time-box">
        <img class="bc-img-1" src="<?php echo $treks_src; ?>/assets/img/bc_img.svg" />
        <span class='course-label'><?php the_title(); ?></span>
        <img src="<?php echo $treks_src; ?>/assets/img/bc_arrow_right.svg" />
        <span class='section-label' id='course-section'></span>
        <img src="<?php echo $treks_src; ?>/assets/img/bc_arrow_right.svg" />
        <span id='section-lesson'></span> <br>
        <div class="time-date-box">
          <span class="date-time" id="student-progress-trek-start-time"></span>
          <span style="padding: 4px 8px; font-weight:bold;" >To</span>
          <span class="date-time" id="student-progress-trek-end-time"></span>
        </div>
      </div> -->
      <section class="central-cncpt-section trek-section-Assignments">
        <div class="student-over-tab-content">
          <div class="tab-pane">
            <div class="stu-assig-cards">
              <?php
                $args['course_id'] = $post->ID;
                include $livePath.'/lxp/student-assignments-blocks.php';
              ?>
            </div>
          </div>
        </div>
      </section>
    </section>

    <script
      src="https://code.jquery.com/jquery-3.6.3.js"
      integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM="
      crossorigin="anonymous"
    ></script>
    <script>
      jQuery(document).ready(function() {
          let hiddenSection = jQuery('#currentSection').val();
          jQuery('#course-section').text(hiddenSection);
          let hiddenLesson = jQuery('#currentLesson').val();
          jQuery('#section-lesson').text(hiddenLesson);

          // starting date and time
          let start_date = new Date(jQuery('#startDateTime').val());
          let start_date_string = start_date.toLocaleDateString('en-US', { weekday: 'long', month: 'short', day: 'numeric', year: 'numeric' });
          let start_time_string = start_date.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
          jQuery('#student-progress-trek-start-time').text(start_date_string + ' ' + start_time_string);
          // ending date and time
          let end_date = new Date(jQuery('#endDateTime').val());
          let end_date_string = end_date.toLocaleDateString('en-US', { weekday: 'long', month: 'short', day: 'numeric', year: 'numeric' });
          let end_time_string = end_date.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
          jQuery('#student-progress-trek-end-time').text(end_date_string + ' ' + end_time_string);
        });
      function go_to_url(url) {
        window.location = url;
      }
    </script>
    <script src="<?php echo $treks_src; ?>/js/Animated-Circular-Progress-Bar-with-jQuery-Canvas-Circle-Progress/dist/circle-progress.js"></script>
    <script src="<?php echo $treks_src; ?>/js/custom.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
      crossorigin="anonymous"
    ></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js'></script>
  </body>
</html>
<?php endwhile; ?>