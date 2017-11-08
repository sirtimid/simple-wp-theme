<?php

// add portfolio post type
if ( ! function_exists( 'works_custom_type' ) ) {
	function works_custom_type() {
		$labels = array(
			'name'               => __( 'Works' ),
			'singular_name'      => __( 'Project' ),
			'add_new'            => __( 'Add New' ),
			'add_new_item'       => __( 'Add New Project' ),
			'edit_item'          => __( 'Edit Project' ),
			'new_item'           => __( 'New Project' ),
			'all_items'          => __( 'All Projects' ),
			'view_item'          => __( 'View Project' ),
			'search_items'       => __( 'Search Works' ),
			'not_found'          => __( 'No Project found' ),
			'not_found_in_trash' => __( 'No Project found in the Trash' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'Works' )
		);
		$args = array(
			'labels'        => $labels,
			'description'   => 'Holds our Works specific data',
			'public'        => true,
			'menu_icon'     => 'dashicons-portfolio',
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
			'has_archive'   => true,
		);
		register_post_type( 'portfolio', $args );
	}
	add_action( 'init', 'works_custom_type' );
}

// add portfolio categories
if ( ! function_exists( 'portfolio_custom_taxonomies' ) ) {
	function portfolio_custom_taxonomies() {
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
			'labels' => $labels,
			'hierarchical' => true,
		);
		register_taxonomy( 'works', 'portfolio', $args );
	}
	add_action( 'init', 'portfolio_custom_taxonomies', 0 );
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
