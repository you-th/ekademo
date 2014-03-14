<?php
/**
 * @package WordPress
 * @subpackage WPCrest
 */
?>
<div class="wpc-pagination wpc-ajax-request wpc-group">
	<?php
		if(function_exists('wp_pagenavi')) {
			wp_pagenavi();
		}
		else {
			?>
			<div class="wpc-group">
				<div class="alignleft"><?php previous_posts_link(); ?></div>

				<div class="alignright"><?php next_posts_link(); ?></div>
			</div>
			<?php
		}
	?>
</div>