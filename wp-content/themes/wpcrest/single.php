<?php
/**
 * @package WordPress
 * @subpackage WPCrest
 */
get_header();

the_post();
?>
<div class="wpc-blog-container wpc-container wpc-container-short-margin wpc-container-with-line-separator wpc-group">
	<div class="wpc-blog-main">
		<div <?php post_class('wpc-type-post-full wpc-group'); ?>>
			<h1 class="wpc-title"><?php the_title(); ?></h1>
		
			<?php get_template_part('post-format'); ?>
		
			<div class="wpc-content">
			<?php
				the_content();
		
				wp_link_pages();
			?>
			</div>
		
			<div class="wpc-meta">
				<span class="wpc-date"><?php the_time('F j, Y'); ?></span>
		
				<span class="wpc-category"><?php the_category(', '); ?></span>
		
				<?php the_tags('<span class="wpc-tag">', ', ', '</span>'); ?>

				<span class="wpc-author"><?php the_author(); ?></span>
			</div>
		</div>
		
		<?php
			if(empty(WPCrest::$settings['configuration']['comments-support']['posts'])) {
				comments_template();
			}
		?>
	</div>

	<aside class="wpc-blog-sidebar">
		<ul><?php dynamic_sidebar('wpc-sidebar-widgets'); ?></ul>
	</aside>
</div>
<?php
get_footer();
?>