<?php

defined( 'ABSPATH' ) || exit;

/**
 * Header for page
 */
if ( ! wp_is_block_theme() ) {
	do_action( 'learn-press/template-header' );
}
echo    '<style>
			.course-detail-info {
				display: none !important;
			}
			.post-inner
			{
				margin: 0 13% 0 13%;
			}
			.stu-assig-cards {
				display: flex;
				align-items: center;
				padding: 16px;
				gap: 8px;
				flex-wrap: wrap;
				width: 100%;
				height: auto;
				background: #ffffff;
				border-radius: 16px;
			}
			.assig-label-card {
				display: flex;
				flex-direction: column;
				padding: 0px;
				gap: 8px;
				width: 308px;
				height: 128px;
				background: #ffffff;
				border: 1px solid #eaedf1;
				border-radius: 8px;
			}
			.assig-label-card .header {
				display: flex;
				align-items: center;
				justify-content: center;
				width: 100%;
				padding: 8px;
				background: #f6f7fa;
				height: 64px;
				position: relative;
			}
			.tags-body-polygon {
				width: 48px;
				height: 42px;
				clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%);
				display: flex;
				align-items: center;
				justify-content: center;
				flex-shrink: 0;
				color: #ffffff;
			}
			.tag-assig-tetaul
			{
				padding: 8px;
			}
			.assig-label-card .tag-assig-tetaul h3 {
				font-family: "Nunito", sans-serif;
				font-style: normal;
				font-weight: 500;
				font-size: 14px;
				line-height: 16px;
				margin: 0 04px;
				color: #1a1a1a;
			}
			.assig-label-card .tag-assig-tetaul p {
				font-family: "Droid Sans", sans-serif;
				font-style: normal;
				font-weight: 400;
				font-size: 10px;
				line-height: 16px;
				margin: 0;
				color: #757575;
			}
			.student-assignment-block {
				padding:10px
			}
			.progress {
				margin-top: 8px;
			}
		</style>';
echo do_shortcode("[etb_template id='14946']");
/**
 * @since 3.0.0
 */
do_action( 'learn-press/before-main-content' );


// WP 6.4 with Block theme can't detect single course, so code while ( have_posts() ) not run.
$args = array(
	'name'        => get_query_var( LP_COURSE_CPT ),
	'post_type'   => LP_COURSE_CPT,
	'numberposts' => 1,
	'post_status' => 'any',
);
$posts = get_posts( $args );
$post  = $posts[0] ?? 0;

learn_press_get_template( 'content-single-course' );
/**
 * @since 3.0.0
 */
do_action( 'learn-press/after-main-content-single-course' );
do_action( 'learn-press/after-main-content' );

/**
 * LP sidebar
 */
do_action( 'learn-press/sidebar' );

/**
 * Footer for page
 */
if ( ! wp_is_block_theme() ) {
	do_action( 'learn-press/template-footer' );
}
