<?php
/**
 * @package WordPress
 * @subpackage WPCrest
 * Template Name: Showcase Page
 */
get_header();

the_post();

if(defined('FF_INSTALLED')) {
	$fields = ff_get_field_from_section('wpc-showcase-page', 'wpc-showcase-page', 'meta', 'post', $post->ID);
}

if(empty($fields) || $fields['content-location'] == 1) {
	?>
	<div <?php post_class('wpc-type-page wpc-container wpc-container-short-margin wpc-container-with-line-separator wpc-group'); ?>>
		<h1 class="wpc-title"><?php the_title(); ?></h1>

		<div class="wpc-content">
		<?php
			the_content();

			wp_link_pages();
		?>
		</div>
	</div>
	<?php
}

if(!empty($fields) && empty($fields['teaser']['disable'])) {
	if(!empty($fields['teaser']['title']) || !empty($fields['teaser']['description'])) {
		WPCrest::insert_teaser(array(
			'title' => $fields['teaser']['title'],
			'button-label' => $fields['teaser']['button-label'],
			'button-url' => $fields['teaser']['button-url'],
			'description' => $fields['teaser']['description']
		));
	}
}

if(!empty($fields) && $fields['content-location'] == 2) {
	?>
	<div <?php post_class('wpc-type-page wpc-container wpc-container-large-margin wpc-group'); ?>>
		<h1 class="wpc-title"><?php the_title(); ?></h1>

		<div class="wpc-content">
		<?php
			the_content();

			wp_link_pages();
		?>
		</div>
	</div>
	<?php
}

get_footer();
?>