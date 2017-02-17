$(document).ready(function(){
    
    $(window).on('load', function(){
        if($(window).outerWidth() <= 769){
            $("#categories").addClass('mobile');
            $("#list .search").hide();
//            $("#content").css('left', '-100%');
        }else{
            $("#categories").removeClass('mobile');
        }
    });
    
    $("#list ul").scroll(function(){
        $("#list ul li.title").each(function(i, e){
            if($(this).scrollTop() >= $(e).offset().top){
                $("#list ul").removeClass('fixedBug');
                $("#list ul li.title").removeClass('fixed');
                $(e).addClass('fixed');
            }else{
                $("#list ul").addClass('fixedBug');
            }
        });
    });
    
    $("#search").on('keyup', function(){
        var keyword = $(this).val().toLowerCase();
        if(keyword.length <= 0){
            $("#list ul li.title").show();
        }else{
            $("#list ul li.title").hide();
        }
        $("#list ul li.item").each(function(i,e){
            if($(this).text().toLowerCase().search(keyword) > -1){
                $(this).show();
            }else{
                $(this).hide();
            }
        });
    });
    
    $("[data-toggle=tooltip]").tooltip();
    
    $("#categories li a").click(function(e){
        $("#categories li").removeClass('active');
        $(this).parent().addClass('active');
        e.preventDefault();
    });
    
    $("#list li").click(function(){
        $("#list li").removeClass('active');
        $(this).addClass('active');
    });
    
    $("#category_icon").fontIconPicker();
    $(".icons-selector").addClass('form-control');
    
    $("#addCategory").click(function(){
        $.post('ajax.php', {'type':'1', 'icon':$("#category_icon").val(), 'name':$("#category").val()}, function(response){
            if(response.success){
                toastr8.success({
                    title       : msg_2,
                    message     : response.success.msg,
                    iconClass   : 'flaticon-checked'
                });
                $("#categories ul").append('<li><a href="" data-id="' + response.success.id + '"><i class="' + $("#category_icon").val() + '"></i> ' + $("#category").val() + ' <span class="badge">0</span></a></li>');
                if($("#categories ul .empty").length > 0){
                    $("#categories ul .empty").remove();
                }
            }else if(response.info){
                toastr8.info({
                    title       : msg_3,
                    message     : response.info.msg,
                    iconClass   : 'flaticon-info'
                });
            }else{
                toastr8.error({
                    title       : msg_4,
                    message     : response.error.msg,
                    iconClass   : 'flaticon-delete'
                });
            }
        });
    });
    
    var landing = $("#content").html();
    $("#loadP").click(function(e){
        if($("#content #landing").length > 0){
            $("#content").load('loadp.php');
            $(this).removeClass('btn-default').addClass('btn-danger').html('<i class="glyphicon glyphicon-remove"></i>');
            $(this).parent().attr('title', msg_6);
        }else{
            $("#content").html(landing);
            $(this).removeClass('btn-danger').addClass('btn-default').html('<i class="glyphicon glyphicon-plus"></i>');
            $(this).parent().attr('title', msg_5);
        }$("[data-toggle=tooltip]").tooltip();
        e.preventDefault();
    });
    
    $("#edit-category").click(function(){
        var mode    = $("#categories ul").attr('class'),
            icon    = '';
        if(mode == ''){
            $(this).removeClass('btn-default').addClass('btn-danger').html('<i class="glyphicon glyphicon-remove"></i>');
            $("#categories ul").attr('class', 'remove-mode');
        }else{
            $(this).removeClass('btn-danger').addClass('btn-default').html('<i class="glyphicon glyphicon-edit"></i>');
            $("#categories ul").attr('class', '');
        }
    });
    
    $("#categories ul li a button").click(function(e){
        var id      = $(this).parent('a').data('id'),
            index   = $(this).parents('li').index();
        if(confirm(sprintf(msg_1, $.trim($(this).prev('.badge').text())))){
            $.post('ajax.php', {'type':2, 'id':id}, function(response){
                if(response.success){
                    toastr8.success({
                        title       : msg_2,
                        message     : response.success.msg,
                        iconClass   : 'flaticon-checked'
                    });
                    $('#categories ul li:eq('+index+')').remove();
                }else if(response.info){
                    toastr8.info({
                        title       : msg_3,
                        message     : response.info.msg,
                        iconClass   : 'flaticon-info'
                    });
                }else{
                    toastr8.error({
                        title       : msg_4,
                        message     : response.error.msg,
                        iconClass   : 'flaticon-delete'
                    });
                }
            });
        }
        e.preventDefault();
    });
    
    $("#categories ul li.item").click(function(){
        var id  = $(this).find('a').data('id');
        $.post('list.php', {'id':id}, function(response){
            $("#list ul").html(response);
        });
    });
    
    $("#list ul li.item").click(function(){
        if($(window).outerWidth() < 1024){
           back();
        }
        var id  = $(this).data('id');
        $.post('loadp.php', {'id':id}, function(response){
            $("#content").html(response);
        });
    });
    
    $("#menu-button").click(function(){
        var h = $("#categories").height();
        if(h == 50){
            $("#categories").removeClass('mobile');
        }else{
            $("#categories").addClass('mobile');
        }
    });
    
    $("#search-button").click(function(){
        $("#list .search").stop(true).slideToggle("fast");
    });
    
    $("#back-button").click(function(){
        back();
    });
    
    function back(){
        $("#content").html(landing);
        var l = $("#content").css('left').split('px')[0];
        if(l == 0){
            $("#back-button i").removeClass('glyphicon-chevron-right').addClass('glyphicon-chevron-left');
            $("#content").stop(true).animate({
                left : '-' + $(window).outerWidth() + 'px'
            }, 300);
        }else{
            $("#back-button i").removeClass('glyphicon-chevron-left').addClass('glyphicon-chevron-right');
            $("#content").stop(true).animate({
                left : 0
            }, 300);
        }
    }
    
    if($("#loginPage").length > 0){
        var url = 'https://unsplash.it/' + $(window).outerWidth() + '/' + $(window).outerHeight() + '/?random';
        $("#loginPage").css('background-image', 'url('+url+')');
    }
    
    if($("#installPage").length > 0){
        var url = 'https://unsplash.it/' + $(window).outerWidth() + '/' + $(window).outerHeight() + '/?random';
        $("#installPage").css('background-image', 'url('+url+')');
    }
    
    $("#login").click(function(){
        var username    = $("#username").val(),
            password    = $("#password").val();
        $.post('ajax.php', {'type':6, 'username':username, 'password':password}, function(response){
            if(response.success){
                toastr8.success({
                    title       : msg_2,
                    message     : response.success.msg,
                    iconClass   : 'flaticon-checked'
                });
                setInterval(function(){
                    window.location.href = '/';
                }, 1000);
            }else{
                toastr8.error({
                    title       : msg_4,
                    message     : response.error.msg,
                    iconClass   : 'flaticon-delete'
                });
            }
        });
    });
    
    if($("#categories ul").length > 0 || $("#list ul").length > 0 || $("#content").length > 0){
        $("#categories ul, #list ul, #content").enscroll({
            showOnHover         : true,
            verticalTrackClass  : 'track3',
            verticalHandleClass : 'handle3'
        });
    }
    
    var sprintf = function(str){
        var args = arguments,
            flag = true,
            i = 1;
        
        str = str.replace(/%s/g, function(){
            var arg = args[i++];
            if(typeof arg === 'undefined'){
                flag = false;
                return '';
            }
            return arg;
        });
        return flag ? str : '';
    };
});