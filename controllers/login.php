<?php
  class Login extends Controller {
   
   public function __construct() {
	parent::__construct();
	}

  public function index() {
   $this->view->render('login',true,true);
  }
  
  public function run() {
  	
	$this->model->auth();
  }
  
}
?>