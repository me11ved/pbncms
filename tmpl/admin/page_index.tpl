<?php if(Session::get('loggedIn') == true): ?> 
	<!-- Modal Window -->
<div class="modal fade" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title">Новая страница</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	  </div>
	<form action="javascript:void(0);" name='data'>
		<div class="modal-body">
			<div class="alert" id="alert" role="alert" style="display:none;"></div>	
			<ul class="nav nav-tabs" id="myTab" role="tablist">
			  <li class="nav-item">
				<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Основные</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Дополнительные</a>
			  </li>
			</ul>
			<div class="tab-content" id="myTabContent">
				<br>
			  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Название</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="Название страницы" name="title">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">&lt;H1&gt;</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="Заголовок первого уровня" name="h1">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Url</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputEmail3" placeholder="Адрес страницы" name="href">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="exampleInputEmail1" class="col-sm-2">Категория</label>
					<div class="dropdown hierarchy-select" id="CategoryList">
						<button type="button" class="btn dropdown-toggle" id="example-one-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
						<div class="dropdown-menu" aria-labelledby="example-one-button">
							<div class="hs-searchbox">
								<input type="text" class="form-control" autocomplete="off">
							</div>
							<div class="hs-menu-inner">
								<a class="dropdown-item" data-value="" data-level="1" data-default-selected="" href="#">Список категорий</a>
								<?php if($this->category["response"]) : ?>
									<?php if($this->category["response"]["category"]) : ?>
											<?php foreach ($this->category["response"]["category"] as $cat) : ?>
												<a class="dropdown-item" data-value="<?=$cat["id"]?>" data-level="1" href="#"><?=$cat["title"]?></a>
												<?php if($cat["products"]) : ?>
													<?php foreach ($cat["products"] as $pr) : ?>
														<a class="dropdown-item" data-value="p<?=$pr["id"]?>" data-level="2" href="#"><?=$pr["title"]?></a>
													<?php endforeach; ?>
												<?php endif; ?>
											<?php endforeach; ?>
									<?php endif; ?>
								<?php endif; ?>	
							</div>
						</div>
						<input class="d-none" name="id_category" readonly="readonly" aria-hidden="true" type="text"/>
					</div>
				 </div>
				
				<div class="form-group">
					<label for="exampleFormControlTextarea1">Описание</label>
					<textarea class="form-control" id="description" rows="3" name="description"></textarea>
				</div>
				
			  </div>
			  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
			  
					<div class="form-group row">
						<label for="inputEmail3" class="col-sm-2 col-form-label">Краткое описание</label>
						<div class="col-sm-10">
						  <input type="text" class="form-control" id="inputEmail3" placeholder="Мини описание" name="mini_desc">
						</div>

					</div>
					
					<div class="form-group row">
						<label for="inputEmail3" class="col-sm-2 col-form-label">&lt;title&gt;</label>
						<div class="col-sm-9">
						  <input type="text" class="form-control" id="inputEmail3" placeholder="Текст тега title" name="meta_title">
						</div>
						<div class="col-sm-1 countWord" title="Оптимальная длина для полного отображения в яндексе">
						<span  class="countTitle">0</span>
						<span>/ 53</span>
					</div>
					</div>
					
					<div class="form-group row">
						<label for="inputEmail3" class="col-sm-2 col-form-label">&lt;description&gt;</label>
						<div class="col-sm-9">
						  <input type="text" class="form-control" id="inputEmail3" placeholder="Текст тега description" name="meta_description">
						</div>
						<div class="col-sm-1 countWord" title="Оптимальная длина для полного отображения в яндексе">
						<span  class="countDescription">0</span>
						<span>/ 160</span>
					</div>
					</div>
					
					<div class="form-group row">
						<label for="inputEmail3" class="col-sm-2 col-form-label">Хлебные крошки</label>
						<div class="col-sm-10">
						  <input type="text" class="form-control" id="inputEmail3" placeholder="Название в навигации" name="bread_crumbs">
						</div>
					</div>
					
					<div class="form-group row">
						<label for="inputEmail3" class="col-sm-2 col-form-label">Путь до фото</label>
						<div class="col-sm-10">
						  <input type="text" class="form-control" id="inputEmail3" placeholder="/public/images/tovar.jpg" name="avatar">
						</div>
					</div>
					
					<div class="input-group mb-3">
					  <div class="input-group-prepend">
						<label class="input-group-text" for="inputGroupSelect01">Опубликована</label>
					  </div>
					  <select class="custom-select" id="inputGroupSelect01" name="public">
						<option selected value="1">Да</option>
						<option value="0">Нет</option>
					  </select>
					</div>
					
					<div class="input-group mb-3">
					  <div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">Произвольное поле 1</span>
					  </div>
					  <input type="text" class="form-control"  aria-describedby="basic-addon1" name="pole1">
					</div>
					
					<div class="input-group mb-3">
					  <div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">Произвольное поле 2</span>
					  </div>
					  <input type="text" class="form-control"  aria-describedby="basic-addon1"name="pole2">
					</div>
					
					<div class="input-group mb-3">
					  <div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">Произвольное поле 3</span>
					  </div>
					  <input type="text" class="form-control"  aria-describedby="basic-addon1" name="pole3">
					</div>
					
					<div class="input-group mb-3">
					  <div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">Произвольное поле 4</span>
					  </div>
					  <input type="text" class="form-control"  aria-describedby="basic-addon1" name="pole4">
					</div>
					
					<div class="input-group mb-3">
					  <div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">Произвольное поле 5</span>
					  </div>
					  <input type="text" class="form-control"  aria-describedby="basic-addon1" name="pole5">
					</div>
			  </div>
			
			</div>
		</div>
		<div class="modal-footer">
			<input type="submit" class="btn btn-primary" app-method="add_page" value="Добавить">
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
					<div id="title"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;
					Страницы (<span id='countsearch'><?php echo count($this->data); ?></span>)</div>
					<div class="selecttop">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<label class="input-group-text" for="inputGroupSelect01">Операции</label>
						  </div>
						  <select class="custom-select" id="inputGroupSelect01" name="functionsSelect">
							<option selected="selected" disabled>Действие</option>
							<optgroup label="Страница">
								<option value="page1">Добавить</option>
								<option value="page2">Редактировать</option>
							</optgroup>
							<optgroup label="Групповые операции">
								<option value="page3">Отключить</option>
								<option value="page4">Включить</option>
								<option value="page5">Удалить</option>
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
						<th class='head'><h3><input type="checkbox" name="opt" class='allcheck'></h3></th>
						<th class='head'><h3>Название</h3></th>
						<th class='head'><h3>Категория</h3></th>
						<th class='head'><h3>H1</h3></th>
						<th class='head'><h3>Title</h3></th>
						<th class='head'><h3>Статус</h3></th>	
						<th class='head'><h3>Дата создания</h3></th>		
					</tr>
				</thead>
					<tbody class="tbody">
						<?php if($this->data) : ?>
							<?php foreach($this->data as $key => $val): ?>
								<tr data-id='<?=$val['id']?>'>
									<td><input type="checkbox" value="<?php echo $val['id']; ?>" name="listpos[]"/></td>
									<td><a href="<?=$val['url']?>" target="_blank" style="color:#000;"><?php echo $val['title']; ?></a></td>
									<td><?=$val["category"]?></td>
									<td><?php echo $val['h1']; ?></td>
									<td><?php echo $val['meta_title']; ?></td>
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
						<?php else : ?>
						<tr><td colspan="7"><p>Нет данных</p></td></tr>
						<?php endif ?>
				</tbody>
			</table>
		</div>		
	</div>				
</div>

<script type="text/javascript">
	CKEDITOR.replace( 'description',{
		customConfig: '/public/js/_apps/ckeditor.js'
	});
</script>
	
<script>
$(document).ready(function(){$('#tableContent').DataTable({"language":{"url":"/public/js/admin/Russian.json"}})});

$('#CategoryList').hierarchySelect({width:'80%'});

$(document).on("keyup","input[name=title]",function(){
	
	$(".countTitle").html($(this).val().length);
	
	if (window.edit_id == '' || window.edit_id == undefined) {
		var ru = $(this).val();

		if (ru.length >= 4)
		{
		var en = transliteUrl(ru);
		$("input[name=href]").val(en);
		}
	}
});

</script>

<script>
$(document).on("keyup","input[name=meta_title]",function(){
	$(".countTitle").html($(this).val().length);});
</script>

<script>
$(document).on("keyup","input[name=meta_description]",function(){
	$(".countDescription").html($(this).val().length);});
</script>
		
<?php endif; ?>