
	
$("select[name=functionsSelect]").on('change',function()
{
				
	$(this).attr("dissabled","dissabled");
				
	var list = $('#tablewrapper input:checked[name="listpos[]"]').map(function () { 	return $(this).val(); }).get();
	var status = $(this).attr('data-id');	
				
	switch (this.value)
	{
		case 'rd1':
			$(".popup").show();
		break;
		case 'rd2':
			switchRD(list,'off');
		break;
		case 'rd5':
			switchRD(list,'on');
		break;
		case 'rd3':
			if (confirm("Вы действительно хотите удалить выбранные редиректы?")) delRD(list);
		break;
	}	
	$(this).prop('selectedIndex', 0);			
});


$(document).ready(function() { $('form').on('submit', function() { window.d = $(this).form(); var method = $(this).find("input[type=submit]").attr("app-method"); switch (method) { case 'add_rd': if(d.to == '' || d.to == undefined || d.from == '' || d.from == undefined) { message("warning","Не заполнены поля",".windowStatusPopup",true); return false; } addRD(d); break; } }); });		
$(document).on('click','#closePopup*',function() { $(this).parents(".popup").hide().find("#title").html('Добавить редирект'); $("textarea*").val(''); $("#popup select").prop('selectedIndex', 0); });
		
			function switchRD(list,status)
			{
				window.col = list;
				
					$.ajax({
							url: "/admin/redirectSwitch/",
							type: "POST",
							data:{list:list,status:status},
							success: function(data){
								console.log(data);
								var arr = JSON.parse(data);
				
								switch(arr.status)
									{
										case 'success' :
											message('success',arr.response,".windowStatus");	
											
											col.forEach(function(item,i,ar){$("#table tbody").find("tr[data-id='"+item+"']").find("td:nth-child(6)").html("<div class='"+status+"'>&nbsp;</div>");});
										break;
										default :
											message(arr.status,arr.response,".windowStatus");			
										break;
									}
							}
					});
			}
			
			
			function addRD(d)
			{
				$.ajax({
							type : "POST",
							url: "/admin/redirectAdd/",
							data: d,
							success: function(r){
								
								arr = JSON.parse(r);
								console.log(arr);
								switch(arr.status)
								{
										case 'success' :
											message('success',"Операция выполнена",".windowStatus");
											$("#popup").hide();
											
											if(arr.status == 'success')
											{
												var htm = '<tr data-id="'+arr.response[0].id+'" class="oddrow" onmouseover="sorter.hover(1,1)" onmouseout="sorter.hover(1,0)">'
																	+'<td class=""><input type="checkbox" value="'+arr.response[0].id+'" name="listpos[]"></td>'
																	+'<td class="">'+arr.response[0].from+'</td>'
																	+'<td class="">'+arr.response[0].to+'</td>'
																	+'<td class="oddselected">'+arr.response[0].user+'</td>'
																	+'<td class="">'+arr.response[0].create_date+'</td>';
												
												if(arr.response[0].public == 1) htm += "<td><div class='on'>&nbsp;</div></td>";
												else htm += "<td><div class='off'>&nbsp;</div></td>";	
												
												htm +='</tr>';
													
												$("#table tbody").prepend(htm);
													
												
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
			
			function delRD(list)
			{
				if (list.length > 0)					
				{
					$.ajax({
								type : "POST",
								url: "/admin/redirectDel/",
								data: {list: list},
								success: function(r){
									console.log(r);
									arr = JSON.parse(r);
									
									switch(arr.status)
									{
											case 'success' :
												message('success',"Данные обновлены",".windowStatus");
												$("#popup").hide();
												
												$("#table tr").each(function(){
													tr = $(this);
													id = $(this).find("td:nth-child(1) input").val();
													
													list.forEach(function(item,i,arr){
														if(id == item)
														{
															tr.remove();
															sorter;
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
		
		$.fn.form = function() {
		var formData = {};
		this.find('[name]').each(function() {
			formData[this.name] = this.value;  
		})
		return formData;
	};
	
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
				sortcolumn:4,
				sortdir:-1,
				init:true
			})