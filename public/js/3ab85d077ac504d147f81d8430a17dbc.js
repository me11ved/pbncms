var mass = [];

$(document).on('click','#deletelistpos',function(){
	
	var type = $(this).attr('data-type');
	
	var list = $('#tablewrapper input:checked[name="listpos[]"]')
            .map(function () { return $(this).val(); }).get();
	
	if(list.length > 0 && confirm("Вы действительно хотите удалить выбранные страницы?")) {
		$.ajax({
				url: "/admin/delete_position/",
				type: "POST",
				data:{type:type,list:list},
				success: function(data){
				
					console.log(data);
				
					var result = JSON.parse(data);
	
					alert(result.description);
					
				}
		});
	}
	
});

$(document).on('click','#deletescripts',function(){
	
	var list = $('#tablewrapper input:checked[name="listpos[]"]')
            .map(function () { return $(this).val(); }).get();
	
	if(list.length > 0 && confirm("Вы действительно хотите удалить выбранные скрипты?")) {
		$.ajax({
				url: "/admin/delete_script/",
				type: "POST",
				data:{list:list},
				success: function(data){
				
					console.log(data);
				
					var result = JSON.parse(data);
	
					if(result.status == 'success') { 
						alert("Удалены");
					}
					
				}
		});
	}
	
});


$(document).on('click','#editlistpos',function(){
	var type = $(this).attr('data-type');
	
	var list = $('#tablewrapper input:checked[name="listpos[]"]')
            .map(function () { return $(this).val(); }).get();
	if(list.length > 0) {
			window.location.replace('/admin/edit_position/?type='+type+'&list='+list);
	}
	
});

$(document).on('click','#checkPage',function(){
	
	var data = {'list' :'','type' :'','pos' :''};
	
	var type = $(this).attr('data-type');
	var pos = $(this).attr('data-pos');
	
	var list = $('#tablewrapper input:checked[name="listpos[]"]')
            .map(function () { return $(this).val(); }).get();
			
	data['list'] = list;
	data['type'] = type;
	data['pos'] = pos;


	if(list.length > 0) {
			console.log(data);
			$.ajax({
				url: "/admin/check_page/",
				type: "POST",
				data:{data:data},
				success: function(response){
					
					console.log(response);
				
					var result = JSON.parse(response);
	
					if(result.status == 'success') { 
						alert("Оперция выполнена");
					}
					else {
						alert(result.description);
					}
					
				}
		});
	}
	
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

			
$(document).on('click','.allcheck', function(){

	 if($(this).is(':checked')) {
		 $('.tinytable tr td:nth-child(1)').each(function( index ) {
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
	 $(this).parents(".tinytable").find('input[type=checkbox]').prop('checked',false);
	 console.log(window.mass);
	 
	 }
});   

$(document).on('change', '.checking', function () {
        var val = this.value; // to int
        
        if (this.checked) {
            mass.push(val); // если в начало, то .ushift(val)
        } else {
            var idx = $.inArray(val, mass);
            if( idx > -1 ){
               mass.splice(idx, 1);
            }
        }
        
       console.log(window.mass);
    });
		
	
	
$(document).on('click','#updatecatnow',function(){
	
	var id = $(this).parents('#content').find('form').attr('data-id');
	if(id) {
			var data = $('form[name=data'+id+']').form();
			data['id'] = id;
			data['mini_desc']	= CKEDITOR.instances.mini_desc.getData();
			data['description'] = CKEDITOR.instances.description.getData(); //data['desc'];
			console.log(data);
			
			$.ajax({
					url: "/admin/page_cat_update/",
					type: "POST",
					data:{data:data},
					success: function(data){
						console.log(data);
						var result = JSON.parse(data);
						if(result.status == 'success') {
							alert("Данные обновлены");
							
						}	
					}
			});
			
			
	}
	
});	

$(document).on('click','#updateproductnow',function(){
	
	var id = $(this).parents('#content').find('form').attr('data-id');
	if(id) {
			var data = $('form[name=data'+id+']').form();
			data['id'] = id;
			data['mini_desc']	= CKEDITOR.instances.mini_desc.getData();
			data['description'] = CKEDITOR.instances.description.getData(); //data['desc'];
			console.log(data);
			
			$.ajax({
					url: "/admin/page_product_update/",
					type: "POST",
					data:{data:data},
					success: function(data){
						console.log(data);
						var result = JSON.parse(data);
						if(result.status == 'success') {
							alert("Данные обновлены");
							
						}	
					}
			});
			
			
	}
	
});	


$.fn.form = function() {
    var formData = {};
    this.find('[name]').each(function() {
        formData[this.name] = this.value;  
    })
    return formData;
};