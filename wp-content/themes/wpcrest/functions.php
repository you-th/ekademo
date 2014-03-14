<?php
/**
 * @package WordPress
 * @subpackage WPCrest
 */

if(!isset($content_width)) {
	$content_width = 600;
}

class WPCrest {
	static $already_loaded = false, $settings = array(), $upload_directory_url, $skins, $background_underlays, $background_overlays;

	static $is_ajax = false;

	static function load() {
		if(!empty(self::$already_loaded)) {
			return;
		}

		add_theme_support('automatic-feed-links');

		add_theme_support('post-formats', array('gallery', 'image', 'video', 'audio'));

		register_nav_menus(array('wpc-primary-navigation-menu' => __('Primary Navigation Menu', 'wpcrest')));

		register_sidebar(array('id' => 'wpc-sidebar-widgets', 'name' => __('Sidebar Widgets', 'wpcrest'), 'before_title' => '<div class="wpc-heading"><span><span>', 'after_title' => '</span></span></div>'));

		register_sidebar(array('id' => 'wpc-panel-widgets', 'name' => __('Panel Widgets', 'wpcrest'), 'before_title' => '<div class="wpc-heading">', 'after_title' => '</div>'));

		add_action('after_setup_theme', array('WPCrest', 'load_theme_textdomain'));

		add_action('after_setup_theme', array('WPCrest', 'after_setup_theme'));

		add_action('init', array('WPCrest', 'fields_framework'));

		add_action('init', array('WPCrest', 'load_configuration'));

		add_action('widgets_init', array('WPCrest', 'widgets_init'));

		if(!is_admin()) {
			add_action('wp_enqueue_scripts', array('WPCrest', 'wp_enqueue_scripts'));

			add_action('wp_head', array('WPCrest', 'wp_head'));

			add_action('widget_tag_cloud_args', array('WPCrest', 'widget_tag_cloud_args'));

			add_filter('wp_title', array('WPCrest', 'wp_title'), 10, 2);

			add_filter('wp_link_pages', array('WPCrest', 'wp_link_pages'));
		}

		$upload_directory = wp_upload_dir();

		if(!empty($upload_directory)) {
			self::$upload_directory_url = $upload_directory['baseurl'] . '/';
		}

		self::$skins = array(
			'classic' => __('Classic', 'wpcrest'),
			'anakiwa' => __('Anakiwa', 'wpcrest'),
			'outerspace' => __('OuterSpace', 'wpcrest'),
			'jaffa' => __('Jaffa', 'wpcrest'),
			'cloudy' => __('Cloudy', 'wpcrest'),
			'moonlit' => __('Moonlit', 'wpcrest'),
			'none' => __('None', 'wpcrest')
		);

		self::$background_underlays = array(
			'yummy-gradient' => array('properties' => array('background-color' => '#fae4b5', 'background-repeat' => 'repeat-x', 'background-image' => 'yummy-gradient.png'), 'options' => array('name' => __('Yummy Gradient', 'wpcrest'))),
			'simple-blue-gradient' => array('properties' => array('background-color' => '#6aa1da', 'background-repeat' => 'repeat-x', 'background-image' => 'simple-blue-gradient.png'), 'options' => array('name' => __('Simple Blue Gradient', 'wpcrest'))),
			'light-and-dark-green' => array('properties' => array('background-color' => '#c2df93', 'background-repeat' => 'repeat-x', 'background-image' => 'light-and-dark-green.png'), 'options' => array('name' => __('Light and Dark Green', 'wpcrest'))),
			'blueishgreenish' => array('properties' => array('background-color' => '#167b9d', 'background-repeat' => 'repeat-x', 'background-image' => 'blueishgreenish.png'), 'options' => array('name' => __('Blueish Greenish', 'wpcrest'))),
			'light-brown' => array('properties' => array('background-color' => '#8c8479', 'background-repeat' => 'repeat-x', 'background-image' => 'light-brown.png'), 'options' => array('name' => __('Light Brown', 'wpcrest'))),
			'melon' => array('properties' => array('background-color' => '#ffe6de', 'background-repeat' => 'repeat-x', 'background-image' => 'melon.png'), 'options' => array('name' => __('Melon', 'wpcrest'))),
			'light-purple' => array('properties' => array('background-color' => '#ababff', 'background-repeat' => 'repeat-x', 'background-image' => 'light-purple.png'), 'options' => array('name' => __('Light Purple', 'wpcrest'))),
			'regular-blue' => array('properties' => array('background-color' => '#00284d', 'background-repeat' => 'repeat-x', 'background-image' => 'regular-blue.png'), 'options' => array('name' => __('Regular Blue', 'wpcrest'))),
			'light-blue' => array('properties' => array('background-color' => '#f5fafb', 'background-repeat' => 'repeat-x', 'background-image' => 'light-blue.png'), 'options' => array('name' => __('Light Blue', 'wpcrest'))),
			'blurred-golden' => array('properties' => array('background-color' => '#f49d3c', 'background-repeat' => 'repeat-x', 'background-image' => 'blurred-golden.png'), 'options' => array('name' => __('Blurred Golden', 'wpcrest'))),
			'light-green' => array('properties' => array('background-color' => '#d6dbbf', 'background-repeat' => 'repeat-x', 'background-image' => 'light-green.png'), 'options' => array('name' => __('Light Green', 'wpcrest'))),
			'simple-purple' => array('properties' => array('background-color' => '#6d0019', 'background-repeat' => 'repeat-x', 'background-image' => 'simple-purple.png'), 'options' => array('name' => __('Simple Purple', 'wpcrest'))),
			'mix-of-three' => array('properties' => array('background-color' => '#b9b26c', 'background-repeat' => 'repeat-x', 'background-image' => 'mix-of-three.png'), 'options' => array('name' => __('Mix of Three', 'wpcrest'))),
			'simple-brown' => array('properties' => array('background-color' => '#312817', 'background-repeat' => 'repeat-x', 'background-image' => 'simple-brown.png'), 'options' => array('name' => __('Simple Brown', 'wpcrest'))),
			'diamond-green' => array('properties' => array('background-color' => '#c3e094', 'background-repeat' => 'repeat-x', 'background-image' => 'diamond-green.png'), 'options' => array('name' => __('Diamond Green', 'wpcrest'))),
			'default' => array('properties' => null, 'options' => array('name' => __('Default', 'wpcrest'))),
		);

		self::$background_overlays = array(
			'shower-lines' => array('properties' => array('background-image' => 'shower-lines.png'), 'options' => array('name' => __('Shower Lines', 'wpcrest'), 'type' => 'dark')),
			'random-dots' => array('properties' => array('background-image' => 'random-dots.png'), 'options' => array('name' => __('Random Dots', 'wpcrest'), 'type' => 'dark')),
			'45-degree-fabric' => array('properties' => array('background-image' => '45-degree-fabric.png'), 'options' => array('name' => __('45 Degree Fabric', 'wpcrest'), 'type' => 'light', 'preview' => '45-degree-fabric-preview.png')),
			'woven' => array('properties' => array('background-image' => 'woven.png'), 'options' => array('name' => __('Woven', 'wpcrest'), 'type' => 'dark')),
			'slant-bricks' => array('properties' => array('background-image' => 'slant-bricks.png'), 'options' => array('name' => __('Slant Bricks', 'wpcrest'), 'type' => 'dark')),
			'noisy' => array('properties' => array('background-image' => 'noisy.png'), 'options' => array('name' => __('Noisy', 'wpcrest'), 'type' => 'dark', 'preview' => 'noisy-preview.png')),
			'textured-stripes' => array('properties' => array('background-image' => 'textured-stripes.png'), 'options' => array('name' => __('Textured Stripes', 'wpcrest'), 'type' => 'dark', 'preview' => 'textured-stripes-preview.png')),
			'brick-wall' => array('properties' => array('background-image' => 'brick-wall.png'), 'options' => array('name' => __('Brick Wall', 'wpcrest'), 'type' => 'dark', 'preview' => 'brick-wall-preview.png')),
			'triangle' => array('properties' => array('background-image' => 'triangle.png'), 'options' => array('name' => __('Triangle', 'wpcrest'), 'type' => 'dark', 'preview' => 'triangle-preview.png')),
			'small-squares' => array('properties' => array('background-image' => 'small-squares.png'), 'options' => array('name' => __('Small Squares', 'wpcrest'), 'type' => 'dark')),
			'square-tiles' => array('properties' => array('background-image' => 'square-tiles.png'), 'options' => array('name' => __('Square Tiles', 'wpcrest'), 'type' => 'dark')),
			'gray-squares' => array('properties' => array('background-image' => 'gray-squares.png'), 'options' => array('name' => __('Gray Squares', 'wpcrest'), 'type' => 'dark')),
			'overlapping-squares' => array('properties' => array('background-image' => 'overlapping-squares.png'), 'options' => array('name' => __('Overlapping Squares', 'wpcrest'), 'type' => 'dark')),
			'default' => array('properties' => null, 'options' => array('name' => __('Default', 'wpcrest'))),
		);
	}

	static function load_configuration() {
		if(!defined('FF_INSTALLED')) {
			self::$settings['configuration'] = array(
				'title-and-logo' => array(
					'use-a-logo' => '0',
					'image' => null,
					'links-to' => null,
				),
				'user-support' => array(
					'enable' => '0',
					'icon' => self::get_file_uri('/images/phone.png'),
					'text' => null,
				),
				'search-form' => array(
					'input-placeholder' => __('Search site here&hellip;', 'wpcrest'),
				),
				'social-media' => array(
				),
				'comments-support' => array(
					'posts' => '0',
					'pages' => '0',
				),
				'general' => array(
					'enable-credit-link' => '0',
					'disable-page-preloader' => '0',
					'disable-ajax-preloader' => '0',
					'disable-ajax-pagination' => '0',
					'default-skin' => 'classic',
					'default-background-underlay' => 'yummy-gradient',
					'custom-background-underlay' => array(
						'enable' => '0',
						'color' => null,
						'position' => array(
							'constant' => 'left top',
							'variable' => array(
								'x' => null,
								'y' => null,
							),
						),
						'size' => array(
							'constant' => 'auto',
							'variable' => array(
								'x' => null,
								'y' => null,
							),
						),
						'repeat' => null,
						'attachment' => null,
						'image' => null,
					),
					'default-background-overlay' => 'shower-lines',
					'custom-background-overlay' => array(
						'enable' => '0',
						'color' => null,
						'position' => array(
							'constant' => 'left top',
							'variable' => array(
								'x' => null,
								'y' => null,
							),
						),
						'size' => array(
							'constant' => 'auto',
							'variable' => array(
								'x' => null,
								'y' => null,
							),
						),
						'repeat' => null,
						'attachment' => null,
						'image' => null,
					),
					'disable-scheme-switcher' => '0',
					'external-urls-new-window' => '0',
					'media-urls-new-window' => '0',
					'disable-qtip' => '0',
					'disable-colorbox-images' => '0',
					'disable-colorbox-posts' => '0',
				),
				'messages' => array(
					'copyright' => __('Copyright &copy; 2013 %1$s. All Rights Reserved.', 'wpcrest'),
					404 => __('The page requested could not be found!', 'wpcrest'),
				),
				'headings' => array(
					'taxonomies' => array(
						'category' => __('Category Archives: %s', 'wpcrest'),
						'tag' => __('Tag Archives: %s', 'wpcrest'),
					),
					'date-based' => array(
						'yearly' => __('Yearly Archives: %s', 'wpcrest'),
						'monthly' => __('Monthly Archives: %s', 'wpcrest'),
						'daily' => __('Daily Archives: %s', 'wpcrest'),
					),
					'search-results' => array(
						'main' => __('Search Results for "%s"', 'wpcrest'),
						'post' => __('Posts matching query "%s"', 'wpcrest'),
						'page' => __('Pages matching query "%s"', 'wpcrest'),
					),
					'others' => array(
						'blog' => __('Blog', 'wpcrest'),
						'author' => __('Author Archives: %s', 'wpcrest'),
						404 => __('404', 'wpcrest'),
					),
				),
				'labels' => array(
					'attachment-browser' => __('Browser for attachments of %s', 'wpcrest'),
					'current-attachment' => __('Image %s', 'wpcrest'),
					'font-size' => __('Font Size', 'wpcrest'),
					'skin' => __('Select Skin', 'wpcrest'),
					'underlay-background' => __('Select Underlay Background', 'wpcrest'),
					'overlay-background' => __('Select Overlay Background', 'wpcrest'),
					'no-search-results' => __('No results found for searched query "%s"', 'wpcrest'),
				),
			);
		}
		else {
			self::$settings['configuration'] = ff_get_field_from_section('wpc-configuration', 'wpc-configuration', 'options');
		}

		self::$settings['dynamic'] = array(
			'loaded-skin' => self::$settings['configuration']['general']['default-skin'],
			'loaded-background-underlay' => self::$settings['configuration']['general']['default-background-underlay'],
			'loaded-background-overlay' => self::$settings['configuration']['general']['default-background-overlay'],
		);

		if(empty(self::$settings['configuration']['general']['disable-scheme-switcher'])) {
			if(!empty($_COOKIE['wpc-active-skin'])) {
				self::$settings['dynamic']['loaded-skin'] = $_COOKIE['wpc-active-skin'];
			}

			if(!empty($_COOKIE['wpc-active-background-underlay'])) {
				self::$settings['dynamic']['loaded-background-underlay'] = $_COOKIE['wpc-active-background-underlay'];
			}

			if(!empty($_COOKIE['wpc-active-background-overlay'])) {
				self::$settings['dynamic']['loaded-background-overlay'] = $_COOKIE['wpc-active-background-overlay'];
			}
		}

		if(!empty(self::$settings['configuration']['general']['custom-background-underlay']['enable'])) {
			$properties = array(
				'background-color' => self::$settings['configuration']['general']['custom-background-underlay']['color'],
				'background-position' => self::$settings['configuration']['general']['custom-background-underlay']['position'],
				'background-size' => self::$settings['configuration']['general']['custom-background-underlay']['size'],
				'background-repeat' => self::$settings['configuration']['general']['custom-background-underlay']['repeat'],
				'background-attachment' => self::$settings['configuration']['general']['custom-background-underlay']['attachment'],
				'background-image' => self::$settings['configuration']['general']['custom-background-underlay']['image'],
			);

			self::$background_underlays['custom'] = array('properties' => $properties, 'options' => array('name' => __('Custom', 'wpcrest')));
		}

		if(!empty(self::$settings['configuration']['general']['custom-background-overlay']['enable'])) {
			$properties = array(
				'background-color' => self::$settings['configuration']['general']['custom-background-overlay']['color'],
				'background-position' => self::$settings['configuration']['general']['custom-background-overlay']['position'],
				'background-size' => self::$settings['configuration']['general']['custom-background-overlay']['size'],
				'background-repeat' => self::$settings['configuration']['general']['custom-background-overlay']['repeat'],
				'background-attachment' => self::$settings['configuration']['general']['custom-background-overlay']['attachment'],
				'background-image' => self::$settings['configuration']['general']['custom-background-overlay']['image'],
			);

			self::$background_overlays['custom'] = array('properties' => $properties, 'options' => array('name' => __('Custom', 'wpcrest')));
		}
	}

	static function fields_framework() {
		if(!defined('FF_INSTALLED')) {
			return;
		}


		ff_create_section('wpc-configuration', 'admin_sub_menu', array(
			'parent_uid' => 'themes.php',
			'page_title' => __('Configuration', 'wpcrest'),
			'menu_title' => __('Configuration', 'wpcrest'),
		));



		ff_create_field('wpc-configuration', 'group', array(
			'minimal' => true,
			'label' => __('Configuration', 'wpcrest'),
		));

		ff_add_field_to_section('wpc-configuration', 'wpc-configuration');



		ff_create_field('wpc-configuration-title-and-logo', 'group', array(
			'name' => 'title-and-logo',
			'label' => __('Title and Logo', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration', 'wpc-configuration-title-and-logo');

		ff_create_field('wpc-configuration-title-and-logo-use-a-logo', 'radio', array(
			'name' => 'use-a-logo',
			'label' => __('Use a Logo', 'wpcrest'),
			'description' => __("Check this option if you want to replace the Title and Description with a Logo instead. Below you'll have to enter the URL which points to the image you want to use as a Logo.", 'wpcrest'),
			'options' => array(
				'0' => __('No', 'wpcrest'),
				'1' => __('Yes', 'wpcrest'),
			),
			'default_value' => '0',
		));

		ff_add_field_to_field_group('wpc-configuration-title-and-logo', 'wpc-configuration-title-and-logo-use-a-logo');

		ff_create_field('wpc-configuration-title-and-logo-image', 'media', array(
			'name' => 'image',
			'label' => __('Logo Image', 'wpcrest'),
			'description' => __("This will only be used if you set the above option to true and you don't leave this field blank. You can either upload a new image you want to use as a Logo or enter the URL of an existing image.", 'wpcrest'),
			'library' => 'image',
		));

		ff_add_field_to_field_group('wpc-configuration-title-and-logo', 'wpc-configuration-title-and-logo-image');

		ff_create_field('wpc-configuration-title-or-logo-links-to', 'text', array(
			'name' => 'links-to',
			'label' => __('Link Title or Logo to', 'wpcrest'),
			'description' => __("Enter URL you want the Title or Logo to link to. Leave this field blank if you don't want the Title or Logo to be a link.", 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-title-and-logo', 'wpc-configuration-title-or-logo-links-to');



		ff_create_field('wpc-configuration-user-support', 'group', array(
			'name' => 'user-support',
			'label' => __('User Support', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration', 'wpc-configuration-user-support');

		ff_create_field('wpc-configuration-user-support-enable', 'radio', array(
			'name' => 'enable',
			'label' => __('Enable User Support?', 'wpcrest'),
			'description' => __('If enabled, you can use this section to display something helpful to visitors using which they can contact you. This could be your phone number, email address, etc.', 'wpcrest'),
			'options' => array(
				'0' => __('No', 'wpcrest'),
				'1' => __('Yes', 'wpcrest'),
			),
			'default_value' => '0'
		));

		ff_add_field_to_field_group('wpc-configuration-user-support', 'wpc-configuration-user-support-enable');

		ff_create_field('wpc-configuration-user-support-icon', 'media', array(
			'name' => 'icon',
			'label' => __('User Support Icon', 'wpcrest'),
			'description' => sprintf(__("By default a telephone icon is used for the User Support section. You can upload an image here if you want to use a custom icon. Or you can use the following if you want to use an email icon: %s", 'wpcrest'), '<br /><u>' . self::get_file_uri('/images/email.png') . '</u>'),
			'library' => 'image',
			'default_value' => self::get_file_uri('/images/phone.png')
		));

		ff_add_field_to_field_group('wpc-configuration-user-support', 'wpc-configuration-user-support-icon');

		ff_create_field('wpc-configuration-user-support-text', 'text', array(
			'name' => 'text',
			'label' => __('User Support Text', 'wpcrest'),
			'description' => __('Enter your Call to Action text here', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-user-support', 'wpc-configuration-user-support-text');



		ff_create_field('wpc-configuration-search-form', 'group', array(
			'name' => 'search-form',
			'label' => __('Search Form', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration', 'wpc-configuration-search-form');

		ff_create_field('wpc-configuration-search-form-input-placeholder', 'text', array(
			'name' => 'input-placeholder',
			'label' => __('Input Placeholder', 'wpcrest'),
			'description' => __('You can use this field to replace the default search input placeholder with something custom', 'wpcrest'),
			'default_value' => __('Search site here&hellip;', 'wpcrest')
		));

		ff_add_field_to_field_group('wpc-configuration-search-form', 'wpc-configuration-search-form-input-placeholder');



		$directory = get_template_directory() . '/images/icons/social/';

		$handle = opendir($directory);

		$description = '<br />';

		while($file = readdir($handle)) {
			if($file != '.' && $file != '..') {
				$description .= '<br />' . self::get_file_uri("/images/icons/social/{$file}");
			}
		}

		ff_create_field('wpc-configuration-social-media', 'group', array(
			'name' => 'social-media',
			'label' => __('Social Media', 'wpcrest'),
			'repeatable' => true,
			'description' => sprintf(__('Here are few social icons that come with this theme: %s', 'wpcrest'), $description),
		));

		ff_add_field_to_field_group('wpc-configuration', 'wpc-configuration-social-media');



		ff_create_field('wpc-configuration-social-media-icon', 'media', array(
			'name' => 'icon',
			'label' => __('Icon', 'wpcrest'),
			'library' => 'image',
		));

		ff_add_field_to_field_group('wpc-configuration-social-media', 'wpc-configuration-social-media-icon');

		ff_create_field('wpc-configuration-social-media-link', 'text', array(
			'name' => 'link',
			'label' => __('Link', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-social-media', 'wpc-configuration-social-media-link');

		ff_create_field('wpc-configuration-social-media-title', 'text', array(
			'name' => 'title',
			'label' => __('Title', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-social-media', 'wpc-configuration-social-media-title');



		ff_create_field('wpc-configuration-comments-support', 'group', array(
			'name' => 'comments-support',
			'label' => __('Comments Support', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration', 'wpc-configuration-comments-support');

		ff_create_field('wpc-configuration-comments-support-posts', 'radio', array(
			'name' => 'posts',
			'label' => __('Disable for Posts?', 'wpcrest'),
			'description' => __("Comments are by default enabled for Posts. Disable if you don't want to display comments for posts.", 'wpcrest'),
			'options' => array(
				'0' => __('No', 'wpcrest'),
				'1' => __('Yes', 'wpcrest'),
			),
			'default_value' => '0'
		));

		ff_add_field_to_field_group('wpc-configuration-comments-support', 'wpc-configuration-comments-support-posts');

		ff_create_field('wpc-configuration-comments-support-pages', 'radio', array(
			'name' => 'pages',
			'label' => __('Enable for Pages?', 'wpcrest'),
			'description' => __("Comments are by default disabled for Pages. Enable if you want to display comments for Pages.", 'wpcrest'),
			'options' => array(
				'0' => __('No', 'wpcrest'),
				'1' => __('Yes', 'wpcrest'),
			),
			'default_value' => '0'
		));

		ff_add_field_to_field_group('wpc-configuration-comments-support', 'wpc-configuration-comments-support-pages');



		ff_create_field('wpc-configuration-general', 'group', array(
			'name' => 'general' ,
			'label' => __('General', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration', 'wpc-configuration-general');

		ff_create_field('wpc-configuration-general-enable-credit-link', 'radio', array(
			'name' => 'enable-credit-link',
			'label' => __('Enable Credit Link', 'wpcrest'),
			'description' => __('Enable Credit Link shown in footer. <strong>Support this theme by enabling this option!</strong>', 'wpcrest'),
			'options' => array(
				'0' => __('No', 'wpcrest'),
				'1' => __('Yes', 'wpcrest'),
			),
			'default_value' => '0'
		));

		ff_add_field_to_field_group('wpc-configuration-general', 'wpc-configuration-general-enable-credit-link');

		ff_create_field('wpc-configuration-general-disable-page-preloader', 'radio', array(
			'name' => 'disable-page-preloader',
			'label' => __('Disable Page Preloader?', 'wpcrest'),
			'description' => __("An animated preloader is displayed while your page loads. If you don't want the preloader to be displayed while the page loads then disable it from here.", 'wpcrest'),
			'options' => array(
				'0' => __('No', 'wpcrest'),
				'1' => __('Yes', 'wpcrest'),
			),
			'default_value' => '0'
		));

		ff_add_field_to_field_group('wpc-configuration-general', 'wpc-configuration-general-disable-page-preloader');

		ff_create_field('wpc-configuration-general-disable-ajax-preloader', 'radio', array(
			'name' => 'disable-ajax-preloader',
			'label' => __('Disable AJAX Preloader?', 'wpcrest'),
			'description' => __("An animated preloader is displayed while content is fetched using AJAX. If you don't want the preloader to be displayed during AJAX calls then disable it from here.", 'wpcrest'),
			'options' => array(
				'0' => __('No', 'wpcrest'),
				'1' => __('Yes', 'wpcrest'),
			),
			'default_value' => '0'
		));

		ff_add_field_to_field_group('wpc-configuration-general', 'wpc-configuration-general-disable-ajax-preloader');

		ff_create_field('wpc-configuration-general-disable-ajax-pagination', 'radio', array(
			'name' => 'disable-ajax-pagination',
			'label' => __('Disable AJAX Pagination', 'wpcrest'),
			'description' => __("By default AJAX is used for paging which means the page doesn't refresh to fetch content from the next/previous page", 'wpcrest'),
			'options' => array(
				'0' => __('No', 'wpcrest'),
				'1' => __('Yes', 'wpcrest'),
			),
			'default_value' => '0'
		));

		ff_add_field_to_field_group('wpc-configuration-general', 'wpc-configuration-general-disable-ajax-pagination');

		ff_create_field('wpc-configuration-general-default-skin', 'select', array(
			'name' => 'default-skin',
			'label' => __('Default Skin', 'wpcrest'),
			'description' => __("Select the skin you want a new visitor to see by default. This can be changed if the scheme switcher is on. Selecting 'None' will display the theme using a monochromatic black/white/grey skin.", 'wpcrest'),
			'options' => self::$skins,
			'default_value' => 'classic'
		));

		ff_add_field_to_field_group('wpc-configuration-general', 'wpc-configuration-general-default-skin');

		foreach(self::$background_underlays as $key => $background_underlay) {
			$background_underlays[$key] = $background_underlay['options']['name'];
		}

		ff_create_field('wpc-configuration-general-default-background-underlay', 'select', array(
			'name' => 'default-background-underlay',
			'label' => __('Default Background Underlay', 'wpcrest'),
			'description' => __("Select the background underlay you want a new visitor to see by default. This can be changed if the scheme switcher is on. Selecting 'None' will display the theme using a monochromatic black/white/grey background underlay.", 'wpcrest'),
			'options' => $background_underlays,
			'default_value' => 'yummy-gradient',
		));

		ff_add_field_to_field_group('wpc-configuration-general', 'wpc-configuration-general-default-background-underlay');

		ff_create_field('wpc-configuration-general-custom-background-underlay', 'group', array(
			'name' => 'custom-background-underlay',
			'label' => __('Custom Background Underlay', 'wpcrest'),
			'description' => __('If you want to use a custom background underlay then you can define it here', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-general', 'wpc-configuration-general-custom-background-underlay');

		ff_create_field('wpc-configuration-general-custom-background-underlay-enable', 'radio', array(
			'name' => 'enable',
			'label' => __('Enable Custom Background Underlay', 'wpcrest'),
			'description' => __('Enable this if you want the custom background underlay to appear. Also make sure to define it below if you enable this option', 'wpcrest'),
			'options' => array(
				'0' => __('No', 'wpcrest'),
				'1' => __('Yes', 'wpcrest'),
			),
			'default_value' => '0'
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-underlay', 'wpc-configuration-general-custom-background-underlay-enable');

		ff_create_field('wpc-configuration-general-custom-background-underlay-color', 'colorpicker', array(
			'name' => 'color',
			'label' => __('Color', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-underlay', 'wpc-configuration-general-custom-background-underlay-color');

		ff_create_field('wpc-configuration-general-custom-background-underlay-position', 'group', array(
			'name' => 'position',
			'label' => __('Position', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-underlay', 'wpc-configuration-general-custom-background-underlay-position');

		foreach(array('left top', 'center top', 'right top', 'left center', 'center center', 'right center', 'left bottom', 'center bottom', 'right bottom') as $position) {
			$positions[$position] = $position;
		}

		ff_create_field('wpc-configuration-general-custom-background-underlay-position-constant', 'select', array(
			'name' => 'constant',
			'label' => __('Select Constant Value', 'wpcrest'),
			'options' => $positions,
			'default_value' => 'left top',
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-underlay-position', 'wpc-configuration-general-custom-background-underlay-position-constant');

		ff_create_field('wpc-configuration-general-custom-background-underlay-position-variable', 'group', array(
			'name' => 'variable',
			'label' => __('Set Variable Value', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-underlay-position', 'wpc-configuration-general-custom-background-underlay-position-variable');

		ff_create_field('wpc-configuration-general-custom-background-underlay-position-variable-x', 'text', array(
			'name' => 'x',
			'label' => __('X', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-underlay-position-variable', 'wpc-configuration-general-custom-background-underlay-position-variable-x');

		ff_create_field('wpc-configuration-general-custom-background-underlay-position-variable-y', 'text', array(
			'name' => 'y',
			'label' => __('Y', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-underlay-position-variable', 'wpc-configuration-general-custom-background-underlay-position-variable-y');

		ff_create_field('wpc-configuration-general-custom-background-underlay-size', 'group', array(
			'name' => 'size',
			'label' => __('Size', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-underlay', 'wpc-configuration-general-custom-background-underlay-size');

		foreach(array('auto', 'cover', 'contain') as $size) {
			$sizes[$size] = $size;
		}

		ff_create_field('wpc-configuration-general-custom-background-underlay-size-constant', 'select', array(
			'name' => 'constant',
			'label' => __('Select Constant Value', 'wpcrest'),
			'options' => $sizes,
			'default_value' => 'auto',
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-underlay-size', 'wpc-configuration-general-custom-background-underlay-size-constant');

		ff_create_field('wpc-configuration-general-custom-background-underlay-size-variable', 'group', array(
			'name' => 'variable',
			'label' => __('Set Variable Value', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-underlay-size', 'wpc-configuration-general-custom-background-underlay-size-variable');

		ff_create_field('wpc-configuration-general-custom-background-underlay-size-variable-x', 'text', array(
			'name' => 'x',
			'label' => __('X', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-underlay-size-variable', 'wpc-configuration-general-custom-background-underlay-size-variable-x');

		ff_create_field('wpc-configuration-general-custom-background-underlay-size-variable-y', 'text', array(
			'name' => 'y',
			'label' => __('Y', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-underlay-size-variable', 'wpc-configuration-general-custom-background-underlay-size-variable-y');

		foreach(array('repeat-x', 'repeat-y', 'no-repeat') as $repeat) {
			$repeats[$repeat] = $repeat;
		}

		ff_create_field('wpc-configuration-general-custom-background-underlay-repeat', 'select', array(
			'name' => 'repeat',
			'label' => __('Repeat', 'wpcrest'),
			'options' => $repeats,
			'prepend_blank' => true,
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-underlay', 'wpc-configuration-general-custom-background-underlay-repeat');

		foreach(array('fixed') as $attachment) {
			$attachments[$attachment] = $attachment;
		}

		ff_create_field('wpc-configuration-general-custom-background-underlay-attachment', 'select', array(
			'name' => 'attachment',
			'label' => __('Attachment', 'wpcrest'),
			'options' => $attachments,
			'prepend_blank' => true,
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-underlay', 'wpc-configuration-general-custom-background-underlay-attachment');

		ff_create_field('wpc-configuration-general-custom-background-underlay-image', 'media', array(
			'name' => 'image',
			'label' => __('Image', 'wpcrest'),
			'description' => __('Use a custom underlay if you want to', 'wpcrest'),
			'library' => 'image',
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-underlay', 'wpc-configuration-general-custom-background-underlay-image');



		foreach(self::$background_overlays as $key => $background_overlay) {
			$background_overlays[$key] = $background_overlay['options']['name'];
		}

		ff_create_field('wpc-configuration-general-default-background-overlay', 'select', array(
			'name' => 'default-background-overlay',
			'label' => __('Default Background Overlay', 'wpcrest'),
			'description' => __("Select the background overlay you want a new visitor to see by default. This can be changed if the scheme switcher is on. Selecting 'None' will display the theme using a monochromatic black/white/grey background overlay.", 'wpcrest'),
			'options' => $background_overlays,
			'default_value' => 'shower-lines',
		));

		ff_add_field_to_field_group('wpc-configuration-general', 'wpc-configuration-general-default-background-overlay');

		ff_create_field('wpc-configuration-general-custom-background-overlay', 'group', array(
			'name' => 'custom-background-overlay',
			'label' => __('Custom Background Overlay', 'wpcrest'),
			'description' => __('If you want to use a custom background overlay then you can define it here', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-general', 'wpc-configuration-general-custom-background-overlay');

		ff_create_field('wpc-configuration-general-custom-background-overlay-enable', 'radio', array(
			'name' => 'enable',
			'label' => __('Enable Custom Background Overlay', 'wpcrest'),
			'description' => __('Enable this if you want the custom background overlay to appear. Also make sure to define it below if you enable this option', 'wpcrest'),
			'options' => array(
				'0' => __('No', 'wpcrest'),
				'1' => __('Yes', 'wpcrest'),
			),
			'default_value' => '0'
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-overlay', 'wpc-configuration-general-custom-background-overlay-enable');

		ff_create_field('wpc-configuration-general-custom-background-overlay-color', 'colorpicker', array(
			'name' => 'color',
			'label' => __('Color', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-overlay', 'wpc-configuration-general-custom-background-overlay-color');

		ff_create_field('wpc-configuration-general-custom-background-overlay-position', 'group', array(
			'name' => 'position',
			'label' => __('Position', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-overlay', 'wpc-configuration-general-custom-background-overlay-position');


		foreach(array('left top', 'center top', 'right top', 'left center', 'center center', 'right center', 'left bottom', 'center bottom', 'right bottom') as $position) {
			$positions[$position] = $position;
		}

		ff_create_field('wpc-configuration-general-custom-background-overlay-position-constant', 'select', array(
			'name' => 'constant',
			'label' => __('Select Constant Value', 'wpcrest'),
			'options' => $positions,
			'default_value' => 'left top',
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-overlay-position', 'wpc-configuration-general-custom-background-overlay-position-constant');

		ff_create_field('wpc-configuration-general-custom-background-overlay-position-variable', 'group', array(
			'name' => 'variable',
			'label' => __('Set Variable Value', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-overlay-position', 'wpc-configuration-general-custom-background-overlay-position-variable');

		ff_create_field('wpc-configuration-general-custom-background-overlay-position-variable-x', 'text', array(
			'name' => 'x',
			'label' => __('X', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-overlay-position-variable', 'wpc-configuration-general-custom-background-overlay-position-variable-x');

		ff_create_field('wpc-configuration-general-custom-background-overlay-position-variable-y', 'text', array(
			'name' => 'y',
			'label' => __('Y', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-overlay-position-variable', 'wpc-configuration-general-custom-background-overlay-position-variable-y');

		ff_create_field('wpc-configuration-general-custom-background-overlay-size', 'group', array(
			'name' => 'size',
			'label' => __('Size', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-overlay', 'wpc-configuration-general-custom-background-overlay-size');

		foreach(array('cover', 'contain') as $size) {
			$sizes[$size] = $size;
		}

		ff_create_field('wpc-configuration-general-custom-background-overlay-size-constant', 'select', array(
			'name' => 'constant',
			'label' => __('Select Constant Value', 'wpcrest'),
			'options' => $sizes,
			'default_value' => 'auto',
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-overlay-size', 'wpc-configuration-general-custom-background-overlay-size-constant');

		ff_create_field('wpc-configuration-general-custom-background-overlay-size-variable', 'group', array(
			'name' => 'variable',
			'label' => __('Set Variable Value', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-overlay-size', 'wpc-configuration-general-custom-background-overlay-size-variable');

		ff_create_field('wpc-configuration-general-custom-background-overlay-size-variable-x', 'text', array(
			'name' => 'x',
			'label' => __('X', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-overlay-size-variable', 'wpc-configuration-general-custom-background-overlay-size-variable-x');

		ff_create_field('wpc-configuration-general-custom-background-overlay-size-variable-y', 'text', array(
			'name' => 'y',
			'label' => __('Y', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-overlay-size-variable', 'wpc-configuration-general-custom-background-overlay-size-variable-y');

		foreach(array('repeat-x', 'repeat-y', 'no-repeat') as $repeat) {
			$repeats[$repeat] = $repeat;
		}

		ff_create_field('wpc-configuration-general-custom-background-overlay-repeat', 'select', array(
			'name' => 'repeat',
			'label' => __('Repeat', 'wpcrest'),
			'options' => $repeats,
			'prepend_blank' => true,
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-overlay', 'wpc-configuration-general-custom-background-overlay-repeat');

		foreach(array('fixed') as $attachment) {
			$attachments[$attachment] = $attachment;
		}

		ff_create_field('wpc-configuration-general-custom-background-overlay-attachment', 'select', array(
			'name' => 'attachment',
			'label' => __('Attachment', 'wpcrest'),
			'options' => $attachments,
			'prepend_blank' => true,
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-overlay', 'wpc-configuration-general-custom-background-overlay-attachment');

		ff_create_field('wpc-configuration-general-custom-background-overlay-image', 'media', array(
			'name' => 'image',
			'label' => __('Image', 'wpcrest'),
			'description' => __('Use a custom overlay if you want to', 'wpcrest'),
			'library' => 'image',
		));

		ff_add_field_to_field_group('wpc-configuration-general-custom-background-overlay', 'wpc-configuration-general-custom-background-overlay-image');

		ff_create_field('wpc-configuration-general-disable-scheme-switcher', 'radio', array(
			'name' => 'disable-scheme-switcher',
			'label' => __('Disable Scheme Switcher?', 'wpcrest'),
			'options' => array(
				'0' => __('No', 'wpcrest'),
				'1' => __('Yes', 'wpcrest'),
			),
			'default_value' => '0'
		));

		ff_add_field_to_field_group('wpc-configuration-general', 'wpc-configuration-general-disable-scheme-switcher');

		ff_create_field('wpc-configuration-general-external-urls-new-window', 'radio', array(
			'name' => 'external-urls-new-window',
			'label' => __('Open External URLs in New Window', 'wpcrest'),
			'description' => __("Open's all external URLs in a new window. Every URL which doesn't begin with %s is considered external.", 'wpcrest'),
			'options' => array(
				'0' => __('No', 'wpcrest'),
				'1' => __('Yes', 'wpcrest'),
			),
			'default_value' => '0'
		));

		ff_add_field_to_field_group('wpc-configuration-general', 'wpc-configuration-general-external-urls-new-window');

		ff_create_field('wpc-configuration-general-media-urls-new-window', 'radio', array(
			'name' => 'media-urls-new-window',
			'label' => __('Open Media URLs in New Window', 'wpcrest'),
			'description' => __("Open's all media URLs in a new window. Media URLs are those which end with extensions: jpg, jpeg, png, gif and pdf. This includes both internal and external URLs", 'wpcrest'),
			'options' => array(
				'0' => __('No', 'wpcrest'),
				'1' => __('Yes', 'wpcrest'),
			),
			'default_value' => '0'
		));

		ff_add_field_to_field_group('wpc-configuration-general', 'wpc-configuration-general-media-urls-new-window');

		ff_create_field('wpc-configuration-general-disable-qtip', 'radio', array(
			'name' => 'disable-qtip',
			'label' => __('Disable qTip', 'wpcrest'),
			'description' => __('Disable display of titles using qTip', 'wpcrest'),
			'options' => array(
				'0' => __('No', 'wpcrest'),
				'1' => __('Yes', 'wpcrest'),
			),
			'default_value' => '0'
		));

		ff_add_field_to_field_group('wpc-configuration-general', 'wpc-configuration-general-disable-qtip');

		ff_create_field('wpc-configuration-general-disable-colorbox-images', 'radio', array(
			'name' => 'disable-colorbox-images',
			'label' => __('Disable Colorbox for Images', 'wpcrest'),
			'description' => __('Disable loading of images using Colorbox', 'wpcrest'),
			'options' => array(
				'0' => __('No', 'wpcrest'),
				'1' => __('Yes', 'wpcrest'),
			),
			'default_value' => '0'
		));

		ff_add_field_to_field_group('wpc-configuration-general', 'wpc-configuration-general-disable-colorbox-images');

		ff_create_field('wpc-configuration-general-disable-colorbox-posts', 'radio', array(
			'name' => 'disable-colorbox-posts',
			'label' => __('Disable Colorbox for Posts', 'wpcrest'),
			'description' => __('Disable loading of posts using Colorbox', 'wpcrest'),
			'options' => array(
				'0' => __('No', 'wpcrest'),
				'1' => __('Yes', 'wpcrest'),
			),
			'default_value' => '0'
		));

		ff_add_field_to_field_group('wpc-configuration-general', 'wpc-configuration-general-disable-colorbox-posts');


		ff_create_field('wpc-configuration-messages', 'group', array(
			'name' => 'messages',
			'label' => __('Messages', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration', 'wpc-configuration-messages');

		ff_create_field('wpc-configuration-messages-copyright', 'editor', array(
			'name' => 'copyright',
			'label' => __('Copyright', 'wpcrest'),
			'description' => __('You can insert a custom copyright notice here. Use %1$s as a placeholder for a linked title of site or %2$s for a non-linked title.', 'wpcrest'),
			'default_value' => __('Copyright &copy; 2013 %1$s. All Rights Reserved.', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-messages', 'wpc-configuration-messages-copyright');

		ff_create_field('wpc-configuration-messages-404', 'editor', array(
			'name' => '404',
			'label' => __('404', 'wpcrest'),
			'description' => __('You can insert a custom 404 message here.', 'wpcrest'),
			'default_value' => __('The page requested could not be found!', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-messages', 'wpc-configuration-messages-404');



		ff_create_field('wpc-configuration-headings', 'group', array(
			'name' => 'headings',
			'label' => __('Headings', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration', 'wpc-configuration-headings');



		ff_create_field('wpc-configuration-taxonomies', 'group', array(
			'name' => 'taxonomies',
			'label' => __('Taxonomies', 'wpcrest'),
			'description' => __('Use %s as replacement substitution for the respective taxonomy term', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-headings', 'wpc-configuration-taxonomies');

		ff_create_field('wpc-configuration-taxonomies-category', 'text', array(
			'name' => 'category',
			'label' => __('Category', 'wpcrest'),
			'default_value' => __('Category Archives: %s', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-taxonomies', 'wpc-configuration-taxonomies-category');

		ff_create_field('wpc-configuration-taxonomies-tag', 'text', array(
			'name' => 'tag',
			'label' => __('Tag', 'wpcrest'),
			'default_value' => __('Tag Archives: %s', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-taxonomies', 'wpc-configuration-taxonomies-tag');



		ff_create_field('wpc-configuration-date-based', 'group', array(
			'name' => 'date-based',
			'label' => __('Date-based', 'wpcrest'),
			'description' => __('Use %s as replacement substitute for year, month or day respectively', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-headings', 'wpc-configuration-date-based');

		ff_create_field('wpc-configuration-date-based-yearly', 'text', array(
			'name' => 'yearly',
			'label' => __('Yearly', 'wpcrest'),
			'default_value' => __('Yearly Archives: %s', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-date-based', 'wpc-configuration-date-based-yearly');

		ff_create_field('wpc-configuration-date-based-monthly', 'text', array(
			'name' => 'monthly',
			'label' => __('Monthly', 'wpcrest'),
			'default_value' => __('Monthly Archives: %s', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-date-based', 'wpc-configuration-date-based-monthly');

		ff_create_field('wpc-configuration-date-based-daily', 'text', array(
			'name' => 'daily',
			'label' => __('Daily', 'wpcrest'),
			'default_value' => __('Daily Archives: %s', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-date-based', 'wpc-configuration-date-based-daily');



		ff_create_field('wpc-configuration-search-results', 'group', array(
			'name' => 'search-results',
			'label' => __('Search Results', 'wpcrest'),
			'description' => __('Use %s as replacement substitution for query being searched', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-headings', 'wpc-configuration-search-results');

		ff_create_field('wpc-configuration-search-results-main', 'text', array(
			'name' => 'main',
			'label' => __('Main', 'wpcrest'),
			'default_value' => __('Search Results for "%s"', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-search-results', 'wpc-configuration-search-results-main');

		ff_create_field('wpc-configuration-search-results-post', 'text', array(
			'name' => 'post',
			'label' => __('Post', 'wpcrest'),
			'default_value' => __('Posts matching query "%s"', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-search-results', 'wpc-configuration-search-results-post');

		ff_create_field('wpc-configuration-search-results-page', 'text', array(
			'name' => 'page',
			'label' => __('Page', 'wpcrest'),
			'default_value' => __('Pages matching query "%s"', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-search-results', 'wpc-configuration-search-results-page');



		ff_create_field('wpc-configuration-others', 'group', array(
			'name' => 'others',
			'label' => __('Others', 'wpcrest'),
			'description' => __('Use %s as replacement substitution for query being searched', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-headings', 'wpc-configuration-others');

		ff_create_field('wpc-configuration-others-blog', 'text', array(
			'name' => 'blog',
			'label' => __('Blog', 'wpcrest'),
			'default_value' => __('Blog', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-others', 'wpc-configuration-others-blog');

		ff_create_field('wpc-configuration-others-author', 'text', array(
			'name' => 'author',
			'label' => __('Author', 'wpcrest'),
			'default_value' => __('Author Archives: %s', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-others', 'wpc-configuration-others-author');

		ff_create_field('wpc-configuration-others-404', 'text', array(
			'name' => '404',
			'label' => __('404', 'wpcrest'),
			'default_value' => __('404', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-others', 'wpc-configuration-others-404');



		ff_create_field('wpc-configuration-labels', 'group', array(
			'name' => 'labels',
			'label' => __('Labels', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration', 'wpc-configuration-labels');

		ff_create_field('wpc-configuration-labels-attachment-browser', 'text', array(
			'name' => 'attachment-browser',
			'label' => __('Attachment Browser', 'wpcrest'),
			'description' => __("Use %s as replacement substitute for attachment's Post title", 'wpcrest'),
			'default_value' => __('Browser for attachments of %s', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-labels', 'wpc-configuration-labels-attachment-browser');

		ff_create_field('wpc-configuration-labels-current-attachment', 'text', array(
			'name' => 'current-attachment',
			'label' => __('Current Attachment', 'wpcrest'),
			'description' => __('Use %s as replacement substitute for attachment number', 'wpcrest'),
			'default_value' => __('Image %s', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-labels', 'wpc-configuration-labels-current-attachment');

		ff_create_field('wpc-configuration-labels-font-size', 'text', array(
			'name' => 'font-size',
			'label' => __('Font Size', 'wpcrest'),
			'default_value' => __('Font Size', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-labels', 'wpc-configuration-labels-font-size');

		ff_create_field('wpc-configuration-labels-skin', 'text', array(
			'name' => 'skin',
			'label' => __('Skin Selection', 'wpcrest'),
			'default_value' => __('Select Skin', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-labels', 'wpc-configuration-labels-skin');

		ff_create_field('wpc-configuration-labels-underlay-background', 'text', array(
			'name' => 'underlay-background',
			'label' => __('Background Underlay Selection', 'wpcrest'),
			'default_value' => __('Select Underlay Background', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-labels', 'wpc-configuration-labels-underlay-background');

		ff_create_field('wpc-configuration-labels-overlay-background', 'text', array(
			'name' => 'overlay-background',
			'label' => __('Background Overlay Selection', 'wpcrest'),
			'default_value' => __('Select Overlay Background', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-labels', 'wpc-configuration-labels-overlay-background');

		ff_create_field('wpc-configuration-labels-no-search-results', 'text', array(
			'name' => 'no-search-results',
			'label' => __('No search results', 'wpcrest'),
			'default_value' => __('No results found for searched query "%s"', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-configuration-labels', 'wpc-configuration-labels-no-search-results');



















		ff_create_section('wpc-post-format-gallery', 'post', array(
			'title' => __('Gallery', 'wpcrest'),
			'post_types' => array('post'),
			'post_formats' => array('gallery'),
		));


		ff_create_field('wpc-post-format-gallery-images', 'group', array(
			'minimal' => true,
			'label' => __('Images', 'wpcrest'),
			'repeatable' => true,
		));

		ff_add_field_to_section('wpc-post-format-gallery', 'wpc-post-format-gallery-images');


		ff_create_field('wpc-post-format-gallery-images-full-size-image', 'media', array(
			'name' => 'full-size-image',
			'label' => __('Full Size Image', 'wpcrest'),
			'library' => 'image',
			'description' => __('This is required', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-post-format-gallery-images', 'wpc-post-format-gallery-images-full-size-image');

		ff_create_field('wpc-post-format-gallery-images-small-size-image', 'media', array(
			'name' => 'small-size-image',
			'label' => __('Small Size Image', 'wpcrest'),
			'library' => 'image',
		));


		ff_add_field_to_field_group('wpc-post-format-gallery-images', 'wpc-post-format-gallery-images-small-size-image');








		ff_create_section('wpc-post-format-image', 'post', array(
			'title' => __('Image', 'wpcrest'),
			'post_types' => array('post'),
			'post_formats' => array('image'),
		));


		ff_create_field('wpc-post-format-image-full-size-image', 'media', array(
			'name' => 'full-size-image',
			'label' => __('Full Size Image', 'wpcrest'),
			'library' => 'image',
			'description' => __('This is required', 'wpcrest'),
		));

		ff_add_field_to_section('wpc-post-format-image', 'wpc-post-format-image-full-size-image');

		ff_create_field('wpc-post-format-image-small-size-image', 'media', array(
			'name' => 'small-size-image',
			'label' => __('Small Size Image', 'wpcrest'),
			'library' => 'image',
		));


		ff_add_field_to_section('wpc-post-format-image', 'wpc-post-format-image-small-size-image');









		ff_create_section('wpc-post-format-video', 'post', array(
			'title' => __('Video', 'wpcrest'),
			'post_types' => array('post'),
			'post_formats' => array('video'),
		));


		ff_create_field('wpc-video', 'media', array(
			'label' => __('Video', 'wpcrest'),
			'description' => __('This is required', 'wpcrest'),
		));

		ff_add_field_to_section('wpc-post-format-video', 'wpc-video');








		ff_create_section('wpc-post-format-audio', 'post', array(
			'title' => __('Audio', 'wpcrest'),
			'post_types' => array('post'),
			'post_formats' => array('audio'),
		));




		ff_create_field('wpc-audio', 'media', array(
			'label' => __('Audio', 'wpcrest'),
			'description' => __('This is required', 'wpcrest'),
		));

		ff_add_field_to_section('wpc-post-format-audio', 'wpc-audio');




		ff_create_section('wpc-showcase-page', 'post', array(
			'title' => __('Showcase Page', 'wpcrest'),
			'post_types' => array('page'),
			'page_templates' => array('template-showcase-page.php'),
		));

		ff_create_field('wpc-showcase-page', 'group', array(
			'minimal' => true,
		));

		ff_add_field_to_section('wpc-showcase-page', 'wpc-showcase-page');

		ff_create_field('wpc-showcase-page-content-location', 'radio', array(
			'name' => 'content-location',
			'label' => __('Content Location', 'wpcrest'),
			'options' => array(
				'0' => __('Disable', 'wpcrest'),
				'1' => __('Top', 'wpcrest'),
				'2' => __('Bottom', 'wpcrest'),
			),
			'default_value' => '1',
		));

		ff_add_field_to_field_group('wpc-showcase-page', 'wpc-showcase-page-content-location');




		ff_create_field('wpc-showcase-page-teaser', 'group', array(
			'name' => 'teaser',
			'label' => __('Teaser', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-showcase-page', 'wpc-showcase-page-teaser');

		ff_create_field('wpc-showcase-page-teaser-disable', 'radio', array(
			'name' => 'disable',
			'label' => __('Disable Teaser', 'wpcrest'),
			'options' => array(
				'0' => __('No', 'wpcrest'),
				'1' => __('Yes', 'wpcrest'),
			),
			'default_value' => '0',
		));

		ff_add_field_to_field_group('wpc-showcase-page-teaser', 'wpc-showcase-page-teaser-disable');




		ff_create_field('wpc-showcase-page-teaser-title', 'text', array(
			'name' => 'title',
			'label' => __('Title', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-showcase-page-teaser', 'wpc-showcase-page-teaser-title');


		ff_create_field('wpc-showcase-page-teaser-description', 'textarea', array(
			'name' => 'description',
			'label' => __('Description', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-showcase-page-teaser', 'wpc-showcase-page-teaser-description');

		ff_create_field('wpc-showcase-page-teaser-button-label', 'text', array(
			'name' => 'button-label',
			'label' => __('Button Label', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-showcase-page-teaser', 'wpc-showcase-page-teaser-button-label');



		ff_create_field('wpc-showcase-page-teaser-button-url', 'text', array(
			'name' => 'button-url',
			'label' => __('Button URL', 'wpcrest'),
		));

		ff_add_field_to_field_group('wpc-showcase-page-teaser', 'wpc-showcase-page-teaser-button-url');
	}

	static function widgets_init() {
		global $wp_widget_factory;

		remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));

		unregister_widget('WP_Widget_Search');
	}

	static function load_theme_textdomain() {
		load_theme_textdomain('wpcrest', get_template_directory() . '/languages');

		if(file_exists(self::get_file_path('/custom/languages', 'parent'))) {
			load_theme_textdomain('wpcrest', self::get_file_path('/custom/languages', 'parent'));
		}
	}

	static function after_setup_theme() {
		if(!empty($_REQUEST['wpc-action']) && $_REQUEST['wpc-action'] == 'ajax-request') {
			self::$is_ajax = true;
		}
	}

	static function wp_enqueue_scripts() {
		wp_enqueue_style('wpc-reset', self::get_file_uri('/css/reset.css'));

		wp_enqueue_style('wpc-frontend', self::get_file_uri('/css/frontend.css'));

		wp_enqueue_style('wpc-yanone-kaffeesatz', 'http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,700');

		wp_enqueue_style('wpc-open-sans', 'http://fonts.googleapis.com/css?family=Open+Sans:400,700');

		wp_enqueue_style('wpc-jquery-qtip', self::get_file_uri('/css/jquery.qtip.min.css'));

		if(file_exists(self::get_file_uri('/custom/css/frontend.css', 'parent'))) {
			wp_enqueue_style('wpc-frontend-custom', self::get_file_uri('/custom/css/frontend.css', 'parent'));
		}

		if(!empty(self::$settings['dynamic']['loaded-skin']) && self::$settings['dynamic']['loaded-skin'] != 'none') {
			wp_enqueue_style('wpc-skin', self::get_file_uri('/schemes/skins/' . self::$settings['dynamic']['loaded-skin'] . '/screen.css'));
		}

		wp_enqueue_style('wpc-jquery-colorbox', self::get_file_uri('/css/colorbox.css'));

		wp_enqueue_script('comment-reply');
		
		wp_enqueue_script('jquery');

		wp_enqueue_script('wpc-jquery-masonry', self::get_file_uri('/js/jquery.masonry.min.js'));

		wp_enqueue_script('wpc-imagesloaded', self::get_file_uri('/js/imagesloaded.min.js'));

		wp_enqueue_script('wpc-jquery-colorbox', self::get_file_uri('/js/jquery.colorbox.min.js'));

		wp_enqueue_script('wpc-jquery-placeholder', self::get_file_uri('/js/jquery.placeholder.min.js'));

		wp_enqueue_script('wpc-jquery-qtip', self::get_file_uri('/js/jquery.qtip.min.js'));

		if(empty(self::$settings['configuration']['general']['disable-scheme-switcher'])) {
			wp_enqueue_script('wpc-jquery-cookie', self::get_file_uri('/js/jquery.cookie.js'));
		}

		wp_enqueue_script('wpc-frontend', self::get_file_uri('/js/frontend.js'));

		if(file_exists(self::get_file_uri('/custom/js/frontend.js', 'parent'))) {
			wp_enqueue_script('wpc-frontend-custom', self::get_file_uri('/custom/js/frontend.js', 'parent'));
		}

		wp_localize_script('wpc-frontend', 'wpcrest_frontend',
			array(
				'template_url' => self::get_file_uri(null, 'parent'),

				'disable_page_preloader' => !empty(self::$settings['configuration']['general']['disable-page-preloader']) ? self::$settings['configuration']['general']['disable-page-preloader'] : null,

				'disable_ajax_preloader' => !empty(self::$settings['configuration']['general']['disable-ajax-preloader']) ? self::$settings['configuration']['general']['disable-ajax-preloader'] : null,

				'disable_ajax_pagination' => !empty(self::$settings['configuration']['general']['disable-ajax-pagination']) ? self::$settings['configuration']['general']['disable-ajax-pagination'] : null,

				'disable_scheme_switcher' => !empty(self::$settings['configuration']['general']['disable-scheme-switcher']) ? self::$settings['configuration']['general']['disable-scheme-switcher'] : null,

				'external_urls_new_window' => !empty(self::$settings['configuration']['general']['external-urls-new-window']) ? self::$settings['configuration']['general']['external-urls-new-window'] : null,

				'media_urls_new_window' => !empty(self::$settings['configuration']['general']['media-urls-new-window']) ? self::$settings['configuration']['general']['media-urls-new-window'] : null,

				'disable_qtip' => !empty(self::$settings['configuration']['general']['disable-qtip']) ? self::$settings['configuration']['general']['disable-qtip'] : null,

				'disable_colorbox_images' => !empty(self::$settings['configuration']['general']['disable-colorbox-images']) ? self::$settings['configuration']['general']['disable-colorbox-images'] : null,

				'disable_colorbox_posts' => !empty(self::$settings['configuration']['general']['disable-colorbox-posts']) ? self::$settings['configuration']['general']['disable-colorbox-posts'] : null,
			)
		);
	}

	static function wp_head() {
		?>
		<meta name="viewport" content="width=device-width; initial-scale=1.0" />

		<meta http-equiv="X-UA-Compatible" content="IE=edge" />

		<?php
			if(!empty($_COOKIE['wpc-font-size-relative'])) {
				?>
				<style type="text/css">
				html {
					font-size: <?php echo (0.625 + $_COOKIE['wpc-font-size-relative']) . 'em'; ?>;
				}
				</style>
				<?php
			}

			if(empty(self::$settings['dynamic']['loaded-skin']) || self::$settings['dynamic']['loaded-skin'] == 'none') {
				?><link rel="stylesheet" id='wpc-skin-css' type="text/css" media="all" /><?php
			}

			if(!empty(self::$settings['dynamic']['loaded-background-underlay'])) {
				if(!empty(self::$background_underlays[self::$settings['dynamic']['loaded-background-underlay']]['properties'])) {
					?>
					<style type="text/css" id="wpc-background-underlay">
					html {
						<?php
							foreach(self::$background_underlays[self::$settings['dynamic']['loaded-background-underlay']]['properties'] as $key => $value) {
								if(!empty($value)) {
									if($key == 'background-image') {
										if(self::$settings['dynamic']['loaded-background-underlay'] == 'custom') {
											?>background-image: url(<?php echo $value; ?>);<?php
										}
										else {
											?>background-image: url(<?php echo self::get_file_uri("/schemes/background-underlays/{$value}"); ?>);<?php
										}
									}
									elseif($key == 'background-position' || $key == 'background-size') {
										if(!empty($value['variable']['x']) && !empty($value['variable']['y'])) {
											$modified_value = "{$value['variable']['x']} {$value['variable']['y']}";
										}
										elseif(!empty($value['constant'])) {
											$modified_value = $value['constant'];
										}

										if(!empty($modified_value)) {
											?><?php echo $key; ?>: <?php echo $modified_value; ?>;<?php
										}
									}
									else {
										?><?php echo $key; ?>: <?php echo $value; ?>;<?php
									}
								}
							}
						?>
					}
					</style>
					<?php
				}
			}

			if(!empty(self::$settings['dynamic']['loaded-background-overlay'])) {
				if(!empty(self::$background_overlays[self::$settings['dynamic']['loaded-background-overlay']]['properties'])) {
					?>
					<style type="text/css" id="wpc-background-overlay">
					body {
						<?php
							foreach(self::$background_overlays[self::$settings['dynamic']['loaded-background-overlay']]['properties'] as $key => $value) {
								if(!empty($value)) {
									if($key == 'background-image') {
										if(self::$settings['dynamic']['loaded-background-overlay'] == 'custom') {
											?>background-image: url(<?php echo $value; ?>);<?php
										}
										else {
											?>background-image: url(<?php echo self::get_file_uri("/schemes/background-overlays/{$value}"); ?>);<?php
										}
									}
									elseif($key == 'background-position' || $key == 'background-size') {
										if(!empty($value['variable']['x']) && !empty($value['variable']['y'])) {
											$modified_value = "{$value['variable']['x']} {$value['variable']['y']}";
										}
										elseif(!empty($value['constant'])) {
											$modified_value = $value['constant'];
										}

										if(!empty($modified_value)) {
											?><?php echo $key; ?>: <?php echo $modified_value; ?>;<?php
										}
									}
									else {
										?><?php echo $key; ?>: <?php echo $value; ?>;<?php
									}
								}
							}
						?>
					}
					</style>
					<?php
				}
			}
		?>
		<noscript>
			<link rel="stylesheet" href="<?php echo self::get_file_uri('/css/noscript.frontend.css'); ?>" type="text/css" media="all" />
		</noscript>
		<?php
	}

	static function widget_tag_cloud_args($arguments) {
		$arguments['smallest'] = '1';

		$arguments['largest'] = '1.5';

		$arguments['unit'] = 'em';

		return $arguments;
	}

	static function wp_title($title, $separator) {
		$title .= get_bloginfo('name');

		return $title;
	}

	static function wp_link_pages($output) {
		return '<div class="wpc-ajax-request">' . $output . '</div>';
	}

	static function insert_teaser($arguments) {
		?>
		<div class="wpc-teaser">
			<div class="wpc-details wpc-group">
				<div class="wpc-title"><?php echo $arguments['title']; ?></div>
				<a href="<?php echo !empty($arguments['button-url']) ? $arguments['button-url'] : '#'; ?>" class="wpc-link"><?php echo $arguments['button-label']; ?></a>
				<div class="wpc-description">
					<?php echo wpautop($arguments['description']); ?>
				</div>
			</div>
		</div>
		<?php
	}

	static function get_attachment_id_by_url($link) {
		$file = str_replace(self::$upload_directory_url, '', $link);

		$arguments = array(
			'post_type' => 'attachment',
			'post_status' => 'any',
			'posts_per_page' => 1,
			'meta_query' =>	array(
				array(
					'key' => '_wp_attached_file',
					'value' => $file,
				)
			)
		);

		$attachments = get_posts($arguments);

		if(!empty($attachments)) {
			$attachment = array_shift($attachments);

			if(!empty($attachment->ID)) {
				return $attachment->ID;
			}
		}
	}

	static function merge_arrays($custom_values, $default_values) {
		foreach($default_values as $df_key => $df_value) {
			if(is_array($df_value)) {
				if(empty($custom_values[$df_key])) {
					$custom_values[$df_key] = $df_value;
				}
				else {
					$custom_values[$df_key] = self::merge_arrays($custom_values[$df_key], $df_value);
				}
			}
			else {
				if(empty($custom_values[$df_key])) {
					$custom_values[$df_key] = $df_value;
				}
			}
		}

		return $custom_values;
	}

	static function start_el($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;

		extract($args, EXTR_SKIP);

		if ('div' == $args['style']) {
			$tag = 'div';

			$add_below = 'wpc-comment';
		}
		else {
			$tag = 'li';

			$add_below = 'wpc-div-comment';
		}
		?>
		<<?php echo $tag ?> <?php comment_class(empty($args['has_children']) ? 'wpc-comment' : 'wpc-comment wpc-parent'); ?> id="wpc-comment-<?php comment_ID(); ?>">
			<?php if('div' != $args['style']) : ?>
			<div id="wpc-div-comment-<?php comment_ID(); ?>" class="wpc-body">
			<?php endif; ?>

			<div class="wpc-top wpc-group">
				<?php
					if($args['avatar_size'] != 0) {
						echo get_avatar($comment, $args['avatar_size']);
					}
				?>

				<div class="bypostauthor"><?php comment_author(); ?></div>

				<div class="wpc-content"><?php comment_text(); ?></div>

				<?php if($comment->comment_approved == '0') : ?>
				<div class="wpc-message"><em><?php _e('Your comment is awaiting moderation.', 'wpcrest'); ?></em></div>
				<?php endif; ?>
			</div>

			<div class="wpc-bottom wpc-group">
				<div class="wpc-meta">
					<?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>

					<?php echo get_comment_date() . ' @ ' . get_comment_time(); ?> | <a href="<?php comment_link(); ?>"><?php _e('Permalink', 'wpcrest'); ?></a>

					<?php edit_comment_link(__('(Edit Comment)', 'wpcrest'), ' | '); ?>
				</div>
			</div>

			<?php if('div' != $args['style']) : ?></div><?php endif; ?>
		<?php
	}

	static function end_el($comment, $args, $depth) {
		if('div' == $args['style']) {
			echo "</div>\n";
		}
		else {
			echo "</li>\n";
		}
	}

	static function get_file_path($file = null, $type = null) {
		if(empty($type)) {
			if(file_exists(get_stylesheet_directory() . $file)) {
				return get_stylesheet_directory() . $file;
			}
			else {
				return get_template_directory() . $file;
			}
		}
		elseif($type == 'child') {
			return get_stylesheet_directory() . $file;
		}
		elseif($type == 'parent') {
			return get_template_directory() . $file;
		}
	}

	static function get_file_uri($file = null, $type = null) {
		if(empty($type)) {
			if(file_exists(get_stylesheet_directory() . $file)) {
				return get_stylesheet_directory_uri() . $file;
			}
			else {
				return get_template_directory_uri() . $file;
			}
		}
		elseif($type == 'child') {
			return get_stylesheet_directory_uri() . $file;
		}
		elseif($type == 'parent') {
			return get_template_directory_uri() . $file;
		}
	}
}

if(file_exists(WPCrest::get_file_path('/custom/php/functions.php', 'parent'))) {
	require_once(WPCrest::get_file_path('/custom/php/functions.php', 'parent'));
}

WPCrest::load();
?>