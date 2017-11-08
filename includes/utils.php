<?php

if(!function_exists('has_text')){
	function has_text(&$str) {
		return isset($str) && ($str != NULL) && (trim($str) != '');
	}
}

if(!function_exists('truncate')){
	function truncate($text, $chars = 25) {
		 $txt = substr($text, 0, $chars);
		 if (strlen($text) > $chars) { $txt .=  "..."; }
		 return $txt;
	}
}

if(!function_exists('strtoupper_utf8')){
	function strtoupper_utf8($string){
		$string = $string.' ';
		$convert_from = array(
			'ά','α','β','γ','δ','έ','ε','ζ','ή','η','θ','ί','ΐ','ϊ','ι','κ','λ','μ','ν','ξ','ό','ο','π','ρ',
			'ς ','ς','σ','τ','ύ','υ','φ','χ','ψ','ώ','ω', 'Ά','Ί','Ή','Ύ','Έ','Ό','Ώ',
			'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u',
			'v', 'w', 'x', 'y', 'z', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï',
			'ð', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж',
			'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы',
			'ь', 'э', 'ю', 'я'
		);
		$convert_to = array(
			'Α','Α','Β','Γ','Δ','Ε','Ε','Ζ','Η','Η','Θ','Ι','Ι','Ι','Ι','Κ','Λ','Μ','Ν','Ξ','Ο','Ο',
			'Π','Ρ','Σ ','Σ','Σ','Τ','Υ','Υ','Φ','Χ','Ψ','Ω','Ω','Α','Ι','Η','Υ','Ε','Ο','Ω',
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U',
			'V', 'W', 'X', 'Y', 'Z', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï',
			'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж',
			'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ъ',
			'Ь', 'Э', 'Ю', 'Я'
		);
		return trim(str_replace($convert_from, $convert_to, $string));
	}
}

if(!function_exists('force_download')){
	function force_download($url) {
		set_time_limit(0);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$r = curl_exec($ch);
		curl_close($ch);
		header('Expires: 0'); // no cache
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
		header('Cache-Control: private', false);
		header('Content-Type: application/force-download');
		header('Content-Disposition: attachment; filename="' . basename($url) . '"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . strlen($r)); // provide file size
		header('Connection: close');
		echo $r;
	}
}

// get video image from youtube or vimeo link
if(!function_exists('get_video_image')){
	function get_video_image($url){
		$thumb = '';

		if(strpos($url, 'vimeo.com') > -1){

			$id = false;
			$result = preg_match('/(\d+)/', $url, $matches);
			if($result) {
				$id = $matches[0];
			}
			if($id != false){
				$hash = unserialize(@file_get_contents("http://vimeo.com/api/v2/video/$id.php"));
				$thumb = $hash[0]['thumbnail_medium'];
			}

		}else{
			parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
			$id = $my_array_of_vars['v'];
			$thumb = 'http://img.youtube.com/vi/'.$id.'/0.jpg';
		}

		return $thumb;
	}
}

// parse string to return youtube id
if(!function_exists('youtube_id')){
	function youtube_id($url) {
		parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
			return $my_array_of_vars['v'];
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