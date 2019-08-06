<?php if(Session::get('loggedIn') == true): ?> 
	<?php if(Session::get('sid2') == "admin"): ?> 

<!-- Modal Window-->
<div class="modal fade" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title">Новый пользователь</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	  </div>
	<form action="javascript:void(0);" name='data'>
		<div class="modal-body">
			<div class="alert" id="alert" role="alert" style="display:none;"></div>	
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Имя</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="Иван Программист" name="name">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Логин</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="ivan" name="login">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Пароль</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="" name="password">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Доступ к API</label>
					<div class="col-sm-10">
					  <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" name="api" style="width: 20px;height: 20px;">
					</div>
				</div>
				
				<div class="input-group mb-3">
				  <div class="input-group-prepend">
					<label class="input-group-text" for="inputGroupSelect01">Статус</label>
				  </div>
				  <select class="custom-select" id="inputGroupSelect01" name="active">
					<option selected value="1">Включен</option>
					<option value="0">Отключен</option>
				  </select>
				</div>
				
				<div class="input-group mb-3">
				  <div class="input-group-prepend">
					<label class="input-group-text" for="inputGroupSelect01">Права доступа</label>
				  </div>
				  <select class="custom-select" id="inputGroupSelect01" name="role">
					<option value="user" selected="selected">Пользователь</option>
					<option value="webmaster">Вебмастер</option>
					<option value="admin">Администратор</option>
				  </select>
				</div>
				
				
				
				<div class="form-group listsub">
					<label for="exampleFormControlTextarea1">Комментарий</label>
					<textarea class="form-control" id="description" rows="3" name="comment"></textarea>
				</div>
					
			</div>
			<div class="modal-footer">
				<input type="submit" class="btn btn-primary" app-method="add_user" value="Добавить">
				<div class="btn btn-secondary clearPopup" title="Очистить поля для ввода">Очистить</div>
			</div>
			</form>
		</div>
	</div>
</div>	
<!-- Modal Window-->
			
<!-- Таблица результатов -->
<div class="container-fluid">	
	<div class="row">
		<div class="col-lg-12">
			<div id="header">
				<div id="title"><i class="fa fa-user" aria-hidden="true"></i>
									&nbsp;Пользователи
				</div>
				<div class="selecttop">
					<div class="input-group mb-3">
					  <div class="input-group-prepend">
						<label class="input-group-text" for="inputGroupSelect01">Операции</label>
					  </div>
					  <select class="custom-select" id="inputGroupSelect01" name="functionsSelect">
							<option selected="selected" disabled>Действие</option>
							<option value="user1">Создать</option>
							<option value="user4">Редактировать</option>
							<optgroup label="Групповые операции">
								<option value="user2">Отключить</option>
								<option value="user5">Включить</option>
								<option value="user3">Удалить</option>
							</optgroup>
					  </select>
					</div>
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
		<!-- Юзеры -->
		<div class="col-lg-12">
			<ul class="nav nav-tabs" id="myTab" role="tablist">
			  <li class="nav-item">
				<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Пользователи</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Логи</a>
			  </li>
			</ul>
			<div class="tab-content" id="myTabContent">
			 <br>
			  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
				<table cellpadding="0" cellspacing="0" border="0" id="tableContent" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>
								<h3><input type="checkbox" name="opt" class='allcheck'></h3>
							</th>
							<th>Имя</th>
							<th>Роль</th>
							<th>Дата входа</th>
							<th>Комментарий</th>
							<th>API ключ</th>
							<th>Статус</th>
						</tr>
					</thead>
					<tbody class="tbody">
						<?php if($this->data["users"]) : ?>
							<?php foreach($this->data["users"] as $key => $val): ?>
								<tr data-id="<?=$val['id']?>">
									<td><input type="checkbox" value="<?=$val['id']?>" name="listpos[]"/></td>
									<td><?=$val['name']; ?></td>
									<td><?=$val['role']?></td>
									<td><?=$val['last_in']?></td>
									<td><?=$val['comment']?></td>
									<td><?=$val['api_key']?></td>
									<td>
										<?php if($val['active']) : ?>
											<div class="on">&nbsp;</div>
										<?php else : ?>
											<div class="off">&nbsp;</div>
										<?php endif ?>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>	
					</tbody>
				</table>
			</div>
			<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
			<!-- Логи -->
				<table cellpadding="0" cellspacing="0" border="0" id="tableContent1" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>Дата</th>
							<th>Действие</th>
							<th>Пользователь</th>
							<th>Интерфейс</th>
						</tr>
					</thead>
					<tbody class="tbody">
						<?php if($this->data["logs"]) : ?>
							<?php foreach($this->data["logs"] as $key => $val): ?>
								<tr>
									<td><?=$val['date']; ?></td>
									<td><?=$val['text']?></td>
									<td><?=$val['name']?></td>
									<td><?=$val['method']?></td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>	
						</tbody>
					</table>
				</div>
			</div>	
		</div>
	</div>
</div>							
<script type="text/javascript">
$(document).ready(function(){$('#tableContent').DataTable({"language":{"url":"/public/js/admin/Russian.json"}})});
$(document).ready(function(){$('#tableContent1').DataTable({ "pageLength": 25 , "ordering": false,"language":{"url":"/public/js/admin/Russian.json"}})});
</script>	

<script type="text/javascript">
$(document).on("keyup","input[name=name]",function(){

	if (window.edit_id == '' || window.edit_id == undefined) {
		var ru = $(this).val();

		if (ru.length >= 2)
		{
		var en = transliteUrl(ru);
		$("input[name=login]").val(en);
		}
	}
});
</script>	
								
						
				
		
		
	<?php else: ?>
			
			<p>Нет доступа в данный раздел</p>
			
	<?php endif; ?>		
			
<?php endif; ?>