<?php if(Session::get('loggedIn') == true): ?> 

		<!--content-->
		<div class="TableContent">
			<div id='breadcrumbs'>
				<div><span>Интеграции</span> <span id='next'>></span> <span>WebMaster</span> <span id='next'>></span> <span>Уникальные текста</span></div>
			</div>
			
			<div style="float:left;width:25%;">
				<div class="ContentMiniBox">
					<div id="header">
						<div id="title" style="width:70%;">Сводка</div>
						<div id="setting"></div>
					</div>
					<div id="content">
						
						<div style="float: left;margin: 7px;">
							<ul>
								<li>Всего текстов:  <b><?php echo $this->data['texts']['count']; ?></b></li>
								<li>Суточная квота (остаток):  <b> <?php echo $this->data['texts']['quota_remainder']; ?></b></li>
							</ul>	
						</div>
					</div>
				</div>
				<div class="ContentMiniBox">
					<div id="header">
						<div id="title" style="width:70%;">Добавить текст</div>
						<div id="setting"></div>
					</div>
					<div id="content">
						
						<div style="float: left;margin: 7px;width:100%;">
							<textarea id='newtext' placeholder="Мин. 500 символов"></textarea>
						</div>
						<div id='status'>
							
						</div>
						<div class="ButtonStyle" id='addtextwebmaster'>Добавить</div>
					</div>
				</div>
				<div class="ContentMiniBox">
					<div id="header">
						<div id="title" style="width:70%;">Экспорт из файла <b>*.txt</b></div>
						<div id="setting"></div>
					</div>
					<div id="content">
						
						<div style="margin:0 auto;width:50%;">
							<div class="ButtonStyle" id='uploads' style='float:none;'>Выбрать файл</div>
						</div>
						<div id='status' class="statusMultiUpload">
							
						</div>
						<p style=" text-align: center; color: gray; font-weight: normal; ">*1 строка - 1 текст, строк не должно быть больше доступной квоты</p>
					</div>
				</div>
			</div>
			<div>
				<div class="ContentMiniBox">
					<div id="header">
						<div id="title">Список тектов (<span id='countsearch'><?php echo $this->data['texts']['count']; ?></span>)</div>
					</div>
					<div id="content">
						<?php if($this->data['texts']['count'] > 0) : ?>
						<div id="tablewrapper">
							<div id="tableheader">
								<div class="search">
									<select id="columns" onchange="sorter.search('query')"></select>
									<input type="text" id="query" onkeyup="sorter.search('query')" />
								</div>
								<span class="details">
									<div>Номера <span id="startrecord"></span>-<span id="endrecord"></span> из <span id="totalrecords"></span></div>
									<div><a href="javascript:sorter.reset()">Сброс</a></div>
								</span>
							</div>
							<table cellpadding="0" cellspacing="0" border="0" id="table" class="tinytable">
								<thead>
									<tr>
										<th class='head'><h3><input type='checkbox' class='allcheck' name='checkall'></h3></th>
										<th class='head'><h3>ID</h3></th>
										<th class='head'><h3>Дата добавления</h3></th>
										<th class='head'><h3>Отрывок текста</h3></th>	
									</tr>
								</thead>
								<tbody class="tbody">
									<?php foreach($this->data['texts']['original_texts'] as $key => $val): ?>
											<tr>
												<td><input type='checkbox' name='listpos' id='listpos' class='checking' value='<?php echo $val['id']; ?>'></td>
												<td><?php echo $val['id']; ?></td>
												<td><?php echo $val['date']; ?></td>
												<td><?php echo $val['content_snippet']; ?></td>
											</tr>
										<?php endforeach; ?>
								</tbody>
								</table>
								<div id="tablefooter">
							  <div id="tablenav">
									<div>
										<img src="/public/images/first.gif" width="16" height="16" alt="First Page" onclick="sorter.move(-1,true)" />
										<img src="/public/images/previous.gif" width="16" height="16" alt="First Page" onclick="sorter.move(-1)" />
										<img src="/public/images/next.gif" width="16" height="16" alt="First Page" onclick="sorter.move(1)" />
										<img src="/public/images/last.gif" width="16" height="16" alt="Last Page" onclick="sorter.move(1,true)" />
									</div>
									<div>
										<select id="pagedropdown"></select>
									</div>
									<div>
										<a href="javascript:sorter.showall()">Показать все</a>
									</div>
								</div>
								<div id="tablelocation">
									<div>
										<select onchange="sorter.size(this.value)">
										<option value="5" >5</option>
											<option value="10" selected="selected">10</option>
											<option value="20">20</option>
											<option value="50" >50</option>
											<option value="100" selected="selected">100</option>
										</select>
										<span>Количество на странице</span>
									</div>
									<div class="page">Страница <span id="currentpage"></span> из <span id="totalpages"></span></div>
								</div>
							</div>
				
							</div>	
							<div class="ButtonStyle" id="deletelistposwebmaster" data-type="text">Удалить</div>
				
				<?php else : ?>
				
				<p style=" text-align: center; ">Текстов пока не добавлено в вебмастер.</p>
				
				<?php endif; ?>
				
					</div>	
					
					</div>
				</div>
			</div>	
		</div>
	</div>
	
</div>

</body>
</html>
<script src="/public/js/runtinytable.js"></script>
<script src="/public/js/ajaxupload.3.5.js"></script>
<script>

$(function(){
	new AjaxUpload($('#uploads'),{
		action: '/admin/text_webmaster_upload',
		name: 'uploadfile',
		onSubmit: function(file, ext){
			if(!(ext &&  /^(txt)$/.test(ext))){
				alert('Можно загружать только файлы с расширением *.txt');
				return false;
			}
			else {
				$('#uploads').fadeOut(1000);
				$(".statusMultiUpload").html('Загружаем тексты в вебмастер');
			}
		},
		onComplete: function(file,response){
			console.log(response);
			$('#uploads').fadeIn(500);
			$(".statusMultiUpload").html('Текста добавлены.');
		}
	});
});


</script>

<?php endif; ?>