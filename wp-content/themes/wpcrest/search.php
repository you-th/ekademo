<?php
/**
 * @package WordPress
 * @subpackage WPCrest
 */
get_header();
?>
<div class="wpc-search-archive wpc-container wpc-container-short-margin">
	<div class="wpc-section-heading"><span><span><?php printf(WPCrest::$settings['configuration']['headings']['search-results']['main'], get_search_query()); ?></span></span></div>

	<?php
		if(have_posts()) {
			while(have_posts()) {
				the_post();

				$results[$post->post_type][$post->ID]['title'] = get_the_title();

				$results[$post->post_type][$post->ID]['permalink'] = get_permalink();

				$results[$post->post_type][$post->ID]['excerpt'] = get_the_excerpt();
			}

			if(!empty($results)) {
				foreach($results as $type => $items) {
					?>
					<div class="wpc-search-<?php echo $type; ?>">

					<div class="wpc-heading"><?php printf(WPCrest::$settings['configuration']['headings']['search-results'][$type], get_search_query()); ?></div>

					<ul>
					<?php

					foreach($items as $item) {
						?>
						<li>
						<div class="wpc-title"><a href="<?php echo $item['permalink']; ?>"><?php echo $item['title']; ?></a></div>
						<?php
						if(!empty($item['excerpt'])) {
							?><div class="wpc-excerpt"><?php echo $item['excerpt']; ?></div><?php
						}
						?>
						</li>
						<?php
					}

					?>
					</ul>
					</div>
					<?php
				}
			}

			get_template_part('pagination');
		}
		else {
			?><div class="wpc-error"><?php printf(WPCrest::$settings['configuration']['labels']['no-search-results'], get_search_query()); ?></div><?php
		}
	?>
</div>
<?php
get_footer();
?>