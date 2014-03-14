<?php
/**
 * @package WordPress
 * @subpackage WPCrest
 */

if(WPCrest::$is_ajax === true) {
	return;
}
?>
	</main>
	<?php
		if(is_active_sidebar('wpc-panel-widgets')) {
			?>
			<div id="goto-ps" class="wpc-panel wpc-group">
				<ul><?php dynamic_sidebar('wpc-panel-widgets'); ?></ul>
			</div>
			<?php
		}
	?>

	<footer id="goto-fs" class="wpc">
		<div class="wpc-copyright">
			<?php printf(WPCrest::$settings['configuration']['messages']['copyright'], '<a href="' . home_url('/') . '">' . get_bloginfo('name') . '</a>', get_bloginfo('name')); ?>

			<?php
				if(!empty(WPCrest::$settings['configuration']['general']['enable-credit-link'])) {
					?><br /><?php

					printf(__('A WordPress Theme by %s.', 'wpcrest'), '<a href="http://www.rhyzz.com/wpcrest.html">Rhyzz</a>');
				}
			?>
		</div>

		<div class="wpc-wp-footer"><?php wp_footer(); ?></div>
	</footer>
</div>

<?php if(empty(WPCrest::$settings['configuration']['general']['disable-scheme-switcher'])) : ?>
<div class="wpc-schemes">
	<div class="wpc-list">
		<div class="wpc-heading"><?php echo WPCrest::$settings['configuration']['labels']['font-size']; ?></div>

		<div class="wpc-font-size">
			<img src="<?php echo WPCrest::get_file_uri('/images/zoom-out.png'); ?>" class="wpc-decrease" alt="<?php _e('Decrease Size', 'wpcrest'); ?>" title="<?php _e('Decrease Size', 'wpcrest'); ?>" />
			<img src="<?php echo WPCrest::get_file_uri('/images/refresh.png'); ?>" class="wpc-default" alt="<?php _e('Default Size', 'wpcrest'); ?>" title="<?php _e('Default Size', 'wpcrest'); ?>" />
			<img src="<?php echo WPCrest::get_file_uri('/images/zoom-in.png'); ?>" class="wpc-increase" alt="<?php _e('Increase Size', 'wpcrest'); ?>" title="<?php _e('Increase Size', 'wpcrest'); ?>" />
		</div>

		<?php
			if(!empty(WPCrest::$skins)) {
				?>
				<div class="wpc-heading"><?php echo WPCrest::$settings['configuration']['labels']['skin']; ?></div>

				<ul class="wpc-skins wpc-short wpc-group">
				<?php

				foreach(WPCrest::$skins as $skin => $value) {
					if($skin == 'none') {
						$icon = '/images/x-mark.png';
					}
					else {
						$icon = "/schemes/skins/{$skin}/preview.png";
					}
					?><li class="<?php echo $skin; ?>"><img src="<?php echo WPCrest::get_file_uri($icon); ?>" alt="<?php echo $value; ?>" title="<?php echo $value; ?>" /></li><?php
				}

				?></ul><?php
			}

			if(!empty(WPCrest::$background_underlays)) {
				?>
				<div class="wpc-heading"><?php echo WPCrest::$settings['configuration']['labels']['underlay-background']; ?></div>

				<ul class="wpc-background-underlays wpc-long wpc-group">
				<?php

				foreach(WPCrest::$background_underlays as $background_underlay => $value) {
					if($background_underlay == 'default') {
						$icon = '/images/reset.png';
					}
					elseif($background_underlay == 'custom') {
						$icon = '/images/custom.png';
					}
					else {
						if(!empty($value['options']['preview'])) {
							$filename = $value['options']['preview'];
						}
						else {
							$filename = $value['properties']['background-image'];
						}

						$icon = "/schemes/background-underlays/{$filename}";
					}
					?><li class="<?php echo $background_underlay; ?> <?php if(!empty($value['options']['type']) && $value['options']['type'] == 'dark') echo 'wpc-dark'; elseif(!empty($value['options']['type']) && $value['options']['type'] == 'light') echo 'wpc-light'; ?>" <?php if(!empty($value['properties'])) echo "data-properties='" . json_encode($value['properties']) . "'"; ?>><img src="<?php echo WPCrest::get_file_uri($icon); ?>" alt="<?php echo $value['options']['name']; ?>" title="<?php echo $value['options']['name']; ?>" /></li><?php
				}

				?></ul><?php
			}

			if(!empty(WPCrest::$background_overlays)) {
				?>
				<div class="wpc-heading"><?php echo WPCrest::$settings['configuration']['labels']['overlay-background']; ?></div>

				<ul class="wpc-background-overlays wpc-long wpc-group">
				<?php

				foreach(WPCrest::$background_overlays as $background_overlay => $value) {
					if($background_overlay == 'default') {
						$icon = '/images/reset.png';
					}
					elseif($background_overlay == 'custom') {
						$icon = '/images/custom.png';
					}
					else {
						if(!empty($value['options']['preview'])) {
							$filename = $value['options']['preview'];
						}
						else {
							$filename = $value['properties']['background-image'];
						}

						$icon = "/schemes/background-overlays/{$filename}";
					}
					?><li class="<?php echo $background_overlay; ?> <?php if(!empty($value['options']['type']) && $value['options']['type'] == 'dark') echo 'wpc-dark'; elseif(!empty($value['options']['type']) && $value['options']['type'] == 'light') echo 'wpc-light'; ?>" <?php if(!empty($value['properties'])) echo "data-properties='" . json_encode($value['properties']) . "'"; ?>><img src="<?php echo WPCrest::get_file_uri($icon); ?>" alt="<?php echo $value['options']['name']; ?>" title="<?php echo $value['options']['name']; ?>" /></li><?php
				}

				?></ul><?php
			}
		?>
	</div>

	<div class="wpc-toggle">
		<img src="<?php echo WPCrest::get_file_uri('/images/toggle-button.png'); ?>" alt="<?php _e('Scheme Switcher Toggle', 'wpcrest'); ?>" />
	</div>
</div>
<?php endif; ?>
</body>
</html>