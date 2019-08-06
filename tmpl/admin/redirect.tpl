<?php if(Session::get('loggedIn') == true): ?> 
<!-- Modal Window -->
<div class="modal fade" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title">Новый редирект</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	  </div>
	<form action="javascript:void(0);" name='data'>
		<div class="modal-body">
			<div class="alert" id="alert" role="alert" style="display:none;"></div>	
				
				<div class="input-group mb-3">
				  <div class="input-group-prepend">
					<label class="input-group-text" for="inputGroupSelect01">Статус</label>
				  </div>
				  <select class="custom-select" id="inputGroupSelect01" name="public">
					<option selected value="1">Включен</option>
					<option value="0">Отключен</option>
				  </select>
				</div>
				
				<div class="form-group">
					<label for="exampleFormControlTextarea1">Откуда</label>
					<textarea class="form-control" id="description" rows="3" name="from" placeholder="/test1/old-url"></textarea>
				</div>
				
				<div class="form-group">
					<label for="exampleFormControlTextarea1">Куда</label>
					<textarea class="form-control" id="description" rows="3" name="to" placeholder="/test1/old-url"></textarea>
				</div>
					
			</div>
		<div class="modal-footer">
			<input type="submit" class="btn btn-primary" app-method="add_rd" value="Добавить">
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
				<div id="title"><i class="fa fa-external-link" aria-hidden="true"></i>
						&nbsp;&nbsp;Редирект
				</div>
				<div class="selecttop">
					<div class="input-group mb-3">
					  <div class="input-group-prepend">
						<label class="input-group-text" for="inputGroupSelect01">Операции</label>
					  </div>
					  <select class="custom-select" id="inputGroupSelect01" name="functionsSelect">
							<option selected="selected" disabled>Действие</option>
							<option value="rd1">Добавить</option>
							<option value="rd3">Удалить</option>
							<option value="rd2">Отключить</option>
							<option value="rd5">Включить</option>
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
					<th class='head'><h3>Откуда</h3></th>
					<th class='head'><h3>Куда</h3></th>
					<th class='head'><h3>Создал</h3></th>
					<th class='head'><h3>Дата</h3></th>
					<th class='head'><h3>Статус</h3></th>
				</tr>
			</thead>
				<tbody class="tbody">
						<?php if($this->data[0]['from']) : ?>
							<?php foreach($this->data as $key => $val): ?>
								<tr data-id="<?=$val['id']; ?>">
									<td><input type="checkbox" value="<?=$val['id']; ?>" name="listpos[]"/></td>
									<td><a href="<?=$val['from']; ?>" target="_blank"><?=$val['from']; ?>	</a></td>
									<td><?=$val['to']; ?></td>
									<td><?=$val['user']; ?></td>
									<td><?=$val['create_date']; ?></td>
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