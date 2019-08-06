<?php if(Session::get('loggedIn') == true): ?> 

<!-- Modal Window Zone-->
<div class="modal fade" id="AddModalZ" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title">Новая зона</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	  </div>
	<form action="javascript:void(0);" name='data'>
		<div class="modal-body">
			<div class="alert" id="alert" role="alert" style="display:none;"></div>	
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Домменая зона</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="ru" name="name">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Телефон</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="" name="telephone">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="" name="email">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Адрес</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="" name="address">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Время работы</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="" name="worktime">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Слова для замены</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="" name="world_replace">
					</div>
				</div>
				
				<div class="input-group mb-3">
				  <div class="input-group-prepend">
					<label class="input-group-text" for="inputGroupSelect01">Статус</label>
				  </div>
				  <select class="custom-select" id="inputGroupSelect01" name="public">
					<option selected value="1">Включен</option>
					<option value="0">Отключен</option>
				  </select>
				</div>
				
				<div class="input-group mb-3">
				  <div class="input-group-prepend">
					<label class="input-group-text" for="inputGroupSelect01">По умолчанию</label>
				  </div>
				  <select class="custom-select" id="inputGroupSelect01" name="default">
					<option value="0" selected="selected">Нет</option>
					<option value="1">Да</option>
				  </select>
				</div>
				
				<div class="form-group listsub">
					<label for="exampleFormControlTextarea1">Список поддоменов (лат.)</label>
					<textarea class="form-control" id="description" rows="3" name="subdomain" placeholder="vladimir
msk
spb"></textarea>
				</div>
					
			</div>
			<div class="modal-footer">
				<input type="submit" class="btn btn-primary" app-method="add_zone" value="Добавить">
				<div class="btn btn-secondary clearPopup" title="Очистить поля для ввода">Очистить</div>
			</div>
			</form>
		</div>
	</div>
</div>	
<!-- Modal Window Zone-->

<!-- Modal Window Subdomain-->
<div class="modal fade" id="AddModalS" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title">Новая поддомен</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	  </div>
	<form action="javascript:void(0);" name='data'>
		<div class="modal-body">
			<div class="alert" id="alert" role="alert" style="display:none;"></div>	
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Название</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="Владимир" name="name_ru">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">URL Адрес (лат.)</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="vladimir" name="name_en">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Телефон</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="" name="phone">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="" name="email">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Адрес</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="" name="adr">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Время работы</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="" name="time">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Слова для замены</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="" name="world_replace">
					</div>
				</div>
				
				<div class="input-group mb-3">
				  <div class="input-group-prepend">
					<label class="input-group-text" for="inputGroupSelect01">Домменая зона</label>
				  </div>
				  <select class="custom-select" id="inputGroupSelect01" name="id_zone">
				  </select>
				</div>
				
				<div class="input-group mb-3">
				  <div class="input-group-prepend">
					<label class="input-group-text" for="inputGroupSelect01">Статус</label>
				  </div>
				  <select class="custom-select" id="inputGroupSelect01" name="public">
					<option selected value="1">Включен</option>
					<option value="0">Отключен</option>
				  </select>
				</div>
				
			</div>
			<div class="modal-footer">
				<input type="submit" class="btn btn-primary" app-method="add_subdomen" value="Добавить">
				<div class="btn btn-secondary clearPopup" title="Очистить поля для ввода">Очистить</div>
			</div>
			</form>
		</div>
	</div>
</div>	
<!-- Modal Window Subdomain-->

<!-- Таблица результатов -->
<div class="container-fluid">	
	<div class="row">
		<div class="col-lg-12">
			<div id="header">
				<div id="title"><i class="fa fa-sitemap" aria-hidden="true"></i>
						&nbsp;&nbsp;
						Поддомены
				</div>
				<div class="selecttop">
					<div class="input-group mb-3">
					  <div class="input-group-prepend">
						<label class="input-group-text" for="inputGroupSelect01">Операции</label>
					  </div>
					  <select class="custom-select" id="inputGroupSelect01" name="functionsSelect">
							<option selected="selected" disabled>Действие</option>
							<optgroup label="Поддомены">
								<option value="sub1">Добавить</option>
								<option value="sub4">Редактировать</option>
								<optgroup label="Груп. операции с поддоменами">
									<option value="sub2">Отключить</option>
									<option value="sub5">Включить</option>
									<option value="sub3">Удалить</option>
								</optgroup>
							</optgroup>
							<optgroup label="Зоны">
								<option value="zone1">Добавить</option>
								<option value="zone4">Редактировать</option>
								<optgroup label="Груп. операции с зонами">
									<option value="zone2">Отключить</option>
									<option value="zone5">Включить</op	tion>
									<option value="zone3">Удалить</option>
								</optgroup>
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
		<!-- Поддомены -->
		<div class="col-lg-12">
			<ul class="nav nav-tabs" id="myTab" role="tablist">
			  <li class="nav-item">
				<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Поддомены</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Зоны</a>
			  </li>
			</ul>
			<div class="tab-content" id="myTabContent">
			 <br>
			  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
				<table cellpadding="0" cellspacing="0" border="0" id="tableContent" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th class="nosort"><h3><input type="checkbox" name="opt" class='allcheck'></th>
							<th class='head1'><h3>Название</th>
							<th class='head1'><h3>Домен</th>
							<th class='head1'><h3>Зона</th>
							<th class='head1'><h3>Телефон</th>
							<th class='head1'><h3>Email</th>
							<th class='head1'><h3>Адрес</th>
							<th class='head1'><h3>Время работы</th>
							<th class='head1'><h3>Статус</th>
						</tr>
					</thead>
					<tbody class="tbody">
							<?php if($this->data['subdomain']) : ?>
								<?php foreach($this->data['subdomain'] as $key => $val): ?>
									<tr data-id="<?php echo $val['id']; ?>">
										<td><input type="checkbox" value="<?=$val['id']?>" name="listpos[]"/></td>
										<td><?php echo $val['name_ru']; ?></td>
										<td>
											<a href="//<?=$val["name_en"].".".substr($_SERVER["SERVER_NAME"],0,stripos($_SERVER["SERVER_NAME"],".")) . "." . $val['zone'] ?>" target="_blank"><?=$val['name_en'] ?></a>
										</td>
										<td><?php echo $val['zone']; ?></td>
										<td><?php echo $val['phone']; ?></td>
										<td><?php echo $val['email']; ?></td>
										<td><?php echo $val['adr']; ?></td>
										<td><?php echo $val['time']; ?></td>
										<td>
											<?php if($val['public']) : ?>
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
			<!-- Зоны -->
				<table cellpadding="0" cellspacing="0" border="0" id="tableContent1" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th class="nosort"><h3><input type="checkbox" name="opt" class='allcheck'></th>
							<th>Название</th>
							<th>Телефон</th>
							<th>Email</th>
							<th>Адрес</th>
							<th>Время работы</th>
							<th>Кол-во поддоменов</th>
							<th>По умолчанию</th>
							<th>Статус</th>
							<th>Создан</th>
						</tr>
					</thead>
					<tbody class="tbody">
							<?php if($this->data['zone'][0]['name']) : ?>
								<?php foreach($this->data['zone'] as $key => $val): ?>
									<tr data-id="<?php echo $val['id']; ?>">
										<td><input type="checkbox" value="<?php echo $val['id']; ?>" name="listpos[]"/></td>
										<td>
											<a href="//<?=substr($_SERVER["SERVER_NAME"],0,stripos($_SERVER["SERVER_NAME"],".")) . "." . $val['name'] ?>" target="_blank"><?=$val['name'] ?></a>
										</td>
										<td><?php echo $val['telephone']; ?></td>
										<td><?php echo $val['email']; ?></td>
										<td><?php echo $val['address']; ?></td>
										<td><?php echo $val['worktime']; ?></td>
										<td><?php echo $val['sub']; ?></td>
										<td>
											<?php if($val['default']) : ?>
												<div class="on">&nbsp;</div>
											<?php endif ?>
										</td>
										<td>
											<?php if($val['public']) : ?>
												<div class="on">&nbsp;</div>
											<?php else : ?>
												<div class="off">&nbsp;</div>
											<?php endif ?>
										</td>
										<td><?php echo $val['create_date']; ?></td>
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
$(document).ready(function(){$('#tableContent1').DataTable({"language":{"url":"/public/js/admin/Russian.json"}})});
</script>	

<script type="text/javascript">
$(document).on("keyup","input[name=name_ru]",function(){

	if (window.edit_id == '' || window.edit_id == undefined) {
		var ru = $(this).val();

		if (ru.length >= 2)
		{
		var en = transliteUrl(ru);
		$("input[name=name_en]").val(en);
		}
	}
});
</script>	


<?php endif; ?>