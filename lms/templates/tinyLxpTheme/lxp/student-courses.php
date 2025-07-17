<?php
// global $treks_src;
//$userdata = get_userdata(get_current_user_id());
$student_post = lxp_get_student_post(get_current_user_id());
$assignments = lxp_get_student_assignments($student_post->ID);
$courses = lxp_get_assignments_courses($assignments);
// Start the loop.
while (have_posts()) : the_post();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link href="<?php echo $treks_src; ?>/style/main.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo $treks_src; ?>/style/header-section.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
    <link href="<?php echo $treks_src; ?>/style/treksstyle.css" rel="stylesheet" />
    <style type="text/css">
        .treks-card {
        width: 300px !important;
        }
        .treks-card-link {
        text-decoration: none !important;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <?php include $livePath.'/trek/header-logo.php'; ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
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
    <section class="main-container treks_main_container">
        <!-- Nav Section -->
        <div class="main-container nav_container">
            <nav class="nav-section nav_section_treks">
                <?php include $livePath.'/trek/navigation-student.php'; ?>
            </nav>
        </div>
        <!-- Recent Filters & Courses flex -->
        <div class="filter_treks_flx">
            <!-- Recent Filters -->
            
            <!-- Recent Courses -->
            <section class="recent-treks-section filter_treks_section filter_my_treks_sec" style="width: 80%; margin: 0 auto;">
                <div class="recent-treks-section-div">
                    <!--  Courses header-->
                    <div class="section-div-header">
                        <h2>My Courses</h2>
                    </div>
                    <nav class="nav-section treks_nav">
                        <ul class="treks_ul" id="myTab" role="tablist">
                            <li>
                                <button class="nav-link active" id="all-tab" data-bs-toggle="tab"
                                    data-bs-target="#all-tab-pane" type="button" role="tab" aria-controls="all-tab-pane"
                                    aria-selected="true">All</button>
                            </li>
                        </ul>
                        <div class="treks_inner_flx">
                            <img src="<?php echo $treks_src; ?>/assets/img/filter-right-logo.svg" />
                            <div class="sort_flex_bx">
                                <img src="<?php echo $treks_src; ?>/assets/img/filter-sort-logo.svg" />
                                <p>Sort by A-Z</p>
                            </div>
                        </div>
                    </nav>
                    <!-- Courses cards -->
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active recent-treks-cards-list treks_card_list" id="all-tab-pane"
                            role="tabpanel" aria-labelledby="all-tab" tabindex="0">
                            <!-- each cards  -->
                            <?php
                            foreach($courses as $course) {
                            ?>
                            <a href="<?php echo '../tl/course/'.get_post_field('post_name', $course->ID); ?>" class="treks-card-link">
                                <div class="recent-treks-card-body treks-card">
                                    <div>
                                    <?php
                                        if ( has_post_thumbnail( $course->ID ) ) {
                                            echo get_the_post_thumbnail($course->ID, "medium", array( 'class' => 'rounded' )); 
                                        } else {
                                        ?>
                                        <img width="300" height="180" src="<?php echo $treks_src; ?>/assets/img/tr_main.jpg" class="rounded wp-post-image" />
                                        <?php        
                                        }
                                    ?>
                                    </div>
                                    <div>
                                    <h3><?php echo get_the_title($course->ID); ?></h3>
                                    <span>Due date: May 17, 2023</span>
                                    </div>
                                </div>
                            </a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.3.js"
        integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <script
        src="<?php echo $treks_src; ?>/js/Animated-Circular-Progress-Bar-with-jQuery-Canvas-Circle-Progress/dist/circle-progress.js"></script>
    <script src="<?php echo $treks_src; ?>/js/custom.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
</body>

</html>
<?php endwhile; ?>