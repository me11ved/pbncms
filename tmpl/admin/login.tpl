<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Страница авторизации</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="https://fonts.googleapis.com/css?family=Exo+2|Exo|Bad+Script" rel="stylesheet">
<script src='https://www.google.com/recaptcha/api.js'></script>
<link rel="stylesheet" href="/public/css/admin.css" type="text/css" media="all">

 </head>
  <div id="ress" class="ress" style="display: none;">Заполните все поля!</div>
<body class="body_login"> 
  <div class="vhod_login">
		<div class="vhod_content">
			<p>Вход</p>
			<form action="/login/run" method="post"name='authfrom'>
            <input name="usrlogin" type="text" placeholder="pole1" style=" padding: 7px; width: 100%; "></br></br>
            <input name="usrpass" type="password" placeholder="pole2" style=" padding: 7px; width: 100%; "> </br>
			</br>
			<?php if (CAPTCHA) : ?>
			<div class="g-recaptcha" data-sitekey="6Ld-Bm4UAAAAAI33sKz_7aW0XIESdpR7ai5JypBp"></div>
			<?php endif; ?>
			</br>
			<input type="submit" value="Войти &#8594;" id="cl" style=" padding: 7px; width: 100%; ">
			</form>
  
	</div> 
</div>

</body>
</html>

