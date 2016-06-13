var array_content_image = new Array();
var array_upload_content_image = new Array();

var timeout = 100;

$.validator.addMethod("valueNotEquals", function(value, element, arg){
  return arg != value;
 }, "Value must not equal arg.");


function imageToken(x){
    var s = "";
    while(s.length<x&&x>0){
        var r = Math.random();
        s+= (r<0.1?Math.floor(r*100):String.fromCharCode(Math.floor(r*26) + (r>0.5?97:65)));
    }
    return s;
}

//Ham ve do thi

function drawChart(canvase_id, txt) {
	var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
	var data = jQuery.parseJSON($("#"+canvase_id).attr(txt));
	
	labels = [];
	values = [];
	$.each(data, function(index, value) {
		labels.push(index);
		values.push(value);
	});
    var lineChartData = {

        labels : labels,
        datasets : [
	            {
	                label: "My Second dataset",
	                fillColor : "rgba(151,187,205,0.2)",
	                strokeColor : "rgba(151,187,205,1)",
	                pointColor : "rgba(151,187,205,1)",
	                pointStrokeColor : "#fff",
	                pointHighlightFill : "#fff",
	                pointHighlightStroke : "rgba(151,187,205,1)",
	                data : values,
	            }
	        ]
        }
    var ctx = document.getElementById(canvase_id).getContext("2d");
    window.myLine = new Chart(ctx).Line(lineChartData, {
        responsive: true
    });
}
$(document).ready(function(){

	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        }
    });

    var timeout = 200;

    $.loadding = function() {
    	$("#loadding").fadeIn( timeout , function(){ 
         	$('#loadding').show();
        });
    }

    $.endload = function() {
    	$("#loadding").fadeOut( timeout , function(){ 
         	$('#loadding').hide();
        });
    }

	//Send feedback Merchant
	$('#send-feedbacks-merchant').click(function(e){
		e.preventDefault();
		var content = $('#message-text-feedback').val();
		if ( content == "" ) {
        	$.toaster({ priority : 'danger', message : 'Vui lòng nhập nội dung cần phản hồi' });
		} else {
			$.loadding();
			$.ajax({
	            url : $('#send-feedbacks-merchant').attr("data-post-url"),
	            method : 'post',
	            data : {
	            	message : content
	            },
	            success : function(data) {
	            	$.endload();
	            	if (data.success == true) {
	            		$('#message-text-feedback').val('');
	            		$('#send-feedbacks-merchant').text('Chúng tôi đã nhận được phản hồi').attr('disabled','disabled');
	            		$('#feedbackMerchant').modal('toggle');
	            		BootstrapDialog.show({
					        title: 'Xin cảm ơn',
					        message: data.messages,
					        buttons: [{
					            label: 'Đóng',
					            cssClass: 'btn-pink',
					            action: function(dialogItself){
					                dialogItself.close();
					            }
					        }]
					    });
	            	} else {
	            		console.log('Error');
	            	}
	            },
	            error : function (){},
	        });
		}
		
	});

/**
 * Upgrade
 */

 	function price(n) {
	    return  " " + n.toFixed(0).replace(/./g, function(c, i, a) {
	        return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
	    });
	}

	function tinhGia(a,b,c)
	{
		if ( b == -1) {
			return 0 + " VNĐ";
		} else{
			var total = Math.floor(((a * b) / 100) * (100 - c) * 1000);
			if (total > 20000000) {
				var totalPay = "Liên hệ 0462592111";
			} else {
				var totalPay = price(total) + " VNĐ";
			}
			return totalPay;
		}
	}

	function saving(tien,gia,sale) {
		var totalPay = tien * gia;
		var salePay = ((tien * gia) / 100) * (100 - sale);

		if (totalPay > 20000) {
			return "";
		} else {
			if (salePay == totalPay ) {
				return "";
			} else if (salePay > 1) {
				return "(Tiết kiệm " + price( Math.floor((totalPay - salePay) * 1000)) + " VNĐ)";
			} else {
				return ""
			}
		}
	}

	//Count packages
	$('#packages').change(function(e){
		e.preventDefault();
		var tien = $( "#packages option:selected" ).data('budget');
		var gia = $( "#time option:selected" ).val();
		var sale = $( "#time option:selected" ).data('sale');
		$('#count-pay').text(tinhGia(tien,gia,sale));

		$('#saving').text(saving(tien,gia,sale))

		
	});

	$('#time').change(function(e){
		e.preventDefault();
		var tien = $( "#packages option:selected" ).data('budget');
		var gia = $( "#time option:selected" ).val();
		var sale = $( "#time option:selected" ).data('sale');

		$('#count-pay').text(tinhGia(tien,gia,sale));

		$('#saving').text(saving(tien,gia,sale))
		
	});

$("#upgrade-request-package").validate({
        rules: {
        	"fullname" : {
                required : true,
            },
            "phone" : {
            	required : true,
            },
            "email" : {
            	required : true,
            },
            "time" : {
            	valueNotEquals: "-1" 
            },
            "packages" : {
            	valueNotEquals: "-1" 
            }
            
        },
        messages: {
        	"fullname" : {
                required : "(*) Vui lòng nhập đầy đủ họ tên ",
            },
            "phone" : {
                required : "(*) Vui lòng nhập số điện thoại ",
            },
            "email" : {
                required : "(*) Vui lòng nhập Email của bạn ",
            },
            "time" : {
                valueNotEquals : "(*) Vui lòng chọn khoảng thời gian sử dụng ",
            },
            "packages" : {
                valueNotEquals : "(*) Vui lòng chọn gói sử dụng ",
            },
               
        }
    });
    $("#saveRequestUpgrade").click(function(e) {
    	e.preventDefault();
    	var form = $("#upgrade-request-package");
    	form.validate();
    	if(form.valid()) {

    		$.confirm({
	            theme: 'supervan',
	            title: 'XÁC NHẬN GỬI YÊU CẦU THAY ĐỔI',
	            confirmButtonClass: 'btn-info',
	            cancelButtonClass: 'btn-danger',
	            content: 'Bạn có chắc chắn gửi cho chúng tôi yêu cầu nâng cấp tài khoản của bạn?',
	            confirm: function(){
	                $.loadding();
	                $.ajax({
	                    	url: 'upgrade-request-package',
		                    method:'post',
		                    data : 
		                    	$("#upgrade-request-package").serialize(),
		                    success: function(data){
		                    	$.endload();
		                        if (data.success == 'dialog') {
		                        	BootstrapDialog.show({
			                        	title : data.title,
							            message: data.messages,
							            buttons: [{
							                label: 'Đóng',
							                action: function(dialogItself){
							                    dialogItself.close();
							                    location.reload();
							                }
							            }]
							        });
							        $('#saveRequestUpgrade').closest('form').find("input[type=text], textarea").val("");
							        $("#packages option:first").attr('selected','selected');
							        $("#time option:first").attr('selected','selected');
							        $("#count-pay").text('0 VNĐ');

		                        } else {
		                        	BootstrapDialog.show({
			                        	title : data.title,
							            message: data.messages,
							            buttons: [{
							                label: 'Đóng',
							                action: function(dialogItself){
							                    dialogItself.close();
							                }
							            }]
							        });
		                        }
		                        

		                    },
		                    error: function(){},
		                });
	            
	                
	            },
	            cancel: function(){
	            	
	            }
	        });
    		
		}
    });

	//Count date
	
	$('.img-logo').click(function(){
		$('.upload-img').trigger('click');
	});


	$('.img-logo').click(function(){
		$('.edit-upload-img').trigger('click');
	});

	//upload avatar trong tao uu dai

	$('#img-avatar-btn').click(function(){
		$('#logo').trigger('click');

	});

	//click button chon avatar trong form edit
	$("#edit-img-avatar-btn").click(function() {
		$("#edit-logo").trigger("click");
	});


	//Edit Logo SuperAdmin
	$("#edit-image_logo").change(function(e) {
			var reader = new FileReader();
		    reader.onload = function(e){
		      	$( "div.contentImage" ).empty();
		      	$( "div.contentImage" ).append( "<img class=\"img-logo\" src=\"" + e.target.result + "\"/>" );
		      	$('.img-logo').click(function(){
					$('.upload-img').trigger('click');
				});
		    };
		    reader.readAsDataURL(event.target.files[0]);

		    $("#checkLogoExist").attr("value",null);
	});	

	

	$("#image_logo").change(function(e) {
		if($("#image_logo").valid()) {
			var reader = new FileReader();
		    reader.onload = function(e){
		      	$( "div.contentImage" ).empty();
		      	$( "div.contentImage" ).append( "<img class=\"img-logo\" src=\"" + e.target.result + "\"/>" );
		      	$( "#checkLogoExistFirst" ).val(imageToken(40));
		      	$( "#checkLogoExistFirst-error" ).remove();
		      	$('.img-logo').click(function(){
					$('.upload-img').trigger('click');
				});
		    };
		    reader.readAsDataURL(event.target.files[0]);

			$("#checkLogoExist").attr("value",null);
		}
		
	});
	//Xu ly khi file avatar thay doi
	$( "#logo" ).change(function( event ) {
		$("#logo").valid();
		
		if($("#logo").valid()) {
			var reader = new FileReader();
		    reader.onload = function(e){
		      	$( "#avatar-demo" ).empty();
		      	$( "#avatar-demo" ).append( "<img class=\"img-content-avatar\" src=\"" + e.target.result + "\"/>" );
		      	
			    var txt = "<ul class=\"list-image-incentives-avatar\"><li>Ảnh đại diện &nbsp;<i class=\"fa fa-trash pink \" id=\"btn-delete-avatar\"></i></li></ul>";
			    
			    $("#count-image-avatar").html(txt);
			    //Xu ly su kien cho btn vua sinh ra. Vi la sinh ra sau nen phai viet o day
			    $("#btn-delete-avatar").click(function() {
			    	var control = $("#logo");
		            control.replaceWith( control = control.clone( true ) );
		            if (control.val() == "") {
		            	$( "#avatar-demo" ).empty();
		            	$("#count-image-avatar").empty();
		            }
		        });
		    };
		    reader.readAsDataURL(event.target.files[0]);
		}
	    

	});

	//Xu ly khi file avatar trong form edit thay doi
	$( "#edit-logo" ).change(function( event ) {
		if ($("#edit-logo").valid()) {
			var reader = new FileReader();
		    reader.onload = function(e){
		      	$( "#edit-show-image" ).empty();
		      	$( "#edit-show-image" ).append( "<img class=\"img-content-avatar\" src=\"" + e.target.result + "\"/>" );
		      	// var txt = "<ul class=\"list-image-incentives-avatar\"><li>Ảnh đại diện &nbsp;<i class=\"fa fa-trash pink\" id=\"btn-delete-edit-avatar\"></i></li></ul>";
			    
			    // $("#count-edit-image-avatar").html(txt);
			    //Xu ly su kien cho btn vua sinh ra. Vi la sinh ra sau nen phai viet o day
			    $("#btn-delete-edit-avatar").click(function() {
			    	var control = $("#edit-logo");
		            control.replaceWith( control = control.clone( true ) );
		            if (control.val() == "") {
		            	$( "div.showEditImagesAvatar" ).empty();
		            	$("#count-edit-image-avatar").empty();
		            }
		        });
		    };
		    reader.readAsDataURL(event.target.files[0]);

		}
		
	});

	//Xy ly khi click vao button them anh noi dung
	$('#img-content-btn').click(function(){
		// $("div.carousel-inner").empty();
		
		$('#image-content').trigger('click');
		
		
	});
	//Xy ly khi click vao button them anh noi dung trong form edit
	$('#edit-img-content-btn').click(function(){
		// $("div.carousel-inner").empty();
		$('#edit-image-content').trigger('click');
		
		
	});
	//Xu ly khi xoa anh trong content
	
	//Xu ly khi file chua anh noi dung thay doi
	$( "#image-content" ).change(function( event ) {
		$( "#image-content" ).valid();
		if ($( "#image-content" ).valid()) {
			// $("#formCreateDeal").valid();
			array_content_image = [];
			array_upload_content_image = [];
			$("#image-content").attr("data-ignore-image","");

			$("#count-image-content").empty();
			$("#listContentImage").addClass("hide");	
			var txt = "<div class=\"item active\" id=\"item_active\"></div>";
			$("#carousel_inner").html(txt);
			txt = "<li data-target=\"#myCarousel\" data-slide-to=\"0\" class=\"active\"></li>";
			$("#carousel_indicators").html(txt);

			var list = $("#listContentImage");
			var carousel_indicators = $("#carousel_indicators");
			var carousel_inner = $("#carousel_inner");
			var files = $("#image-content")[0].files;
			var item_active = $("#item_active");

			array_content_image = new Array();

			for (var x = 0; x < files.length; x++) {
				var reader = new FileReader();
				reader.onload = function(e){
		      		//Them mang cac file
		      		
		      		array_content_image.push(e.target.result);
		    	};
		    	
		    	reader.readAsDataURL(event.target.files[x]);

			}			
		//remove class hide of list
			list.removeClass("hide");
			
			if (files.length > 0) {
				var img = document.createElement('img');
				var ul = document.createElement("ul");
	            var li = document.createElement("li");
	            var i_tag = document.createElement("a");
	            $(i_tag).addClass("fa fa-trash pink");
	            $(ul).addClass("list-image-incentives-avatar");
	            $(i_tag).addClass("btn-delete-content-image");
	            $(li).attr("id","image-content-1");
	            $(li).prepend("Ảnh nội dung 1&nbsp;");
	            $(i_tag).attr("data-image-order",1);

	            $(li).append(i_tag);

	            $(ul).append(li);
	            // $("#count-image-content").empty();
	            $("#count-image-content").append(ul);
				var reader = new FileReader();
				
				reader.onload = function(e){
		      		//Xu ly hien thi anh tren Bootstrap Carousel trong may di dong
		      		//link anh dau tien
		      		$(img).attr("src",e.target.result);
					item_active.append(img);
		    	};
		    	reader.readAsDataURL(event.target.files[0]);
			}
			if (files.length > 1) {
				for (var x = 1; x < files.length; x++) {
					//add to carousel-indicators
					var ul = document.createElement("ul");
		            var li = document.createElement("li");
		            var i_tag = document.createElement("a");
		            $(i_tag).addClass("fa fa-trash pink");
		            $(ul).addClass("list-image-incentives-avatar");
		            $(i_tag).addClass("btn-delete-content-image");
		            
		            $(li).prepend("Ảnh nội dung&nbsp;"+ (x + 1) + " ");
		            $(li).attr("id","image-content-"+(x + 1));
		            $(i_tag).attr("data-image-order",x + 1);

		            $(li).append(i_tag);

		            $(ul).append(li);
		            // $("#count-image-content").empty();
		            $("#count-image-content").append(ul);

					var reader = new FileReader();
					
					var li = document.createElement('li');
					
					//link anh
					reader.onload = function(e) {
		      			
		      			var div = document.createElement('div');
						var img = document.createElement('img');
						$(img).attr("src",e.target.result);
		      			$(div).addClass("item");
		      			$(div).append(img);
						carousel_inner.append(div);
					
		    		};
		    		reader.readAsDataURL(event.target.files[x]);
				
					
					$(li).attr({"data-target":"#myCarousel", "data-slide-to": x});
					carousel_indicators.append(li);

				}

			}	
			$(".btn-delete-content-image").click(function() {
				
				var image_id = $(this).attr("data-image-order");
				$("#image-content-"+image_id).remove();
				var array_ignore_image = $("#image-content").attr("data-ignore-image");
				if(array_ignore_image != ""){
					var temp = array_ignore_image + "," + (parseInt(image_id) - 1);
				} else {
					var temp = (parseInt(image_id) - 1);
				}
				$("#image-content").attr("data-ignore-image", temp);
				var array_ignore_image = $("#image-content").attr("data-ignore-image").split(",");
				var array_update_image = new Array();
				// var check = $.inArray(,array_ignore_image);
				for (var i = 0; i < array_content_image.length; i++) {
					if ($.inArray(i.toString(), array_ignore_image) == -1) {
						
						array_update_image.push(array_content_image[i]);
					}
				}

				
				// $("#listContentImage").addClass("hide");
				$("#carousel_inner").empty();
				var txt = "<div class=\"item active\" id=\"item_active\"></div>";
				$("#carousel_inner").html(txt);
				txt = "<li data-target=\"#myCarousel\" data-slide-to=\"0\" class=\"active\"></li>";
				$("#carousel_indicators").html(txt);

				if (array_update_image.length > 0) {
					var img = document.createElement('img');
					
		            
					$(img).attr("src",array_update_image[0]);
					$("#item_active").append(img);
				}
				if (array_update_image.length > 1) {
					for (var x = 1; x < array_update_image.length; x++) {
						//add to carousel-indicators
						
			            
						var li = document.createElement('li');
						
						//link anh

						var div = document.createElement('div');
						var img = document.createElement('img');
						$(img).attr("src",array_update_image[x]);
		      			// console.log(e.target.result);
		      			$(div).addClass("item");
		      			$(div).append(img);
						$("#carousel_inner").append(div);


					}

				}	
				array_upload_content_image = array_update_image;

				// $("#array-image-content").attr("data-array-image-content",array_update_image);
			});	
		}
		
		
	});

	
	//Xu ly khi file chua anh noi dung trong form edit thay doi
	$( "#edit-image-content" ).change(function( event ) {
	//get the input and UL list
		if($( "#edit-image-content").valid()){
			$("#listEditContentImage").addClass("hide");
			var txt = "<div class=\"item active\" style=\"width: 237px;\" id=\"edit_item_active\" ></div>";
			$("#edit_carousel_inner").html(txt);
			txt = "<li data-target=\"#myEditCarousel\" data-slide-to=\"0\" class=\"active\"></li>";
			$("#edit_carousel_indicators").html(txt);
			
			
			var list = $("#listEditContentImage");
			var carousel_indicators = $("#edit_carousel_indicators");
			var carousel_inner = $("#edit_carousel_inner");
			var files = $("#edit-image-content")[0].files;
			var item_active = $("#edit_item_active");

		//remove class hide of list
			list.removeClass("hide");
			if (files.length > 0) {
				var img = document.createElement('img');
				var reader = new FileReader();
				
				reader.onload = function(e){
		      		//Xu ly hien thi anh tren Bootstrap Carousel trong may di dong
		      		//link anh dau tien
		      		$(img).attr("src",e.target.result);
					item_active.append(img);
		    	};
		    	reader.readAsDataURL(event.target.files[0]);
			}
			if (files.length > 1) {
				for (var x = 1; x < files.length; x++) {
					//add to carousel-indicators
					var reader = new FileReader();
					
					var li = document.createElement('li');
					
					//link anh
					reader.onload = function(e) {
		      			
		      			var div = document.createElement('div');
						var img = document.createElement('img');
						$(img).attr("src",e.target.result);
		      			// console.log(e.target.result);
		      			$(div).addClass("item");
		      			$(div).append(img);
						carousel_inner.append(div);
					
		    		};
		    		reader.readAsDataURL(event.target.files[x]);
				
					
					$(li).attr({"data-target":"#myEditCarousel", "data-slide-to": x});
					carousel_indicators.append(li);
				
					
				}

			}
		}
		
	//for every file...
		
		
	});





	$(".type-chops").click(function(e){
		$( ".type-levels" ).attr( "data-type", "0" );
		$( this ).attr( "data-type", "1" );
		e.preventDefault();
		$("#level").css("display", "none");
		$("#chops").css("display", "block");

		$("#content-level").css("display", "none");
		$("#content-chops").css("display", "block");

		$('.type-chops span').addClass("current1");
		$('.type-levels span').removeClass("current1");

		$(".type-levels ul").hide();
		$(".type-levels-option").hide();
	});

	$(".type-levels").click(function(e){
		$( ".type-chops" ).attr( "data-type", "0" );
		$( this ).attr( "data-type", "1" );
		$(".type-levels-option").show();
		e.preventDefault();
		$("#level").css("display", "block");
		$("#chops").css("display", "none");


		$("#content-level").css("display", "block");
		$("#content-chops").css("display", "none");

		$('.type-levels span').addClass("current1");
		$('.type-chops span').removeClass("current1");
		$(".type-levels ul").show();


	});

	$(".type-levels-1").click(function(e){
		$( ".type-levels-2" ).attr( "data-level-option", "0" );
		$( ".type-levels-3" ).attr( "data-level-option", "0" );
		$( this ).attr( "data-level-option", "1" );
		e.preventDefault();
		$(".typel-level-option-1").show();
		$(".typel-level-option-2").hide();
		$(".typel-level-option-3").hide();
		$( ".type-levels-1 span" ).addClass("current1");
		$( ".type-levels-2 span" ).removeClass("current1");
		$( ".type-levels-3 span" ).removeClass("current1");

	});

	$(".type-levels-2").click(function(e){
		$( ".type-levels-1" ).attr( "data-level-option", "0" );
		$( ".type-levels-3" ).attr( "data-level-option", "0" );
		$( this ).attr( "data-level-option", "1" );
		e.preventDefault();
		$(".typel-level-option-1").hide();
		$(".typel-level-option-2").show();
		$(".typel-level-option-3").hide();
		$( ".type-levels-2 span" ).addClass("current1");
		$( ".type-levels-3 span" ).removeClass("current1");
		$( ".type-levels-1 span" ).removeClass("current1");

	});

	$(".type-levels-3").click(function(e){
		$( ".type-levels-1" ).attr( "data-level-option", "0" );
		$( ".type-levels-2" ).attr( "data-level-option", "0" );
		$( this ).attr( "data-level-option", "1" );
		e.preventDefault();
		$(".typel-level-option-1").hide();
		$(".typel-level-option-2").hide();
		$(".typel-level-option-3").show();
		$( ".type-levels-3 span" ).addClass("current1");
		$( ".type-levels-2 span" ).removeClass("current1");
		$( ".type-levels-1 span" ).removeClass("current1");

	});

	//Chops option

	$(".select-chop-option-1").click(function(e){
		$( ".select-chop-option-2" ).attr( "data-chops-option", "0" );
		$( this ).attr( "data-chops-option", "1" );
		e.preventDefault();
		$(".chops-option-gift-1").show();
		$(".chops-option-gift-2").hide();

		$( ".select-chop-option-1 span" ).addClass("current1");
		$( ".select-chop-option-2 span" ).removeClass("current1");


	});

	$(".select-chop-option-2").click(function(e){
		$( ".select-chop-option-1" ).attr( "data-chops-option", "0" );
		$( this ).attr( "data-chops-option", "1" );
		e.preventDefault();
		$(".chops-option-gift-2").show();
		$(".chops-option-gift-1").hide();

		$( ".select-chop-option-1 span" ).removeClass("current1");
		$( ".select-chop-option-2 span" ).addClass("current1");
	});




	var shopUsername = "<td><input type='text' name='shopUser' id='shopUser' class='form-control' placeholder='Nhập tên tài khoản cho shop'></td>";
	var passwordShopUser = "<td><input type='text' name='passwordShopUser' id='passwordShopUser' class='form-control' placeholder='Nhập mật khẩu'></td>";
	var actionAccountShop = "<td><center><i class='fa fa-pencil gray'></i></a><i class='fa fa-trash pink'></i></center></td>";



	//Show Packages
	$('#show-packages').click(function(e) {
		e.preventDefault();
		$('.table-packages').slideToggle( "slow" );

	});

	$('#show-packages-more').click(function(e) {
		e.preventDefault();
		if($('.table-packages').css('display') == 'none')
		{
			$('.table-packages').show( "slow" );
		}
	});

	$('#content-message').keyup(function () {
    	$('.text-messages').text($(this).val());
    });

	$('#titleIncentives').keyup(function () {
    	$('.title-demo-incentives-2, .title-demo-incentives-3').text($(this).val());
    });

	$('#contentIncentives').keyup(function () {
    	$('.content-demo-incentives, .demo-title').text($(this).val());
    });


	//Add star to Card



	$('.choice-stick').on('change', function(e) {
		e.preventDefault();
		var numbTick = $(this).val();
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
	});

	$('.choice-stick-1').on('change', function(e) {
		e.preventDefault();
		var numbTick = $(this).val();
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
	});

    function fixedCharCodeAt(str, idx) {
        idx = idx || 0;
        var code = str.charCodeAt(idx);
        var hi, low;
        if (0xD800 <= code && code <= 0xDBFF) { // High surrogate (could change last hex to 0xDB7F to treat high private surrogates as single characters)
            hi = code;
            low = str.charCodeAt(idx + 1);
            if (isNaN(low)) {
                throw 'Kein gültiges Schriftzeichen oder Speicherfehler!';
            }
            return ((hi - 0xD800) * 0x400) + (low - 0xDC00) + 0x10000;
        }
        if (0xDC00 <= code && code <= 0xDFFF) { // Low surrogate
            // We return false to allow loops to skip this iteration since should have already handled high surrogate above in the previous iteration
            return false;
            /*hi = str.charCodeAt(idx-1);
            low = code;
            return ((hi - 0xD800) * 0x400) + (low - 0xDC00) + 0x10000;*/
        }
        return code;
    }

    function countUtf8Length(str, length) {
    	var temp = "";
    	// console.log(str[0]);
    	for(var i = 0; i < str.length ; i++) {
    		temp += str[i];
    		// console.log(temp);
    		if(countUtf8(temp) == length){
    			return temp.length;
    		}
    	}
    }
	function countUtf8(str) {
	    var result = 0;
	    for (var n = 0; n < str.length; n++) {
	        var charCode = fixedCharCodeAt(str, n);
	        if (typeof charCode === "number") {
	            if (charCode < 128) {
	                result = result + 1;
	            } else if (charCode < 2048) {
	                result = result + 2;
	            } else if (charCode < 65536) {
	                result = result + 3;
	            } else if (charCode < 2097152) {
	                result = result + 4;
	            } else if (charCode < 67108864) {
	                result = result + 5;
	            } else {
	                result = result + 6;
	            }
	        }
	    }
	    return result;
    }


	// Counter input

	$('#titleIncentives').keyup(function () {
		$('#titleIncentives').valid();
		var str = $(this).val();
		var maxbyte = $(this).attr("maxbyte");
		var counterByte = countUtf8($(this).val()); 
		var utf8_length = countUtf8Length(str,maxbyte);
		var left = maxbyte - counterByte;
		// console.log(utf8_length);
		if(counterByte > maxbyte){
			$(this).val(str.substring(0,utf8_length));
			left = 0;
		}
	    
	    
	    $('#counter-title').text(left + '/60');
	    if(left<=0)
	    {
	    	return false;
	    }
	    return true;
	});

	$('#editTitleIncentives').keyup(function () {
		$('#editTitleIncentives').valid();
		var str = $(this).val();
		var maxbyte = $(this).attr("maxbyte");
		var counterByte = countUtf8($(this).val()); 
		var utf8_length = countUtf8Length(str,maxbyte);
		var left = maxbyte - counterByte;
		// console.log(utf8_length);
		if(counterByte > maxbyte){
			$(this).val(str.substring(0,utf8_length));
			left = 0;
		}
	    
	    
	    $('#edit-counter-title').text(left + '/60');
	    if(left<=0)
	    {
	    	return false;
	    }
	    return true;
	});
    

	$('#contentIncentives').keyup(function () {
		$('#contentIncentives').valid();

	    var str = $(this).val();
		var maxbyte = $(this).attr("maxbyte");
		var counterByte = countUtf8($(this).val()); 
		var utf8_length = countUtf8Length(str,maxbyte);
		var left = maxbyte - counterByte;
		// console.log(utf8_length);
		if(counterByte > maxbyte){
			$(this).val(str.substring(0,utf8_length));
			left = 0;
		}



	    $('#counter-content').text(left + '/500');
	    if(left<=0)
	    {
	    	return false;
	    }
	    return true;

	});

	$('#editContentIncentives').keyup(function () {
		$('#editContentIncentives').valid();

	    var str = $(this).val();
		var maxbyte = $(this).attr("maxbyte");
		var counterByte = countUtf8($(this).val()); 
		var utf8_length = countUtf8Length(str,maxbyte);
		var left = maxbyte - counterByte;

		if(counterByte > maxbyte){
			$(this).val(str.substring(0,utf8_length));
			left = 0;
		}



	    $('#edit-counter-content').text(left + '/500');
	    if(left<=0)
	    {
	    	return false;
	    }
	    return true;

	});
	$('#content-message').keyup(function () {
		$('#content-message').valid();
	    var str = $(this).val();
		var maxbyte = $(this).attr("maxbyte");
		var counterByte = countUtf8($(this).val()); 
		var utf8_length = countUtf8Length(str,maxbyte);
		var left = maxbyte - counterByte;
		// console.log(utf8_length);
		if(counterByte > maxbyte){
			$(this).val(str.substring(0,utf8_length));
			left = 0;
		}

	    $('#counter-message').text(left + '/140');
	    if(left<=0)
	    {
	    	return false;
	    }
	    return true;
	});

	//WELCOME
	$('.welcome-circle').focusin(function(){
	// $( ".welcome-circle" ).focus(function() {
		alert( "Handler for .focus() called." );
	});

});










