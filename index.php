<?php 
	
	// ini_set('error_reporting', E_ALL); ini_set('display_errors', 1); ini_set('display_startup_errors', 1);
	
	if( isset($_GET['debug']) == 'show' ) debug();
	
	function debug()
	{
		echo "<pre>";
		ini_set('error_reporting', E_ALL); ini_set('display_errors', 1); ini_set('display_startup_errors', 1);
	}
	
	function autoLoader ($dir)
	{
		if ($objs = glob($dir."/*")) 
		{
			require_once 'config.php';	
			
			foreach($objs as $obj) 
			{
				if (is_dir($obj))
				{
					autoLoader($obj);
				}
				else
				{
					$ext = pathinfo( parse_url($obj, PHP_URL_PATH) , PATHINFO_EXTENSION);
					if($ext == 'php') require_once $obj;
				}
			}
		}
	}
	
	autoLoader('libs');
	
	$app = new Bootstrap();
  
?>