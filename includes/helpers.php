<?php

if(!function_exists('list_categories')){
	function list_categories($post_id, $taxonomy, $link = true) {
		$cats = get_the_terms($post_id, $taxonomy);
		if (!$cats || !count($cats)) return '';
		$arr = array();
		foreach($cats as $cat) {
			$arr[] = $link ? '<a href="'. get_category_link( $cat->term_id ) .'">' . __($cat->name) . '</a>' : __($cat->name);
		}
		return implode(', ', $arr);
	}
}

if(!function_exists('get_url_by_template')){
	function get_url_by_template($tmpl){
		$pages = query_posts(array(
			'post_type' =>'page',
			'meta_key'  =>'_wp_page_template',
			'meta_value'=> $tmpl
		));

		$url = null;
		if(isset($pages[0])) {
			$url = get_page_link($pages[0]->ID);
		}
		wp_reset_query();
		return $url;
	}
}