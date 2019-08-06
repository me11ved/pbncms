<?php 

class MetrikaYandexApi {
	
	
	private $url = 'https://api-metrika.yandex.ru/stat/v1/data.json';
	public $token;
	public $id;
	private $getparam;
	public $param;
	
	public function getKeywords($p) {
			
			$result = $this->curlInit($p);
			
			return $result;
	}
	
	private function CreateGetParam()	{
		
		$return = NULL;
		
		$data = $this->param;
		
		if($data) {
			
			$queryString = array();
        
			foreach ($data as $param => $value) {
				if (is_string($value) || is_int($value) || is_float($value)) {
					$queryString[] = urlencode($param) . '=' . urlencode($value);
				} elseif (is_array($value)) {
					foreach ($value as $valueItem) {
						$queryString[] = urlencode($param) . '=' . urlencode($valueItem);
					}
				} else {
					$this->errorWarning("Bad type of key {$param}. Value must be string or array");
					continue;
				}
			}

			$return = implode('&', $queryString);
			
		}
        return $return;
    }

	private function errorWarning($text) {
		echo $text."\r\n";
		
	}
	
	public function curlInit($p) {
		
		$headers = [ "json" => ['Authorization: OAuth '.$p['token'],
								'Content-Type: application/json;charset=UTF-8'],
					 "token" => ['Content-type: application/x-www-form-urlencoded'],
					];
					
		if( !$p["headers"]) $p["headers"] = 'json';
		if (!$p["type"]) $p["type"] = 'post';
		
		if ($p["data"] and $p["type"] == 'get') {
			
			$get = http_build_query($p["data"]);
			$url = $this->url."?".$get;
		}
		else
		{
			$url = $this->url;
		}
		
		echo $url;exit;
		
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
	
}


?>