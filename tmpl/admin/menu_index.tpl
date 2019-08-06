<?php if(Session::get('loggedIn') == true): ?> 

<!-- Modal Window Add-->
<div class="modal fade" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title">Новое меню / Шаг 1</h5>
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
					  <input type="text" class="form-control" id="inputEmail3" placeholder="" name="name">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Тег</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="" name="name_en">
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
					
				</div>
			<div class="modal-footer">
				<input type="submit" class="btn btn-primary" app-method="add_nav" value="Далее">
				<div class="btn btn-secondary clearPopup" title="Очистить поля для ввода">Очистить</div>
			</div>
		</form>
	</div>
</div>
</div>	
<!-- Modal Window Add-->

<!-- Modal Window Edit-->

<div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title">Редактировать меню</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	  </div>
	<form action="javascript:void(0);" name='data'>
		<div class="modal-body">
			<div class="alert" id="alert" role="alert" style="display:none;"></div>	
			<ul class="nav nav-tabs" id="myTab" role="tablist">
			  <li class="nav-item">
				<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">Настройки</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Пункты</a>
			  </li>
			</ul>
			<div class="tab-content" id="myTabContent">
				<br>
			  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Название</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="" name="name">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Тег</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="" name="name_en">
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
				
			  </div>
			  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
					
				<div class="form-group row">
					<label for="exampleInputEmail1" class="col-sm-2">Список</label>
					<div class="dropdown hierarchy-select" id="Mlist">
						<button type="button" class="btn dropdown-toggle" id="example-one-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
						<div class="dropdown-menu" aria-labelledby="example-one-button">
							<div class="hs-searchbox">
								<input type="text" class="form-control" autocomplete="off">
							</div>
							<div class="hs-menu-inner">
								<a class="dropdown-item" data-value="" data-level="1" data-default-selected="" href="#">Все страницы</a>
							</div>
						</div>
						<input class="d-none" name="id_page" readonly="readonly" aria-hidden="true" type="text"/>
					</div>
				</div>
					<div class="form-group row">
							<label for="inputEmail3" class="col-sm-2 col-form-label">Новый пункт</label>
							<div class="col-sm-3">
							  <input type="text" class="form-control" id="inputEmail3" placeholder="Заголовок" name="newTitle">
							</div>
							<div class="col-sm-3">
							  <input type="text" class="form-control" id="inputEmail3" placeholder="Ссылка" name="newUrl">
							</div>
							<div class="btn btn-primary clearPopup col-sm-2" title="Добавить произвольный пунтк в меню" onclick="addNewNavPoint();">Добавить</div>
					</div>	
					<div class="dblock w100 zoneCreateMenu">
						<div id="sortable1" class="dd w50 fl dblock">
							<ol class="dd-list"></ol>
						</div>
						<div class="dd w50 fl dblock dd-dragzone2" id="sortable2">
							<ol class="dd-list" id="dd-empty-element">
								<li class="dd-item">
								</li>				
							</ol>
						</div>	
					</div>
					
			  </div>
			
			</div>
		</div>
		<div class="modal-footer">
			<input type="submit" class="btn btn-primary" app-method="add_cat" value="Добавить">
			<div class="btn btn-secondary clearPopup" title="Очистить поля для ввода">Очистить</div>
		</div>
	</form>
	</div>
</div>
</div>	

<!-- Modal Window Edit-->

<!-- Таблица результатов -->
<div class="container-fluid">	
			<div class="row">
				<div class="col-lg-12">
				<div id="header">
					<div id="title"><i class="fa fa-bars" aria-hidden="true"></i>&nbsp;Навигация (<span id='countsearch'><?php echo count($this->data); ?></span>)
					</div>
					<div class="selecttop">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<label class="input-group-text" for="inputGroupSelect01">Операции</label>
						  </div>
						  <select class="custom-select" id="inputGroupSelect01" name="functionsSelect">
								<option selected="selected" disabled>Действие</option>
								<optgroup label="Меню">
									<option value="nav1">Создать</option>
									<option value="nav2">Редактировать</option>
								</optgroup>
								<optgroup label="Групповые операции">
									<option value="nav3">Отключить</option>
									<option value="nav4">Включить</option>
									<option value="nav5">Удалить</option>
								</optgroup>
						  </select>
						</div>
					</div>
				</div>
				<br><br>			
			<!-- Вывод ошибок	 -->
				<div class="alert alert-success" id="alertBody" role="alert" style="display:none;">
				</div>		
				<br>
			<!-- Вывод ошибок	 -->	
			<table cellpadding="0" cellspacing="0" border="0" id="tableContent" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th><input type="checkbox" name="opt" class='allcheck'></th>
						<th>Название</th>
						<th>Тег для шаблона</th>
						<th>Кол-во пунктов</th>
						<th>Состояние</th>										
					</tr>
				</thead>
				<tbody class="tbody">
					<?php if($this->data) : ?>
						<?php foreach($this->data as $key => $val): ?>
							<tr data-id="<?=$val['id']?>">
								<td><input type="checkbox" value="<?=$val['id']?>" name="listpos[]"/></td>
								<td><?=$val['name']; ?></td>
								<td>{<?=$val['name_en']?>}</td>
								<td><?=$val['point']?></td>
								<td>
											<?php if($val['public']) : ?>
												<div class="on">&nbsp;</div>
											<?php else : ?>
												<div class="off">&nbsp;</div>
											<?php endif ?>
										</td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
					<tr><td colspan="7"><p>Нет данных</p></td></tr>
					<?php endif ?>
				</tbody>
			</table>
		</div>		
	</div>				
</div>
<!-- Таблица результатов -->

<script>
$(document).ready(function(){$('#tableContent').DataTable({"language":{"url":"/public/js/admin/Russian.json"}})});

$(document).on("keyup","input[name=name]",function(){

	if (window.edit_id == '' || window.edit_id == undefined) {
		var ru = $(this).val();

		if (ru.length >= 4)
		{
		var en = transliteUrl(ru);
		$("input[name=name_en]").val("nav-"+en);
		}
	}
});

$('#Mlist').hierarchySelect({width:'80%'});


</script>
<?php endif; ?>