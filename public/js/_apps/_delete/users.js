	
	/* Функция для выбора операции */

	$("select[name=functionsSelect]").on('change',function()
	{
					
		$(this).attr("dissabled","dissabled");
					
		var list = $('#tablewrapper input:checked[name="listpos[]"]').map(function () { 	return $(this).val(); }).get();
		
		var status = $(this).attr('data-id');	
					
		switch (this.value)
		{
			case '1':
				$("#popup1").show();
			break;
			case '2':
				switchUser(list,'off');
			break;
			case '3':
				delUser(list);
			break;
			case '4':
				editUser(list);
			break;
			case '5':
				switchUser(list,'on');
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
				case 'add_user':
				if(d.name == '' || d.name == undefined) message("warning","Не заполнено логин",".windowStatusPopup",true);
					addUser(d);
				break;
				case 'save_user' :
					saveEditUser(d);
				break;
			}
		}); 
	});
			
	

	/* Модуль: Пользователи */
	/* Добавление пользователя */
	
	function addUser(data)
	{
		
		data.api = $('#popup1 input[name=api]').prop("checked");
		
		var r = curl([{		
				"url" : "/admin/usersAdd",
				"params" : data
			}]);
		
		switch(r.status)
		{
			case 'success' :
			
				message('success',"Операция выполнена",".windowStatus");
				
				$("#popup").hide();
				
				var htm = '<tr data-id="'+r.response.id+'" class="oddrow" onmouseover="sorter1.hover(1,1)" onmouseout="sorter1.hover(1,0)">'
									+'<td class=""><input type="checkbox" value="'+r.response.id+'" name="listpos[]"></td>'
									+'<td class="">'+r.response.name+'</a></td>'
									+'<td class="">'+r.response.role+'</td>'
									+'<td class="oddselected">'+r.response.last_in+'</td>'
									+'<td>'+r.response.comment+'</td>'
									+'<td>'+r.response.api_key+'</td>';
			
				if(r.response.active == 1) 
				{
					htm += "<td><div class='on'>&nbsp;</div></td>";
				}
				else 
				{
					htm += "<td><div class='off'>&nbsp;</div></td>";	
				}
				
				htm +='<td>'+r.response.login+'/'+r.response.password+'</td></tr>';
					
				$(".tinytable tbody").prepend(htm);
				
				window.sorter;
				
				$("#popup1").find("input").val('');
				$("#popup1").hide();
				
				
				
			break;
			
			default :
				message(r.status,r.response,".windowStatusPopup");
			break;
		}
	
	}
	
	/* Модуль: Пользователи */
	/* Удаление пользователя */
	
	function delUser(list)
	{
		if(list.length > 0 && confirm("Вы действительно хотите удалить выбранных пользователей?")) 
		{
			window.removelcol = list;
					
			var r = curl([{	"url" : "/admin/usersDel",
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
	
	/* Модуль: Пользователи */
	/* Отключение/Включение пользователей */
	
	function switchUser (list,status)
	{
		window.col = list;
		var arr = curl([{	"url" : "/admin/usersSwitch",
							"params" : {list:list, status:status}}]);
		switch(arr.status)
		{
			case 'success' :
				message('success',arr.response,".windowStatus");	
				col.forEach(function(item,i,ar){$(".tinytable tbody").find("tr[data-id='"+item+"']").find("td:nth-child(7)").html("<div class='"+status+"'>&nbsp;</div>");});
			break;
			default :
				message(arr.status,arr.response,".windowStatus");
			break;
		}
	}
	
	/* Модуль: Пользователи */
	/* Получение данных для редактирования пользователя */
	
	function editUser(list)
	{
		console.log(list);
		
		if (list[0])
		{			
			var r = curl([{		
						"url" : "/admin/usersEdit",
						"params" : {"id" : list[0]}
					}]);
			
			switch(r.status)
			{
				case 'success':
					
					window.edit_id = r.response.id;
					
					$("#popup1").fadeIn(1000,function()
					{
						
						$(this).find('#title').text("Редактировать пользователя");;
						
						delete r.response.login;
						delete r.response.password;
						
						$.each($('form input').serializeArray(), function(i, field) {
							var name = field.name;
							if(r.response[name]) $("#popup1").find('input[name='+name+']').val(r.response[name]); ;
						});
						
						
						$(this).find('select[name=active]').prop('value', r.response.active); 
						
						$(this).find('select[name=role]').prop('value', r.response.role);
						
						switch(r.response.api) 
						{
							case "yes" :
								$(this).find('input[name=api]').prop('checked', true);
							break;
							case "no" :
								$(this).find('input[name=api]').prop('checked', false);
							break;
							
						}
						$(this).find('input[type=submit]').val("Сохранить").attr('app-method','save_user');
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
	
	/* Модуль: Пользователи */
	/* Сохранение данных после редактирования */
	
	function saveEditUser(d)
	{
		
		if(window.edit_id)
		{
			d.id = window.edit_id;
			d.api = $('#popup1 input[name=api]').prop("checked");
			
			var r = curl([{		
							"url" : "/admin/usersSaveEdit",
							"params" : d
						}]);
			switch(r.status)
					{
						case 'success':
							
							delete window.edit_id;
							
							var htm = '<td class=""><input type="checkbox" value="'+r.response.id+'" name="listpos[]"></td>'
										+'<td class="">'+r.response.name+'</a></td>'
										+'<td class="">'+r.response.role+'</td>'
										+'<td class="oddselected">'+r.response.last_in+'</td>'
										+'<td>'+r.response.comment+'</td>'
										+'<td>'+r.response.api_key+'</td>';
			
							if(r.response.active == 1) 
							{
								htm += "<td><div class='on'>&nbsp;</div></td>";
							}
							else 
							{
								htm += "<td><div class='off'>&nbsp;</div></td>";	
							}
							
							htm +='</tr>';
								
							$(".tinytable tbody").find("tr[data-id='"+r.response.id+"']").html(htm);
														
							window.sorter;
							
							$("#popup1").find("input").val('');
							$("#popup1").hide();
							
							$(".popup").find('input[type=submit]').val("Добавить").attr('app-method','add_user');
							
							message('success',"Операция выполнена",".windowStatus");
							
						break;
						case 'warning':
							message('warning',r.response,"#windowStatusPopup");
						break;
						case 'error':
							message('error',r.response,"#windowStatusPopup");					
						break;
					}		
		}
		else
		{
			console.log("Не найден id");
		}
	}