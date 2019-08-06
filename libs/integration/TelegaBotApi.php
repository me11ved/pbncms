<?php
class TelegaBot {
	
	public $param;
	public $message;
	public $token;
	public $proxy = false;
	private $url = 'https://api.telegram.org/bot';
	private $getparam;
	private $method;
	
	public function sendMessage() {
		
		$this->method = 'sendMessage';
		
		$this->getparam = $this->CreateGetParam();
		
		return $this->CurlGet();
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
	
	private function CurlGet() {
		
		$headers = array('Content-Type: application/json;charset=UTF-8');
		
		$handle=curl_init();
		
		curl_setopt($handle, CURLOPT_URL, $this->url.$this->token."/".$this->method."?".$this->getparam);
		curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		
		if(isset($this->proxy)) 
		{
			curl_setopt($handle, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
			curl_setopt($handle, CURLOPT_PROXY,"socks5://".$this->proxy);
		}
		
		$response=curl_exec($handle);
		
		$code=curl_getinfo($handle, CURLINFO_HTTP_CODE);
		
		$result = array("code"=>$code,"response"=>$response);
		
		//$result["response"] = json_decode($result["response"],true);
		
		curl_close($handle);
		
		return $result;
	
	}
	
}


?>