<?php

// return social menu
if(!function_exists('get_social_menu')){
	function get_social_menu(){
		$social_profiles = array(
			'facebook',
			'youtube',
			'twitter',
			'pinterest',
			'instagram',
		);

		$html = '<div class="social-menu">';
		foreach ($social_profiles as $value) {
			$url = get_field($value.'_url', 'option');
			if($url){
				$html .= '<a href="'.$url.'" target="_blank">'.$value.'</a>';
			}
		}
		$html .= '</div>';
		return $html;
	}
}

if(!function_exists('setup_theme_menu')){
	function setup_theme_menu(){
		register_nav_menus([
			// 'header_menu' => __('Header Menu', 'theme'),
			'primary_menu' => __('Main Menu', 'theme'),
			// 'footer_menu' => __('Footer Menu', 'theme'),
			// 'copyright_menu' => __('Copyright Menu', 'theme')
			// 'social_menu' => __('Social Menu', 'theme')
		]);
	}
	add_action('after_setup_theme', 'setup_theme_menu');
}


if ( !function_exists('footer_widgets_init') ) {
	function footer_widgets_init() {
		register_sidebar( array(
			'name'          => 'Footer Widget',
			'id'            => 'footer-widget',
			'description'   => __( 'The content that is displayed on footer', 'text_domain' ),
			'before_widget' => '<div class="footer-widget">',
			'after_widget'  => '</div>'
		) );
	}
	add_action( 'widgets_init', 'footer_widgets_init' );
}

if(!function_exists('get_menu')){
	function get_menu($location = 'primary', $class = 'menu', $walker = false){

		$args = array(
			'theme_location'  => $location,
			'container' => 'div',
			'container_class' => $class,
			'menu_class' => $class.'-menu',
			'depth' => 0,
			'echo' => false,
		);

		if($walker){
			$args['walker'] = new NavWalker;
		}

		return wp_nav_menu($args);
	}
}

/**
 * Custom walker for wp_nav_menu()
 */
class NavWalker extends Walker_Nav_Menu {

	function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
		$element->is_dropdown = ((!empty($children_elements[$element->ID]) && (($depth + 1) < $max_depth || ($max_depth === 0))));
		if ($element->is_dropdown) {
			$element->classes[] = 'dropdown';
		}
		parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';

		$item_output .= $args->link_before;
		$item_output .= apply_filters( 'the_title', $item->title, $item->ID );
		$item_output .= $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		if ($item->is_dropdown && ($depth === 0)) {
			$item_output = str_replace('<a', '<a class="dropdown-toggle" data-toggle="dropdown" data-target="#"', $item_output);
			$item_output = str_replace('</a>', ' <b class="caret"></b></a>', $item_output);
		}
		elseif (stristr($item_output, 'li class="divider')) {
			$item_output = preg_replace('/<a[^>]*>.*?<\/a>/iU', '', $item_output);
		}
		elseif (stristr($item_output, 'li class="dropdown-header')) {
			$item_output = preg_replace('/<a[^>]*>(.*)<\/a>/iU', '$1', $item_output);
		}

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}