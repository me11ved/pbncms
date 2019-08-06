<?php if(Session::get('loggedIn') == true): ?> 
		
		<div class="ContentMiniBox">
			<div id="header">
				<div id="title">
					Я.Вебмастер
				</div>
			</div>
		</div>
		</br>
		
		<div class="windowStatus alert"></div>
		
		<!--content-->
			<div class="tabs">
				<input id="tab1" type="radio" name="tabs" <?php if(!$this->result->response["tabs"]) : ?> checked <?php endif;?>>
				<label for="tab1" title="Информация о сайте">Информация</label>
				
				<input id="tab2" type="radio" name="tabs">
				<label for="tab2" title="Внешние ссылки">Ссылки</label>
				
				<input id="tab3" type="radio" name="tabs">
				<label for="tab3" title="Уникальные текста">Текста</label>
				
				<input id="tab4" type="radio" name="tabs">
				<label for="tab4" title="Sitemap">Sitemap</label>
				
				<input id="tab5" type="radio" name="tabs" <?php if($this->result->response["tabs"] == "setting") : ?> checked <?php endif;?>>
				<label for="tab5" title="Настройки">Настройки</label>
				
				<section id="content-tab1">
					<div style=" display:inline-block; width:100%;">
						<div style="float:left;width:30%; display:inline-block;">
							<div>
								<table>
									<tr>
										<td>Главное зеркало сайта</td>
										<td><b><?=$this->result->info->unicode_host_url; ?></b></td>
									</tr>
									<tr>
										<td>Подтвержден сайт</td>
										<td><b>
											<?php if($this->result->info->verified) : ?>
											Да
											<?php else : ?>
											Нет
											<?php endif; ?>
											</b>
										</td>
									</tr>
									<tr>
										<td>Отображаемое имя</td>
										<td><?=$this->result->info->host_display_name; ?></td>
									</tr>
									<tr>
										<td>Статус вебмастера</td>
										<td>
											<b>
													<?php if($this->result->info->host_data_status == 'NOT_INDEXED') : ?>
														Сайт еще не проиндексирован
													<?php elseif($this->result->info->host_data_status == 'NOT_LOADED') : ?>
														Данные о сайте еще не загружены
													<?php elseif($this->result->info->host_data_status == 'OK') : ?>
														Сайт проиндексирован
													<?php endif; ?>
											</b>
										</td>
									</tr>
									<tr>
										<td>Всего текстов</td>
										<td><b><?=$this->result->texts->count; ?></b></td>
									</tr>
									<tr>
										<td>Остаток квоты</td>
										<td><b><?=$this->result->texts->quota_remainder; ?></b></td>
									</tr>
									<tr>
										<td>ИКС</td>
										<td><b><?=$this->result->trabl->sqi?></b></td>
									</tr>
									<tr>
										<td>Исключенных стр.</td>
										<td><b><?=$this->result->trabl->excluded_pages_count?></b></td>
									</tr>
									<tr>
										<td>В поиске стр.</td>
										<td><b><?=$this->result->trabl->searchable_pages_count?></b></td>
									</tr>
									<tr>
										<td>Проблемы [<a href="https://webmaster.yandex.ru/site/<?=$this->result->info->host_id?>/diagnosis/checklist/" target="_blank">в вебмастер</a>]</td>
										<td>
											<ul>
												<?php if($this->result->trabl->site_problems->FATAL) : ?>
												<li>Фатальные <b><?=$this->result->trabl->site_problems->FATAL; ?></b></li>
												<?php endif; ?>
												<?php if($this->result->trabl->site_problems->FACRITICALTAL) : ?>
												<li>Критичные<b> <?=$this->result->trabl->site_problems->CRITICAL; ?></b></li>
												<?php endif; ?>
												<?php if($this->result->trabl->site_problems->POSSIBLE_PROBLEM) : ?>
												<li>Возможные<b> <?=$this->result->trabl->site_problems->POSSIBLE_PROBLEM; ?></b></li>
												<?php endif; ?>
												<?php if($this->result->trabl->site_problems->RECOMMENDATION) : ?>
												<li>Рекомендация<b> <?=$this->result->trabl->site_problems->RECOMMENDATION; ?></b></li>
												<?php endif; ?>
											</ul>		
										</td>
									</tr>
									<tr>
										<td></td>
										<td></td>
									</tr>
								</table>
								
								
							
								
						</div>
				
						
					</div>
					
					<div class="ContentMiniBox" style="width:70%;float:left; display:inline-block;">
						<div id="header">
							<div id="title">Популярные запросы за месяц (<span id='countsearch'><?=count($this->result->query->queries); ?></span>)</div>
							<div id="setting">
							</div>
						</div>
						<div id="content">
							
							
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
											<th class='head'><h3>Запрос</h3></th>
											<th class='head'><h3>Кол-во показов</h3></th>
											<th class='head'><h3>Кол-во кликов</h3></th>
											<th class='head'><h3>Ср. позиция показа</h3></th>	
											<th class='head'><h3>Ср. позиция клика</h3></th>	
										</tr>
									</thead>
									<tbody class="tbody">
										<?php foreach($this->result->query->queries as $key => $val): ?>
												<tr>
													<td><?=$val->query_text; ?></td>
													<td><?=$val->indicators->TOTAL_SHOWS; ?></td>
													<td><?=$val->indicators->TOTAL_CLICKS; ?></td>
													<td><?=$val->indicators->AVG_SHOW_POSITION; ?></td>
													<td><?=$val->indicators->AVG_CLICK_POSITION; ?></td>
												</tr>
											<?php endforeach; ?>
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
				</section>
				<section id="content-tab2">
					<div id="header">
							<div id="title">Внешние ссылки (<span id='countsearch'><?=$this->result->links->count ?></span>)</div>
							<div id="setting">
							</div>
						</div>
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
										<th class='head'><h3>Откуда</h3></th>
										<th class='head'><h3>Куда</h3></th>
										<th class='head'><h3>Дата Появления</h3></th>
										<th class='head'><h3>Дата последнего обнаружения роботом</h3></th>	
									</tr>
								</thead>
								<tbody class="tbody">
									<?php foreach($this->result->links->links as $key => $val): ?>
											<tr>
												<td><a href="<?=$val->source_url; ?>" target="_blank"><?=$val->host; ?></a></td>
												<td><a href="<?=$val->destination_url; ?>" target="_blank"><?=$val->path; ?></a></td>
												<td><?=$val->discovery_date; ?></td>
												<td><?=$val->source_last_access_date; ?></td>
											</tr>
										<?php endforeach; ?>
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
				</section>
				
				<section id="content-tab3">
					<div id="content">
						<?php if($this->result->texts->count > 0) : ?>
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
									<?php foreach($this->result->texts->original_texts as $key => $val): ?>
											<tr>
												<td><input type='checkbox' name='listpos' id='listpos' class='checking' value='<?=$val->id; ?>'></td>
												<td><?=$val->id; ?></td>
												<td><?=$val->date; ?></td>
												<td><?=$val->content_snippet; ?></td>
											</tr>
										<?php endforeach; ?>
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
							<div class="ButtonStyle" id="deletelistposwebmaster" data-type="text">Удалить</div>
				
				<?php else : ?>
				
				<p style=" text-align: center; ">Текстов пока не добавлено в вебмастер.</p>
				
				<?php endif; ?>
				
					</div>	
				</section>
				<section id="content-tab4">
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
										<th class='nosort'><h3>ID</h3></th>
										<th class='head'><h3>URL </h3></th>
										<th class='head'><h3>Дата загрузки</h3></th>
										<th class='head'><h3>Ошибок</h3></th>
										<th class='head'><h3>Ссылок</h3></th>
										<th class='head'><h3>Тип</h3></th>	
									</tr>
								</thead>
								<tbody class="tbody">
									<?php foreach($this->result->sitemap->sitemaps as $key => $val): ?>
											<tr>
												<td><span id='removesitemap' data="<?=$val->sitemap_id; ?>"><?=$val->sitemap_id; ?></span></td>
												<td><?=$val->sitemap_url; ?></td>
												<td><?=$val->last_access_date; ?></td>
												<td><?=$val->errors_count; ?></td>
												<td><?=$val->urls_count; ?></td>
												<td><?=$val->sitemap_type; ?></td>
											</tr>
										<?php endforeach; ?>
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
				</section>
				<section id="content-tab5">
					<div id="content">
						<form action="javascript:void(0);" name='data_setting'>
							<table id="hor-minimalist-b">
								<tr>
									<td>Токен <b>(Актуален до <?=date("d.m.Y H:i",$this->result->setting['expires_in']);?>)</b></td>
									<td><?=$this->result->setting['access_token']; ?></td>
								</tr>
								<tr>
									<td>Пользователь</td>
									<td><?=$this->result->setting['user_id'];?></td>
								</tr>
								<?php if($this->result->setting->hosts) : ?>
								<tr>
									<td>Список доступных сайтов:</td>
									<td>
										<p><i style="color:#cc0000;">Выберите сайт по которому необходимо получать данные:</i></p>
										<select name="host_id">
											<?php foreach($this->result->hosts as $key => $val) : ?>
												<option value="<?=$val->host_id;?>"><?=$val->unicode_host_url;?></option>
											<?php endforeach; ?>
										</select>
									</td>
								</tr>
								<?php elseif ($this->result->setting['host_id']) : ?>
								<tr>
									<td>Cайт:</td>
									<td><?=$this->result->setting['host_id'];?></td>
								</tr>
								<?php endif;?>
							</table>
							<?php if($this->result->setting->hosts) : ?>
								<input type='submit' class="ButtonStyle" value='Сохранить' app-method="webmaster_host_save" style="padding:5px 0;width:100px;">
							<?php else : ?>
								<input type='submit' class="ButtonStyle" value='Обновить токен' app-method="webmaster_up_token" style="">
							<?php endif;?>
						</form>
						</br>
					</div>
				</section>
				
		</div>

</body>
</html>
<script src="/public/js/runtinytable.js"></script>
<script src="/public/js/_apps/integration/webmaster.js"></script>

<?php endif; ?>