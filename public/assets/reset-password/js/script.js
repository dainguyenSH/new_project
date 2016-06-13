$(document).ready(function(){
	$("#reset-password-button").click(function(e){
		e.preventDefault();

		$.ajax({
	        url : $("#reset-password-button").attr("data-post-url"),
	        method : 'post',
	        data : {
	        	email : $("#reset-password-input").val()
	        },
	        success : function(data) {
	        	BootstrapDialog.show({
			        title: data.priority,
			        message: data.messages,
			        buttons: [{
			            label: 'Đóng',
			            cssClass: 'btn-pink',
			            action: function(dialogItself){
			                dialogItself.close();
			            }
			        }]
			    });
	        },
	        error : function (){},
	    });
	});

	$("#confirmResetPasswordForm").validate({
	    rules: {
	    	
	        "new_password" : {
	            required : true,
	            minlength: 6
	            
	        },
	        "re_new_password" : {
	            required : true,
	            minlength: 6
	        }
	        
	    },
	    messages: {
	    	
	        "new_password" : {
	            required : "(*) Mật khẩu mới bắt buộc phải nhập",
	            minlength: "Mật khẩu không được nhỏ hơn 6 kí tự"
	        },
	        "re_new_password" : {
	            required : "(*) Xác nhận mật khẩu mới bắt buộc phải nhập",
	            minlength: "Mật khẩu không được nhỏ hơn 6 kí tự"
	        }
	           
	    }
	});

	$.validator.addMethod("checkTwoResetPasswordIsSame", function() {
            
        var pass1 = $("#new-reset-password").val();
        var pass2 = $("#re-new-reset-password").val();
        //Any code that will return TRUE or FALSE
        if ((pass2 != pass1)){
            return false;
        }else{
            return true;
        }

    },"Hai mật khẩu nhập vào không khớp");

	$("#confirm-password-button").click(function(e) {
		e.preventDefault();
		var form = $("#confirmResetPasswordForm");
		form.validate();

		if(form.valid()) {

			$.confirm({
	            theme: 'supervan',
	            title: 'XÁC NHẬN ĐỔI MẬT KHẨU',
	            confirmButtonClass: 'btn-info',
	            cancelButtonClass: 'btn-danger',
	            content: 'Bạn có chắc chắn muốn đổi mật khẩu hay không ???',
	            confirm: function(){
	                
	                
	    			var pass2 = $("#new-reset-password").val();
	                var token = $("#token_password").val();	
	                
	            
	                $.ajax({
	                    url: $("#confirm-password-button").attr("data-post-url"),
	                    
	                    method:'post',
	                    data : {
	                    	
	                    	"new_password" : pass2,
	                    	"token" : token,
	                    },
	                
	                    success: function(data){
	                        
	                        
	                		if(data.success == true) {
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
