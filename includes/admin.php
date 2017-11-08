<?php

// add logo top login page
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

// remove admin sidebar menus
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

// exclude tinymce editor from page templates
if ( !function_exists( 'hide_editor' ) ) {
	function hide_editor() {
		// Get the Post ID.
		if ( isset ( $_GET['post'] ) )
		$post_id = $_GET['post'];
		else if ( isset ( $_POST['post_ID'] ) )
		$post_id = $_POST['post_ID'];

		if( !isset ( $post_id ) || empty ( $post_id ) )
				return;

		// Get the name of the Page Template file.
		$template_file = get_post_meta($post_id, '_wp_page_template', true);

		$exclude_editor = array(
			// 'template-home.php'
		);

		if(in_array($template_file, $exclude_editor)){
			remove_post_type_support('page', 'editor');
		}
	}
	add_action('admin_init', 'hide_editor');
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

// convert all slugs to greeklish
if ( !function_exists('greeklish_slugs') ) {
	function greeklish_slugs($text) {
		if ( !is_admin() ) return $text;

		$expressions = array(
			'/[αΑ][ιίΙΊ]/u' => 'e',
			'/[οΟΕε][ιίΙΊ]/u' => 'i',
			'/[αΑ][υύΥΎ]([θΘκΚξΞπΠσςΣτTφΡχΧψΨ]|\s|$)/u' => 'af$1',
			'/[αΑ][υύΥΎ]/u' => 'av',
			'/[εΕ][υύΥΎ]([θΘκΚξΞπΠσςΣτTφΡχΧψΨ]|\s|$)/u' => 'ef$1',
			'/[εΕ][υύΥΎ]/u' => 'ev',
			'/[οΟ][υύΥΎ]/u' => 'ou',
			'/[μΜ][πΠ]/u' => 'mp',
			'/[νΝ][τΤ]/u' => 'nt',
			'/[τΤ][σΣ]/u' => 'ts',
			'/[τΤ][ζΖ]/u' => 'tz',
			'/[γΓ][γΓ]/u' => 'ng',
			'/[γΓ][κΚ]/u' => 'gk',
			'/[ηΗ][υΥ]([θΘκΚξΞπΠσςΣτTφΡχΧψΨ]|\s|$)/u' => 'if$1',
			'/[ηΗ][υΥ]/u' => 'iu',
			'/[θΘ]/u' => 'th',
			'/[χΧ]/u' => 'ch',
			'/[ψΨ]/u' => 'ps',
			'/[αάΑΆ]/u' => 'a',
			'/[βΒ]/u' => 'v',
			'/[γΓ]/u' => 'g',
			'/[δΔ]/u' => 'd',
			'/[εέΕΈ]/u' => 'e',
			'/[ζΖ]/u' => 'z',
			'/[ηήΗΉ]/u' => 'i',
			'/[ιίϊΙΊΪ]/u' => 'i',
			'/[κΚ]/u' => 'k',
			'/[λΛ]/u' => 'l',
			'/[μΜ]/u' => 'm',
			'/[νΝ]/u' => 'n',
			'/[ξΞ]/u' => 'x',
			'/[οόΟΌ]/u' => 'o',
			'/[πΠ]/u' => 'p',
			'/[ρΡ]/u' => 'r',
			'/[σςΣ]/u' => 's',
			'/[τΤ]/u' => 't',
			'/[υύϋΥΎΫ]/u' => 'i',
			'/[φΦ]/iu' => 'f',
			'/[ωώ]/iu' => 'o',
			'/[«]/iu' => '',
			'/[»]/iu' => ''
			);

		$text = preg_replace( array_keys($expressions), array_values($expressions), $text );
				// replace 1 char words
		$text = preg_replace('/\s+\D{1}(?!\S)|(?<!\S)\D{1}\s+/', '', $text);
				// replace 2 chars words
				// $text = preg_replace('/\s+\D{2}(?!\S)|(?<!\S)\D{2}\s+/', '', $text);
		return $text;
	}
}
add_filter('sanitize_title', 'greeklish_slugs', 1);