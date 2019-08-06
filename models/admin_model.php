<?php
 class Admin_Model extends Model {
	 
	 
   public function __construct() 
   {
		parent::__construct();
		$this->sitemapPath = $_SERVER["DOCUMENT_ROOT"]."/public/sitemaps/";
   
   }
   
	
	/***************************/
	/* Управление пользователями */
	/* Дата: 10.01.2019 */
	/* Методы:
		* usersIndex - Главная страница
		* usersAdd - Добавить пользователя
		* usersDel - Удалить пользователя
		* usersSwitch - Вкл/Отключить
		* usersEdit - Редакировать
		* usersSaveEdit - Сохранить редакирование
		
	*/
	/***************************/
	
	public function usersIndex ()
	{
		$users = $this->db->GetAll("SELECT * FROM users");
		
		$logs = $this->db->GetAll("SELECT 
											ul.*,
											u.name
										FROM 
											users_logs ul 
										LEFT JOIN users u ON u.id = ul.user_id 
										ORDER BY ul.id DESC 
										LIMIT ?i",100);
		
		return [ 
					"users" => $users,
					"logs" => $logs];
	}
	
	
	/* Управление пользователями */
	/***************************/
	
	public function usersAdd($data)
	{
		if (strlen($data["login"]) > 3)
		{
			$login = $this->parser->createUrl($data["login"]);
			
			$test = $this->db->GetOne("SELECT id FROM users WHERE login = ?s", md5($login));
			
			if (!$test)
			{
				if (empty($data["password"])) $data["password"] = $this->generate_code(8);
				
				if ($data["api"] )
				{
					$data["api"] = "yes";
					$data["api_key"] = md5 ($this->generate_code(15));
				}
				else
				{
					$data["api"] = "no";
				}
				
				$response = [ 	"login" => $data["login"],
								'password' => $data["password"],
								'api_key' => $data["api_key"]
							];
				
				$newp = $data["password"];
				$newl = $data["login"];
				
				$data["login"] = md5 ($data["login"]);
				$data["password"] = md5 ($data["password"]);
				
				
				$add = $this->db->query("INSERT INTO users SET ?u",$data);
				
				$id = $this->db->insertId();
				
				if($id)
				{
					$res = $this->db->GetRow("SELECT * FROM  users WHERE id = ?i",$id);
					
					$res["newp"] = $newp;
					$res["newl"] = $newl;
					
					$this->writeLogs("Создал пользователя <b>" . $newl . "</b> c доступом <b>". $data["role"] . "</b>" );
					
					$r = array( "status" => "success", "response" => $res);
				}
				else
				{
					$r = array( "status" => "warning", "response" => "Не удалось добавить");
				}
			}
			else
			{
				$r = array( "status" => "warning", "response" => "Такой пользователь уже существует");
			}
		}
		else 
		{
			$r = array( "status" => "warning", "response" => "Не задан логин (мин. 4 символа)");
		}
		
		return $r;
	}
	
	public function usersDel ($ids)
	{
		if (count($ids) > 0)
		{
			if(array_search(Session::get('sid1'),$ids) === false)
			{
				$users = $this->db->GetAll("SELECT name FROM users WHERE id IN(?a)",$ids);
				
				$del = $this->db->query("DELETE FROM users WHERE id IN (?a)",$ids);
			
				if($del)
				{
					foreach ($users as $a)
					{
						$this->writeLogs("Удалил пользователя <b>" . $a["name"] . "</b>");
					}
					
					$r = array( "status" => "success", "response" => "Пользователи удалены");
				}
				else
				{
					$r = array( "status" => "warning", "response" => "Не удалось удалить администратора");
				}
			}
			else
			{
				$r = array( "status" => "warning", "response" => "Нельзя удалить пользователя с правами администратора");
			}
		}
		else
		{
			$r = array( "status" => "warning", "response" => "Не заданы параметры");
		}
		
		return $r;
	}
	
	public function usersSwitch($p)
	{
		if (count($p->l) > 0)
		{
			$st = array("on" => 1, "off" => 0);
				
				$q = $this->db->query("UPDATE users SET active = ?i WHERE id IN(?a)",$st[$p->s],$p->l);
				
				if($q) {
					
					$users = $this->db->GetAll("SELECT name FROM users WHERE id IN(?a)",$p->l);
					
					if($st[$p->s]) {
						$stname = "Включил";
					}
					else {
						$stname = "Отключил";
					}
					
					foreach ($users as $a)
					{
						$this->writeLogs($stname . " пользователя <b>" . $a["name"] . "</b>");
					}
					
					$return = array("status" => "success", "response" =>"Статусы обновлены на <b>" . $p->s . "</b>");
				}
				else {
					$return = array("status" => "error", "response" => "Ошибка выполнения операции");
				}
		}
		else
		{
			$return = array("status" => "warning", "response" =>"Не заданы параметры");
		}
		
		return $return;	
		
	}
	
	public function usersEdit($id)
	{
		if ($id)
		{
			$data = $this->db->GetRow("SELECT * FROM users WHERE id = ?i",$id);
			
			if ($data)  
			{
				$r = array( "status" => "success","response" => $data);
			}
			else
			{
				$r = array( "status" => "warning", "response" => "Нет данных базе");
			}
		}
		else 
		{
			$r = array( "status" => "warning", "response" => "Не заданы параметры");
		}
		
		return $r;
		
	}
	
	public function usersSaveEdit($data) {
		
		if(!empty($data['id']))
		{
			$id = $data['id'];
			unset($data['id']);
			
			$user = $this->db->GetRow("SELECT * FROM users WHERE id = ?i",$id);
			
			$data['login'] = $this->parser->createUrl($data["login"]);
			
			if (empty($data["login"]))
			{
				unset($data["login"]);
			}
			else
			{
				$data["login"] = md5 ($data["login"]);
				
			}
				
			if (empty($data["password"])) 
			{
				unset($data["password"]);
			}
			else
			{
				$data["password"] = md5 ($data["password"]);
			}
			
			if ($data["api"] and $user["api"] == "no")
			{
				
				$data["api"] = "yes";
				$data["api_key"] = md5 ($this->generate_code(15));
			}
			else
			{
				$data["api"] = "no";
				$data["api_key"] = null;
			}
			
			$query = $this->db->query("	UPDATE 
											users 
										SET 
											?u
											WHERE id = ?i",
											$data,
											$id);
			
			if($query) 
			{
				$user = $this->db->GetRow("SELECT * FROM users WHERE id = ?i",$id);
				
				$this->writeLogs("Изменные данные пользователя <b>" . $user["name"] . "</b>");
				
				$r	= array('status' => 'success',
							'response'=> $user);
				
			}
			else 
			{
				$r	= array('status' => 'error',
									  'response' => "Не удалось обновить");
			}		
		}
		else
		{
			$r = array( "status" => "warning", "response" => "Не заданы параметры");
		}
		
		return $r;
	}
	
	/* Управление пользователями */
	/***************************/
	
	
	public function pageListAll ()
	{
		$category = $this->db->GetAll("SELECT title,
											  id
											FROM
												category 
											WHERE
												public = ?i",1);
												
		$res["response"]["static"] = $this->db->GetAll("SELECT title,
											 id
												FROM
													product 
												WHERE
													public = ?i
												AND 
													id_category = ?i",
													1,0);
		
		if($category)
		{
			foreach ($category as $key => $value)
			{
				$p = $this->db->GetAll("SELECT title,
											 id
												FROM
													product 
												WHERE
													public = ?i
												AND 
													id_category = ?i",
												1,
												$value["id"]);
													
				if ($p) 
				{
					$value["products"] = $p;
					$res["response"]["category"][] = $value;
				}
				  
			}
		}
		
		if( count($category) >0 OR count($res["static"]) > 0)
		{
			$res["status"] = "success";
		}
		else
		{
			$res = [	"status" => "warning",
						"response" => "Нет данных для отображения"
					];
		}
		
		return $res;
		
	}
	
	
	
	public function pageIndex() { 
		
		$data = $this->db->GetAll("SELECT
											p.*,
											LOWER( p.title ) AS title,
											CHAR_LENGTH( p.description ) AS count,
											LOWER( c.title ) AS category,
											CONCAT(
											DATE_FORMAT( p.create_date, '%d ' ),
											ELT(
											MONTH ( p.create_date ),
											'Янв.',
											'Фев.',
											'Мар.',
											'Апр.',
											'Мая',
											'Июня',
											'Июля',
											'Авг.',
											'Сен.',
											'Окт.',
											'Ноя.',
											'Дек.' 
											),
											DATE_FORMAT( p.create_date, ' %Y ' ),
											DATE_FORMAT( p.create_date, ' %H:%i ' ) 
											) AS create_date,
										IF
											(
											c.href IS NULL,
											CONCAT( '/', p.href ),
											CONCAT( '/', c.href, '/', p.href ) 
											) AS url 
										FROM
											product p
											LEFT JOIN category c ON c.id = p.id_category 
										ORDER BY
											title,
											public ASC");
												
			return $data;
	}
	
	public function pageAdd($data,$multi = false){
		
		$href = $data['href'];
		
		if(strlen(trim($data['title'])) >= 3)
		{
				/* Формируем урл если пользователь не задал */
				if(empty($data['href']))
				{
					$data['href'] = $this->parser->createUrl($data['title']);
				}
				
				$params = (object) [ 
											"href" => $data["href"],
											"id_category" => $data["id_category"]];
				
				/* Проверяем /задана подкатегория/ существует ли страница/ */
				$test = $this->pageCheck($params);
				
				if ($test)
				{
					
					if( !empty($data["id_category"]))
					{
						if ( $data["id_category"][0] == "p")
						{
							$sub_category = $this->db->GetRow("SELECT 	position,
																		href,
																		id_category
																	FROM 
																		product 
																	WHERE 
																		id = ?i",
																	substr($data["id_category"],1));
							
							$end = array_pop(explode("/",$data["href"]));
							$data["href"] = $sub_category["href"]."/".$end;
							$data["position"] = $sub_category["position"] + 1;
							$data["id_category"] = $sub_category["id_category"];
						}
						elseif (is_numeric($data["id_category"]))
						{
							$data["position"] = 1;
						}
					}
					else
					{
						unset($data["id_category"]);
					}
					
					$q = $this->db->query("INSERT INTO ?n SET ?u, create_date = NOW() ON DUPLICATE KEY UPDATE ?u",'product',$data,$data);
					
					if ($q)
					{
						$id = $this->db->insertId();
						
						if(is_numeric($id)) 
						{
							$res = $this->db->GetRow("SELECT	 p.*,
																CONCAT(
																		DATE_FORMAT( p.create_date, '%d ' ),
																		ELT(
																		MONTH ( p.create_date ),
																		'Янв.',
																		'Фев.',
																		'Мар.',
																		'Апр.',
																		'Мая',
																		'Июня',
																		'Июля',
																		'Авг.',
																		'Сен.',
																		'Окт.',
																		'Ноя.',
																		'Дек.' 
																		),
																		DATE_FORMAT( p.create_date, ' %Y ' ),
																		DATE_FORMAT( p.create_date, ' %H:%i ' ) 
																		) AS create_date,
																if(
																	p.id_category != 0,
																	CONCAT('/',c.href,'/',p.href),
																	CONCAT('/',p.href)) as href,
																c.title as cat
																FROM 
																	product p
																LEFT JOIN 
																	category c 
																ON 
																	c.id = p.id_category
																WHERE 
																	p.id = ?i",$id);
							
							$r = array( "status" => "success","response" => $res);
						}
					}
				}
				else
				{
					$r = array( "status" => "warning", "response" => "Страница существует или пытаетесь задать подкатегорию к статической странице");
				}
		}
		else 
		{
			$r = array( "status" => "warning", "response" => "Не задано название (мин. 3 символа)");
		}
		
		return $r;
	}
	
	
	public function pageSwitch($p)
	{
		if (count($p->l) > 0)
		{
			$st = array("on" => 1, "off" => 0);
			
			$q = $this->db->query("UPDATE product SET public = ?i WHERE id IN(?a)",$st[$p->s],$p->l);
			
			if($q) $return = array("status" => "success", "response" =>"Статусы обновлены у ".count($p->l));
		}
		else
		{
			$return = array("status" => "warning", "response" =>"Не заданы параметры");
		}
		
		return $return;	
		
	}
	
	public function pageDel($p)
	{
		if (count($p) > 0)
		{
			$rem = $this->db->query("DELETE FROM product WHERE id IN(?a)",$p);
			
			if($rem) $return = array("status" => "success", "response" =>"Удалено ".count($p));
		}
		else
		{
			$return = array("status" => "warning", "response" =>"Не заданы параметры");
		}
		
		return $return;	
	}
	
	public function pageEdit($id)
	{
		if ($id)
		{
			$data = $this->db->GetRow("SELECT * FROM product WHERE id = ?i",$id);
			
			if ($data)  
			{
				$r = array( "status" => "success","response" => $data);
			}
			else
			{
				$r = array( "status" => "warning", "response" => "Нет данных базе данных");
			}
		}
		else 
		{
			$r = array( "status" => "warning", "response" => "Не заданы параметры");
		}
		
		return $r;
		
	}
	
	public function pageSaveEdit($data) {
		
		if(!empty($data['id']))
		{
			$id = $data['id'];
			
			unset($data['id']);
			
			$dataOld = $this->db->GetRow("SELECT * from product WHERE id = ?i",$id);
			
			if (isset($dataOld))
			{
				if (!empty($data["href"])) 
				{
					
					$params = (object) [ 
											"href" => $data["href"],
											"category" => $data["id_category"]];
					
					// Проверка на существование урла					

					if ($data["href"] != $dataOld["href"] OR $data["id_category"] != $dataOld["id_category"])
					{
						$test = $this->pageCheck($params);
					}
					else
					{
						$test = true;
					}
					
					if ($test)
					{
						if( !empty($data["id_category"]))
						{
							if ( $data["id_category"][0] == "p")
							{
								$sub_category = $this->db->GetRow("SELECT 	position,
																			href,
																			id_category
																		FROM 
																			product 
																		WHERE 
																			id = ?i",
																		substr($data["id_category"],1));
								
								$end = array_pop(explode("/",$data["href"]));
								$data["href"] = $sub_category["href"]."/".$end;
								$data["position"] = $sub_category["position"] + 1;
								$data["id_category"] = $sub_category["id_category"];
							}
							elseif (is_numeric($data["id_category"]))
							{
								$data["position"] = 1;
							}
						}
						else
						{
							$data["id_category"] = 0;
							$data["position"] = NULL;
						}
						

						
						$query = $this->db->query("	UPDATE 
															product 
														SET 
															update_date = NOW(),
															id_user = ?i,
															?u
															WHERE id = ?i",
															Session::get('sid1'),
															$data,
															$id);
					
						if($query) 
						{
							$r	= array('status' => 'success',
										'response'=> "Обновлен продукт Id: ".$id);
							
						}
						else 
						{
							$r	= array('status' => 'error',
										'response' => "Не удалось обновить");
						}
					}
					else
					{
							
						$r	= array(	'status' => 'warning', 'response'=> "Данная страница существует/");
					}
				}
				else
				{
					$r	= array('status' => 'warning','response'=> "Не заполнено поле Url");
				}
			}
			else
			{
				$r = array( "status" => "warning", "response" => "Данной страницы не существует");
			}
		}
		else
		{
			$r = array( "status" => "warning", "response" => "Не заданы параметры");
		}
		
		return $r;
	}
	
	private function pageCheck ($p)
	{
		$p->href = $this->parser->createUrl($p->href);
		
		// Проверяем категорию
		if(!empty($p->id_category))
		{
			// Проверим является ли категория подкатегорией
			if ( $p->id_category[0] == "p")
			{
				$subCat = $this->db->GetRow("SELECT 	position,
															href,
															id_category
														FROM 
															product 
														WHERE 
															id = ?i
														AND
															position != 0
														AND
															position IS NOT NULL",
														substr($p->id_category,1));
														
				// Если может быть подкатегорией
				if ($subCat)
				{
					//Проверим существует ли такая страница 2 вложенности
					$name_exists = $this->db->GetOne("SELECT id 
														FROM 
															product 
														WHERE 
															href = ?s
														AND
															id_category = ?i",
														$subCat["href"]."/".$p->href,
														$subCat["id_category"]);
			
					if ($name_exists)
					{
						return false;
					}
					else
					{
						return true;
					}
				}
				// Если нет говорим пользователю что статическая страница не может быть категорией
				else
				{
					return false;
				}
			}
			else if (is_numeric($p->id_category))
			{
				
				$name_exists = $this->db->GetOne("SELECT	id 
														FROM
															?n 
														WHERE
															href = ?s 
														AND 
															id_category = ?i",
														"product",
														$p->href,
														$p->id_category); 
														
				if ($name_exists)
				{
					return false;
				}
				else
				{
					return true;
				}
			}
		}
		else
		{
			$name_exists = $this->db->GetOne("SELECT id 
														FROM 
															product 
														WHERE 
															href = ?s
														AND
															(id_category = 0
														OR
															id_category IS NULL)",
															$p->href);
			
			if ($name_exists)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
	}
	
	public function lastAddPage() { 
		
		$data = $this->db->GetAll("SELECT 	if(DATE(p.create_date) = CURDATE(),CONCAT('<b>Сегодня</b> в',DATE_FORMAT(p.create_date,' %H:%i')),CONCAT(	DATE_FORMAT(p.create_date,'%d '),
													ELT( MONTH(p.create_date), 'Янв.','Фев.','Мар.','Апр.','Мая','Июня','Июля','Авг.','Сен.','Окт.','Ноя.','Дек.'),
													DATE_FORMAT(p.create_date,' %Y '),
													DATE_FORMAT(p.create_date,' %H:%i '))) as create_date,
											LOWER(p.title) as title,
											p.id,
											LOWER(c.title) as category,
											p.public,
											CONCAT('/',c.href,'/',p.href) as href,
											if(p.id_user,u.name,'') as user
												FROM product p
												LEFT JOIN category c ON c.id = p.id_category
												LEFT JOIN users u ON p.id_user = u.id
												ORDER BY p.id DESC 
												LIMIT 5");
												
			return $data;
	}
	
	public function lastUpPage() { 
		
		$data = $this->db->GetAll("SELECT if(DATE(p.update_date) = CURDATE(),CONCAT('<b>Сегодня</b> в',DATE_FORMAT(p.update_date,' %H:%i')),CONCAT(	DATE_FORMAT(p.update_date,'%d '),
													ELT( MONTH(p.update_date), 'Янв.','Фев.','Мар.','Апр.','Мая','Июня','Июля','Авг.','Сен.','Окт.','Ноя.','Дек.'),
													DATE_FORMAT(p.update_date,' %Y '),
													DATE_FORMAT(p.update_date,' %H:%i '))) as update_date,
											LOWER(p.title) as title,
											p.id,
											LOWER(c.title) as category,
											CONCAT('/',c.href,'/',p.href) as href,
											p.public,
											if(p.id_user,u.name,'') as user
												FROM product p
												LEFT JOIN category c ON c.id = p.id_category
												LEFT JOIN users u ON p.id_user = u.id
												ORDER BY p.update_date DESC 
												LIMIT 10");
												
			return $data;
	}
	
	public function lastorders() { 
		
		$data = $this->db->GetAll("SELECT
											*
										FROM
											orders
										WHERE
										 YEAR(`time`) = YEAR(NOW()) AND WEEK(`time`, 1) = WEEK(NOW(), 1)
										ORDER BY
											id DESC");
												
		if($data) {
				$i = 0;
				foreach($data as $val)										
				{
					$data[$i]['time'] = date('d M Y H:i',strtotime($val['time']));
					$data[$i]['host'] = $this->parser->domain($val['ref']); 
					
					if(empty($val['keywords'])) {
						
						$s_query = $this->parser->searchQuery($val['ref']);
	
						if($s_query == NULL) {
							if($data_ref['query']['utm_term']) {
								$s_query = $data_ref['query']['utm_term'];
							}
						}
						$data[$i]['query'] = $s_query; 
					}
					else
					{
						$data[$i]['query'] = $val['keywords']; 
					}
						/*parse_str($url['query'],$params);
						if(!empty($params['q'])) $data[$i]['query'] = $params['q'];
						if(!empty($params['text']) and empty($params['q'])) $data[$i]['query'] = $params['text'];
						if(!empty($params['utm_term']) and empty($params['q']) and empty($params['text'])) $data[$i]['query'] = $params['utm_term'];
						*/
					
					
					$i++;
				}
		}
		
		return $data;
	}
	
	public function pageStatic() {
		
		$data = array();
		
		$data['all'] = $this->db->GetOne("select count(*) + (select count(*) from category) from product");
		$data['off'] = $this->db->GetOne("select count(*) + (select count(*) from category where public = 0) from product where public = 0");
		$data['on'] = $this->db->GetOne("select count(*) + (select count(*) from category where public = 1) from product where public = 1");					
		
		return $data;
	}
	
	public function ordersstatic() {
		
		$data = $this->db->GetAll("SELECT count(*) as countOrders,
										  (select count(*) from orders WHERE ref LIKE '%yandex.%') as CountYandex,
										  (select count(*) from orders WHERE ref LIKE '%google.%') as CountGoogle,
										  (select time from orders ORDER BY id DESC LIMIT 1) as Last
										  FROM orders");
												
			return $data;
	}
	
	
	/***************************/
	/* Управление категориями */
	/* Дата: 25.01.2019 */
	/* Методы:
		* categoryIndex - Главная страница
		* catAdd - Добавить категорию
		* catDel - Удалить категорию
		* catSwitch - Вкл/Отключить
		* catEdit - Редакировать
		* catSaveEdit - Сохранить редакирование
		
	*/
	/***************************/
	
	public function category(){
		
		$data = $this->db->GetAll("SELECT * FROM category");
		
		return $data;
	}
	
	
	public function catAdd($data) 
	{
		if($data['title'] and strlen(trim($data['title'])) >= 3)
		{
				if(empty($data['href'])) $data['href'] = $this->parser->createUrl($data['title']);
				
				
				$name_exists = $this->db->GetOne("SELECT title FROM ?n WHERE href = ?s",'category',$data['href']);
				
				if (!$name_exists)
				{
					$q = $this->db->query("INSERT INTO ?n SET ?u, create_date = NOW() ON DUPLICATE KEY UPDATE ?u",'category',$data,$data);
					
					if ($q)
					{
						$id = $this->db->insertId();
						
						if(is_numeric($id)) 
						{
							$res = $this->db->GetRow("SELECT * FROM category WHERE id = ?i",$id);
							
							$r = array( "status" => "success","response" => $res);
						}
					}
				}
				else
				{
					$r = array( "status" => "warning", "response" => "Такая категория уже существует");
				}
		}
		else 
		{
			$r = array( "status" => "warning", "response" => "Не задано название (мин. 3 символа)");
		}
		
		return $r;
	}
	
	public function catDel($p)
	{
		if (count($p) > 0)
		{
			$rem = $this->db->query("DELETE FROM ?n WHERE id IN(?a)",'category',$p);
			
			if($rem) $return = array("status" => "success", "response" =>"Удалено ".count($p));
		}
		else
		{
			$return = array("status" => "warning", "response" =>"Не заданы параметры");
		}
		
		return $return;	
	}
	
	public function catEdit($id)
	{
		if ($id)
		{
			$data = $this->db->GetRow("SELECT * FROM category WHERE id = ?i",$id);
			
			if ($data)  
			{
				$r = array( "status" => "success","response" => $data);
			}
			else
			{
				$r = array( "status" => "warning", "response" => "Нет данных базе данных");
			}
		}
		else 
		{
			$r = array( "status" => "warning", "response" => "Не заданы параметры");
		}
		
		return $r;
		
	}
	
	public function catSaveEdit($data) 
	{
		
		if (!empty($data['id']))
		{
			if (!empty($data["href"]))
			{
				$data["href"] = $this->parser->createUrl($data['href']);
				
				$test = $this->db->GetOne("SELECT id FROM category WHERE href = ?s",$data["href"]);
			
				if ($test === $data["id"] OR $test == NULL)
				{
					$id = $data['id'];
					unset($data['id']);
					
					$query = $this->db->query("	UPDATE 
													category 
												SET 
													update_date = NOW(),
													id_user = ?i,
													?u
													WHERE id = ?i",
													Session::get('sid1'),
													$data,
													$id);
					
					if($query) 
					{
						$row = $this->db->GetRow("SELECT * FROM category WHERE id = ?i",$id);
						
						$r	= array('status' => 'success',
									'response'=> $row);
						
					}
					else 
					{
						$r	= array('status' => 'error',
									'response' => "Не удалось обновить");
					}	
				}
				else
				{
					$r = array( "status" => "warning", "response" => "Страница с таким адресом существует");
				}
			}
			else
			{
				$r = array( "status" => "warning", "response" => "Не задан Url");
			}
		}
		else
		{
			$r = array( "status" => "warning", "response" => "Не заданы параметры");
		}
		
		return $r;
	}
	
	public function catSwitch($p)
	{
		if (count($p->l) > 0)
		{
			$st = array("on" => 1, "off" => 0);
			
			$q = $this->db->query("UPDATE ?n SET public = ?i WHERE id IN(?a)",'category',$st[$p->s],$p->l);
			
			if($q) $return = array("status" => "success", "response" =>"Статусы обновлены у ".count($p->l));
		}
		else
		{
			$return = array("status" => "warning", "response" =>"Не заданы параметры");
		}
		
		return $return;	
		
	}
	
	public function categoryList() {
		
		$data = $this->db->GetAll("SELECT * FROM category WHERE type = 'category' or type = 'blog' ");
		
		return $data;
	}
	
	public function categoryListAll() {
		
		$data = $this->db->GetAll("SELECT * FROM category");
		
		return $data;
	}
	
	
	/* Управление категориями */
	/***************************/
	
	/* Настройка сайта и заявок */
	/* Добавлена: 31.01.2019 */
	/*  */
	
	public function settingSite() 
	{
		
		$names = [ "extredirect", "cache", "mail", "telegram" ];
		
		$subDomain  = $this->db->GetAll("SELECT gs.name_ru,
												gs.name_en,
												gz.name as zone
											FROM 
												geo_subdomain gs
											LEFT JOIN geo_zone gz ON gz.id = gs.id_zone
											WHERE 
												gs.public = ?i",1);
		
		$zone  = $this->db->GetAll("SELECT name
											FROM 
												geo_zone
											WHERE 
												public = ?i",1);
		
		$data = $this->db->GetALL("SELECT 
											name,
											content 
										FROM 
											setting 
										WHERE 
											name IN (?a)",
										$names);
		
		
		
		
		foreach ($data as $v)
		{
			$res[$v['name']] = $this->jsonDecode($v["content"]);
		}
		
		if($subDomain) 
		{
			$domain =  explode(".",$_SERVER["HTTP_HOST"]);
			
			foreach ($subDomain as $key => $val){
				
				$subDomain[$key]["fullname"] = $val["name_en"].".".$domain[0].".".$val["zone"];
			}
			
			$res["sub"] = $subDomain;
		}
		if($zone) {
			
			$domain =  explode(".",$_SERVER["HTTP_HOST"]);
			
			foreach ($zone as $key => $val){
				
				$zone[$key]["fullname"] = $domain[0].".".$val["name"];
			}
			
			$res["zone"] = $zone;
		}
		return $res;
	}
	
	public function settingSiteSave($p)
	{
		$q0 = "SELECT id FROM setting WHERE `name` = ?s";
		$q1 = "UPDATE setting SET content = ?s WHERE `name` = ?s";
		$q2 = "INSERT INTO setting SET content = ?s, `name` = ?s";
		
		if ($this->db->GetOne($q0,"extredirect"))
		{
			$this->db->query($q1,$p["ext"],'extredirect');
		}
		else
		{
			$this->db->query($q2,$p["ext"],'extredirect');
		
		}
		
		$cache  = json_encode([ 	"status" => $p["cache"], 
									"time" => $p["cacheTime"] ]);
		
		if ($this->db->GetOne($q0,"cache"))
		{
			$this->db->query($q1,$cache,'cache');
		}
		else
		{
			$this->db->query($q2,$cache,'cache');
		}
		
		if ($p["telegaToken"] and $p["telegaSendId"])
		{
			$telegram  = json_encode([ 	"token" => $p["telegaToken"], 
										"send" => $p["telegaSendId"],
										"proxy" => $p["telegaProxy"],
										"status" => $p["telegaSend"]]);
										
			if ($this->db->GetOne($q0,"telegram"))
			{
				$this->db->query($q1,$telegram,'telegram');
			}
			else
			{
				$this->db->query($q2,$telegram,'telegram');
			}
		}
		
		
		$mail  = [ 	"email" => 
					[				"login" => $p["emailLogin"], 
									"send" => $p["emailSend"],
									"status" => $p["emailStatus"]],
					"phoneverify" => 
					[
									 "login" => $p["PhoneChecklogin"],
									 "status" => $p["PhoneChecknum"]
					]];
		
		$data_mail = $this->db->GetOne("SELECT content FROM setting WHERE `name` = ?s","mail");		
		$data_mail = $this->jsonDecode($data_mail);
		
		if(!empty($p["emailPassword"])) 
		{
			$mail["email"]["password"] = $p["emailPassword"];	
		}
		else
		{
			$mail["email"]["password"] = $data_mail['email']["password"];
		}
		
		if(!empty($p["PhoneCheckpassword"])) 
		{
			$mail["phoneverify"]["password"] = 	md5($p["PhoneCheckpassword"]);
		}
		else
		{
			$mail["phoneverify"]["password"] =  $data_mail["phoneverify"]["password"];
		}
		
		$mail = json_encode($mail);
		
		if ($this->db->GetOne("SELECT content FROM setting WHERE name = ?s","mail"))
		{
			$this->db->query($q1,$mail,'mail');
		}
		else
		{
			$this->db->query($q2,$mail,'mail');
		}
		
		return [ "status" => "success", "response" => "Настройки сохранены" ];
	}
	
	/***************************/
	/* Управление настройками сайта */
	/* Дата: 05.02.2019 */
	/* Методы:
		* settingSite - Главная страница
		* settingSiteSave - Сохранить редакирование
		* settingSiteСreateSitemap - Создание sitemap
		
	*/
	/***************************/
		
	public function settingSiteСreateSitemap($p)
	{	
		if(!file_exists($this->sitemapPath)) {
			return [ 	"status" => "error", 
						"response" => "Не создана папка для хранения файлов <b>/public/sitemaps</b> и попробуйте снова"];
		}
	
		/* Проверим надо ли добавить расширение к страницам */
		$this->testExt() ? $html = ".html" : $html = '';
		
		/* Проверям по какому протоколу работает сервер */
		$_SERVER["SERVER_PORT"] == 443 ? $port = "https://" : $port = "http://";
		
		/* Получаем данные категории и продуктов */
		$product = $this->db->GetAll("SELECT IF (c.href IS NULL,p.href,CONCAT(c.href, '/', p.href )) AS url, IF (p.update_date IS NULL,p.create_date,p.update_date) as lastmod FROM product p LEFT JOIN category c ON c.id = p.id_category WHERE p.public = 1 AND p.href != 'index' ORDER BY p.title, p.public ASC");
		$category = $this->db->GetAll("SELECT c.href as url, IF(c.update_date IS NULL,c.create_date,c.update_date) as lastmod FROM category c WHERE c.public = 1");
		
		/* Получаем имя домена */
		$domain =  explode(".",$_SERVER["HTTP_HOST"]);
		
		/* Проверяем нужно ли генерить для всех поддоменов и зон */
		if ($p["fullname"] == "all") {
			$list = $this->db->GetAll("		(SELECT gs.name_en AS domain, 
													CONCAT( gs.name_en, '_', gz.NAME ) AS filename,
													gz.name as zone
												FROM geo_subdomain gs 
												LEFT JOIN geo_zone gz ON gs.id_zone = gz.id 
												WHERE gs.public = 1 AND gz.public = 1) 
										UNION
											( SELECT	'' AS domain, 
														gz.name AS filename,
															gz.name AS zone 
														FROM geo_zone gz 
														WHERE gz.public = 1 )");
														
			if ($list) {
				
				foreach ($list as $key => $val){
					
					if ($val["domain"]) $d = $val["domain"].".";
					else $d = '';
					$list[$key]["fullname"] = $d.$domain[0].".".$val["zone"];
				}
			}
		}
		else {
			
			$fullname = explode(".",$p["fullname"]);
			
			if (count($fullname) == 3) {
				$sub = $fullname[0]."_";
				$zone = $fullname[2];
			}
			else{
				$sub = '';
				$zone = $fullname[1];
			}
			
			$list[0] = [ 	"fullname" => $p["fullname"],
							"filename" => $sub.$zone];
		}
		
		foreach ($list as $k => $value) {	
			
			$host =  $port.$value["fullname"]."/";
			
			$sitemap = $this->sitemapPath . "sitemap_" . $value['filename'] . ".xml";
			
			ob_start(); 
		
			echo '<?xml version="1.0" encoding="UTF-8"?>
					<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
							xmlns:xhtml="http://www.w3.org/1999/xhtml"
							xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
							xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
					<url>
						<loc>'.$host.'</loc>
						<changefreq>weekly</changefreq>
						<priority>1.0</priority>
					</url>';
					
			if ($category) {
				foreach ($category as $cat)
				{
					if($cat['lastmod']){
						$cat['lastmod'] = date(DATE_ATOM, strtotime($cat['lastmod']));
					}
					else{
						$cat['lastmod'] = date(DATE_ATOM, strtotime(date("Y-01-01 05:01:00")));
					}
					
					echo "<url>
							  <loc>{$host}{$cat['url']}{$html}</loc>
							   <lastmod>{$cat['lastmod']}</lastmod>
							   <changefreq>weekly</changefreq>
							   <priority>1.0</priority>
						 </url>";
				}
			}
			
			if ($product) {
				foreach ($product as $pr)
				{
					if($pr['lastmod']){
						$pr['lastmod'] = date(DATE_ATOM, strtotime($pr['lastmod']));
					}
					else{
						$pr['lastmod'] = date(DATE_ATOM, strtotime(date("Y-01-01 05:01:00")));
					}
					
					echo "<url>
							   <loc>{$host}{$pr['url']}{$html}</loc>
							   <lastmod>{$pr['lastmod']}</lastmod>
							   <changefreq>weekly</changefreq>
							   <priority>0.9</priority>
						 </url>";
				}
			}
			
			echo "</urlset>";
			
			$result = ob_get_clean(); 
			
			$writeToFile = file_put_contents ($sitemap,$result);
			
		}
		
		
		if($writeToFile)
		{
			$return = array("status" => "success", "response" => $this->bp->fileDate($sitemap));
		}
		else
		{
			$return = array("status" => "warning", "response" =>"Ошибка создания файла");
		}
		
		return $return;
	}
	
	/* Создание статического sitemap.xml */
	/***************************/
	
	/* Настройка сайта и заявок */
	/***************************/
	
	/* Список бэкапов */
	/* Добавлена: 09.10.2018 */
	/* Обновлена: 14.11.2018 - добавил размер файла */
	
	public function settingBackups()
	{
		$d = $this->db->GetAll("SELECT 	b.*, 
										CONCAT(u.surname, ' ', u. NAME) AS user,
										CONCAT(	DATE_FORMAT(b.date,'%d '),
													ELT( MONTH(b.date), 'Янв.','Фев.','Мар.','Апр.','Мая','Июня','Июля','Авг.','Сен.','Окт.','Ноя.','Дек.'),
													DATE_FORMAT(b.date,' %Y '),
													DATE_FORMAT(b.date,' %H:%i ')) as create_date
									FROM
										?n b
									LEFT JOIN ?n u ON u.id = b.user_id",'backup','users');
								
							
		foreach($d as $k => $v)
		{
			$d[$k]['size'] = $this->bp->fileSizes($v['files_path']);
		}
		
		return $d;
	}
	
	/* Поиск бэкапа для загрузки */
	/* Добавлена: 09.10.2018 */
	
	public function settingBackupGet($id)
	{
		try
		{
			$q = "SELECT files_path FROM backup WHERE download_link = ?s";
			$res = $this->db->GetOne($q,$id);
			
			if( $res )
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
	
	/* Создание бэкапа по POST параметрам */
	/* Функция полностью взята из API */
	/* Добавлена: 09.10.2018 */
	
	public function settingBackupCreate($param = false) {
		$return = array();
		
		try
		{
			if(is_array($param))
			{
				if ( isset( $param['refresh'] ) == true ) 
				{
					$this->bp->remove();
					$r1 = "DELETE FROM ?n";
					$this->db->query($r1,'backup');
					
					$return['status'] == 'warning';
					$return['response'] = "Файлы удалены";
					
				}
				
				if( $param['base'] == true )
				{	
					$this->bp->source = DB_NAME;
					$res1 = $this->bp->copy_database($this->db);
					
					if($res1['status'] == 'success')
					{
						$q1 = "INSERT INTO 
										backup 
										SET 
											user_id = ?i,
											files_path = ?s,
											date = ?s,
											download_link = ?s";
											
						$linkf = $this->generate_code();	
						
						$this->db->query($q1,
											Session::get('sid1'),
											$res1['path'].$res1['file'],
											date('Y-m-d H:i:s', $res1['ts']),
											$linkf);
											
						$user = $this->userinfo();
						
						$return['status'] = 'success';					
						$return['response'] = array('link' => $linkf,
													  "date" => date('H:i:s'),
													  "user" => $user,
													  "size" => $this->bp->fileSizes($res1['path'].$res1['file'])
													  );
					}
					else
					{
						throw new Exception("Не удалось сделать бэкап базы");	
					}
					
				}
				
				if( $param['files'] == true )
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
											user_id = ?i,
											files_path = ?s,
											date = ?s,
											download_link = ?s";
											
						$linkb = $this->generate_code();
						
						if( $this->db->query($q2,
											Session::get('sid1'),
											$res2['path'].$res2['file'],
											date('Y-m-d H:i:s', $res2['ts']),
											$linkb)
						)
						{
							$user = $this->userinfo();
						
							$return['status'] = 'success';					
							$return['response'][] = array('link' => $linkb,
														  "date" => date('H:i:s'),
														  "user" => $user,
														  "size" => $this->bp->fileSizes($res2['path'].$res2['file'])
															);
						}
						else 
						{
							throw new Exception (array('status' =>'error', 'response' => "Не удалось записать в базу"));	
						}
					}
					else
					{
						throw new Exception(array('status' =>'error', 'response' => "Не удалось сделать бэкап файлов"));	
					}
					
				}
				
				
				return $return;
			}
			else 
			{
				throw new Exception(array('status' =>'warning', 'response' => "Не заданы параметры"));
			}
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
		
	}
	
	/* Загрузка бэкапа */
	/* Добавлена: 09.10.2018 */
	
	public function settingDownloadFile ($file)  {
		
		if(!file_exists($file)) {
			return array('status' =>'warning', 'response' => "file not exists");
		}
		
		 // если этого не сделать файл будет читаться в память полностью!
		if (ob_get_level()) {
		  ob_end_clean();
		}
		// заставляем браузер показать окно сохранения файла
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . basename($file));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		// читаем файл и отправляем его пользователю
		readfile($file);
		exit;
    }

	/***************************/
	/* Управление тег менеджером */
	/* Дата: 30.01.2019 */
	/* Методы:
		* tagManagerIndex - Главная страница
		* tagManagerAdd - Добавить тег
		* tagManagerDel - Удалить теги списком
		* tagManagerSwitch - Вкл/Отключить показ
		* tagManagerEdit - Редакировать
		* tagManagerSaveEdit - Сохранить редакированный тег
		
	*/
	/***************************/
	
	public function tagManagerIndex()
	{
		$data = $this->db->GetOne("SELECT content FROM setting WHERE name = 'scripts' LIMIT 1");

		if($data != NULL) 
		{
			$result = $this->jsonDecode($data);
		}
		
		return $result;
	}
	
	public function tagManagerAdd($params) 
	{	
		if($params['name'] != '' and $params['text'] != '') 
		{
		  
			$data = $this->db->GetOne("SELECT content FROM setting WHERE name = 'scripts'");
			
			/* Проверим есть ли еще скрипты в БД */
			if ($data) 
			{
				$scripts_list = $this->jsonDecode($data);
				
				/* Добавляем к скриптам наш скрипт */
				$scripts_list[] = $params;
				
				/* Преобразуем в формат JSON */
				$json = json_encode($scripts_list);
				
				
				$update = $this->db->query("UPDATE 
													setting 
														SET 
															content = ?s 
														WHERE 
															name = ?s",
														$json,
														'scripts');
			}
			else
			{
				$json = json_encode([$params]);
				
				$update =	$this->db->query("INSERT INTO 
													setting 
												SET 
													content = ?s, 
													name = ?s",
													$json,
													'scripts');

			}
		  
			if ($update) 
			{
				
				$return = [	
							'status' => 'success',					
							'response' => [	'name' => $params['name'],
										"position" => $params['position'],
										"public" => $params['public']]];
			}
			else 
			{
				$return['status'] = 'error';					
				$return['response'] = "Ошибка добавления";
			}
		}
		else
		{
			$return = array("status" => "warning", "response" =>"Не заполнены обязательные поля");
		}
		
		return $return;
	}
	
	public function tagManagerDel($list) 
	{	
		if (count($list) > 0)
		{
			$scripts = $this->db->GetOne("SELECT content FROM setting WHERE name = 'scripts'");
		
			$scripts_old = $this->jsonDecode($scripts);
			
			
			foreach ($scripts_old as $v)
			{	
				if (array_search($v['name'],$list) === false)
				{	
					$str = array(	'name' => $v['name'],
									'text' => $v['text'],
									'position' => $v['position'],
									'public' => $v['public']
								);
					$arr[] = $str;
				}
			}
			
			if ($arr) {				
				$json = json_encode($arr);
				$removeQuery = $this->db->query("UPDATE setting SET content = ?s WHERE name = 'scripts'",$json);
			}
			else  {
				$removeQuery = $this->db->query("DELETE FROM setting WHERE name = 'scripts'");
			}
			
			if($removeQuery) 
			{
				$return = array('status' => 'success','response' => "Операция выполнена");
			}
			else 
			{
				$return = 	array('status' => 'error','response' => "Не удалось удалить скрипты");
			}	
		}
		else 
		{
			$return = 	array('status' => 'warning', 'response' => "Не заданы параметры для удаления");	
		}
		
		return $return;
	
	}
	
	public function tagManagerSwitch($list,$status) 
	{	
		if (count($list) > 0)
		{
			$st = array("on" => 1, "off" => 0);
			
			$scripts = $this->db->GetOne("SELECT content FROM setting WHERE name = 'scripts'");
		
			$scripts_old = $this->jsonDecode($scripts);
			
			foreach ($scripts_old as $v)
			{	
				$str = array(	'name' => $v['name'],
									'text' => $v['text'],
									'position' => $v['position'],
									'public' => $v['public']
								);
				if (array_search($v['name'],$list) !== false) $str['public'] = $st[$status];
				
				$arr[] = $str;
			}
			
			$json = json_encode($arr);
			
			$query2 = $this->db->query("UPDATE setting SET content = ?s WHERE name = 'scripts'",$json);
			
			if($query2) 
			{
				$return = array('status' => 'success','response' => "Операция выполнена");
			}
			else 
			{
				$return = 	array('status' => 'error','response' => "Не удалось удалить скрипты");
			}		
		}
		else 
		{
			$return = 	array('status' => 'warning', 'response' => "Не заданы параметры для удаления");	
		}
		
		return $return;
	
	}
	
	public function tagManagerEdit($list) 
	{	
		if (count($list) == 1)
		{
			$scripts = $this->db->GetOne("SELECT content FROM setting WHERE name = 'scripts'");
		
			$scripts_old = $this->jsonDecode($scripts);
			
			foreach ($scripts_old as $v)
			{					
				if ($v['name'] == $list[0]) $return = array('status' => 'success','response' => $v);
			}
			
			if (empty($return)) $return = array('status' => 'error','response' => "Не удалось найти скрипт");
		}
		else 
		{
			$return = 	array('status' => 'warning', 'response' => "Недопустимые параметры");	
		}
		
		return $return;
	
	}
	
	public function tagManagerSaveEdit($params) 
	{	
		if($params['name'] != '' and $params['text'] != '') 
		{
			$scripts = $this->db->GetOne("SELECT content FROM setting WHERE name = 'scripts'");
			
			if ($scripts) 
			{
				$scripts_old = $this->jsonDecode($scripts);
			
				foreach ($scripts_old as $v)
				{	
					$str = $v;
									
					if ($v['name'] == $params['oldname'])
					{
						unset($str);
						$str = array(	'name' => $params['name'],
										'text' => $params['text'],
										'position' => $params['position'],
										'public' => $params['public']
									);
					}
					$arr[] = $str;
				}
				
				$json = json_encode($arr);
				
				$update = $this->db->query("UPDATE setting SET content = ?s WHERE name = 'scripts'",$json);
				
				if($update) 
				{
					$return = array('status' => 'success','response' => $params);
				}
				else
				{
					$return = array('status' => 'error','response' => "Ошибка обновления тега");
				}
			}
			else {
				$return = array("status" => "warning", "response" =>"Удаленный тег нельзя редакировать");
			}
		}
		else{
			$return = array("status" => "warning", "response" =>"Не заполнены обязательные поля");
		}
		
		return $return;
	}
	
	/* Управление тег менеджером */
	/***************************/
	
	
	
	public function redirectIndex()
	{
		$data = $this->db->GetAll("SELECT r.*, u.name as user
										FROM
											redirect r
										LEFT JOIN users u ON u.id = r.id_user 
										ORDER BY r.id DESC
										LIMIT 100");
		
		return $data;
	}
	
	public function redirectAdd($p)
	{
		$url_from = explode("\n",$p['from']);
		$url_to = explode("\n",$p['to']);
		$public = $p['public'];
		
		if(count($url_from) > 0 and count($url_to) > 0)
		{
			$ids = [];
			
			foreach($url_from as $key => $link)
			{
				$check = $this->db->GetOne("SELECT id 
													FROM 
													redirect 
													WHERE 
														`from` = ?s
													AND
														`to` = ?s",
														$link,
														$url_to[$key]);
														
				if(!$check)
				{
					if($url_to[$key])
					{					
						$q = "INSERT INTO redirect SET  `from` = ?s,
													`to` = ?s,
													id_user = ?i,
													public = ?i,
													create_date = NOW()";
													
						$this->db->query($q,$link,$url_to[$key],Session::get('sid1'),$p['public']);
						
						$ids[] = $this->db->insertId();
					}
				}
			}
			
			if (count($ids) > 0)
			{
				$data = $this->db->GetAll("SELECT 	r.*,
													CONCAT(u.surname,' ',u.name) as user 
													FROM 
														redirect r 
													LEFT JOIN 
														users u 
													ON u.id = r.id_user
													WHERE
													 r.id IN (?a)",$ids);
				
				if($data)
				{
					$return = array("status" => "success", "response" => $data);
				}
				else
				{
					$return = array("status" => "warning", "response" => "Не удалось добавить");
				}
			}
			else
			{
				$return = array("status" => "warning", "response" =>"Не удалось добавить");
			}
		}
		else
		{
			$return = array("status" => "warning", "response" =>"Не заданы параметры");
		}
		
		return $return;	
	}
	
	public function redirectDel($p)
	{
		if (count($p) > 0)
		{
			$rem = $this->db->query("DELETE FROM redirect WHERE id IN(?a)",$p);
			
			if($rem) $return = array("status" => "success", "response" =>"Удалено ".count($p));
		}
		else
		{
			$return = array("status" => "warning", "response" =>"Не заданы параметры");
		}
		
		return $return;	
	}
	
	public function redirectSwitch($p)
	{
		if (count($p->l) > 0)
		{
			$st = array("on" => 1, "off" => 0);
			
			$q = $this->db->query("UPDATE redirect SET public = ?i WHERE id IN(?a)",$st[$p->s],$p->l);
			
			if($q) $return = array("status" => "success", "response" =>"Статусы обновлены у ".count($p->l));
		}
		else
		{
			$return = array("status" => "warning", "response" =>"Не заданы параметры");
		}
		
		return $return;	
		
	}
	
	public function navigationIndex ()
	{
		
		$data = $this->db->GetAll("SELECT
											m.*,
											COUNT(ml.id) as point
										FROM
											menu m
										LEFT JOIN menu_list ml ON ml.id_menu = m.id
										GROUP BY (m.id)");
		
		return $data;
	}
	
	public function navigationAdd($p)
	{
		if($p['name'] != '' OR $p['name_en'] != 'nav_')
		{
			$check = $this->db->getOne("SELECT id FROM menu WHERE name_en = ?s",$p['name_en']);
			
			if(!$check)
			{
				$this->db->query("INSERT INTO menu SET ?u",$p);
				
				$id = $this->db->insertId();
				
				if($id)
				{
					$p['id'] = $id;
					
					$return = array("status" => "success", "response" => $p);
				}
				else
				{
					$return = array("status" => "warning", "response" =>"Ошибка добавления");
				}
			}
			else
			{
				$return = array("status" => "warning", "response" =>"Меню с таким названием существует");
			}
			
		}
		else
		{
			$return = array("status" => "warning", "response" =>"Не заполнены обязательные поля");
		}
		
		return $return;
	}
	
	public function navigationDel($p)
	{
		if (count($p) > 0)
		{
			$rem = $this->db->query("DELETE FROM menu WHERE id IN(?a)",$p);
			
			$rem2 = $this->db->query("DELETE FROM menu_list WHERE id_menu IN(?a)",$p);
			
			if($rem) $return = array("status" => "success", "response" =>"Удалено ".count($p));
		}
		else
		{
			$return = array("status" => "warning", "response" =>"Не заданы параметры");
		}
		
		return $return;	
	}
	
	public function navigationEdit ($id)
	{
		if (is_numeric($id)) 
		{ 	
			$menu = $this->db->GetRow("SELECT * FROM menu WHERE id = ?i",$id);
			
			$return['status'] = "success";
			
			$return['response']['menu'] = $menu;
			
			$list = $this->db->GetAll("SELECT *
															FROM
																menu_list
															WHERE
																id_menu = ?i
															AND
																id_sub IS NULL
															ORDER BY 
																position",$id);
			
			foreach ($list as $k => $val)
			{
				$data =  $this->db->GetAll("SELECT *
															FROM
																menu_list
															WHERE
																id_menu = ?i
															AND
																id_sub = ?i",$id,$val['id']);
																
				if(count($data) > 0) $list[$k]['children'] = $data;												
			}
			
			$return['response']['list'] = $list;
			
		}
		else
		{
			$return = array("status" => "warning", "response" =>"Не заполнены обязательные поля");
		}
		
		return $return;
	}
	
	public function navigationSaveEdit($p)
	{
		if (is_numeric($p['menu']['id']))
		{
			$q_menu = "UPDATE menu SET 
									name = ?s,
									name_en = ?s,
									public = ?i 
									WHERE
										id = ?i";
			
			$r_menu = $this->db->query($q_menu,$p['menu']['name'],$p['menu']['name_en'],$p['menu']['public'],$p['menu']['id']);
			
			if (count($p['list']) > 0)
			{
				$del = $this->db->query("DELETE FROM menu_list WHERE id_menu = ?i",$p['menu']['id']);
				
				foreach ($p['list'] as $k => $v)
				{
					if($k == 0 or $k == 1) $k = $k+1;
					
					$this->db->query("INSERT INTO menu_list SET 
																id_menu = ?i,
																name = ?s,
																href = ?s,
																position = ?i",
																$p['menu']['id'],
																$v['name'],
																$v['href'],
																$k);
					
					$id_sub = $this->db->insertId();
					
					if( isset($v['children']) and count($v['children']) > 0)
					{
						foreach($v['children'] as $k2 => $v2)
						{
							if($k2 == 0 or $k2 == 1) $k2 = $k2+1;
							
							$this->db->query("INSERT INTO menu_list SET 
																	id_menu = ?i,
																	name = ?s,
																	href = ?s,
																	position = ?i,
																	id_sub = ?i",
																$p['menu']['id'],
																$v2['name'],
																$v2['href'],
																$k2,
																$id_sub);
						}
					}
				}
			}
			
			$return = array("status" => "success", "response" =>"Операция выполнена");
		}
		else
		{
			$return = array("status" => "warning", "response" =>"Не заполнены обязательные поля");
		}
		
		return $return;
		
		
	}
	
	public function navigationGetCategory()
	{
		$return = array();
		
		$d = $this->db->GetAll("SELECT id,title,CONCAT('/',href) as href FROM category WHERE public = 1");
			
		$return['response'] = $d;
			
		$return['status'] = "success";
				
		return $return;
	}
	
	public function navigationGetProducts($id)
	{
		$return = [];
		
		if (!empty($id))
		{
			$d = $this->db->GetAll("SELECT p.id,
																				p.title,
																				if(c.href != '',
																					CONCAT('/',c.href,'/',p.href),
																					CONCAT('/',p.href)) as href
																				FROM 
																					product p
																				LEFT JOIN
																					category c ON c.id = p.id_category
																				WHERE  
																					p.public = 1
																				AND
																					p.title like ?s
																				ORDER BY 
																					p.title",
																				"%".$id."%");
			
			if(count($d) > 0)
			{
				$return['status'] = "success";
				$return['response'] = $d;
			}
			else
			{
				$return['status'] = "warning";
				$return['response'] = null;
			}
		
		}
		else
		{
			$return['status'] = "warning";
			$return['response'] = "Не задан параметр";
		}
		
		return $return;																		
	}
	
	public function navigationSwitch($p)
	{
		if (count($p->l) > 0)
		{
			$st = array("on" => 1, "off" => 0);
			
			$q = $this->db->query("UPDATE menu SET public = ?i WHERE id IN(?a)",$st[$p->s],$p->l);
			
			if($q) $return = array("status" => "success", "response" =>"Статусы обновлены у ".count($p->l));
		}
		else
		{
			$return = array("status" => "warning", "response" =>"Не заданы параметры");
		}
		
		return $return;	
		
	}
	
	public function subDomain()
	{
		
		$zone = $this->db->GetAll("SELECT
											gz.*, COUNT(gs.id_zone) AS sub
										FROM
											geo_zone gz
										LEFT JOIN geo_subdomain gs ON gs.id_zone = gz.id
										GROUP BY
											(gz.id)");
		
		
		if(count($zone) > 0)
		{
			$return = array( "zone" => $zone);
			
			$return['subdomain'] = $this->db->GetAll("SELECT
															gs.*, gz.name AS zone
														FROM
															geo_subdomain gs
														LEFT JOIN geo_zone gz ON gz.id = gs.id_zone");
			
		}
		else
		{
			$return = array( "status" => "warning", "response" => "Не задан осовной домен");
		}
		
		return $return;
	}
	
	public function  geoAddZone($data)
	{
			if(!empty($data['name']))
			{
				$name_exists = $this->db->GetOne("SELECT id FROM ?n WHERE name = ?s",'geo_zone',$data['name']);
				
				if (!$name_exists)
				{
					if(strlen($data['subdomain']) > 1) 
					{
						$sub = explode("\n",$data['subdomain']);
						
						if(count($sub) == 0) unset($sub);
					}
					
					unset($data['subdomain']);
					
					$q = $this->db->query("INSERT INTO ?n 
															SET 
																?u, 
																create_date = NOW() 
																ON DUPLICATE KEY UPDATE ?u",
																'geo_zone',
																$data,
																$data);
					
					if ($q)
					{
						$id = $this->db->insertId();
						
						if($data['default'] == 1) $this->db->query("UPDATE geo_zone gs
																					SET gs.default = 0
																					WHERE
																						gs.id != ?i",
																					$id);
							
						if($sub)
						{
							foreach ($sub as $n)
							{
								$n = $this->parser->createUrl($n);
								
								$this->db->query("INSERT INTO ?n 
																SET 
																	name_en = ?s, 
																	id_zone = ?i,
																	public = 0
																	ON DUPLICATE KEY UPDATE name_en = ?s AND id_zone = ?i",
																	'geo_subdomain',
																	$n,
																	$id,
																	$n,
																	$id);
								$sub_list[] = $this->db->insertId();
							}
						}
						
						if($id) 
						{
							if($sub_list) {
								
								$subdomains = $this->db->GetAll("SELECT	gs.*,
														gz.name as zone
													FROM
														geo_subdomain gs
														LEFT JOIN geo_zone gz ON gz.id = gs.id_zone
													WHERE
														gs.id IN (?a)",$sub_list);
							}
							$res = $this->db->GetRow("SELECT gz.*,count(gs.id) as subdomain FROM geo_zone gz LEFT JOIN geo_subdomain gs ON gs.id_zone = gz.id WHERE gz.id = ?i",$id);
							
							$this->writeLogs("Добавил новую зону ".$res['name']);
							
							$r = [ "status" => "success","response" => ["zone" =>$res]];
							
							if ($subdomains) $r["response"]["sub"] = $subdomains;
						}
					}
				}
				else
				{
					$r = array( "status" => "warning", "response" => "Такое зона существует");
				}
			}
			else 
			{
				$r = array( "status" => "warning", "response" => "Не заданы параметры");
			}
		
		return $r;
	}
	
	public function geoDelZone($data)
	{
			if ($data)
			{
				if (count($data) > 0)
				{
					$ret = null;
			
					foreach ($data as $v)
					{
						$default = $this->db->GetOne("SELECT `default` FROM geo_zone WHERE id = ?i",$v);
						
						if (!$default) {
							$qz = $this->db->query("DELETE	FROM
																geo_zone
															WHERE
																id = ?i",
															$v);
						
							if ($qz)
							{
								$ids = $this->db->GetAll("SELECT id FROM geo_subdomain WHERE id_zone = ?i",$v);
								
								if ($ids) {
									$qs = $this->db->query("DELETE FROM geo_subdomain WHERE id_zone = ?i",$v);
								}
								
								$ret[] = [ 
											"zone" => $v,
											"subdomain" => $ids];
							}
						}
						
					}
					
					if($ret)
					{
						$r = array( "status" => "success","response" => $ret);	
					}
					else
					{
						$r = array( "status" => "warning","response" => "Не удалось выполнить удаление");	
					}
				}
				else
				{
					$r = array( "status" => "warning","response" =>  "Не заданы параметры");	
				}	
			}
			else 
			{
				$r = array( "status" => "warning", "response" => "Не заданы параметры");
			}
		
		return $r;
	}

	public function geoEditZone($id)
	{
		if ($id)
		{
			$data = $this->db->GetRow("SELECT * FROM geo_zone WHERE id = ?i",$id);
			
			if ($data)  
			{
				$r = array( "status" => "success","response" => $data);
			}
			else
			{
				$r = array( "status" => "warning", "response" => "Нет данных базе");
			}
		}
		else 
		{
			$r = array( "status" => "warning", "response" => "Не заданы параметры");
		}
		
		return $r;
		
	}
	
	public function geoSaveEditZone($data)
	{
		if ($data['id'])
		{
			$id = $data['id'];
			unset($data['id']);
			unset($data['subdomain']);
			
			if($data['default'] == 1) {
				$this->db->query("UPDATE geo_zone gz SET gz.default = 0 WHERE gz.id != ?i",$id);
			}
		
			$result = $this->db->query("UPDATE geo_zone SET ?u WHERE id = ?i",$data,$id);
			
			if ($result)  
			{
				$res = $this->db->GetRow("SELECT gz.*,count(gs.id) as subdomain 
													FROM 
														geo_zone gz 
													LEFT JOIN geo_subdomain gs ON gs.id_zone = gz.id 
													WHERE 
														gz.id = ?i",$id);
				
				$r = array( "status" => "success","response" => $res);
			}
			else
			{
				$r = array( "status" => "warning", "response" => "Не удалось обновить");
			}
		}
		else 
		{
			$r = array( "status" => "warning", "response" => "Не заданы параметры");
		}
		
		return $r;
		
	}
	
	public function geoZoneSwitch($list,$status) 
	{	
		if (count($list) > 0)
		{
			$st = array("on" => 1, "off" => 0);
			
			$query2 = $this->db->query("UPDATE geo_zone SET public = ?i WHERE id IN (?a)",$st[$status],$list);
			
			if($query2) $return = array('status' => 'success','response' => "Операция выполнена");
			else $return = 	array('status' => 'error','response' => "Не удалось обновить статус");
					
		}
		else 
		{
			$return = 	array('status' => 'warning', 'response' => "Не заданы параметры для удаления");	
		}
		
		return $return;
	
	}
	
	
	public function geoGetZone()
	{
		
		$data = $this->db->GetAll("SELECT * FROM geo_zone");
		
		if($data[0])
		{
			$return = array('status' => 'success','response' => $data);
		}
		else
		{
			$return = 	array('status' => 'warning', 'response' => "Нету данных");	
		}
		
		return $return;
		
	}
	
	public function geoAddSubdomain($data)
	{
		if (strlen($data['name_ru']) > 0)
		{
			$check = $this->db->GetOne("SELECT id 
												FROM geo_subdomain 
												WHERE id_zone = ?i 
												AND name_ru = ?s",
												$data['id_zone'],
												$data['name_ru']);
			
			if(!$check)
			{
				if( empty($data['name_en']) ) $data['name_en'] = $this->parser->createUrl($data['name_ru']);
				
				
				$this->db->query("INSERT INTO geo_subdomain 
															SET ?u, 
															create_date = NOW()",
															$data);
				
				$id = $this->db->insertId();
			
				if($id)
				{
					$res = $this->db->GetROW("SELECT	gs.*,
														gz.name as zone
													FROM
														geo_subdomain gs
														LEFT JOIN geo_zone gz ON gz.id = gs.id_zone
													WHERE
														gs.id = ?i",
													$id);
																		
					// Запись логов
					$this->writeLogs("Добавил новый поддомен ".$res['name_en'] . " в зоне ".$res["zone"]);
					
					$return = array('status' => 'success', 'response' => $res);
				}
				else
				{
					$return = array('status' => 'error', 'response' => "Ошибка добавления");
				}	
			}
			else 
			{
				$return = 	array('status' => 'warning', 'response' => "Такой поддомен существует");	
			}
		}
		else
		{
			$return = 	array('status' => 'warning', 'response' => "Не заданы параметры");	
		}
		
		return $return;
	}
	
	public function geoSwitchSubdomain($list,$status) 
	{	
		if (count($list) > 0)
		{
			$st = array("on" => 1, "off" => 0);
			
			$query2 = $this->db->query("UPDATE geo_subdomain SET public = ?i WHERE id IN (?a)",$st[$status],$list);
			
			if($query2) $return = array('status' => 'success','response' => "Операция выполнена");
			else $return = 	array('status' => 'error','response' => "Не удалось обновить статус");
					
		}
		else 
		{
			$return = 	array('status' => 'warning', 'response' => "Не заданы параметры для удаления");	
		}
		
		return $return;
	
	}
	
	public function geoDelSubdomain ($data)
	{	
			if ($data) {
				
				$remove = $this->db->query("DELETE FROM ?n WHERE id IN (?a)",'geo_subdomain',$data);
				
				if ($remove)
				{
					$r = array( "status" => "success","response" => $data);		
				}
				else
				{
					$r = array( "status" => "error", "response" => "Не удалось удалить");
				}
			}
			else {
				$r = array( "status" => "warning", "response" => "Не заданы параметры");
			}
		
		return $r;
	}
	
	public function geoEditSubdomain($id)
	{
		if ($id)
		{
			$data = $this->db->GetRow("SELECT * FROM geo_subdomain WHERE id = ?i",$id);
			
			if ($data)  
			{
				$r = array( "status" => "success","response" => $data);
			}
			else
			{
				$r = array( "status" => "warning", "response" => "Нет данных базе данных");
			}
		}
		else 
		{
			$r = array( "status" => "warning", "response" => "Не заданы параметры");
		}
		
		return $r;
		
	}
	
	public function geoSaveEditSubdomain($data)
	{
		if ($data['id'] and !empty($data["name_en"]))
		{
			$id = $data['id'];
			
			$test = $this->db->GetOne("SELECT id FROM geo_subdomain WHERE name_en = ?s",$data["name_en"]);
			
			if (!$test)
			{
				$q = $this->db->query("UPDATE geo_subdomain SET ?u WHERE id = ?s",$data,$id);
				
				if ($q) {
					
					$ret = $this->db->GetROW("SELECT	gs.*,
														gz.name as zone
													FROM
														geo_subdomain gs
														LEFT JOIN geo_zone gz ON gz.id = gs.id_zone
													WHERE
														gs.id = ?i",
														$id);
					
					$r = array( "status" => "success","response" => $ret);
				}
				else {
					$r = array( "status" => "error","response" => "Не удалось обновить");
				}
			}
			else
			{
				$r = array( "status" => "error","response" => "Такой поддомен уже существует");
			}
		}
		else 
		{
			$r = array( "status" => "warning", "response" => "Не заданы параметры");
		}
		
		return $r;
		
	}
	
	private function generateCode($length=12) {
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
			$code = "";
			$clen = strlen($chars) - 1;
			while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];
			}
			return $code;
	}
	
	/***************************/
	/* Управление пользователями */
	/* Дата: 27.03.2019 */
	/* Методы:
		* pageReLinkIndex - Главная страница
		* pageReLinkAdd - Добавить перелинковку
		* usersDel - Удалить пользователя
		* usersSwitch - Вкл/Отключить
		* usersEdit - Редакировать
		* usersSaveEdit - Сохранить редакирование
		
	*/
	/***************************/
	
	public function pageReLinkIndex () {
		
		$query = $this->db->GetAll("SELECT
											r.*,
											IFNULL( pD.title, cD.title ) AS donor,
											CONCAT('/',IFNULL(cD.href,CONCAT_WS('/',c1.href,pD.href))) AS durl,
											IFNULL( pM.title, cM.title ) AS main,
											CONCAT('/',IFNULL(cM.href,CONCAT_WS('/',c2.href,pM.href))) AS murl
										FROM
											relink r
											LEFT JOIN product pD ON ( r.donor_id = pD.id AND r.donor_type = 'p' )
											LEFT JOIN category c1 ON c1.id = pD.id_category
											LEFT JOIN category cD ON ( r.donor_id = cD.id AND r.donor_type = 'c' )
											LEFT JOIN product pM ON ( r.main_id = pM.id AND r.main_type = 'p' )
											LEFT JOIN category c2 ON c2.id = pM.id_category
											LEFT JOIN category cM ON ( r.main_id = cM.id AND r.main_type = 'c' )");
														
		return $query;
	}
	
	public function  pageReLinkAdd ($data)
	{
		$donor_type = "p";
		$acceptor_type = "p";
		
		if (is_array($data))
		{
			if (!empty($data["donor"]) AND !empty($data["acceptor"]) AND !empty($data["ankor"]) )
			{
				if ( $data["donor"] !== $data["acceptor"])
				{
					if (is_numeric($data["donor"])) 
					{
						$donor = $data["donor"];
					}
					else
					{
						$pos = strpos($data["donor"], "c");
						
						if ($pos !== false)
						{
							$donor_type = "c";
							$donor = substr($data["donor"],1);
						}
						
					}
					
					if (is_numeric($data["acceptor"])) 
					{
						$acceptor = $data["acceptor"];
					}
					else
					{
						$pos = strpos($data["acceptor"], "c");
						
						if ($pos !== false)
						{
							$acceptor_type = "c";
							$acceptor = substr($data["acceptor"],1);
						}
						
					}
					
					$test = $this->db->GetOne("SELECT id 
														FROM 
															relink 
														WHERE 
															main_id = ?i
														AND	
															donor_id = ?i
														AND
															ankor = ?s
														AND
															main_type = ?s
														AND 
															donor_type = ?s",
														$acceptor,
														$donor,
														$data["ankor"],
														$acceptor_type,
														$donor_type);
					
					if ($test)
					{
						$r = array( "status" => "warning", "response" => "Данная перелинковка уже существует");
					}
					else
					{
						$add = $this->db->query("INSERT INTO 
														relink 
													SET 
														main_id = ?i,	
														donor_id = ?i,
														ankor = ?s,
														main_type = ?s, 
														donor_type = ?s,
														create_date = NOW()",
														$acceptor,
														$donor,
														$data["ankor"],
														$acceptor_type,
														$donor_type);
						$id = $this->db->insertId();								
					
						if($id)
						{
							$this->writeLogs("Добавил новую перелинковку Id {$id} { Анкор: {$data["ankor"]} / Id {$donor} -> Id {$acceptor}}");
							
							$result = $this->db->GetRow("SELECT
																r.*,
																IFNULL( pD.title, cD.title ) AS donor,
																CONCAT('/',IFNULL(cD.href,CONCAT_WS('/',c1.href,pD.href))) AS durl,
																IFNULL( pM.title, cM.title ) AS main,
																CONCAT('/',IFNULL(cM.href,CONCAT_WS('/',c2.href,pM.href))) AS murl
															FROM
																relink r
																LEFT JOIN product pD ON ( r.donor_id = pD.id AND r.donor_type = 'p' )
																LEFT JOIN category c1 ON c1.id = pD.id_category
																LEFT JOIN category cD ON ( r.donor_id = cD.id AND r.donor_type = 'c' )
																LEFT JOIN product pM ON ( r.main_id = pM.id AND r.main_type = 'p' )
																LEFT JOIN category c2 ON c2.id = pM.id_category
																LEFT JOIN category cM ON ( r.main_id = cM.id AND r.main_type = 'c' )
															 WHERE 
																r.id = ?i",
															$id);
							
							$r = array( "status" => "success", "response" => $result);
						}
						else
						{
							$r = array( "status" => "error", "response" => "Ошибка добавления");
						}
					}
					
				}
				else
				{
					$r = array( "status" => "warning", "response" => "Донор и акцептор не могут быть одинаковыми");
				}
			}
			else
			{
				$r = array( "status" => "warning", "response" => "Не заданы параметры");
			}
		}
		else 
		{
			$r = array( "status" => "warning", "response" => "Не заданы параметры");
		}
		
		return $r;
		
	}
	
	public function pageReLinkDel ($ids)
	{
		if (is_array($ids))
		{
			if (count($ids))
			{
				$res = $this->db->query("DELETE FROM relink WHERE id IN (?a)",$ids);
				
				if ($res)
				{
						$this->writeLogs("Удалил(а) записи из таблица перелинковка ids ->".implode(",",$ids));
							
						$r = array( "status" => "success", "response" => "Операция выполнена");
				}
				else
				{
					$r = array( "status" => "error", "response" => "Ошибка удаления");
				}
			}
			else
			{
				$r = array( "status" => "warning", "response" => "Не заданы параметры");
			}
		}
		else 
		{
			$r = array( "status" => "warning", "response" => "Не заданы параметры");
		}
		
		return $r;
	}
	
	public function page_create_upload(){
		 
		$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/public/uploads/';
		$rename = $this->generateCode(10);
		
		$fimename = $_FILES['uploadfile']['name'];
		
		$ext = substr($_FILES['uploadfile']['name'],strrpos($_FILES['uploadfile']['name'],'.'),strlen($_FILES['uploadfile']['name'])-1); 
		$file = $uploaddir . basename($rename.$ext);
		$filetypes = array('.xls','.XLS');
		
		
		if(!in_array($ext, $filetypes)){
				
				echo "Неправильный формат!";
				exit;
		}
		else{ 
			
			if ($m = move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) {
						
				$f = $uploaddir.$rename.$ext;
				$arr = file($f);
		
				$res = $this->add_multi_page($f);
				
				echo json_encode(	
								array('status' => 'success',
									  'description'=> $res
									),
									JSON_HEX_QUOT | JSON_HEX_TAG
							);
			}

		}
		
	
	}
	
	
	public function add_multi_page($filename){
		
		require $_SERVER['DOCUMENT_ROOT'].'/public/modules/excel/PHPExcel.php';
		
		//$filename = $_SERVER['DOCUMENT_ROOT'].'/public/uploads/'."test.xls";
		
		if (file_exists($filename)) {
		
			$objPHPExcel = new PHPExcel();
			$objPHPExcel  = $objPHPExcel->getActiveSheet(0);
			$objPHPExcel = PHPExcel_IOFactory::load($filename);
		
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
			{

				$lastRow         = $worksheet->getHighestRow();
				$lastColumn      = $worksheet->getHighestColumn();
				$lastColumnIndex = PHPExcel_Cell::columnIndexFromString($lastColumn);
				
				//echo '<table border="1" cellspacing="0">';
			   
			   for ($row = 2; $row < $lastRow+1; ++$row) 
				{
					  $params = array();
					  $params['title'] = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					  $params['mini_desc'] = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					  $params['description'] = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					  $params['id_category'] = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
					  $params['href'] = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
					  $params['meta_title'] = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
					  $params['meta_description'] = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
					  $params['avatar'] = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
					  $params['public'] = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
					  $params['h1'] = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
					  
					  $params['pole1'] = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
					  $params['pole2'] = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
					  $params['pole3'] = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
					  $params['pole4'] = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
					  $params['pole5'] = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
					  
					
				  /* echo '<tr>'	.'<td>'.$row.'&nbsp;</td>'
								.'<td>'.$name.'&nbsp;</td>'
								.'<td>'.$mini_desc.'&nbsp;</td>'
								.'<td>'.$description.'&nbsp;</td>'
								.'<td>'.$category.'&nbsp;</td>'
								.'<td>'.$type.'&nbsp;</td>'
								.'<td>'.$meta_title.'&nbsp;</td>' 
								.'<td>'.$meta_description.'&nbsp;</td>' 
								.'<td>'.$public.'&nbsp;</td></tr>';  */
					//echo "</table>";
			
				if(strlen(trim($params['title'])) >= 4) {
						
					$params['id_category'] = $this->db->GetOne("SELECT id FROM category WHERE title = ?s and (type = 'category' OR type = 'blog')",$params['id_category']);
					if(empty($category_id)) {
						$category_id = NULL;
					}

					if(empty($params['public'])) $params['public'] = 1;
					
					if(strlen(strip_tags($params['description'])) < 500) $public = 0;
										
					$res[] = $this->addproduct($params,true);
					
					}
				}
				
			}	
				return $res;
			}
		else {
			echo "file not exists";
		}
	
	}
	
	/* Функция проверки редиректа на html */
	/* Добавлена: 16.04.2019 */
	
	private function testExt ()
	{
		$html = $this->db->GetOne("SELECT content 
											FROM ?n 
											WHERE 
												name = ?s",
												"setting",
												"extredirect");
		
		if ($html == 1) {
			return true;
		}
		else {
			return false;
		}
	}
	
	
	
	
	/* 26.12.2017 послед редактированные */
	
	public function last_red_page() {
		
		$data = $this->db->GetAll("(SELECT CONCAT('/',c.href,'/',p.href,'.html') as href, p.update_date
									from product p 
									LEFT JOIN category c ON c.id = p.id_category
									WHERE date(p.update_date) = CURDATE() OR date(p.update_date) = DATE_ADD(CURDATE(), INTERVAL -1 DAY) ) UNION 
									( SELECT CONCAT('/',c.href,'.html') as href,  c.update_date
									from category c
									WHERE date(c.update_date) = CURDATE() OR date(c.update_date) = DATE_ADD(CURDATE(), INTERVAL -1 DAY)
									)");
	
		return $data;
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
				$data[] = json_decode($v,JSON_HEX_QUOT | JSON_HEX_TAG);
			}
		}
		else
		{
			$data = json_decode($d,JSON_HEX_QUOT | JSON_HEX_TAG);
		}
		
		return $data;
	}
	
	/* Функция для отладки  *
	/* Добавлена: 11.10.2018 */
	
	private function getDebug($r)
	{
			echo "Debug: ";
			echo "<pre>";
			var_dump
			(
				$r
			);
			// echo "<pre>";
			
			exit;
		
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