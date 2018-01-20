<?php

// add scripts
if ( !function_exists( 'theme_enqueue_scripts' ) ) {
	function theme_enqueue_scripts() {
		$ver = '0.1';
		$min = '.min';
		$extension = substr($_SERVER['SERVER_NAME'], strrpos($_SERVER['SERVER_NAME'], '.')+1);
		if(!in_array($extension, array('gr','com','org','eu','net'))){
			wp_register_script('livereload', '//'.$_SERVER['SERVER_NAME'].':35729/livereload.js?snipver=1', null, false, true);
			wp_enqueue_script('livereload');
			$min = '';
		}

		wp_register_script('modernizr', get_stylesheet_directory_uri() . '/assets/js/modernizr.min.js');
		wp_enqueue_script('modernizr');

		// Deregister the jquery version bundled with WordPress.
		wp_deregister_script( 'jquery' );
		// CDN hosted jQuery placed in the header
		wp_enqueue_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js', array(), '3.2.1', false );

		wp_register_script('vendors', get_stylesheet_directory_uri() . '/assets/js/vendors.min.js', array('jquery'), false, true);
		wp_enqueue_script('vendors');

		wp_register_script('theme', get_stylesheet_directory_uri() . '/assets/js/app.min.js', array('jquery','vendors'), $ver, true);
		wp_localize_script( 'theme', 'themeAjax',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php'),
				'nonce'=> wp_create_nonce('ajax_nonce')
			)
		);
		wp_enqueue_script('theme');

		wp_enqueue_style('theme', get_stylesheet_directory_uri() . '/assets/css/theme'.$min.'.css', array(), $ver);
	}
}
add_action('wp_enqueue_scripts', 'theme_enqueue_scripts', 11);

// theme setup
if ( !function_exists( 'theme_setup' ) ) {
	function theme_setup() {
		load_theme_textdomain( 'theme_txt', get_template_directory() . '/languages' );
		add_theme_support('title-tag');
		remove_theme_support( 'post-formats' );
		add_theme_support('post-thumbnails');
		add_filter('deprecated_constructor_trigger_error', '__return_false');
	}
}
add_action( 'after_setup_theme', 'theme_setup' );

// filter to remove TinyMCE emojis
if ( !function_exists( 'disable_emojicons_tinymce' ) ) {
	function disable_emojicons_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}
	}
}

// launching operation cleanup
if ( !function_exists( 'head_cleanup' ) ) {
	function head_cleanup() {
		// EditURI link
		remove_action( 'wp_head', 'rsd_link' );
		// windows live writer
		remove_action( 'wp_head', 'wlwmanifest_link' );
		// previous link
		remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
		// start link
		remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
		// links for adjacent posts
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		// WP version
		remove_action( 'wp_head', 'wp_generator' );
		// remove emoji
		// all actions related to emojis
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );

		// remove_all_filters('posts_orderby');
		add_filter( 'max_srcset_image_width', create_function( '', 'return 1;' ) );
	}
	add_action( 'init', 'head_cleanup' );
}

if ( !function_exists( 'cb_remove_smileys' ) ) {
	function cb_remove_smileys($bool) {
		return false;
	}
	add_filter('option_use_smilies','cb_remove_smileys',99,1);
}

// remove WP version from RSS
if ( !function_exists( 'rss_version' ) ) {
	function rss_version() { return ''; }
	add_filter( 'the_generator', 'rss_version' );
}

// remove WP version from scripts
if ( !function_exists( 'remove_wp_ver_css_js' ) ) {
	function remove_wp_ver_css_js( $src ) {
		if ( strpos( $src, 'ver=' ) )
			$src = remove_query_arg( 'ver', $src );
		return $src;
	}
	add_filter( 'style_loader_src', 'remove_wp_ver_css_js', 9999 );
	add_filter( 'script_loader_src', 'remove_wp_ver_css_js', 9999 );
}

// remove pesky injected css for recent comments widget
if ( !function_exists( 'remove_wp_widget_recent_comments_style' ) ) {
	function remove_wp_widget_recent_comments_style() {
		if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
			remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
		}
	}
	add_filter( 'wp_head', 'remove_wp_widget_recent_comments_style', 1 );
}

// remove injected CSS from recent comments widget
if ( !function_exists( 'remove_recent_comments_style' ) ) {
	function remove_recent_comments_style() {
		global $wp_widget_factory;
		if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
			remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
		}
	}
	add_action( 'wp_head', 'remove_recent_comments_style', 1 );
}
