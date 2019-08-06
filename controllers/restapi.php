<?php 

class RestApi extends Controller {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->apikey = NULL;
	}
	
	private function auth()
	{
		if( isset($_GET['key']) )
		{	
			$key = $_GET['key'];
		
			$res = $this->model->check_auth($key);
			
			if($res != 'success')
			{
				$this->return_json ("error", "user does not have access");
			}
			else 
			{
				$this->apikey = $key;
			}
		}
		else
		{
			$this->return_json ( "error" , "no parameters for authorization");
		}
	}
	
	private function return_json ( $status , $response) {
		
		if($status) {
				
			if(empty($response)) $response = null;
				
			$result = json_encode(	
									array('status' => $status,
										  'response' => $response
										),
										JSON_HEX_QUOT | JSON_HEX_TAG
								);	
			echo $result;		
			exit;
		} 
	}	

	public function keywords_get ()
	{
		$this->auth();
		$this->model->keywords_metrika();
	}
	
	public function subdomain_get ()
	{
		$this->auth();
		$this->model->get_list_sudomain();
	}
	
	public function zone_get ()
	{
		$this->auth();
		$this->model->get_list_zone();
	}
	
	
	public function backup_create()
	{
		$this->auth();
		
		$param = array();
		
		if(isset($_GET))
		{
			$param['apikey'] = $this->apikey;
			
			if( isset($_GET['files']) ) $param['files'] = $_GET['files'];
			if( isset($_GET['base']) ) $param['base'] = $_GET['base'];
			if( isset($_GET['download']) ) $param['download'] = $_GET['download'];
			if( isset($_GET['refresh']) ) $param['refresh'] = $_GET['refresh'];
			if( isset($_GET['exception']) ) $param['exception'] = $_GET['exception'];
			
		}			
		
		$response = $this->model->create_backup($param);
		
		$this->return_json( "success" , $response);
	}
	
	
	public function backup_get($id)
	{
		$this->auth();
		
		if( isset($id))
		{
			$res = $this->model->download_backup($id);
			
			if($res['status'] == "success")
			{
				$file = $res['link'];
				
				if(isset($file))
				{
					if (headers_sent($filename, $linenum)) 
					{
						$this->return_json( "error" , "HTTP header already sent in file:".$filename." line: ".$linenum);
					} 
					else 
					{
						if (!is_file($file)) 
						{
							header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
							$this->return_json( "error" , "File not found");
							
						} else if (!is_readable($file)) 
						{
							header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
							$this->return_json( "error" , "File not readable");
							
						} else 
						{
							$this->download($file);
							
						}
					}
				}
				else
				{
					$this->return_json( "error" , "File not found");
				}					
			}
		}
		else
		{
			$this->return_json( "error" , "Not ID to download");
		}
		
	}
	
	private function download($file)  {
		
		header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
		header("Content-Type: application/zip");
		header("Content-Transfer-Encoding: Binary");
		header("Content-Disposition: attachment; filename=\"".basename($file)."\"");
		header("Content-Length: ".filesize($file));
		
		readfile($file);
		exit;
    }
	
	public function orders() {
		$data = $_POST['data'];	
		$this->model->orders($data);
	}
	
	public function send() {
		$this->model->gosend();
	}
	
	public function vieworder($id) {
		
		$key = $_GET['key'];
		$data = $this->model->viewdata($id,$key);
		
		
		if(!$data) {
			echo "<h1>403 Forbidden</h1>";
			exit;
		}
		else {
			$this->view->data = $data;
			$this->view->render('order_show',true,true);
		}
		
	} 
	


}
?>