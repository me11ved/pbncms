<?php if(Session::get('loggedIn') == true): ?> 
	<?php if(Session::get('sid2') == "admin"): ?> 

<!-- Таблица результатов -->
<form action="javascript:void(0);" name='settingSite'>
<div class="container-fluid">	
	<div class="row">
		<div class="col-lg-12">
			<div id="header">
				<div id="title"><i class="fa fa-cog" aria-hidden="true"></i>
									&nbsp;Настройки
				</div>
			</div>
			<br><br>			
			<!-- Вывод ошибок	 -->
			<div class="alert alert-success" id="alertBody" role="alert" style="display:none;"></div>		
			<br>
			<!-- Вывод ошибок	 -->
		</div>
	</div>
	<div class="row">
		<!-- Настройки -->
		<div class="col-lg-12">
			<ul class="nav nav-tabs" id="myTab" role="tablist">
			  <li class="nav-item">
				<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Сайт</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Заявки</a>
			  </li>
			</ul>
			<div class="tab-content" id="myTabContent">
			 <br>
			  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
					<div class="row">
						<div class="col-lg-6">
							<span class="namelist">
								<p>
									<i class="fa fa-link" aria-hidden="true"></i> Расширение <b>*.html</b> в конце адреса страницы</span>
								</p>
								<p id="descriptiontag">
									При активации опции на всех страницах сайт будет добавлено расширение html<br>
									- Пример адреса обычной страницы: <u>http://<?=$_SERVER['HTTP_HOST']?>/about</u><br>
									- Пример адреса при включении функции: <u>http://<?=$_SERVER['HTTP_HOST']?>/about.html</u> 
								</p>
							</span>
						</div>
						<div class="col-lg-6">
							<label class="switch">
										<?php if ($this->data["extredirect"]) : ?>
											<input type="checkbox" name="ext"  checked>
										<?php else : ?>
											<input type="checkbox" name="ext">
										<?php endif; ?>
									  <span class="slider"></span>
							</label>
						</div>
					</div>
					
					<div class="row">
						<div class="col-lg-6">
							 <hr>
							<p class="namelist"><i class="fa fa-clone" aria-hidden="true"></i> Кэширование</p>
							<p id="descriptiontag">
								Создает файл страницу, значительно ускоряет загрузку страницы без обращения к БД
							</p>
						</div>
						<div class="col-lg-6">
							 <hr>
							<label class="switch">
							<?php if ($this->data["cache"]["status"]) : ?>
								<input type="checkbox" name="cache"  checked >
							<?php else : ?>
								<input type="checkbox" name="cache">
							<?php endif; ?>
							  <span class="slider"></span>
							</label>
						</div>
					</div>
					
					<div class="row">
						<div class="col-lg-10">
							<span class="namelist">Время жизни кэша</span>
									<p id="descriptiontag">
										Данный параметр задает время хранения файла, после истечения срока - создается новый файл
									</p>
						</div>
						<div class="col-lg-2">
							<div class="input-group mb-3">
							 <select class="custom-select" id="inputGroupSelect01" name="cacheTime" <?php if ($this->data["cache"]["status"] == 0):?> disabled="disabled" <?endif; ?> >
								<option selected="selected" disabled>Выбрать время</option>
								<option value="86400"	
								<?php if ($this->data["cache"]["time"] == 86400) : ?>
									 selected="selected" 
								<?php endif; ?>
								
								>День</option>
								
								<option value="604800"
								<?php	if ($this->data["cache"]["time"] == 604800) : ?>
								 selected="selected" 
								<?php endif; ?> 
								 >Неделя</option>
								<option value="2592000"
								<?php if ($this->data["cache"]["time"] == 2592000) : ?>
									selected="selected" 
								<?php endif; ?>
								>Месяц</option>
								</select>
							</div>
						</div>
					</div>
									
					<div class="row">
						<div class="col-lg-9">
							 <hr>
							<span class="namelist"><i class="fa fa-sitemap" aria-hidden="true"></i> Сгенерировать 
									<a href="/sitemap.xml">sitemap.xml</a>
									</span>
									<p id="descriptiontag">
										Создает в корне сайта файл с актуальными ссылками для поискового робота
									</p>
									 <hr>
						</div>
						<div class="col-lg-3">
						 <hr>
						<div class="input-group mb-3">
							
						  <select class="custom-select" id="inputGroupSelect01" onchange="CreateSitemap(this);">
							<option selected="selected" disabled>Создать sitemap для:</option>
							<option value="all">Всех</option>
							<?php if ($this->data['zone']) : ?>
								<optgroup label="Зоны">
								<?php foreach ($this->data['zone'] as $zone) : ?>
									<option value="<?=$zone['fullname']?>"><?=$zone['fullname']?></option>
								<?php endforeach; ?>
								</optgroup>
							<?php endif; ?>
							
							<?php if ($this->data['sub']) : ?>
								<optgroup label="Поддомены">
								<?php foreach ($this->data['sub'] as $sub) : ?>
									<option value="<?=$sub['fullname']?>"><?=$sub['fullname']?></option>
								<?php endforeach; ?>
								</optgroup>
							<?php endif; ?>
					  </select>
						
					</div>
					 <hr>
				</div>
			</div>	
			</div>
			<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
			<!-- Заявки -->
					<div class="row">
						<div class="col-lg-6">
							<p>
								<span class="namelist"><i class="fa fa-at" aria-hidden="true"></i> Настройки почты</span>
									<label class="switch">
										<?php if ($this->data['mail']['email']["status"]) : ?>
											<input type="checkbox" name="emailStatus" checked>
										<?php else : ?>
											<input type="checkbox" name="emailStatus">
										<?php endif; ?>
										  <span class="slider"></span>
									</label>
							</p>
							<p id="descriptiontag">
								Позволяет отправить письмо с данными о заявке на указанную почту
							</p>
						</div>
						<div class="col-lg-6">
						
							<div class="input-group mb-3">
							  <div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">Логин</span>
							  </div>
							  <input type="text" class="form-control" aria-describedby="basic-addon1" name="emailLogin" placeholder="info@test.ru" value="<?=$this->data['mail']['email']['login']?>">
							</div>
							
							<div class="input-group mb-3">
							  <div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">Пароль</span>
							  </div>
							  <input type="text" class="form-control" aria-describedby="basic-addon1" name="emailPassword" >
							</div>
							
							<div class="input-group mb-3">
							  <div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">Отправлять на почту</span>
							  </div>
							  <input type="text" class="form-control" aria-describedby="basic-addon1" name="emailSend" placeholder="info@test.ru" value="<?=$this->data['mail']['email']['send']?>">
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-lg-6">
							 <hr>
							<p>
								<span class="namelist"><i class="fa fa-telegram" aria-hidden="true"></i> Отправка в Telegram</span>
								<label class="switch">
									<?php if ($this->data['telegram']['status']) : ?>
										<input type="checkbox" name="telegaSend" checked>
									<?php else : ?>
										<input type="checkbox" name="telegaSend">
									<?php endif; ?>
										  <span class="slider"></span>
								</label>
							</p>
							<p id="descriptiontag">
								Мы предусмотрели возможность отправки заявок не только на почту.<br>
								После настройки, все заявки будут дублироваться в мессенжер
								лично вам или в корпоративный чат.
								<br>
								<i class="fa fa-warning" aria-hidden="true"></i> Советуем добавить прокси - без него доставка сообщений не гарантируется
							</p>
						</div>
						<div class="col-lg-6">
							 <hr>
							 
							 <div class="input-group mb-3">
							  <div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">Токен бота</span>
							  </div>
							  <input type="text" class="form-control" aria-describedby="basic-addon1" name="telegaToken" placeholder="info@test.ru" value="<?=$this->data['telegram']['token']?>">
							</div>
							
							<div class="input-group mb-3">
							  <div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">Id чата/человека</span>
							  </div>
							  <input type="text" class="form-control" aria-describedby="basic-addon1" name="telegaSendId" placeholder="info@test.ru" value="<?=$this->data['telegram']['send']?>">
							</div>
							
							<div class="input-group mb-3">
							  <div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">Прокси</span>
							  </div>
							  <input type="text" class="form-control" aria-describedby="basic-addon1" name="telegaProxy" placeholder="info@test.ru" value="<?=$this->data['telegram']['proxy']?>">
							</div>
						</div>
					</div>
					
					<div class="row">
						
						<div class="col-lg-6">
							 <hr>
							<p>
								<span class="namelist"><i class="fa fa-phone" aria-hidden="true"></i> Сервис для проверки телефона <span class="tag2">phoneverify.org</span></span>
								<label class="switch">
									<?php if ($this->data['mail']['phoneverify']['status']) : ?>
										<input type="checkbox" name="PhoneChecknum" checked>
									<?php else : ?>
										<input type="checkbox" name="PhoneChecknum">
									<?php endif; ?>
									<span class="slider"></span>
								</label>
							</p>
							<p id="descriptiontag">
								Данный сервис позволяет определить геоданные по номеру телефона.
								<br>
								<i class="fa fa-warning" aria-hidden="true"></i> В бесплатной версии установлены ограничения – 25 запросов в 5 минут.
							</p>
						</div>
						<div class="col-lg-6">
							 <hr>
							 
							 <div class="input-group mb-3">
							  <div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">Логин</span>
							  </div>
							  <input type="text" class="form-control" aria-describedby="basic-addon1" name="PhoneChecklogin" placeholder="info@test.ru" value="<?=$this->data['mail']["phoneverify"]['login']?>">
							</div>
							
							<div class="input-group mb-3">
							  <div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">Пароль</span>
							  </div>
							  <input type="text" class="form-control" aria-describedby="basic-addon1" name="PhoneCheckpassword" value="">
							</div>
						</div>
					</div>								
			</div>
		</div>	
		</div>
	</div>
		<div class="row">
		<div class="col-lg-12">
			<input type="submit" class="btn btn-primary float-right" app-method="save_setting_site" value="Сохранить">
			 <div class="float-right">&nbsp;</div>&nbsp;&nbsp;<input type="submit" class="btn btn-secondary float-right" value="По умолчанию" onclick="SettingDefault();"> 
		</div>
		</div>
	</div>
</form>
	
	<script type="text/javascript">
		
		$(document).on("click","input[name=cache]",function(){
			var check = $(this).prop("checked");
			if (check == 0) $("select[name=cacheTime]").attr("disabled","disabled");
			else $("select[name=cacheTime]").removeAttr("disabled","disabled");
		});
		
		$(document).on("click","input[name=emailStatus]",function(){
			var check = $(this).prop("checked");
			if (check == 0) {
				$("input[name=emailLogin]").attr("readonly","readonly");
				$("input[name=emailPassword]").attr("readonly","readonly");
				$("input[name=emailSend]").attr("readonly","readonly");}
				
			else {
				$("input[name=emailLogin]").removeAttr("readonly");
				$("input[name=emailPassword]").removeAttr("readonly");
				$("input[name=emailSend]").removeAttr("readonly");}
		});
		
		$(document).on("click","input[name=telegaSend]",function(){
			var check = $(this).prop("checked");
			if (check == 0) {
				$("input[name=telegaToken]").attr("readonly","readonly");
				$("input[name=telegaSendId]").attr("readonly","readonly");
				$("input[name=telegaProxy]").attr("readonly","readonly");}
				
			else {
				$("input[name=telegaToken]").removeAttr("readonly");
				$("input[name=telegaSendId]").removeAttr("readonly");
				$("input[name=telegaProxy]").removeAttr("readonly");}
		});
		
		$(document).on("click","input[name=PhoneChecknum]",function(){
			var check = $(this).prop("checked");
			if (check == 0) {
				$("input[name=PhoneChecklogin]").attr("readonly","readonly");
				$("input[name=PhoneCheckpassword]").attr("readonly","readonly");}
				
			else {
				$("input[name=PhoneChecklogin]").removeAttr("readonly");
				$("input[name=PhoneCheckpassword]").removeAttr("readonly");}
		});	
	
	</script>

	<?php else: ?>
			<p>Нет доступа в данный раздел</p>
	<?php endif; ?>					
<?php endif; ?>