$(document).on('click', '#showformpopup', function() {
    d = document.createElement('div');
    $(d).addClass("bg_black").appendTo($("body")).attr('onclick', '$(".bg_black").hide();$("body").removeAttr("style");$(".Fcallback").hide();').show();
    $(".Fcallback").show().find('h4').html($(this).attr('data-name'));
    $("body").attr('style', 'height:100%;overflow:hidden;');
});

$(document).on('click', '#menu_tg', function() {
    d = document.createElement('div');
    $("#mobile_menu").toggle();
});


var kk1 = 60;
var kk2 = 100;
$(document).on('click', '.calc_btn_do', function() {
    var parent_id = $(this).parent().attr('id');
    if (parent_id == 'b1') {
        $("#i_s").removeClass("error");
        $("#i_h").removeClass("error");
        var s = parseFloat($('#i_s').val());
        if (isNaN(s)) {
            $("#i_s").addClass("error");
            $('#' + parent_id + ' .calc_result').text('Некорректный ввод');
            return false;
        }
        var h = parseFloat($('#i_h').val());
        if (isNaN(h)) {
            $("#i_h").addClass("error");
            $('#' + parent_id + ' .calc_result').text('Некорректный ввод');
            return false;
        }
        var v = s * h;
        if (v <= 1000) {
            kk1 = 60;
            kk2 = 100;
        } else if (v <= 10000) {
            kk1 = 30;
            kk2 = 60;
        } else if (v <= 50000) {
            kk1 = 18;
            kk2 = 30;
        } else if (v <= 100000) {
            kk1 = 12;
            kk2 = 15;
        } else if (v <= 200000) {
            kk1 = 10;
            kk2 = 12;
        } else {
            kk1 = 10;
            kk2 = 10;
        }
        var p1 = v * kk1;
        var p2 = v * kk2;
        if (p1 == p2) {
            $('#' + parent_id + ' .calc_result').text(p1 + ' руб.');
        } else {
            $('#' + parent_id + ' .calc_result').text('От ' + p1 + ' до ' + p2 + ' руб.');
        }
        $('#' + parent_id + ' .calc_result').attr('rel', kk);
    }
    if (parent_id == 'b2') {
        $("#i_v").removeClass("error");
        var v = parseFloat($('#i_v').val());
        if (isNaN(v)) {
            $("#i_v").addClass("error");
            $('#' + parent_id + ' .calc_result').text('Некорректный ввод');
            return false;
        }
        if (v <= 1000) {
            kk1 = 60;
            kk2 = 100;
        } else if (v <= 10000) {
            kk1 = 30;
            kk2 = 60;
        } else if (v <= 50000) {
            kk1 = 18;
            kk2 = 30;
        } else if (v <= 100000) {
            kk1 = 12;
            kk2 = 15;
        } else if (v <= 200000) {
            kk1 = 10;
            kk2 = 12;
        } else {
            kk1 = 10;
            kk2 = 10;
        }
        var p1 = v * kk1;
        var p2 = v * kk2;
        if (p1 == p2) {
            $('#' + parent_id + ' .calc_result').text(p1 + ' руб.');
        } else {
            $('#' + parent_id + ' .calc_result').text('От ' + p1 + ' до ' + p2 + ' руб.');
        }
    }
    return false;
});
$(document).ready(function() {
    $(".slogan .polo2 .perekl a").click(function() {
        $(".slogan .polo2 .perekl a").removeClass("active");
        $(this).addClass("active");
        $(".slogan .polo2 .bl_pe").removeClass("active");
        $(".slogan .polo2 .bl_pe#" + $(this).attr("data-bl")).addClass("active");
        return false;
    });
});