<?php 

class YandexWebMasterApi {
	
	private $v = 'v4';
	private $old_v = 'v3.2';
	private $u = 'https://api.webmaster.yandex.net/';
	private $client_id = "c51b1393df54438caefa22539c8bbd62";
	private $client_secret = "6e7f97bce5b74a85af7322670dce412b";
	
	public function getToken ($code = false) 
	{
		
		if ($code === false) 
		{
			Header("Location: https://oauth.yandex.ru/authorize?response_type=code&client_id=".$this->client_id."&state=http://".$_SERVER["HTTP_HOST"]."/integration/webMasterSetting/");
		}
		else if (is_numeric($code)) 
		{
			
			$data = http_build_query([
										'grant_type'=> 'authorization_code',
										'client_id'=>$this->client_id,
										'client_secret'=>$this->client_secret,
										"code" => $code 
									]);
			
			$params = [		"url" =>  "https://oauth.yandex.ru/token",
							"headers" => "token",
							"data" => $data];
			
			
			$result = $this->curlInit($params);
			
			if($result->details->code == 200) 
			{
				
				$result = (object) [ 	'status' => true,
										'response' => $result->response
							];
			}
			else 
			{
				$result = (object) [ 	'status' => false,
										'response' => $result->response->error.": ".$result->response->error_description
							];
			}
		}
			
		
		return $result;
	}
	
	
	//Получение id пользователя
	public function getUserId($token)
	{
		
		if (!empty($token)) 
		{
				
			$result = $this->curlInit([	"url" => $this->u.$this->v.'/user/',
										"type" => "get",
										"token" => $token]);
				
			if ($result->details->code == 200)
			{
				$return = (object) [	'status' => true,
									'response' => $result->response->user_id
							 ];	
			}
			else
			{
				$return =(object) [	'status' => false,
									'response' => $result->response->error_code.": ".$result->response->error_message
							 ];			
			}
		}
		else 
		{
			$return = (object) [	'status' => false,
									'response' => 'no token'
							 ];	
		}
		
		return $return;
		
	}
	//Получение списка сайтов пользователя
	//https://tech.yandex.ru/webmaster/doc/dg/reference/hosts-docpage/
	public function getSiteList($user_id,$token) 
	{
		
		if (!empty($user_id) and !empty($token)) 
		{
				
				$result = $this->curlInit([	"url" => $this->u.$this->v.'/user/'.$user_id.'/hosts/',
											"type" => "get",
											"token" => $token]);
					
				if ($result->details->code == 200)
				{
					$return = (object) [	'status' => true,
											'response' => $result->response
								 ];	
				}
				else
				{
					$return =(object) [	'status' => false,
										'response' => $result->response->error_code.": ".$result->response->error_message
								 ];			
				}
		}
		else {
			$return = (object)[		'status' => false,
									'response' => 'no token or user_id'
							 ];	
		}
		
		return $return;
	}
	//Получение информации о сайте
	//https://tech.yandex.ru/webmaster/doc/dg/reference/hosts-id-docpage/
	
	public function getHostInfo($user_id,$token,$host_id){
		
		if(!empty($user_id) and !empty($token) and !empty($host_id)) {
				
			$result = $this->curlInit([	"url" => $this->u.$this->v.'/user/'.$user_id.'/hosts/'.$host_id."/",
										"type" => "get",
										"token" => $token]);
					
			if ($result->details->code == 200)
			{
				$return = (object) [	'status' => true,
										'response' => $result->response
							 ];	
			}
			else
			{
				$return =(object) [	'status' => false,
									'response' => $result->response->error_code.": ".$result->response->error_message
							 ];			
			}
		}
		else {
			$return = (object)[		'status' => false,
									'response' => 'no token or user_id or host_id'
							 ];	
		}
		
		return $return;	
	}
	
	//Получение статистики сайта
	//https://tech.yandex.ru/webmaster/doc/dg/reference/host-id-summary-docpage/
	
	public function getHostStata($user_id,$token,$host_id){
		
		if(!empty($user_id) and !empty($token) and !empty($host_id)) {
				
			$result = $this->curlInit([	"url" => $this->u.$this->v.'/user/'.$user_id.'/hosts/'.$host_id."/summary/",
										"type" => "get",
										"token" => $token]);
					
			if ($result->details->code == 200)
			{
				$return = (object) [	'status' => true,
										'response' => $result->response
							 ];	
			}
			else
			{
				$return =(object) [	'status' => false,
									'response' => $result->response->error_code.": ".$result->response->error_message
							 ];			
			}			
		}
		else {
			$return = (object)[		'status' => false,
									'response' => 'no token or user_id or host_id'
							 ];	
		}
		
		return $return;	
	}
	
	//Получение информации о популярных запросах
	//https://tech.yandex.ru/webmaster/doc/dg/reference/host-search-queries-popular-docpage/
	
	public function getPopularQuery($user_id,$token,$host_id,$options){
		
		if(!empty($user_id) and !empty($token) and !empty($host_id)) {
				
			$result = $this->curlInit([	"url" => $this->u.$this->v.'/user/'.$user_id.'/hosts/'.$host_id."/search-queries/popular/",
										"type" => "get",
										"data" => $options,
										"token" => $token]);
					
			if ($result->details->code == 200)
			{
				$return = (object) [	'status' => true,
										'response' => $result->response
							 ];	
			}
			else
			{
				$return =(object) [	'status' => false,
									'response' => $result->response->error_code.": ".$result->response->error_message
							 ];			
			}			
		}
		else {
			$return = (object)[		'status' => false,
									'response' => 'no token or user_id or host_id'
							 ];	
		}
		
		return $return;	
	}
	
	//Получение истории индексирования сайта
	//https://tech.yandex.ru/webmaster/doc/dg/reference/hosts-indexed-docpage/
	
	public function getHistoryIndexs($user_id,$token,$host_id,$options)
	{
		
		if(!empty($user_id) and !empty($token) and !empty($host_id)) {
				
			$result = $this->curlInit([	"url" => $this->u.$this->old_v.'/user/'.$user_id.'/hosts/'.$host_id."/indexing-history/",
										"type" => "get",
										"data" => $options,
										"token" => $token]);
			
			
			if ($result->details->code == 200)
			{
				$return = (object) [	'status' => true,
										'response' => $result->response
							 ];	
			}
			else
			{
				$return =(object) [	'status' => false,
									'response' => $result->response->error_code.": ".$result->response->error_message
							 ];			
			}			
		}
		else {
			$return = (object)[		'status' => false,
									'response' => 'no token or user_id or host_id'
							 ];	
		}
		
		return $return;	
	}
	
	//Получение информации о внешних ссылках на сайт
	//https://tech.yandex.ru/webmaster/doc/dg/reference/host-links-external-samples-docpage/
	
	public function getLinksExternal($user_id,$token,$host_id,$offset,$limit){
		
		if(!empty($user_id) and !empty($token) and !empty($host_id)) {
				
				$result = $this->curlInit([	"url" => $this->u.$this->v.'/user/'.$user_id.'/hosts/'.$host_id."/links/external/samples/",
											"type" => "get",
											"data" => ["offset" => $offset, "limit" => $limit],
											"token" => $token]);
					
						
				if ($result->details->code == 200)
					{
						$return = (object) [	'status' => true,
												'response' => $result->response
									 ];	
					}
					else
					{
						$return =(object) [	'status' => false,
											'response' => $result->response->error_code.": ".$result->response->error_message
									 ];			
					}			
				}
				else {
					$return = (object)[		'status' => false,
											'response' => 'no token or user_id or host_id'
									 ];	
				}
		
		return $return;	
	}
	
	//Получение списка файлов sitemap
	//https://tech.yandex.ru/webmaster/doc/dg/reference/host-sitemaps-get-docpage/
	public function getSiteMap($user_id,$token,$host_id){
		
		if(!empty($user_id) and !empty($token) and !empty($host_id)) {
				
			$result = $this->curlInit([	"url" => $this->u.$this->v.'/user/'.$user_id.'/hosts/'.$host_id."/sitemaps/",
										"type" => "get",
										"data" => ["limit" => 100],
										"token" => $token]);
			
			if ($result->details->code == 200)
				{
					$return = (object) [	'status' => true,
											'response' => $result->response
								 ];	
				}
				else
				{
					$return =(object) [	'status' => false,
										'response' => $result->response->error_code.": ".$result->response->error_message
								 ];			
				}			
			}
			else {
				$return = (object)[		'status' => false,
										'response' => 'no token or user_id or host_id'
								 ];	
			}
		
		return $return;	
	}
	
	//Получение списка оригинальных текстов
	//https://tech.yandex.ru/webmaster/doc/dg/reference/host-original-texts-get-docpage/
	
	public function getListTexts($user_id,$token,$host_id,$options){
		
		if(!empty($user_id) and !empty($token) and !empty($host_id)) {
				
			$result = $this->curlInit([	"url" => $this->u.$this->v.'/user/'.$user_id.'/hosts/'.$host_id."/original-texts/",
										"type" => "get",
										"data" => $options,
										"token" => $token]);
				
			if ($result->details->code == 200)
			{
				$return = (object) [	'status' => true,
										'response' => $result->response
							 ];	
			}
			else
			{
				$return =(object) [	'status' => false,
									'response' => $result->response->error_code.": ".$result->response->error_message
							 ];			
			}			
		}
		else {
			$return = (object)[		'status' => false,
									'response' => 'no token or user_id or host_id'
							 ];	
		}
		
		return $return;	
	}
	
	
	
	
	//Пример подтверждения прав
	//https://tech.yandex.ru/webmaster/doc/dg/concepts/verification-docpage/
	
	public function getVerificationCode($user_id,$token,$host_id){
		
		if(!empty($user_id) and !empty($token) and !empty($host_id)) {
				
				$result = $this->curlInit($this->u.$this->v.'/user/'.$user_id.'/hosts/'.$host_id."/verification/",$token);
					
				return array(	'status' => true,
								'response' => $result
							 );			
		}
		else {
			return array(	'status' => false,
								'response' => 'no token or user_id or host_id'
							 );	
		}
	}
	
	//Добавление сайта
	//https://tech.yandex.ru/webmaster/doc/dg/reference/hosts-add-site-docpage/
	public function addNewHost($user_id,$token,$host) {
			
		if(!empty($user_id) and !empty($token)) {	
			
			$peremen = array('host_url'=> $host);
			
			$result = $this->getPostCurl("https://api.webmaster.yandex.net/v3/user/".$user_id."/hosts/",$peremen,$token);
			
			return array(	'status' => true,
								'response' => $result
							 );			
		}
		else {
			return array(	'status' => false,
								'response' => 'no token or user_id or host_id'
							 );	
		}
			
	}
	
	//Добавление уникального текста
	//https://tech.yandex.ru/webmaster/doc/dg/reference/host-original-texts-post-docpage/
	public function addNewText($user_id,$token,$host,$content) {
			
		if(!empty($user_id) and !empty($token)) {	
			
			$peremen = array('content'=> $content);
			
			$result = $this->getPostCurl("https://api.webmaster.yandex.net/v3/user/".$user_id."/hosts/".$host."/original-texts/",$peremen,$token);
			
			return array(	'status' => true,
								'response' => $result
							 );			
		}
		else {
			return array(	'status' => false,
								'response' => 'no token or user_id or host_id'
							 );	
		}
			
	}
	
	
	//Удаление сайта
	//https://tech.yandex.ru/webmaster/doc/dg/reference/hosts-delete-docpage/
	public function removeHost($user_id,$token,$host) {
			
		if(!empty($user_id) and !empty($token)) {	
			
			$result = $this->getDeleteCurl("https://api.webmaster.yandex.net/v3/user/".$user_id."/hosts/".$host."/",$token);
			
			return array(	'status' => true,
								'response' => $result
							 );			
		}
		else {
			return array(	'status' => false,
								'response' => 'no token or user_id or host_id'
							 );	
		}
			
	}

	//Удаление sitemap
	//https://tech.yandex.ru/webmaster/doc/dg/reference/host-user-added-sitemaps-sitemap-id-delete-docpage/
	public function removeSiteMap($user_id,$token,$id) {
			
		if(!empty($user_id) and !empty($token)) {	
			
			$result = $this->getDeleteCurl("https://api.webmaster.yandex.net/v3/user/".$user_id."/user-added-sitemaps/".$id."/",$token);
			
			return array(	'status' => true,
								'response' => $result
							 );			
		}
		else {
			return array(	'status' => false,
								'response' => 'no token or user_id or host_id'
							 );	
		}
			
	}
	
	//Удаление текста
	//https://tech.yandex.ru/webmaster/doc/dg/reference/host-original-texts-delete-docpage/
	public function removeText($user_id,$token,$host,$text) {
			
		if(!empty($user_id) and !empty($token)) {	
			
			$result = $this->getDeleteCurl("https://api.webmaster.yandex.net/v3/user/".$user_id."/hosts/".$host."/original-texts/".$text."/",$token);
			
			return array(	'status' => true,
								'response' => $result
							 );			
		}
		else {
			return array(	'status' => false,
								'response' => 'no token or user_id or host_id'
							 );	
		}
			
	}
	
	public function curlInit($p) {
		
		$headers = [ "json" => ['Authorization: OAuth '.$p['token'],
								'Content-Type: application/json;charset=UTF-8'],
					 "token" => ['Content-type: application/x-www-form-urlencoded'],
					];
					
		if( !$p["headers"]) $p["headers"] = 'json';
		if (!$p["type"]) $p["type"] = 'post';
		
		if ($p["data"] and $p["type"] == 'get') {
			
			$dop = '';
			
			if ($p["data"]["str"])
			{
				$dop = $this->indicators($p["data"]["str"]);
				
				unset($p["data"]["str"]);
			}
			
			$get = http_build_query($p["data"]);
			$url = $p["url"]."?".$get.$dop;
		}
		else
		{
			$url = $p["url"];
		}
		
		// echo $url;exit;
		
		$handle = curl_init();
		
		curl_setopt($handle, CURLOPT_URL, $url);
		curl_setopt($handle, CURLOPT_HTTPHEADER, $headers[$p["headers"]]);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_CUSTOMREQUEST, strtoupper($p["type"]));
		
		if ($p["data"] and $p["type"] == 'post')
		{
			curl_setopt($handle, CURLOPT_POSTFIELDS, $p['data']);
		}
		
		$response = curl_exec($handle);
		
		$responseHeadersSize = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
		$responseHeaders = substr($response, 0, $responseHeadersSize);
		$responseBody = json_decode($response);
		$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		
		
		if (!$response)  
			{	
				$result = (object) [ 
							'status' => false, 
							"response" => 'Ошибка cURL: '.curl_errno($handle).' - '.curl_error($handle)
				];
			}
			else
			{
				$result = (object) [	
										'status' => true, 
										"details" => (object) [ 
														"code" => $httpCode,
														"responseBody" => $response,
														"body" => $p["data"],
										],
										"response" => $responseBody
				];
			}
			curl_close($handle);
			
			return $result;
	}
	
	private function indicators($p)
	{
		if (is_array($p["data"]))
		{
			$str = '';
			foreach($p["data"] as $key => $val)
			{
				$str .= "&" . $p["name"] . "=" . $val;
			}
				
			return $str;
		}
	}
}

?>