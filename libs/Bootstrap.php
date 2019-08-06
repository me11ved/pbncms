<?php
  class Bootstrap {
	  								
		private $controllerExt = array('html');
		
		public function __construct () 
		{
		
			/*
			 * Начало сессии пользователя
			 *
			 * var referral $_SERVER['HTTP_REFERER'] - реферрал ссылка пользователя 
			 * var client_id strtotime(date('Y-m-d H:i:s')) - уникальный код клиента для передачи в я.метрику
			 * 
			 */
			$this->setSessionStart();
			
			/*
			 * Парсим входящий URL
			 * $url - Значение по умлочанию - null
			 *
			 * @return $url - полный url
			 * @return $exp - массив элеметов с запрашиваемой страницей
			 * @return $ext - расширение в конце url
			 * @return $end - конечный элемент пути
			 */
			isset($_GET['url']) ? $url = $this->urlParse( $_GET['url'] ) : null;
			
			/*
			 * Отображение главной страницы - если нет параметров в $url 
			 *
			 */
			if( empty($url)) $this->loadPage(null,true);
			
			/*
			 * Загрузка другого модуля (кроме страниц сайта) -  например админ панель
			 *
			 * @params $url->exp[0] - название контроллера
			 * @params $url->exp[1] - название метода
			 *
			 */
			if ( $url->exp[0] ) $this->loadModule($url);
			
			/*
			 * Загрузка динамического sitemap или robots.txt
			 *
			 * @params $url->path - путь до файла
			 *
			 */
			
			if($url->path == "/sitemap.xml" or $url->path == "/robots.txt") 
			{
				switch($url->path)
				{
					case "/sitemap.xml" :
						$this->loadPage(false,false,true);
					break;
					
					case "/robots.txt" :
						$this->loadPage(false,false,false,true);
					break;
				}
			}
			
			/*
			 * Загружаем страницу если есть параметры
			 *
			 * Условия: $exp - элементов пути должно быть больше 1
			 *
			 * @params $url - полный url
			 * @params $exp - массив элеметов с запрашиваемой страницей
			 * @params $ext - расширение в конце url
			 * @params $end - конечный элемент пути
			 */
			 
			if(count($url->exp) >= 1) $this->loadPage($url);

			/*
			 * Если условия не подошли, грузим страницу 404
			 */
			$this->loadErrorPage();
			
			return false;
		 
	   }
	    
	   private function loadPage($url,$main = false, $sitemap = false, $robots = false)
	   {
			require_once 'controllers/page.php';
			
			$controller = new Page();
			
			$controller->loadModel('page');
			
			/*
			 * Получем параметры для определения домена
			 *
			 * @return $p		- протокол http/https
			 * @return $sub 	- поддомен
			 * @return $zone	- доменная зона
			 * @return $query 	- запрашиваемый путь 
			 */
			
			$param = $this->urlParse(true);
			
			if($main)
			{
				$param->exp[0] = "index";
			}
			else if($sitemap)
			{
				$controller->sitemap($param);
				exit;
			}
			else if($robots)
			{
				$controller->robots($param);
				exit;
			}
			else
			{
				$result = $controller->redirectCheck($param);
			
				if($result) 
				{
						$go_path = $param->protokol."://".$_SERVER['HTTP_HOST'].$result;
						
						$this->r(301, $go_path);	
						
						exit;
				}
				
				/* 
				 * Проверка на *.html 
				 *
				 * Управление в админке, пукнт: настройки
				 * Редирект 301 если не соответсвует условиям 
				 */
				
				$this->htmlExtTest($param);
			}
			/* 
			 * Загружаем страницу или категорию 
			 *
			 * @params $exp - количество параметров зависит загружаемого модуля
			 */
			 
			$controller->pageLoad($param);
	   }
	   
	   private function loadErrorPage()
	   {
			header("http/1.0 404 Not Found"); 
			
			require_once 'controllers/page.php';
			
			$controller = new Page();
			
			$controller->loadModel('page');
			
			$controller->page_error();	
			
			exit;
	   }
	   
	   private function loadModule($url = null) 
	   {
		   if(isset($url))
		   {
			   
			   if( file_exists('controllers/'.$url->exp[0].'.php') )
			   {   
			   
				require_once 'controllers/'.$url->exp[0].'.php';
				
				$controller = new $url->exp[0]();
				
				$controller->loadModel($url->exp[0]);
				
				if (empty($url->exp[1]))
				{	
					$controller->index();
				}
				else
				{
					if(!isset($url->exp[2])) $url->exp[2] = '';
					$controller->{$url->exp[1]}($url->exp[2]);	
				}
				
				exit;
			   }
		   }
	   }
   
	   private function htmlExtTest($p) 
	   {
		   
			
		  
		  $start = $p->protokol."://".$_SERVER['HTTP_HOST'];
		  
		  if($p->redirect == 0 AND $p->ext == 'html')
		  {
			$start = $start.str_replace(".html","",$p->path);
			
			if ( isset($p->query)) $start .= "?".$p->query;
			
			$this->r(301,$start);  
		  }
		  else if($p->redirect == 1 AND empty($p->ext) )
		  {
			$start = $start.$p->path.".html";
			
			if ( $p->query ) $start .= "?".$p->query;
			
			$this->r(301,$start); 			
		  }
	   }
	   
	   private function getExtInBase()
	   {
		   $db = new sql(array(
								'user'    => DB_USER,
								'pass'    => DB_PASSWORD,
								'db'      => DB_NAME,
								'host'	=> DB_HOST,
								'charset' => DB_CHARSET
				));
		   
		  $status = $db->GetOne("SELECT 
										content 
									FROM 
										setting 
									WHERE 
										name = ?s",
									'extredirect');
		  
		  return $status;
	   }
		
		private function checkBots()
		{
			if($_SERVER['HTTP_REFERER'] == 'http://127.0.0.1:8888/orange.html') 
			{
					$this->forbidden();
			}
			else {
				 
				$agent = $_SERVER['HTTP_USER_AGENT'];
				 
				$str_pr1 = substr($agent,stripos($agent,'(')+1,stripos($agent,')'));
					
				$parse_arr = explode(";",$str_pr1);
				 
				if(array_search(' Android 6.0.1',$parse_arr) !== false)
				{
					$this->forbidden();
				}
			}
			
			
		}
		
		private function forbidden()
		{
			
			header('HTTP/1.0 403 Forbidden', true, 403);
			die("No access to bots");
			exit;
		}
		
		private function setSessionStart()
		{
			Session::init();
			if(isset($_SERVER['HTTP_REFERER']))
			{
				!Session::get('referral') ? Session::set('referral',$_SERVER['HTTP_REFERER']) : NULL; 
			}
			!Session::get('client_id') ? Session::set('client_id',strtotime(date('Y-m-d H:i:s'))) : NULL; 
		}
		
		private function urlParse($parse = false) {
			 
			$protokol = 'http';
			
			if ($_SERVER["SERVER_PORT"] == 443) $protokol .= "s";
			
			$pageURL = $protokol."://".$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			 
			 if($parse)
			 {
				$host = parse_url($pageURL);
				
				$domen =  explode('.',$_SERVER["SERVER_NAME"]);
				
				switch(count($domen)) 
				{
					case 3 :
						$zone =  $domen[2];
						$sub =  $domen[0];
					break;
					case 2 :
						$zone =  $domen[1];
						$sub =  null;
					break;
				}
				
				$ext = pathinfo( parse_url($pageURL, PHP_URL_PATH) , PATHINFO_EXTENSION);
				$end = pathinfo( parse_url($pageURL, PHP_URL_PATH) , PATHINFO_FILENAME);
				if (isset($_GET['url'])) $exp = explode('/', $_GET['url'] );
				
				if($ext == "html")
				{
					foreach($exp as $k => $v)
					{
						$exp[$k] = str_ireplace(".html","",$v);
					}
				}
				
				$exts_arr = array(".","/",'');		
				
				if ( array_search( substr ($host['path'], -1),$exts_arr ) !== false)
				{
					$host['path'] = substr($host['path'],0,-1);
					
					if(!end($exp)) 
					{
						array_pop($exp);
					}
				}
				
				$html = $this->getExtInBase();
				
				if(!isset($host['query'])) $host['query'] = false;
				
				$res  = (object) array( 	
								'protokol' => $host['scheme'],
								'subdomain' => $sub,
								'zone' => $zone,
								'path' => $host['path'],
								'query' => $host['query'],
								'exp' => $exp,
								'ext' => $ext,
								'end' => $end,
								"redirect" => $html
					  );
			 }
			 else 
			 {
			  $res =  $pageURL;
			 }
			 
			  return $res;

		}
		
		// debug show result function
		
		private function d($data){
	
			echo "<pre>";
			
			print_r($data);
			
			exit;
		  
		}
		
		// redirect params: status, url 
		
		private function r ($status,$url) {
			header('Location: '.$url,true,$status);
		}
  
  
  }
  
  
?>