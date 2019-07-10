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

if(!function_exists('normalize')){
	function normalize($str) {
		$str = strtoupper_utf8(greeklish(trim($str)));
		$unwanted_array = array(
			'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
      'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
      'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
      'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
      'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y'
    );
    return strtr( $str, $unwanted_array );
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
				$hash = unserialize(@file_get_contents("https://vimeo.com/api/v2/video/$id.php"));
				$thumb = $hash[0]['thumbnail_medium'];
			}

		}else{
			parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
			$id = $my_array_of_vars['v'];
			$thumb = 'https://img.youtube.com/vi/'.$id.'/0.jpg';
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

if(!function_exists('get_asset')){
	function get_asset($path){
		return get_stylesheet_directory_uri() . '/assets/'. $path;
	}
}