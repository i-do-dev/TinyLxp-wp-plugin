<?php
global $treks_src;
$args = array(
    'posts_per_page'   => -1,
    'post_type'        => LP_COURSE_CPT,
    'order' => 'asc'
);
$courses = get_posts($args);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Courses</title>
    <link href="<?php echo $treks_src; ?>/style/main.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo $treks_src; ?>/style/header-section.css" />
    <link rel="stylesheet" href="<?php echo $treks_src; ?>/style/treksstyle.css" />
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
        .welcome-section {
            max-width: 1440px;
            margin: auto;
            padding: 0 64px;
        }
        .treks-card-saved {
        position: absolute;
        z-index: 2;
        margin-top: 10px;
        margin-left: 284px;
      }
      .course-img:hover {
        filter: blur(5px);
      }
      .dot {
        width: 6px;
        height: 6px;
        background-color: #757575;
        border-radius: 50%;
        margin: 2px 0;
    }
    </style>
</head>

<body>

    <!-- Header Section -->
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

    <!-- Nav Section -->
    <section class="main-container">
        <nav class="nav-section">
            <?php include $livePath.'/lxp/admin-nav.php'; ?>
        </nav>
    </section>

    
    <section class="welcome-section">
        
        <div class="welcome-content">
            <h2 class="welcome-heading">COURSES</h2>
            <p class="welcome-text">Manage your Courses</p>
            <br />
        </div>

        
        <section class="main-container treks_main_container">
            <!-- Recent Filters & Courses flex -->
            <div class="filter_treks_flx">
                <!-- Recent Courses -->
                <section class="recent-treks-section filter_treks_section filter_my_treks_sec">
                    <div class="recent-treks-section-div">
                        <!--  Courses header-->
                        <div class="section-div-header">
                            <h2>My Courses</h2>
                        </div>
                        <nav class="nav-section treks_nav">
                            <ul class="treks_ul" id="myTab" role="tablist">
                                <li>
                                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-tab-pane" type="button" role="tab" aria-controls="all-tab-pane" aria-selected="true">All</button>
                                </li>
                            </ul>
                        </nav>
                        <!-- Courses cards -->
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active recent-treks-cards-list treks_card_list" id="all-tab-pane"
                                role="tabpanel" aria-labelledby="all-tab" tabindex="0">
                                <!-- each cards  -->
                                <?php
                                foreach($courses as $course) {
                                ?>
                                    <div class="recent-treks-card-body treks-card">
                                        <div class="treks-card-saved">
                                            <div class="dropdown">
                                                <i id="dropdownMenu<?php echo $course->ID ?>" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <div class="dot"></div>
                                                    <div class="dot"></div>
                                                    <div class="dot"></div>
                                                </i>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenu<?php echo $course->ID ?>">
                                                    <button class="dropdown-item" type="button" onclick="onCourseEdit('<?php echo $course->ID ?>')">
                                                        <img src="<?php echo $treks_src; ?>/assets/img/edit.svg" alt="logo">
                                                        Edit</button>
                                                    
                                                </div>
                                            </div>
                                        
                                        </div>
                                        
                                        <div class='course-img' id="img-id-<?php echo $course->ID; ?>">
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
                                        </div>
                                    </div>
                                
                                <?php
                                }
                                ?>
                            </div>

                        </div>
                    </div>
                </section>
            </div>
        </section>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.3.js"
        integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <script
        src="<?php echo $treks_src; ?>/js/Animated-Circular-Progress-Bar-with-jQuery-Canvas-Circle-Progress/dist/circle-progress.js"></script>
    <script src="<?php echo $treks_src; ?>/js/custom.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    
    <script>
        $(document).ready(function () {
            $('#tekversion-drop-down').change(function () {
                var tekversion = $(this).val();
                var url = new URL(window.location.href);
                url.searchParams.set('tekversion', tekversion);
                window.location.href = url.href;
            });
        });
    </script>
    <script type="text/javascript">
        function onCourseEdit(x) {
            // redirect to wp admin post edit page in new tab
            window.open('<?php echo get_admin_url(); ?>post.php?post=' + x + '&action=edit', '_blank');
        }
    </script>
</body>

</html>