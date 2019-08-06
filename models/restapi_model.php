<?php

class RestApi_Model extends Model {
	
	public function __construct() {
		parent::__construct();	
		
	}
		
	public function check_auth($key = false)
	{
		try
		{
			if($key)
			{
				$q1 = "SELECT api FROM users WHERE api_key = ?s";
				
				$res = $this->db->GetOne($q1,$key);
				
				if($res == 'on')
				{
					throw new Exception("success");
				}
			}
			else
			{
				throw new Exception("error");
			}
		}		
		catch(	Exception $e)
		{
		 return $e->getMessage();
		}
	}	
		
	public function keywords_metrika(){
		
		$count = $this->db->GetOne("SELECT count(o.id) FROM orders o WHERE o.check_s = 0 and o.ref LIKE 'https://yandex.ru%'");
		
		if($count > 0) {
			
			$d = $this->db->GetAll("SELECT
										o.client_id,
										o.ip_user,
										DATE_FORMAT(o.time, '%Y%m%d') AS dt
									FROM
										orders o
									WHERE
										o.check_s = 0
									AND o.ref NOT LIKE '%yabs.yandex.ru%'
									ORDER BY
										o.time DESC");
												
			if(METRIKA_API_TOKEN and METRIKA_API_ID) {
				
				foreach($d as $val) {
				
					$this->metApi->token = METRIKA_API_TOKEN;
					$this->metApi->id = METRIKA_API_ID;
					$this->metApi->param = array( 	'metrics' => 'ym:s:pageviews',
													'dimensions' => 'ym:s:dateTime,ym:s:searchPhrase,ym:s:startURL',
													'date1'=> $val['dt'],
													'date2' => $val['dt'],
													'limit' => 1,
													'offset' => 1,
													'filters' => "ym:s:paramsLevel1=='".$val['client_id']."' AND ym:s:paramsLevel2=='".$val['ip_user']."'",
													'sort' => 'ym:s:dateTime'
										);
										
					$res = $this->metApi->getKeywords();
					
					//echo "<pre>"; print_r($res);exit;
					
					if(!empty($res['response']['data'][0]['dimensions'][1]['name']) and !empty($res['response']['data'][0]['dimensions'][2]['name'])) {
						
						$this->db->query("UPDATE orders SET check_s = 1,
															keywords = ?s, 
															startUrl = ?s
															WHERE
															client_id = ?i",
															$res['response']['data'][0]['dimensions'][1]['name'],
															$res['response']['data'][0]['dimensions'][2]['name'],
															$val['client_id']);
						
					}
					else {
						$this->db->query("UPDATE orders SET check_s = 1
																WHERE
															client_id = ?i",
															$val['client_id']);
					}
				
				}
			}
			
		}
		 				
	}
	
	
	# Поддомены
	
	public function get_list_sudomain() 
	{	
		try 
		{
			$query = "SELECT
										gs.name_ru,
										gs.name_en,
										gz.name as zone,
										phone,
										gs.email,
										gs.adr,
										gs.public
									FROM
										geo_subdomain gs
									LEFT JOIN geo_zone gz ON gz.id = gs.id_zone";
									
			$res = $this->db->GetAll($query);
			
			if($res)
			{
				$this->return_json( 200 , $res );
			}
			else {
				throw new Exception("no results");
			}
			
		}
		catch(	Exception $e) 
		{
		 
		 $mess = $e->getMessage();
		 $this->return_json( 404 , $mess );
		 
		}
	}
	
	public function get_list_zone() 
	{	
		try 
		{
			$query = "SELECT
							gz.*,
							(
								SELECT
									COUNT(gs.id)
								FROM
									geo_subdomain gs
								WHERE
									gs.id_zone = gz.id
							) AS subdomain
						FROM
							geo_zone gz";
									
			$res = $this->db->GetAll($query);
			
			if($res)
			{
				$this->return_json( 200 , $res );
			}
			else {
				throw new Exception("no results");
			}
			
		}
		catch(	Exception $e) 
		{
		 
		 $mess = $e->getMessage();
		 $this->return_json( 404 , $mess );
		 
		}
	}
	
	public function get_list_page($id,$subdomain = null) {
		
		$query = "SELECT * FROM product LIMIT 500";
		
		try 
		{
			$query = "SELECT * FROM product LIMIT 500";
									
			$res = $this->db->GetAll($query);
			
			if($res)
			{
				$this->return_json( 200 , $res );
			}
			else {
				throw new Exception("no results");
			}
			
		}
		catch(	Exception $e) 
		{
		 
		 $mess = $e->getMessage();
		 $this->return_json( 404 , $mess );
		 
		}
		
		
	}
	
	# Навигация
	
	# Результат
	
	private function return_json ( $status , $response) {
		
		if($status) {
				
			if(empty($response)) $response = null;
				
			$result = json_encode(	
									array('status' => $status,
										  'response' => $response
										),
										JSON_HEX_QUOT | JSON_HEX_TAG
								);	
		
			if( $show === true )
			{
				echo $result;	
			}
			else
			{
				return $result;
			}	
		}
		
	}
	
	
	#backup
	
	public function create_backup($param = false){
		
		$return = array();
		
		try
		{
			if(is_array($param))
			{
				
				if( isset( $param['refresh'] ) == true ) {
					$this->bp->remove();
					$r1 = "DELETE FROM ?n";
					$this->db->query($r1,'backup');
				}
				
				if( isset( $param['base'] ) == true )
				{	
					$this->bp->source = DB_NAME;
					$res1 = $this->bp->copy_database($this->db);
					
					if($res1['status'] == 'success')
					{
						$q1 = "INSERT INTO 
										backup 
										SET 
											user_id = (SELECT u.id FROM users u WHERE u.api_key = ?s),
											files_path = ?s,
											date = ?s,
											download_link = ?s";
											
						$linkf = $this->generate_code();	
						
						$this->db->query($q1,
											$param['apikey'],
											$res1['path'].$res1['file'],
											date('Y-m-d H:i:s', $res1['ts']),
											$linkf);
											
						$return[] = $linkf;		
					}
					else
					{
						throw new Exception("Не удалось сделать бэкап базы");	
					}
					
				}
				
				if( isset( $param['files'] ) == true )
				{	
					$this->bp->source = dirname(__DIR__);
					
					if(isset($param['exception']))
					{
						$exception = substr($param['exception'],1,strlen($param['exception'])-2);
						$exception = explode(',',$exception);
						$d = array();
						
						foreach($exception as $key => $val)
						{
							$check = pathinfo($val);
							
							if( isset($check['extension']) )
							{
								$d[] = "/".$val;
							}
							else
							{
								$d[] = $_SERVER['DOCUMENT_ROOT']."/".$val;
							}
							
						}
						$this->bp->drops = $d;
					}
					
					$res2 = $this->bp->copy_files();
					
					if($res2['status'] == 'success')
					{
						$q2 = "INSERT INTO 
										backup 
										SET 
											user_id = (SELECT u.id FROM users u WHERE u.api_key = ?s),
											files_path = ?s,
											date = ?s,
											download_link = ?s";
											
						$linkb = $this->generate_code();
						
						if( $this->db->query($q2,
											$param['apikey'],
											$res2['path'].$res2['file'],
											date('Y-m-d H:i:s', $res2['ts']),
											$linkb)
						)
						{
							$return[] = $linkb;
						}
						else 
						{
							throw new Exception("Не удалось записать в базу");	
						}
					}
					else
					{
						throw new Exception("Не удалось сделать бэкап файлов");	
					}
					
				}
				
				
				return $return;
			}
			else 
			{
				throw new Exception("Не заданы параметры");
			}
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	}
	
	public function download_backup($id)
	{
		try
		{
			$q = "SELECT files_path FROM backup WHERE download_link = ?s";
			
			$res = $this->db->GetOne($q,$id);
			
			if( isset($res) )
			{
					$ret = array( 	"status" => "success",
									"link" => $res);
					
					return $ret;
			}
			else {
				throw new Exception("error");
			}
		}
		catch( Exception $e)
		{
			return $e->getMessage();
		}
	}
	
	
	private function generate_code($length=20) 
	{
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
			$code = "";
			$clen = strlen($chars) - 1;
			while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];
			}
			return $code;
	}
	
	public function orders($data) {
		
		
		$phoneData = $this->telephoneCheck($data['contact']);
		
		if($phoneData != 'error') 
		{						
			
			if(strlen($data['message']) > 5) {
				$message = strip_tags($data['message']);
			}
			else {
				$message = NULL;
			}
			
			$ipData = $this->parser->get_geo_ip();
			
			$query = $this->db->query("INSERT INTO orders SET 
													 name = ?s,
													 description = ?s,
													 phone = ?s,
													 phone_operator = ?s,
													 phone_region = ?s,
													 url = ?s,
													 form = ?s,
													 ref = ?s,
													 ip_user = ?s,
													 ip_city = ?s,
													 ip_region = ?s,
													 agent_user = ?s,
													 time = NOW(),
													 client_id = ?s",
													 $data['name'],
													 $message,
													 $phoneData['telephone'],
													 $phoneData['operator'],
													 $phoneData['region'],
													 $data['url'],
													 $data['form'],
													 Session::get("referral"),
													 $ipData['ip'],
													 $ipData['city'],
													 $ipData['region'],
													 $_SERVER['HTTP_USER_AGENT'],
													 Session::get("client_id"));
			
			
			
			if($query != NULL) {
				
				echo json_encode(	
								array('status' => '200'),
									JSON_HEX_QUOT | JSON_HEX_TAG
							);
				
			}
			else {
				
				echo json_encode(	
								array('status' => '404'),
									JSON_HEX_QUOT | JSON_HEX_TAG
							);
			}
			
		
		}
		else {
				echo json_encode(	
								array('status' => '404'),
									JSON_HEX_QUOT | JSON_HEX_TAG
							);
		}
		
		
  }
  
  public function gosend(){
	  
	  $ord = $this->db->GetAll("SELECT * FROM orders WHERE send = 0");
	  
	  if(isset($ord)) {
		 
			$query = $this->db->GetOne("SELECT content as mail FROM setting WHERE name = 'mail'");
			
			$mail_setting = json_decode($query,JSON_HEX_QUOT | JSON_HEX_TAG);			
			
			if($mail_setting['public'] == 1) {
						$mail = array(
										'login' => $mail_setting['login'],
										'password' => $mail_setting['password'],
										'send' => $mail_setting['send']);
			}
			
			foreach($ord as $data) {
			  
				/* send to telegram */
				
				if(TM_SEND) $this->sendTelegram($data);
				
				/* send to bitrix */
				
				if(B24_SEND) $this->sendBitrix($data);
				
				/* send to email */
				
				if($mail_setting['public'] == 1) $this->sendMail($data,$mail);
				
				$this->db->query("UPDATE orders SET send = 1 WHERE id = ?i",$data['id']);	
		  }
	  }
  }
  
  private function telephoneCheck($phone) {
		
		$phone = $this->parser->phone($phone);
		
		if($phone['status'] != 'error') {
			
			$phoneverify = $this->db->GetOne("SELECT content as phoneverify FROM setting WHERE name = 'phoneverify'");
			
			$data = json_decode($phoneverify,JSON_HEX_QUOT | JSON_HEX_TAG);
		
			if($data['public'] == 1) {
			
				$geo_number = $this->parser->get_geo_number($phone['number'],$data);
				
				return $geo_number;
			}
			else {
				return array('telephone' => $phone['number']);
				exit;
			}
		}	
		else {
			return "error";
			exit;
		}
  }
  
  
  private function sendMail($data,$mail) 
  {
				
		$domain = $this->parser->domain($data['ref']);
		
		$content = 	'<table style="border-collapse: collapse;" cellpadding="7">'.
					'<tr><td style="border-bottom: 1px solid #ccc;"><b>Время заявки:</b></td><td style="border-bottom: 1px solid #ccc;">'.$data['time']."</td></tr>".
					'<tr><td style="border-bottom: 1px solid #ccc;"><b>Имя:</b></td><td style="border-bottom: 1px solid #ccc;">'.$data['name']."</td></tr>".
					'<tr><td style="border-bottom: 1px solid #ccc;"><b>Данные об телефоне:</b></td><td style="border-bottom: 1px solid #ccc;"></td></tr>'.
					'<tr><td>Номер:</td><td><a href="tel:+'.$data['phone'].'">'.$data['phone']."</a></td></tr>".
					'<tr><td>Оператор:</td><td>'.$data['phone_operator']."</td></tr>".
					'<tr><td>Регион:</td><td>'.$data['phone_region']."</td></tr>".
					'<tr><td style="border-bottom: 1px solid #ccc;"><b>Данные об ip:</b></td><td style="border-bottom: 1px solid #ccc;"></td></tr>'.
					'<tr><td>Город:</td><td>'.$data['ip_city']."</td></tr>".
					'<tr><td>Регион:</td><td>'.$data['ip_region']."</td></tr>".
					'<tr><td style="border-bottom: 1px solid #ccc;"><b>Данные об источнике:</b></td><td style="border-bottom: 1px solid #ccc;">'."</td></tr>".
					'<tr><td>Заказ со старницы:&nbsp;</td><td>'.$data['url']."</td></tr>".
					'<tr><td>Форма:</td><td>'.$data['form']."</td></tr>".
					'<tr><td>Источник:</td><td>'.$domain."</td></tr>".
					'<tr><td><b><a href="'.$_SERVER['HTTP_HOST'].'/requests/vieworder/'.$data['client_id'].'/?key='.$data['phone'].'">Подробнее</a></b></td></tr>'.							
					'</table>';
		
		$this->email->smtp_username = $mail['login'];
		$this->email->smtp_password = $mail['password'];
		
		$smtp_from = ["Заявка с сайта", $mail['login']];
							
		
		$result = $this->email->send($mail['send'],'Новая заявка: '.$_SERVER['SERVER_NAME'],$content, $smtp_from); // отправляем письмо
									
				
  }
  
  
  
  private function sendTelegram($data){
	
	$ref = $data['ref']; 
	
	$domain = $this->parser->domain($ref);
	
	$data_ref = $this->parser->parseUrl($ref);
	$data_url = $this->parser->parseUrl($data['url']);
	
	if($domain == $_SERVER['HTTP_HOST']) $domain = $data_ref['query']['pm_source']; 
	
	$s_query = $this->parser->searchQuery($ref);
	
	if($s_query == NULL) {
		if($data_ref['query']['utm_term']) {
			$s_query = $data_ref['query']['utm_term'];
		}
		else {
			$s_query = "-";
		}
	}
	
	$host = $_SERVER['HTTP_HOST'];
	
	$this->telegramApi->token = TM_API_TOKEN;
	
	$desc = '';
	
	if(!empty($data['description'])) $desc = '<b>Описание:</b> '.$data['description']."\r\n"."\r\n";
	
	$this->telegramApi->param = array(	'chat_id' => TM_API_GROUP,
										'parse_mode' => 'HTML',
										'text' => 			"\r\n".
															"<b>Сайт:</b> ".$host."\r\n".
															'<b>Время заявки:</b> '.$data['time']."\r\n".
															'<b>Имя:</b> '.$data['name']."\r\n".
															'<b>Номер телефона:</b> '.$data['phone']."\r\n"."\r\n".
															$desc.
															'<b>Заказ со старницы:</b> '.$data_url['url']['host'].$data_url['url']['path']."\r\n".
															'<b>Форма:</b> '.$data['form']."\r\n".
															'<b>Источник:</b> <i>'.$domain.'</i>'."\r\n".
															'<b>Фраза:</b> <i>'.$s_query.'</i>'."\r\n"."\r\n".
															
															'<a href="'.$_SERVER['HTTP_HOST'].'/requests/vieworder/'.$data["client_id"].'/?key='.$data['phone'].'">Подробнее</a>'."\r\n"
															
							);
								
	$this->telegramApi->sendMessage();
  }
  
  
  public function sendBitrix($data){
	
	$query = $this->db->GetOne("SELECT content FROM setting WHERE name = 'bitrix24'");
			
	$access = json_decode($query,JSON_HEX_QUOT | JSON_HEX_TAG);
		
	if(empty($data['description'])) 
	{
		$s_query = $this->parser->searchQuery($ref);
	
		if($s_query == NULL) {
			if($data_ref['query']['utm_term']) {
				$data['description'] = $data_ref['query']['utm_term'];
			}
		}
		else
		{
			$data['description'] = $s_query;
		}
		
	}
	
	if( $access['expires'] <= time() )
	{	
		$res = $this->bt24->updateToken($access);
		
		if(isset( $res['response']['access_token'] ) )
		{
			$access['token'] = $res['response']['access_token'];
			$access['uptoken'] = $res['response']['refresh_token'];
			$access['expires'] = $res['response']['expires'];
			
			$data_1 = array(	"id" => $access['id'],
								"secret" => $access['secret'],
								"url" => $access['url'],
								"token" => $res['response']['access_token'],
								"expires" => $res['response']['expires'],
								"uptoken" => $res['response']['refresh_token']
							);
			
			$djson = json_encode($data_1,JSON_HEX_QUOT | JSON_HEX_TAG);
			
			$this->db->query("UPDATE setting SET
													content = ?s
												WHERE 
													name = ?s",
												$djson,
												'bitrix24');
			
		}
	}
	
	if( isset( $access['token'] ) and isset($data['phone']) )
	{
			$data_lead = array(
				'auth' => $access['token'],
				'fields' => array( 	'TITLE' => 'Заявка с сайта '.$_SERVER['HTTP_HOST'],
									'NAME' => $data['name'],
									'ADDRESS_PROVINCE' => $data['phone_region'],
									'STATUS_ID' => 'NEW',
									'OPENED' => 'Y',
									'COMMENTS' => $data['description'],
									'ASSIGNED_BY_ID' => B24_ASSIGNED,
									'PHONE' => array(	
													array (
															'VALUE' => $data['phone'],
															'VALUE_TYPE' => 'WORK' ,
													))),
				'params' => array( '[REGISTER_SONET_EVENT]' => 'Y')
					);
			
			$e1 = $this->parser->email($data['description']);	
			
			if($e1['status'])
			{
				$data_lead['fields']['EMAIL'] = array (	
														array(
																'VALUE' => $e1['response'],
																'VALUE_TYPE' => 'WORK' 
												));
			} 	
			$this->bt24->url = $access['url'];
			
			$id_new_leed = $this->bt24->crmLeadAdd($data_lead);
			
			if( is_numeric($id_new_leed['response']['result']) and !empty($data['description']) )
			{
				$id_new_leed = $id_new_leed['response']['result'];
				
				$data_message_feed = array(
											'auth' => $access['token'],
											'fields' => array( 	
																'POST_TITLE' => 'Комментарий клиента',
																'MESSAGE' => $data['description'],
																'SPERM' => array(
																					"CRMLEAD" => array ("CRMLEAD".$id_new_leed),
																				)
															),
											'ENTITYTYPEID' => '1',
											'ENTITYID' => $id_new_leed
								
											);
				
				$this->bt24->crmLiveFeedAdd($data_message_feed);
			}
			
	}		
	
  }
  
  
  public function viewdata($id,$key) {
	  
	   if(is_numeric($id) AND !empty($key)) {
		
		$data = $this->db->GetAll("SELECT * from orders WHERE client_id = ?i and phone = ?s",$id,$key);  
		
		if($data) {
				$i = 0;
				foreach($data as $val)										
				{
					$decode = urldecode($val['ref']);
					$url = parse_url($decode);

					$data[$i]['time'] = date('d M Y H:i',strtotime($val['time']));
					$data[$i]['host'] = $url['host']; 
					
					if( empty($url['query']) ){

						$decode2 = urldecode($val['url']);
						$url2 = parse_url($decode2);
						parse_str($url2['query'],$params);
						$data[$i]['query'] = $params['keyword'];
						
					}
					else if( $url['query'] and empty($val['keywords']) ) {
						parse_str($url['query'],$params);
						if(!empty($params['q'])) $data[$i]['query'] = $params['q'];
					}
					else {
						$data[$i]['query'] = $val['keywords'];
					}
					
					$i++;
				}
		}
		else {
			$data = false;
		}
		
		return $data;
	  }
	  
  }
  
  private function debug($data = null){
	  echo "<pre>";
	  print_r($data);
	  exit;
  }
	
	
	
} 
 
?>