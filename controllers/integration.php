<?php 
class Integration extends Controller {	  
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
	
	
	public function webMaster() 
	{ 
	
		$this->view->result = $this->model->webMasterIndex();
			
		$this->view->render('integration/webmaster',true);
		
	}
	
	public function webMasterSetting() 
	{
		
		$data = $_POST;
		
		if (isset($_GET["code"])) $data["code"] = $_GET["code"];
		
		$this->view->result = $this->model->webMasterGetSetting($data);
		
		$this->view->render('integration/webmaster',true);
	
	}
	
	public function webMasterSaveSetting() 
	{
		if (!empty($_POST)) 
		{
			
			$data = $_POST;
			
			$res = $this->model->webMasterSaveSetting($data);
			
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
	
	public function webMasterLinks() {
		
		$this->view->data = $this->model->setting_links_webMaster();
		
		
		$this->view->render('webmaster_links',true);
		
	}
	
	public function webMasterSettingText() {
		
		$this->view->data = $this->model->setting_textList_webMaster();
		
		
		$this->view->render('webmaster_text',true);
		
	}
	
	public function webMasterDelText() {
		
		$list = $_POST['list'];
		$this->model->setting_textRemove_webMaster($list);
		
	}
	
	public function webMasterAddText() {
		$text = $_POST['text'];
		$this->model->setting_textAdd_webMaster($text);
	}
	
	public function webMasterAddTextPage() {
		$list = $_POST['list'];
		$this->model->text_importpage($list);
	}
	
	public function webMasterUpText(){
		
		$this->model->text_webmaster_uploadfile();
	}
	
	public function webMasterUpTextid() {
			$this->model->update_idtextList_webMaster();
	}
	
	/* 02.11.2017 */
	public function webMasterSitemap() {
		
		$this->view->data = $this->model->setting_sitemap_webMaster();
		
		
		$this->view->render('webmaster_sitemap',true);
		
	}
	
	/* 15.06.2018 */
	
	public function bitrix() { 
			
			$this->view->data = $this->model->bitrix24_index();
			
			$this->view->render('bitrix24',true);
		
	}
	
	public function bitrixSetting() {
		
		if( !empty($_POST) ) {
		
			$data = $_POST;
			$this->model->bitrix24_setting($data);
			$this->model->bitrix24_token();
		}
		else {
			$this->model->bitrix24_token();
		}
		$this->view->data = $this->model->bitrix24_index();
			
		$this->view->render('bitrix24',true);
	}
	
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