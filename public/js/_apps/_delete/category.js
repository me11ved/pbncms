	
	/* Функция для выбора операции */

	$("select[name=functionsSelect]").on('change',function()
	{
					
		$(this).attr("dissabled","dissabled");
					
		var list = $('#tablewrapper input:checked[name="listpos[]"]').map(function () { 	return $(this).val(); }).get();
		var status = $(this).attr('data-id');	
					
		switch (this.value)
		{
			case 'cat1':
				$("#popup").show();
			break;
			case 'cat2':
				switchCat(list,'off');
			break;
			case 'cat3':
				delCat(list);
			break;
			case 'cat4':
				editCat(list);
			break;
			case 'cat5':
				switchCat(list,'on');
			break;
		}	
		$(this).prop('selectedIndex', 0);			
	});
	
	$(document).ready(function() 
	{ 
		$('form').on('submit', function() 
		{  
			window.d = $(this).form();
			var method = $(this).find("input[type=submit]").attr("app-method");
			delete window.d["tabs"];
			
			switch (method) {
				case 'add_cat':
				if(d.name == '' || d.name == undefined) message("warning","Не заполнено название",".windowStatusPopup",true);
					addCat(d);
				break;
				case 'save_cat' :
					saveEditCat(d);
				break;
			}
		}); 
	});
			
	

	/* Модуль: Категория */
	/* Добавление категории */
	
	function addCat(data)
	{
		
		data["description"] = CKEDITOR.instances.description.getData(); 
		
		var r = curl([{		
				"url" : "/admin/catAdd",
				"params" : data
			}]);
		
		switch(r.status)
		{
			case 'success' :
			
				message('success',"Операция выполнена",".windowStatus");
				
				$("#popup").hide();
				
				var htm = '<tr data-id="'+r.response.id+'" class="oddrow" onmouseover="sorter1.hover(1,1)" onmouseout="sorter1.hover(1,0)">'
									+'<td class=""><input type="checkbox" value="'+r.response.id+'" name="listpos[]"></td>'
									+'<td class=""><a href="/'+r.response.href+'" target="_blank" alt="'+r.response.title+'">'+r.response.title+'</a></td>'
									+'<td class="">'+r.response.h1+'</td>'
									+'<td class="oddselected">'+r.response.meta_title+'</td>';
									
				if(r.response.public == 1) 
				{
					htm += "<td><div class='on'>&nbsp;</div></td>";
				}
				else 
				{
					htm += "<td><div class='off'>&nbsp;</div></td>";	
				}
				
				htm +='<td>'+r.response.create_date+'</td></tr>';
					
				$(".tinytable tbody").prepend(htm);
				
				window.sorter;
				
				$("#popup").find("input").val('');
				$("#popup").find("textarea").val('');
				CKEDITOR.instances.description.setData(''); 
				
			break;
			
			default :
				message(r.status,r.response,".windowStatusPopup");
			break;
		}
	
	}
	
	/* Модуль: Категория */
	/* Удаление категории */
	
	function delCat(list)
	{
		if(list.length > 0 && confirm("Вы действительно хотите удалить выбранные категории?")) 
		{
			window.removelcol = list;
					
			var r = curl([{	"url" : "/admin/catDel",
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
	
	/* Модуль: Категория */
	/* Отключение/Включение категории */
	
	function switchCat (list,status)
	{
		window.col = list;
		var arr = curl([{	"url" : "/admin/catSwitch",
							"params" : {list:list, status:status}}]);
		switch(arr.status)
		{
			case 'success' :
				message('success',arr.response,".windowStatus");	
				col.forEach(function(item,i,ar){$(".tinytable tbody").find("tr[data-id='"+item+"']").find("td:nth-child(6)").html("<div class='"+status+"'>&nbsp;</div>");});
			break;
			default :
				message(arr.status,arr.response,".windowStatus");
			break;
		}
	}
	
	/* Модуль: Категория */
	/* Получение данных для редактирования категории */
	
	function editCat(list)
	{
		console.log(list);
		
		if (list[0])
		{			
			var r = curl([{		
						"url" : "/admin/catEdit",
						"params" : {"id" : list[0]}
					}]);
			
			switch(r.status)
			{
				case 'success':
					
					window.edit_id = r.response.id;
					
					$("#popup").fadeIn(1000,function()
					{
						
						$(this).find('#title').text("Редактировать категорию");;
						CKEDITOR.instances.description.setData(r.response.description); 
						
						$.each($('form input').serializeArray(), function(i, field) {
							var name = field.name;
							if(r.response[name]) $("#popup").find('input[name='+name+']').val(r.response[name]); ;
						});
						
						
						$(this).find('select[name=id_category]').prop('value', r.response.id_category); 
						
						if(!r.response.id_category) $(this).find('select[name=id_category]').prop('selectedIndex', r.response.id_category); 
						
						$(this).find('select[name=public]').prop('value', r.response.public);
						
						$(this).find('input[type=submit]').val("Сохранить").attr('app-method','save_cat');
					});
					
				default :
					message(r.status,r.response,".windowStatus");
				break;
			}
		}
		else
		{
			message('warning',"Не выбран элемент для редактирования #1",".windowStatus");
		}
	}
	
	/* Модуль: Категория */
	/* Получение данных для редактирования категории */
	
	function saveEditCat(d)
	{
		
		if(window.edit_id)
		{
			d['id'] = window.edit_id;
			d['description'] = CKEDITOR.instances.description.getData(); 
			var res = curl([{		
							"url" : "/admin/catSaveEdit",
							"params" : d
						}]);
			switch(res.status)
					{
						case 'success':
							
							delete window.edit_id;
							CKEDITOR.instances.description.setData(""); 
							$(".popup").find('input[type=submit]').val("Добавить").attr('app-method','add_cat');
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