<?php
/**
 * @package WordPress
 * @subpackage WPCrest
 */

$post_format = get_post_format($post->ID);

if(empty($post_format)) {
	$post_format = 'standard';
}

if($post_format != 'standard') {
	$value = get_post_meta($post->ID, "wpc-post-format-{$post_format}", true);

	if(empty($value)) {
		$children = get_children("post_parent={$post->ID}&post_type=attachment&orderby=menu_order&order=ASC");

		if(!empty($children)) {
			foreach($children as $child) {
				if($post_format == 'gallery') {
					$small_size = wp_get_attachment_image_src($child->ID, 'medium');
				}
				elseif($post_format == 'image') {
					$small_size = wp_get_attachment_image_src($child->ID, 'large');
				}

				if($post_format == 'gallery') {
					$value[] = array('full-size-url' => $child->guid, 'small-size-url' => $small_size[0]);
				}
				elseif($post_format == 'image') {
					$value = array('full-size-url' => $child->guid, 'small-size-url' => $small_size[0]);

					break;
				}
				else {
					$value['url'] = $child->guid;

					break;
				}
			}
		}
	}

	if(!empty($value)) {
		?><div class="wpc-media"><?php

		switch($post_format) {
			case 'gallery':
				?><div class="wpc-group"><?php

				if(!is_singular('post')) {
					$value = array_slice($value, 0, 4);
				}

				foreach($value as $image) {
					?><a href="<?php echo $image['full-size-url']; ?>"><img src="<?php echo !empty($image['small-size-url']) ? $image['small-size-url'] : $image['full-size-url']; ?>" alt="" /></a><?php
				}

				?></div><?php
			break;

			case 'image':
				?><a href="<?php echo $value['full-size-url']; ?>"><img src="<?php echo !empty($value['small-size-url']) ? $value['small-size-url'] : $value['full-size-url']; ?>" alt="" /></a><?php
			break;

			case 'video':
				$filter = apply_filters('the_content', "[embed]{$value['url']}[/embed]");

				if(trim(strip_tags($filter)) == $value['url']) {
					$attachment_id = WPCrest::get_attachment_id_by_url($value['url']);

					if(!empty($attachment_id)) {
						$type = get_post_mime_type($attachment_id);
					}
					?>
					<video controls="controls" preload="none">
						<source <?php if(!empty($type)) echo 'type="' . $type . '"'; ?> src="<?php echo $value['url']; ?>" />

						<object type="application/x-shockwave-flash" data="<?php echo WPCrest::get_file_uri('/swf/flashmediaelement.swf'); ?>">
							<param name="movie" value="<?php echo WPCrest::get_file_uri('/swf/flashmediaelement.swf'); ?>" />

							<param name="flashvars" value="controls=true&amp;file=<?php echo $value['url']; ?>" />
						</object>
					</video>
					<?php
				}
				else {
					echo $filter;
				}
			break;

			case 'audio':
				$filter = apply_filters('the_content', "[embed]{$value['url']}[/embed]");

				if(trim(strip_tags($filter)) == $value['url']) {
					$attachment_id = WPCrest::get_attachment_id_by_url($value['url']);

					if(!empty($attachment_id)) {
						$type = get_post_mime_type($attachment_id);
					}
					?>
					<audio controls="controls" preload="none">
						<source <?php if(!empty($type)) echo 'type="' . $type . '"'; ?> src="<?php echo $value['url']; ?>" />

						<object type="application/x-shockwave-flash" data="<?php echo WPCrest::get_file_uri('/swf/flashmediaelement.swf'); ?>">
							<param name="movie" value="<?php echo WPCrest::get_file_uri('/swf/flashmediaelement.swf'); ?>" />

							<param name="flashvars" value="controls=true&amp;file=<?php echo $value['url']; ?>" />
						</object>
					</audio>
					<?php
				}
				else {
					echo $filter;
				}
			break;
		}

		?></div><?php
	}
}
elseif(!is_singular('post')) {
	?><div class="wpc-excerpt"><?php the_excerpt(); ?></div><?php
}
?>
<div class="wpc-format">
	<img src="<?php echo WPCrest::get_file_uri("/images/formats/{$post_format}.png"); ?>" class="wpc-type" title="<?php echo ucwords($post_format); ?>" alt="<?php echo ucwords($post_format); ?>" />

	<img src="<?php echo WPCrest::get_file_uri('/images/format-underlay.png'); ?>" class="wpc-underlay" alt="" />
</div>