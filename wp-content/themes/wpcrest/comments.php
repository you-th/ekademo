<?php
/**
 * @package WordPress
 * @subpackage WPCrest
 */

if(!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) {
	die('Please do not load this page directly. Thanks!');
}
?>
<div id="comments" class="wpc-comments">
<?php
if(post_password_required()) {
	?><div class="wpc-message"><?php _e('This post is password protected. Enter the password to view comments.', 'wpcrest'); ?></div><?php
}
else {
	if(have_comments()) {
		?>
		<div class="wpc-heading"><?php printf(_n('One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), 'wpcrest'), get_comments_number(), get_the_title()); ?></div>

		<div class="wpc-commentlist"><ul><?php wp_list_comments('avatar_size=64&callback=WPCrest::start_el&end-callback=WPCrest::end_el'); ?></ul></div>

		<div class="wpc-pagination wpc-ajax-request wpc-group">
			<div class="alignleft"><?php previous_comments_link(); ?></div>

			<div class="alignright"><?php next_comments_link(); ?></div>
		</div>
		<?php
	}

	$commenter = wp_get_current_commenter();

	$req = get_option('require_name_email');

	$aria_req = ($req ? " aria-required='true'" : '');

	$arguments = array(
		'comment_field' =>  '<div class="wpc-field"><label for="comment">' . __('Comment', 'wpcrest') . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true">' . '</textarea></div>',
		'must_log_in' => '<div class="wpc-message">' . sprintf(__('You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url(apply_filters('the_permalink', get_permalink()), 'wpcrest')) . '</div>',
		'logged_in_as' => '<div class="wpc-message">' . sprintf(__('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>'), admin_url('profile.php'), $user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink()), 'wpcrest')) . '</div>',
		'comment_notes_before' => null,
		'comment_notes_after' => '<div class="wpc-message">' . sprintf(__('You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'wpcrest'), ' <code>' . allowed_tags() . '</code>') . '</div>',
		'fields' => apply_filters('comment_form_default_fields', array(
				'author' => '<div class="wpc-field">' . '<label for="author">' . __('Name', 'wpcrest') . ($req ? ' <span>*</span>' : '') . '</label> ' . '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' /></div>',
				'email' => '<div class="wpc-field"><label for="email">' . __('Email', 'wpcrest') . ($req ? ' <span>*</span>' : '') . '</label> ' . '<input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' /></div>',
				'url' => '<div class="wpc-field"><label for="url">' . __('Website', 'wpcrest') . '</label>' . '<input id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" /></div>'
			)
		),
	);

	comment_form($arguments);
}
?>
</div>