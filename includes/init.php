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

		wp_register_script('theme', get_stylesheet_directory_uri() . '/assets/js/theme'.$min.'.js', array('jquery','vendors'), $ver, true);
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

// Initialize options page for use in ACF pro
if ( !function_exists( 'theme_options_init' ) ) {
	function theme_options_init() {
		if( function_exists('acf_add_options_page') ) {
			$page = acf_add_options_page(array(
				'page_title'  => 'Theme Settings',
				'menu_title'  => 'Theme Settings',
				'menu_slug'   => 'theme-settings',
				'capability'  => 'edit_posts',
				'redirect'    => false,
				'position' => 58
				// 'icon_url' => ''
			));
		}
	}
}
add_action('init', 'theme_options_init');

// add admin styles
if ( !function_exists( 'admin_styles' ) ) {
	function admin_styles() {
		wp_enqueue_style( 'admin_css', get_stylesheet_directory_uri() . '/admin/admin.css', false );
	}
}
add_action('admin_head', 'admin_styles');

// add tinymce editor styles
if ( !function_exists( 'custom_add_editor_styles' ) ) {
	function custom_add_editor_styles() {
			add_editor_style( get_stylesheet_directory_uri() . '/admin/editor.css' );
	}
}
add_action( 'admin_init', 'custom_add_editor_styles' );

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

// disable default dashboard widgets
if ( !function_exists( 'disable_default_dashboard_widgets' ) ) {
	function disable_default_dashboard_widgets() {
		global $wp_meta_boxes;
		// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);    // Right Now Widget
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);        // Activity Widget
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // Comments Widget
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);  // Incoming Links Widget
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);         // Plugins Widget

		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);    // Quick Press Widget
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);     // Recent Drafts Widget
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);           //
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);         //

		// remove plugin dashboard boxes
		// unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);           // Yoast's SEO Plugin Widget
		unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);        // Gravity Forms Plugin Widget
		unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);   // bbPress Plugin Widget
	}
	add_action( 'wp_dashboard_setup', 'disable_default_dashboard_widgets' );
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

if ( !function_exists( 'remove_menus' ) ) {
	function remove_menus(){
		// remove_menu_page( 'index.php' );                  //Dashboard
		// remove_menu_page( 'edit.php' );                   //Posts
		// remove_menu_page( 'upload.php' );                 //Media
		// remove_menu_page( 'edit.php?post_type=page' );    //Pages
		remove_menu_page( 'edit-comments.php' );          //Comments
		// remove_menu_page( 'themes.php' );                 //Appearance
		// remove_menu_page( 'plugins.php' );                //Plugins
		// remove_menu_page( 'users.php' );                  //Users
		// remove_menu_page( 'tools.php' );                  //Tools
		// remove_menu_page( 'options-general.php' );        //Settings
	}
	add_action( 'admin_menu', 'remove_menus' );
}

// Enable font size & font family selects in the editor
if ( !function_exists( 'wpex_mce_buttons' ) ) {
	function wpex_mce_buttons( $buttons ) {
			// array_unshift( $buttons, 'fontselect' ); // Add Font Select
			array_unshift( $buttons, 'fontsizeselect' ); // Add Font Size Select
			// array_unshift($buttons, 'styleselect'); // Add stle select
			return $buttons;
	}
	add_filter( 'mce_buttons_2', 'wpex_mce_buttons' );
}

// Customize mce editor font sizes
if ( !function_exists( 'wpex_mce_text_sizes' ) ) {
	function wpex_mce_text_sizes( $initArray ){
		$initArray['fontsize_formats'] = "9px 10px 11px 12px 13px 14px 16px 18px 21px 24px 28px 32px 36px";
		return $initArray;
	}
	add_filter( 'tiny_mce_before_init', 'wpex_mce_text_sizes' );
}

if ( !function_exists( 'loginLogo' ) ) {
	function loginLogo(){
		echo '<style type="text/css">
			body.login div#login h1 a {
				width:100%;
				background-image: url('. get_stylesheet_directory_uri() . '/assets/images/logo.svg);
				background-size: auto 60px;
				height:60px;
			}
		</style>';
	}
	add_action( 'login_enqueue_scripts', 'loginLogo' );
}

?>
