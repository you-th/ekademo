<?php
/**
 * @package WordPress
 * @subpackage WPCrest
 */
get_header();

the_post();
?>
<div <?php post_class('wpc-type-page wpc-container wpc-container-short-margin wpc-container-with-line-separator wpc-group'); ?>>
	<h1 class="wpc-title"><?php the_title(); ?></h1>

	<div class="wpc-content">
	<?php
		the_content();

		wp_link_pages();
	?>
	</div>

	<?php
		if(!empty(WPCrest::$settings['configuration']['comments-support']['pages'])) {
			comments_template();
		}
	?>
</div>
<?php
get_footer();
?>