<?php
/**
 * @package WordPress
 * @subpackage WPCrest
 */
get_header();

the_post();
?>
<div class="wpc-container wpc-container-short-margin wpc-container-with-line-separator">
	<?php
		$arguments = array('post_type' => 'attachment', 'numberposts' => -1, 'post_status' => null, 'post_parent' => $post->post_parent, 'orderby' => 'menu_order', 'order' => 'ASC');
		
		$attachments = get_posts($arguments);
		
		if(!empty($attachments)) {
			$count = count($attachments);
		
			$i = 0;
		
			$last = null;
		
			foreach($attachments as $attachment) {
				if(!empty($get_next)) {
					$next = $attachment;
		
					break;
				}
		
				if($attachment->ID == $post->ID) {
					$previous = $last;
		
					$get_next = true;
				}
		
				$last = $attachment;
		
				$i++;
			}
		}
	?>
	<div class="wpc-ajax-container">
		<div <?php post_class('wpc-type-attachment wpc-group'); ?>>
			<h1 class="wpc-title"><?php the_title(); ?></h1>
	
			<?php
				if(wp_attachment_is_image()) {
					?>
					<div class="wpc-image"><?php echo wp_get_attachment_link($post->ID, 'large'); ?></div>
	
					<?php
						if(!empty($post->post_parent)) {
							?>
							<div class="wpc-browser">
								<?php printf(WPCrest::$settings['configuration']['labels']['attachment-browser'], '<em><a href="' . get_permalink($post->post_parent) . '">' . get_the_title($post->post_parent) . '</a></em>'); ?>
	
								<div class="wpc-navigation wpc-ajax-request">
									<?php if(!empty($previous)) : ?>
									<a href="<?php echo get_attachment_link($previous->ID); ?>"><img src="<?php echo WPCrest::get_file_uri('/images/previous-small.png'); ?>" class="wpc-previous" alt="<?php _e('Previous', 'wpcrest'); ?>" /></a>
									<?php endif; ?>
	
									<span><?php printf(WPCrest::$settings['configuration']['labels']['current-attachment'], "{$i}/{$count}"); ?></span>
	
									<?php if(!empty($next)) : ?>
									<a href="<?php echo get_attachment_link($next->ID); ?>"><img src="<?php echo WPCrest::get_file_uri('/images/next-small.png'); ?>" class="wpc-next" alt="<?php _e('Next', 'wpcrest'); ?>" /></a>
									<?php endif; ?>
								</div>
							</div>
							<?php
						}
					?>
					<?php
				}
				else {
					$attachment_url = wp_get_attachment_url($post->ID);
	
					if(substr($post->post_mime_type, 0, 5) == 'audio') {
						$type = $post->post_mime_type;
						?>
						<audio controls="controls" preload="none">
							<source type="<?php echo $type; ?>" src="<?php echo $attachment_url; ?>" />
	
							<object type="application/x-shockwave-flash" data="<?php echo WPCrest::get_file_uri('/swf/flashmediaelement.swf'); ?>">
								<param name="movie" value="<?php echo WPCrest::get_file_uri('/swf/flashmediaelement.swf'); ?>" />
	
								<param name="flashvars" value="controls=true&amp;file=<?php echo $attachment_url; ?>" />
							</object>
						</audio>
						<?php
					}
					elseif(substr($post->post_mime_type, 0, 5) == 'video') {
						$filter = apply_filters('the_content', "[embed]{$attachment_url}[/embed]");
	
						if(trim(strip_tags($filter)) == $attachment_url) {
							$type = $post->post_mime_type;
							?>
							<video controls="controls" preload="none">
								<source type="<?php echo $type; ?>" src="<?php echo $attachment_url; ?>" />
	
								<object type="application/x-shockwave-flash" data="<?php echo WPCrest::get_file_uri('/swf/flashmediaelement.swf'); ?>">
									<param name="movie" value="<?php echo WPCrest::get_file_uri('/swf/flashmediaelement.swf'); ?>" />
	
									<param name="flashvars" value="controls=true&amp;file=<?php echo $attachment_url; ?>" />
								</object>
							</video>
							<?php
						}
						else {
							echo $filter;
						}
					}
					else {
						?><a href="<?php echo wp_get_attachment_url(); ?>"><?php echo basename(get_permalink()); ?></a><?php
					}
				}
			?>
		</div>
	</div>
</div>
<?php
get_footer();
?>