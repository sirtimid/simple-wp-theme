<?php

// add project post type
if ( ! function_exists( 'project_custom_type' ) ) {
	function project_custom_type() {
		$labels = array(
			'name'               => __( 'Projects', 'theme' ),
			'singular_name'      => __( 'Project', 'theme' ),
			'add_new'            => __( 'Add New', 'theme' ),
			'add_new_item'       => __( 'Add New Project', 'theme' ),
			'edit_item'          => __( 'Edit Project', 'theme' ),
			'new_item'           => __( 'New Project', 'theme' ),
			'all_items'          => __( 'All Projects', 'theme' ),
			'view_item'          => __( 'View Project', 'theme' ),
			'search_items'       => __( 'Search Projects', 'theme' ),
			'not_found'          => __( 'No Project found', 'theme' ),
			'not_found_in_trash' => __( 'No Project found in the Trash', 'theme' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'Projects', 'theme' )
		);
		$args = array(
			'labels'        => $labels,
			'description'   => 'Holds our Projects specific data',
			'public'        => true,
			'menu_icon'     => 'dashicons-portfolio',
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
			'has_archive'   => true,
			'rewrite' 			=> array('slug' => 'projects')
		);
		register_post_type( 'project', $args );
	}
	add_action( 'init', 'project_custom_type' );
}

// add project categories
if ( ! function_exists( 'project_custom_taxonomies' ) ) {
	function project_custom_taxonomies() {
		$labels = array(
			'name'              => __( 'Categories' ),
			'singular_name'     => __( 'Category' ),
			'search_items'      => __( 'Search Categories' ),
			'all_items'         => __( 'All Categories' ),
			'parent_item'       => __( 'Parent Category' ),
			'parent_item_colon' => __( 'Parent Category:' ),
			'edit_item'         => __( 'Edit Category' ),
			'update_item'       => __( 'Update Category' ),
			'add_new_item'      => __( 'Add New Category' ),
			'new_item_name'     => __( 'New Category' ),
			'menu_name'         => __( 'Categories' )
		);
		$args = array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'projects/category' ),
		);
		register_taxonomy( 'project_category', 'project', $args );
	}
	add_action( 'init', 'project_custom_taxonomies', 0 );
}

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
	add_action('init', 'theme_options_init');
}
