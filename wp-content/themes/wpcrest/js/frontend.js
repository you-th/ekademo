/**
 * @package WordPress
 * @subpackage WPCrest
 */

var WPCrest = WPCrest || {
	Frontend : {
		load_page_preloader: function() {
			if(wpcrest_frontend.disable_page_preloader != 1) {
				jQuery('.wpc-loading').fadeOut();
			}
		},
		load_ajax_preloader: function() {
			if(wpcrest_frontend.disable_ajax_preloader != 1) {
				jQuery('.wpc-loading').ajaxStart(function() {
					jQuery(this).show();
				});
		
				jQuery('.wpc-loading').ajaxStop(function() {
					jQuery(this).hide();

					jQuery(document).trigger('wpc-refresh');
				});
			}
		},
		load_links_menu: function() {
			jQuery('.wpc-links .wpc-navigation li').each(function() {
				if(jQuery(this).children('ul').length > 0) {
					jQuery('> a', this).append('<span>&raquo;</span>');
				}
			});
		},
		load_scheme_switcher: function() {
			if(wpcrest_frontend.disable_scheme_switcher != 1) {
				jQuery('.wpc-schemes .wpc-list .wpc-font-size img').click(function() {
					var font_base_size = 0.625;
		
					var font_size_relative = parseFloat(jQuery.cookie('wpc-font-size-relative'));
		
					if(isNaN(font_size_relative)) {
						font_size_relative = 0;
					}
		
					var font_action = jQuery(this).attr('class');
		
					if(font_action == 'wpc-decrease') {
						font_size_relative -= 0.1;
					}
					else if(font_action == 'wpc-increase') {
						font_size_relative += 0.1;
					}
					else if(font_action == 'wpc-default') {
						font_size_relative = 0;
					}
		
					font_size = font_base_size + font_size_relative;
		
					font_size = font_size + 'em';
		
					if(font_size == '0.625em') {
						jQuery.removeCookie('wpc-font-size-relative', {path: '/'});
					}
					else {
						jQuery.cookie('wpc-font-size-relative', font_size_relative, {path: '/', expires: 30});
					}
		
					jQuery('html').css('font-size', font_size);

					jQuery(document).trigger('wpc-refresh');
				});
		
				jQuery('.wpc-schemes .wpc-skins li').click(function() {
					var skin_name = jQuery(this).attr('class').split(' ')[0];

					jQuery.cookie('wpc-active-skin', skin_name, {path: '/', expires: 30});

					if(skin_name == 'none') {
						jQuery('#wpc-skin-css').replaceWith('<link rel="stylesheet" id="wpc-skin-css" type="text/css" media="all" />');
					}
					else {
						jQuery('#wpc-skin-css').replaceWith('<link rel="stylesheet" id="wpc-skin-css" href="' + wpcrest_frontend.template_url + '/schemes/skins/' + skin_name + '/screen.css" type="text/css" media="all" />');
					}

					return false;
				});

				var background_types = {background_underlay: {class_name: 'underlay', tag_name: 'html'}, background_overlay: {class_name: 'overlay', tag_name: 'body'}};

				for(var key in background_types) {
					WPCrest.Frontend.load_scheme_switcher_background(background_types[key].class_name, background_types[key].tag_name);
				}

				var scheme_swither = 'closed';
		
				jQuery('.wpc-schemes .wpc-toggle').click(function() {
					if(scheme_swither == 'closed') {
						jQuery('.wpc-schemes').animate({left: 0});
		
						scheme_swither = 'open';
					}
					else {
						jQuery('.wpc-schemes').animate({left: '-' + jQuery('.wpc-schemes').css('width')});
		
						scheme_swither = 'closed';
					}
				});
			}
		},
		load_scheme_switcher_background: function(class_name, tag_name) {
			jQuery('.wpc-schemes .wpc-background-' + class_name + 's li').click(function() {
				var background_name = jQuery(this).attr('class').split(' ')[0];
	
				jQuery.cookie('wpc-active-background-' + class_name, background_name, {path: '/', expires: 30});
	
				var properties = jQuery(this).data('properties');
	
				if(properties === undefined) {
					jQuery('#wpc-background-' + class_name).remove();
	
					jQuery(tag_name).removeAttr('style');
				}
				else {
					jQuery(tag_name).css('background', 'none');
		
					for(var key in properties) {
						if(key == 'background-image') {
							if(background_name == 'custom') {
								jQuery(tag_name).css('background-image', 'url(' + properties[key] + ')');
							}
							else {
								jQuery(tag_name).css('background-image', 'url(' + wpcrest_frontend.template_url + '/schemes/background-' + class_name + 's/' + properties[key] + ')');
							}
						}
						else if(key == 'background-position' || key == 'background-size') {
							var property = null;
	
							if((properties[key].variable !== undefined) && properties[key].variable.x !== undefined && properties[key].variable.x !== null && properties[key].variable.y !== undefined && properties[key].variable.y !== null) {
								property = properties[key].variable.x + ' ' + properties[key].variable.y;
							}
							else if(properties[key].constant !== null) {
								property = properties[key].constant;
							}
	
							if(property !== 'null') {
								jQuery(tag_name).css(key, property);
							}
						}
						else {
							jQuery(tag_name).css(key, properties[key]);
						}
					}
				}
	
				return false;
			});
		},
		load_masonry: function() {
			jQuery('.wpc-blog-main .wpc-items').imagesLoaded(function() {
				jQuery('.wpc-blog-main .wpc-items').masonry();
			});

			jQuery('.wpc-panel > ul').imagesLoaded(function() {
				jQuery('.wpc-panel > ul').masonry();
			});
		},
		load_colorbox_posts: function() {
			if(wpcrest_frontend.disable_colorbox_posts != 1) {
				WPCrest.Frontend.load_colorbox(jQuery('.wpc-type-post-preview .wpc-title a'), WPCrest.Frontend.colorbox_item_options);
			}
		},
		load_colorbox_images: function() {
			if(wpcrest_frontend.disable_colorbox_images != 1) {
				WPCrest.Frontend.load_colorbox(jQuery('a img').parent('a[href$=".jpg"], a[href$=".jpeg"], a[href$=".png"], a[href$=".gif"]'), WPCrest.Frontend.colorbox_item_options);
			}
		},
		load_placeholder: function() {
			jQuery('input, textarea').placeholder();
		},
		load_qtip: function() {
			if(wpcrest_frontend.disable_qtip != 1) {
				jQuery(':not([title]) > [title]').qtip({position: {my: 'left top', at: 'right top'}, style: {classes: 'ui-tooltip-dark ui-tooltip-shadow ui-tooltip-rounded'}});
			}
		},
		load_external_urls_new_window: function() {
			if(wpcrest_frontend.external_urls_new_window == 1) {
				jQuery("a[href*='http://']:not([href*='" + location.hostname + "']),[href*='https://']:not([href*='" + location.hostname + "'])").attr('target', '_blank');
			}
		},
		load_media_urls_new_window: function() {
			if(wpcrest_frontend.media_urls_new_window == 1) {
				jQuery("a[href$='.jpg'], a[href$='.jpeg'], a[href$='.png'], a[href$='.gif'], a[href$='.pdf']").attr('target', '_blank');
			}
		},
		load_ajax_pagination: function() {
			if(wpcrest_frontend.disable_ajax_pagination != 1) {
				jQuery('.wpc-container').on('click', '.wpc-ajax-request a, .wpc-ajax-request a img', function(event) {
					if(jQuery(event.target).is('.wpc-ajax-request a img')) {
						var ajax_href = jQuery(event.target).parent('a').attr('href');
					}
					else {
						var ajax_href = jQuery(event.target).attr('href');
					}

					jQuery.post(ajax_href, {
							'wpc-action': 'ajax-request'
						},
						function(data) {
							jQuery('.wpc-container').replaceWith(data);

							history.pushState(null, null, ajax_href);
						}
					);
	
					return false;
				});
			}
		},
		load_colorbox: function(element, options) {
			jQuery(element).colorbox(options);
		},
		colorbox_item_options: {
			href: function() {
				return jQuery(this).attr('href') + (/\?+/.test(jQuery(this).attr('href')) === true ? '&' : '?') + 'wpc-action=ajax-request';
			},
			width: '94%',
			height: '94%',
			initialWidth: '94%',
			initialHeight: '94%',
			maxWidth: '94%',
			maxHeight: '94%',
			top: '3%',
			left: '3%',
			fixed: true,
		}
	}
}

jQuery(document).on('ready', function() {
	WPCrest.Frontend.load_page_preloader();

	WPCrest.Frontend.load_ajax_preloader();

	WPCrest.Frontend.load_links_menu();

	WPCrest.Frontend.load_scheme_switcher();
});

jQuery(document).on('ready wpc-refresh', function() {
	WPCrest.Frontend.load_ajax_pagination();

	WPCrest.Frontend.load_masonry();

	WPCrest.Frontend.load_colorbox_posts();

	WPCrest.Frontend.load_colorbox_images();

	WPCrest.Frontend.load_placeholder();

	WPCrest.Frontend.load_qtip();

	WPCrest.Frontend.load_external_urls_new_window();

	WPCrest.Frontend.load_media_urls_new_window();
});