<?php if(Session::get('loggedIn') == true): ?> 
		
<!-- Modal Window -->
<div class="modal fade" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title">Новый тег</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	  </div>
	<form action="javascript:void(0);" name='data'>
		<div class="modal-body">
			<div class="alert" id="alert" role="alert" style="display:none;"></div>	
			
				<div class="input-group mb-3">
				  <div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon1">Название</span>
				  </div>
				  <input type="text" class="form-control"  aria-describedby="basic-addon1" name="name">
				</div>
				
				<div class="input-group mb-3">
				  <div class="input-group-prepend">
					<label class="input-group-text" for="inputGroupSelect01">Позиция</label>
				  </div>
				  <select class="custom-select" id="inputGroupSelect01" name="position">
					<option selected value="header">Header</option>
					<option value="footer">Footer</option>
				  </select>
				</div>
				
				<div class="input-group mb-3">
				  <div class="input-group-prepend">
					<label class="input-group-text" for="inputGroupSelect01">Опубликован</label>
				  </div>
				  <select class="custom-select" id="inputGroupSelect01" name="public">
					<option selected value="1">Да</option>
					<option value="0">Нет</option>
				  </select>
				</div>
				
				<div class="display-block ">
					<div class="float-left">Шаблон тега</div>
					<div class="float-left">
						<span onclick="addScriptTag('script')" style="float: left;color: blue; text-decoration: underline; cursor: pointer; margin: 0 5px;">script</span>
						<span onclick="addScriptTag('style')" style="float: left;color: blue; text-decoration: underline; cursor: pointer; margin: 0 5px;">style</span>
					</div>
				</div>
				<br>
				<div class="form-group">
					<label for="exampleFormControlTextarea1"></label>
					<textarea class="form-control" id="description" rows="3" name="text" placeholder="Код скрипта с тегами css или javascript"></textarea>
				</div>
					
			</div>
		<div class="modal-footer">
			<input type="submit" class="btn btn-primary" app-method="add_tag" value="Добавить">
			<div class="btn btn-secondary clearPopup" title="Очистить поля для ввода">Очистить</div>
		</div>
	</form>
	</div>
</div>
</div>	
<!-- Modal Window -->
<!-- Таблица результатов -->
<div class="container-fluid">	
		<div class="row">
			<div class="col-lg-12">
				<div id="header">
					<div id="title"><i class="fa fa-code" aria-hidden="true"></i>
						&nbsp;Тег менеджер
					</div>
					<div class="selecttop">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<label class="input-group-text" for="inputGroupSelect01">Операции</label>
						  </div>
						  <select class="custom-select" id="inputGroupSelect01" name="functionsSelect">
								<option selected="selected" disabled>Действие</option>
								<optgroup label="Тег">
									<option value="tag1">Добавить</option>
									<option value="tag4">Редактировать</option>
								</optgroup>
								<optgroup label="Групповые операции">
									<option value="tag2">Отключить</option>
									<option value="tag5">Включить</option>
									<option value="tag3">Удалить</option>
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
			<table cellpadding="0" cellspacing="0" border="0" id="tableContent" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th><h3><input type="checkbox" name="opt" class='allcheck'></h3></th>
						<th><h3>Название</h3></th>
						<th><h3>Позиция</h3></th>
						<th><h3>Статус</h3></th>
					</tr>
				</thead>
				<tbody class="tbody">
						<?php if (is_array($this->data)) : ?>
							<?php foreach($this->data as $key => $val): ?>
								<tr data-id="<?php echo $val['name']; ?>">
									<td><input type="checkbox" value="<?php echo $val['name']; ?>" name="listpos[]"/></td>
									<td><?php echo $val['name']; ?></td>
									<td><?php echo $val['position']; ?></td>
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
	</div>	
</div>
<!-- Таблица результатов -->

<script type="text/javascript">
$(document).ready(function(){$('#tableContent').DataTable({"language":{"url":"/public/js/admin/Russian.json"}})});
</script>
		
<?php endif; ?>