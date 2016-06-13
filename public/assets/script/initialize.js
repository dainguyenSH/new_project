// $(document).ready(function() {
$('#example-advanced-form').bind("keypress", function(e) {
  if (e.keyCode == 13) {               
    e.preventDefault();
    return false;
  }
});

jQuery.validator.addMethod("noWhiteSpace", function(value, element) {
    return this.optional(element) || value == value.trim();
}, "Vui lòng không thêm khoảng trắng ở 2 đầu dữ liệu nhập");

if ($("#checkLogoExist").val() != null) {
    $("#image_logo_btn").removeClass("required");
}
$.validator.addMethod("valueNotEquals", function(value, element, arg){
  return arg != value;
 }, "(*) Nhập giá trị lớn hơn 0");

$.validator.addMethod("smallerThan", function(value, element, param){
    var $otherElement = $(param);
    return parseInt(value, 10) < parseInt($otherElement.val(), 10);
 }, "Lớn hơn");

$.validator.addMethod("phoneNumber", function(value, element) {
  // return this.optional(element) || /^[0-9]+$/i.test(value);
  return this.optional(element) || /^[0-9\-\()]+$/i.test(value);
}, "(*) Sai định dạng. Bao gồm từ [0-9] và ký tự [(-)]"); 









var form = $("#example-advanced-form").show();
    form.steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "slideLeft",
        onStepChanging: function(event, currentIndex, newIndex) {
            // Allways allow previous action even if the current form is not valid!
            //return true;

            if (currentIndex > newIndex) {
                return true;
            }

            // Needed in some cases if the user went back (clean up)
            if (currentIndex < newIndex) {
                // To remove error styles
                form.find(".body:eq(" + newIndex + ") label.error").remove();
                form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
            }
            // form.validate().settings.ignore = ":disabled,:hidden";

            // return true;
            
            if ( form.valid() ) {
                if ( currentIndex === 0 ) {

                    $.ajax({
                        url: 'initialize/infomerchant',
                        method:'post',
                        data : {
                            trademark: $.trim( $("input[name='trademark']").val() ),
                            field: $("select[name='field']").val(),

                            current_i_m_g_s: $("#current_i_m_g_s").val(),

                            logo : $("#image_logo")[0].files,
                            
                            logo: $("img.img-logo").attr( "src" ),
                            check_logo: $("#checkLogoExist").val(),
                            // logo: $("input[name='logo']").files[0],
                            color : $("#background-color").val(),

                            fullname: $("input[name='fullname']").val(),
                            role: $("input[name='role']").val(),

                            day: $("select[name='day']").val(),
                            month: $("select[name='month']").val(),
                            year: $("select[name='year']").val(),


                            address: $("input[name='address']").val(),

                            province: $("select[name='province']").val(),
                            district: $("select[name='district']").val(),


                            phone: $("input[name='phone']").val(),

                            email: $("input[name='email']").val(),

                        },
                        success: function(data){
                            $.toaster({ priority : data.priority, message : data.messages });
                        },
                        error : function (xhr){
                            $.endload();
                            var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                            $(location).attr('href', host);
                        },
                    });

                    if ( 1 ) {
                        return true;
                    }
                    return false;

                } else if( currentIndex === 1 ){

                        var dataType;
                        var selectOption;
                        var settings = [];

                        if ( $( ".type-chops" ).attr( "data-type" ) === "1" ) {
                            dataType = 1;
                            if ( $( ".select-chop-option-1" ).attr( "data-chops-option" ) === "1" ) {
                                selectOption = 1;

                                if ($('#select-chop-gift-1-1').val() != "") {
                                    var temp1 =[];
                                    temp1.push($( "#select-chop-gift-1-1" ).val());
                                    temp1.push($('#value-discount-gift-1-1').val());
                                    temp1.push($('#value-discount-1').val());

                                    settings.push(temp1);
                                }

                                if ($('#select-chop-gift-1-2').val() != "") {
                                    var temp2 =[];
                                    temp2.push($( "#select-chop-gift-1-2" ).val());
                                    temp2.push($('#value-discount-gift-1-2').val());
                                    temp2.push($('#value-discount-1').val());

                                    settings.push(temp2);
                                }

                                if ($('#select-chop-gift-1-3').val() != "") {
                                    var temp3 =[];
                                    temp3.push($( "#select-chop-gift-1-3" ).val());
                                    temp3.push($('#value-discount-gift-1-3').val());
                                    temp3.push($('#value-discount-1').val());

                                    settings.push(temp3);
                                }

                            } else {
                                selectOption = 2;

                                if ($('#select-chop-gift-2-1').val() != "") {
                                    var temp1 =[];
                                    temp1.push($( "#select-chop-gift-2-1" ).val());
                                    temp1.push($('#value-discount-gift-2-1').val());
                                    temp1.push($('#value-discount').autoNumeric('get'));

                                    settings.push(temp1);
                                }

                                if ($('#select-chop-gift-2-2').val() != "") {
                                    var temp2 =[];
                                    temp2.push($( "#select-chop-gift-2-2" ).val());
                                    temp2.push($('#value-discount-gift-2-2').val());
                                    temp2.push($('#value-discount').autoNumeric('get'));

                                    settings.push(temp2);
                                }

                                if ($('#select-chop-gift-2-3').val() != "") {
                                    var temp3 =[];
                                    temp3.push($( "#select-chop-gift-2-3" ).val());
                                    temp3.push($('#value-discount-gift-2-3').val());
                                    temp3.push($('#value-discount').autoNumeric('get'));

                                    settings.push(temp3);
                                }

                            }
                        } else if ( $( ".type-levels" ).attr( "data-type" ) === "1" ) {
                            dataType = 0;
                            if ( $( ".type-levels-1" ).attr( "data-level-option" ) == "1" ) {
                                selectOption = 11; 
                                //vàng, bac, dong
                                var temp1 = [];
                                temp1.push($('#point-3-1').autoNumeric('get'));
                                temp1.push($('#bonus-point-3-1').autoNumeric('get'));
                                temp1.push($('#value-discount-level').autoNumeric('get')); //unit
                                settings.push(temp1);

                                var temp2 = [];
                                temp2.push($('#point-3-2').autoNumeric('get'));
                                temp2.push($('#bonus-point-3-2').autoNumeric('get'));
                                temp2.push($('#value-discount-level').autoNumeric('get')); //unit
                                settings.push(temp2);

                                var temp3 = [];
                                temp3.push($('#point-3-3').autoNumeric('get'));
                                temp3.push($('#bonus-point-3-3').autoNumeric('get'));
                                temp3.push($('#value-discount-level').autoNumeric('get')); //unit
                                settings.push(temp3);

                            } else if( $( ".type-levels-2" ).attr( "data-level-option" ) == "1" ) {
                                // vvip, vip, thanh 
                                selectOption = 22;

                                var temp1 = [];
                                temp1.push($('#point-3-3-1').autoNumeric('get'));
                                temp1.push($('#bonus-point-3-3-1').autoNumeric('get'));
                                temp1.push($('#value-discount-level').autoNumeric('get')); //unit
                                settings.push(temp1);

                                var temp2 = [];
                                temp2.push($('#point-3-3-2').autoNumeric('get'));
                                temp2.push($('#bonus-point-3-3-2').autoNumeric('get'));
                                temp2.push($('#value-discount-level').autoNumeric('get')); //unit
                                settings.push(temp2);

                                var temp3 = [];
                                temp3.push($('#point-3-3-3').autoNumeric('get'));
                                temp3.push($('#bonus-point-3-3-3').autoNumeric('get'));
                                temp3.push($('#value-discount-level').autoNumeric('get')); //unit
                                settings.push(temp3);

                            } else if( $( ".type-levels-3" ).attr( "data-level-option" ) == "1" ) {
                                selectOption = 33; 
                                //vip, thanh vien
                                var temp1 = [];
                                temp1.push($('#point-1').autoNumeric('get'));
                                temp1.push($('#bonus-point-1').autoNumeric('get'));
                                temp1.push($('#value-discount-level').autoNumeric('get')); //unit
                                settings.push(temp1);

                                var temp2 = [];
                                temp2.push($('#point-2').autoNumeric('get'));
                                temp2.push($('#bonus-point-2').autoNumeric('get'));
                                temp2.push($('#value-discount-level').autoNumeric('get')); //unit
                                settings.push(temp2);
                            }
                        }

                    if ( settings.length == 0 ) {
                        BootstrapDialog.show({
                            title: 'Lỗi tạo hạng thẻ',
                            message: "Vui lòng nhập đầy đủ thông tin trước khi hoàn thành khởi tạo thẻ"
                        });
                        return false;
                    } else {
                        $.ajax({
                            url : 'initialize/createtypecard',
                            method : 'post',

                            
                            data : {
                                choiced : dataType,
                                selectOption : selectOption,
                                settings :  settings
                            },
                            success : function(data) {
                                $.toaster({ priority : data.priority, message : data.messages });
                            },
                            error : function (xhr){
                                $.endload();
                                var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                                $(location).attr('href', host);
                            },
                        });
                        return true;

                    }
                } else if( currentIndex === 2 ){


                    $.ajax({
                        url : 'get-all-info-packages',
                        method : 'get',
                        data : {},
                        success : function(data) {
                            $('.count-store').text(data.count);
                            $('.pricing-packages').text(data.packages);

                            $('#info-merchant-fullname').val(data.info.fullname);
                            $('#info-merchant-phone').val(data.info.phone);
                            $('#info-merchant-email').val(data.info.email);

                            if ( data.count > 1 ) {
                                $('.show-only-free').html("Nếu quý khách muốn dùng thử bản Miễn phí vui lòng Quay lại bước 3 và xóa bớt cửa hàng trước khi xác nhận. <span class='pink'>Chú ý: Gói Miễn phí chỉ cho phép đăng ký 1 cửa hàng</span>");
                            } else {
                                $('.show-only-free').html('');
                            }
                        },
                        error : function (xhr){
                            $.endload();
                            var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                            $(location).attr('href', host);
                        },
                    });
                    if ( 1 ) {
                        return true;
                    }
                    return false;
                    
                }
            }
            //return true;

        },
        onStepChanged: function(event, currentIndex, priorIndex) {
            // Used to skip the "Warning" step if the user is old enough.
            if (currentIndex === 2 && Number($("#age-2").val()) >= 18) {
                // form.steps("next");
            }
            // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
            if (currentIndex === 2 && priorIndex === 3) {
                // form.steps("previous");

            }
        },
        onFinishing: function(event, currentIndex) {
            // form.validate().settings.ignore = ":disabled";
            return form.valid();
        },
        onFinished: function(event, currentIndex) {

            $.confirm({
                theme: 'supervan',
                title: 'KHỞI TẠO CHƯƠNG TRÌNH THẺ THÀNH VIÊN',
                confirmButtonClass: 'btn-info',
                cancelButtonClass: 'btn-danger',
                content: 'Bạn có chắc chắn muốn khởi tạo chương trình thẻ thành viên này?',
                confirm: function(){
                    // e.preventDefault();
                    $.loadding();

                    var fullname = $('#info-merchant-fullname').val();
                    var phone = $('#info-merchant-phone').val();
                    var email = $('#info-merchant-email').val();
                    var content = $('#info-merchant-content').val();



    
                
                    $.ajax({
                        url : 'register-merchants',
                        method : 'post',
                        data : {
                            fullname : fullname,
                            phone : phone,
                            email : email,
                            content : content
                        },
                        success : function(data) {
                            $.endload();
                            $.toaster({ priority : data.priority, message : data.messages });
                            location.reload();
                        },
                        error : function (xhr){
                            $.endload();
                            var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                            $(location).attr('href', host);
                        },
                    })
                },
                cancel: function(){
                    
                }
            });


            
        }
    }).validate({
        errorPlacement: function errorPlacement(error, element) {
            element.before(error);
        },
        ignore: "input[type='text']:hidden",
        rules: {
            trademark: {
                required: true,
                noWhiteSpace: true
            },
            field: {
                valueNotEquals: "-1"
            },
            fullname: {
                required: true,
                noWhiteSpace: true
            },
            role: {
                required: true,
                noWhiteSpace: true
            },
            // address: {
            //     required: true,
            //     noWhiteSpace: true
            // },
            // district: {
            //     valueNotEquals: "-1" 
            // },

            // province: {
            //     valueNotEquals: "-1" 
            // },
            phone: {
                required: true,
                noWhiteSpace: true,
                number: true
            },

            // LEVEL

            valuediscountlevel : {
                required: true,
                valueNotEquals: "0"
            },

            //CHOPS

            valuesetchop : {
                required: true,
                valueNotEquals: "0"
            },

            //Option LV1
            vang1 : {
                required : true,
                valueNotEquals: "0"
            },
            vang2 : {
                required : true,
                valueNotEquals: "0"
            },

            bac1 : {
                required : true,
            },
            bac2 : {
                required : true,
            },

            dong1 : {
                required : true,
            },
            dong2 : {
                required : true,
            },

            // Option LV2
            vvip1 : {
                required : true,
                valueNotEquals: "0"
            },
            vvip2 : {
                required : true,
                valueNotEquals: "0"
            },

            vip1 : {
                required : true,
            },
            vip2 : {
                required : true,
            },

            mem1 : {
                required : true,
            },
            mem2 : {
                required : true,
            },

            // Option 3

            vip3 : {
                required : true,
                valueNotEquals: "0"
                
            },
            vip4 : {
                required : true,
                valueNotEquals: "0"
            },

            mem3: {
                required : true,
            },
            mem4 : {
                required : true,
            },

            //Steep 4
            fullname : {
                required : true,
            },

            phone : {
                required : true,
                // noWhiteSpace: true,
                // number: true
                phoneNumber : true,
            },

            email : {
                required : true,
                noWhiteSpace: true,
                email: true
            },

            email: {
                required: true,
                noWhiteSpace: true,
                email: true
            }, 
            logo : {
                // required : true,
                // maxlength : 3,
                extension: "jpg|jpeg|png",
                filesize: 1024000
            },
            checklogo : {
                required: true,
                minlength:39,
            }
        },
        messages: {
            trademark:{
                required: "(*) Vui lòng nhập tên thương hiệu",
            },
            field: {
                valueNotEquals: "(*) Vui lòng chọn lĩnh vực",
            },
            fullname: {
                required: "(*) Vui lòng nhập họ tên",
            },
            role: {
                required: "(*) Vui lòng nhập vai trò",
            },
            // address: {
            //     required: "(*) Vui lòng nhập địa chỉ",
            // },
            // district: {
            //     valueNotEquals: "(*) Vui lòng chọn quận/huyện",
            // },
            // province: {
            //     valueNotEquals: "(*) Vui lòng chọn tỉnh/thành",
            // },
            phone: {
                required: "(*) Vui lòng nhập số điện thoại",
                // number: "(*) Vui lòng chỉ nhập số"
            },
            email: {
                required: "(*) Vui lòng nhập email",
                email: "(*) Vui lòng nhập đúng định dạng email"
            },
            logo: {
                // required : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(*) Ảnh nội dung bắt buộc phải được chọn",
                // maxlength : "Ảnh nội dung chỉ được upload tối đa 3 ảnh",
                extension: "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(*) Vui lòng lựa chọn đúng định dạng ảnh .jpg | .jpeg | .png",
                filesize : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(*) Kích thước tập tin phải nhỏ hơn 1MB"
            },
            checklogo : {
                required : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(*) Ảnh nội dung bắt buộc phải được chọn",
                min : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Không nhận được tham số image_token. Vui lòng tải lại trang',
            }
        },
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

$.validator.addMethod('filesize', function(value, element, param) {
    // param = size (in bytes) 
    // element = element to validate (<input>)
    // value = value of the element (file name)
    return this.optional(element) || (element.files[0].size <= param) 
});

$.validator.addMethod('checkhtmltag', function(value, element) {
    if (value.match(/<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/)) {
        return false;
    } else {
        return true;
    }
}, "(*) Chuỗi nhập vào không được chứa thẻ HTML");



// Xóa cửa hàng

$(document).on('click','.destroy-store',function(){
    var id = $(this).data("id");
    var __this = $(this);
    $.confirm({
        theme: 'supervan',
        title: 'XÓA CỬA HÀNG',
        confirmButtonClass: 'btn-info',
        cancelButtonClass: 'btn-danger',
        content: 'Xóa cửa hàng đồng nghĩa với việc bạn sẽ xóa đi tài khoản của THU NGÂN phía dưới. Các thông tin giao dịch cũ sẽ bị mất. Bạn có chắc muốn xóa cửa hàng này?',
        confirm: function(){
           

            $.ajax(
            {
                url: 'destroy-store/',
                type: 'DELETE',
                dataType: "JSON",
                data: {
                    "id": id
                },
                success: function (data)
                {
                    if (data.success == true) {
                    __this.closest('tr').remove();
                    $('.table-create-account-shop').find('.save-store[data-id="'+id+'"]').closest('tr').remove();

                    }
                    $.toaster({ priority : data.priority, message : data.messages });
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
});


//Kích hoạt cửa hàng

$(document).on('click','.active-store',function(){
    var id = $(this).attr("data-id");
    var __this = $(this);
    $.confirm({
        theme: 'supervan',
        title: 'KÍCH HOẠT CỬA HÀNG',
        confirmButtonClass: 'btn-info',
        cancelButtonClass: 'btn-danger',
        content: 'Bạn có chắc chắn muốn kích hoạt cửa hàng này',
        confirm: function(){
            
            $.loadding();
            $.ajax(
            {
                url: 'config-active-store',
                type: 'POST',
                dataType: "JSON",
                data: {
                    "id": id
                },
                success: function (data)
                {
                    $.endload();
                    
                    if ( data.type == 'dialog' ) {
                        BootstrapDialog.show({
                            title: data.title ,
                            message: data.messages,
                            buttons: [{
                                label: 'Đóng',
                                action: function(dialogItself) {
                                    dialogItself.close();

                                    if (data.success == true) {
                                        location.reload();
                                    }
                                }
                            }]
                        });
                    } else if (data.type == 'upgrade') {
                        BootstrapDialog.show({
                            title: data.title ,
                            message: data.messages,
                            buttons: [{
                                label: 'Đóng',
                                action: function(dialogItself) {
                                    dialogItself.close();
                                }
                            }, {
                                label: 'Tìm hiểu các gói dịch vụ',
                                cssClass: 'btn-pink',
                                action: function(dialogItself) {
                                    $("#showPackagesInfo").modal()
                                    dialogItself.close();
                                }
                            }]
                        });

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
});


//Đóng cửa cửa hàng

$(document).on('click','.unactive-store',function(){
    var id = $(this).attr("data-id");
    var __this = $(this);
    $.confirm({
        theme: 'supervan',
        title: 'TẠM NGỪNG CỬA HÀNG',
        confirmButtonClass: 'btn-info',
        cancelButtonClass: 'btn-danger',
        content: 'Bạn có chắc chắn muốn dừng hoạt động cửa hàng này?',
        confirm: function(){
            
            $.loadding();
            $.ajax(
            {
                url: 'config-inactive-store',
                type: 'POST',
                dataType: "JSON",
                data: {
                    "id": id
                },
                success: function (data)
                {
                    $.endload();
                    
                    if ( data.type == 'dialog' ) {
                        BootstrapDialog.show({
                            title: data.title ,
                            message: data.messages,
                            buttons: [{
                                label: 'Đóng',
                                action: function(dialogItself) {
                                    dialogItself.close();

                                    if (data.success == true) {
                                        location.reload();
                                    }
                                }
                            }]
                        });
                    } else if (data.type == 'upgrade') {
                        BootstrapDialog.show({
                            title: data.title ,
                            message: data.messages,
                            buttons: [{
                                label: 'Đóng',
                                action: function(dialogItself) {
                                    dialogItself.close();
                                }
                            }, {
                                label: 'Tìm hiểu các gói dịch vụ',
                                cssClass: 'btn-pink',
                                action: function(dialogItself) {
                                    $("#showPackagesInfo").modal()
                                    dialogItself.close();
                                }
                            }]
                        });

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
});






//Store and Edit Store
$(document).on('click','.save-store',function(){
        $(this).closest('tr').find('input').addClass('sr-only');
        $(this).closest('tr').find('label').removeClass('sr-only');

        $(this).closest('tr').find('i.save-store').addClass('sr-only');
        $(this).closest('tr').find('i.edit-store').removeClass('sr-only');

        var username =  $.trim( $(this).closest('tr').find('input').first().val() ).replace(/\s/g, "") ;
        var password = $.trim( $(this).closest('tr').find('input').last().val() ).replace(/\s/g, "") ;

        var id = parseInt($(this).data('id'));

        var __this = $(this);
            $.confirm({
                theme: 'supervan',
                title: 'Thay đổi tài khoản thu ngân',
                confirmButtonClass: 'btn-info',
                cancelButtonClass: 'btn-danger',
                content: 'Bạn có chắc chắn muốn thay đổi tài khoản thu ngân?',
                confirm: function(){
                    $.loadding();
                    

                    $.ajax({
                        url : 'initialize/update-user-store',
                        method : 'post',
                        data : {
                            id : id,
                            username : username,
                            password : password,
                        },
                        success : function (data) {
                            $.endload();
                            if (data.success == 'validator') {
                                BootstrapDialog.show({
                                    title: data.title,
                                    message: data.messages,

                                });
                            } else {
                                BootstrapDialog.show({
                                    title: data.title,
                                    message: "<center>"+data.messages+"</center>"
                                });
                                if (data.success == true) {
                                    $(__this).closest('tr').find('label').first().text(username);
                                    $(__this).closest('tr').find('label').last().text('******');
                                    $(__this).closest('tr').find('input').last().val('');
                                }
                            }
                        },
                        error : function (xhr){
                            $.endload();
                            var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                            $(location).attr('href', host);
                        },
                    })
                    
                },
                cancel: function(){
                    
                }
            });      
});

// Save Update Name & Address

$(document).on('click','.save-name-store',function(){
        $(this).closest('tr').find('input').addClass('sr-only');
        $(this).closest('tr').find('label').removeClass('sr-only');

        $(this).closest('tr').find('i.save-name-store').addClass('sr-only');
        $(this).closest('tr').find('i.edit-name-store').removeClass('sr-only');

        var storeName = $.trim( $(this).closest('tr').find('input').first().val() );
        var storeAddresss = $.trim( $(this).closest('tr').find('input').last().val() );

        var id = parseInt($(this).attr('data-id'));

        var __this = $(this);
            $.confirm({
                theme: 'supervan',
                title: 'Thay đổi tên và địa chỉ cửa hàng',
                confirmButtonClass: 'btn-info',
                cancelButtonClass: 'btn-danger',
                content: 'Bạn có chắc chắn muốn thay đổi tên và tài khoản cửa hàng?',
                confirm: function(){
                    $.loadding();
                    $.ajax({
                        url : 'initialize/update-name-store',
                        method : 'post',
                        data : {
                            id : id,
                            storename : storeName,
                            storeaddress : storeAddresss,
                        },
                        success : function (data) {
                            $.endload();
                            if (data.success == 'validator') {
                                BootstrapDialog.show({
                                    title: data.title,
                                    message: data.messages,

                                });
                            } else {
                                BootstrapDialog.show({
                                    title: data.title,
                                    message: "<center>"+data.messages+"</center>"
                                });
                                if (data.success == true) {
                                    $(__this).closest('tr').find('label').first().text(storeName);
                                    $(__this).closest('tr').find('label').last().text(storeAddresss);
                                    $('.table-create-account-shop').find('.save-store[data-id="'+id+'"]').closest('tr').find('h4').text(storeName);                                }
                            }
                        },
                        error : function (xhr){
                            $.endload();
                            var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                            $(location).attr('href', host);
                        },
                    })
                    
                },
                cancel: function(){
                    
                }
            });      
});


$(document).on('click','.edit-store',function(){
        $(this).closest('tr').find('input').removeClass('sr-only');
        $(this).closest('tr').find('label').addClass('sr-only');

        $(this).closest('tr').find('i.save-store').removeClass('sr-only');
        $(this).closest('tr').find('i.edit-store').addClass('sr-only');
});


$(document).on('click','.edit-name-store',function(){
        $(this).closest('tr').find('input').removeClass('sr-only');
        $(this).closest('tr').find('label').addClass('sr-only');

        $(this).closest('tr').find('i.save-name-store').removeClass('sr-only');
        $(this).closest('tr').find('i.edit-name-store').addClass('sr-only');
});



    $(".button-create-store-address").click(function(e){
        e.preventDefault();

        var getNameShop =  $.trim( $('#name-shop').val() );
        var getAddressShop = $.trim( $('#address-shop').val() );
        $.loadding();
        
    
        $.ajax({
            url : 'initialize/storeshop',
            method : 'post',
            data : {
                name : getNameShop,
                address : getAddressShop
            },
            success : function (data) {
                $.endload();
                if (data.success == true) {
                    $(".create-shop-address .table").append("<tr><td><label>"+data.allstore.name+"</label><input type='text' name='' value='"+data.allstore.name+"' class='form-control sr-only'></td><td><label>"+data.allstore.address+"</label><input type='text' name='' value='"+data.allstore.address+"' class='form-control sr-only'></td><td><i class='fa fa-pencil gray edit-name-store' data-toggle='tooltip' title='Sửa thông tin'></i><i data-id='"+data.id+"' class='fa fa-check pink save-name-store sr-only' data-toggle='tooltip' title='Lưu thay đổi thông tin cửa hàng'></i><i data-id='"+data.id+"' class='fa fa-trash  destroy-store' data-toggle='tooltip' title='Xóa cửa hàng (Đồng nghĩa với xóa tài khoản thu ngân)'></i><i data-id='"+data.id+"' class='fa fa-play pink active-store' data-toggle='tooltip' title='Kích hoạt cửa hàng'></i></td><td><span class='pink'>Đang tạm dừng</span></td></tr>");
                    $.toaster({ priority : data.priority, message : data.messages });
                    $('#name-shop').val('');
                    $('#address-shop').val('');
                    $("#table-create-account-shop").append("<tr><td>" + getNameShop + "</td><td>" + getAddressShop + "</td><td><center><i class='fa fa-pencil gray'></i></a><i class='fa fa-trash pink'></i></center></td></tr>");
                    //Append 
                    $(".table-create-account-shop").append("<tr><td><h4 class='pink'>"+getNameShop+"</h4></td><td><label><span class='pink'>(Tên đăng nhập)</span></label><input type='text' name='' value='' class='form-control sr-only'></td><td><label><span class='pink'>(Mật khẩu đăng nhập)</span></label><input type='password' name='' value='' class='form-control sr-only'></td><td><i class='fa fa-pencil gray edit-store'></i><i data-id='"+data.id+"' class='fa fa-check pink save-store sr-only'></i></td></tr>");
                    $('[data-toggle="tooltip"]').tooltip();
                } else if (data.success == 'dialog') {
                    BootstrapDialog.show({
                        message: data.messageDialog,
                        buttons: [{
                            label: 'Hủy bỏ',
                            action: function(){
                                $('.bootstrap-dialog-close-button').find('button').click();

                                var id = data.id;
                            
                                $.ajax(
                                {
                                    url: 'destroy-store/',
                                    type: 'DELETE',
                                    dataType: "JSON",
                                    data: {
                                        "id": id
                                    },
                                    success: function (data)
                                    {
                                        
                                    },
                                    error : function (xhr){
                                        $.endload();
                                        var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                                        $(location).attr('href', host);
                                    },
                                });

                            }
                        }, {
                            label: 'Đồng ý',
                            cssClass: 'btn-pink',
                            action: function(dialogItself){

                                $(".create-shop-address .table").append("<tr><td><label>"+data.allstore.name+"</label><input type='text' name='' value='"+data.allstore.name+"' class='form-control sr-only'></td><td><label>"+data.allstore.address+"</label><input type='text' name='' value='"+data.allstore.address+"' class='form-control sr-only'></td><td><i class='fa fa-pencil gray edit-name-store' data-toggle='tooltip' title='Sửa thông tin'></i><i data-id='"+data.id+"' class='fa fa-check pink save-name-store sr-only' data-toggle='tooltip' title='Lưu thay đổi thông tin cửa hàng'></i><i data-id='"+data.id+"' class='fa fa-trash  destroy-store' data-toggle='tooltip' title='Xóa cửa hàng (Đồng nghĩa với xóa tài khoản thu ngân)'></i><i data-id='"+data.id+"' class='fa fa-play pink active-store' data-toggle='tooltip' title='Kích hoạt cửa hàng'></i></td><td><span class='pink'>Đang tạm dừng</span></td></tr>");
                                $.toaster({ priority : data.priority, message : data.messages });
                                $('#name-shop').val('');
                                $('#address-shop').val('');
                                $("#table-create-account-shop").append("<tr><td>" + getNameShop + "</td><td>" + getAddressShop + "</td><td><center><i class='fa fa-pencil gray'></i></a><i class='fa fa-trash pink'></i></center></td></tr>");
                                $(".table-create-account-shop").append("<tr><td><h4 class='pink'>"+getNameShop+"</h4></td><td><label><span class='pink'>(Tên đăng nhập)</span></label><input type='text' name='' value='' class='form-control sr-only'></td><td><label><span class='pink'>(Mật khẩu đăng nhập)</span></label><input type='password' name='' value='' class='form-control sr-only'></td><td><i class='fa fa-pencil gray edit-store'></i><i data-id='"+data.id+"' class='fa fa-check pink save-store sr-only'></i></td></tr>");
                                $('[data-toggle="tooltip"]').tooltip();
                                dialogItself.close();
                            }
                        }]
                    });
                } else {
                    $.toaster({ priority : data.priority, message : data.messages });
                }
            },
            error : function (xhr){
                $.endload();
                var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                $(location).attr('href', host);
            },
        })
    });


    $("#initialize-store-shop").click(function(e){
        e.preventDefault();
        var getNameShop = $('#name-shop').val();
        var getAddressShop = $('#address-shop').val();
        

        $.ajax({
            url : 'initialize/initializeaddshop',
            method : 'post',
            data : {
                name : getNameShop,
                address : getAddressShop
            },
            success : function (data) {
                if (data.success == true) {
                    $(".create-shop-address .table").append("<tr><td>" + data.allstore.name + "</td><td>" + data.allstore.address + "</td><td><center><i class='fa fa-pencil gray'></i></a><i data-id='" + data.id + "' class='fa fa-trash pink destroy-store'></i></center></td></tr>");
                    $('#name-shop').val('');
                    $('#address-shop').val('');
                    $.toaster({ priority : data.priority, message : data.messages });
                    window.setTimeout(function(){location.reload()},3000)
                } else {
                    $.toaster({ priority : data.priority, message : data.messages });
                }
            },
            error : function (xhr){
                $.endload();
                var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                $(location).attr('href', host);
            },
        })
    });

    $(".store-account").click(function(e){
            e.preventDefault();
            var id = $(this).data("id");
            var valueUsername = '#username-shop'+'-'+id;
            var valuePassword = '#password-shop'+'-'+id;
            var getUsername = $(valueUsername).val();
            var getPassword = $(valuePassword).val();
            $.confirm({
                theme: 'supervan',
                title: 'Cấu hình tài khoản thu ngân',
                confirmButtonClass: 'btn-info',
                cancelButtonClass: 'btn-danger',
                content: 'Bạn có chắc chắn muốn thay đổi ?',
                confirm: function(){
                 

                    $.ajax({
                        url : 'initialize/storenewaccount',
                        method : 'post',
                        data : {
                            id : id,
                            username : getUsername,
                            password : getPassword
                        },
                        success : function (data) {
                            if (data.success == true) {
                                $.toaster({ priority : data.priority, message : data.messages });
                            } else {
                                $.toaster({ priority : data.priority, message : data.messages });
                            }
                        },
                        error : function (xhr){
                            $.endload();
                            var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                            $(location).attr('href', host);
                        },
                    })
                    
                },
                cancel: function(){
                    
                }
            });
        });

$(function() {

    //init
    var provinces_id = $('#province').val();
    var exist_district = $('#exist_district').val();

    if (provinces_id != -1) {
        $.get(window.location.protocol+"//"+window.location.hostname+'/merchant/district?provinces_id='+provinces_id,function (data) {
                $('#district').empty();
                $('#district').append('<option value="-1">- Quận/Huyện -</option>');
                $.each(data, function(index, product){
                $('#district').append('<option value="'+product.districts_id+'"'+ (product.districts_id == exist_district? "selected":"")  +'>'+product.name+'</option>');
            });
         });
    }
    
    //get district
    $('#province').on('change',function(e) {
        var provinces_id= e.target.value;
        //ajax
         $.get(window.location.protocol+"//"+window.location.hostname+'/merchant/district?provinces_id='+provinces_id,function(data){

            $('#district').empty();

            if(provinces_id == -1)
            {
                $('#district').append('<option value="-1">-- Quận/Huyện --</option>');
            }
            else
            {
                $('#district').append('<option value="-1">-- Quận/Huyện --</option>');
                $.each(data, function(index, product){
                    $('#district').append('<option value="'+product.districts_id+'">'+product.name+'</option>');
                });
            }
           
         });
    });


    //option 1
    var count1 = 0; 
    $('#create-option-gift-1').click(function(e) {

        e.preventDefault();
        var lengthChop = parseInt( $( "#select-chop-gift-1 option:selected" ).val() );
        var valueDiscount = $('#value-discount-gift-1').val();
        var valueChop = $('#value-discount').autoNumeric('get');

        if ( lengthChop == "" ) {
            $.toaster({ priority : 'danger', message : 'Vui lòng chọn số lượng Chops cần tích lũy' });
        } else if ( lengthChop == 0 ) {
            $.toaster({ priority : 'danger', message : 'Giá trị hóa đơn tương ứng với một Chops phải lớn hơn 0' });
        } else if ( valueDiscount == "" ) {
            $.toaster({ priority : 'danger', message : 'Vui lòng chọn số tiền tương đương với 1 sản phảm' });
        } else if ( valueChop == "" ) {
            $.toaster({ priority : 'danger', message : 'Giá trị thanh toán không được phép để trống' });
        } else if ( lengthChop >= 1 && lengthChop <= 15 ) {
            count1= count1 +1;
            $('#value-discount-1').val($('#value-discount').autoNumeric('get'));
            if (count1 == 1 ) {
                $('#select-chop-gift-1-1').val($( "#select-chop-gift-1 option:selected" ).val());
                $('#value-discount-gift-1-1').val($('#value-discount-gift-1').autoNumeric('get'));
            } else if (count1 == 2)
            {
                $('#select-chop-gift-1-2').val($( "#select-chop-gift-1 option:selected" ).val());
                $('#value-discount-gift-1-2').val($('#value-discount-gift-1').autoNumeric('get'));
            }
            else if (count1 == 3) {
                $('#select-chop-gift-1-3').val($( "#select-chop-gift-1 option:selected" ).val());
                $('#value-discount-gift-1-3').val($('#value-discount-gift-1').autoNumeric('get'));
            }
            else {
                $.toaster({ priority : 'danger', message : 'Bạn chỉ được tạo tối đa 3 mức tích lũy Chops ' });
                return;
            }
            
            $('#list-gift-1').append("<tr class='add-new-config-chop'><td colspan='2'>Tích đủ <span class='pink'>"+lengthChop+" Chops</span>. Tặng SP trị giá <span class='pink'>"+valueDiscount+" vnđ</span></td></tr>");
            $( "#select-chop-gift-1 option:selected" ).attr('disabled','disabled');
            $("#select-chop-gift-1 option:first").attr('selected','selected');
            $('#value-discount-gift-1').val('');

            //Show input reset
            $('.destroy-create-chop').show();
            $('.box-show-chop').find('i').remove();

        } else {
            $.toaster({ priority : 'danger', message : 'Có lỗi xảy ra. Vui lòng thử lại' });
        }
    });

    //Option 2
    var count2 = 0;
    $('#create-option-gift-2').click(function(e) {
        e.preventDefault();
        var lengthChop2 = parseInt( $( "#select-chop-gift-2 option:selected" ).val() );
        var valueDiscount2 = $('#value-discount-gift-2').val();
        var valueChop1 = $('#value-discount-2').val();

        // alert('xxxx');
        if ( lengthChop2 == "" ) {
            $.toaster({ priority : 'danger', message : 'Vui lòng chọn số lượng Chops cần tích lũy' });
        } else if (lengthChop2 == 0) {
            $.toaster({ priority : 'danger', message : 'Giá trị hóa đơn tương ứng với một Chops phải lớn hơn 0' });
        } else if ( valueDiscount2 == "" ) {
            $.toaster({ priority : 'danger', message : 'Vui lòng chọn % giảm giá cho lần tiếp theo' });
        } else if( valueDiscount2 <= 0 || valueDiscount2 > 100 ) {
            $.toaster({ priority : 'danger', message : 'Phần trăm giảm giá từ 1 ~ 100. Vui lòng nhập lại' });
        } else if ( valueChop1 == "" ) {
            $.toaster({ priority : 'danger', message : 'Giá trị thanh toán không được phép để trống' });
        } else if ( lengthChop2 >= 1 && lengthChop2 <= 15 ) {

            count2= count2 +1;
            $('#value-discount-2-1').val($('#value-discount-2').val());
            if (count2 == 1 ) {
                $('#select-chop-gift-2-1').val($( "#select-chop-gift-2 option:selected" ).val());
                $('#value-discount-gift-2-1').val($('#value-discount-gift-2').autoNumeric('get'));
            } else if (count2 == 2)
            {
                $('#select-chop-gift-2-2').val($( "#select-chop-gift-2 option:selected" ).val());
                $('#value-discount-gift-2-2').val($('#value-discount-gift-2').autoNumeric('get'));
            }
            else if (count2 == 3) {
                $('#select-chop-gift-2-3').val($( "#select-chop-gift-2 option:selected" ).val());
                $('#value-discount-gift-2-3').val($('#value-discount-gift-2').autoNumeric('get'));
            }
            else {
                $.toaster({ priority : 'danger', message : 'Bạn chỉ được tạo tối đa 3 mức tích lũy Chops' });
                return;
            }
            
            $('#list-gift-2').append("<tr class='add-new-config-chop-2'><td colspan='2'>Tích đủ <span class='pink'>"+lengthChop2+" Chops</span>. Giảm giá <span class='pink'>"+valueDiscount2+" %</span> cho lần mua tiếp</td></tr>");

            $( "#select-chop-gift-2 option:selected" ).attr('disabled','disabled');
            $("#select-chop-gift-2 option:first").attr('selected','selected');
            $('#value-discount-gift-2').val('');

            //Show input reset
            $('.destroy-create-chop-2').show();
            $('.box-show-chop').find('i').remove();


        } else {
            $.toaster({ priority : 'danger', message : 'Có lỗi xảy ra. Vui lòng thử lại' });
        }
    });

    $(document).on('click','.destroy-create-chop',function(){
        var __this = $(this);
        count1 = 0;

        $.confirm({
            theme: 'supervan',
            title: 'KHỞI TẠO LẠI',
            confirmButtonClass: 'btn-info',
            cancelButtonClass: 'btn-danger',
            content: 'Bạn có chắc chắn muốn quy định lại toàn bộ số Chops cần tích lũy & giá trị sản phẩm được đổi?',
            confirm: function(){
                $('#select-chop-gift-1-1').val('');
                $('#select-chop-gift-1-2').val('');
                $('#select-chop-gift-1-3').val('');

                $('#value-discount-gift-1-1').val('');
                $('#value-discount-gift-1-2').val('');
                $('#value-discount-gift-1-3').val('');

                $('.add-new-config-chop').remove();
                $('#select-chop-gift-1').find('option').removeAttr('disabled');

                __this.hide();
                $('.box-show-chop').find('i').remove();
            },
            cancel: function(){
            }
        });
    });


    $(document).on('click','.destroy-create-chop-2',function(){
        var __this = $(this);
        count2 = 0;

        $.confirm({
            theme: 'supervan',
            title: 'KHỞI TẠO LẠI',
            confirmButtonClass: 'btn-info',
            cancelButtonClass: 'btn-danger',
            content: 'Bạn có chắc chắn muốn quy định lại toàn bộ số Chops cần tích lũy và giá trị sản phẩm được đổi?',
            confirm: function(){
                $('#select-chop-gift-2-1').val('');
                $('#select-chop-gift-2-2').val('');
                $('#select-chop-gift-2-3').val('');

                $('#value-discount-gift-2-1').val('');
                $('#value-discount-gift-2-2').val('');
                $('#value-discount-gift-2-3').val('');

                $('.add-new-config-chop-2').remove();
                $('#select-chop-gift-2').find('option').removeAttr('disabled');

                __this.hide();
                $('.box-show-chop').find('i').remove();
            },
            cancel: function(){
            }
        });
    });

    



});



$('.value-config-store').click(function(e){
    e.preventDefault();
    var id1 = 13;
    var id2 = 14;
    var point1 = $('#point-1').val();
    var point2 = $('#point-2').val();

    var bonus1 = $('#bonus-point-1').val();
    var bonus2 = $('#bonus-point-2').val();

    var value = 60;

    $.ajax({
        url : 'initialize/store-level2',
        method : 'post',
        data : {

            id1 : id1,
            point1 : point1,
            bonus1 : bonus1,

            id2 : id2,
            point2 : point2,
            bonus2 : bonus2,

            value : value,
        },
        success : function(data) {
            
        },
        error : function (xhr){
            $.endload();
            var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
            $(location).attr('href', host);
        },
    });
});


$('.value-config-stores').click(function(e){
    e.preventDefault();

    var id1 = 10;
    var point1 = $('#point-3-1').val();
    var bonus1 = $('#bonus-point-3-1').val();

    var id2 = 11;
    var point2 = $('#point-3-2').val();
    var bonus2 = $('#bonus-point-3-2').val();

    var id3 = 12;
    var point3 = $('#point-3-3').val();
    var bonus3 = $('#bonus-point-3-3').val();

    var value = 50;

    $.ajax({
        url : 'initialize/store-level3',
        method : 'post',
        data : {

            id1 : id1,
            point1 : point1,
            bonus1 : bonus1,

            id2 : id2,
            point2 : point2,
            bonus2 : bonus2,

            id3 : id3,
            point3 : point3,
            bonus3 : bonus3,

            value : value,
        },
        success : function(data) {
            
        },
        error : function (xhr){
            $.endload();
            var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
            $(location).attr('href', host);
        },
    });
});

//Show example
    

    $(document).on('click','.ex-value tr input',function() {
        var levelEx = $( this ).closest( "tr" ).find( "h4" ).text();
        var pointEx = $( this ).closest( "tr" ).find( "input" ).first().val();
        var valueEx = $( this ).closest( "tr" ).find( "input" ).last().val();
        $('.show-ex').show(300);
        $('#point-ex').text(pointEx);
        $('#level-ex').text(levelEx);
        $('#value-ex').text(valueEx);
    });


$('#value-discount-level').keyup(function() {
    $('#value-discount-hidend').text($(this).autoNumeric('get'));
})


//2 event
$(".ex-value tr input").on({
    focus:function(){  //see above
        // Does some stuff and logs the event to the console
        var setPointEx = $( this ).closest( "tr" ).find( "input" ).first().autoNumeric('get');
        var setValueEx = $( this ).closest( "tr" ).find( "input" ).last().autoNumeric('get');


        if (setValueEx == "") {
            $('#point-ex').text('...');
            $('#level-ex').text('...');
            $('#value-ex').text('...');
        } else {
            var defaultSetPoint = $('#value-discount-level').autoNumeric('get');
            var vndValue = defaultSetPoint * setPointEx;

            $('#point-ex').text(setPointEx);
            $('#value-ex').text(setValueEx);
            
            if (vndValue == 0) {
                $('#vnd-ex').text('...');
            } else {
                $('#vnd-ex').text(vndValue);
            }
        }
    },
    keyup:function(){
        var setPointEx = $( this ).closest( "tr" ).find( "input" ).first().autoNumeric('get');
        var setValueEx = $( this ).closest( "tr" ).find( "input" ).last().autoNumeric('get');

        var defaultSetPoint = ($('#value-discount-hidend').text());
        var vndValue = defaultSetPoint * setPointEx;
            $('#point-ex').text(setPointEx);
            $('#value-ex').text(setValueEx);
            $('#vnd-ex').text(vndValue);
    }
});
   

$('.ex-value').focusout(function(){
    $('.show-ex').hide(300);
})


$('#background-color').change(function(){
   $(this).addClass('changed');
   $('#choice-color').val($(this).val());
})

$("#reset-setting-color").click(function () {
    $("#background-color").val("#f94876");
    $('#choice-color').val("#f94876");
});


$('#choice-color').keyup(function() {
    $('#background-color').val($(this).val());
})

// });
















