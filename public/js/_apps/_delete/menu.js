$("select[name=functionsSelect]").on('change',function()
{
				
	$(this).attr("dissabled","dissabled");
	
	var list = $('#tablewrapper input:checked[name="listpos[]"]').map(function () 
																	{ 	return $(this).val(); })
															.get();
	var status = $(this).attr('data-id');	
	
	switch(this.value)
	{
		case '1':
			$("#popup1").show();
		break;
		case '2':
			editNav(list);
		break;
		case '3':
			switchNav(list,'off');
		break;
		case '4':
			switchNav(list,'on');
		break;
		case '5':
			removeNav(list);
		break;
	}
	
	$("select[name=functionsSelect]").prop('selectedIndex', 0);
	
});
	
	function curl(p)
	{
		if(p[0].url)
		{
			var response = null;
			
			if(!p[0].type) p[0].type = "POST";
			console.log(p[0].params);
			var result = $.ajax({
								async: false,
								type : p[0].type,
								url: p[0].url,
								data: p[0].params
							});
							
			if(!p[0].json) result.responseText = JSON.parse(result.responseText);				
			return result.responseText;		
		}
		else
		{
			console.log("Нет данных для запроса ajax");
		}
	}
			
	function editNav(list)
	{
		
		if (list[0])
		{			
			var r = curl([{		
						"url" : "/admin/navigationEdit",
						"params" : {"id" : list[0]}
					}]);
			
			switch(r.status)
			{
				case 'success':
					
					window.menu_edit_id = r.response.menu[0].id;
					
					$("#popup2 input[name=name]").val(r.response.menu[0].name);
					$("#popup2 input[name=name_en]").val(r.response.menu[0].name_en);
					
					switch(r.response.menu[0].public)
					{
						case '0':
							$("#popup2 select[name=public]").prop('selectedIndex', 1);
						break;
						case '1':
							$("#popup2 select[name=public]").prop('selectedIndex', 0);
						break;
					}
					
					var cat = curl([{	"url" : "/admin/navigationGetCategory",
										"params" : {}
									}]); 			
					
					if(r.response.list.length > 0)
					{
						var list2 = '';
										
						r.response.list.forEach(function(item,i,ar)
						{
							list2 += '<li class="dd-item" data-id="'+item.position+'">'
										+'<div class="dd-handle dd3-handle">Drag</div>'
												+'<div class="dd3-content">'
													+"<div class='fl w85'>"+item.name+"</div>"
														+"<div class='fr'>"
															+"<span id='delicon'>x</span>"
															+"<span id='plicon'>+</span>"
														+"</div>"
													+"</div>"
													+"<div class='opisanie'>"
														+"<form name='form' action='javascript:void(0);'>"
															+"<input type='text' name='name' value='"+item.name+"'>"
															+"<input type='text' name='href' value='"+item.href+"'>"
														+"</form>"
													+"</div>";
							
							if (item.children != undefined)
							{
								
								list2 += '<ol class="dd-list">';
								
								item.children.forEach(function(item2,i,ar)
								{
									list2 += '<li class="dd-item" data-id="'+item2.position+'">'
												+'<div class="dd-handle dd3-handle">Drag</div>'
														+'<div class="dd3-content">'
															+"<div class='fl w85'>"+item2.name+"</div>"
																+"<div class='fr'>"
																	+"<span id='delicon'>x</span>"
																	+"<span id='plicon'>+</span>"
																+"</div>"
															+"</div>"
															+"<div class='opisanie'>"
																+"<form name='form' action='javascript:void(0);'>"
																	+"<input type='text' name='name' value='"+item2.name+"'>"
																	+"<input type='text' name='href' value='"+item2.href+"'>"
																+"</form></div></div></div></li>";							
								});
								
								list2 += '</ol>';
							}
							
							list2 += "</div></div></li>";
						});
						
						 
					}
					else
					{
								list2 = '<li class="dd-item" data-id="new">'
												+'<div class="dd-handle dd3-handle">Drag</div>'
														+'<div class="dd3-content">'
															+"<div class='fl w85'>Новый пункт</div>"
																+"<div class='fr'>"
																	+"<span id='delicon'>x</span>"
																	+"<span id='plicon'>+</span>"
																+"</div>"
															+"</div>"
															+"<div class='opisanie'>"
																+"<form name='form' action='javascript:void(0);'>"
																	+"<input type='text' name='name' value='Новый пункт'>"
																	+"<input type='text' name='href' value='/new-point'>"
																+"</form></div></div></div></li>";			
					}
							
					if(cat.status == 'success')
					{
						var list = '';
										
						cat.response.forEach(function(item,i,ar)
						{
							list += '<li class="dd-item" data-id="'+item.id+'">'
										+'<div class="dd-handle dd3-handle">Drag</div>'
												+'<div class="dd3-content">'
													+"<div class='fl w85'>"+item.title+"</div>"
														+"<div class='fr'>"
															+"<span id='delicon'>x</span>"
															+"<span id='plicon'>+</span>"
														+"</div>"
													+"</div>"
													+"<div class='opisanie'>"
														+"<form name='form' action='javascript:void(0);'>"
															+"<input type='text' name='name' value='"+item.title+"'>"
															+"<input type='text' name='href' value='"+item.href+"'>"
														+"</form>"
													+"</div>"
												+"</div>"
										+"</div>"
										+"</li>";
										});
					
					}
					$("#sortable1 .dd-list").html(list);
					$("#sortable2 .dd-list").html(list2);
										
					$('#sortable1').nestable({group:1,maxDepth:2});
					$('#sortable2').nestable({group:1,maxDepth:2});
					$("#popup2").show();
					
				break;
				case 'warning':
					message('warning',d.response,".windowStatus");
				break;
				case 'error':
					message('error',d.response,".windowStatus");					
				break;
			}
		}
		else
		{
			message('warning',"Не выбран элемент для редактирования",".windowStatus");
		}
	}
			
	function addNav(data)
	{
		var arr = curl([{	"url" : "/admin/navigationAdd",
							"params" : data}]);
		switch(arr.status)
		{
			case 'success' :
				
				message('success',"Операция выполнена",".windowStatus");
				$(".popup").hide();
				
				if($("#table tbody tr").find("td:nth-child(1)").html() == 'Нет данных')
				{
					$("#table tbody tr:nth-child(1)").remove();
				}
				
					var htm = '<tr data-id="'+arr.response.id+'" class="oddrow" onmouseover="sorter.hover(1,1)" onmouseout="sorter.hover(1,0)">'
										+'<td class=""><input type="checkbox" value="'+arr.response.id+'" name="listpos[]"></td>'
										+'<td class="">'+arr.response.name+'</td>'
										+'<td class="">{'+arr.response.name_en+'}</td>'
										+'<td class="oddselected">-</td>';
					
					if(arr.response.public == 1) htm += "<td><div class='on'>&nbsp;</div></td>";
					else htm += "<td><div class='off'>&nbsp;</div></td>";	
					
					htm +='</tr>';
						
					$("#table tbody").prepend(htm);
					
					window.sorter;
					
					editNav([arr.response.id]);
					
			break;
			default :
				message(arr.status,arr.response,".windowStatusPopup");
			break;
		}
	}
			
	function removeNav(list)
	{
		if(list.length > 0 && confirm("Вы действительно хотите удалить выбранные меню?")) 
		{
			window.removelcol = list;
					
			var r = curl([{	"url" : "/admin/navigationDel",
							"params" : {"ids" : list}}]);
			switch(r.status)
			{
				case 'success' :
					removelcol.forEach(function(item,i,ar)
					{
						$(".tinytable tbody").find("tr[data-id='"+item+"']").remove();
					});
					message('success');	
				break;
				default :
					message(r.status,arr.response,".windowStatus");
				break;
			}
		}
	}
			
	function switchNav (list,status)
	{
		window.col = list;
		var arr = curl([{	"url" : "/admin/navigationSwitch",
							"params" : {list:list, status:status}}]);
		switch(arr.status)
		{
			case 'success' :
				message('success',arr.response,".windowStatus");	
				col.forEach(function(item,i,ar){$(".tinytable tbody").find("tr[data-id='"+item+"']").find("td:nth-child(5)").html("<div class='"+status+"'>&nbsp;</div>");});
			break;
			default :
				message(arr.status,arr.response,".windowStatus");
			break;
		}
	}
	
	function saveEditNav(d)
	{
		var sort = $('.dd:nth-child(2)').nestable('serialize');
		
		console.log(sort);
		
		sort.forEach(function(item,i,ar)
		{
				sort[i]['name'] = $(".dd:nth-child(2) li[data-id="+item.id+"] input[name=name]").val();
				sort[i]['href'] = $(".dd:nth-child(2) li[data-id="+item.id+"] input[name=href]").val();
				
				if(item.children)
				{
					item.children.forEach(function(sub,i2,ar2)
					{
						sort[i]['children'][i2]['name'] = $(".dd:nth-child(2) li[data-id="+sub.id+"] input[name=name]").val();
						sort[i]['children'][i2]['href'] = $(".dd:nth-child(2) li[data-id="+sub.id+"] input[name=href]").val();
					});
				}
		});
		console.log(sort);
		
		if(window.menu_edit_id)
		{
			d['id'] = window.menu_edit_id;
			
			var res = curl([{		
							"url" : "/admin/navigationSaveEdit",
							"params" : {"menu" : d , "list" : sort}
						}]);
			switch(res.status)
					{
						case 'success':
							$("#popup2").hide();
							message('success',"Операция выполнена",".windowStatus");
						break;
						case 'warning':
							message('warning',d.response,"#windowStatusPopup2");
						break;
						case 'error':
							message('error',d.response,"#windowStatusPopup2");					
						break;
					}		
		}
		else
		{
			console.log("Не найден id меню");
		}
	}
			
			
	$(document).ready(function() {
		$('form').on('submit', function() 
		{
			window.d = $(this).form();
			var method = $(this).find("input[type=submit]").attr("app-method");
			switch (method) {
				case 'add_nav':
				
					if (d.name == '' || d.name == undefined || d.name_en == '' || d.name_en == 'nav_') {
						message("warning", "Не заполнены поля", ".windowStatusPopup", true);
						return false;
					}
					addNav(d);
					break;
				case 'save_nav':
					saveEditNav(d);
				break;
			}
		});
	});
		
	$.fn.assocarrform = function() { var formData = {}; this.find('[name]').each(function() { formData[this.name] = this.value; }); return formData; };
			
			
	window.sorter = new TINY.table.sorter('sorter','table',{
		headclass:'head',
		ascclass:'asc',
		descclass:'desc',
		evenclass:'evenrow',
		oddclass:'oddrow',
		evenselclass:'evenselected',
		oddselclass:'oddselected',
		paginate:true,
		size:10,
		colddid:'columns',
		currentid:'currentpage',
		totalid:'totalpages',
		startingrecid:'startrecord',
		endingrecid:'endrecord',
		totalrecid:'totalrecords',
		hoverid:'selectedrow',
		pageddid:'pagedropdown',
		navid:'tablenav',
		sortcolumn:1,
		sortdir:-1,
		init:true
	});
			
			
$(document).ready(function()
{
	$("input[name=searchInMenu]").keyup(function()
	{
		// Retrieve the input field text and reset the count to zero
		var filter = $(this).val(), count = 0;
		
		if($(this).parents(".searchForm").find("input[name=inbd]").prop('checked') && filter.length >= 5)
		{
			
					
			var d = curl([{		
						"url" : "/admin/navigationGetProducts",
						"params" : {"filter":filter}
					}]);
			console.log(d);
			switch(d.status)
			{
				case 'success' :
						
						var list = '<ul id="sortable1" class="sortable-ul">';
						
						d['response'].forEach(function(item,i,ar)
						{
								list += '<li class="dd-item" data-id="'+item.id+'">'
											+'<div class="dd-handle dd3-handle">Drag</div>'
											+'<div class="dd3-content">'
												+"<div class='fl w85'>"+item.title+"</div>"
													+"<div class='fr'>"
														+"<span id='delicon'>x</span>"
														+"<span id='plicon'>+</span>"
													+"</div>"
												+"</div>"
											+"<div class='opisanie'>"
												+"<form name='form' action='javascript:void(0);'>"
													+"<input type='text' name='name' value='"+item.title+"'>"
													+"<input type='text' name='href' value='"+item.href+"'>"
												+"</form>"
											+"</div>";
						});
						
						list += '</ol>';
						
						$("#sortable1").html(list);
						
						$('#sortable1').nestable({ 
							group:1
						});
						$('#sortable2').nestable({ 
							group:1
						});
				break;
				case 'warning':
					message('warning',d.response,".windowStatus");
				break;
				case 'error':
					message('error',d.response,".windowStatus");					
				break;
			}
						
		}
		// Loop through the comment list
		$("#sortable1 li").each(function(){
 
			// If the list item does not contain the text phrase fade it out
			if ($(this).text().search(new RegExp(filter, "i")) < 0) {
				$(this).closest('li').fadeOut();
					
			// Show the list item if the phrase matches and increase the count by 1
			} else {
				$(this).closest('li').show();
				count++;
			}
		});
	}); });
			
	$(document).on('click','#allcheck',function(){$("input[name='listnewmenu[]']").prop('checked', true); });
	
	$(document).on("click","#plicon",function(){$(this).parents("li").find(".opisanie").toggle();});
	
	$(document).on("click","#delicon",function(){$(this).parents("li").remove();});

	$(document).on('click','#closePopup',function()
	{
		$(this).parents(".popup").hide().find("#title").html('Добавить меню');
		$("input[name=name]").val('');
		$("textarea[name=text]").val('');
		$(".popup select").prop('selectedIndex', 0);
	});
				
			