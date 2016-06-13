
var deal_object_name = new Array();
$(document).ready(function() {
    var root = $('#root').val();
    var timeout = 100;
        
        
        //Xu ly click show modal
       
        
        // Xu ly datepicker
        $("#start-date").datetimepicker();
        $("#start-date").change(function() {
            var dateString = $("#start-date").val(),
                dateParts = dateString.split(' '),
                timeParts = dateParts[1].split(':');

                dateParts = dateParts[0].split('/');

            date = new Date(dateParts[0], dateParts[1], dateParts[2], timeParts[0], timeParts[1], 0, 0).getTime();
         
            if($("#end-date").val()){
                $("#end-date").valid();
            }
            
            
        });

        $("#end-date").datetimepicker();
        $("#end-date").change(function() {
            var dateString = $("#end-date").val(),
                dateParts = dateString.split(' '),
                timeParts = dateParts[1].split(':');

                dateParts = dateParts[0].split('/');

            date = new Date(dateParts[0], dateParts[1], dateParts[2], timeParts[0], timeParts[1], 0, 0).getTime();
       
            if($("#start-date").val()){
                $("#start-date").valid();
            }
        });
        


        // $("#edit-end-date").change(function() {
        //     $("#edit-start-date").focus();
        //     $("#edit-end-date").focus();
            
        // });
        // $("#edit-start-date").change(function() {
        //     $("#edit-end-date").focus();
        //     $("#edit-start-date").focus();
        // });

            $("#edit-start-date").datetimepicker();
            $("#edit-start-date").change(function() {
                var dateString = $("#edit-start-date").val(),
                    dateParts = dateString.split(' '),
                    timeParts = dateParts[1].split(':');

                    dateParts = dateParts[0].split('/');

                date = new Date(dateParts[0], dateParts[1], dateParts[2], timeParts[0], timeParts[1], 0, 0).getTime();
         
                if($("#edit-end-date").val()){
                   
                    $("#edit-end-date").valid();
                }
            });

            $("#edit-end-date").datetimepicker();
            $("#edit-end-date").change(function() {
                var dateString = $("#edit-end-date").val(),
                    dateParts = dateString.split(' '),
                    timeParts = dateParts[1].split(':');

                    dateParts = dateParts[0].split('/');
                

                date = new Date(dateParts[0], dateParts[1], dateParts[2], timeParts[0], timeParts[1], 0, 0).getTime();
                
                if($("#edit-start-date").val()){
                    
                    $("#edit-start-date").valid();
                }
            });
        //Edit deal

        function getRootUrl() {
          var defaultPorts = {"http:":80,"https:":443};

          return window.location.protocol + "//" + window.location.hostname
           + (((window.location.port)
            && (window.location.port != defaultPorts[window.location.protocol]))
            ? (":"+window.location.port) : "");
        }

        $(".btn-edit-deal").click(function(e) {


            $('#myModal').modal('show');

            e.preventDefault();
            var id = $(this).attr('data-deal-id');
            var deal_name = $(this).attr("data-deal-name");
            var deal_description = $(this).attr("data-deal-description");
            var deal_avatar = $(this).attr("data-deal-avatar");

            var deal_image_content = $(this).attr("data-deal-image-content");

            if (deal_image_content != null) {
                var obj_image_content = jQuery.parseJSON(deal_image_content);
            } else {
                var obj_image_content = []
            }

            var deal_end_date = $(this).attr("data-end-date").substr(0,16);
            var deal_start_date = $(this).attr("data-start-date").substr(0,16);

            // var path = location.protocol + '//' + location.host;
            


            $(".modal-title").html(deal_name);
            $(".modal-title").attr("data-deal-id",id);
            var count = 0;
            for (var obj in obj_image_content) {
                if (obj_image_content.hasOwnProperty(obj)) {
                    count++;
                }
            }
            $("#listEditContentImage").removeClass("hide");

            if ($(this).attr("data-object-apply")) {
                var deal_object_apply = $(this).attr("data-object-apply");
                $("#edit-object-apply").val(deal_object_apply.split(","));
            }
         
            
            
            //Them du lieu cu vao form
            $("#editTitleIncentives").val(deal_name); //  ten anh load tu db
            $("#editContentIncentives").val(deal_description); // mo ta hoa don load tu db
            $("#edit-start-date").val(deal_start_date); // Ngay bat dau load tu db
             //Doi tuong ap dung

            if ($("#edit-object-apply").attr("data-object-apply")){
                $("#edit-object-apply").val(deal_object_apply.split(","));
            }
            $("#edit-end-date").val(deal_end_date); // Ngay ket thuc load tu db
            $("#edit-show-image" ).empty();
            $("#edit-show-image" ).append( "<img class=\"img-content-avatar\" src=\"" +root + "/" + deal_avatar + "\"/>" );
            //Them anh noi dung 
            for( var x = 1; x <= count; x++) {
                //Them the li
                var li = document.createElement('li');
                if (x == 1) {
                    //add li active
                    $("#edit_carousel_indicators").empty();
                    $(li).attr({"data-target":"#myEditCarousel","class":"active", "data-slide-to": x-1});
                    $("#edit_carousel_indicators").append(li);
                    //add div active
                    $("#edit_carousel_inner").empty();
                    var div = document.createElement('div');
                    var img = document.createElement('img');
                    $(img).attr({"src" : root + '/'+obj_image_content[x], "style" : "width: 237px;"});

                    $(div).addClass("item active");

                    $(div).append(img);

                    $("#edit_carousel_inner").append(div);


                } else {
                    //add li normal
                    $(li).attr({"data-target":"#myEditCarousel","data-slide-to": x-1});
                    $("#edit_carousel_indicators").append(li);
                    //add div mormal
                    var div = document.createElement('div');
                    var img = document.createElement('img');
                    $(img).attr({"src" : root + '/' + obj_image_content[x], "style" : "width: 237px;"});
                    $(div).addClass("item");
                    $(div).append(img);
                    $("#edit_carousel_inner").append(div);
                }
     
                
            }
            //Xu ly submit form
            $("#btn-edit-incentives").click(function(e) {
            
                var form = $("#formEditDeal");
                var url_return = $("#btn-edit-incentives").attr("data-url-return");
                form.validate();
                if (form.valid()) {

                    $.confirm({
                        theme: 'supervan',
                        title: 'CHỈNH SỬA CHƯƠNG TRÌNH ƯU ĐÃI',
                        confirmButtonClass: 'btn-info',
                        cancelButtonClass: 'btn-danger',
                        content: 'Quý khách có chắc chắn muốn chỉnh sửa chương trình ưu đãi hay không',
                        confirm: function(){
                            $.loadding();
                            e.preventDefault();
                            var formData = new FormData();
                            var image_avatar = $("#edit-logo")[0].files;
                            var image_content = $("#edit-image-content")[0].files;
                            if (image_content.length > 0) {
                                for (var i = 0; i < image_content.length; i++) {
                                    formData.append('image_content[]', image_content[i]);
                                }   
                            }
                           
                            if (image_avatar.length > 0) {
                                formData.append("image_avatar", image_avatar[0]);
                            }
                            
                            formData.append("id",$(".modal-title").attr("data-deal-id"));
                            formData.append("name", $("#editTitleIncentives").val());
                            formData.append("description", $("#editContentIncentives").val());
                            formData.append("start_day", $("#edit-start-date").val());
                            formData.append("end_day", $("#edit-end-date").val());
                            if ($("#edit-object-apply")) {

                                var object_apply_id_value = [];
                               
                                $('#edit-object-apply option:selected').each(function(){
                                    object_apply_id_value.push($(this).attr('object_apply_id'));
                                });
                                formData.append("apply_objects", object_apply_id_value);  
                                // formData.append("apply_objects", $("#edit-object-apply").val());
                            } else {
                                formData.append("apply_objects", "");
                            }
                            

                        
                            $.ajax({
                                url: 'update-incentives',
                                processData : false,
                                cache: false,
                                contentType: false,
                                method:'post',
                                data : formData,
                            
                                success: function(data){
                                    $.endload();
                                    BootstrapDialog.show({
                                        title: data.priority,
                                        message: data.messages,
                                        buttons: [{
                                            label: 'Đóng',
                                            cssClass: 'btn-pink',
                                            action: function(dialogItself){
                                                dialogItself.close();
                                                setTimeout(function(){
                                                    
                                                    $.loadding();
                                                        location.reload();
                                                    $.endload();
                                                    
                                                }, 1000);
                                            }
                                        }]
                                    });

                                },
                                error: function(){},
                            });
                        },
                        cancel: function(){
                            // location.reload();
                        }
                    });
                    
                }
            });
           
        });
        //Xoa Deal
        $(".btn-delete-deal").click(function(e) {

            var id = $(this).attr('data-deal-id');
            
            $.confirm({
                theme: 'supervan',
                title: 'HỦY CHƯƠNG TRÌNH ƯU ĐÃI',
                confirmButtonClass: 'btn-info',
                cancelButtonClass: 'btn-danger',
                content: 'Bạn có chắc muốn hủy chương trình này? <p class="hide" id="confirm-deal-id" data-deal-id=' + id + '></p>',
                confirm: function(){
                    e.preventDefault();
                    id = $("#confirm-deal-id").attr("data-deal-id");
                    $.ajax({
                        url: 'delete-incentives',                
                        method:'post',
                        data : {"id":id},
                                
                        success: function(data){
                            // $.toaster({ priority : data.priority, message : data.messages });
                            BootstrapDialog.show({
                                title: data.priority,
                                message: data.messages,
                                buttons: [{
                                    label: 'Đóng',
                                    cssClass: 'btn-pink',
                                    action: function(dialogItself){
                                        dialogItself.close();
                                        setTimeout(function(){
                                            
                                        $.loadding();
                                            location.reload();
                                            
                                        $.endload();
                                        }, 1000);
                                    }
                                }]
                            });
                            
                            // location.reload();
                        },
                        error: function(){

                        },
                    });
                },
                cancel: function(){
                    
                }
            });
            
        });
        //Active Deal
        $(".btn-active-deal").click(function(e) {
            var id = $(this).attr('data-deal-id');
            
            $.confirm({
                theme: 'supervan',
                title: 'KÍCH HOẠT CHƯƠNG TRÌNH ƯU ĐÃI',
                confirmButtonClass: 'btn-info',
                cancelButtonClass: 'btn-danger',
                content: 'Kích hoạt ưu đãi sẽ gửi tới tất cả thành viên của thương hiệu. Bạn có chắc chắn muốn kích hoạt?',
                confirm: function(){
                    e.preventDefault();
                    
                        
                    $.ajax({
                        url: 'active-incentives',                
                        method:'post',
                        data : {"id":id},
                                
                        success: function(data){
                            BootstrapDialog.show({
                                title: data.priority,
                                message: data.messages,
                                buttons: [{
                                    label: 'Đóng',
                                    cssClass: 'btn-pink',
                                    action: function(dialogItself){
                                        dialogItself.close();
                                        setTimeout(function(){
                                            $.loadding();
                                            location.reload();
                                            $.endload();
                                        }, 1000);
                                    }
                                }]
                            });
                        },
                        error: function(){

                        },
                    });
                },
                cancel: function(){
                    
                }
            });
            
        });
        //Xu ly tao uu dai
        $('#object-apply').change(function(){
            if($('#object-apply').val()){
                $('#object-apply').valid();
            }
        });

        $('#titleIncentives').change(function(){
            if($('#titleIncentives').val()){
                $('#titleIncentives').valid();
            }
        });
        $('#contentIncentives').change(function(){
            if($('#contentIncentives').val()){
                $('#contentIncentives').valid();
            }
        });

        $( ".btn-create-incentives" ).click(function(e) {
         

            // return false;
            e.preventDefault();
            var form = $("#formCreateDeal");
            form.validate();
            if (form.valid()) {


                $.confirm({
                    theme: 'supervan',
                    title: 'TẠO CHƯƠNG TRÌNH ƯU ĐÃI',
                    confirmButtonClass: 'btn-info',
                    cancelButtonClass: 'btn-danger',
                    content: 'Bạn có chắc chắn muốn tạo chương trình ưu đãi hay không',
                    confirm: function(){
                        var formData = new FormData();
                        var image_avatar = $("#logo")[0].files[0];
                        var image_content = $("#image-content")[0].files;
                        for (var i = 0; i < image_content.length; i++) {
                            formData.append('image_content[]', image_content[i]);
                        }
                        var array_ignore_image = $("#image-content").attr("data-ignore-image").split(",");
                        formData.append("ignore_image",array_ignore_image);
                        // formData.append("upload_image_content", array_upload_content_image);
                        formData.append("image_avatar", image_avatar);
                        formData.append("titleIncentives", $("#titleIncentives").val());
                        formData.append("contentIncentives", $("#contentIncentives").val());
                        var value_start_date = $("#start-date").val();
                        var value_end_date = $("#end-date").val();
                        formData.append("start-date", value_start_date.replace(/\//g,"-"));
                        formData.append("end-date", value_end_date.replace(/\//g,"-"));
                        if ($("#object-apply")) {
                            var object_apply_id_value = [];
                          
                            $('#object-apply option:selected').each(function(){
                                object_apply_id_value.push($(this).attr('object_apply_id'));
                            });
                            formData.append("object-apply", object_apply_id_value);    
                        } else {
                            formData.append("object-apply", ""); 
                        }
                        $( ".btn-create-incentives" ).attr("disabled");
                        $.loadding();


                        
                        $.ajax({
                            url: 'store-incentives',
                            processData : false,
                            cache: false,
                            contentType: false,
                            method:'post',
                            data : formData,
                            
                            success: function(data){
                                $.endload();
                                if (data.priority == "danger") {
                                    
                                    BootstrapDialog.show({
                                        title: "THÔNG BÁO",
                                        message: data.messages,
                                        buttons: [{
                                            label: 'Đóng',
                                            cssClass: 'btn-pink',
                                            action: function(dialogItself){
                                                dialogItself.close();
                                                
                                                setTimeout(function(){
                                                    $.loadding();
                                                        location.reload();
                                                    $.endload();
                                                }, 10);
                                                
                                            }
                                        }]
                                    });
                                } else {
                                    BootstrapDialog.show({
                                        title: data.priority,
                                        message: data.messages,
                                        buttons: [{
                                            label: 'Đóng',
                                            cssClass: 'btn-pink',
                                            action: function(dialogItself){
                                                dialogItself.close();
                                                setTimeout(function(){
                                                    $.loadding();
                                                    location.reload();
                                                    $.endload();
                                                }, 1000);                                            }
                                        }]
                                    });
                                }
                                
                               
                                

                            },
                            error: function(){},
                        });
                    },
                    cancel: function(){
                        // location.reload();
                    }
                });

                
            } else {
                $.alert({
                    theme: 'supervan',
                    title: 'LỖI NHẬP THÔNG TIN',
                    confirmButtonClass: 'btn-info',
                    
                    content: 'Thông tin bạn nhập có lỗi. Vui lòng kiểm tra lại',
                    
                });
            }
        });
        
        

        //BAT DAU VALIDATE
 
        $.validator.addMethod("checkCurrentDate", function(value, element) {
            var myDate = value;
         
            var today = new Date();
            
            //alert(today);
            var currentDate = new Date();
           
            
            
            //Any code that will return TRUE or FALSE
            if (Date.parse(myDate) < currentDate){
                return false;
            }else{
                return true;
            }

        },"Thời gian này không được ở trong quá khứ");

        $.validator.addMethod("checkStartDate", function(value, element) {
            var myDate = value;
            var endDate = $("#end-date").val();
        
            //Any code that will return TRUE or FALSE
            if ((endDate != "") && (myDate > endDate)){
                return false;
            }else{
                return true;
            }

        },"Thời gian bắt đầu không được lớn hơn thời gian kết thúc");

        $.validator.addMethod("checkEditStartDate", function(value, element) {
            var myDate = value;
            var endDate = $("#edit-end-date").val();
        
            //Any code that will return TRUE or FALSE
            if ((endDate != "") && (myDate > endDate)){
                return false;
            }else{
                return true;
            }


        },"Thời gian bắt đầu không được lớn hơn thời gian kết thúc");


		$.validator.addMethod("checkMaxUploadFile", function(element) {
            
            var array_ignore_image = $("#image-content").attr("data-ignore-image");
            if(array_ignore_image != ""){
                var array_ignore_image = $("#image-content").attr("data-ignore-image").split(",");
                
            } else {
                var array_ignore_image = [];
            }
            
       

            if (($("#image-content")[0].files.length - array_ignore_image.length) > 15){
                
                return false;
            }else{
                return true;
            }

        },"Bạn chỉ được upload tối đa 15 ảnh");

        $.validator.addMethod("checkMaxUploadEditFile", function(element) {
            
            
            if ($("#edit-image-content")[0].files.length > 3){
                return false;
            }else{
                return true;
            }

        },"Bạn chỉ được upload tối đa 15 file");

        $.validator.addMethod("checkEndDate", function(value, element) {
            var myDate = value;
            var startDate = $("#start-date").val();
        
            //Any code that will return TRUE or FALSE
            if ((startDate != "") && (myDate < startDate)){
                return false;
            }else{
                return true;
            }

        },"Thời gian kết thúc không được nhỏ hơn thời gian bắt đầu");

        $.validator.addMethod("checkEditEndDate", function(value, element) {
            var myDate = value;
            var startDate = $("#edit-start-date").val();
            //Any code that will return TRUE or FALSE
            if ((startDate != "") && (myDate < startDate)){
                return false;
            }else{
                return true;
            }

        },"Thời gian kết thúc không được nhỏ hơn thời gian bắt đầu");

        $.validator.addMethod('filesize', function(value, element, param) {

            var file_lenght = element.files.length;
            var count = 0;
            for(var i = 0; i< file_lenght; i++) {
                if (this.optional(element) || (element.files[i].size <= param)) {
                    
                } else {
                    count++;
                }
            }
            if( count > 0) {
                return false;
            } else {
                return true;
            }
           
        });

        $.validator.addMethod('fileext', function(value, element) {
            
            var file_lenght = element.files.length;
            var count = 0;
            for(var i = 0; i< file_lenght; i++) {
                
                var filename = element.files[i].name;
                var ext = filename.split('.').pop();
                var ext = ext.toLowerCase();
     
                if (ext === "jpg" || ext === "png" || ext === "jpeg") {
                    
                } else {
                    count++;
                }
            }

            if( count > 0) {
                return false;
            } else {
                return true;
            }
          
        },"(*) Vui lòng chọn định dạng ảnh phù hợp là .jpg .png .jpeg");

        $.validator.addMethod('checkhtmltag', function(value, element) {
            if (value.match(/<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/)) {
                return false;
            } else {
                return true;
            }
        }, "(*) Chuỗi nhập vào không được chứa thẻ HTML");



        $("#formEditDeal").validate({
            rules: {
                "titleIncentives" : {
                    required : true,
                    maxlength: 60
                },
                "image-content[]" : {
                    
                    fileext : true,
                    filesize : 2097152/4
                    
                },
                
                "contentIncentives" : {
                    required : true,
                    maxlength: 500
                },
                
                "start-date" : {
                    required : true,
                    // optdate : true,
                    
                },
                "end-date" : {
                    required : true,
                    // min : true,
                    
                },
                "object-apply": {
                    required : true,
                },
                "image_avatar" : {
                    
                    extension: "jpg|jpeg|png",
                    filesize: 2097152/4
                },
                
                
            },
            messages: {
                "titleIncentives" : {
                    required : "(*) Tên chương trình ưu đãi bắt buộc phải nhập",
                    maxlength: "(*) Tên chương trình ưu đãi không vượt quá 60 kí tự"
                },
                "contentIncentives" : {
                    required : "(*) Nội dung chương trình ưu đãi bắt buộc phải nhập",
                    maxlength: "(*) Nội dung chương trình ưu đãi không vượt quá 500 kí tự"
                },
                
                "image-content[]" : {
                    
                    // maxlength : "Ảnh nội dung chỉ được upload tối đa 3 ảnh",
                    fileext : "(*) Vui lòng lựa chọn đúng định dạng ảnh .jpg | .jpeg | .png",
                    filesize : "(*) Kích thước các tập tin phải nhỏ hơn 500KB"
                    
                },
                "start-date" : {
                    required : "(*) Ngày bắt đầu chương trình bắt buộc phải nhập",
                    // min : "(!) Ngày bắt đầu chương trình không được trong quá khứ",
                },
                "end-date" : {
                    required : "(*) Ngày kết thúc chương trình bắt buộc phải nhập",
                    // min : "(!) Ngày kết thúc chương trình không được trong quá khứ",
                },
                "object-apply" : {
                    required : "(*) Đối tượng áp dụng bắt buộc phải lựa chọn",
                },
                "image_avatar" : {
                    
                    extension: "(*) Vui lòng lựa chọn đúng định dạng ảnh .jpg | .jpeg | .png",
                    filesize : "(*) Kích thước tập tin phải nhỏ hơn 500KB"
                },
                
                
            }
        });
        $("#formCreateDeal").validate({
            rules: {
                "titleIncentives" : {
                    required : true,
                    
                },
                "image-content[]" : {
                	required : true,
                    // maxlength : 3,
                    fileext : true,
                    filesize: 2097152/4
                	
                },
                "contentIncentives" : {
                    required : true,
                },
                
                "start-date" : {
                    required : true,
                    // optdate : true,
                    
                },
                "end-date" : {
                    required : true,
                    // min : true,
                    
                },
                "object-apply": {
                    required : true,
                },
                "image_avatar" : {
                    required : true,
                    extension: "jpg|jpeg|png",
                    filesize: 2097152/4
                },
                
                
            },
            messages: {
                "titleIncentives" : {
                    required : "(*) Tên chương trình ưu đãi bắt buộc phải nhập",
                },
                "contentIncentives" : {
                    required : "(*) Nội dung chương trình ưu đãi bắt buộc phải nhập",
                },
                "image-content[]" : {
                	required : "(*) Ảnh nội dung bắt buộc phải chón",
                    // maxlength : "Ảnh nội dung chỉ được upload tối đa 3 ảnh",
                    fileext : "(*) Vui lòng lựa chọn đúng định dạng ảnh .jpg | .jpeg | .png",
                    filesize : "(*) Kích thước các tập tin lựa chọn phải nhỏ hơn 500KB"
                	
                },
                "start-date" : {
                    required : "(*) Ngày bắt đầu chương trình bắt buộc phải nhập",
                    // min : "(!) Ngày bắt đầu chương trình không được trong quá khứ",
                },
                "end-date" : {
                    required : "(*) Ngày kết thúc chương trình bắt buộc phải nhập",
                    // min : "(!) Ngày kết thúc chương trình không được trong quá khứ",
                },
                "object-apply" : {
                    required : "(*) Đối tượng áp dụng bắt buộc phải lựa chọn",
                },
                "image_avatar" : {
                    required : "(*) Ảnh đại diện bắt buộc phải lựa chọn",
                    extension: "(*) Vui lòng lựa chọn đúng định dạng ảnh .jpg | .jpeg | .png",
                    filesize : "(*) Kích thước tập tin phải nhỏ hơn 500KB"
                },
                
            }
        });
    });
