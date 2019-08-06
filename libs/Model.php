<?php

class Model {
   public function __construct() {		
	
	$opts = array(
 		'user'    => DB_USER,
 		'pass'    => DB_PASSWORD,
 		'db'      => DB_NAME,
		'host'	=> DB_HOST,
		'charset' => DB_CHARSET
	);
	
	$this->db = new sql($opts);	
	$this->bp = new Files();
	$this->parser = new Parser();
	$this->email = new SendMailSmtpClass();
	$this->mobile = new Mobile_Detect();
	
	$this->wmApi = new YandexWebMasterApi();
	$this->metApi = new MetrikaYandexApi();
	$this->telegramApi = new TelegaBot();
	$this->bt24 = new Bitrix24();
	
   }
  }
?>