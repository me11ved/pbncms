/* Интеграция с вебмастером */

/* Сохранение host id сайта для отображения статистика сайта */
	function SaveHostId(d)
	{
		var r = curl([{
						 "url" : "/integration/webMasterSaveSetting/",
						 "params" : d
				}]);
		
		message(r.status,r.response,".alert");
	}

	/* Функция для обработки формы после нажатия */
	
	$(document).ready(function() 
	{ 
		$('form').on('submit', function() 
		{  
			window.d = $(this).form();
			var method = $(this).find("input[type=submit]").attr("app-method");
			delete window.d["tabs"];
			
			switch (method) {
				
				case 'webmaster_host_save':
					SaveHostId(d);
				break;
				
			}
		}); 
	});



$(document).on('click','#deletelistposwebmaster',function(){
	
	if(window.mass.length > 0 && confirm("Вы действительно хотите удалить выбранные страницы?")) {
		var list = window.mass;
		$.ajax({
				url: "/admin/delete_text_webmaster/",
				type: "POST",
				data:{list:list},
				success: function(data){
				
					console.log(data);
				
					var result = JSON.parse(data);
					
					if(result.status == 'success') {
						
						var result = result.description;
						var html = 'Удалены:\r\n';	
						var cnt  = 0;     
						for(var i in result) {
							if(i != 'exists') {
								if(cnt == 0) {
								html += '';
								}
								html += result[i]['id']+'::'+result[i]['code']+"\r\n";
								cnt++;
								if(cnt == 1) {
									cnt = 0;
									html += '';
								}	
							}
						} 
						if(cnt != 0) {
						html += '';
						}
						alert(html);
					}
					
					
				}
		});
	}
	
});

$(document).on('click','#addtextwebmaster',function(){
	
	var div = $(this);
	var text =  $("#newtext").val();
	console.log(text.length);
	if(text.length > 500) {
		$("#status").html("Идет добавление текста. Подождите!");
		div.css('display','none');
		
		$.ajax({
				url: "/admin/add_text_webmaster/",
				type: "POST",
				data:{text:text},
				success: function(data){
					console.log(data);
					var result = JSON.parse(data);
					if(result.status == 'success') {
						$("#status").html("Текст добавлен в вебмастер");
						div.fadeIn(500);
					}
					
					
				}
		});
	}
	
});

$(document).on('click','#importwebmaster',function(){
		//var list = $('#tablewrapper input:checked[name="listpos[]"]')
            //.map(function () { return $(this).val(); }).get();
        var list = window.mass;

	if(list.length > 0) {
			console.log('start import, count:'+list.length);
			$.ajax({
					url: "/admin/add_text_page_webmaster/",
					type: "POST",
					data:{list:list},
					success: function(data){
						console.log(data);
						var result = JSON.parse(data);
						if(result.status == 'success') {
							alert("Текст добавлен в вебмастер");
							
						}
						
						
					}
			});
	}
	
});