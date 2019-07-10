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
		ob_start();

		$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
		$product           = wc_get_product( $product_id );
		$quantity          = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
		$product_status    = get_post_status( $product_id );
		$variation_id      = empty( $_POST['variation_id'] ) ? 0 : absint( $_POST['variation_id'] );
		$variation         = array();

		if ( $product && 'variation' === $product->get_type() ) {
			$variation    = $product->get_variation_attributes();
		}

		if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation ) && 'publish' === $product_status ) {
			do_action( 'woocommerce_ajax_added_to_cart', $product_id );
		}

		wp_send_json(array(
			'html' => get_cart_contents(),
			'count' => WC()->cart->get_cart_contents_count()
		));
	}
}

// change quantity from ajax call
if(!function_exists('ajax_remove_from_cart')){
	add_action( 'wp_ajax_ajax_remove_from_cart', 'ajax_remove_from_cart' );
	add_action( 'wp_ajax_nopriv_ajax_remove_from_cart', 'ajax_remove_from_cart' );

	function ajax_remove_from_cart() {
		ob_start();

		$cart_item_key = wc_clean( $_POST['cart_item_key'] );

		WC()->cart->remove_cart_item( $cart_item_key );

		wp_send_json(array(
			'html' => get_cart_contents(),
			'count' => WC()->cart->get_cart_contents_count()
		));
	}
}

// change quantity from ajax call
if(!function_exists('change_quantity')){
	add_action( 'wp_ajax_change_quantity', 'change_quantity' );
	add_action( 'wp_ajax_nopriv_change_quantity', 'change_quantity' );

	function change_quantity() {
		ob_start();

		$cart_item_key = wc_clean( $_POST['cart_item_key'] );
		$quantity = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );

		WC()->cart->set_quantity($cart_item_key, $quantity);

		wp_send_json(array(
			'html' => get_cart_contents(),
			'count' => WC()->cart->get_cart_contents_count()
		));
	}
}

// get term image
if(!function_exists('product_cat_image')){
	function product_cat_image($term_id) {
		$thumbnail_id = get_term_meta( $term_id, 'thumbnail_id', true );
		return wp_get_attachment_url( $thumbnail_id );
	}
}

// get tax rate
if(!function_exists('get_tax')){
	function get_tax() {
		$taxes = WC_Tax::get_rates();
		$taxes = reset($taxes);
		return round($taxes['rate']);
	}
}