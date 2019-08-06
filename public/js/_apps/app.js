	/* Front-End Часть для CMS */
	/* Модуль: Общий */
	
	/* Модуль: Общий */
	/* Закрыть попап */
	
	$(document).on('click','.close',function()
	{
		delete window.edit_id;
		
		clearForm("#AddModal");
		
		$(".modal-title").text(window.title_old);
		if (CKEDITOR.instances["description"] != undefined) {
			CKEDITOR.instances.description.setData('');
		}
		$("#AddModal #CategoryList").find('a').eq(0).click();  

		delete window.title_old;
		
	})

	/* Модуль: Общий */
	/* Очистить форму в попап окне */
	
	$(document).on('click','.clearPopup',function()
	{
		var id = $(this).closest(".modal").attr("id");
		
		clearForm("#"+id);
		
		if (CKEDITOR.instances["description"] != undefined) {
			CKEDITOR.instances.description.setData('');
		}
		
		$("#AddModal #CategoryList").find('a').eq(0).click();  

	});
	
	/* Модуль: Общий */
	/* Сделать из формы ассоц массив */
	
	$.fn.form = function() {
		var formData = {};
		this.find('[name]').each(function() {
			formData[this.name] = this.value;  
		})
		return formData;
	};
	
	/* Модуль: Общий */
	/* Включение отладки вывода */
	window.Debug = false;


	/* Модуль: Общий */
	/* Вывод сообщения */
	/* Добавлен: 12.10.2018 */
	
	function message(status,text = null,id,ret = false)
	{
		switch(status)
		{
			case 'success' :
				if(!text) text = 'Операция завершена успешно';
				
				$(id).attr("class","alert alert-success").html(text).show();	
				setTimeout('$("'+id+'").hide().removeAttr("class")', 5000);	
				
			break;
								
			case 'warning':
				$(id).attr('class','alert alert-warning').
									html(text).
									show();
				setTimeout('$("'+id+'").hide().removeAttr("class")', 5000);
			break;
								
			case 'error':
				$(id).attr('class','alert alert-danger').
									html(text).
									show();
				setTimeout('$("'+id+'").hide().removeAttr("class")', 5000);						
			break;
		}
		
		if(ret)  window.stop();
	}
	
	
	/* Модуль: Общий */
	/* Почистить форму */
	
	function clearForm(name) {
		if (window.title_old)
		{
			$(name).find('.modal-title').text(window.title_old); 
		}
		
		$(name).find('input[type=text]').val(''); 
		$(name).find('textarea').val(''); 
	}
	
	/* Модуль: Общий */
	/* Курл AJAX */

	function curl(p)
	{
		
		if(p[0].url)
		{
			var response = null;
			
			if (window.Debug) p[0].url = p[0].url+"?debug=show";
			
			if(!p[0].type) p[0].type = "POST";
			
			console.log("Params:");
			console.log(p[0].params);
			
			var result = $.ajax({
								async: false,
								type : p[0].type,
								url: p[0].url,
								data: p[0].params
							});
			
			if (window.Debug)
			{	
				console.log("Debug:");
				console.log(result.responseText);
				return false;
			}
			if(!p[0].json) result.responseText = JSON.parse(result.responseText);	
			
			console.log(result.responseText);
			
			return result.responseText;		
		}
		else
		{
			console.log("Нет данных для запроса ajax");
		}
	}
	
	/* Модуль: Общий */
	/* Обработка кнопки выбрать все */
	
	var mass = [];
	
	$(document).on('click','.allcheck', function(){

		 if($(this).is(':checked')) {
			 $(this).closest("table").find('tr td:nth-child(1)').each(function( index ) {
			  if($(this).parents('tr').attr('style') != 'display: none;' ) {
				  
				$(this).find('input[type=checkbox]').prop('checked',true);
				var id =  $(this).find('input[type=checkbox]').attr('value');
				window.mass.push(id) ;
				
			  }
			  
			});
			
		 console.log(window.mass);
		 
		 }
		 else {
		 
		  window.mass = [];
		 $(this).parents("table").find('input[type=checkbox]').prop('checked',false);
		 console.log(window.mass);
		 
		 }
	});   
	
	/* Модуль: Общий */
	/* Функция для обработки формы после нажатия */
	
	$(document).ready(function() 
	{ 
		$('form').on('submit', function() 
		{  
			
			window.d = $(this).form();
			var method = $(this).find("input[type=submit]").attr("app-method");
			
			switch (method) {
				/* Категории */
				case 'add_cat':
				if(d.title == '' || d.title == undefined) 
				{
					message("warning","Не заполнено название",".modal-body #alert",true);
					return false;
				}	
					addCat(d);
				break;
				case 'save_cat' :
					saveEditCat(d);
				break;
				/* Страницы */
				case 'add_page':
					if (d.title == '' || d.title == undefined) {
						message("warning", "Не заполнены поля", ".modal-body #alert", true);
						return false;
					}
					addPage(d);
					break;
				case 'save_page':
					saveEditPage(d);
				break;
				/* Пользователи */
				case 'add_user':
				if(d.name == '' || d.name == undefined) 
				{
					message("warning","Не заполнено логин",".modal-body #alert",true);
					return false;
				}	
					addUser(d);
				break;
				case 'save_user' :
					saveEditUser(d);
				break;
				case 'add_tag' :
					addScripts(d);
				break;
				/* Тег менеджер */
				case 'save_tag' :
					saveEditScripts(d);
				break;
				/* Редиректы */
				case 'add_rd':
					if (d.to == '' || d.to == undefined || d.from == '' || d.from == undefined) 
					{
						message("warning", "Не заполнены поля", ".modal-body #alert", true);
						return false;
					}
					addRD(d);
                break;
				/* Навигация */
				case 'add_nav':
				
					if (d.name == '' || d.name == undefined || d.name_en == '' || d.name_en == 'nav-') {
						message("warning", "Не заполнены поля", ".modal-body #alert", true);
						return false;
					}
					addNav(d);
					break;
				case 'save_nav':
					saveEditNav(d);
				break;
				
				/* Зоны */
				case 'add_zone':
					if(d.name == '' || d.name == undefined) 
					{
						message("warning","Не заполнено поле зона",".modal-body #alert",true);
						return false;
					}
					addZone(d);
				break;
				case 'edit_zone' :
					saveEditZone(d);
				break;
				
				/* Поддомены */
				case 'edit_subdomain':
					saveEditSubdomain(d);
				break;
				case 'add_subdomen':
					addSubdomain(d);
				break;
				
				/* Настройки сайта */
				case 'save_setting_site' :
					saveSettingSite(d);
				break;
				
				/* Перелинковка */
				case 'add_relink' :
					addReLink(d);
				break;
			}
		}); 
	});
	
	/* Модуль: Общий */
	/* Функция для выбора операции */
	
	$(document).on('change',"select[name=functionsSelect]",function()
	{
					
		$(this).attr("dissabled","dissabled");
					
		var list = $('table input:checked[name="listpos[]"]').map(function () { 	return $(this).val(); }).get();
		var status = $(this).attr('data-id');	
		
		console.log(this.value);
		console.log(list);
					
		switch (this.value)
		{
			/* Категории */
			case 'cat1':
				$("#AddModal").modal('show');
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
			/* Категории */
			
			/* Страницы */
			case 'page1':
				$("#AddModal").modal('show');
			break;
			case 'page2':
				editPage(list);
			break;
			case 'page3':
				switchPage(list,'off');
			break;
			case 'page4':
				switchPage(list,'on');
			break;
			case 'page5':
				removePage(list);
			break;
			/* Страницы */
			
			/* Пользователи */
			case 'user1':
				$("#AddModal").modal('show');
			break;
			case 'user2':
				switchUser(list,'off');
			break;
			case 'user3':
				delUser(list);
			break;
			case 'user4':
				editUser(list);
			break;
			case 'user5':
				switchUser(list,'on');
			break;
			/* Пользователи */
			
			/* Тег менеджер */
			case 'tag1':
				$("#AddModal").modal('show');
			break;
			case 'tag2':
				switchScripts(list,'off');
			break;
			case 'tag3':
				removeScripts(list);
			break;
			case 'tag4':
				editScripts(list);
			break;
			case 'tag5':
				switchScripts(list,'on');
			break;
			/* Тег менеджер */
			
			/* Редиректы */
			case 'rd1':
				$("#AddModal").modal('show');
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
			/* Редиректы */
			
			/* Навигация */
			case 'nav1':
				$("#AddModal").modal("show");
			break;
			case 'nav2':
				editNav(list);
			break;
			case 'nav3':
				switchNav(list,'off');
			break;
			case 'nav4':
				switchNav(list,'on');
			break;
			case 'nav5':
				removeNav(list);
			break;
			/* Навигация */
			
			/* Поддомены */
			case 'sub1':
				
				$("#AddModalS").find("select[name=id_zone]").html(getZone());
				$("#AddModalS").modal("show");
			break;
			case 'sub2':
				switchSubdomain(list,'off');
			break;
			case 'sub3':
				if (confirm("Вы действительно хотите удалить выбранные поддомены?")) delSubdomain(list);
			break;
			case 'sub4':
				editSubdomain(list);
			break;
			case 'sub5':
				switchSubdomain(list,'on');
			break;
			/* Поддомены */
			
			/* Зоны */
			case 'zone1':
				$("#AddModalZ").modal("show");
			break;
			case 'zone2':
				switchZone(list,'off');
			break;
			case 'zone3':
				if (confirm("Вы действительно хотите удалить выбранные зоны?")) delZone(list);
			break;
			case 'zone4':
				editZone(list);
			break;
			case 'zone5':
				switchZone(list,'on');
			break;
			/* Зоны */
			
			/* Бэкапы */
			case 'backup1':
		createBackup({"files" : true, "base" : true});
			break;
				
			case 'backup2':
				createBackup({"files" : true, "base" : true,"refresh" : true});
			break;
			
			case 'backup3':
				createBackup({"files" : true});
			break;
			
			case 'backup4':
				createBackup({"base" : true});
			break;
			
			case 'backup5':
				createBackup({"refresh" : true});
			break;
			/* Бэкапы */
			
			/* Перелинковка */
			case 'relink1':
				
				var count = $(".hs-menu-inner:first a").length;
				if (count == 1){
					$(".hs-menu-inner").append(getPageListAll());
				}
				
				$("#AddModal").modal('show');
			break;
			case 'relink2':
				delRelink(list);
			break;
			/* Перелинковка */
			
		}	
		$(this).prop('selectedIndex', 0);			
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
				
				var t = $('#tableContent').DataTable();
				
				if (r.response.public == 1)
				{
					var status =  '<div class="on">&nbsp;</div>';
				}
				else
				{
					var status =  '<div class="off">&nbsp;</div>';
				}
				
				var content = [	'',
								'<a href="/'+r.response.href+'" target="_blank">'+r.response.title+'</a>',
								'',
								'',
								status,
								r.response.create_date];
				
				if (r.response.id) content[0] = '<input type="checkbox" value="'+r.response.id+'" name="listpos[]">';
				if (r.response.h1) content[2] = r.response.h1;
				if (r.response.meta_title) content[3] = r.response.meta_title;
				
				var row = t.row.add(content).draw();
				
				row.nodes().to$().attr('data-id', r.response.id);
				
				$("#AddModal").modal('hide');
				
				clearForm("#AddModal");
				
				message('success',"Операция выполнена","#alertBody");
				
			break;
			
			default :
				message(r.status,r.response,".modal-body #alert");
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
					
					var t = $('#tableContent').DataTable();
				
					removelcol.forEach(function(item,i,ar)
					{
						t.row( $("#tableContent").find("tr[data-id='"+item+"']") ).remove().draw();
					});
					
					message('success',r.response,"#alertBody");	
					
				break;
				default :
					message(r.status,r.response,"#alertBody");
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
				message('success',arr.response,"#alertBody");
				
				var t = $('#tableContent').DataTable();				
				
				col.forEach(function(item,i,ar){
					t.cell($("#tableContent tr[data-id='"+item+"']").find("td:nth-child(5)")).data("<div class='"+status+"'>&nbsp;</div>");
				});
			break;
			default :
				message(arr.status,arr.response,"#alertBody");
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
					window.title_old = $('.modal-title').text();
					
					$('.modal-title').text("Редактировать категорию");
					
					CKEDITOR.instances.description.setData(r.response.description); 
					
					$.each($('form input').serializeArray(), function(i, field) {
						var name = field.name;
						if(r.response[name] != '' || r.response[name] != null) 
						{
							$("#AddModal").find('input[name='+name+']').val(r.response[name]); ;
						}
						else
						{
							$("#AddModal").find('input[name='+name+']').val('');
						}
					});
					
					$("#AddModal").find('select[name=public]').prop('value', r.response.public);
					
					$("#AddModal").find('input[type=submit]').val("Сохранить").attr('app-method','save_cat');
					
					$("#AddModal").modal(1000);
				
			break;
			
			default :
				message(r.status,r.response,"#alertBody");
			break;
			}
		}
		else
		{
			message('warning',"Не выбран элемент для редактирования","#alertBody");
		}
	}
	
	/* Модуль: Категория */
	/* Отправка данных для сохранения категории */
	
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
					
					var t = $('#tableContent').DataTable();
				
					if (res.response.public == 1)
					{
						var status =  '<div class="on">&nbsp;</div>';
					}
					else
					{
						var status =  '<div class="off">&nbsp;</div>';
					}
				
					var content = [	'',
									'<a href="/'+res.response.href+'" target="_blank">'+res.response.title+'</a>',
									'',
									'',
									status,
									res.response.create_date];
				
					if (res.response.id) content[0] = '<input type="checkbox" value="'+res.response.id+'" name="listpos[]">';
					if (res.response.h1) content[2] = res.response.h1;
					if (res.response.meta_title) content[3] = res.response.meta_title;
				
					t.row($("#tableContent tr[data-id='"+res.response.id+"']")).data(content);
						
					delete window.edit_id;
					CKEDITOR.instances.description.setData(""); 
					
					$("#AddModal").modal("hide");
					$("#AddModal").find('input[type=submit]').val("Добавить").attr('app-method','add_cat');
					
					clearForm("#AddModal");
					
					message('success',"Операция выполнена","#alertBody");
					
				break;
				default :
					message(res.status,res.response,".modal-body #alert");
				break;
			}	
		}
		else
		{
			console.log("Не найден id");
		}
	}

	/* Модуль: Страницы */
	/* Получение данных для редактирования страницы  */
	
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
					window.title_old = $('.modal-title').text();
						
					$('.modal-title').text("Редактировать страницу");
					
					CKEDITOR.instances.description.setData(r.response.description); 
					
					$.each($('form input').serializeArray(), function(i, field) {
						var name = field.name;
						if(r.response[name] != '' || r.response[name] != null) 
						{
							$("#AddModal").find('input[name='+name+']').val(r.response[name]); ;
						}
						else
						{
							$("#AddModal").find('input[name='+name+']').val('');
						}
					});
					
					if(r.response.id_category == 0) {
						$("#AddModal #CategoryList").find('a').eq(0).click();  
					}
					else {
						$("#AddModal #CategoryList").find('a[data-value='+r.response.id_category+']').click(); 
					}
					
					$("#AddModal").find('select[name=public]').prop('value', r.response.public);
					
					$("#AddModal").find('input[type=submit]').val("Сохранить").attr('app-method','save_page');
					
					$("#AddModal").modal(1000);
				
			break;
			
			default :
				message(r.status,r.response,"#alertBody");
			break;
			}
		}
		else
		{
			message('warning',"Не выбран элемент для редактирования","#alertBody");
		}
	}
	
	/* Модуль: Страницы */
	/* Добавление страницы  */
	
	function addPage(data)
	{
		data["description"] = CKEDITOR.instances.description.getData(); 
		
		var r = curl([{	
							"url" : "/admin/pageAdd",
							"params" : data}]);
							
							
		switch(r.status)
		{
			case 'success' :
				
				var t = $('#tableContent').DataTable();
				
				if (r.response.public == 1)
				{
					var status =  '<div class="on">&nbsp;</div>';
				}
				else
				{
					var status =  '<div class="off">&nbsp;</div>';
				}
				
				var content = [	'',
								'<a href="'+r.response.href+'" style="color:#000;" target="_blank">'+r.response.title+'</a>',
								'',
								'',
								'',
								status,
								r.response.create_date];
				
				if (r.response.id) content[0] = '<input type="checkbox" value="'+r.response.id+'" name="listpos[]">';
				if (r.response.cat) content[2] = r.response.cat;
				if (r.response.h1) content[3] = r.response.h1;
				if (r.response.meta_title) content[4] = r.response.meta_title;
				
				var row = t.row.add(content).draw();
				
				row.nodes().to$().attr('data-id', r.response.id);
				
				$("#AddModal").modal('hide');
				
				clearForm("#AddModal");
				
				
				message('success',"Операция выполнена","#alertBody");
				
			break;
			
			default :
				message(r.status,r.response,".modal-body #alert");
			break;
		}			
	}
	
	/* Модуль: Страницы */
	/* Удаление страницы  */
	
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
					
					var t = $('#tableContent').DataTable();
				
					list.forEach(function(item,i,ar)
					{
						t.row( $("#tableContent").find("tr[data-id='"+item+"']") ).remove().draw();
					});
					
					message('success',r.response,"#alertBody");	
					
				break;
				default :
					message(r.status,r.response,"#alertBody");
				break;
			}
		}
	}
	
	/* Модуль: Страницы */
	/* Включение/Отключение страницы  */
	
	function switchPage (list,status)
	{
		window.col = list;
		var arr = curl([{	"url" : "/admin/pageSwitch",
							"params" : {list:list, status:status}}]);
		switch(arr.status)
		{
			case 'success' :
				message('success',arr.response,"#alertBody");
				
				var t = $('#tableContent').DataTable();				
				
				col.forEach(function(item,i,ar){
					t.cell($("#tableContent tr[data-id='"+item+"']").find("td:nth-child(6)")).data("<div class='"+status+"'>&nbsp;</div>");
				});
			break;
			default :
				message(arr.status,arr.response,"#alertBody");
			break;
		}
	}
	
	
	/* Модуль: Страницы */
	/* Сохранение страницы после редактирования */
	
	function saveEditPage(d)
	{
		
		if(window.edit_id != undefined)
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
					$("#AddModal").find("input[type=submit]").attr("app-method","add_page").modal('hide');
					message('success',"Операция выполнена","#alert");
				break;
				default :
					message(res.status,res.response,"#alert");
				break;
			}
		}
		else
		{
			console.log("Сохранение страницы после редактирования: Не найден id");
		}
	}

	/* Модуль: Пользователи */
	/* Добавление пользователя */
	
	function addUser(data)
	{
		
		data.api = $('#AddModal input[name=api]').prop("checked");
		
		var r = curl([{		
				"url" : "/admin/usersAdd",
				"params" : data
			}]);
		
		switch(r.status)
		{
			case 'success' :
				
				var t = $('#tableContent').DataTable();
				
				if(r.response.active == 1) status = "<div class='on'>&nbsp;</div>";
				else status = "<div class='off'>&nbsp;</div>";	
				
				var content = [	'<input type="checkbox" value="'+r.response.id+'" name="listpos[]">',
								r.response.name,
								r.response.role,
								'','','',
								status];
				
				if (r.response.last_in) content[3] = r.response.last_in;
				if (r.response.comment) content[4] = r.response.comment;
				if (r.response.api_key) content[5] = r.response.api_key;
				
				var row = t.row.add(content).draw();
				
				row.nodes().to$().attr('data-id', r.response.id);
				
				$("#AddModal").modal('hide');
				
				clearForm("#AddModal");
				
				alert("Новые логин/пароль: "+r.response.newl+'/'+r.response.newp);
				
				message('success',"Операция выполнена","#alertBody");
				
			break;
			
			default :
				message(r.status,r.response,".modal-body #alert");
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
					
					var t = $('#tableContent').DataTable();
				
					removelcol.forEach(function(item,i,ar)
					{
						t.row( $("#tableContent").find("tr[data-id='"+item+"']") ).remove().draw();
					});
					
					message('success',r.response,"#alertBody");	
					
				break;
				default :
					message(r.status,r.response,"#alertBody");
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
				message('success',arr.response,"#alertBody");
				
				var t = $('#tableContent').DataTable();				
				
				col.forEach(function(item,i,ar){
					t.cell($("#tableContent tr[data-id='"+item+"']").find("td:nth-child(7)")).data("<div class='"+status+"'>&nbsp;</div>");
				});
			break;
			default :
				message(arr.status,arr.response,"#alertBody");
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
					window.title_old = $('.modal-title').text();
					
					$('.modal-title').text("Редактировать пользователя "+r.response.name);
					
					delete r.response.login;
					delete r.response.password;
					
					$.each($('form input').serializeArray(), function(i, field) {
						var name = field.name;
						if(r.response[name] != '' || r.response[name] != null) 
						{
							$("#AddModal").find('input[name='+name+']').val(r.response[name]); ;
						}
						else
						{
							$("#AddModal").find('input[name='+name+']').val('');
						}
					});
					
					switch(r.response.api) 
					{
						case "yes" :
							$("#AddModal").find('input[name=api]').prop('checked', true);
						break;
						case "no" :
							$("#AddModal").find('input[name=api]').prop('checked', false);
						break;
						
					}
					
					$("#AddModal").find('select[name=active]').prop('value', r.response.active);
					$("#AddModal").find('select[name=role]').prop('value', r.response.role);
					$("#AddModal").find('textarea[name=comment]').val(r.response.comment);
					
					$("#AddModal").find('input[type=submit]').val("Сохранить").attr('app-method','save_user');
					
					$("#AddModal").modal(1000);
					
				default :
					message(r.status,r.response,"#alertBody");
				break;
			}
		}
		else
		{
			message('warning',"Не выбран элемент для редактирования #1","#alertBody");
		}
	}
	
	/* Модуль: Пользователи */
	/* Сохранение данных после редактирования */
	
	function saveEditUser(d)
	{
		
		if(window.edit_id)
		{
			d.id = window.edit_id;
			d.api = $('#AddModal input[name=api]').prop("checked");
			
			var r = curl([{		
							"url" : "/admin/usersSaveEdit",
							"params" : d
						}]);
						
			
			switch(r.status)
					{
						case 'success':
							
							delete window.edit_id;
							
							var t = $('#tableContent').DataTable();
				
							if (r.response.active == 1) var status =  '<div class="on">&nbsp;</div>';
							else var status =  '<div class="off">&nbsp;</div>';
							
							var content = [	'<input type="checkbox" value="'+r.response.id+'" name="listpos[]">',
											r.response.name,
											r.response.role,
											'','','',
											status];
						
							if (r.response.last_in) content[3] = r.response.last_in;
							if (r.response.comment) content[4] = r.response.comment;
							if (r.response.api_key) content[5] = r.response.api_key;
							
							t.row($("#tableContent tr[data-id='"+r.response.id+"']")).data(content);
								
							$("#AddModal").modal("hide");
							$("#AddModal").find('input[type=submit]').val("Добавить").attr('app-method','add_user');
							
							clearForm("#AddModal");
							
							message('success',"Операция выполнена","#alertBody");
						break;
						default:
							message(r.status,r.response,".modal-body #alert");
						break;
					}		
		}
		else
		{
			console.log("Не найден id");
		}
	}
	
	
	/* Модуль: Тег менеджер */
	/* Получение данных для редактирования */
	
	function editScripts(list)
	{
		if(list.length == 0)
		{
			message('warning',"Не выбран тег для редактирования","#alertBody");
			return false;
		}
			
		var r = curl([{		
						"url" : "/admin/tagManagerEdit",
						"params" : { list : list }
					}]);
						
		switch(r.status)
		{
			case 'success' :
				
				window.edit_id = r.response.name;
				window.title_old = $('.modal-title').text();
					
				$('.modal-title').text("Редактировать тег");
				
				
				$.each($('form input').serializeArray(), function(i, field) {
					var name = field.name;
					if(r.response[name] != '' || r.response[name] != null) 
					{
						$("#AddModal").find('input[name='+name+']').val(r.response[name]); ;
					}
					else
					{
						$("#AddModal").find('input[name='+name+']').val('');
					}
				});
				
				$("#AddModal").find("textarea[name=text]").val(r.response.text);
				
				$("#AddModal").find('select[name=public]').prop('value', r.response.public);
				
				$("#AddModal").find('input[type=submit]').val("Сохранить").attr('app-method','save_tag');
				
				$("#AddModal").modal(1000);
			
			break;
			default :
				message(r.status,r.response,"#alertBody");
			break;
			
		}
	}
	
	function saveEditScripts(d)
	{
		
		if(window.edit_id)
		{
			d.oldname = window.edit_id;
			
			var r = curl([{		
							"url" : "/admin/tagManagerSaveEdit",
							"params" : d
						}]);
			switch(r.status)
			{
				case 'success':
					
					delete window.edit_id;
					
					var t = $('#tableContent').DataTable();
				
					if (r.response.public == 1) var status =  '<div class="on">&nbsp;</div>';
					else var status =  '<div class="off">&nbsp;</div>';
					
					var content = [	"<input type='checkbox' value='"+r.response.name+"' name='listpos[]'>",
										r.response.name,
										r.response.position,
										status];
					
					$("#tableContent tr[data-id='"+r.response.oldname+"']").attr("data-id",r.response.name);
					
					t.row($("#tableContent tr[data-id='"+r.response.name+"']")).data(content);
					
					$("#AddModal").modal("hide");
					$("#AddModal").find('input[type=submit]').val("Добавить").attr('app-method','add_tag');
					
					clearForm("#AddModal");
					message('success',"Операция выполнена","#alertBody");
					
				break;
				default:
					message(r.status,r.response,".modal-body #alert");					
				break;
			}		
		}
		else
		{
			console.log("Не найден id");
		}
	}
	
	/* Модуль: Тег менеджер */
	/* Массовое удаление тегов */
	
	function removeScripts(list)
	{
		if(list.length > 0 && confirm("Вы действительно хотите удалить выбранные скрипты?")) 
		{
			window.removelcol = list;
			
			var r = curl([{		
						"url" : "/admin/tagManagerDel",
						"params" : { list : list }
					}]);
			
			switch(r.status)
			{
				case 'success' :
					
					var t = $('#tableContent').DataTable();
				
					list.forEach(function(item,i,ar)
					{
						t.row( $("#tableContent").find("tr[data-id='"+item+"']") ).remove().draw();
					});
					
					message('success',r.response,"#alertBody");	
				break;
				default :
					message(r.status,r.response,"#alertBody");
				break;
			}
		}
	}
	
	/* Модуль: Тег менеджер */
	/* Включение/Отключение тегов */
	
	function switchScripts(list,status)
	{
		window.col = list;
		
		var r = curl([{		
						"url" : "/admin/tagManagerSwitch",
						"params" : { "list" : list, "status" : status}
					}]);
			
		switch(r.status)
		{
			case 'success' :
				
				var t = $('#tableContent').DataTable();
				col.forEach(function(item,i,ar){
					t.cell($("#tableContent tr[data-id='"+item+"']").find("td:nth-child(4)")).data("<div class='"+status+"'>&nbsp;</div>");
				});
				
				message(r.status,r.response,"#alertBody");	
			break;
			default:
				message(r.status,r.response,"#alertBody");
			break;
		}
	}
	
	/* Модуль: Тег менеджер */
	/* Добавление тега */
	function addScripts(data)
	{
		if(data.name.length >= 4 && data.text.length >= 10)
		{
			if(window.oldname) 
			{
				data.oldname = window.oldname;
			}
			
			var r = curl([{		
						"url" : "/admin/tagManagerAdd",
						"params" : data
					}]);
				
			switch(r.status)
			{
				case 'success' :
				
					var t = $('#tableContent').DataTable();
				
					if (r.response.public == 1) var status =  '<div class="on">&nbsp;</div>';
					else var status =  '<div class="off">&nbsp;</div>';
					
					var content = [	"<input type='checkbox' value='"+r.response.name+"' name='listpos[]'>",
										r.response.name,
										r.response.position,
										status];
					
					console.log(content);
					
					var row = t.row.add(content).draw();
					
					row.nodes().to$().attr('data-id', r.response.name);
					
					$("#AddModal").modal('hide');
					
					clearForm("#AddModal");
					
					message('success',"Операция выполнена","#alertBody");
						
				break;
				default :
					message(r.status,r.response,".modal-body #alert");				
				break;
			}
		}
		else
		{
			message('warning',"Не заполнены обязательные поля",".modal-body #alert");	
		}
		
	}
	
	/* Модуль: Тег менеджер */
	/* Добавление тега в поле скрипта */
	
	function addScriptTag(e)
	{
		switch (e)
		{
			case 'script':
				$("form").find("textarea[name=text]").val('<script type="text/javascript"></script>');
			break;
			case 'style' :
				$("form").find("textarea[name=text]").val("<style></style>");
			break;
		}
		
	}
	
	/* Модуль: Редиректы */
	/* Включение/Отключение редиректа */
	
	function switchRD(list,status)
	{
		window.col = list;
		var arr = curl([{		
						"url" : "/admin/redirectSwitch",
						"params" : { "list" : list, "status" : status}
					}]);
					
		switch(arr.status)
		{
			case 'success' :
				
				message('success',arr.response,"#alertBody");
				
				var t = $('#tableContent').DataTable();				
				
				col.forEach(function(item,i,ar){
					t.cell($("#tableContent tr[data-id='"+item+"']").find("td:nth-child(6)")).data("<div class='"+status+"'>&nbsp;</div>");
				});
			
			break;
			default :
				message(arr.status,arr.response,"#alertBody");			
			break;
		}
	}
			
	/* Модуль: Редиректы */
	/* Добавление редиректов */
	
	function addRD(d)
	{
		var arr = curl([{		
						"url" : "/admin/redirectAdd",
						"params" : d
					}]);
		
		switch(arr.status)
		{
				case 'success' :
					
					var t = $('#tableContent').DataTable();
				
					arr.response.forEach(function(item,i,ar)
					{
						if (item.public == 1) var status =  '<div class="on">&nbsp;</div>';
						else var status =  '<div class="off">&nbsp;</div>';
						
						var content = [	'<input type="checkbox" value="'+item.id+'" name="listpos[]">',
										'<a href="'+item.from+'" target="_blank">'+item.from+'</a>',
										'<a href="'+item.to+'" target="_blank">'+item.to+'</a>',
											item.user,
											item.create_date,
											status];
						
						var row = t.row.add(content).draw();
					
						row.nodes().to$().attr('data-id', item.id);
					});
					
					$("#AddModal").modal('hide');
					
					clearForm("#AddModal");
					
					message('success',"Операция выполнена","#alertBody");
					
				break;	
				default:
					message(arr.status,arr.response,".modal-body #alert");
				break;
		}
	}
	
	/* Модуль: Редиректы */
	/* Удаление редиректа */
	
	function delRD(list)
	{
		if (list.length > 0)					
		{
			var arr = curl([{		
						"url" : "/admin/redirectDel",
						"params" : {list: list}
					}]);
			
			switch(arr.status)
			{
					case 'success' :
						
						var t = $('#tableContent').DataTable();
				
						list.forEach(function(item,i,arr)
						{
							t.row( $("#tableContent").find("tr[data-id='"+item+"']") ).remove().draw();
						});
						
						message('success',arr.response,"#alertBody");	
						
					break;
					default :
						message(arr.status,arr.response,"#alertBody");			
					break;
			}			
		}
		else
		{
				message('warning',"Не выбраны элементы","#alertBody");
		}
	}
	
	/* Модуль: Навигация */
	/* Получение данных для редактирования */
	
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
					
					window.edit_id = r.response.id;
					
					$("#EditModal").find('select[name=public]').prop('value', r.response.menu.public);
					
					$.each($('#EditModal input').serializeArray(), function(i, field) {
						var name = field.name;
						if(r.response.menu[name] != '' || r.response.menu[name] != null) {
							$("#EditModal").find('input[name='+name+']').val(r.response.menu[name]); ;
						}
						else {
							$("#EditModal").find('input[name='+name+']').val('');
						}
					});
					
					$(".hs-menu-inner").append(getPageListAll());
					
					var cat = curl([{	"url" : "/admin/navigationGetCategory",
										"params" : {}
									}]); 			
					
					if(r.response.list.length > 0)
					{
						var list2 = '';
										
						r.response.list.forEach(function(item,i,ar)
						{
							list2 += '<li class="dd-item" data-id="'+item.position+'">'
										+'<div class="dd-handle dd3-handle"></div>'
												+'<div class="dd3-content">'
													+"<div class='fl w85'>"+item.name+"</div>"
														+"<div class='dd3-operation'>"
															+"<div id='plicon'>+</div>"
															+"<div id='delicon'>x</div>"
														+"</div>"
													+"</div>"
													+"<div class='opisanie'>"
														+"<form name='form' action='javascript:void(0);'>"
															+"<span>Название</span>&nbsp;<input type='text' name='name' value='"+item.name+"'>"
															+"<span>Ссылка</span>&nbsp;<input type='text' name='href' value='"+item.href+"'>"
														+"</form>"
													+"</div>";
							
							if (item.children != undefined)
							{
								
								list2 += '<ol class="dd-list">';
								
								item.children.forEach(function(item2,i,ar)
								{
									list2 += '<li class="dd-item" data-id="'+item2.position+'">'
												+'<div class="dd-handle dd3-handle"></div>'
														+'<div class="dd3-content">'
															+"<div class='fl w85'>"+item2.name+"</div>"
																+"<div class='dd3-operation'>"
																	+"<div id='plicon'>+</div>"
																	+"<div id='delicon'>x</div>"
																+"</div>"
															+"</div>"
															+"<div class='opisanie'>"
																+"<form name='form' action='javascript:void(0);'>"
																	+"<span>Название</span>&nbsp;<input type='text' name='name' value='"+item2.name+"'>"
																	+"<span>Ссылка</span>&nbsp;<input type='text' name='href' value='"+item2.href+"'>"
																+"</form></div></div></div></li>";							
								});
								
								list2 += '</ol>';
							}
							
							list2 += "</div></div></li>";
						});
						
						 
					}
					
							
					if(cat.status == 'success')
					{
						var list = '';
										
						cat.response.forEach(function(item,i,ar)
						{
							list += '<li class="dd-item" data-id="'+item.id+'">'
										+'<div class="dd-handle dd3-handle"></div>'
												+'<div class="dd3-content">'
													+"<div class='fl w85'>"+item.title+"</div>"
														+"<div class='dd3-operation'>"
															+"<div id='plicon'>+</div>"
															+"<div id='delicon'>x</div>"
														+"</div>"
													+"</div>"
													+"<div class='opisanie'>"
														+"<form name='form' action='javascript:void(0);'>"
															+"<span>Название</span>&nbsp;<input type='text' name='name' value='"+item.title+"'>"
															+"<span>Ссылка</span>&nbsp;<input type='text' name='href' value='"+item.href+"'>"
														+"</form>"
													+"</div>"
												+"</div>"
										+"</div>"
										+"</li>";
										});
					
					}
					$("#sortable1 .dd-list").html(list);
					$("#sortable2 .dd-list").html(list2);
										
					$('#sortable1').nestable({	group:1,
												maxDepth:2});
					$('#sortable2').nestable({group:1,maxDepth:2});
					
					
					$("#EditModal").find('input[type=submit]').val("Сохранить").attr('app-method','save_nav');
					
					$("#EditModal").modal(1000);
					
					
				break;
				
				default:
					message(d.status,d.response,"#alertBody");					
				break;
			}
		}
		else
		{
			message('warning',"Не выбран элемент для редактирования","#alertBody");
		}
	}
	
	/* Модуль: Навигация */
	/* Живой поиск */
	
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
											+'<div class="dd-handle dd3-handle"></div>'
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
					message('warning',d.response,"#alertBody");
				break;
				case 'error':
					message('error',d.response,"#alertBody");					
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
	
	/* Модуль: Навигация */
	/* Удаление/Редактирование пунктов */
	
	$(document).on("click","#plicon",function(){$(this).parents("li").find(".opisanie").toggle();});
	
	$(document).on("click","#delicon",function(){$(this).parents("li").remove();});
	
	/* Модуль: Навигация */
	/* Добавление нового меню */
	
	function addNav(data)
	{
		var arr = curl([{	"url" : "/admin/navigationAdd",
							"params" : data}]);
		switch(arr.status)
		{
			case 'success' :
				
				message('success',"Операция выполнена","#alertBody");
				
				if (arr.response.public == 1) var status =  '<div class="on">&nbsp;</div>';
				else var status =  '<div class="off">&nbsp;</div>';
					
				var t = $('#tableContent').DataTable();
				
				var content = [	'<input type="checkbox" value="'+arr.response.id+'" name="listpos[]">',
								arr.response.name,
								"{"+arr.response.name_en+"}",
								0,
								status];
				
				var row = t.row.add(content).draw();
				
				row.nodes().to$().attr('data-id', arr.response.id);
				
				$("#AddModal").modal('hide');
				
				clearForm("#AddModal");
				
				/* Шаг 2 Вызываем редактирования нового меню */
				editNav([arr.response.id]);
					
			break;
			default :
				message(arr.status,arr.response,".modal-body #alert");
			break;
		}
	}
	
	/* Модуль: Навигация */
	/* Удаление навигации */
			
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
					
					var t = $('#tableContent').DataTable();
				
					removelcol.forEach(function(item,i,ar)
					{
						t.row( $("#tableContent").find("tr[data-id='"+item+"']") ).remove().draw();
					});
					
					message('success',r.response,"#alertBody");	
					
				break;
				default :
					message(r.status,r.response,"#alertBody");
				break;
			}
		}
	}
	
	/* Модуль: Навигация */
	/* Включение/Отключение отображения меню */
	
	function switchNav (list,status)
	{
		window.col = list;
		var arr = curl([{	"url" : "/admin/navigationSwitch",
							"params" : {list:list, status:status}}]);
		switch(arr.status)
		{
			case 'success' :
				message('success',arr.response,"#alertBody");
				
				var t = $('#tableContent').DataTable();				
				
				col.forEach(function(item,i,ar){
					t.cell($("#tableContent tr[data-id='"+item+"']").find("td:nth-child(5)")).data("<div class='"+status+"'>&nbsp;</div>");
				});
			break;
			default :
				message(arr.status,arr.response,"#alertBody");
			break;
		}
	}
	
	/* Модуль: Навигация */
	/* Сохранение данных после редактирования меню */
	
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
							message('success',"Операция выполнена","#alertBody");
						break;
						case 'warning':
							message('warning',d.response,".modal-body #alert2");
						break;
						case 'error':
							message('error',d.response,".modal-body #alert2");					
						break;
					}		
		}
		else
		{
			console.log("Не найден id меню");
		}
	}
	
	/* Модуль: Зоны */
	/* Включение/Отключение зоны */
	
	function switchZone(list,status)
	{
		window.col = list;
		
		var arr = curl([{		
							"url" : "/admin/geoZoneSwitch",
							"params" : {"list" : list , "status" : status}
						}]);
						
		switch(arr.status)
		{
			case 'success' :
			
			var t = $('#tableContent1').DataTable();
				
				col.forEach(function(item,i,ar){
					t.cell($("#tableContent1 tr[data-id='"+item+"']").find("td:nth-child(9)")).data("<div class='"+status+"'>&nbsp;</div>");
				});
				
				message(arr.status,arr.response,"#alertBody");
				
			break;
			default :
				message(arr.status,arr.response,"#alertBody");			
			break;
		}
	}
	
	
	function addNewNavPoint() {
		
		var title = $("input[name=newTitle]").val();
		var url = $("input[name=newUrl]").val();
		
		if (title != '' && title != undefined && url != '' && url != undefined)
		{
			$("#sortable2").prepend('<li class="dd-item" data-id="new">'
										+'<div class="dd-handle dd3-handle"></div>'
										+'<div class="dd3-content">'
											+"<div class='fl w85'>"+title+"</div>"
												+"<div class='dd3-operation'>"
													+"<div id='plicon'>+</div>"
													+"<div id='delicon'>x</div>"
												+"</div>"
											+"</div>"
											+"<div class='opisanie'>"
												+"<form name='form' action='javascript:void(0);'>"
													+"<span>Название</span>&nbsp;<input type='text' name='name' value='"+title+"'>"
													+"<span>Ссылка</span>&nbsp;<input type='text' name='href' value='"+url+"'>"
												+"</form></div></div></div></li>");	
			
			$('#sortable2').nestable({group:1,maxDepth:2});
		}
		else
		{
			message("warning","Не заполнены поля",".modal-body #alert");	
		}
		
	}
			
	/* Модуль: Зоны */
	/* Добавление новой зоны */		
	function addZone(d)
	{
		var arr = curl([{		
							"url" : "/admin/geoAddZone",
							"params" : d
						}]);
		
		switch(arr.status)
		{
				case 'success' :
					
					var t = $('#tableContent1').DataTable();
					
					if(arr.response.zone.default == 1) {
						def = "<div class='on'>&nbsp;</div>";
						$("#tableContent1 td:nth-child(8)").html("");
						
					}
					else {
						def = "";
					}
					
					if(arr.response.zone.public == 1) status = "<div class='on'>&nbsp;</div>";
					else status = "<div class='off'>&nbsp;</div>";	
					
					var lastIndex = document.location.hostname.lastIndexOf("."); 
					domainName = document.location.hostname.substring(0, lastIndex);
					
					var content = [	'<input type="checkbox" value="'+arr.response.zone.id+'" name="listpos[]">',
									"<a href='//"+domainName+"."+arr.response.zone.name+"' target='_blank'>"+arr.response.zone.name+"</a>",
									'','','','','0',
									def,
									status,
									arr.response.zone.create_date];

					if (arr.response.zone.telephone) content[2] = arr.response.zone.telephone;
					if (arr.response.zone.email) content[3] = arr.response.zone.email;
					if (arr.response.zone.address) content[4] = arr.response.zone.address;
					if (arr.response.zone.worktime) content[5] = arr.response.zone.worktime;
					if (arr.response.zone.subdomain) content[6] = arr.response.zone.subdomain;
					
				
				var row = t.row.add(content).draw();
				
				row.nodes().to$().attr('data-id', arr.response.zone.id);
				
				if (arr.response.sub != undefined) {
					
					var t = $('#tableContent').DataTable();
					
					arr.response.sub.forEach(function(item,i,ar){
					
						if(arr.response.public == 1) status = "<div class='on'>&nbsp;</div>";
						else status = "<div class='off'>&nbsp;</div>";	
						
						var lastIndex = document.location.hostname.lastIndexOf("."); 
						domainName = document.location.hostname.substring(0, lastIndex);
						
						var content = [	'<input type="checkbox" value="'+item.id+'" name="listpos[]">','',
										"<a href='//"+item.name_en+"."+domainName+"."+item.zone+"' target='_blank'>"+item.name_en+"</a>",
										item.zone,
										'','','','',
										status];

						if (item.name_ru) content[1] = item.name_ru;
						if (item.phone) content[4] = item.phone;
						if (item.email) content[5] = item.email;
						if (item.adr) content[6] = item.adr;
						if (item.time) content[7] = item.time;
						
						var row = t.row.add(content).draw();
						
						row.nodes().to$().attr('data-id', item.id);
						
					});
				}
				
				
				$("#AddModalZ").modal('hide');
				
				clearForm("#AddModalZ");
				
				message('success',"Операция выполнена","#alertBody");
					
					
				break;
				default:
					message(arr.status,arr.response,".modal-body #alert");					
				break;
		}
	}
	
	/* Модуль: Зоны */
	/* Удаление зоны */
	
	function delZone(list)
	{
		if (list.length > 0)					
		{
			var arr = curl([{		
							"url" : "/admin/geoDelZone",
							"params" : {"list" : list }
						}]);
			
			switch(arr.status)
			{
					case 'success' :
						
						var t = $('#tableContent').DataTable();
						var t2 = $('#tableContent1').DataTable();
					
						arr.response.forEach(function(item,i,ar){
							
							t2.row( $("#tableContent1").find("tr[data-id='"+item.zone+"']") ).remove().draw();
							
							if (item.subdomain){
								item.subdomain.forEach(function(item2,i,ar){
									t.row( $("#tableContent").find("tr[data-id='"+item2.id+"']") ).remove().draw();});
							}
						});
					
						message(arr.status,"Операция выполнена","#alertBody");	
					
					break;	
					default:
						message(arr.status,arr.response,"#alertBody");					
					break;
			}
		}
		else
		{
				message('warning',"Не выбраны элементы","#alertBody");
		}
	}
			
	
	/* Модуль: Зоны */
	/* Получение данных для редактирования зоны */
	function editZone(id)
	{
		if(id[0])
		{
			id = id[0];
			
			var arr = curl([{		
							"url" : "/admin/geoEditZone",
							"params" : {"id" : id }
						}]);
						
			switch(arr.status)
			{
				case 'success' :
				
					
					window.edit_id = arr.response.id;
					window.title_old = $('#AddModalZ .modal-title').text();
					
					$('.modal-title').text("Редактировать зону");
					
					$.each($('#AddModalZ form input').serializeArray(), function(i, field) {
						var name = field.name;
						if(arr.response[name] != '' || arr.response[name] != null) 
						{
							$("#AddModalZ").find('input[name='+name+']').val(arr.response[name]); ;
						}
						else
						{
							$("#AddModalZ").find('input[name='+name+']').val('');
						}
					});
					
					$("#AddModalZ").find('select[name=public]').prop('value', arr.response.public);
					$("#AddModalZ").find('select[name=default]').prop('value', arr.response.default);
					
					$("#AddModalZ textarea[name=subdomain]").attr("dissabled","dissabled");
					$("#AddModalZ .listsub").hide();
					$("#AddModalZ").find('input[type=submit]').val("Сохранить").attr('app-method','edit_zone');
					
					$("#AddModalZ").modal(1000);
		
				break;
				
				default :
					message(arr.status,arr.response,"#alertBody");
				break;
			}
		}
		else
		{
			message('warning',"Не задана зона для редактирования","#alertBody");
		}
	}
	
	/* Модуль: Зоны */
	/* Сохранение данных после редактирования зоны */
	
	function saveEditZone(d)
	{
		if(d)
		{
			d.id = window.edit_id;
			
			var r = curl([{		
							"url" : "/admin/geoSaveEditZone",
							"params" : {"d" : d }
						}]);
			
			switch (r.status)	
			{
				case 'success':
						
						delete window.edit_id;
						
						var t = $('#tableContent1').DataTable();
					
						if (r.response.public == 1) var status =  '<div class="on">&nbsp;</div>';
						else var status =  '<div class="off">&nbsp;</div>';
						
						if(r.response.default == 1) {
							def = "<div class='on'>&nbsp;</div>";
							$("#tableContent1 td:nth-child(8)").html("");
							
						}
						else {
							def = "";
						}
						
						var lastIndex = document.location.hostname.lastIndexOf("."); 
						domainName = document.location.hostname.substring(0, lastIndex);
					
						var content = [	'<input type="checkbox" value="'+r.response.id+'" name="listpos[]">',
										"<a href='//"+domainName+"."+r.response.name+"' target='_blank'>"+r.response.name+"</a>",
										'','','','',0,
										def,
										status,
										r.response.create_date];
					
						if (r.response.telephone) content[2] = r.response.telephone;
						if (r.response.email) content[3] = r.response.email;
						if (r.response.address) content[4] = r.response.address;
						if (r.response.worktime) content[5] = r.response.worktime;
						if (r.response.subdomain) content[6] = r.response.subdomain;
						
					
						t.row($("#tableContent1 tr[data-id='"+r.response.id+"']")).data(content);
						
						$("#AddModalZ").modal("hide");
						$("#AddModalZ").find('input[type=submit]').val("Добавить").attr('app-method','add_zone');
						
						clearForm("#AddModalZ");
						
						message('success',"Операция выполнена","#alertBody");
					
			}
		}
		else
		{
			message('warning',"Не заданы параметры","#alertBody");
		}
		
	}
	
	/* Модуль: Зоны */
	/* Получение списка зон */
		
	function getZone()
	{
		var d = curl([{		
							"url" : "/admin/geoGetZone",
							"type" : "GET"
						}]);
		switch(d.status)
		{
			case 'success' :
					
				window.list = "";
					
				d['response'].forEach(function(item,i,ar){list += '<option value="'+item.id+'">'+item.name+'</option>'});
					
				
			break;
			case 'warning':
				message('warning',d.response,"#windowStatus2");
			break;
		}
					
		
		return window.list;	
	}

	/* Модуль: Поддомены */
	/* Добавление нового поддомена */
		
	function addSubdomain(d)
	{
		if (d['name_ru'].length >= 1)
		{
			var arr = curl([{		
							"url" : "/admin/geoAddSubdomain",
							"params" : d
					}]);
					
			switch(arr.status)
		{
				case 'success' :
					
					var t = $('#tableContent').DataTable();
					
					if(arr.response.public == 1) status = "<div class='on'>&nbsp;</div>";
					else status = "<div class='off'>&nbsp;</div>";	
					
					var lastIndex = document.location.hostname.lastIndexOf("."); 
					domainName = document.location.hostname.substring(0, lastIndex);
					
					var content = [	'<input type="checkbox" value="'+arr.response.id+'" name="listpos[]">','',
									"<a href='//"+arr.response.name_en+"."+domainName+"."+arr.response.zone+"' target='_blank'>"+arr.response.name_en+"</a>",
									arr.response.zone,
									'','','','',
									status];

					if (arr.response.name_ru) content[1] = arr.response.name_ru;
					if (arr.response.phone) content[4] = arr.response.phone;
					if (arr.response.email) content[5] = arr.response.email;
					if (arr.response.adr) content[6] = arr.response.adr;
					if (arr.response.time) content[7] = arr.response.time;
					
				
				var row = t.row.add(content).draw();
				
				row.nodes().to$().attr('data-id', arr.response.id);
				
				$("#AddModalS").modal('hide');
				
				clearForm("#AddModalS");
				
				message('success',"Операция выполнена","#alertBody");
					
					
				break;
				default:
					message(arr.status,arr.response,".modal-body #alert");					
				break;
		}
		}
		else
		{
			message('warning',"Заполните поле Название",".modal-body #alert2");
		}
	}
	
	/* Модуль: Поддомены */
	/* Удаление поддоменов по списку */
	
	function delSubdomain(data)
	{
		if(data.length > 0)
		{
			var arr = curl([{		
							"url" : "/admin/geoDelSubdomain",
							"params" : {"data" : data }
							}]);
			
			
			switch(arr.status)
			{
					case 'success' :
						
						var t = $('#tableContent').DataTable();
						
						arr.response.forEach(function(item,i,ar){
							t.row( $("#tableContent").find("tr[data-id='"+item+"']") ).remove().draw();
						});
					
						message(arr.status,"Операция выполнена","#alertBody");	
					
					break;	
					default:
						message(arr.status,arr.response,"#alertBody");					
					break;
			}
		}
		else
		{
			message('warning',"Нужно выбрать элементы списка","#alertBody");
		}
		
	}
	
	/* Модуль: Поддомены */
	/* Включение/Отключение поддомена */
	
	function switchSubdomain(list,status)
	{
		window.col = list;
		
		var arr = curl([{		
							"url" : "/admin/geoSwitchSubdomain",
							"params" : {"list" : list , "status" : status}
						}]);
						
		switch(arr.status)
		{
			case 'success' :
			
			var t = $('#tableContent').DataTable();
				
				col.forEach(function(item,i,ar){
					t.cell($("#tableContent tr[data-id='"+item+"']").find("td:nth-child(9)")).data("<div class='"+status+"'>&nbsp;</div>");
				});
				
				message(arr.status,arr.response,"#alertBody");
				
			break;
			default :
				message(arr.status,arr.response,"#alertBody");			
			break;
		}
	}
	
	/* Модуль: Поддомены */
	/* Получение данных для редактирования поддомена */
		
	function editSubdomain(id)
	{
		if(id[0])
		{
			id = id[0];
			
			var arr = curl([{		
							"url" : "/admin/geoEditSubdomain",
							"params" : {id: id}
					}]);
				
			switch(arr.status)
			{
				case "success" :
				
					window.edit_id = arr.response.id;
					window.title_old = $('#AddModalS .modal-title').text();
					
					$('.modal-title').text("Редактировать поддомен");
					
					$.each($('#AddModalS form input').serializeArray(), function(i, field) {
						var name = field.name;
						if(arr.response[name] != '' || arr.response[name] != null) 
						{
							$("#AddModalS").find('input[name='+name+']').val(arr.response[name]); ;
						}
						else
						{
							$("#AddModalS").find('input[name='+name+']').val('');
						}
					});
					
					$("#AddModalS").find('select[name=public]').prop('value', arr.response.public);
					$("#AddModalS").find('select[name=id_zone]').html(getZone());
					$("#AddModalS").find('select[name=id_zone]').prop('value', arr.response.id_zone);
					
					$("#AddModalS").find('input[type=submit]').val("Сохранить").attr('app-method','edit_subdomain');
					
					$("#AddModalS").modal(1000);
		
				break;
				default :
					message(arr.status,arr.response,"#alertBody");			
				break;
			}
				
		}
		else
		{
			message('warning',"Не задан поддомен редактирования",".alert2");
		}
		
	}
	
	/* Модуль: Поддомены */
	/* Сохранения данных после редактирования поддомена */
	
	function saveEditSubdomain(d)
	{
		if(d)
		{
			d.id = window.edit_id;
			
			var arr = curl([{		
							"url" : "/admin/geoSaveEditSubdomain",
							"params" : {"d" : d }
						}]);
			
			switch (arr.status)	
			{
				case 'success':
						
					delete window.edit_id;
					
					var t = $('#tableContent').DataTable();
				
					if (arr.response.public == 1) var status =  '<div class="on">&nbsp;</div>';
					else var status =  '<div class="off">&nbsp;</div>';
					
					
					var lastIndex = document.location.hostname.lastIndexOf("."); 
					domainName = document.location.hostname.substring(0, lastIndex);
					
					var content = [	'<input type="checkbox" value="'+arr.response.id+'" name="listpos[]">','',
									"<a href='//"+arr.response.name_en+"."+domainName+"."+arr.response.zone+"' target='_blank'>"+arr.response.name_en+"</a>",
									arr.response.zone,
									'','','','',
									status];

					if (arr.response.name_ru) content[1] = arr.response.name_ru;
					if (arr.response.phone) content[4] = arr.response.phone;
					if (arr.response.email) content[5] = arr.response.email;
					if (arr.response.adr) content[6] = arr.response.adr;
					if (arr.response.time) content[7] = arr.response.time;
					
				
					t.row($("#tableContent tr[data-id='"+arr.response.id+"']")).data(content);
					
					$("#AddModalS").modal("hide");
					$("#AddModalS").find('input[type=submit]').val("Добавить").attr('app-method','add_subdomen');
					
					clearForm("#AddModalS");
					
					message('success',"Операция выполнена","#alertBody");
				
				break;
				default :
					message(arr.status,arr.response,".modal #alert");			
				break;
			}	
		}
		else
		{
			message('warning',"Не заданы параметры","#alertBody");
		}	
	}
	
	/* Модуль: Бэкапа */
	/* Создание резервной копии */
	
	function createBackup(p)
	{
		p['extension'] = '/public/backup';
		
		var arr = curl([{
						 "url" : "/admin/settingBackupCreate/",
						 "params" : p
				}]);
		
		switch(arr.status)
		{
			case 'success' :
			
				arr.response.forEach(function(item,i,ar)
				{
					$("tbody").prepend("<tr><td>"+item.date+"</td><td><a href='/admin/settingBackupGet/"+item.link+"'>Скачать ("+item.size+")</a></td><td>"+item.user+"</td></tr>");
				});
				
				message(arr.status,arr.response,"#alertBody");
				
			break;
			
			default:
				message(arr.status,arr.response,"#alertBody");
			break;
		
		}
		
	}
	
	
	/* Модуль: Настроек сайта */
	/* Сохранение форм настроек */
	
	
	function saveSettingSite(d)
	{
		
		d["ext"] = $("input[name=ext]").prop("checked");
		
		if(d["ext"])
		{
			d["ext"] = 1;
		}
		else
		{
			d["ext"] = 0;
		}
		d["cache"] = $("input[name=cache]").prop("checked");
		
		if(d["cache"])
		{
			d["cache"] = 1;
		}
		else
		{
			d["cache"] = 0;
		}
		
		d["telegaSend"] = $("input[name=telegaSend]").prop("checked");
		
		if(d["telegaSend"])
		{
			d["telegaSend"] = 1;
		}
		else
		{
			d["telegaSend"] = 0;
		}
		
		d["emailStatus"] = $("input[name=emailStatus]").prop("checked");
		
		if(d["emailStatus"])
		{
			d["emailStatus"] = 1;
		}
		else
		{
			d["emailStatus"] = 0;
		}
		
		d["PhoneChecknum"] = $("input[name=PhoneChecknum]").prop("checked");
		
		if(d["PhoneChecknum"])
		{
			d["PhoneChecknum"] = 1;
		}
		else
		{
			d["PhoneChecknum"] = 0;
		}
		
		var r = curl([{
						 "url" : "/admin/settingSiteSave/",
						 "params" : d
				}]);
		
		message(r.status,r.response,"#alertBody");
	}
	
	
	/* Модуль: Перелинковки */
	/* Добавление перелинковки */
	
	function addReLink(data)
	{
		
		var r = curl([{		
				"url" : "/admin/pageReLinkAdd",
				"params" : data
			}]);
		
		switch(r.status)
		{
			case 'success' :
				
				var t = $('#tableRelink').DataTable();
				
				if (r.response.public == 1)
				{
					var status =  '<div class="on">&nbsp;</div>';
				}
				else
				{
					var status =  '<div class="off">&nbsp;</div>';
				}
				
				var row = t.row.add([
							'<input type="checkbox" value="'+r.response.id+'" name="listpos[]">',
							'<a href="'+r.response.durl+'" target="_black">'+r.response.donor+'</a>',
							'<a href="'+r.response.murl+'" target="_black">'+r.response.main+'</a>',
							r.response.ankor,
							status
						]).draw();
				
				row.nodes().to$().attr('data-id', r.response.id);
				
				$("#AddModal").modal('hide');
				
				message('success',"Операция выполнена","#alertBody");
				
				
			break;
			
			default :
				message(r.status,r.response,".modal-body #alert");
			break;
		}
	
	}
	
	/* Модуль: Перелинковка */
	/* Удаление перелинковки */
	
	function delRelink(list)
	{
		if(list.length > 0 && confirm("Вы действительно хотите удалить выбранные позиции?")) 
		{
			window.removelcol = list;
					
			var r = curl([{	"url" : "/admin/pageReLinkDel",
							"params" : {"ids" : list}}]);
			switch(r.status)
			{
				case 'success' :
					
					var t = $('#tableRelink').DataTable();
				
					list.forEach(function(item,i,ar)
					{
						t.row( $("#tableRelink").find("tr[data-id='"+item+"']") ).remove().draw();
					});
					
					message('success',r.response,"#alertBody");	
					
				break;
				default :
					message(r.status,r.response,"#alertBody");
				break;
			}
		}
	}
	
	/* Модуль: Настроек сайта */
	/* Создание sitemap в формате xml */
	
	function CreateSitemap (e)
	{
		var r = curl([{	"url" : "/admin/settingSiteСreateSitemap",
						"params" : { "fullname" : $(e).val()}}]);
		
		$(e).prop('selectedIndex',0);
		
		message(r.status,r.response,"#alertBody");
	}
	
	/* Модуль: Настроек сайта */
	/* Сброс нанстроек по умолчанию */
	
	function SettingDefault()
	{
		
		$.each($('form input').serializeArray(), function(i, field) {
			var name = field.name;
			var val = field.value;
			
			if (val == "on" || val == "off") {
				$("form").find('input[name='+name+']').removeAttr("checked").prop("checked",false);
			}
			else {
				$("form").find('input[name='+name+']').attr("readonly","readonly").val('');
			}
		});
		
		$("select[name=cacheTime]").attr("disabled","disabled");
		
		message("warning","Настройки выставлены по-умолчанию, не забудьте сохранить!","#alertBody");
	}
	
	
	/* Модуль: Страницы */
	/* Получение всех страниц */
		
	function getPageListAll()
	{
		var d = curl([{		
							"url" : "/admin/pageListAll",
							"type" : "GET"
						}]);
						
		
		
		switch(d.status)
		{
			case 'success' :
				
				window.html = '';
				
				if (d['response']["static"])
				{
					d['response']["static"].forEach(function(item,i,ar) {
					
						html += '<a class="dropdown-item" data-value="'+item.id+'" data-level="1" href="#">'+item.title+'</a>';
						
					});
				}
				
				if (d['response']["category"])
				{
					d['response']["category"].forEach(function(item,i,ar) {
					
						html += '<a class="dropdown-item" data-value="c'+item.id+'" data-level="1" href="#">'+item.title+'</a>';
						
						if (item.products)
						{
							item.products.forEach(function(pr,i,ar) {
								html += '<a class="dropdown-item" data-value="'+pr.id+'" data-level="2" href="#">'+pr.title+'</a>';
							});
						}
						
					});
				}
				
			break;
			default :
				message(d.status,d.response,"#alertBody");
				
			break;
		}
					
		
		return window.html;	
	}
	
	
	/* Модудль: Общий */
	/* Функция транслита урла */
	
	
	function transliteUrl(word){
		var a = {"Ё":"YO","Й":"I","Ц":"TS","У":"U","К":"K","Е":"E","Н":"N","Г":"G","Ш":"SH","Щ":"SCH","З":"Z","Х":"H","Ъ":"'","ё":"yo","й":"i","ц":"ts","у":"u","к":"k","е":"e","н":"n","г":"g","ш":"sh","щ":"sch","з":"z","х":"h","ъ":"'","Ф":"F","Ы":"I","В":"V","А":"a","П":"P","Р":"R","О":"O","Л":"L","Д":"D","Ж":"ZH","Э":"E","ф":"f","ы":"i","в":"v","а":"a","п":"p","р":"r","о":"o","л":"l","д":"d","ж":"zh","э":"e","Я":"Ya","Ч":"CH","С":"S","М":"M","И":"I","Т":"T","Ь":"'","Б":"B","Ю":"YU","я":"ya","ч":"ch","с":"s","м":"m","и":"i","т":"t","ь":"'","б":"b","ю":"yu"};

	  var res = word.split('').map(function (char) { 
		return a[char] || char; 
	  }).join("");
	  
	  return res.toLowerCase().replace(/ /g, '-');
	}
	
	
