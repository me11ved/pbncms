
if(!$("#not_data_zone").length)
{
var sorter1 = new TINY.table.sorter('sorter1','table_zone',{
		headclass:'head',
		ascclass:'asc',
		descclass:'desc',
		evenclass:'evenrow',
		oddclass:'oddrow',
		evenselclass:'evenselected',
		oddselclass:'oddselected',
		paginate:true,
		size:5,
		colddid:'columns',
		currentid:'currentpage',
		totalid:'totalpages',
		startingrecid:'startrecord',
		endingrecid:'endrecord',
		totalrecid:'totalrecords',
		hoverid:'selectedrow',
		pageddid:'pagedropdown',
		navid:'tablenav',
		sortcolumn:3,
		sortdir:-1,
		sum:[8],
		avg:[6,7,8,9],
		init:true
	}); 
}	
if(!$("#not_data_sub").length)
{
	var sorter2 = new TINY.table.sorter('sorter2','table_subdomain',{
			headclass:'head1',
			ascclass:'asc1',
			descclass:'desc1',
			evenclass:'evenrow1',
			oddclass:'oddrow1',
			evenselclass:'evenselected1',
			oddselclass:'oddselected1',
			paginate:true,
			size:50,
			colddid:'columns1',
			currentid:'currentpage1',
			totalid:'totalpages1',
			startingrecid:'startrecord1',
			endingrecid:'endrecord1',
			totalrecid:'totalrecords1',
			hoverid:'selectedrow1',
			pageddid:'pagedropdown1',
			navid:'tablenav1',
			sortcolumn:3,
			sortdir:-1,
			sum:[8],
			avg:[6,7,8,9],
			init:true
		}); 
	
}
	
$("select[name=functionsSelect]").on('change',function()
{
				
	$(this).attr("dissabled","dissabled");
				
	var list = $('#tablewrapper input:checked[name="listpos[]"]').map(function () { 	return $(this).val(); }).get();
	var status = $(this).attr('data-id');	
				
	switch (this.value)
	{
		case '1':
			$("#popup").show();
		break;
		case '2':
			switchZone(list,'off');
		break;
		case '3':
			if (confirm("Вы действительно хотите удалить выбранные зоны?")) delZone(list);
		break;
		case '4':
			editZone(list);
		break;
		case '5':
			switchZone(list,'on');
		break;
	}	
	$(this).prop('selectedIndex', 0);			
});

$("select[name=functionsSelect2]").on('change',function()
{
				
	$(this).attr("dissabled","dissabled");
				
	var list = $('#tablewrapper input:checked[name="listpos[]"]').map(function () { 	return $(this).val(); }).get();
	var status = $(this).attr('data-id');	
				
	switch (this.value)
	{
		case 'sub1':
			var l = getZone();
			$("#popup2").find("table tr:nth-child(7)").find("td:nth-child(2)").html(l);
			$("#popup2").show();
		break;
		case 'sub2':
			switchZone(list,'off');
		break;
		case 'sub3':
			if (confirm("Вы действительно хотите удалить выбранные поддомены?")) delSubdomain(list);
		break;
		case 'sub4':
			editSubdomain(list);
		break;
		case 'sub5':
			switchZone(list,'on');
		break;
	}	
	$(this).prop('selectedIndex', 0);			
});

$(document).ready(function() { 
	$('form').on('submit', function() {  
		window.d = $(this).form();
		var method = $(this).find("input[type=submit]").attr("app-method");
		switch (method) {
			case 'add_zone':
			if(d.name == '' || d.name == undefined) message("warning","Не заполнено поле зона",".windowStatusPopup",true);
				addZone(d);
			break;
			case 'edit_zone' :
				saveEditZone(d);
			break;
			case 'edit_subdomain':
				saveEditSubdomain(d);
			break;
			case 'add_subdomen':
				addSubdomain(d);
			break;
		}
	}); 
});
			
$(document).on('click','#closePopup*',function()
{
	$(this).parents(".popup").hide().find("#title").html('Добавить тег');
	$("input[name=name]").val('');
	$("textarea[name=text]").val('');
	$("#popup select").prop('selectedIndex', 0);
});


$(document).on('click','input[name=create_content]',function(){
	if($(this).prop("checked")) 
	{
		$(this).val(1);
		$("#popup2").find('input[name*=replace_text]').removeAttr("hidden");
	}	
	else 
	{
		$(this).val(0);
		$("#popup2").find('input[name*=replace_text]').attr("hidden","hidden");
	}
});
		
			function switchZone(list,status)
			{
				window.col = list;
				
					$.ajax({
							url: "/admin/geoZoneSwitch/",
							type: "POST",
							data:{list:list,status:status},
							success: function(data){
								console.log(data);
								var arr = JSON.parse(data);
				
								switch(arr.status)
									{
										case 'success' :
											message('success',arr.response,".windowStatus");	
											
											col.forEach(function(item,i,ar){$("#table_zone tbody").find("tr[data-id='"+item+"']").find("td:nth-child(5)").html("<div class='"+status+"'>&nbsp;</div>");});
										break;
										default :
											message(arr.status,arr.response,".windowStatus");			
										break;
									}
							}
					});
			}
			
			
			function addZone(d)
			{
				$.ajax({
							type : "POST",
							url: "/admin/geoAddZone/",
							data: d,
							success: function(r){
								
								arr = JSON.parse(r);
								
								switch(arr.status)
								{
										case 'success' :
											message('success',"Операция выполнена",".windowStatus");
											$("#popup").hide();
											
											if(arr.status == 'success')
											{
												var htm = '<tr data-id="'+arr.response[0].name+'" class="oddrow" onmouseover="sorter1.hover(1,1)" onmouseout="sorter1.hover(1,0)">'
																	+'<td class=""><input type="checkbox" value="'+arr.response[0].name+'" name="listpos[]"></td>'
																	+'<td class="">'+arr.response[0].name+'</td>'
																	+'<td class="">'+arr.response[0].telephone+'</td>'
																	+'<td class="oddselected">'+arr.response[0].email+'</td>'
																	+'<td class="">'+arr.response[0].address+'</td>'
																	+'<td class="">'+arr.response[0].worktime+'</td>'
																	+'<td class="">'+arr.response[0].subdomain+'</td>';
												
												if(arr.response[0].default == 1) htm += "<td><div class='on'>&nbsp;</div></td>";
												else htm += "<td><div class='off'>&nbsp;</div></td>";
												
												if(arr.response[0].public == 1) htm += "<td><div class='on'>&nbsp;</div></td>";
												else htm += "<td><div class='off'>&nbsp;</div></td>";	
												
												htm +='<td class="">'+arr.response[0].create_date+'</td></tr>';
													
												$("#table_zone tbody").prepend(htm);
													
												
											}
											
											
										break;
										case 'warning':
											message('warning',arr.response,".windowStatusPopup");
										break;
										case 'error':
											message('error',arr.response,".windowStatusPopup");					
										break;
								}
							}
				});	
			}
			
			function delZone(list)
			{
				if (list.length > 0)					
				{
					$.ajax({
								type : "POST",
								url: "/admin/geoDelZone/",
								data: {list: list},
								success: function(r){
									console.log(r);
									arr = JSON.parse(r);
									
									switch(arr.status)
									{
											case 'success' :
												message('success',arr.response+". Данные обновлены, <a href='"+document.location.href+"'>Обновить</a>",".windowStatus");
												$("#popup").hide();
												
												$("#table_zone tr").each(function(){
													tr = $(this);
													id = $(this).find("td:nth-child(1) input").val();
													
													list.forEach(function(item,i,arr){
														if(id == item)
														{
															tr.remove();
															sorter1;
														}
													});
												});
												
											break;
											case 'warning':
												
												message('warning',arr.response,".windowStatus");
											break;
											case 'error':
												message('error',arr.response,".windowStatus");					
											break;
									}
								}
					});	
				}
				else
				{
						message('warning',"Не выбраны элементы",".windowStatus");
				}
			}
			
			
		function editZone(id)
		{
			console.log(id);
			if(id[0])
			{
				id = id[0];
					$.ajax({
									type : "POST",
									url: "/admin/geoEditZone/",
									data: {id: id},
									success: function(r){
										
										console.log(r);
										arr = JSON.parse(r);
										
										switch(arr.status)
										{
											case 'success' :
											
												$("#popup").fadeIn(1000,function()
												{
													var d = arr.response[0];
													window.oldnamezone = d.name;
													$(this).find('textarea').hide();
													$(this).find('#title').text("Редактировать зону");;
													
													$(this).find('input[name=name]').val(d.name); 
													$(this).find('input[name=telephone]').val(d.telephone); 
													$(this).find('input[name=email]').val(d.email); 
													$(this).find('input[name=address]').val(d.address); 
													$(this).find('input[name=worktime]').val(d.worktime); 
													$(this).find('select[name=default]').prop('selectedIndex', d.default); 
													$(this).find('select[name=public]').prop('selectedIndex', d.public);
													$(this).find('input[type=submit]').val("Сохранить").attr('app-method','edit_zone');
												});
									
											break;
										}
									}	
					});
				}
				else
				{
					message('warning',"Не задана зона для редактирования",".windowStatus");
				}
		}
		
		function saveEditZone(d)
		{
			console.log(d);
			
			if(d)
			{
				d.oldnamezone = window.oldnamezone;
					$.ajax({
									type : "POST",
									url: "/admin/geoSaveEditZone/",
									data: {d: d},
									success: function(r)
									{
										console.log(r);
										arr = JSON.parse(r);
										$("#popup").hide();
										if(arr.status == 'success')
										{
											$("#popup input*").val('');
											
											if(window.oldnamezone) 
											{
												$("#table_zone tr").each(function()
												{
													if( $(this).find("td:nth-child(2)").html() == window.oldnamezone)
													{
														$(this).find("td:nth-child(1) input").val(d.name);
														$(this).find("td:nth-child(2)").html(d.name);
														$(this).find("td:nth-child(3)").html(d.telephone);
														$(this).find("td:nth-child(4)").html(d.email);
														$(this).find("td:nth-child(5)").html(d.address);
														$(this).find("td:nth-child(6)").html(d.worktime);
														
														if(d.public == 1) $(this).find("td:nth-child(9) div").attr("class","on");
														else $(this).find("td:nth-child(9) div").attr("class","off");
														
														if(d.default == 1) $(this).find("td:nth-child(8)").html("<div class='on'>&nbsp;</div>");
														else $(this).find("td:nth-child(8)").html("<div class='off'>&nbsp;</div>");														
													}
												});
											}
										}
										message(arr.status,arr.response,".windowStatus");
									}	
					});
				}
				else
				{
					message('warning',"Не заданы параметры",".windowStatus");
				}
			
		}
		
		function getZone()
		{
			$.ajax({	type : "GET",
						async: false,
						url: "/admin/geoGetZone/",
						success: function(r)
						{
							d = JSON.parse(r);
							switch(d.status)
							{
											case 'success' :
													
												window.list = "<select name='id_zone' class='w100'>";
													
												d['response'].forEach(function(item,i,ar){list += '<option value="'+item.id+'">'+item.name+'</option>'});
													
												list += '</select>';
												
											break;
											case 'warning':
												message('warning',d.response,"#windowStatus2");
											break;
										}
						}	
					});
			
			return window.list;	
		}	
		
		function addSubdomain(d)
		{
			console.log(d);
			
			if (d['name_ru'].length >= 1)
			{
				$.ajax({
							type : "POST",
							url: "/admin/geoAddSubdomain/",
							data: d,
							success: function(r){
								console.log(r);
								arr = JSON.parse(r);
								
								switch(arr.status)
								{
										case 'success' :
											message('success',arr.response+". Данные обновлены, <a href='"+document.location.href+"'>Обновить</a>","#windowStatus2");
											$("#popup2").hide();
										break;
										default :
											message(arr.status,arr.response,"#windowStatusPopup2");
										break;
										
								}
							}
				});		
			}
			else
			{
				message('warning',"Заполните поле Название","#windowStatusPopup2");
			}
		}
		
		function delSubdomain(data)
		{
			if(data.length > 0)
			{
				$.ajax({
							type : "POST",
							url: "/admin/geoDelSubdomain/",
							data: {data:data},
							success: function(r){
								console.log(r);
								arr = JSON.parse(r);
								
								switch(arr.status)
								{
										case 'success' :
											message('success',"Данные обновлены, <a href='"+document.location.href+"'>Обновить</a>","#windowStatus2");
											
										break;
										default :
											message(arr.status,arr.response,"#windowStatus2");
										break;
										
								}
							}
				});		
				
			}
			else
			{
				message('warning',"Нужно выбрать элементы списка","#windowStatus2");
			}
			
		}
		
		
		function editSubdomain(id)
		{
			console.log(id);
			if(id[0])
			{
				id = id[0];
					$.ajax({
									type : "POST",
									url: "/admin/geoEditSubdomain/",
									data: {id: id},
									success: function(r){
										
										console.log(r);
										arr = JSON.parse(r);
										
										switch(arr.status)
										{
											case 'success' :
												$("input[type=checkbox]").prop('checked',false);
												
												$("#popup2").fadeIn(1000,function()
												{
													$(this).find('#title').text("Редактировать поддомен");
													
													item = arr['response'][0];
													window.idsub = item.id;
													var l = getZone();
													$(this).find("table tr:nth-child(7)").find("td:nth-child(2)").html(l);
														
													$(this).find('input[name=name_ru]').val(item.name_ru); 
													$(this).find('input[name=name_en]').val(item.name_en); 
													$(this).find('input[name=phone]').val(item.phone); 
													$(this).find('input[name=email]').val(item.email); 
													$(this).find('input[name=adr]').val(item.adr); 
													$(this).find('input[name=time]').val(item.time); 
													$(this).find('select[name=id_zone]').val(item.id_zone); 
													$(this).find('select[name=public]').prop('selectedIndex', item.public);
													$(this).find('input[name=name_ru]').val(item.name_ru); 
													$(this).find("table tr:nth-child(9)").hide();
													$(this).find('input[name=create_content]').val(""); 
													$(this).find('input[type=submit]').val("Сохранить").attr('app-method','edit_subdomain');
												});
									
											break;
										}
									}	
					});
				}
				else
				{
					message('warning',"Не задан поддомен редактирования",".windowStatus2");
				}
			
		}
		
		function saveEditSubdomain(d)
		{
			console.log(d);
			
			if(d)
			{
				d.id = window.idsub;
					$.ajax({
									type : "POST",
									url: "/admin/geoSaveEditSubdomain/",
									data: {d: d},
									success: function(r)
									{
										console.log(r);
										arr = JSON.parse(r);
										$("#popup2").hide();
										message(arr.status,arr.response,".windowStatus2");
									}	
					});
				}
				else
				{
					message('warning',"Не заданы параметры",".windowStatus2");
				}
			
		}
		
		$.fn.form = function() {
		var formData = {};
		this.find('[name]').each(function() {
			formData[this.name] = this.value;  
		})
		return formData;
	};