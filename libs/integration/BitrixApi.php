<?php 

class Bitrix24 {
	
	
	public function __construct() {
		
		@session_start();
		
	}
	
	
	public function crmLeadAdd($data){
		
			
		$url = $this->url.'/rest/crm.lead.add';
		
		$d['data'] = $this->buildQuery($data);
		
		$d['json'] = true;
		
		$res = $this->curlPost($url,$d);
			
		return $res;
		
		
	}
	
	public function crmLiveFeedAdd($data)
	{
		
		$url = $this->url.'/rest/crm.livefeedmessage.add';
		
		$d['data'] = $this->buildQuery($data);
		
		$d['json'] = true;
		
		$res = $this->curlPost($url,$d);
			
		return $res;
	}
	
	
	public function getAccess($session = false){
		
		# step 2 - token
		if( isset($_GET['code']) ) :
			
			$result = $this->getToken();
			
			if( isset($result['response']['access_token']) ) :
				
				$res = array(  	'status' => 'success',
								'response' => array(
													'btoken' => $result['response']['access_token'],
													'bexpires' => $result['response']['expires'],
													'brefresh_token' => $result['response']['refresh_token']
													)
						);
				
				
				if($session === true) $this->setSession( $result['response'] );
				
				return $res;
					
			else :
					$this->errorWarning("Нету токена");
			endif;
			
			
		# step 1 - code
		else :
			
			$this->getCode();
			
		endif;
		
		
		
	}
	
	private function setSession($data)
	{			 
		$_SESSION['btoken'] = $data['access_token'];
		$_SESSION['bexpires'] = $data['expires'];
		$_SESSION['brefresh_token'] = $data['refresh_token'];
	
	}
	
	public function updateToken($params)
	{
		
		$d = array(	'grant_type' => 'refresh_token',
					'client_id' => $params['id'],
					'client_secret' => $params['secret'],
					'refresh_token' => $params['uptoken']);
		
		$p = $this->buildQuery($d);
			
		$url = 'https://oauth.bitrix.info/oauth/token/?'.$p;
			
		$data['json'] = true;

		$res = $this->curlGet($url,$data);
			
		return $res;			
	}
	
	
	private function getCode(){
		
		$param = array(
						'response_type' => 'code',
						'client_id' => $this->client_id
		);
		
		$p = $this->buildQuery($param);
		
		$url = $this->url.'/oauth/authorize/?'.$p;
		
		Header("Location: ".$url);
		
	}
	
	
	private function getToken(){
		
		$data = array();
		
		$param = array(
						'grant_type' => 'authorization_code',
						'client_id' => $this->client_id,
						'client_secret' =>  $this->client_secret,
						'code' => $_GET['code'],
						'scope' => 'crm'
			);
			
		$p = $this->buildQuery($param);
			
		$url = $this->url.'/oauth/token/?'.$p;
			
		$data['headers'] = array('Content-Type: application/json;charset=UTF-8');
		$data['json'] = true;

		$res = $this->curlGet($url,$data);
			
		return $res;
	}
	
	
	private function buildQuery($data)	{
		
		if(is_array($data))
		{
			return http_build_query($data);
		}
    }

	private function errorWarning($text) 
	{
		echo $text."\r\n";
		
	}
	
	private function response($status,$data)
	{
		
		return array(	'status' => $status,
						'response' => $data
					);
		
	}
	
	private function curlGet($url,$params = array()) {
		
		if( isset($params['headers']) ) :
			$headers = array('Content-Type: application/x-www-form-urlencoded;charset=UTF-8');
		endif;
		
		$handle=curl_init();
		
		curl_setopt($handle, CURLOPT_URL, $url);
		curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true); 
		  
		$response=curl_exec($handle);
		
		$code=curl_getinfo($handle, CURLINFO_HTTP_CODE);
		
		$result = array("code"=>$code,"response"=>$response);
		
		if( $params['json'] == true ) :
			$result["response"] = json_decode($result["response"],true);
		endif;
		
		curl_close($handle);
		
		return $result;
	
	}
	
	public function curlPost($url,$params = array()) {
		
		if( !isset($params['headers']) ) :
			$headers = array('Content-Type: application/x-www-form-urlencoded;charset=UTF-8');
		endif;
		
		$handle=curl_init();
		
		curl_setopt($handle, CURLOPT_URL, $url);
		curl_setopt($handle, CURLOPT_HTTPHEADER, $headers); 
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($handle, CURLOPT_POSTFIELDS, $params['data']);                                                                  
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true); 
		
		$response=curl_exec($handle);
		
		$code=curl_getinfo($handle, CURLINFO_HTTP_CODE);
		
		$result = array("code"=>$code,"response"=>$response);
		
		if( $params['json'] == true ) :
			$result["response"] = json_decode($result["response"],true);
		endif;
			
		curl_close($handle);
		
		return $result;
	}
	
	
}

?>