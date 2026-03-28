<?php
$lti_metadata_repository = new TL_LTI_Metadata_Repository();
$metadata = $lti_metadata_repository->get($post->ID);

$attrId = $metadata->lti_post_attr_id;
$title = $metadata->lti_content_title;
$toolCode = $metadata->lti_tool_code;
$customAttr = $metadata->lti_custom_attr;
$toolUrl = $metadata->lti_tool_url;
$plugin_name = Tiny_LXP_Platform::get_plugin_name();
$content = '<p>' . $post->post_content . '</p>';
if ($attrId) {
	$content .= '<p> [' . $plugin_name . ' tool=' . $toolCode . ' id=' . $attrId . ' title=\"' . $title . '\" url=' . $toolUrl . ' custom=' . $customAttr . ']' . "" . '[/' . $plugin_name . ']  </p>';
}

$lessonCourseId = get_post_meta($post->ID, 'tl_course_id', true);
$trekTitle = "";
$trekPermaLink="";


$args = array(
	'post_type' => TL_COURSE_CPT,
	'orderby'    => 'ID',
	'post_status' => 'publish,draft',
	'order'    => 'DESC',
	'posts_per_page' => -1
 );
$courses = get_posts($args);
foreach( $courses as  $course){

	if($course->ID == $lessonCourseId ){
		$trekTitle = $course->post_title;
		$trekPermaLink = get_permalink($course->ID);
	}

}

$queryParam = '';
if (isset($_GET['slide'])) {
	$queryParam = "&slideNumber=" . $_GET['slide'];
}

if (isset($_GET["assignment_id"])) {
	$queryParam = $queryParam . "&assignment_id=" . $_GET["assignment_id"];	
}

$toolUrl = $toolUrl . $queryParam;
?>



<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php the_title(); ?></title>
	<link href="<?php echo $treks_src; ?>/style/main.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
	<link rel="stylesheet" href="<?php echo $treks_src; ?>/style/header-section.css" />
	<link href="<?php echo $treks_src; ?>/style/treksstyle.css" rel="stylesheet" />
</head>

<body>
	<nav class="navbar navbar-expand-lg bg-light">
		<div class="container-fluid">
			<?php include $livePath.'/trek/header-logo.php'; ?>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
		<section class="main-container nav_container">
			<!-- Nav Section -->
			<nav class="nav-section nav_section_interpendence">
				<?php include $livePath.'/trek/navigation.php'; ?>
				<!-- <ul>
					<li class="nav-section-selected">
						<img src="<?php echo $treks_src; ?>/assets/img/nav_dashboard-dots.svg" />
						<a href="/">Dashboard</a>
					</li>
					<li>
						<img src="<?php echo $treks_src; ?>/assets/img/nav_Treks.svg" />
						<a href="/treks.html">TREKs</a>
					</li>
					<li>
						<img src="<?php echo $treks_src; ?>/assets/img/nav_students.svg" />
						<a href="/">Students</a>
					</li>
					<li>
						<img src="<?php echo $treks_src; ?>/assets/img/nav_reports.svg" />
						<a href="/">Reports</a>
					</li>
				</ul> -->
			</nav>
		</section>
		<!-- Interpendence Practice Section -->
		<section class="interpendence_content_section">
			<div class="treks_practice_bx">
				<div class="practice_flx">
					<img src="<?php echo $treks_src; ?>/assets/img/nav_Treks.svg" />
					<p class="practice_text">My Courses</p>
				</div>
				<div class="practice_flx">
					<img src="<?php echo $treks_src; ?>/assets/img/bc_arrow_right.svg" />
					<p class="practice_text"><a  style = "color: #979797 !important;text-decoration: none !important;" href="<?php echo $trekPermaLink ?> "  target="_self"><?php echo $trekTitle ?></a></p>
				</div>
				<div class="practice_flx">
					<img src="<?php echo $treks_src; ?>/assets/img/bc_arrow_right.svg" />
					<p class="practice_text"><?php the_title(); ?></p>
				</div>
			</div>
			<p class="interpendence_text"><?php echo $trekTitle ?></p>
			<p class="practice_text student_text">Digital Student Journal &nbsp;<span><a id="dsj_link" href="#"><img class="copy-anchor-icon-img" src="<?php echo $treks_src; ?>/assets/img/link_icon.png" width="18" height="18" /></a></span></p>
			<iframe style="border: none;width: 100%;height: 706px;" class="" src="<?php echo site_url() ?>?lti-platform&post=<?php echo $post->ID ?>&id=<?php echo $attrId ?><?php echo $queryParam ?>" allowfullscreen></iframe>
		</section>
	</section>

	<script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
	<script src="<?php echo $treks_src; ?>/js/Animated-Circular-Progress-Bar-with-jQuery-Canvas-Circle-Progress/dist/circle-progress.js"></script>
	<script src="<?php echo $treks_src; ?>/js/custom.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery("#dsj_link").on('click',function(e){
				e.preventDefault();
                document.addEventListener('copy', function(e) {
                    e.clipboardData.setData('text/plain', jQuery('#dsj_link').attr('href'));
                    e.preventDefault();
                }, true);
                document.execCommand('copy');  
			});
		});

		window.addEventListener('message', function (event) {
			if (typeof event.data === 'object' && event.data.hasOwnProperty('currentSlide')) {
				let dsj_copy_link = location.origin + location.pathname + '?slide='+ event.data.currentSlide;
				jQuery('#dsj_link').attr('href', dsj_copy_link);
			}
		});
	</script>
</body>

</html>
