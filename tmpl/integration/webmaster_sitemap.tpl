<?php if(Session::get('loggedIn') == true): ?> 

		<!--content-->
		<div class="TableContent">
			<div id='breadcrumbs'>
				<div><span>Интеграции</span> <span id='next'>></span> <span>WebMaster</span> <span id='next'>></span> <span>SiteMap</span></div>
			</div>
			
			
				<div class="ContentMiniBox" style="width: 95%;">
					<div id="header">
						<div id="title">Список SiteMap (<span id='countsearch'><?php echo count($this->data['sitemap']['sitemaps']); ?></span>)</div>
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
										<th class='nosort'><h3>ID</h3></th>
										<th class='head'><h3>URL </h3></th>
										<th class='head'><h3>Дата загрузки</h3></th>
										<th class='head'><h3>Ошибок</h3></th>
										<th class='head'><h3>Ссылок</h3></th>
										<th class='head'><h3>Тип</h3></th>	
									</tr>
								</thead>
								<tbody class="tbody">
									<?php foreach($this->data['sitemap']['sitemaps'] as $key => $val): ?>
											<tr>
												<td><span id='removesitemap' data="<?php echo $val['sitemap_id']; ?>"><?php echo $val['sitemap_id']; ?></span></td>
												<td><?php echo $val['sitemap_url']; ?></td>
												<td><?php echo $val['last_access_date']; ?></td>
												<td><?php echo $val['errors_count']; ?></td>
												<td><?php echo $val['urls_count']; ?></td>
												<td><?php echo $val['sitemap_type']; ?></td>
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


<?php endif; ?>