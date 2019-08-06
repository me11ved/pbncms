<?php if(Session::get('loggedIn') == true): ?> 
		
		<!-- Modal Window -->
		<div class="modal fade" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title">Новая перелинковка</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			<form action="javascript:void(0);" name="data">
			<div class="modal-body">
				
				<div class="alert" id="alert" role="alert" style="display:none;"></div>	
			
				 <div class="form-group">
					<label for="exampleInputEmail1">Страница донор</label>
					<div class="dropdown hierarchy-select" id="donorList">
						<button type="button" class="btn dropdown-toggle" id="example-one-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
						<div class="dropdown-menu" aria-labelledby="example-one-button">
							<div class="hs-searchbox">
								<input type="text" class="form-control" autocomplete="off">
							</div>
							<div class="hs-menu-inner">
								<a class="dropdown-item" data-value="" data-level="1" data-default-selected="" href="#">Список страниц</a>
							</div>
						</div>
						<input class="d-none" name="donor" readonly="readonly" aria-hidden="true" type="text"/>
					</div>
					<small id="emailHelp" class="form-text text-muted">Страница на которой будет расположены ссылка</small>
				  </div>
				  
				   <div class="form-group">
					<label for="exampleInputEmail1">Страница акцептор</label>
					<div class="dropdown hierarchy-select" id="aceptorList">
						<button type="button" class="btn dropdown-toggle" id="example-one-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
						<div class="dropdown-menu" aria-labelledby="example-one-button">
							<div class="hs-searchbox">
								<input type="text" class="form-control" autocomplete="off">
							</div>
							<div class="hs-menu-inner">
								<a class="dropdown-item" data-value="" data-level="1" data-default-selected="" href="#">Список страниц</a>
							</div>
						</div>
						<input class="d-none" name="acceptor" readonly="readonly" aria-hidden="true" type="text"/>
					</div>
					<small id="emailHelp" class="form-text text-muted">Страница на которую будет ссылатся донор</small>
				  </div>
				  
					<div class="form-group">
					<label for="exampleInputEmail1">Анкор</label>
					<input type="text" class="form-control" id="ankorInput" aria-describedby="emailHelp" placeholder="Введите текст" name="ankor">
					<small id="emailHelp" class="form-text text-muted">Текст к которому будет подставлятся ссылка на странице-донор</small>
				  </div>
			</div>
				  <div class="modal-footer">
					<input type="submit" class="btn btn-primary" app-method="add_relink" value="Добавить">
				  </div>
			</form>
				</div>
			</div>
		</div>
		<!-- Modal Window -->
			
		<div class="container-fluid">	
			<div class="row">
				<div class="col-lg-12">
				<div id="header">
					<div id="title"><i class="fa fa-link" aria-hidden="true"></i>&nbsp;
					Перелинковка</div>
					<div class="selecttop">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<label class="input-group-text" for="inputGroupSelect01">Операции</label>
						  </div>
						  <select class="custom-select" id="inputGroupSelect01" name="functionsSelect">
							<option selected="selected" disabled>Действие</option>
							<option value="relink1">Добавить</option>
							<option value="relink2">Удалить</option>
						  </select>
						</div>
						</div>
				</div>
				<br><br>
				
				<!-- Вывод ошибок	 -->
				<div class="alert alert-success" id="alertBody" role="alert" style="display:none;">
				</div>		
				<br>
				
				<div id="content">
						<table cellpadding="0" cellspacing="0" border="0" id="tableRelink" class="table table-striped table-bordered">
							<thead>
								<tr>
									<th><h3><input type="checkbox" name="opt" class='allcheck'></h3></th>

									<th class='head'><h3>Стр. акцептор</h3></th>
									<th class='head'><h3>Страница донор</h3></th>
									<th class='head'><h3>Анкор</h3></th>
									<th class='head'><h3>Статус</h3></th>
								</tr>
							</thead>
							<tbody class="tbody">
							<?php foreach($this->data as $key => $val): ?>
								<tr data-id="<?=$val['id']?>">
									<td><input type="checkbox" value="<?php echo $val['id']; ?>" name="listpos[]"/></td>
									<td><a href='<?php echo $val['murl']; ?>' target='_black'><?php echo $val['main']; ?></a></td>
									<td><a href='<?php echo $val['durl']; ?>' target='_black'><?php echo $val['donor']; ?></a></td>
									<td><?php echo $val['ankor']; ?></td>
									<td>
										<?php if($val['public']) : ?>
											<div class="on">&nbsp;</div>
										<?php else : ?>
											<div class="off">&nbsp;</div>
										<?php endif ?>
									</td>
								</tr>
							<?php endforeach; ?>
							</tbody>
							</table>
				</div>
			</div>		
		</div>
	</div>
				
		
<script type="text/javascript">
$('#donorList').hierarchySelect({width:'100%'});$('#aceptorList').hierarchySelect({width:'100%'});$('#AddModal').on('shown.bs.modal',function(){$('#ankorInput').trigger('focus')});$(document).ready(function(){$('#tableRelink').DataTable({"language":{"url":"/public/js/admin/Russian.json"}})});
</script>		
		

<?php endif; ?>