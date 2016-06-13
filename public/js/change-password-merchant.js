$(document).ready(function(){
    jQuery.validator.addMethod("notEqualTo",
	function(value, element, param) {
	    var notEqual = true;
	    value = $.trim(value);
	    for (i = 0; i < param.length; i++) {
	        if (value == $.trim($(param[i]).val())) { notEqual = false; }
	    }
	    return this.optional(element) || notEqual;
	},
	"Mật khẩu cũ và mật khẩu mới không được giống nhau"
	);

    $("#formChangePassword").validate({

        rules: {
        	"old_password" : {
                required : true,                
            },
            "new_password" : {
                required : true,
                minlength: 6,
                notEqualTo : ['#old_password'],   
            },
            "re_new_password" : {
                required : true,
                minlength: 6,
                equalTo : "#new_password",
            }
            
        },
        messages: {
        	"old_password" : {
                required : "(*) Mật khẩu cũ bắt buộc phải nhập",
            },
            "new_password" : {
                required : "(*) Mật khẩu mới bắt buộc phải nhập",
                minlength: "Mật khẩu không được nhỏ hơn 6 kí tự"
            },
            "re_new_password" : {
                required : "(*) Xác nhận mật khẩu mới bắt buộc phải nhập",
                minlength: "Mật khẩu không được nhỏ hơn 6 kí tự",
                equalTo : "Xác nhận mật khẩu mới không đúng",
            }
               
        }
    });
    $("#changePassWordButton").click(function(e) {
    	var form = $("#formChangePassword");
    	form.validate();
    	if(form.valid()) {
    		$.confirm({
	            theme: 'supervan',
	            title: 'XÁC NHẬN ĐỔI MẬT KHẨU',
	            confirmButtonClass: 'btn-info',
	            cancelButtonClass: 'btn-danger',
	            content: 'Bạn có chắc chắn muốn đổi mật khẩu hay không ???',
	            confirm: function(){
	                e.preventDefault();
	                var pass1 = $("#old_password").val();
        			var pass2 = $("#new_password").val();

        			$.loadding();
	                $.ajax({
	                    url: $("#change-pass-btn").attr("data-link-post"),
	                    
	                    method:'post',
	                    data : {
	                    	"old_password" : pass1,
	                    	"new_password" : pass2,
	                    },

	                    success: function(data){
	                    	$.endload();
	                		if ( data.success == true ) {
	                			BootstrapDialog.show({
			                        title: "THÀNH CÔNG",
			                        message: data.messages,
			                        buttons: [{
			                            label: 'Đóng',
			                            cssClass: 'btn-pink',
			                            action: function(dialogItself){
			                                dialogItself.close();
			                                window.location.href = data.logout
			                            }
			                        }]
			                    });
	                		} else {
	                			BootstrapDialog.show({
			                        title: "SAI MẬT KHẨU CŨ",
			                        message: data.messages,
			                        buttons: [{
			                            label: 'Đóng',
			                            cssClass: 'btn-pink',
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
	                location.reload();
	            }
	        });
		}
    });
});
