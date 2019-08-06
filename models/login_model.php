<?php 
  class Login_Model extends Model {
   public function __construct() {
		parent::__construct();
	}
	
	public function auth() {
		
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$recaptcha= $_POST['g-recaptcha-response'];
			
			
			if(!empty($recaptcha) OR CAPTCHA === false)
			{
				if(CAPTCHA) $captha = $this->CheckCaptchaGoogle($recaptcha); 
				
				if($captha['success'] OR CAPTCHA === false)
				{ 
					if(!empty($_POST['usrlogin']) AND !empty($_POST['usrpass'])) 
					{
						
						$user = md5($_POST['usrlogin']);
						
						$password = md5($_POST['usrpass']);
					
						$query = $this->db->GetRow("SELECT 	u.id,
															u.role,
															u.name
														FROM 
															users u 
														WHERE 
															u.login = ?s 
														AND 
															u.password = ?s 
														AND 
															u.active = 1",
														$user,
														$password);
						
						if($query) 
						{
							Session::init();
							
							Session::set('loggedIn', true);
							Session::set('sid1',$query['id']);
							Session::set('sid2',$query['role']);
							Session::set('sid3',$query['name']);
							
							$this->db->query("UPDATE users 
															SET 
																last_in = NOW() 
															WHERE
																id = ?i",$query["id"]);
							
							header('Location: /admin');
						} 
						else
						{
							
							header("Location: /");
						}
					}
					else 
					{
							
							header("Location: /");
					}
			}
			else 
			{
				
				header("Location: /");
				
			} 
		}
		else 
			{
				
				header("Location: /");
				
			}
		}
	}
	
  
	private function CheckCaptchaGoogle($recaptcha){
			
				$google_url = "https://www.google.com/recaptcha/api/siteverify";
				
				$secret = '6Lf6QzEUAAAAANTBFX81mq7xpgVQq_FJTeqY4lBF';
				
				$ip = $_SERVER['REMOTE_ADDR'];
				
				$params = [	"secret" => $secret,
							"response" => $recaptcha,
							"remoteip" => $ip]; 
				
			
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $google_url);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_TIMEOUT, 10);
			curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		
			$curlData = curl_exec($curl);
			
			//if($curlData === false) echo 'Ошибка curl: ' . curl_error($curl);
			
			$res = json_decode($curlData, true);
			
			curl_close($curl);
			
			return $res;
					
		
	}
}	

?>