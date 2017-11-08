<?php

// theme setup
if ( !function_exists( 'shop_theme_setup' ) ) {
	function shop_theme_setup() {
		// declare woocommerce support
		add_theme_support( 'woocommerce' );
	}
}
add_action( 'after_setup_theme', 'shop_theme_setup' );

// remove unesessary woocommerce actions
if ( !function_exists( 'shop_cleanup' ) ) {
	function shop_cleanup() {
		remove_action('wp_head','wc_generator_tag');
		remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
		remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
		remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
		remove_action('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10);
		remove_action('woocommerce_archive_description', 'woocommerce_product_archive_description', 10);
		remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
		remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
		remove_action('woocommerce_before_single_product', 'wc_print_notices', 10);

		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
	}
	add_action( 'init', 'shop_cleanup' );
}

// return basket count
if(!function_exists('get_basket_count')){
	function get_basket_count($force=FALSE){
		$item_output = $force ? '' : '<span class="cart-count"></span>';

		$items =  WC()->cart->get_cart_contents_count();
		if($items){
			$label = $items === 1 ? __('item', 'typical') : __('items', 'typical');
			$items_label =$items < 10 ? '0'.$items : $items;
			$item_output = ' <span class="cart-count">('. $items_label .' '. $label . ')</span>';
		}

		return $item_output;
	}
}

// return basket count from ajax call
if(!function_exists('ajax_cart_count_fragments')){
	function ajax_cart_count_fragments( $fragments ) {
		$fragments['span.cart-count'] = get_basket_count();
		return $fragments;
	}
	add_filter( 'woocommerce_add_to_cart_fragments', 'ajax_cart_count_fragments', 10, 1 );
}

// add to cart from ajax call
if(!function_exists('add_to_cart_single')){
	add_action( 'wp_ajax_add_to_cart_single', 'add_to_cart_single' );
	add_action( 'wp_ajax_nopriv_add_to_cart_single', 'add_to_cart_single' );

	function add_to_cart_single() {
		$product_id = $_REQUEST['product_id'];
		$variation_id = $_REQUEST['variation_id'];
		$quantity = $_REQUEST['quantity'];

		if (has_text($variation_id)) {
			WC()->cart->add_to_cart( $product_id, $quantity, $variation_id );
		} else {
			WC()->cart->add_to_cart( $product_id, $quantity );
		}

		wp_send_json(array(
			'html' => get_basket_count(TRUE),
			'count' => WC()->cart->get_cart_contents_count()
		));

		die();
	}
}

// change quantity from ajax call
if(!function_exists('change_quantity')){
	add_action( 'wp_ajax_change_quantity', 'change_quantity' );
	add_action( 'wp_ajax_nopriv_change_quantity', 'change_quantity' );

	function change_quantity() {
		$cart_item_key = $_REQUEST['cart_item_key'];
		$quantity = $_REQUEST['quantity'];

		WC()->cart->set_quantity($cart_item_key, $quantity);

		wp_send_json(array(
			'html' => get_basket_count(TRUE),
			'count' => WC()->cart->get_cart_contents_count()
		));

		die();
	}
}