<?php
/**
 * @package WordPress
 * @subpackage WPCrest
 */
get_header();
?>
<div class="wpc-not-found wpc-container wpc-container-short-margin wpc-container-with-line-separator">
	<img src="<?php echo WPCrest::get_file_uri('/images/404-error.png'); ?>" alt="" />

	<div class="wpc-heading"><?php echo WPCrest::$settings['configuration']['headings']['others']['404']; ?></div>

	<div class="wpc-message"><?php echo WPCrest::$settings['configuration']['messages']['404']; ?></div>
</div>
<?php
get_footer();
?>