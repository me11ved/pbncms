<?php
  class Page extends Controller {	  
    
	public function __construct() 
	{
		parent::__construct();
	}	
	
	/* page data :: [all page] */
	
	public function pageLoad($argument = false) {
		
		$check = $this->model->checkPage($argument);
		
		if($check["status"])
		{
				$this->model->readCache($argument); // Кэш
				
				$this->view->menu =	$this->model->menuList(); // Список меню
				
				$this->view->meta_contacts = $this->model->metaContacts($argument,$check['data']['id']); // Мета данные
				$this->view->script_static = $this->model->scriptStatic(); // Статические скрипты
				
				$this->view->breadCrumbs =	$this->model->breadCrumbs($argument,$check); // Хлебные крошки
				
				
				$params = $this->view->get_params($check['type'],$check['data']); //Параметры из шаблона
				$params != NULL ? $this->view->query_post = $this->model->queryPost($params,NULL,$check['data']) : NULL; //Данные по параметрам
				
				
				$this->view->data =	$this->model->{"page".$check['type']}($check['data']['id'],$argument); 
				
				$this->view->render($check['type']);
				
				$this->model->writeCache($argument); // Кэш
			
		}
		else
		{
			if($check["subdomain"])
			{
				$this->pageStop($argument);
			}
			else
			{
				$this->pageError($argument);
			}
		}
	}
	
	/* sitemap xml */
	
	public function sitemap($args) 
	{
		
		if ($this->model->sitemap($args) === false)
		{
			$this->pageStop();
		}
	}
	
	
	/* robots txt */
	public function robots($args) 
	{
		
		if ($this->model->robots($args) === false)
		{
			$this->pageStop();	
		}
	}
	
	/* rss  */
	
	public function rss() {
		
		$this->view->data = $this->model->createRss();
			
		header ("content-type: text/xml");
		$this->view->render('rss_xml',false,true);
		
	
	}
	
	public function pageStop()
	{
		
		header('HTTP/1.0 404 Not Found');

		echo '<h1>404 Not Found</h1>';
		
		exit;
	}
	
	/* page error/404 data */
	
	public function pageError($argument = false){
		
		header("http/1.0 404 Not Found"); 
		
		$this->cache->read(); // cache
			
		$this->view->data =	array(	
									'title' => 'Страницы не существует',
									'meta_title' => 'Страницы не существует',
									'meta_description' => 'Страницы не существует');
			
			
		$params = $this->view->get_params('404');
		$params != NULL ? $this->view->query_post = $this->model->queryPost($params) : NULL;
			
		$this->view->menu =	$this->model->menuList();
		$this->view->script_static =	$this->model->scriptStatic();
		$this->view->meta_contacts =	$this->model->metaContacts($argument);
			
		$this->view->render('404');
		
		$this->cache->white(); // cache
		
		$this->view->render('scripts',false,true); // dinamic scripts
		
	}
	
	public function redirectCheck($args = false)
	{
		$res = $this->model->checkOldUrl($args);
		
		return $res;
	}
		
	
	private function debug($data){
	
	echo "<pre>";
	
	print_r($data);
	
	exit;
	
	
	
	
  
  }
	
}	
?>