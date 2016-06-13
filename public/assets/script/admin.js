$(document).ready(function() {
    var path = $('#root').val();
    var timeout = 200;
    $('input[type=radio][name=bedStatus]').change(function() {
        if (this.value == 'infomation') {
            $('.edit-infomation').show(timeout);
            $('.edit-card').hide(timeout);
            $('.edit-store').hide(timeout);
        }
        else if (this.value == 'store') {
            $('.edit-store').show(timeout);
            $('.edit-infomation').hide(timeout);
            $('.edit-card').hide(timeout);
        }
        else if (this.value == 'card') {
            $('.edit-card').show(timeout);
            $('.edit-infomation').hide(timeout);
            $('.edit-store').hide(timeout);
        }
    });

// FOR INDEX

    var count1 = 0; 
    $('#create-option-gift-1').click(function(e) {

        e.preventDefault();
        var lengthChop = $( "#select-chop-gift-1 option:selected" ).val();
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
        } else {
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
            
            $('#list-gift-1').append("<tr class='add-new-config-chop'><td colspan='2'>Tích đủ <span class='pink'>"+lengthChop+" Chops</span>. Tặng SP trị giá <span class='pink'>"+valueDiscount+" vnđ</span></td><td><i class='fa fa-trash pink destroy-create-chop'></i></td></tr>");
            $('#select-chop-gift-1 option[value=""]').attr('selected','selected');
            $('#value-discount-gift-1').val('');
        }
    });

    //Option 2
    var count2 = 0;
    $('#create-option-gift-2').click(function(e) {
        e.preventDefault();
        var lengthChop2 = $( "#select-chop-gift-2 option:selected" ).val();
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
        } else {

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
            
            $('#list-gift-2').append("<tr class='add-new-config-chop-2'><td colspan='2'>Tích đủ <span class='pink'>"+lengthChop2+" Chops</span>. Giảm giá <span class='pink'>"+valueDiscount2+" %</span> cho lần mua tiếp</td><td><i class='fa fa-trash pink destroy-create-chop-2'></i></td></tr>");
            $('#select-chop-gift-2 option[value=""]').attr('selected','selected');
            $('#value-discount-gift-2').val('');
        }
    });

    //Edit Vang Bac Dong

    $(document).on('click' , '.edit-card-info-admin', function (event) {

        //Card tyoe == 1 / Vàng bạc đồng;
                //  == 2 / VVIP VIP Tahnfh viên

        var merchantId = $(this).data('merchant-id');
        var cardType = $(this).data('card-type');
        var unit = $( "#value-discount-level" ).autoNumeric('get');

        var settings = [];

        if (cardType == 1) {
            //Xu ly luu vang bac dong

            var vang1 = $( "#point-3-1" ).autoNumeric('get');
            var vang2 = $( "#bonus-point-3-1" ).autoNumeric('get');
            var vang = [];
            vang.push(vang1);
            vang.push(vang2);

            var bac1 = $( "#point-3-2" ).autoNumeric('get');
            var bac2 = $( "#bonus-point-3-2" ).autoNumeric('get');
            var bac = [];
            bac.push(bac1);
            bac.push(bac2);

            var dong1 = $( "#point-3-3" ).autoNumeric('get');
            var dong2 = $( "#bonus-point-3-3" ).autoNumeric('get');
            var dong = [];
            dong.push(dong1);
            dong.push(dong2);

            settings.push(vang);
            settings.push(bac);
            settings.push(dong);


        }else if (cardType == 2) {

            var vip1 = $( "#point-1" ).autoNumeric('get');
            var vip2 = $( "#bonus-point-1" ).autoNumeric('get');
            var vip =[];
            vip.push(vip1);
            vip.push(vip2);

            var thanhvien1 = $( "#point-2" ).autoNumeric('get');
            var thanhvien2 = $( "#bonus-point-2" ).autoNumeric('get');
            var thanhvien =[];
            thanhvien.push(thanhvien1);
            thanhvien.push(thanhvien2);

            settings.push(vip);
            settings.push(thanhvien);
        } else if (cardType == 3) {
            //VVIP VIP Thanh viên
            var vvip1 = $( "#point-3-3-1" ).autoNumeric('get');
            var vvip2 = $( "#bonus-point-3-3-1" ).autoNumeric('get');
            var vvip =[];
            vvip.push(vvip1);
            vvip.push(vvip2);

            var vip1 = $( "#point-3-3-2" ).autoNumeric('get');
            var vip2 = $( "#bonus-point-3-3-2" ).autoNumeric('get');
            var vip =[];
            vip.push(vip1);
            vip.push(vip2);

            var thanhvien1 = $( "#point-3-3-3" ).autoNumeric('get');
            var thanhvien2 = $( "#bonus-point-3-3-3" ).autoNumeric('get');
            var thanhvien =[];
            thanhvien.push(thanhvien1);
            thanhvien.push(thanhvien2);

            settings.push(vvip);
            settings.push(vip);
            settings.push(thanhvien);

        } 




        // Call Ajax
        $.ajax({
            url: path +"admincp/change-card-info",
            method:'post',
            data : {
                merchantId: merchantId,
                cardtype: cardType,
                settings:settings,
                unit: unit
            },
            success: function(data){
                $.endload();
                // $.toaster({ priority : data.priority, message : data.messages });
            },
            error : function (xhr){
                $.endload();
                // var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                // $(location).attr('href', host);
            },
        });

    });

    $(document).on('click' , '.edit-card-info-admin-chop' , function (e) {
        e.preventDefault();

        var merchantId = $(this).data('merchant-id');
        var cardType = $(this).data('card-type');
        var unit = $( "#value-discount" ).autoNumeric('get');

        if (cardType == 4) {

        } else if (cardType == 5) {

            
        }
    }) 


$(document).on('click' , '#save-edit-infomerchant' , function (e) {
    e.preventDefault();
    var id = $(this).data('id');

    $.confirm({
        theme: 'supervan',
        title: 'THAY ĐỔI THÔNG TIN MERCHANT',
        confirmButtonClass: 'btn-info',
        cancelButtonClass: 'btn-danger',
        content:"Bạn có chắc chắn muốn thay đổi thông tin Merchant",
        confirm: function(){
            $.loadding();
            $.ajax({
                url: window.location.protocol+"//"+window.location.hostname+"/admincp/save-edit-infomation-merchant",
                method:'post',
                data : {
                    id:id,
                    trademark: $("input[name='trademark']").val(),
                    field: $("select[name='field']").val(),

                    logo : $("#edit-image_logo")[0].files,
                    
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
                    $.endload();
                    $.toaster({ priority : data.priority, message : data.messages });
                },
                error : function (xhr){
                    $.endload();
                    // var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                    // $(location).attr('href', host);
                },
            });
        },
        cancel: function(){
        },
    });

    
});

    // //Paginate Index Merchant
    // $(document).on('click', '.content-merchant .pagination a' , function(e) {
    //     e.preventDefault();
    //     var keyword = $.trim($('#searchIndexAdmin').val());
    //     var page = $(this).attr('href').split('page=')[1];
    //     $.loadding();
    //     $.ajax({
    //         url : '/ajax/page-merchant?page='+ page +'&keyword='+keyword
    //     }).done(function(data) {
    //         $.endload();
    //         $('.content-merchant').html(data);
    //     });
    // });
    // //Search & paginate only Partner
    // $('#btnSearchAdminPartner').click(function(){
    //     var keyword = $.trim($('#searchAdminPartner').val());
    //     $.loadding();
    //         $.ajax({
    //             url : '/ajax/search-partner?keyword=' + keyword
    //         }).done(function(data) {
    //             $.endload();
    //             $('.content-merchant').html(data);
    //         }).error(function(xhr){
    //             $.endload();
    //             var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
    //             $(location).attr('href', host);
    //         });
    // })
    
// FOR PARTNER
    //Paginate Only Partner

    

    $("#searchIndexAdmin").change(function(e){
        e.preventDefault();
        if($("#searchIndexAdmin").val() == "") {
            $("#divListSearchResultAdmin").empty();
            $("#divListAllMerchantAdmin").removeClass("hide");
        }
        filterMerchantPage(0);
    });

    function filterMerchantPage(page){
        
        
        var search_box = $.trim($("#searchIndexAdmin").val());

        
        $.loadding();
        $.ajax({
            url: 'admincp/filter-merchant',
            // url: 'filter-account',
            method:'post',
            data : {
                
                "search_box" : search_box,
                "page" : page
            },
        
            success: function(data){
                $.endload();
                $("#divListAllMerchantAdmin").addClass("hide");
                // $('#account_paginator').empty();
                
                $("#divListSearchResultAdmin").removeClass("hide");
                $("#divListSearchResultAdmin").empty();
                $("#divListSearchResultAdmin").append(data.result);              
            },
            error: function(){},
        });
    }

    $(document).on('click', '.content-partner .pagination a' , function(e) {
        e.preventDefault();
        var keyword = $.trim($('#searchAdminPartner').val());
        var page = $(this).attr('href').split('page=')[1];
        $.loadding();
        $.ajax({
            url : '/ajax/page-partner?page='+ page +'&keyword='+keyword
        }).done(function(data) {
            $.endload();
            $('.content-partner').html(data);
        });
    });
    //Search & paginate only Partner
    $('#btnSearchAdminPartner').click(function(){
        var keyword = $.trim($('#searchAdminPartner').val());
        $.loadding();
            $.ajax({
                url : '/ajax/search-partner?keyword=' + keyword
            }).done(function(data) {
                $.endload();
                $('.content-partner').html(data);
            }).error(function(xhr){
                $.endload();
                var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                $(location).attr('href', host);
            });
    })



    /**
     * FOR NEW MERCHANT
     **************************
     */

    // Paginate new Merchant
    $(document).on('click', '.content-new-merchant .pagination a' , function(e) {
        e.preventDefault();
        var keyword = $.trim($('#searchAdminNewMerchant').val());
        var page = $(this).attr('href').split('page=')[1];
        $.loadding();
        $.ajax({
            url : '/ajax/page-new-partner?page='+ page +'&keyword='+keyword
        }).done(function(data) {
            $.endload();
            $('.content-new-merchant').html(data);
        });
    });

    //Search & paginate only Partner
    $('#btnSearchAdminNewMerchant').click(function(){
        var keyword = $.trim($('#searchAdminNewMerchant').val());
        $.loadding();
            $.ajax({
                url : '/ajax/search-new-merchant?keyword=' + keyword
            }).done(function(data) {
                $.endload();
                $('.content-new-merchant').html(data);
            }).error(function(xhr){
                $.endload();
                var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                $(location).attr('href', host);
            });
    })

// Paginate Boo Merchant

    // Paginate new Merchant
    $(document).on('click', '.content-boo-merchant .pagination a' , function(e) {
        e.preventDefault();
        var keyword = $.trim($('#searchAdminBooMerchant').val());
        var page = $(this).attr('href').split('page=')[1];
        $.loadding();
        $.ajax({
            url : '/ajax/page-boo-partner?page='+ page +'&keyword='+keyword
        }).done(function(data) {
            $.endload();
            $('.content-boo-merchant').html(data);
        });
    });
    //Search & paginate only Boo Merchant
    $('#btnSearchAdminBooMerchant').click(function(){
        var keyword = $.trim($('#searchAdminBooMerchant').val());
        $.loadding();
            $.ajax({
                url : '/ajax/search-boo-merchant?keyword=' + keyword
            }).done(function(data) {
                $.endload();
                $('.content-boo-merchant').html(data);
            }).error(function(xhr){
                $.endload();
                var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                $(location).attr('href', host);
            });
    })




    
    /**
     * Search All Merchant
     */
    $('#searchAdminPartner').keypress(function(e){
        if(e.which == 13){//Enter key pressed
            $('#btnSearchAdminPartner').click();//Trigger search button click event
        }
    });

    $('#searchAdminNewMerchant').keypress(function(e){
        if(e.which == 13){//Enter key pressed
            $('#btnSearchAdminNewMerchant').click();//Trigger search button click event
        }
    });

    $('#searchAdminBooMerchant').keypress(function(e){
        if(e.which == 13){//Enter key pressed
            $('#btnSearchAdminBooMerchant').click();//Trigger search button click event
        }
    });
    // END SEARCH
    $("#searchAdmin").keyup(function(){
        _this = this;
        // Show only matching TR, hide rest of them
        $.each($("#listAllMerchant tbody").find("tr"), function() {
            if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) == -1)
                $(this).hide('swing');
            else
                $(this).show('swing');                
        });
    }); 
    $(".btn-admin-task").click(function(e) {
        e.preventDefault();
        $('#quickActionByAdmin').modal('show');
        var user_id = $(this).attr("data-user-id");
        var user_status = $(this).attr("data-user-status");
        var user_month = $(this).attr("data-user-package");
        var user_package = $(this).attr("data-user-package");
        var start_day = $(this).attr("data-start-day").split(" ");
        var end_day = $(this).attr("data-end-day").split(" ");
        $("#formUserId").attr("data-user-id",user_id);
        $("#changeStatus").val(user_status);
        $("#changePackage").val(user_package);
        $("#customer-start-date").val(start_day[0]);
        $("#customer-end-date").val(end_day[0]);
    });

    //Xu ly thay doi ngay thang trong chinh sua admincp
    Date.prototype.yyyymmdd = function() {
        var yyyy = this.getFullYear().toString();
        var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
        var dd  = this.getDate().toString();
        return yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]); // padding
    };

    Date.prototype.addMonths = function (value) {
        var n = this.getDate();
        this.setDate(1);
        this.setMonth(this.getMonth() + value);
        this.setDate(Math.min(n, this.getDaysInMonth()));
        return this;
    };


    $("#monthPackage").change(function(){
        d = new Date();
        $("#customer-start-date").val(d.yyyymmdd());
        var mounth = $('option:selected',$("#monthPackage")).val();

        d = new Date();
        end_d = d.addMonths(parseInt(mounth));


        $("#customer-end-date").val(end_d.yyyymmdd());

        
    });
    $("#monthPackageSearch").change(function(){
        d = new Date();
        $("#customer-start-date-search").val(d.yyyymmdd());
        var mounth = $('option:selected',$("#monthPackageSearch")).val();

        d = new Date();
        end_d = d.addMonths(parseInt(mounth));


        $("#customer-end-date-search").val(end_d.yyyymmdd());
    });
    //Xu ly submit form cap nhat thong tin
    $("#btn-admin-update-info").click(function(e) {
        e.preventDefault();
        var form = $("#formAdminTaskForm");
        form.validate();

        if (form.valid()) {

            var package_id = $('option:selected',$("#changePackage")).attr("data-package-id");
            var active_id = $('option:selected',$("#changeStatus")).attr("data-user-status");
            var mounth = $('option:selected',$("#monthPackage")).val();
            var start_day = $("#customer-start-date").val();
            var end_day = $("#customer-end-date").val();

            var formData = new FormData();
            formData.append("id",$("#formUserId").attr("data-user-id"));
            formData.append("active",active_id);
            formData.append("mounth",mounth);
            formData.append("package",package_id);
            formData.append("end_day",end_day);
            formData.append("start_day",start_day);

            $.loadding();
            $.ajax({

                url: 'admincp/update-info',
                processData : false,
                cache: false,
                contentType: false,
                method:'post',
                data : formData,
                success: function(data){
                    $.endload();
                    if (data.success == true) {
                        $.toaster({ priority : data.priority, message : data.messages });
                        setTimeout(function(){
                            location.reload();
                        }, 1000);
                    } else  {
                        BootstrapDialog.show({
                            title: data.title,
                            message: data.messages,
                        });
                    }
                },
                error: function(){},
            });
        }
    });
    $(".btn-admin-merchant-view-detail").click(function(){

        var email = $(this).attr('data-merchant-email'),
            name = $(this).attr('data-merchant-name'),
            address = $(this).attr('data-merchant-address'),
            field = $(this).attr('data-merchant-field'),
            info = $(this).attr('data-merchant-info'),
            role = $(this).attr('data-merchant-role'),
            birthday = $(this).attr('data-merchant-birthday'),
            phone = $(this).attr('data-merchant-phone'),
            card_type = $(this).attr('data-merchant-card-type'),
            model = $(this).attr("data-merchant-model");
        if(card_type == 3) {
            $("#detail-merchant-cardtype").html('Level');
            $("#detail-merchant-model").html("Thẻ thành viên "+model);
        } else if(card_type == 4) {
            $("#detail-merchant-cardtype").html('Chops');
            $("#detail-merchant-model").html(model);
        }
        $("#detail-merchant-title").html(name);
        $("#detail-merchant-name").html(name);
        $("#detail-merchant-field").html(field);
        $("#detail-merchant-info").html(info);
        $("#detail-merchant-role").html(role);
        $("#detail-merchant-birthday").html(birthday);
        $("#detail-merchant-address").html(address);
        $("#detail-merchant-phone").html(phone);
        $("#detail-merchant-email").html(email);
    });
    $(".admin-package-status").click(function(e) {
        e.preventDefault();
        var package = $(this).attr("data-package");
        var package_status = $(this).attr("data-package-id");
        var package_name = $(this).attr("data-package-name");
        var user_id = $(this).attr("data-user-id");
        $.confirm({
            theme: 'supervan',
            title: 'NÂNG CẤP MERCHANT',
            confirmButtonClass: 'btn-info',
            cancelButtonClass: 'btn-danger',
            content:"'Bạn có chắc chắn muốn nâng cấp tài khoản lên <p style='text-transform: uppercase; font-size: 30px;'>"+package_name+"</p>",
            confirm: function(){
                    $.loadding();
                    $.ajax({
                        url: 'admincp/update-package',                
                        method:'post',
                        data : {
                            "package" : package,
                            "package_status" : package_status,
                            "user_id" : user_id,
                        },
                        success: function(data){
                            $.endload();
                            if (data.success == true) {
                                $.toaster({ priority : data.priority, message : data.messages });
                                setTimeout(function(){
                                    location.reload();
                                }, 1000);
                            } else {
                                BootstrapDialog.show({
                                    title: data.title,
                                    message: data.messages,
                                });
                            }
                        },
                        error: function(){
                        },
                    });
            },
            cancel: function(){
            },
        });
    });
    $(".btn-verify").click(function(e) {
        e.preventDefault();
        var merchant_id = $(this).attr("data-merchant-id");
        var formData = new FormData();
        formData.append("id",merchant_id);
        formData.append("active",1);

        $.confirm({
            theme: 'supervan',
            title: 'XÁC NHẬN MERCHANT',
            confirmButtonClass: 'btn-info',
            cancelButtonClass: 'btn-danger',
            content:"'Bạn có chắc chắn muốn xác nhận tài khoản không?",
            confirm: function(){

                    // console.log(id)
                    $.loadding();
                    $.ajax({
                        url: 'admincp/active-status',
                        processData : false,
                        cache: false,
                        contentType: false,
                        method:'post',
                        data : formData,
                        success: function(data){
                            $.endload();
                            $.toaster({ priority : data.priority, message : data.messages });
                            setTimeout(function(){
                                location.reload();
                            }, 1000);
                        },
                        error: function(){
                            $.endload();
                            var host = window.location.protocol+"//"+window.location.hostname+"/"+xhr.status;
                            $(location).attr('href', host);
                        },
                    });
            },
            cancel: function(){
            },
        });
    });
    //Validate Form
    $.validator.addMethod("checkCurrentCustomerDate", function(value, element) {
        var myDate = value;
        var today = new Date();
        var day = today.getDate();
        var mon = today.getMonth()+1;
        var year = today.getFullYear();
        today = (mon+"/"+day+"/"+year);
        //alert(today);
        var currentDate = new Date(today);
      
        //Any code that will return TRUE or FALSE
        if (Date.parse(myDate) < currentDate){
            return false;
        }else{
            return true;
        }
    },"Thời gian này không được ở trong quá khứ");
    $.validator.addMethod("checkStartCustomerDate", function(value, element) {
        var myDate = value;
        var endDate = $("#customer-send-date").val();
      
        //Any code that will return TRUE or FALSE
        if ((endDate != "") && (myDate > endDate)){
            return false;
        }else{
            return true;
        }
    },"Thời gian bắt đầu không được lớn hơn thời gian kết thúc");
    $.validator.addMethod("checkEndCustomerDate", function(value, element) {
        var myDate = value;
        var startDate = $("#customer-start-date").val();
 
        //Any code that will return TRUE or FALSE
        if ((startDate != "") && (myDate < startDate)){
            return false;
        }else{
            return true;
        }
    },"Thời gian kết thúc không được nhỏ hơn thời gian bắt đầu");
    $("#customer-start-date").datetimepicker();
    $("#customer-start-date").change(function() {
        var dateString = $("#customer-start-date").val(),
            dateParts = dateString.split(' '),
            timeParts = dateParts[1].split(':');
            dateParts = dateParts[0].split('/');
        date = new Date(dateParts[0], dateParts[1], dateParts[2], timeParts[0], timeParts[1], 0, 0).getTime();
     
        if($("#customer-end-date").val()){
            $("#customer-end-date").valid();
        }
    });
    $("#customer-end-date").datetimepicker();
    $("#customer-end-date").change(function() {
        var dateString = $("#customer-end-date").val(),
            dateParts = dateString.split(' '),
            timeParts = dateParts[1].split(':');
            dateParts = dateParts[0].split('/');
        date = new Date(dateParts[0], dateParts[1], dateParts[2], timeParts[0], timeParts[1], 0, 0).getTime();
        
        if($("#customer-start-date").val()){
            $("#customer-start-date").valid();
        }
    });
    $("#formAdminTaskForm").validate({
        rules: {
            "customer-start-date" : {
                required : true,
                // optdate : true,
            },
            "customer-end-date" : {
                required : true,
                // min : true,
            }
        },
        messages: {
            "customer-start-date" : {
                required : "(*) Ngày bắt đầu bắt buộc phải nhập",
                // min : "(!) Ngày bắt đầu chương trình không được trong quá khứ",
            },
            "customer-end-date" : {
                required : "(*) Ngày kết thúc bắt buộc phải nhập",
                // min : "(!) Ngày kết thúc chương trình không được trong quá khứ",
            }
        }
    });
});
