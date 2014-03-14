<?php
/**
 * @package WordPress
 * @subpackage WPCrest
 */

if(WPCrest::$is_ajax === true) {
	return;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>" />

	<title><?php wp_title('|', true, 'right'); ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11" />

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<ul class="wpc-accessibility">
	<li><a href="#goto-pl"><?php _e('Go to primary links', 'wpcrest'); ?></a></li>
	<li><a href="#goto-sl"><?php _e('Go to social links', 'wpcrest'); ?></a></li>
	<li><a href="#goto-hs"><?php _e('Go to header section', 'wpcrest'); ?></a></li>
	<li><a href="#goto-sf"><?php _e('Go to site search form', 'wpcrest'); ?></a></li>
	<li><a href="#goto-ms"><?php _e('Go to main section', 'wpcrest'); ?></a></li>
	<li><a href="#goto-ps"><?php _e('Go to panel section', 'wpcrest'); ?></a></li>
	<li><a href="#goto-fs"><?php _e('Go to footer section', 'wpcrest'); ?></a></li>
</ul>

<?php
	if(empty(WPCrest::$settings['configuration']['general']['disable-page-preloader'])) {
		?><div class="wpc-loading"><span></span></div><?php
	}
?>

<div class="wpc-wrapper">
	<div class="wpc-links wpc-group">
		<div id="goto-pl" class="wpc-navigation"><?php wp_nav_menu(array('theme_location' => 'wpc-primary-navigation-menu', 'container' => false, 'fallback_cb' => false)); ?></div>

		<?php
			if(!empty(WPCrest::$settings['configuration']['social-media'])) {
				?>
				<div id="goto-sl" class="wpc-social">
					<ul>
					<?php
						foreach(WPCrest::$settings['configuration']['social-media'] as $key => $value) {
							if(is_email($value['link'])) {
								$value['link'] = 'mailto:' . $value['link'];
							}

							?><li><a href="<?php echo $value['link']; ?>"><img src="<?php echo $value['icon']; ?>" title="<?php echo $value['title']; ?>" alt="<?php echo $value['title']; ?>" /></a></li><?php
						}
					?>
					</ul>
				</div>
				<?php
			}
		?>
	</div>

	<header class="wpc wpc-group">
		<div id="goto-hs" class="alignleft">
			<?php
				if(!empty(WPCrest::$settings['configuration']['title-and-logo']['use-a-logo']) && !empty(WPCrest::$settings['configuration']['title-and-logo']['image'])) {
					if(!empty(WPCrest::$settings['configuration']['title-and-logo']['links-to'])) {
						?><a href="<?php echo WPCrest::$settings['configuration']['title-and-logo']['links-to']; ?>"><?php
					}

					?><img src="<?php echo WPCrest::$settings['configuration']['title-and-logo']['image']; ?>" alt="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>" /><?php

					if(!empty(WPCrest::$settings['configuration']['title-and-logo']['links-to'])) {
						?></a><?php
					}
				}
				else {
					?>
					<div class="wpc-title">
						<?php
							if(!empty(WPCrest::$settings['configuration']['title-and-logo']['links-to'])) {
								?><a href="<?php echo WPCrest::$settings['configuration']['title-and-logo']['links-to']; ?>"><?php
							}

							bloginfo('name');

							if(!empty(WPCrest::$settings['configuration']['title-and-logo']['links-to'])) {
								?></a><?php
							}
						?>
					</div>

					<div class="wpc-description"><?php bloginfo('description'); ?></div>
					<?php
				}
			?>
		</div>
		<div class="alignright">
			<?php
				if(!empty(WPCrest::$settings['configuration']['user-support']['enable'])) {
					?><div class="wpc-phone"><img src="<?php echo WPCrest::$settings['configuration']['user-support']['icon']; ?>" alt="" /> <span><?php echo WPCrest::$settings['configuration']['user-support']['text']; ?></span></div><?php
				}
			?>

			<form id="goto-sf" method="get" action="<?php echo esc_url(home_url( '/' )); ?>" class="wpc-search wpc-group">
				<input type="text" class="wpc-text" name="s" placeholder="<?php echo WPCrest::$settings['configuration']['search-form']['input-placeholder']; ?>" value="" />

				<input type="image" class="wpc-image" src="<?php echo WPCrest::get_file_uri('/images/magnifying-glass.png'); ?>" alt="<?php _e('Search', 'wpcrest'); ?>" />
			</form>
		</div>
	</header>

	<main id="goto-ms">