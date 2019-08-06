<?php 
class Admin extends Controller {	  
  public function __construct() {
	parent::__construct();
	
		Session::init();
		$logged = Session::get('loggedIn');
		
		if($logged == false) 
		{
			Session::destroy();
			sleep(0);
			header('Location: /login/');
			exit();
	  
		}
	}
	
	public function index() {
		
		$this->view->title = "Статистика";
		
		$this->view->pagel = $this->model->lastAddPage();
		$this->view->pagelu = $this->model->lastUpPage();
		$this->view->datastatic = $this->model->pagestatic();
		$this->view->ordersstatic = $this->model->ordersstatic();
		$this->view->data2 = $this->model->lastorders();
		$this->view->category = $this->model->category();
		
		$this->view->render('index',true);
	}
	
	
	public function logout() {
		Session::destroy();
		header('Location: /admin');
		exit();
	}
	
	public function pageListAll ($decode)
	{
		$res = $this->model->pageListAll();
		
		if(!empty($res['status']))
		{
			if($decode)
			{
				return $res["response"];
			}
			
			$this->returnJson($res['status'],$res['response'],true);
		}
		else
		{
			$this->returnJson("error","Неизвестная ошибка",true);
		}
	}
	
	public function pageIndex() {
		
		$this->view->title = "Страницы";
		
		$this->view->data = $this->model->pageIndex();
		$this->view->datastatic = $this->model->pageStatic();
		$this->view->category = $this->model->pageListAll(true);
		$this->view->lastred = $this->model->last_red_page();
		
		$this->view->render('page_index',true);
	}
	
	public function pageAdd() 
	{
		if (!empty($_POST)) 
		{
			
			$data = $_POST;
			
			$res = $this->model->pageAdd($data);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
		
	}
	
	public function pageSwitch ()
	{
		if (!empty($_POST)) {
			
			$data = $_POST['list'];
			$status = $_POST['status'];
			
			$res = $this->model->pageSwitch ( (object) array( "l" => $data, "s" => $status));
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
		
	}
	
	public function pageDel ()
	{
		if(!empty($_POST)) 
		{
			$ids = $_POST['ids'];
			
			$res = $this->model->pageDel($ids);
			
			if(!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}
			
		}	
	}
	
	public function pageEdit()
	{
		if (!empty($_POST['id'])) 
		{	
			$id = $_POST['id'];
			
			$res = $this->model->pageEdit($id);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
	}
	
	public function pageSaveEdit ()
	{
		if (!empty($_POST)) 
		{	
			$data = $_POST;
			
			$res = $this->model->pageSaveEdit($data);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
	
	}
	
	/* Управление страницами */
	/***************************/
	
	/***************************/
	/* Управление перелинковкой */
	/* Дата: 27.03.2019 */
	/* Методы:
		* pageReLink - Главная страница
		* pageReLinkAdd - Добавить пользователя
		* pageReLinkDel - Удалить пользователя
		* pageReLinkSwitch - Вкл/Отключить
	*/
	/***************************/
	
	public function pageReLink(){

		$this->view->title = "Перелинковка";
		
		$this->view->data = $this->model->pageReLinkIndex();
		
		$this->view->render('relink_index',true);
		
	}
	
	public function pageReLinkAdd() 
	{
		if (!empty($_POST)) 
		{
			
			$data = $_POST;
			
			$res = $this->model->pageReLinkAdd($data);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
		
	}
	
	public function pageReLinkDel ()
	{
		if(!empty($_POST)) 
		{
			$ids = $_POST['ids'];
			
			$res = $this->model->pageReLinkDel($ids);
			
			if(!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}
			
		}	
	}
	
	/***************************/
	
	public function pageCreateUp(){
		
		$this->model->page_create_upload();
	}
	
	
	public function pageMultiAdd() {
			$this->model->add_multi_page();
	}
	
	public function createurl() {
		
		$data = $_POST['data'];
		
		$this->model->create($data);
		
	}
	
	public function navigation ()
	{
		$this->view->title = "Навигация";
		
		$this->view->data = $this->model->navigationIndex();
		
		$this->view->render('menu_index',true);
		
	}
	
	public function navigationEdit ()
	{
		if(!empty($_POST)) 
		{
			$id = $_POST['id'];
			
			$res = $this->model->navigationEdit($id);
			
			if(!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}
			
		}	
	}
	
	public function navigationDel ()
	{
		if(!empty($_POST)) 
		{
			$ids = $_POST['ids'];
			
			$res = $this->model->navigationDel($ids);
			
			if(!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}
			
		}	
	}
	
	public function navigationSaveEdit ()
	{
		if(!empty($_POST)) 
		{
			$p = $_POST;
			
			$res = $this->model->navigationSaveEdit($p);
			
			if(!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}
			
		}
		
		
		
	}
	
	public function navigationSwitch ()
	{
		if (!empty($_POST)) {
			
			$data = $_POST['list'];
			$status = $_POST['status'];
			
			$res = $this->model->navigationSwitch ( (object) array( "l" => $data, "s" => $status));
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
		
	}
	
	
	public function navigationAdd () 
	{	
		if (!empty($_POST)) {
			
			$data = $_POST;
			
			$res = $this->model->navigationAdd($data);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
	}
	
	public function navigationGetCategory()
	{
		$res = $this->model->navigationGetCategory();
		
		if(!empty($res['status']))
		{
			$this->returnJson($res['status'],$res['response'],true);
		}
		else
		{
			$this->returnJson("error","Неизвестная ошибка",true);
		}
	
	}
	
	public function navigationGetProducts()
	{
		$id = $_POST['filter'];
		
		$res = $this->model->navigationGetProducts($id);
		
		if(!empty($res['status']))
		{
			$this->returnJson($res['status'],$res['response'],true);
		}
		else
		{
			$this->returnJson("error","Неизвестная ошибка",true);
		}
	
	}
	
	public function geoIndex()
	{
		
		$this->view->title = "Управление поддоменами";
		
		
		
		$this->view->data = $this->model->subDomain();
		
		$this->view->render('subdomain_index',true);
		
	}
	
	public function geoGetZone ()
	{
		
		$res = $this->model->geoGetZone();
		
		if(!empty($res['status']))
		{
			$this->returnJson($res['status'],$res['response'],true);
		}
		else
		{
			$this->returnJson("error","Неизвестная ошибка",true);
		}
		
	}
	
	public function geoAddZone () 
	{	
		if (!empty($_POST)) {
			
			$data = $_POST;
			
			$res = $this->model->geoAddZone($data);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
	}
	
	public function geoDelZone()
	{
		if (!empty($_POST['list'])) 
		{	
			$data = $_POST['list'];
			
			$res = $this->model->geoDelZone($data);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
	}
	
	public function geoEditZone()
	{
		if (!empty($_POST['id'])) 
		{	
			$id = $_POST['id'];
			
			$res = $this->model->geoEditZone($id);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
	}
	
	public function geoSaveEditZone()
	{
		if (!empty($_POST)) 
		{	
			$data = $_POST['d'];
			
			$res = $this->model->geoSaveEditZone($data);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
	}
	
	public function geoZoneSwitch ()
	{
		if (!empty($_POST)) {
			
			$data = $_POST['list'];
			$status = $_POST['status'];
			
			$res = $this->model->geoZoneSwitch($data,$status);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
		
	}
	
	public function geoAddSubdomain()
	{
		if (!empty($_POST)) 
		{
			
			$data = $_POST;
			
			$res = $this->model->geoAddSubdomain($data);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
	}
	
	public function geoDelSubdomain()
	{
		if (!empty($_POST['data'])) 
		{
			
			$data = $_POST['data'];
			
			$res = $this->model->geoDelSubdomain($data);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
	}
	
	public function geoEditSubdomain()
	{
		if (!empty($_POST['id'])) 
		{	
			$id = $_POST['id'];
			
			$res = $this->model->geoEditSubdomain($id);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
	}
	
	public function geoSaveEditSubdomain()
	{
		if (!empty($_POST)) 
		{	
			$data = $_POST['d'];
			
			$res = $this->model->geoSaveEditSubdomain($data);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
	}
	
	public function geoSwitchSubdomain ()
	{
		if (!empty($_POST)) {
			
			$data = $_POST['list'];
			$status = $_POST['status'];
			
			$res = $this->model->geoSwitchSubdomain($data,$status);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
		
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
	
	public function settingSite() 
	{
		
		$this->view->title = "Настройки сайта";
		
		$this->view->data = $this->model->settingSite();
		
		$this->view->render('setting_site',true);
	}
	
	public function settingSiteSave()
	{
		if (!empty($_POST)) 
		{	
			$res = $this->model->settingSiteSave($_POST);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
		
	}
	
	public function settingSiteСreateSitemap()
	{
		if (!empty($_POST)) 
		{	
			$res = $this->model->settingSiteСreateSitemap($_POST);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
		
	}
	
	public function settingBackup()
	{
		$this->view->title = "Резервные копии";
			
		$this->view->data = $this->model->settingBackups();
		
		$this->view->render('backup_index',true);
	}
	
	public function settingBackupGet ($link)
	{
		$d = $this->model->settingBackupGet ($link);
		
		if($d['status'] == 'success')
		{
			$this->model->settingDownloadFile($d['link']);
		}
		else
		{
			$this->view->data = 'error'; 
			$this->view->render('setting_backup',true);
		}
		
	}
	
	public function settingBackupCreate() {
		
		if(!empty($_POST)) 
		{
			$res = $this->model->settingBackupCreate($_POST);
			
			if(!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}
			
		}
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
		$this->view->title = "Тег менеджер";
		
		$this->view->data = $this->model->tagManagerIndex();
		
		$this->view->render('tagmanager_index',true);
	}
	
	
	public function tagManagerAdd () 
	{	
		if (!empty($_POST)) {
			
			$data = $_POST;
			
			$res = $this->model->tagManagerAdd($data);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
	}
	
	public function tagManagerDel ()
	{
		if (!empty($_POST)) {
			
			$data = $_POST['list'];
			
			$res = $this->model->tagManagerDel($data);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
		
	}
	
	public function tagManagerSwitch ()
	{
		if (!empty($_POST)) {
			
			$data = $_POST['list'];
			$status = $_POST['status'];
			
			$res = $this->model->tagManagerSwitch($data,$status);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
		
	}
	
	public function tagManagerEdit ()
	{
		if (!empty($_POST)) {
			
			$data = $_POST['list'];
			
			$res = $this->model->tagManagerEdit($data);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
		
	}
	
	public function tagManagerSaveEdit () 
	{	
		if (!empty($_POST)) {
			
			$data = $_POST;
			
			$res = $this->model->tagManagerSaveEdit($data);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
	}
	
	/* Управление тег менеджером */
	/***************************/
	
	public function redirectIndex ()
	{
		$this->view->title = "Управление редиректами";
		
		
		
		$this->view->data = $this->model->redirectIndex();
		
		$this->view->render('redirect',true);
		
	}
	
	public function redirectAdd () 
	{	
		if (!empty($_POST)) {
			
			$data = $_POST;
			
			$res = $this->model->redirectAdd($data);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
	}
	
	public function redirectSwitch ()
	{
		if (!empty($_POST)) {
			
			$data = $_POST['list'];
			$status = $_POST['status'];
			
			$res = $this->model->redirectSwitch ( (object) array( "l" => $data, "s" => $status));
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
		
	}
	
	public function redirectDel ()
	{
		if (!empty($_POST)) {
			
			$data = $_POST['list'];
			
			$res = $this->model->redirectDel($data);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
		
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
	
	public function categoryIndex() { 
	
		$this->view->title = "Категории";
		
		$this->view->data = $this->model->categoryListAll();
		
		$this->view->render('page_category',true);
	
	}
	
	public function catAdd() { 
	
		if (!empty($_POST)) 
		{
			
			$data = $_POST;
			
			$res = $this->model->catAdd($data);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
	}
	
	public function catDel ()
	{
		if(!empty($_POST)) 
		{
			$ids = $_POST['ids'];
			
			$res = $this->model->catDel($ids);
			
			if(!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}
			
		}	
	}
	
	public function catEdit()
	{
		if (!empty($_POST['id'])) 
		{	
			$id = $_POST['id'];
			
			$res = $this->model->catEdit($id);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
	}
	
	public function catSaveEdit ()
	{
		if (!empty($_POST)) 
		{	
			$data = $_POST;
			
			$res = $this->model->catSaveEdit($data);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
	
	}
	
	public function catSwitch ()
	{
		if (!empty($_POST)) {
			
			$data = $_POST['list'];
			$status = $_POST['status'];
			
			$res = $this->model->catSwitch ( (object) array( "l" => $data, "s" => $status));
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
		
	}
	
	/* Управление категориями */
	/***************************/
	
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
		$this->view->title = "Пользователи";
		
		$this->view->data = $this->model->usersIndex();
		
		$this->view->render('users_index',true);
	}
	
	public function usersDel ()
	{
		if(!empty($_POST)) 
		{
			$ids = $_POST['ids'];
			
			$res = $this->model->usersDel($ids);
			
			if(!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}
			
		}	
	}
	
	public function usersAdd() 
	{
		if (!empty($_POST)) 
		{
			
			$data = $_POST;
			
			$res = $this->model->usersAdd($data);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
		
	}
	
	public function usersSwitch ()
	{
		if (!empty($_POST)) {
			
			$data = $_POST['list'];
			$status = $_POST['status'];
			
			$res = $this->model->usersSwitch ( (object) array( "l" => $data, "s" => $status));
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
		
	}
	
	public function usersEdit()
	{
		if (!empty($_POST['id'])) 
		{	
			$id = $_POST['id'];
			
			$res = $this->model->usersEdit($id);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
	}
	
	public function usersSaveEdit ()
	{
		if (!empty($_POST)) 
		{	
			$data = $_POST;
			
			$res = $this->model->usersSaveEdit($data);
			
			if (!empty($res['status']))
			{
				$this->returnJson($res['status'],$res['response'],true);
			}
			else
			{
				$this->returnJson("error","Неизвестная ошибка",true);
			}	
		}
	
	}
	
	/* Управление пользователями */
	/***************************/
	
	/* Для вызова отладки кода */
	/* Добавлена: 09.10.2018 */
	
	private function debug($data)
	{
		echo "<pre>";
		
		print_r($data);
		
		echo "</pre>";
		
		exit;
	}
	
	/* Вывод результата в JSON */
	/* Добавлена: 09.10.2018 */
	
	private function returnJson ( $status , $response, $show = false) {
		
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

}
?>