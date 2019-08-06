<?php if(Session::get('loggedIn') == true): ?> 

<html>
<head>
	<link rel="shortcut icon" href="/public/images/admin/adminfavicon.png" type="image/png"> 
	
	<!-- bootstrap 4.1.3 -->
	<link rel="stylesheet" href="/public/css/admin/bootstrap.css">
	<!-- dataTables.bootstrap4 1.10.19 -->
	<link rel="stylesheet" href="/public/css/admin/dataTables.bootstrap4.min.css">
	<!-- Font Awesome 4.7.0 -->
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<!-- default style -->
	<link rel="stylesheet" href="/public/css/admin/admin.css" type="text/css" media="all">
	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600,700,700" rel="stylesheet">
	<!-- hierarchy Bootstrap -->
	<link href="/public/css/admin/hierarchy-select.min.css" rel="stylesheet">
	
	<!-- bootstrap 4.1.3 -->
	<script src="/public/js/admin/jquery-3.3.1.js"></script>
	<script src="/public/js/admin/popper.min.js"></script>
	<script src="/public/js/admin/bootstrap.min.js"></script>
	
	<!-- dataTables.bootstrap4 1.10.19 -->
	<script src="/public/js/admin/jquery.dataTables.min.js"></script>
	<script src="/public/js/admin/dataTables.bootstrap4.min.js"></script>
	
	<!-- hierarchy Bootstrap -->
	<script src="/public/js/admin/hierarchy-select.min.js"></script>
	
	<script src="/public/js/_apps/j.ui.js"></script>
	<script src="/public/js/_apps/j.nestable.js"></script>
	<script src="/public/js/_apps/app.js"></script>
	<script src="/public/js/d49e19c4cd5c864d73a25d56c80b3e1f.js"></script>
	
	<!-- ckeditor FULL 4.8.0 -->
	<script src="https://cdn.ckeditor.com/4.8.0/full/ckeditor.js"></script>
	
	<title><?=$this->title?></title>

</head>
<body>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-2 sidebar" style="background:#252e2f;">
		<div class="d-inline-block w-100 m-2">
			<a href="/" alt="PBNcms" class="logopbn">
				<div class="logoicon float-left"></div>
				<div class="float-left">
					<div>PBN</div>
					<div class="cms">cms</div>
				</div>
			</a>
		</div>
				<ul class="nav flex-column">
					<li class="nav-item">
						<a href="/admin/index" class="nav-link text-white">
						<i class="fa fa-home" aria-hidden="true"></i>&nbsp;&nbsp;
						Статистика</a>
					</li>
					<li class="nav-item">
						<a href="/admin/categoryIndex" class="nav-link text-white">
						<i class="fa fa-newspaper-o"></i>&nbsp;&nbsp;
						Категории</a>
					</li>
					<li class="nav-item">
						<a href="/admin/pageIndex" class="nav-link text-white">
						<i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;&nbsp;
						Страницы</a>
					</li>
					<?php if(array_search(Session::get('sid2'),["admin","webmaster"]) !== false): ?> 
					<li class="nav-item">
						<a href="/admin/pageReLink" class="nav-link text-white">
						<i class="fa fa-link" aria-hidden="true"></i>&nbsp;&nbsp;
						Перелинковка</a>
					</li>
					<?php endif; ?>
					<li class="nav-item">
						<a href="/admin/navigation" class="nav-link text-white">
						<i class="fa fa-bars" aria-hidden="true"></i>&nbsp;&nbsp;
						Навигация</a>
						
					</li>
					<?php if(array_search(Session::get('sid2'),["admin","webmaster"]) !== false): ?> 
					<li class="nav-item">
						<a href="/admin/tagManagerIndex/" class="nav-link text-white">
						<i class="fa fa-code" aria-hidden="true"></i>
						&nbsp;Тег менеджер</a>
					</li>
					<li class="nav-item">
						<a href="/admin/redirectIndex" class="nav-link text-white">
						<i class="fa fa-external-link" aria-hidden="true"></i>
						&nbsp;&nbsp;Редирект</a>
					</li>
					<li class="nav-item">
						<a href="/admin/geoIndex" class="nav-link text-white">
						<i class="fa fa-sitemap" aria-hidden="true"></i>
						&nbsp;&nbsp;
						Поддомены</a>
					</li>
					<?php endif; ?>
					<?php if (Session::get('sid2') == "admin") : ?>
					<li class="nav-item">
						<a href="/admin/settingBackup/" class="nav-link text-white">
						<i class="fa fa-copy" aria-hidden="true"></i>
						&nbsp;&nbsp;Резервная копия</a>
					</li>
					<li class="nav-item">
						<a href="/admin/settingSite/" class="nav-link text-white">
						<i class="fa fa-cog" aria-hidden="true"></i>
						&nbsp;&nbsp;Настройки</a>
					</li>
					<li class="nav-item">
						<a href="/admin/usersIndex/" class="nav-link text-white">
						<i class="fa fa-user" aria-hidden="true"></i>
									&nbsp;&nbsp;Пользователи</a>
					</li>
					<?php endif; ?>
					<li class="nav-item text-white">
						<a href="javascript:void(0)" class="nav-link text-white">
						<i class="fa fa-plug" aria-hidden="true"></i>&nbsp;&nbsp;Интеграции</a>
						<ul class="nav">
							<li class="nav-item"><a href="/integration/webmaster/" class="nav-link text-white"><i class="fab fa-yandex"></i> Яндекс.Вебмастер</a>
							</li>
						</ul>
						<ul class="nav">
							<li>
								<a href="/integration/bitrix/" class="nav-link text-white">Битрикс24</a>
							</li>
						</ul>
					</li>
				</ul>		


	<div  class="position-absolute" style="bottom: 1%; font-size: 11px; text-align: center; left: 0; right: 0;">
		<span class="text-center text-white" style=" margin: 7px; "><?=Session::get('sid3')?></span>
		<a href="/admin/logout/" title="Выход" class="text-white">
			<i class="fa fa-sign-out" aria-hidden="true"></i>
		</a>
	</div>
	
	</div>
    <div class="col-md-10 offset-2">
<?php endif; ?>