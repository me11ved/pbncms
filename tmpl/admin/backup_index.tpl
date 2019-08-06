<?php if(Session::get('loggedIn') == true): ?> 

		<!--content-->
		<div class="TableContent">
			
			<div id='breadcrumbs'>	
			</div>
			
			<div class="windowStatus"></div>			
			
			<div>	
				<div class="ContentMiniBox">
					<div id="header">
						<div id="title"><i class="fa fa-copy" aria-hidden="true"></i>&nbsp;<?=$this->title?></div>
						<div class="selecttop">
							<select name="functionsSelect">
								<option selected="selected" disabled>Действие</option>
								<option value="backup1">Создать копию файлов + базы данных</option>
								<option value="backup2">Создать копию всего и удалить старые версии</option>
								<option value="backup3">Создать копию файлов</option>
								<option value="backup4">Создать копию базы данных</option>
								<option value="backup5">Удалить все</option>
							
							</select>
						
						</div>
					</div>
					<br><br>
					<div id="content">
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
										<th class='head'><h3>Дата создания</h3></th>
										<th class='head'><h3>Файл</h3></th>
										<th class='head'><h3>Размер</h3></th>
										<th class='head'><h3>Пользователь</h3></th>
									</tr>
								</thead>
								<tbody class="tbody">
									<?php if($this->data) : ?>
										<?php foreach($this->data as $key => $val): ?>
											<tr>
												<td><?=$val['create_date']; ?></td>
												<td><?=$val['size']; ?></td>
												<td><a href="/admin/settingBackupGet/<?=$val['download_link']?>"/>Скачать</a></td>
												<td><?=$val['user']; ?></td>
											</tr>
										<?php endforeach; ?>
									<?php endif; ?>	
										</tbody>
								</table>
								<div id="tablefooter">
							  <div id="tablenav">
									<div>
										<img src="/public/images/admin/first.gif" width="16" height="16" alt="First Page" onclick="sorter.move(-1,true)" />
										<img src="/public/images/admin/previous.gif" width="16" height="16" alt="First Page" onclick="sorter.move(-1)" />
										<img src="/public/images/admin/next.gif" width="16" height="16" alt="First Page" onclick="sorter.move(1)" />
										<img src="/public/images/admin/last.gif" width="16" height="16" alt="Last Page" onclick="sorter.move(1,true)" />
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
						
					</div>
				</div>
				</div>
				
				
				
		</div>
		
		
<?php endif; ?>