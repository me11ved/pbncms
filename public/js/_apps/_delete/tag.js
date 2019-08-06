$("select[name=functionsSelect]").on('change',function(){
				
				$(this).attr("dissabled","dissabled");
				
				var list = $('#tablewrapper input:checked[name="listpos[]"]').map(function () 
																				{ 	return $(this).val(); })
																		.get();
				var status = $(this).attr('data-id');	
				
				switch(this.value)
				{
					case '1':
						$(".popup").show();
					break;
					case '2':
						switchScripts(list,'off');
					break;
					case '3':
						removeScripts(list);
					break;
					case '4':
						editScripts(list);
					break;
					case '5':
						switchScripts(list,'on');
					break;
				}
				
				$("select[name=functionsSelect]").prop('selectedIndex', 0);
				
			});
			
			$(document).on('click','#closePopup',function()
			{
				$(this).parents(".popup").hide().find("#title").html('Добавить тег');
				$("input[name=name]").val('');
				$("textarea[name=text]").val('');
				$(".popup select").prop('selectedIndex', 0);
			});
			
			function editScripts(list)
			{
				if(list.length > 1) 
				{
					Message('warning',"Массовое редактирование недоступно, сделайте выбор 1 тега");
				}
				else if(list.length == 0)
				{
					Message('warning',"Не выбран тег для редактирования");
				}
				else
				{
					$.ajax({
							url: "/admin/tagManagerEdit/",
							type: "POST",
							data:{list:list},
							success: function(data){
								console.log(data);
								var arr = JSON.parse(data);
								
								switch(arr.status)
									{
										case 'success' :
											
											window.oldname = arr.response.name;
											
											$("input[name=name]").val(arr.response.name);
											$("textarea[name=text]").val(arr.response.text);
											$("select[name=position]").val(arr.response.position);
											$("select[name=public]").val(arr.response.public);
											$(".popup").find("#title").html('Редактировать тег');
											$(".popup").find("input[type=submit]").val("Сохранить");
											setTimeout('$(".popup").show()', 500);
											
											
										
										break;
										case 'warning':
											Message('warning',arr.response);
										break;
										case 'error':
											Message('error',arr.response);					
										break;
									}
							}
					});
				}
			}
			
			function removeScripts(list)
			{
				if(list.length > 0 && confirm("Вы действительно хотите удалить выбранные скрипты?")) {
					
					window.removelcol = list;
					
					$.ajax({
							url: "/admin/tagManagerDel/",
							type: "POST",
							data:{list:list},
							success: function(data){
							
								var arr = JSON.parse(data);
				
								switch(arr.status)
									{
										case 'success' :
											removelcol.forEach(function(item,i,ar){$(".tinytable tbody").find("tr[data-id='"+item+"']").remove();});
											Message('success');	
										break;
										case 'warning':
											Message('warning',arr.response);
										break;
										case 'error':
											Message('error',arr.response);					
										break;
									}
							}
					});
				}
			}
			
			function switchScripts(list,status)
			{
				window.col = list;
				
					$.ajax({
							url: "/admin/tagManagerSwitch/",
							type: "POST",
							data:{list:list,status:status},
							success: function(data){
								console.log(data);
								var arr = JSON.parse(data);
				
								switch(arr.status)
									{
										case 'success' :
											Message('success');	
											col.forEach(function(item,i,ar){$(".tinytable tbody").find("tr[data-id='"+item+"']").find("td:nth-child(4)").html("<div class='"+status+"'>&nbsp;</div>");});
											
										break;
										case 'warning':
											Message('warning',arr.response);
										break;
										case 'error':
											Message('error',arr.response);					
										break;
									}
							}
					});
			}
			
			function Message(status,text = null)
			{
				switch(status)
				{
					case 'success' :
						if(!text) text = 'Операция завершена успешно';
						
						$(".windowStatus").html(text).show();	
						setTimeout('$(".windowStatus").hide().removeAttr("id")', 3000);	
					break;
										
					case 'warning':
						$(".windowStatus").attr('id','warning').
											html(text).
											show();
						setTimeout('$(".windowStatus").hide().removeAttr("id")', 3000);		
											
					break;
										
					case 'error':
						$(".windowStatus").attr('id','error').
											html(text).
											show();
						setTimeout('$(".windowStatus").hide().removeAttr("id")', 3000);						
					break;
				}
			}
			
			
			$(document).ready(function() { 
			
				$('form').on('submit', function() {  
					
					
					window.data = $(this).assocarrform();
					
					if(data.name.length >= 4 && data.text.length >= 10)
					{
							var method = 'tagManagerAdd';
							
							if(window.oldname) 
							{
								method = 'tagManagerSaveEdit';
								data.oldname = window.oldname;
							}
							
							$.ajax({
								type : "POST",
								url: "/admin/"+method+"/",
								data: data,
								success: function(res){
									
									arr = JSON.parse(res);
									
									switch(arr.status)
									{
										case 'success' :
										
											$(".popup").fadeOut(300,function()
											{
												$(".windowStatusPopup").removeAttr('style'); 
												$(".popup").find('input').val(''); 
												$(".popup").find('textarea').val(''); 
											});
											if(window.oldname) 
											{
												var data_arr = [data.oldname];
												
												data_arr.forEach(function(item,i,ar){
																				var tr = $(".tinytable tbody").find("tr[data-id='"+item+"']");
																						$(tr).attr("data-id",data.name);
																						$(tr).find("td:nth-child(1)").find("input").val(data.name);
																						$(tr).find("td:nth-child(2)").html(data.name);
																						$(tr).find("td:nth-child(3)").html(data.position);
																						if(data.public == 1) htm = "<div class='on'>&nbsp;</div>";
																						else htm = "<div class='off'>&nbsp;</div>";
																						$(tr).find("td:nth-child(4)").html(htm);
																						
																				});
											
											}
											else
											{
												arr.response.forEach(function(item,i,ar)
												{	
													var htm = "<tr data-id='"+item.name+"'><td><input type='checkbox' value='"+item.name+"' name='listpos[]'>"+"</td><td>"+item.name+"</td><td>"+item.position+"</td>";
													
													if(item.public == 1)
													{
														htm += "<td><div class='on'>&nbsp;</div></td></tr>";
													}
													else
													{
														htm += "<td><div class='off'>&nbsp;</div></td></tr>";
													}
													
													$(".tinytable tbody").prepend(htm);
												});
											}
											
											$(".windowStatus").html("Операция завершена успешно").show();
											
											$(".windowStatus").fadeOut(5000,function(){$(".windowStatus").removeAttr('style')});	
										break;
										
										case 'warning':
											$(".windowStatusPopup").
																attr('style','border:1px solid orange;background:orange;').
																html(arr.response).
																show();
											$(".windowStatusPopup").fadeOut(5000,function(){$(".windowStatusPopup").removeAttr('style')});	
											
										
										break;
										
										case 'error':
											$(".windowStatusPopup").
																attr('style','border:1px solid red;background:#cc0000;color:#fff;').
																html(arr.response).
																show();
											$(".windowStatusPopup").fadeOut(5000,function(){$(".windowStatusPopup").removeAttr('style')});					
										break;
									}
								}
							});
					}
					else
					{
						$(".windowStatusPopup").
													attr('style','border:1px solid orange;background:orange;').
													html("Не заполнены обязательные поля").
													show();
						$(".windowStatusPopup").fadeOut(5000,function(){$(".windowStatusPopup").removeAttr('style')});		
					}
					
				}); 
				
			});
			
			
			$.fn.assocarrform = function() { var formData = {}; this.find('[name]').each(function() { formData[this.name] = this.value; }); return formData; };
			
			
			var sorter = new TINY.table.sorter('sorter','table',{
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
			})
			