<?php if(Session::get('loggedIn') == true): ?> 

<!-- Таблица результатов -->
<div class="container-fluid">	
	<div class="row">
		<div class="col-lg-12">
		<div id="header">
			<div id="title">
				<i class="fa fa-home" aria-hidden="true"></i>&nbsp;
				Статистика
			</div>
		</div>
		<br><br>			
		
		<!-- Вывод ошибок	 -->
			<div class="alert alert-success" id="alertBody" role="alert" style="display:none;">
			</div>		
			<br>
		<!-- Вывод ошибок	 -->
		</div>		
	</div>		
	<div class="row">
		<div class="col-lg-6">
			<div class="boxShadow fs20">
				<div>
					<i class="fa fa-file-text-o" aria-hidden="true"></i>
					<span>Страницы</span>
				</div>
				<div class="fs30">
					<div class="float-left font-weight-bold" style="color:green;" title="Количество включенных страниц">
						<?=$this->datastatic['on']?>
					</div>
					<div class="float-left">/</div>
					<div class="float-left font-weight-bold" title="Количество отключенных страниц">
						<?=$this->datastatic['off'] ?>
					</div>
				</div>
			</div>
			
			<div class="boxShadow fs20">
				<div>
					<i class="fa fa-newspaper-o"></i>
					<span>Категории</span>
				</div>
				<div class="fs30">
					<div class="float-left font-weight-bold" style="color:green;" title="Количество включенных категорий">
						<?=$this->datastatic['on']?>
					</div>
					<div class="float-left">/</div>
					<div class="float-left font-weight-bold" title="Количество отключенных категорий">
						<?=$this->datastatic['off'] ?>
					</div>
				</div>
			</div>
			<div>
				<p class="font-weight-bold">Недавно обновленные страницы</p>
				<table cellpadding="0" cellspacing="0" border="0" id="tableContent" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th><h3>Дата</h3></th>
						<th><h3>Пользователь</h3></th>
						<th><h3>Название</h3></th>
						<th><h3>Категория</h3></th>
						<th><h3>Операции</h3></th>
						<th><h3>Статус</h3></th>	
					</tr>
				</thead>
				<tbody class="tbody">
						<?php foreach($this->pagelu as $key => $val): ?>
							<tr>
								<td><?php echo $val['update_date']; ?></td>
								<td><?php echo $val['user']; ?></td>
								<td><?php echo $val['title']; ?></td>
								<td><?php echo $val['category']; ?></td>
								<td>
									<a href="<?php echo $val['href'].".html"; ?>" target="_blank" title="Перейти"><i class="fa fa-external-link" aria-hidden="true"></i></a>&nbsp;
									<a href="/admin/edit_position/?type=page&list=<?php echo $val['id']; ?>" target="_blank" title="Редактировать"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
								</td>
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
			<br>
			<div>
				<p class="font-weight-bold">Недавно добавленные страницы</p>
				<table cellpadding="0" cellspacing="0" border="0" id="tableContent1" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th><h3>Дата</h3></th>
							<th><h3>Пользователь</h3></th>
							<th><h3>Название</h3></th>
							<th><h3>Категория</h3></th>
							<th><h3>-</h3></th>
							<th><h3>Статус</h3></th>	
						</tr>
					</thead>
					<tbody class="tbody">
					<?php foreach($this->pagel as $key => $val): ?>
						<tr>
							<td><?php echo $val['create_date']; ?></td>
							<td><?php echo $val['user']; ?></td>
							<td><?php echo $val['title']; ?></td>
							<td><?php echo $val['category']; ?></td>
							<td>
								<a href="<?php echo $val['href'].".html"; ?>" target="_blank" title="Перейти"><i class="fa fa-external-link" aria-hidden="true"></i></a>&nbsp;
								<a href="/admin/edit_position/?type=page&list=<?php echo $val['id']; ?>" target="_blank" title="Редактировать"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
							</td>
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
			<br>
		</div>
		<div class="col-lg-6">
			
			<div class="boxShadow fs20">
				<div>
					<i class="fa fa-shopping-cart"></i>
					<span>Заявки</span>
				</div>
				
				<div class="fs16">
					<div>
						<div class="float-left">Эта неделя&nbsp;</div>
						<div class="float-left font-weight-bold" title="Yandex <?=$this->ordersstatic[0]['CountYandex']?> / Google <?=$this->ordersstatic[0]['CountGoogle']?>"><?=count($this->data2)?></div>
					</div>
					<div>
						<div class="float-left">Последняя в&nbsp;</div>
						<div class="float-left font-weight-bold">10</div>
					</div>
					
				</div>
				
			</div>
			<table cellpadding="0" cellspacing="0" border="0" id="tableContent2" class="table table-striped table-bordered">
				<thead>
					<tr>
						
						<th><h3>Имя</h3></th>
						<th><h3>Телефон</h3></th>
						<th><h3>Время</h3></th>
						<th><h3>Хост</h3></th>	
						<th><h3>Форма</h3></th>
						<th><h3>Запрос</h3></th>
						<th>-</th>
					</tr>
				</thead>
				<tbody class="tbody">
					<?php foreach($this->data2 as $key => $val): ?>
						<tr>
							<td><?php echo $val['name']; ?></td>
							<td><a href="tel:<?php echo $val['phone']; ?>"><?=$val['phone']?></a></td>
							<td><?php echo $val['time']; ?></td>
							<td><?php echo $val['host']; ?></span></td>
							<td><?php echo $val['form']; ?></span></td>
							<td><?php echo $val['query']; ?></span></td>
							<td><a href="/requests/vieworder/<?php echo $val['client_id']; ?>/?key=<?php echo $val['phone']; ?>" target="_blank" title="Перейти"><i class="fa fa-external-link" aria-hidden="true"></i></a>&nbsp;</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>			
</div>	
	
<script type="text/javascript">
$(document).ready(function(){$('#tableContent').DataTable({searching: false, paging: false, info: false,"language":{"url":"/public/js/admin/Russian.json"}})});
$(document).ready(function(){$('#tableContent1').DataTable({searching: false, paging: false, info: false,"language":{"url":"/public/js/admin/Russian.json"}})});
$(document).ready(function(){$('#tableContent2').DataTable({"language":{"url":"/public/js/admin/Russian.json"}})});
</script>
<?php endif; ?>