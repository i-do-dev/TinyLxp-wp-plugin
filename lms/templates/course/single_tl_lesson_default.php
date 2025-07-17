<?php
/*
Template Name: Course-template
*/
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div class="wp-site-blocks">
		<header class="wp-block-template-part site-header">
			<?php block_header_area(); ?>
		</header>
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
				<?php
				echo "<p>" . $post->post_content . " </p>";
				?>
				<h3>Lessons </h3>
				<?php
				// Start the loop.
				$args = array(
					'posts_per_page'   => -1,
					'post_type'        => TL_LESSON_CPT,
					'meta_query' => array(
						array(
							'key'   => 'tl_course_id',
							'value' =>  $post->ID
						)
					)
				);
				$result = get_posts($args);
				echo "<ul>";
				foreach ($result as $result) {
					echo '<li>';
					echo '<a href="' . get_permalink($result->ID) . '" target="blank">' . $result->post_title . '</a>';
					echo '</li>';
				}
				echo "</ul>";

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
				echo $content;

				// if ( have_posts() ) : while ( have_posts() ) : the_post();
				while ( have_posts() ) {
					the_post();
					echo the_title();
					echo the_content();
				} 
				// https://edtechmasters.us?lti-platform&post=221468&id=63c4f42d40e99/
				$queryParam = '';
				if(isset($_GET['slide'])){
					$queryParam = "&slideNumber=". $_GET['slide'];
				}
				$toolUrl = $toolUrl . $queryParam;
				?>
				
				<iframe style="border: none;width: 100%;height: 400px;" class="" src="<?php echo site_url() ?>?lti-platform&post=<?php echo $post->ID ?>&id=<?php echo $attrId ?><?php echo $queryParam ?>"  allowfullscreen></iframe>
				<div>
					<?php
					$tags = get_the_terms($post->ID, 'tl_lesson_tag');
					if ($tags) {
						foreach ($tags as $tag) {
							$tag_link = get_tag_link($tag->term_id);
							$html = "<a href='{$tag_link}' title='{$tag->name} Tag' class='{$tag->slug}'>{$tag->name}</a>";
							$tag_names[] = $html;
						}
						echo  "<span class='dashicons dashicons-tag'></span>&nbsp&nbsp" . implode(', ', $tag_names);
					}
					?>
				</div>
			</main><!-- .site-main -->
		</div><!-- .content-area -->
		<footer class="wp-block-template-part site-footer">
			<?php block_footer_area(); ?>
		</footer>
	</div>
	<?php wp_footer(); ?>
</body>

