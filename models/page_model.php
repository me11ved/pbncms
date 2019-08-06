<?php
 class Page_Model extends Model {
	 
   public function __construct() 
	{
	   parent::__construct();
	   $this->exten_page = '.html';
	   $this->folder_cache = $_SERVER["DOCUMENT_ROOT"].'/public/cache';
	}
	
	
	private function checkSubdomain($args)
	{
		$data = $this->db->GetRow("SELECT gs.*
											FROM 
												geo_subdomain gs 
											LEFT JOIN geo_zone gz ON gz.id = gs.id_zone
											WHERE 
												gs.name_en = ?s
											AND
												gz.name = ?s",
											$args->subdomain,
											$args->zone);
		
		if($data)
		{
			$res = $data;
		}
		else
		{
			$res = false;
		}
			
		return $res;
	}
 
 	public function checkPage($args) 
	{
		
		$return = ["status" => false];
		
		$zone = $this->db->GetRow("SELECT	 gz.id,
													gz.default 
												FROM 
													geo_zone gz
												WHERE 
													gz.name = ?s
												AND 
													gz.public = 1",
												$args->zone);
		
		if(!empty($args->subdomain) OR $zone["default"] != 1)
		{
			$sub = $this->checkSubdomain($args);
			
			if($sub)
			{
				/* Проверяем наличие категории */
			
				if ( count ($args->exp) == 1)
				{
					$data = $this->db->GetRow("SELECT
														gc.id_cat AS id,
														gc.id_cat as id_category,
														c.href
													FROM
														geo_content gc
														LEFT JOIN geo_subdomain gs ON gs.id = gc.id_subdomain
														LEFT JOIN geo_zone gz ON gz.id = gc.id_zone
														LEFT JOIN category c ON c.id = gc.id_cat 
													WHERE
														c.href = ?s 
														AND gc.id_cat IS NOT NULL 
														AND gs.name_en = ?s 
														AND gz.name = ?s
														AND gz.public = 1
														AND gs.public = 1",
														$args->exp[0],
														$args->subdomain,
														$args->zone);
					
						if($data)
						{
							$return = [
										"status" => true,
										"type" => "category",
										"data" => $data
										];
						}
						/* Проверяем наличие статической страницы */
						else
						{
							$data = $this->db->GetRow("SELECT
															gc.id_page AS id,
															p.id_category,
															p.href
														FROM
															geo_content gc
															LEFT JOIN geo_subdomain gs ON gs.id = gc.id_subdomain
															LEFT JOIN geo_zone gz ON gz.id = gc.id_zone
															LEFT JOIN product p ON p.id = gc.id_page 
														WHERE
															p.href = ?s 
															AND gc.id_page IS NOT NULL 
															AND gs.name_en = ?s
															AND gz.name = ?s
															AND gz.public = 1
															AND gs.public = 1",
															$args->exp[0],
															$args->subdomain,
															$args->zone);
							
							if($data)
							{
								$type = "static";
								
								if($data["href"] == "index") $type  = "index";
									
								$return = [
											"status" => true,
											"type" => $type,
											"data" => $data
											];
							}
							else
							{
								$return["subdomain"] = true; 
							}
						}
					}
					/* Проверяем наличие страницы продукта */
					else
					{
						$url = $args->exp;
						array_shift($url);
						$url = implode("/",$url);
						
						$data = $this->db->GetRow("SELECT
															gc.id_page AS id,
															p.id_category,
															p.href,
															c.href as template
														FROM
															geo_content gc
															LEFT JOIN geo_subdomain gs ON gs.id = gc.id_subdomain
															LEFT JOIN geo_zone gz ON gz.id = gc.id_zone
															LEFT JOIN product p ON p.id = gc.id_page
															LEFT JOIN category c ON c.id = p.id_category															
														WHERE
															p.href = ?s 
															AND gc.id_page IS NOT NULL 
															AND gs.name_en = ?s
															AND gz.name = ?s
															AND gz.public = 1
															AND gs.public = 1",
															$url,
															$args->subdomain,
															$args->zone);
							
							if($data)
							{
								$return = [
											"status" => true,
											"type" => "product",
											"data" => $data
											];
							}
							else
							{
								$return["subdomain"] = true; 
							}
					}	
			}
			else
			{
				$return["subdomain"] = true; 
			}
			
		}
		else
		{
				/* Проверяем наличие категории */
				
				if ( count ($args->exp) == 1)
				{
					$data = $this->db->GetRow("SELECT * 
														FROM 
															category 
														WHERE 
															href = ?s
														AND
															public = 1",
														$args->exp[0]);
					
					if($data)
					{
						$return = [
									"status" => true,
									"type" => "category",
									"data" => $data
									];
					}
					/* Проверяем наличие статической страницы */
					else
					{
						$data = $this->db->GetRow("SELECT * 
															FROM 
																product 
															WHERE 
																href = ?s 
															AND 
																id_category = 0
															AND 
																public = 1",
															$args->exp[0]);
						
						if($data)
						{
							$type = "static";
							
							if($data["href"] == "index") $type  = "index";
								
							$return = [
										"status" => true,
										"type" => $type,
										"data" => $data
										];
						}
					}
				}
				/* Проверяем наличие страницы продукта */
				else
				{
					$url = $args->exp;
					array_shift($url);
					$url = implode("/",$url);
					
					$data = $this->db->GetRow("SELECT p.*,
														c.href as template
														FROM 
															product p
														LEFT JOIN
															category c
														ON c.id = p.id_category
														WHERE 
															p.href = ?s
														AND
															c.href = ?s
														AND
															p.public = 1",
														$url,
														$args->exp[0]);
						
						if($data)
						{
							$return = [
										"status" => true,
										"type" => "product",
										"data" => $data
										];
						}
				}
		}
	
		return $return;
	}
	
	// check product page
	
	public function product() {
		
		$category = $this->parser->url(0);
		$page = $this->parser->productHref();
		
		
		
		if($page and $category) {
			
			$data = $this->db->GetAll("SELECT 	p.id
											FROM product p
											LEFT JOIN category c ON c.href = ?s
											WHERE 
												p.href = ?s and 
												p.id_category = c.id
												and p.public = 1",$category,$page);
			if(count($data) > 0) {
				
				if($data[0]['type'] == 'category') {
					$response = array (
									'type' => $data[0]['type'],
									'id' => $data[0]['id']
									);
				
				}
				elseif($data[0]['type'] == 'blog') {
					$response = array (
									'type' => $data[0]['type'],
									'id' => $data[0]['id']
									);
				
				}
				else {
					$response = array (
										'type' => 'error',
										);
				
				}
				
				return $response;
				
			}
			else {
				
				
					$response = array (
										'type' => 'error',
										);
				
					return $response;
				}
			}
	
	}
	
	private function getExtInBase()
	   {
		   
		  $status = $this->db->GetOne("SELECT 
										content 
									FROM 
										setting 
									WHERE 
										name = ?s",
									'extredirect');
		  
		  return $status;
	   }
	
	
	public function queryPost($params = NULL,$id_product = NULL,$data1 = NULL){
		
		if(is_numeric($data1["id_category"]))
		{
			$id_category = $data1["id_category"];
		}
		else
		{
			$id_category= $data1["id"] ;
		}
		
		$this->getExtInBase() == 1 ? $html = ".html" : $html = '';
		
		$q_category = "SELECT
							CONCAT(c.href,'{$html}') AS url,
							c.id AS id_category,
							c.mini_desc,

						IF (
							c.h1 = '',
							c.title,
							IFNULL(c.h1, c.title)
						) AS h1,
						 c.title,
						 c.avatar AS avatar,
						 c.description,

						IF (
							c.meta_title = '',
							c.title,
							c.meta_title
						) AS meta_title,

						IF (
							c.meta_description = '',
							c.meta_description,
							c.meta_description
						) AS meta_description
						FROM
							category c
						WHERE	
							 c.public = 1";
		
		$q_product = "SELECT
							p.*,
						IF (
							p.h1 = '',
							p.title,
							IFNULL(p.h1, p.title)
						) AS h1,
						 IFNULL(p.avatar, '') AS avatar,
						 IFNULL(c.avatar, '') AS avatar_cat,
						 c.id AS id_category,
						 p.id AS id_product,
						 c.title AS title_category,

						IF (
							p.bread_crumbs = '',
							c.title,
							p.bread_crumbs
						) AS bread_crumbs,

						IF (
							p.meta_title IS NULL
							OR p.meta_title = '',
							CONCAT(
								IFNULL(c.meta_product_title1, ''),
								' ',
								p.title,
								' ',
								IFNULL(c.meta_product_title2, '')
							),
							p.meta_title
						) AS meta_title,

						IF (
							p.meta_description IS NULL
							OR p.meta_description = '',
							CONCAT(
								IFNULL(c.meta_product_desc1, ''),
								' ',
								p.title,
								' ',
								IFNULL(c.meta_product_desc2, '')
							),
							p.meta_description
						) AS meta_description,
						 CONCAT('/', c.href, '/', p.href,'{$html}') AS url
						FROM
							product p
						LEFT JOIN category c ON c.id = p.id_category
						WHERE
							p.public = 1";
		$result  = NULL;
		
		if(is_array($params))
		{
			$result  = array();
			
			foreach( $params as $key => $p )
			{
				$query = array();
				
				//Проверяем из какой таблицы нужно тащить данные
				switch($p['type']) {
					case 1:
						$query[] = $q_category;
						$prefix = "c.";
						break;
					case 2:
						$query[] = $q_product;
						$prefix = "p.";
						break;
				}
				
				//Проверяем есть ли id категории $p['cat']
				if( is_numeric($p['cat']) OR $p['cat'] == 'this' ) 
				{
					$query[] =  "AND p.id_category = ?i";
				}
				else if(is_array($p['cat']))
				{
					$query[] =  "AND p.id_category IN (?a)";
				}
				
				//Проверяем есть ли id категории $p['cat']
				if( is_numeric($p['pos']) ) 
				{
					$query[] =  "AND p.position = ".$p['pos'];
				}
				
				// Преобразуем ид записей в массив
				if(strlen($p['ids']) >= 1)  
				{
					$p['ids'] = explode(',',$p['ids']); 
					
					$p['ids'] = array_diff($p['ids'], array(''));
					
					foreach($p['ids'] as $n) 
					{
						if( is_numeric($n) ) $_ids[] = $n;
					}
					
					$_ids = implode(",",$_ids);
					
					if(count($p['ids']) > 0) $query[] = "AND " . $prefix . "id IN (". $_ids .")";
				}
				
				// Проверям порядок сортировки
				switch($p['order'])  
				{
					case "title":
						$query[] = "ORDER BY " . $prefix . "title"; 
					break;
					
					case "h1":
						$query[] = "ORDER BY " . $prefix . "h1"; 
					break;
					
					case "desc":
						$query[] = "ORDER BY " . $prefix . "id DESC"; 
					break;
				}
				
				
				//Проверяем задан ли лимит
				if( is_numeric($p['limit']) ) $query[] = "LIMIT " . $p['limit'];
				 
				$query = implode(" ",$query);
					
				// $this->debug($p);
				
				if(is_numeric($p['cat']) or $p['cat'] == 'this') 
				{
					if($p['cat'] == 'this') $p['cat'] = $id_category;
					
					$result[$key] = $this->db->GetAll($query,$p['cat']);
				}
				else {
					$result[$key] = $this->db->GetAll($query);
				}
				unset($query);
			}	
		}
		// $this->debug($result);
		return $result;
	}
	
	// выборка меню для отображения
	public function menuList() {
		
		//Выбираем списки актуального меню
		
		$menu_public = $this->db->GetAll("SELECT id,name_en FROM menu WHERE public = 1");
		
		//Добавляем ассоциацию пунктов для категорий
			
		foreach ($menu_public as $n => $menu)
		{
			$list = $this->db->GetAll("SELECT 	id,
												name,
												href,
												position
											FROM
												menu_list
											WHERE
												id_menu = ?i
											AND
												id_sub IS NULL
											ORDER BY 
												position",$menu['id']);
			
			$menu_public[$n]['menu'] = $list;
			
			foreach ($list as $k => $sub)
			{
				$p =  $this->db->GetAll("SELECT *
															FROM
																menu_list
															WHERE
																id_menu = ?i
															AND
																id_sub = ?i",$menu['id'],$sub['id']);
																
				$menu_public[$n]['menu'][$k]['children'] = $p; 												
			}
		}
		
		return $menu_public;
	}
	
	// дата главной страницы
	
	public function pageindex($id,$args = false) {		
			
			if($args->subdomain) 
			{
				$sub = $this->checkSubdomain($args);
				
				if($sub)
				{
					$data = $this->db->GetRow("SELECT	g.*,
														if(g.h1 = '',g.title,IFNULL(g.h1,g.title)) as h1
														FROM
															geo_content g
														LEFT JOIN geo_subdomain gs ON gs.name_en = g.id_subdomain
														WHERE
															g.id_subdomain = ?i
														AND	
															g.public = 1
														AND
															g.id_zone = ?i
														AND
															g.id_page  = ?i",
														$sub["id"],
														$sub["id_zone"],
														$id);
				}
				
			}
			else
			{
				$zone = $this->db->GetRow("SELECT	 gz.id,
													gz.default 
												FROM 
													geo_zone gz
												WHERE 
													gz.name = ?s",
												$args->zone);
				
				if($zone['default'] == 1)
				{
					$data = $this->db->GetRow("SELECT 	p.*,
														if(p.h1 = '',p.title,IFNULL(p.h1,p.title)) as h1
													FROM 
														product p  
													WHERE 
														p.href = 'index'");
				}
				else
				{
					$data = $this->db->GetRow("SELECT 	gc.*,
														if(gc.h1 = '',gc.title,IFNULL(gc.h1,gc.title)) as h1
														FROM 
															geo_content gc  
														WHERE 
															gc.id_zone = ?i 
														AND
															gc.id_subdomain = 0",
															$zone["id"]);
				}
				
			}
			
			return $data;
			
			
			
	}
	
	// дата старницы категории
	public function pageCategory($id,$argument) {
		
		
		if($argument->subdomain) 
		{
				$sub = $this->checkSubdomain($argument);
				
				if($sub)
				{
					$dataCategory = $this->db->GetRow("SELECT		c.*,
																	g.*,
																	if(g.h1 = '',g.title,IFNULL(g.h1,g.title)) as h1,
																	if(g.meta_title = '',g.title,g.meta_title) as meta_title,
																	if(g.meta_description = '',g.meta_description,g.meta_description) as meta_description,
																	c.avatar as avatar,
																	gs.yv,
																	gs.gv
																	FROM
																		geo_content g
																	LEFT JOIN category c ON c.id = g.id_cat
																	LEFT JOIN geo_subdomain gs ON gs.name_en = g.id_subdomain
																	WHERE
																		g.id_subdomain = ?i
																	AND	
																		g.public = 1
																	AND
																		g.id_zone = ?i
																	AND 
																		g.id_cat = ?i	
																	",
														$sub['id'],
														$sub['id_zone'],
														$id);		
				}
		}
		else
		{
			$zone = $this->db->GetRow("SELECT	 gz.id,
													gz.default 
												FROM 
													geo_zone gz
												WHERE 
													gz.name = ?s",
												$argument->zone);
			if($zone['default'] == 1)
			{
				$dataCategory = $this->db->GetRow("SELECT 	c.*,
																if(c.h1 = '',c.title,IFNULL(c.h1,c.title)) as h1,
																c.title,
																c.description,
																c.id as id_category,
																if(c.meta_title = '',c.title,c.meta_title) as meta_title,
																if(c.meta_description = '',c.meta_description,c.meta_description) as meta_description,
																c.avatar as avatar
																FROM 
																	category c 
																WHERE 	
																	c.id = ?s 
																AND 
																	c.public = 1",
																$id);
			}
			else
			{
				$dataCategory = $this->db->GetRow("SELECT		c.*,
																	g.*,
																	if(g.h1 = '',g.title,IFNULL(g.h1,g.title)) as h1,
																	if(g.meta_title = '',g.title,g.meta_title) as meta_title,
																	if(g.meta_description = '',g.meta_description,g.meta_description) as meta_description,
																	c.avatar as avatar,
																	gs.yv,
																	gs.gv
																	FROM
																		geo_content g
																	LEFT JOIN category c ON c.id = g.id_cat
																	LEFT JOIN geo_subdomain gs ON gs.name_en = g.id_subdomain
																	WHERE
																		g.id_subdomain = 0
																	AND	
																		g.public = 1
																	AND
																		g.id_zone = ?i
																	AND 
																		g.id_cat = ?i	
																	",
														$zone['id'],
														$id);	
			}
		}
		
		/* 19.03.2019 */
		/* Добавляем перелинку в текст */
		if ($dataCategory["description"]) 
		{
			$relink = $this->reLinkList($id,$dataCategory["description"]);
			
			if ($relink["check"])
			{
				$dataCategory["description"] = $relink["description"];
			}
		}
		
		return $dataCategory;
	}
	
	
	public function pageStatic($id,$args) {
		
		if($args->subdomain) 
		{
				$sub = $this->checkSubdomain($args);
				
				if($sub)
				{
					$data = $this->db->GetRow("SELECT	g.*,
														IF
															( g.h1 = '', g.title, IFNULL( g.h1, g.title ) ) AS h1,
														IF
															( g.meta_title = '', g.title, IFNULL( g.meta_title, g.title ) ) AS meta_title,
														IF
															( g.meta_description = '', g.title, IFNULL( g.meta_description, g.title ) ) AS meta_description 
														FROM
															geo_content g 
														WHERE
															g.id_page = ?i
														AND 
															g.id_subdomain = ?i",
												$id,
												$sub["id"]);
				}
		}
		else
		{
				$zone = $this->db->GetRow("SELECT	 gz.id,
													gz.default 
												FROM 
													geo_zone gz
												WHERE 
													gz.name = ?s",
												$args->zone);
				
				if($zone['default'] == 1)
				{
					$data = $this->db->GetRow("SELECT	p.*,
														IF
															( p.h1 = '', p.title, IFNULL( p.h1, p.title ) ) AS h1,
														IF
															( p.meta_title = '', p.title, IFNULL( p.meta_title, p.title ) ) AS meta_title,
														IF
															( p.meta_description = '', p.title, IFNULL( p.meta_description, p.title ) ) AS meta_description 
														FROM
															product p 
														WHERE
															p.href = ?s",
												$args->exp[0]);
				}
				else
				{
					$data = $this->db->GetRow("SELECT	g.*,
														IF
															( g.h1 = '', g.title, IFNULL( g.h1, g.title ) ) AS h1,
														IF
															( g.meta_title = '', g.title, IFNULL( g.meta_title, g.title ) ) AS meta_title,
														IF
															( g.meta_description = '', g.title, IFNULL( g.meta_description, g.title ) ) AS meta_description 
														FROM
															geo_content g 
														WHERE
															g.id_page = ?i
														AND 
															g.id_subdomain = 0",
												$id);
				}
		}	
		
		/* 19.03.2019 */
		/* Добавляем перелинку в текст */
		if ($data["description"]) 
		{
			$relink = $this->reLinkList($id,$data["description"]);
			
			if ($relink["check"])
			{
				$data["description"] = $relink["description"];
			}
		}
		
		return $data;
				
	}
		
	
	// data page product
	public function pageProduct($id,$args) 
	{
				
				
		if($args->subdomain) 
		{
			$sub = $this->checkSubdomain($args);
			
			if($sub)
			{
				$data = $this->db->GetRow("SELECT			g.*,
															if(g.h1 = '',g.title,IFNULL(g.h1,g.title)) as h1,
															if(g.meta_title = '',g.title,g.meta_title) as meta_title,
															if(g.meta_description = '',g.meta_description,g.meta_description) as meta_description,
															IFNULL(p.avatar,'') as avatar,
															IFNULL(c.avatar,'') as avatar_cat,
															c.href as cat_path,
															CONCAT('/',c.href,'/',p.href) as href
															FROM
																geo_content g
															LEFT JOIN product p ON p.id = g.id_page
															LEFT JOIN category c ON c.id = p.id_category
															WHERE
																g.id_subdomain = ?i
															AND	
																g.public = 1
															AND
																g.id_zone = ?i
															AND 
																g.id_page = ?i",
														$sub["id"],
														$sub['id_zone'],
														$id);	
			}
		}
		else
		{
			$zone = $this->db->GetRow("SELECT	 gz.id,
												gz.default 
											FROM 
												geo_zone gz
											WHERE 
												gz.name = ?s",
											$args->zone);
			
			if($zone['default'] == 1)
			{
				$data = $this->db->GetRow("SELECT 	p.*,
													if(p.h1 = '',p.title,IFNULL(p.h1,p.title)) as h1,
													IFNULL(p.avatar,'') as avatar,
													IFNULL(c.avatar,'') as avatar_cat,
													p.id as id_product,
													c.id as id_category,
													c.title as title_category,
													if(p.bread_crumbs = '',c.title,p.bread_crumbs) as bread_crumbs,
													if(p.meta_title IS NULL OR p.meta_title = '',CONCAT(IFNULL(c.meta_product_title1,''),' ',p.title,' ',IFNULL(c.meta_product_title2,'')),p.meta_title) as meta_title,
													if(p.meta_description IS NULL OR p.meta_description = '',CONCAT(IFNULL(c.meta_product_desc1,''),' ',p.title,' ',IFNULL(c.meta_product_desc2,'')),p.meta_description) as meta_description,
													c.href as cat_path,
													CONCAT('/',c.href,'/',p.href) as href,
													'product' as cont_article
													FROM 
														product p
													LEFT JOIN 
														category c ON c.id = p.id_category
													WHERE 
														p.id = ?i AND 
														p.public = 1",$id);
			}
			else
			{
				$data = $this->db->GetRow("SELECT			g.*,
															if(g.h1 = '',g.title,IFNULL(g.h1,g.title)) as h1,
															if(g.meta_title = '',g.title,g.meta_title) as meta_title,
															if(g.meta_description = '',g.meta_description,g.meta_description) as meta_description,
															IFNULL(p.avatar,'') as avatar,
															IFNULL(c.avatar,'') as avatar_cat,
															c.href as cat_path,
															CONCAT('/',c.href,'/',p.href) as href
															FROM
																geo_content g
															LEFT JOIN product p ON p.id = g.id_page
															LEFT JOIN category c ON c.id = p.id_category
															WHERE
																g.id_subdomain = 0
															AND	
																g.public = 1
															AND
																g.id_zone = ?i
															AND 
																g.id_page = ?i",
														$zone['id'],
														$id);	
			}
		}
		
		/* 19.03.2019 */
		/* Добавляем перелинку в текст */
		if ($data["description"]) 
		{
			$relink = $this->reLinkList($id,$data["description"]);
			
			if ($relink["check"])
			{
				$data["description"] = $relink["description"];
			}
		}
		
		return $data;
	}
	
	public function reLinkList($id,$text) {
			
			$result = array('check' => false);
			
			$data = $this->db->GetAll("(SELECT 	pr.ankor,
												CONCAT('/',c.href,'.html') as link
												FROM relink pr 
												LEFT JOIN product p ON p.id = pr.donor_id
												LEFT JOIN category c ON c.id = pr.main_id
												WHERE 
															pr.donor_id = ?i AND 
															pr.public = 1 AND
															pr.donor_type = 'p' AND
															pr.main_type = 'c'
												GROUP BY (pr.id) )
										UNION
										(
											SELECT 	pr.ankor,
															CONCAT('/',c.href,'/',p.href,'.html') as link
															FROM relink pr 
															LEFT JOIN product p ON p.id = pr.main_id
															LEFT JOIN category c ON c.id = p.id_category
															WHERE 
																		pr.donor_id = ?i AND 
																		pr.public = 1 AND
																		pr.donor_type = 'c' AND
																		pr.main_type = 'p'
															GROUP BY (pr.id)
										)
										UNION
										(
											SELECT 	pr.ankor,
															if(c.href != '',CONCAT('/',c.href,'/',p.href,'.html'),CONCAT('/',p.href)) as link
															FROM relink pr 
															LEFT JOIN product p ON p.id = pr.main_id
															LEFT JOIN category c ON c.id = p.id_category
															WHERE 
																		pr.donor_id = ?i AND 
																		pr.public = 1 AND
																		pr.donor_type = 'p' AND
																		pr.main_type = 'p'
															GROUP BY (pr.id)
										)",$id,$id,$id);
		
			if ($data) 
			{
				foreach($data as $val) 
				{
					
					$pos = stripos($text,$val['ankor']);
					
					
					if ($pos !== false) 
					{ 
						$text = substr_replace ($text, '<a href="'.$val['link'].'">'.$val['ankor'].'</a>', $pos, strlen($val['ankor']) );
					} 
				}
							
				$result = array(	'check' => true,
									'description' => $text);
				
			}
				
		return $result;
		
				
	}
	
	// data bread crumbs products
	
	public function breadCrumbs($args,$data) 
	{
		
		$host  = $args->protokol . "://" . $_SERVER["HTTP_HOST"];
	
		if ($this->getExtInBase())
		{
			$html = ".html";
		}
		else
		{
			$html;
		}
		
		$return = [	['title' => 'Главная',
					 'href' =>  '/']
		];
		
		if($data["type"] == "static" or $data["type"] == "category")
		{
			if ($data["data"]["bread_crumbs"]) 
			{
				$data["data"]["title"] = $data["data"]["bread_crumbs"];
			}
			else if ($data["data"]["h1"] and !$data["data"]["bread_crumbs"])
			{
				$data["data"]["title"] = $data["data"]["h1"];
			}
			
			$return[]	= [	'title' => $data["data"]["title"],
							'href' =>  "/".$data["data"]["href"].$html];
		}
		
		if($data["type"] == "product")
		{
			$all_urls = $this->db->GetRow("SELECT 
													p.href as url,
													CONCAT('/',c.href,'/',p.href) as href,
													CONCAT('/',c.href) as cat_href,
													IF ( 
														p.avatar != '' OR p.avatar IS NOT NULL,
														p.avatar,
														NULL) as avatar,
													p.position,
													IF (
														p.bread_crumbs = '' 
														OR p.bread_crumbs IS NULL,
														IF
															( p.h1 = '' OR p.h1 IS NULL, p.title, p.h1 ),
															p.bread_crumbs 
															) AS title,
													IF (
														c.bread_crumbs = '' 
														OR c.bread_crumbs IS NULL,
														IF
															( c.h1 = '' OR c.h1 IS NULL, c.title, c.h1 ),
															c.bread_crumbs 
															) AS cat_title
												FROM 
													product p
												LEFT JOIN category c ON c.id = p.id_category
												WHERE 
													p.href = ?s",
												$data["data"]["href"]);
			
			$urls = explode("/",$all_urls["url"]);
			
			if (count($urls) <= 1)
			{
				$return[]	= [	'title' => $all_urls["cat_title"],
							'href' => $all_urls["cat_href"].$html
							];
				$return[]	= [	'title' => $all_urls["title"],
								'href' => $all_urls["href"].$html
								];
			}
			else
			{
				foreach ($urls as $k => $val)
				{
					if (!$k)
					{
						$q = "SELECT 
									CONCAT('/',c.href,'/',p.href) as href,
									CONCAT('/',c.href) as cat_href,
									p.position,
									IF ( 
										p.avatar != '' OR p.avatar IS NOT NULL,
										p.avatar,
										NULL) as avatar,
									IF (
										p.bread_crumbs = '' 
										OR p.bread_crumbs IS NULL,
										IF
											( p.h1 = '' OR p.h1 IS NULL, p.title, p.h1 ),
											p.bread_crumbs 
											) AS title,
									IF (
										c.bread_crumbs = '' 
										OR c.bread_crumbs IS NULL,
										IF
											( c.h1 = '' OR c.h1 IS NULL, c.title, c.h1 ),
											c.bread_crumbs 
											) AS cat_title
								FROM 
									product p
								LEFT JOIN category c ON c.id = p.id_category
								WHERE 
									p.href = ?s";
						
						$u = $val;
						
						$return[]	= [	'title' => $all_urls["cat_title"],
										'href' => $all_urls["cat_href"].$html];
										
					}
					else
					{
						$q = "SELECT 
										CONCAT('/',c.href,'/',p.href) as href,
										p.position,
										IF ( 
											p.avatar != '' OR p.avatar IS NOT NULL,
											p.avatar,
											NULL) as avatar,
										IF (
											p.bread_crumbs = '' 
											OR p.bread_crumbs IS NULL,
											IF
											( p.h1 = '' OR p.h1 IS NULL, p.title, p.h1 ),
											p.bread_crumbs 
											) AS title
									FROM 
										product p
									LEFT JOIN category c ON c.id = p.id_category
									WHERE 
										p.href = ?s";
						$u = $u."/".$val;
					}
					
					$get = $this->db->GetRow($q,$u);
					
					$return[]	= [	
									'title' => $get["title"],
									'href' => $get["href"].$html
									];
				}
			}
			
											
		}
		
		return (object) [
							"data" => $return,
							"host" => $host];
	}
	
	// data static scripts
	
	public function scriptStatic() {
		
		$query = $this->db->GetOne("SELECT content FROM setting WHERE name = 'scripts'");
		
		$scripts = $this->jsonDecode($query);
		
		$header = [];
		$footer = [];
		
		if ($scripts)
		{
			foreach($scripts as $key => $val)
			{	
				 if($val->position == 'header' and $val->public == 1) 
				 {
					array_push($header,'<!-- name: '.$val->name.'--> '.$val->text);
				 } 
				 else if($val->position == 'footer' and $val->public == 1) 
				 {
					array_push($footer,'<!-- name: '.$val->name.'--> '.$val->text);
				 }
			}
		}
		
		$result = array( 	'header' => $header,
							'footer' => $footer);
		
		return $result;
	}
	// data phone and email for all page
	public function metaContacts($args = false,$id = null) 
	{		
			$data = $this->db->getRow("SELECT telephone,email,address FROM geo_zone WHERE name = ?s",$args->zone);
			
			if($args->subdomain) 
			{
					
					$q2 = $this->db->GetRow("SELECT 	gs.phone,
														gs.adr,
														gs.email
														FROM 
															geo_subdomain gs
														LEFT JOIN 
															geo_zone gz ON gz.name = ?s  	
														WHERE
															gs.public = 1
														AND
															gs.name_en = ?s",
															$args->zone,
															$args->subdomain); 
					$data['telephone'] = $q2['phone'];
					$data['email'] = $q2['email'];	
					$data['address'] = $q2['adr'];					
			}
			
			
			
			return  $data;
	}
	
	// create sitemap xml/html
	public function sitemap($args) {
		
		if($args->subdomain)
		{
			$sub = $this->db->GetOne("SELECT gs.id 
									FROM 
										geo_subdomain gs
									LEFT JOIN geo_zone gz ON gz.id = gs.id_zone
									WHERE 
										gs.name_en = ?s
									AND
										gs.public = 1
									AND
										gz.public = 1",
								$args->subdomain);
								
			if(!$sub) return false;
		}
		
		$path = $_SERVER["DOCUMENT_ROOT"]."/public/sitemaps/";
		$file = "sitemap.xml";
		
		if ($args->subdomain) {
			$file = "sitemap_".$args->subdomain."_".$args->zone.".xml";
		}
		else {
			$file = "sitemap_".$args->zone.".xml";
		}
		
		
		
		if (file_exists($path.$file))
		{	
			header ("content-type: text/xml");
			echo file_get_contents($path.$file);
			exit;
		}
		else
		{
			return false;
		}
	}
	
	// create sitemap xml/html
	public function robots($args) 
	{
		if($args->subdomain)
		{
			$sub = $this->db->GetOne("SELECT gs.id 
									FROM 
										geo_subdomain gs
									LEFT JOIN geo_zone gz ON gz.id = gs.id_zone
									WHERE 
										gs.name_en = ?s
									AND
										gs.public = 1
									AND
										gz.public = 1",
								$args->subdomain);
			if(!$sub) 
			{
				return false;
			}
		}
		
		$robotsFile = $_SERVER["DOCUMENT_ROOT"]."/public/robots.txt";
		
		$host = $args->protokol."://".$_SERVER["SERVER_NAME"];
		
		header("Content-Type: text/plain");
		
		echo 	"# robots.txt -- Forward bots!\r\n";
		echo	"Host: ".$host."\r\n";
		echo	"Sitemap: ".$host."/sitemap.xml\r\n";
		echo	"User-agent: *\r\n".
				"Disallow: /admin/\r\n".
				"Disallow: /tmpl/\r\n".
				"Disallow: /public/js/"."\r\n".
				"Disallow: /public/personal.html\r\n".
				"\r\n";
		
		if (file_exists($robotsFile)) echo file_get_contents($robotsFile);
	}
	
	// create rss channel xml
	public function createRss() {
		
		$data = $this->db->GetAll("SELECT 	p.*,
													if(p.h1 = '',p.title,IFNULL(p.h1,p.title)) as h1,
													IFNULL(p.avatar,'') as avatar,
													c.id as id_category,
													c.title as title_category,
													c.href as cat_path,
													CONCAT('/',c.href,'/',p.href) as href,
													p.create_date
													FROM 
														product p
													LEFT JOIN 
														category c ON c.id = p.id_category
													WHERE  
														p.public = 1
													ORDER BY 
														p.id
													ASC");
														
		foreach ($data AS $key_prod => $prod) 
		{
				$data[$key_prod]['create_date'] =  date(DATE_ATOM, strtotime($prod['create_date']));
				
				$data[$key_prod]['description'] =  str_replace(array("&nbsp;",'&laquo;','«','»'),'',$prod['description']);
				$data[$key_prod]['mini_desc'] =  str_replace(array("&nbsp;",'&laquo;','«','»','&raquo;'),'',$prod['mini_desc']);
				
				if(CHECK_HTML === true) $data[$key_prod]['href'] = $prod['href'].$this->exten_page;
				
		}
			
		return $data;
		
	}
	
  private function debug($data){
	
	echo "<pre>";
	
	var_dump($data);
	
	exit;
  
  }
  
  // check old url
	
	public function checkOldUrl($args) {
		
		$res = null;
		
		$from = $args->path;
		
		if($args->query) $from = $from."/?".$args->query;
		
		$q = "SELECT r.to
					FROM
						redirect r
					WHERE
						r.from = ?s
					AND
						r.public = 1";	
		
		$href = $this->db->GetOne($q,$from) ; 
			
		if($href) $res = $href;

		
		
		return $res;
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
				$data[] = json_decode($v);
			}
		}
		else
		{
			$data = json_decode($d);
		}
		
		return $data;
	}
	
	/***************************/
	/* Управление кэшированием сайта */
	/* Дата: 05.02.2019 */
	/* Методы:
		* checkCache - Получаем статус кэша
		* readCache - Чтение кэша
		* whiteCache - Запись
		
	*/
	/***************************/
	
	public function checkCache()
	{
		if (is_null(Session::get('cache')))
		{
			$data = $this->db->GetOne("SELECT 
											content 
										FROM 
											setting 
										WHERE 
											name = ?s",
										"cache");
		
		
		
			if ($data)
			{
				$cache = $this->jsonDecode($data);
				
				$res = [	"status" => $cache->status,
							"time" => $cache->time
						];
				
				Session::set('cache', $cache->status);
				Session::set('cache_time', $cache->time);
			}
			else
			{
				$res["status"] = false;
			}
		}
		else
		{
			$res = [	"status" => Session::get('cache'),
						"time" => Session::get('cache_time')
						];
		}
		
		return $res;
	}
	
	public function readCache($args) 
	{			
			$cache = $this->checkCache();
			$html = '';
			
			if($cache["status"]) 
			{
				$file = $args->path;
				
				if (empty($file))  $file = '/index';
				if(!$args->redirect) $html = '.html';
				
				$cache_file = $this->folder_cache.$file.$html;
				
				// Если файл с кэшем существует
				if (file_exists($cache_file)) 
				{
					 // Если его время жизни ещё не прошло
					if ((time() - $cache['time']) < filemtime($cache_file)) 
					{
						echo file_get_contents($cache_file); // Выводим содержимое файла
					  
						$this->view->render('scripts',false,true); // выводим дин скрипты
					  
						exit; // Завершаем скрипт, чтобы сэкономить время при дальнейшей обработки
					}
				}
				ob_start(); // Открываем буфер для вывода, если кэша нет, или он устарел
			}
		}
		
	public function writeCache($args) 
	{
	
		$cache = $this->checkCache();
		$html = '';
		
		if($cache["status"]) 
		{	
			
			$file = $args->path;
				
			if (empty($file)) 
			{
				$file = '/index';
			}
			
			if(!$args->redirect) $html = '.html';
			
			if($this->bp->createDirectory($file))
			{
				
				$handle = fopen($this->folder_cache.$file.$html, 'w'); // Открываем файл для записи и стираем его содержимое
				
				fwrite($handle, ob_get_contents()); // Сохраняем всё содержимое буфера в файл
			
				fclose($handle); // Закрываем файл
			
				ob_end_flush(); // Выводим страницу в браузере
			}
			
			
			
		}
	}
  
 }  
  
?>