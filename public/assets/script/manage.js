$(document).ready(function() {



$(document).on('click','.box-trackby-lv',function(){
    $('.box-trackby-lv').toggleClass('border-color-pink');
    $("#show-checked-user").toggleClass('checked-user');
    $('.button-add-point-track-by-id').toggleClass('show-box-add-point');
}),5000;

var timeout = 500;

//Tringger keypress click button search
$('.find-customers-left').click(function (e) {
    e.preventDefault();
    var e = $.Event('keypress');
    e.which = 13;
    $('#id-account').trigger(e);
})

$('.find-customers-right').click(function (e) {
    e.preventDefault();
    var e = $.Event('keypress');
    e.which = 13;
    $('#id-account-2').trigger(e);
})


//Tìm kiếm tích điểm + chops
$('.box-trackby-lv').hide();
$('#id-account').bind('keypress', function(e) {
    $('#result-search-2').empty();
    $('#result-search').empty();
    if (e.which === 13) {
    	var keyWords =  $.trim( $(this).val() );
		if (keyWords == "") {
            $("#result-search").empty();
            $('.box-trackby-lv').hide(500, 'swing');
            $.toaster({ priority : 'danger', message : 'Vui lòng nhập mã khách hàng' });
            $('#id-account').focus();
		} else {
            $('#loadding-progess').show();
			$.ajax({
            url     : 'manage/excutesearch',
            method  : 'post',
            data    : {
                keywords : keyWords
            },
            success : function (data) {
            	if (data.success == true) {
                    $('#loadding-progess').hide();
                    if ( data.types == 3 ) {
                        $("#result-search").empty();
                        $('.box-trackby-lv').show(500, 'swing');
                        $('#result-search').append(data.append).fadeIn('slow');
                        $('.box-trackby-lv').trigger('click');
                        $(".currentcy").autoNumeric('init');
                        $('#order-price').keyup(function(){
                            var price = $(this).autoNumeric('get');
                            var pointChange = Math.floor(parseInt(price) / parseInt(data.point));
                            if (price == "") {
                                $('#point-changed').text(0);
                            } else {
                                if (isNaN(pointChange) == true) {
                                    $('#point-changed').text('0');
                                    $.toaster({ priority : 'danger', message : 'Vui lòng cấu hình tích lũy điểm trước khi nhập điểm' });
                                } else {
                                    $('#point-changed').text(pointChange);
                                }
                            }
                        });
                    } else if ( data.types == 4 ) {
                        $("#result-search").empty();
                        $('.box-trackby-lv').show(500, 'swing');
                        $('#result-search').append(data.append).fadeIn('slow');
                        $(".currentcy").autoNumeric('init');
                        $('#order-price').keyup(function(){
                            var price = $(this).autoNumeric('get');
                            var pointChange = Math.floor(parseInt(price) / parseInt(data.config));
                            if (price == "") {
                                $('#point-changed').text(0);
                            } else {
                                $('#point-changed').text(pointChange);
                            }
                        });
                        var numbTick = data.chops;
                        if(numbTick > 0) {

                            for(var i = 1; i <= numbTick; i++){
                                $(".tick-"+i).html("<center><i class='fa fa-star pink star-chops fa-2x'></i></center>");
                            }

                            for(var j = parseInt(numbTick)+1; j <= 15; j++){
                                $(".tick-"+j).html("");
                            }

                        } else {
                            for(var i = 1; i <= 15; i++){
                                $(".tick-"+i).html("");
                            }
                        }
                    }
                } else {
                    $('#loadding-progess').hide();
                    $("#result-search").empty();
                    $('.box-trackby-lv').hide(500, 'swing');
                    $('#result-search').append(data.append);
                }
            },
            error : function (xhr){
                $.endload();
                var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                $(location).attr('href', host);
            },
        })
		}
    }
});

//Tìm kiếm đổi Chops
$('#id-account-2').bind('keypress', function(e) {
    var __this = $(this);
    $('#result-search-2').empty();
    $('#result-search').empty();
    if (e.which === 13) {
        var keyWords = $.trim( $(this).val() );
        if (keyWords == "") {
            $("#result-search-2").empty();
            $('.box-trackby-lv').hide(500, 'swing');
            $.toaster({ priority : 'danger', message : 'Vui lòng nhập mã khách hàng' });
            $('#id-account-2').focus();
        } else {
            $('#loadding-progess-2').show(timeout);
            __this.attr('readonly','true');
            $.ajax({
            url : 'manage/excute-search2',
            method : 'post',
            data : {
                keywords : keyWords
            },
            success : function (data) {
                __this.removeAttr('readonly');
                if (data.success == true) {
                    $('#loadding-progess-2').hide(timeout);
                    if ( data.types == 3 ) {
                        
                        $("#result-search-2").empty();
                        $('.box-trackby-lv').show(500, 'swing');
                        $('#result-search-2').append(data.append).fadeIn('slow');
                        $(".currentcy").autoNumeric('init');
                        $('#order-price').keyup(function(){
                            var price = $(this).autoNumeric('get');
                            var pointChange = Math.floor(parseInt(price) / parseInt(data.point));
                            if (price == "") {
                                $('#point-changed').text(0);
                            } else {
                                $('#point-changed').text(pointChange);
                            }
                        });
                    } else if ( data.types == 4 ) {
                        $("#result-search-2").empty();
                        $('.box-trackby-lv').show(500, 'swing');
                        $('#result-search-2').append(data.append).fadeIn('slow');
                        $(".currentcy").autoNumeric('init');
                        $('#order-price').keyup(function(){
                            var price = $(this).autoNumeric('get');
                            
                            var pointChange = Math.floor(parseInt(price) / parseInt(data.chops));
                            if (price == "") {
                                $('#point-changed').text(0);
                            } else {
                                $('#point-changed').text(pointChange);
                            }
                        });
                        var numbTick = data.chops;
                        if(numbTick > 0) {

                            for(var i = 1; i <= numbTick; i++){
                                $(".tick-"+i).html("<center><i class='fa fa-star pink star-chops fa-2x'></i></center>");
                            }

                            for(var j = parseInt(numbTick)+1; j <= 15; j++){
                                $(".tick-"+j).html("");
                            }

                        } else {
                            for(var i = 1; i <= 15; i++){
                                $(".tick-"+i).html("");
                            }
                        }
                    }
                } else {
                    $('#loadding-progess-2').hide(timeout);
                    $("#result-search-2").empty();
                    $('.box-trackby-lv').hide(500, 'swing');
                    $('#result-search-2').append(data.append);
                }
            },
            error : function (xhr){
                $.endload();
                var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                $(location).attr('href', host);
            },
        })
        }
    }
});

// End Search Discount Point


//Tích điểm
$(document).on('click','#change-point',function(e){
        e.preventDefault();
        var id = $(this).data("id");

        var order_id = $('.order_id').val();
        var point_change = $('.point_change').autoNumeric('get');
        var name = $('.name-customer').text();

        var transferPoint = parseInt( $('#point-changed').text() );

        if ( point_change == "" ) {
            $.toaster({ priority : 'danger', message : 'Giá trị hóa đơn không được để trống' });
            $("#order-price").focus();
        } else if ( transferPoint == 0 ) {
            $.toaster({ priority : 'danger', message : 'Số điểm tích lũy cho khách hàng phải lớn hơn 0. Vui lòng thử lại' });
            $("#order-price").focus();
        } else if ( transferPoint >= 1 ) {
            $.confirm({
                theme: 'supervan',
                title: 'Xác nhận tích điểm',
                confirmButtonClass: 'btn-info',
                cancelButtonClass: 'btn-danger',
                content: "Bạn có muốn tích <span class='pink'>" + $('#point-changed').text() +"</span> điểm cho khách hàng <span class='pink'>"+name+"</span>",
                confirm: function(){

                    $.loadding();                        
                    $.ajax({
                        url: 'manage/storepoint',                
                        method:'post',
                        data : {
                            id : id,
                            order_id : order_id,
                            point_change : point_change

                        },
                                
                        success: function(data){
                            $.endload();   
                            if (data.success == 'dialog') {
                                BootstrapDialog.show({
                                    title: data.title,
                                    message: "<center>"+data.messages+"</center>"
                                });
                            } else if (data.success == true) {
                                $.toaster({ priority : data.priority , message : data.messages });
                                $('#result-search').empty();
                                setTimeout(function(){
                                    location.reload();
                                },1000)
                            } else {
                                $.toaster({ priority : data.priority , message : data.messages });
                            }
                            
                        },
                        error : function (){
                            $.endload();
                            var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                            $(location).attr('href', host);
                        },
                    });
                },
                cancel: function(){
                }
            });
        } else {
            $.toaster({ priority : 'danger', message : 'Có lỗi khi tích điểm cho khách hàng. Vui lòng thử lại hoặc liên hệ với Admin để được trợ giúp' });
        }
});
// END tích điểm


// Nhập Chops (USED)
$(document).on('click','#change-chop',function(e){
        e.preventDefault();
        var id = $(this).data("id");

        var order_id = $('.order_id').val();
        var point_change = $('.point_change').autoNumeric('get');
        var name = $('.name-customer').text();
        var transferPoint = parseInt( $('#point-changed').text() );

        if ( point_change == "" ) {
            $.toaster({ priority : 'danger', message : 'Giá trị hóa đơn không được để trống' });
            $("#order-price").focus();
        } else if ( transferPoint == 0 ) {
            $.toaster({ priority : 'danger', message : 'Số Chops tích lũy cho khách hàng phải lớn hơn 0. Vui lòng thử lại' });
            $("#order-price").focus();
        } else if ( transferPoint >= 1 ) {
            $.confirm({
                theme: 'supervan',
                title: 'Xác nhận tích Chops',
                confirmButtonClass: 'btn-info',
                cancelButtonClass: 'btn-danger',
                content: "Bạn có muốn tích <span class='pink'>" + transferPoint +"</span> Chops cho khách hàng <span class='pink'>"+ name +"</span> không?",
                confirm: function(){
                    $.loadding();                        
                    $.ajax({
                        url: 'manage/store-chop',                
                        method:'post',
                        data : {
                            id : id,
                            order_id : order_id,
                            point_change : point_change

                        },
                                
                        success: function(data){
                            $.endload();
                            if (data.success == true) {
                                $.toaster({ priority : data.priority , message : data.messages });
                                $('#result-search').empty();
                                setTimeout(function(){
                                    location.reload();
                                },1000)
                            } else {
                                $.toaster({ priority : data.priority , message : data.messages });
                            }
                        },
                        error : function (xhr){
                            $.endload();
                            var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                            $(location).attr('href', host);
                        },
                    });
                },
                cancel: function(){
                }
            });
        } else {
            $.toaster({ priority : 'danger', message : 'Có lỗi khi tích Chops cho khách hàng. Vui lòng thử lại hoặc liên hệ với Admin để được trợ giúp' });
        }
});

// End tích chop






// Đổi chops (USED)
$(document).on('click','#change-chop-2',function(e){
        e.preventDefault();
        var id = $(this).data("id");

        var order_id = $('.order_id').val();
        var chop_change = $('.chop-change').val();
        var name = $('.name-customer').text();

        if ( chop_change == "" ) {
            $.toaster({ priority : 'danger', message : 'Vui lòng chọn số lượng Chops cần đổi' });
        } else {
            $.confirm({
                theme: 'supervan',
                title: 'Xác nhận đổi Chops',
                confirmButtonClass: 'btn-info',
                cancelButtonClass: 'btn-danger',
                content: "Bạn có muốn đổi <span class='pink'>" + chop_change +"</span> Chops cho khách hàng <span class='pink'>"+ name +"</span> không?",
                confirm: function(){
                    $.loadding();
                    $.ajax({
                        url: 'manage/discount-chop',                
                        method:'post',
                        data : {
                            id : id,
                            order_id : order_id,
                            chop_change : chop_change

                        },
                                
                        success: function(data){
                            $.endload();
                            if (data.success == true) {
                                $.toaster({ priority : data.priority , message : data.messages });
                                $('#result-search-2').empty();
                                setTimeout(function(){
                                    location.reload();
                                },1000);
                            } else {
                                $.toaster({ priority : data.priority , message : data.messages });
                            }
                            
                        },
                        error: function(){
                            $.endload();
                            var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                            $(location).attr('href', host);
                        },
                    });
                },
                cancel: function(){
                }
            });
        }
});





    //Empty search-result if value id-account null
    $('#id-account').keyup(function () {
        var checkValueSearchId = $.trim ( $(this).val() );
        if (checkValueSearchId == "") {
            $('#result-search').empty();
        }
    });

    //Empty search-result if value id-account null
	$('#id-account-2').keyup(function () {
	    var checkValueSearchId = $.trim ( $(this).val() );
	    if (checkValueSearchId == "") {
	    	$('#result-search-2').empty();
	    }
	});



	//Show checked select user
	$('.box-trackby-lv').click(function() {
		$('.box-trackby-lv').toggleClass('border-color-pink');
		$("#show-checked-user").toggleClass('checked-user');
		$('.button-add-point-track-by-id').toggleClass('show-box-add-point');
	});

var timer;

function up() {
	timer = setTimeout(function() {
		var keyWords = $('#id-account').val();

		if ( keyWords.length > 0 ) {
			
		}
	}, 500);
}

function down() {
	clearTimeout(timer);
}

});
