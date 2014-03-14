<?php
/**
 * @package WordPress
 * @subpackage WPCrest
 */
get_header();
?>
<div class="wpc-blog-container wpc-container wpc-container-short-margin wpc-group">
	<div class="wpc-section-heading"><span><span>
		<?php
			if(is_home()) {
				echo WPCrest::$settings['configuration']['headings']['others']['blog'];
			}
			elseif(is_category()) {
				printf(WPCrest::$settings['configuration']['headings']['taxonomies']['category'], single_cat_title(null, false));
			}
			elseif(is_tag()) {
				printf(WPCrest::$settings['configuration']['headings']['taxonomies']['tag'], single_tag_title(null, false));
			}
			elseif(is_author()) {
				the_post();
		
				printf(WPCrest::$settings['configuration']['headings']['others']['author'],  get_the_author());
		
				rewind_posts();
			}
			elseif(is_year()) {
				printf(WPCrest::$settings['configuration']['headings']['date-based']['yearly'], get_the_date('Y'));
			}
			elseif(is_month()) {
				printf(WPCrest::$settings['configuration']['headings']['date-based']['monthly'], get_the_date('F Y'));
			}
			elseif(is_day()) {
				printf(WPCrest::$settings['configuration']['headings']['date-based']['daily'], get_the_date());
			}
		?>
	</span></span></div>

	<div class="wpc-blog-main">
	<?php
		if(have_posts()) {
			?><div class="wpc-items wpc-group"><?php
		
			while(have_posts()) {
				the_post();
				?>
				<div class="wpc-type-post-preview-container"><div <?php post_class("wpc-type-post-preview"); ?>>
					<div class="wpc-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
		
					<?php get_template_part('post-format'); ?>
		
					<div class="wpc-meta">
						<span class="wpc-date"><?php the_time('F j, Y'); ?></span>
				
						<span class="wpc-category"><?php the_category(', '); ?></span>

						<br />

						<?php the_tags('<span class="wpc-tag">', ', ', '</span>'); ?>

						<span class="wpc-author"><?php the_author(); ?></span>
					</div>
				</div></div>
				<?php
			}
		
			?></div><?php
		
			get_template_part('pagination');
		}
	?>
	</div>

	<aside class="wpc-blog-sidebar">
		<?php
			if(is_active_sidebar('wpc-sidebar-widgets')) {
				?><ul><?php dynamic_sidebar('wpc-sidebar-widgets'); ?></ul><?php
			}
		?>
	</aside>
</div>
<?php
get_footer();
?>