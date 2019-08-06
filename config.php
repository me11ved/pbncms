<?php
  	
	date_default_timezone_set('Europe/Moscow');
	
	if(!defined('URL')) define('URL', 'https://'.$_SERVER['HTTP_HOST']);
	if(!defined('JS')) define('JS','/public/js/');
	if(!defined('CSS')) define('CSS','/public/css/');
	if(!defined('IMG')) define('IMG','/public/images/');
	if(!defined('HREF')) define('HREF', 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	
	if(!defined('TEMPLATE')) define('TEMPLATE','wiki');

	if(!defined('DB_USER')) define('DB_USER','root');
	if(!defined('DB_PASSWORD')) define('DB_PASSWORD','');
	if(!defined('DB_NAME')) define('DB_NAME','cms.v2');
	if(!defined('DB_HOST')) define('DB_HOST','localhost');
    if(!defined('DB_CHARSET')) define('DB_CHARSET','utf8');
	if(!defined('ROOT_PATH')) define('ROOT_PATH',$_SERVER['DOCUMENT_ROOT']);
	
	//Управление кэшом
	if(!defined('CACHE')) define('CACHE',false);
	//Время жизни кэша в сек. - раз в [ день = 86400, неделя = 604800, месяц = 2592000]
	if(!defined('CACHE_TIME')) define('CACHE_TIME',604800);
	//Включить добавление html вконце урла
	if(!defined('CHECK_HTML')) define('CHECK_HTML',true);

	if(!defined('CAPTCHA')) define('CAPTCHA',false);

	//telegram
	
	// if(!defined('TM_API_TOKEN')) define('TM_API_TOKEN','545267421:AAFDTXV6fSJDHrooeFjYh-NypVHLPMxinUk');
	// if(!defined('TM_API_GROUP')) define('TM_API_GROUP','-274249073');
	// if(!defined('TM_PROXY')) define('TM_PROXY',"telega1:telega@31.40.32.10:1111");
	// if(!defined('TM_SEND')) define('TM_SEND',true);
	
	//bitrix24
	if(!defined('B24_ASSIGNED')) define('B24_ASSIGNED',67);
	if(!defined('B24_SEND')) define('B24_SEND',true);
	
	
	
	//Yandex metrika
	
	// if(!defined('METRIKA_API_TOKEN')) define('METRIKA_API_TOKEN','AQAAAAAiETXzAATAaHZwCYCp4kfelloBQUlmR38');
	// if(!defined('METRIKA_API_ID')) define('METRIKA_API_ID','46808703');
	
?>