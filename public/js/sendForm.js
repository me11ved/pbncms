function send_request(a) {
	
	$("body").find('button').attr('disabled','disabled').html('Заявка отправляется');
	
	if ($(a).find("input[name='validpersonal']").is(':checked')) {
		
		formContent=$(a);

		var data = $(a).assocarrform();
		console.log(data);
		data['url'] = document.location.href;
        data['form'] = $(a).attr('data-name');
		
		if(data['contact'] != undefined || data['contact'] > 3) {
			
			if (ValidMail(data['contact']) || ValidPhone(data['contact'])) {
			
            $("#warrningform").hide();
			
			window.yaCounter46808703.reachGoal('ORDERGO');
			
            $.ajax({
                url: '/requests/orders/',
                type: 'POST',
                data: {
                    data:data
                },
                success: function(b) {
                    console.log(b);
                    var c = JSON.parse(b);
                    if (c.status == '200') {
                        $(formContent).closest('div').html('<div id="resultquery"><h1>Спасибо за заявку</h1></br><p>Мы свяжемся с вами в ближайшее время</p></div>');
                        $(formContent).parents('.Fcallback').find('.podp').html("Мы свяжемся с вами в ближайшее время");
                        $("body").removeAttr("style");
                        $(formContent).parents('.Fcallback').fadeOut(2000);
                        $('.bg_black').fadeOut(2000);
                        formContent.remove();
						$("body").find('button').removeAttr('disabled','disabled').html('Отправить');
						$.get( "/requests/send/", function(){});
                    } else {
                        $(a).find("#warrningform").show().html("Извините, не удалось отправить заявку.");
						$("body").find('button').removeAttr('disabled','disabled').html('Отправить');
                    }
                }
            })
			} 
			else {
				$(a).find("#warrningform").show().html("Неверно заполнено поле");
				$("body").find('button').removeAttr('disabled','disabled').html('Отправить');
			}
		}
		else {
			 $(a).find("#warrningform").show().html("Пожалуйста, заполните все поля");
			 $("body").find('button').removeAttr('disabled','disabled').html('Отправить');
		}
		
    } else {
		$("body").find('button').removeAttr('disabled','disabled').html('Отправить');
    }
    return false
};

$(document).ready(function() { $('form').on('submit', function() { send_request($(this)) }) });
$.fn.assocarrform = function() { var formData = {}; this.find('[name]').each(function() { formData[this.name] = this.value; }); return formData; };

//$(document).on('click','input[name=contact]',function(){if($(this).val().length < 1) $(this).val('8').focus();});

$(document).on('click','input[name=validpersonal]',function(){
	if ($(this).is(':checked')) {
		$('body').find('button').removeAttr('disabled').removeAttr('style');
	}
	else {
		$('body').find('button').attr('disabled','disabled').attr('style','color:#000;background:gray;border:0;');
	}
});

function ValidMail(data) {
    var re = /^[\w-\.]+@[\w-]+\.[a-z]{2,4}$/i;
    var myMail = data;
    var valid = re.test(myMail);
    if (valid) output = true;
    else output = output = false;
 
    return valid;
}
 
function ValidPhone(data) {
    var re = /^\d[\d\(\)\ -]{4,14}\d$/;
    var myPhone = data.replace(/[^0-9]/gim,'');
    var valid = re.test(myPhone);
    if (valid) output = true;
    else output = false;
    
    return valid;
}  
 