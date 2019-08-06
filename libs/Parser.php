<?php 
class Parser {
	
	public function countUrl(){
		$url = isset($_GET['url']) ? $_GET['url'] : null;
		$url = rtrim($url, '/');
		$url = str_replace('.html','',$url);
		$url = explode('/', $url);
		$count = count($url);
		return $count;
	}
	
	public function countUrlLocal($str){
		$url = $str;
		$url = rtrim($url, '/');
		$url = explode('/', $url);
		$count = count($url);
		return $count;
	}
	
	public function url($position) {
		
		$url = isset($_GET['url']) ? $_GET['url'] : null;
		$url = $url = rtrim($url, '/');
		$url = str_replace('.html','',$url);
		$url = explode('/', $url);
		return  $url[$position];
		
	}
	
	public function createUrl($str){
		
		$rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', ',',' ','_');
		$lat = array('a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'u', 'ya', '/','-','-');
		$url = str_replace($rus, $lat, $str);
		
		return $url;
	}
	
	public function productHref() {
		
		$url = isset($_GET['url']) ? $_GET['url'] : null;
		$url = str_replace('.html','',$url);
		$url = trim($url);
		$url = explode('/', $url);
		array_shift($url);
		$url = implode('/',$url);
		return  $url;
		
	}
	
	public function urlPath() {
		
		$url = isset($_GET['url']) ? $_GET['url'] : null;
		$url = rtrim($url, '.html');
		$url = explode('/', $url);
		return  $url;
		
	}
	
	public function urlPathLocal($str) {
		
		$url = $str;
		$url = rtrim($url, '/');
		$url = explode('/', $url);
		return  $url;
		
	}
	
	public function domain($string) {
		
		$tmp = parse_url($string);
		$url = $tmp['host'];
		return $url;
	}
	
	public function searchQuery($str) {
		
		$return = NULL;
		
		if($str) {
					$decode = urldecode($str);
					$url = parse_url($decode);
					
					if($url['query']) {
						
						parse_str($url['query'],$params);
						
						if(!empty($params['q'])) $return = $params['q'];
						
						if(!empty($params['text']) and empty($params['q'])) $return = $params['text'];
						
						if(!empty($params['utm_term']) and empty($params['q']) and empty($params['text'])) $data[$i]['query'] = $params['utm_term'];
					}
					
				}
		
		return $return;
	}
	
	public function parseUrl($str) {
		
		$return = NULL;
		
		if($str) 
		{
			$decode = urldecode($str);
			
			$url = parse_url($decode);
					
			if($url['query']) parse_str($url['query'],$params);
			
			$return = array( "url" => $url,
							 "query" => $params
							);
		}
		
		return $return;
	}
	
	public function phone($string) {
		
		$number = preg_replace('~\D+~','',$string);  
		 
		if ( preg_match( '/^[+]?([\d]{0,3})?[\(\.\-\s]?([\d]{3})[\)\.\-\s]*([\d]{3})[\.\-\s]?([\d]{2})$/', $number,$math ) ) {
			$tel = $math[0];
			if (substr($tel,0,1) == 8) $tel[0] = 7;
			return array(
						'number'=> $tel,
						'status'=> 'success'
						);
			} 
		else 
			{
			return array(
						'status'=> 'error'
						);
		}

	}
	
	public function get_geo_number($number) {
		
		$url = "http://phoneverify.org/api.pl?id=phoneinfo&email=".phoneverify_user."&password=".phoneverify_password."&phone=".$number."&format=json";
		$result = $this->curl($url);
		$result = json_decode($result); 
		$data = array(	'region' => $result->region,
						'operator' => $result->operator,
						'telephone' => $number);
		return $data;
	}
	
	public function get_geo_ip() {
		$ip = $_SERVER['REMOTE_ADDR'];
		$url = "http://api.sypexgeo.net/json/".$ip;
		$result = $this->curl($url);
		$result = json_decode($result); 
		$data = array(	'city' => $result->city->name_ru,
						'region' => $result->region->name_ru,
						'ip' => $ip);
		return $data;
	}
	
	public function get_ref(){
		$ref = Session::get("referral");
		
	}
	
	
	private function curl($url) {
		
		$ch = curl_init($url);                                                                     
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS,'');                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      									  
		$result =  curl_exec($ch);
		curl_close($ch);
		
		return $result;
	}
	
	public function preg_match_search($pattern ,$string,$pos = NULL)
	{
		if($string)
		{
			preg_match_all($pattern, $string, $matches);
			
			is_numeric($pos) ? $res = $matches[$pos] : $res  = $matches;
		
			return $res;
		}
	}
	
	public function hostzone($url)
	{
		
		if($url) 
		{
			$parse = parse_url($url); 
			
			if($parse['host'])
			{
				$host = explode('.',$parse['host']);
				
				
				
				if( !empty($host) )
				{
					$subdoman = array_shift($host);
					$zone = end($host);
					$port = $_SERVER["SERVER_PORT"];
					
					count($host) == 3 ? $site = $host[2] : $site = $host[1]; 
					
					$port == 443 ? $port = "https://" : $port = "http://";
					
					return $this->return_res("success", 
											array( "subdomain" => $subdomain,
													"zone" => $zone,
													"site" => $site,
													"port" => $port
												));
					
				}
				else {
					return $this->return_res("error","not set host");
				}
			}
		}
		else 
		{
				return $this->return_res("error","not set url");
		}
	}
		
	private function return_res($status,$response)
		{
			if($status) {
				$data = array(	"status" => $status,
								"response" => $response);
			}
			else {
				$data = array( "status" => "error", "response" => $response);
			}
			
			return $data;
		}	
	public function email($str){
		
			if ( preg_match_all( '/([a-z0-9_\.\-])+\@(([a-z0-9\-])+\.)+([a-zа-я0-9]{2,4})+/i', $str,$math ) ) {
	
				$r = $this->return_res(true,$math[0][0]);
			}	
			else
			{
				$r = $this->return_res(false, "not valid email");
			}
			
			return $r;
	}
}


?>