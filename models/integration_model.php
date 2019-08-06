<?php
 class Integration_Model extends Model {
   public function __construct() {
	parent::__construct();
	
   }
   
   /***************************/
	/* Интеграция с Я.Вебмастером */
	/* Дата: 21.02.2019 */
	/* Методы:
		* webMasterSetting - Проверяем наличие токена в БД (private)
		* webMasterIndex - Данные для главной страницы
		* webMasterToken -  Получение или обновление токена
		* webMasterSaveSetting - Сохранение Host id 
	*/
	/***************************/
   
	private function webMasterSetting ($check = false) 
	{
		
		$query = $this->db->GetOne("SELECT content FROM setting WHERE name = 'webmaster'");

		$data = $this->jsonDecode($query);
		
		if ($data['access_token'] and (time() < $data["expires_in"])) 
		{
			
			$return = (object) [ 	'status' => true,
									"response" => $data
			];
		}
		else if($check)
		{
			$return = (object) [ 'status' => false ];
		}
		else 
		{
			$this->wmApi->getToken();
		}
		
		return $return;
	}
	
	public function webMasterIndex()
	{
		
			$setting = $this->webMasterSetting();
			
			if($setting->status)
			{
				$data = (object)[];
				
				$token = $setting->response['access_token'];
				$user_id = $setting->response['user_id'];
				$host_id = $setting->response['host_id'];
				
				/* Настройки */
				
				$data->setting = $setting->response;
				
				/* Информация о сайте */
				$info = $this->wmApi->getHostInfo($user_id,$token,$host_id);
				if($info->status)
				{
					$data->info = $info->response;
				}
				/* Информация о проблемах */
				$trabl = $this->wmApi->getHostStata($user_id,$token,$host_id);
				
				if($trabl->status)
				{
					$data->trabl = $trabl->response;
				}
				/* Внешние ссылки */
				$links = $this->wmApi->getLinksExternal($user_id,$token,$host_id,0,100);
				
				if($links->status)
				{
					foreach ($links->response->links as $k => $l)
					{
						$parse = parse_url($l->source_url);
						$parse2 = parse_url($l->destination_url);
						$links->response->links[$k]->host = $parse["host"];
						
						if (isset($parse2["query"]))
						{
							$links->response->links[$k]->path = $parse2["path"]."?".$parse2["query"];
						}
						else
						{
							$links->response->links[$k]->path = $parse2["path"];
						}
					}
					
					$data->links = $links->response;
				}
				/* Сайтмапы */
				$sitemap = $this->wmApi->getSiteMap($user_id,$token,$host_id);
				
				if($sitemap->status)
				{
					$data->sitemap = $sitemap->response;
				}
				
				/* Список текстов */
				$texts = $this->wmApi->getListTexts($user_id,$token,$host_id,[
														'offset' => 0,
														'limit' => 100
														]);
				if($texts->status)
				{
					$data->texts = $texts->response;
				}
				
				/* Популярные запросы */
				$query = $this->wmApi->getPopularQuery($user_id,$token,$host_id,[	
																							'order_by' => 'TOTAL_CLICKS',
																							'date_from' => date("Y-m-d", strtotime("-1 month", strtotime(date("Y-m-d")))),
																							'date_to' => date('Y-m-d'),
																							"str" => [ 	"name" => 'query_indicator',	
																										"data" => [	'TOTAL_SHOWS',
																													'TOTAL_CLICKS',
																													'AVG_SHOW_POSITION',
																													'AVG_CLICK_POSITION']]									
																						]);
				if($query->status)
				{
					$data->query = $query->response;
				}
				
				/* История индексации */
				$indexing = $this->wmApi->getHistoryIndexs($user_id,$token,$host_id,[	"str" => [ 	"name" => 'indexing_indicator',
																									"data" => [				'SEARCHABLE',
																															'DOWNLOADED',
																															'DOWNLOADED_2XX',
																															'DOWNLOADED_3XX',
																															'DOWNLOADED_4XX',
																															'DOWNLOADED_5XX',
																															'FAILED_TO_DOWNLOAD',
																															'EXCLUDED',
																															'EXCLUDED_DISALLOWED_BY_USER',
																															'EXCLUDED_SITE_ERROR',
																															'EXCLUDED_NOT_SUPPORTED']],
																								'date_from' => date("Y-m-d", strtotime("-1 week", strtotime(date("Y-m-d")))),
																								'date_to' => date('Y-m-d')
																								]);
				if($indexing->status)
				{
					$data->indexing = $indexing->response;
				}
			
				// $this->getDebug($data);
				return $data;
			}
	}
	
	public function webMasterGetSetting($p) 
	{
		
		if (is_numeric($p['code'])) 
		{
			/* Получение токена */
			$res = $this->wmApi->getToken($p['code']);
			
			if ($res->status) 
			{
				$token_data = $res->response;
				/* Получение id юзера */
				$user_id = $this->wmApi->getUserId($token_data->access_token);
				
				if($user_id->status)
				{
					$user_id = $user_id->response;
					
					/* Сохраняем данные */
					
					$check = $this->webMasterSetting(true);
					
					if($check->status)
					{
						$q = "UPDATE setting SET content = ?s WHERE name = 'webmaster'";
					}
					else
					{
						$q = "INSERT INTO setting SET content = ?s, name = 'webmaster'";
					}
					
					$token_data->user_id = $user_id;
					
					$token_data->expires_in = time() + $token_data->expires_in;
					
					$content = json_encode($token_data);
					
					$this->db->query($q,$content);
					
					/* Список хостов */
					
					$host_list = $this->wmApi->getSiteList($user_id,$token_data->access_token);
					
					
					if($host_list->status)
					{
						$token_data->hosts = $host_list->response->hosts;
					}
					else
					{
						$token_data->hosts = null;
					}
				
					$data = (object)[ "status" => true, "setting" => $token_data, "tabs" => "setting"];
				}
			}
			else
			{
				$data = (object)[ "status" => false, "response" => null];
			}
		}
		else
		{
			$setting = $this->webMasterSetting();
			
			if($setting->status)
			{
				$data = (object)[ "status" => true, "response" => $setting->response];
			}
		}
		
		return $data;
	}
	
	
	public function webMasterSaveSetting($p)
	{
		
		if($p["host_id"])
		{
			$data = $this->webMasterSetting();
			
			if($data->status)
			{
				$data->response["host_id"] = $p["host_id"];
					
				$up = $this->db->query("UPDATE setting SET content = ?s WHERE name = 'webmaster'",json_encode($data->response));
				
				if(up)
				{
					$return = array("status" => "success", "response" =>"Данные обновлены");
				}
				else
				{
					$return = array("status" => "warning", "response" =>"Ошибка обновления");
				}
			}
			else
			{
				$return = array("status" => "warning", "response" =>"Нет данных для обновления");
			}
		}
		else
		{
			$return = array("status" => "warning", "response" =>"Не выбран сайт");
		}
		
		return $return;
	}
	
	

	public function update_idtextList_webMaster() { 
	                
			$Textdata = $this->setting_textList_webMaster();
			
			$Productdata = $this->db->GetAll("SELECT id,description FROM product");
			
			foreach($Productdata as $val) {
				
				$desc = strip_tags($val['description']);
				
				foreach($Textdata['texts']['original_texts'] as $val2) {
					
					if(mb_strimwidth($val2['content_snippet'],0,150) == mb_strimwidth($desc,0,150))
					{
						$up = $this->db->query("UPDATE product SET idwebmaster = ?s WHERE id = ?i",$val2['id'],$val['id']);
						
						if($up) {
							$data[]  = array( 'product' => $val['id'],
											  'id_text' => $val2['id'],
											  'description' => mb_strimwidth($desc,0,150)
											  );
						}
						
					}
					
				}
			}
			
			if(count($data) > 0) {
				echo json_encode(	
								array('status' => 'success',
									  'description'=> $data
									),
									JSON_HEX_QUOT | JSON_HEX_TAG
							);
				
			}
			else {
				echo json_encode(	
								array('status' => 'error',
									),
									JSON_HEX_QUOT | JSON_HEX_TAG
							);
				
			}

	}
	
	
	public function setting_textRemove_webMaster($list) {
		if(is_array($list) and count($list) > 0) {
			
			$query = $this->db->GetOne("SELECT content FROM setting WHERE name = 'webmaster'");
			
			$data = json_decode($query,JSON_HEX_QUOT | JSON_HEX_TAG);
			
			$token = $data['token'];
			$user_id = $data['userid'];
			$host_id = $data['hostid'];
			
			
			foreach($list as $val) {
				
				$res = $this->wmApi->removeText($user_id,$token,$host_id,$val);
				$outData[] = array( 'id' => $val,
									'code' => $res['description']['code']);
			}
		
			echo json_encode(	
								array('status' => 'success',
									  'description'=> $outData
									),
									JSON_HEX_QUOT | JSON_HEX_TAG
							);
		}
		else {
			return array( 	'status' => 'error',
								'description' => 'no id in array');
		}
	}
	
	public function setting_textAdd_webMaster($text) {
	
		if(strlen($text) > 500) {
			
			$query = $this->db->GetOne("SELECT content FROM setting WHERE name = 'webmaster'");
			$data = json_decode($query,JSON_HEX_QUOT | JSON_HEX_TAG);
			
			$token = $data['token'];
			$user_id = $data['userid'];
			$host_id = $data['hostid'];
			
			
			$res = $this->wmApi->addNewText($user_id,$token,$host_id,$text);
			echo json_encode(	
								array('status' => 'success',
									  'description'=> $res
									),
									JSON_HEX_QUOT | JSON_HEX_TAG
							);
		}
		else {
			return array( 	'status' => 'error',
								'description' => 'length = '.strlen($text));
		}
	}
	
	public function text_importpage($list) {
		
		if(is_array($list) and count($list) > 0) {
			
			foreach($list as $val) {
				$text = $this->db->GetOne("SELECT description FROM product WHERE id = ?s",$val);
				$text = strip_tags($text);
				
				if(!empty($text)) {
					$res = $this->setting_textAdd_webMaster($text);
				}
				
				
			}
			
			echo json_encode(	
								array('status' => 'success',
									  'description'=> $res
									),
									JSON_HEX_QUOT | JSON_HEX_TAG
							);
			
		}
		else {
			echo json_encode(	
								array('status' => 'error',
									  'description'=> "no array"
									),
									JSON_HEX_QUOT | JSON_HEX_TAG
							);
		}
	}
	
	
	/* 15.06.2018 */
	
	public function bitrix24_index() {
		
		$query = $this->db->GetOne("SELECT content FROM setting WHERE name = 'bitrix24'");
			
		$data = json_decode($query,JSON_HEX_QUOT | JSON_HEX_TAG);
			
		if( isset($data) ){
			return $data;
		}
		else {
			return false;
		}
		
	}
	
	public function bitrix24_setting($data){
		
		if(is_array($data))
		{
			$check = $this->db->GetOne("SELECT content FROM setting WHERE name = 'bitrix24'");
		
			if(!$check)
			{
				$q = "INSERT INTO ?n 
													SET 
														content = ?s, 
														name = 'bitrix24'";
										
			}
			else {
				$q  = "UPDATE ?n SET content = ?s WHERE name = 'bitrix24'";
			}
			$data = json_encode($data,JSON_HEX_QUOT | JSON_HEX_TAG);
			
			$query = $this->db->query($q,'setting',$data);
			
			
		}
		
	}
	
	public function bitrix24_token(){
		
		$query = $this->db->GetOne("SELECT content FROM setting WHERE name = 'bitrix24'");
			
		$data = json_decode($query,JSON_HEX_QUOT | JSON_HEX_TAG);
		
		if(empty($data['token']))
		{	
			$this->bt24->url = $data['url'];
			$this->bt24->client_id = $data['id'];
			$this->bt24->client_secret = $data['secret'];
			
			$res = $this->bt24->getAccess(true);
		}

		if($res['status'] == 'success')
		{
			$q  = "UPDATE ?n SET content = ?s WHERE name = 'bitrix24'";
			
			$data['token'] = $res['response']['btoken']; 
			$data['expires'] = $res['response']['bexpires'];
			$data['uptoken'] = $res['response']['brefresh_token'];
								
			$d = json_encode($data,JSON_HEX_QUOT | JSON_HEX_TAG);
			
			$this->db->query($q,'setting',$d);
		}
		
	}
	
	/* Функция генерации случайного имени */
	/* Добавлена: 09.10.2018 */
	
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
	
	/* Функция парсинга JSON  *
	/* $s - разделитель */
	/* Добавлена: 11.10.2018 */
	
	private function jsonDecode($d,$s = false)
	{
		
		if($s)
		{
			$separator = explode($s,$d);
			
			foreach($separator as $key => $v) 
			{
				$data[] = json_decode($v,true);
			}
		}
		else
		{
			$data = json_decode($d,true);
		}
		
		return $data;
	}
	
	/* Функция для отладки  *
	/* Добавлена: 11.10.2018 */
	
	private function getDebug($r)
	{
		if ($r)
		{
			echo "<pre>";
			print_r
			(
				$r
			);
			echo "<pre>";
			
			exit;
		}
		
	}
	
	/* Функция записи логов  *
	/* Добавлена: 23.10.2018 */
	
	private function writeLogs($text = false)
	{
		if($text)
		{
			$this->db->query("INSERT INTO users_logs
										SET 
											date = NOW(),
											method = ?s,
											text = ?s,
											user_id = ?i",
											'web',
											 $text,
											 Session::get('sid1'));
		}
		
	}
 } 
?>