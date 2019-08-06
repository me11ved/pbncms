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
			$("#popup").show();
		break;
		case '2':

			editPage(list);
		break;
		case '3':
			switchPage(list,'off');
		break;
		case '4':
			switchPage(list,'on');
		break;
		case '5':
			removePage(list);
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
			
	function editPage(list)
	{
		console.log(list);
		
		if (list[0])
		{			
			var r = curl([{		
						"url" : "/admin/pageEdit",
						"params" : {"id" : list[0]}
					}]);
			
			switch(r.status)
			{
				case 'success':
					
					window.edit_id = r.response.id;
					
					$("#popup").fadeIn(1000,function()
					{
						
						$(this).find('#title').text("Редактировать страницу");;
						CKEDITOR.instances.description.setData(r.response.description); 
						
						$.each($('form input').serializeArray(), function(i, field) {
							var name = field.name;
							if(r.response[name]) $("#popup").find('input[name='+name+']').val(r.response[name]); ;
						});
						
						
						$(this).find('select[name=id_category]').prop('value', r.response.id_category); 
						
						if(!r.response.id_category) $(this).find('select[name=id_category]').prop('selectedIndex', r.response.id_category); 
						
						$(this).find('select[name=public]').prop('value', r.response.public);
						
						$(this).find('input[type=submit]').val("Сохранить").attr('app-method','save_page');
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
		else
		{
			message('warning',"Не выбран элемент для редактирования #1",".windowStatus");
		}
	}
			
	function addPage(data)
	{
		data["description"] = CKEDITOR.instances.description.getData(); 
		
		var arr = curl([{	"url" : "/admin/pageAdd",
							"params" : data}]);
		switch(arr.status)
		{
			case 'success' :
				console.log(arr);
				message('success',"Операция выполнена",".windowStatus");
				
				$(".popup").hide();
				
				if($("#table tbody tr").find("td:nth-child(1)").html() == 'Нет данных')
				{
					$("#table tbody tr:nth-child(1)").remove();
				}
				
					var htm = '<tr data-id="'+arr.response.id+'" class="oddrow" onmouseover="sorter.hover(1,1)" onmouseout="sorter.hover(1,0)">'
										+'<td class=""><input type="checkbox" value="'+arr.response.id+'" name="listpos[]"></td>'
										+'<td class="">'+arr.response.title+'</td>'
										+'<td class=""><a href="'+arr.response.href+'">'+arr.response.href+'</a></td>'
										+'<td class="">'+arr.response.h1+'</td>'
										+'<td class="">'+arr.response.meta_title+'</td>'
					
					if(arr.response.public == 1) htm += "<td><div class='on'>&nbsp;</div></td>";
					else htm += "<td><div class='off'>&nbsp;</div></td>";	
					
					htm +='</tr>';
						
					$("#table tbody").prepend(htm);
					
					window.sorter;
					$("#popup").find("input").val('');
					$("#popup").find("textarea").val('');
					CKEDITOR.instances.description.setData(''); 
					$("#popup").show();
					
			break;
			default :
				message(arr.status,arr.response,".windowStatusPopup");
			break;
		}
	}
			
	function removePage(list)
	{
		if(list.length > 0 && confirm("Вы действительно хотите удалить выбранные страницы?")) 
		{
			window.removelcol = list;
					
			var r = curl([{	"url" : "/admin/pageDel",
							"params" : {"ids" : list}}]);
			switch(r.status)
			{
				case 'success' :
					
					removelcol.forEach(function(item,i,ar)
					{
						$(".tinytable tbody").find("tr[data-id='"+item+"']").remove();
					});
					
					window.sorter;
					
					message('success',r.response,".windowStatus");	
				break;
				default :
					message(r.status,r.response,".windowStatus");
				break;
			}
		}
	}
			
	function switchPage (list,status)
	{
		window.col = list;
		var arr = curl([{	"url" : "/admin/pageSwitch",
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
	
	function saveEditPage(d)
	{
		
		if(window.edit_id)
		{
			d['id'] = window.edit_id;
			d['description'] = CKEDITOR.instances.description.getData(); 
			var res = curl([{		
							"url" : "/admin/pageSaveEdit",
							"params" : d
						}]);
			switch(res.status)
					{
						case 'success':
							
							delete window.edit_id;
							CKEDITOR.instances.description.setData(""); 
							$(".popup").find('input[type=submit]').val("Добавить").attr('app-method','add_page');
							$("#popup").hide();
							
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
			console.log("Не найден id");
		}
	}
			
			
	$(document).ready(function() {
		$('form').on('submit', function() 
		{
			window.d = $(this).form();
			var method = $(this).find("input[type=submit]").attr("app-method");
			delete window.d["tabs"];
			
			switch (method) {
				case 'add_page':
					if (d.title == '' || d.title == undefined) {
						message("warning", "Не заполнены поля", ".windowStatusPopup", true);
						return false;
					}
					addPage(d);
					break;
				case 'save_page':
					saveEditPage(d);
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
		
	$(document).on('click','#allcheck',function(){$("input[name='listnewmenu[]']").prop('checked', true); });
	
	$(document).on("click","#plicon",function(){$(this).parents("li").find(".opisanie").toggle();});
	
	$(document).on("click","#delicon",function(){$(this).parents("li").remove();});

	$(document).on('click','#closePopup',function()
	{
		$(this).parents(".popup").hide().find("#title").html('Добавить');
		$(".popup input*").val('');
		$(".popup textarea*").val('');
		$(".popup select").prop('selectedIndex', 0);
	});
				
			