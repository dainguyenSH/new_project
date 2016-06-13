$(document).ready(function() {

    $("#closeShowPackagesInfo").click(function(){
      
        setTimeout(function(){
            location.reload();
        }, 1000);
    });

    var timeout = 100;


    $("#formPushMessage").validate({
        rules: {
            "content-message" : {
                required : true,
                maxlength: 140
            }
            
            
        },
        messages: {
            "content-message" : {
                required : "(*) Nội dung tin nhắn bắt buộc phải nhập",
                maxlength: "(*) Nội dung tin nhắn không vượt quá 140 kí tự"
            }
            
            
        }
    });

    $("#btn-push-message").click(function(e) {
        var __this = $(this);
        $("#formPushMessage").validate();
        if ($("#formPushMessage").valid()) {
            $.confirm({
                theme: 'supervan',
                title: 'GỬI TIN NHẮN',
                confirmButtonClass: 'btn-info',
                cancelButtonClass: 'btn-danger',
                content: "Tin nhắn sẽ được gửi tới thành viên. Bạn có muốn gửi ngay?",
                confirm: function(){
                    e.preventDefault(); 
                    var form = $("#formPushMessage");
                    var content = $("#content-message").val();
                    var deal_id = $('option:selected',$("#messageDealApply")).attr("data-deal-id");
                    var formData = new FormData();

                    formData.append("deal_id",deal_id);
                    formData.append("content",content);
                    $.loadding();

                    $("#btn-push-message").attr('disabled','disabled');
                    $.ajax({

                        url: 'store-messages',
                        processData : false,
                        cache: false,
                        contentType: false,
                        method:'post',
                        data : formData,
                    
                        success: function(data){
                            $.endload();
                            $("#btn-push-message").removeAttr('disabled');

                            if ( data.priority == "success") {
                                $("#content-message").empty();
                                BootstrapDialog.show({
                                    title: data.priority,
                                    message: data.messages,
                                    buttons: [{
                                        label: 'Đóng',
                                        cssClass: 'btn-pink',
                                        action: function(dialogItself){
                                            dialogItself.close();
                                            setTimeout(function(){
                                                location.reload();
                                            }, 1000);
                                        }
                                    }]
                                });
                            } else {
                                BootstrapDialog.show({
                                    title: 'Thông báo',
                                    message: data.messages,
                                    

                                    buttons: [{
                                        label: 'Đóng',
                                        action: function(dialogItself) {
                                            dialogItself.close();
                                            setTimeout(function(){
                                                location.reload();
                                            }, 1000);
                                        }
                                    }, {
                                        label: 'Tìm hiểu các gói dịch vụ',
                                        cssClass: 'btn-pink',
                                        action: function(dialogItself) {
                                            $("#showPackagesInfo").modal();
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
});
	
