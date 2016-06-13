
@if($merchant->count() == 0)
<table class="table table-striped list-customer-panel"> 
    <thead class="list-customer-header">

        <th class="text-center pink">Không tìm thấy kết quả phù hợp</th>
        
    </thead>
</table>
@else
<table class="table table-striped list-merchant-adminpage" id="listAllMerchant" style="font-size:13px;">
    <thead>
        <tr>
            <th class="pink">ID</th>
            <th class="pink">Thương hiệu</th>
            <th class="pink">Trạng thái</th>
            <th class="pink">Gói hiện tại</th>
            <th class="pink">Nâng cấp</th>
            <th class="pink">Ngày còn lại</th>
            <!-- <th class="pink">Chi tiết</th> -->
            <th class="pink">Tác vụ</th>
        </tr>
    </thead>
    <tbody class="list-merchant">
        @foreach ($merchant as $row)
        <tr>
            <td>{{ $row->id }}</td>
            <td class="limit-name-admin">{{ $row->name }}</td>
            <td>
                {{ checkActiveAdmin($row->active, $row->id) }}
            </td>
            <td>{{ checkPackageAdmin($row->package, $package) }}</td>
            <td >{{ checkPackageStatusAdmin($row->package, $row->package_status, $package,  $row->id) }}</td>
            <td>{{ checkDayRemaining( $row->end_day, $row->package) }}</td>
            <!-- <td class="status-display">{{ $row->created_at }}</td> -->
            <td>
                <button  type="button" class="btn btn-primary btn-xs btn-admin-task" data-user-id="{{ $row->id }}" data-user-package="{{ getPackageName($row->package) }}" data-user-package-id="{{ $row->package }}" data-user-status="{{ getUserStatus($row->active) }}" data-start-day="{{ $row->start_day }}" data-end-day="{{ $row->end_day }}" data-toggle="modal">Tác vụ</button>
                <a href="{{ URL('admincp/merchant') }}/{{ $row->id }}"><button class="btn btn-xs btn-pink">Sửa</button></a>
            </td>
        </tr>
        @endforeach
        <div class="modal fade" id="quickActionByAdminSearch" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">TÁC VỤ XỬ LÝ MERCHANT</h4>
                    </div>
                    <div class="modal-body">
                        <form id="formAdminTaskFormSearch" class="col-lg-12" style="padding-top:5%;">
                            <div class="form-groupop position-counter">
                                <input class="hide" id="formUserIdSearch" data-user-id=""/>
                            </div>
                            <div class="form-group">
                                <label for="changePackage">Thay đổi gói áp dụng của thành viên</label>
                                <select class="form-control" id="changePackageSearch" value="" name="changePackage">
                                @foreach ($package as $row)
                                <option data-package-id="{{ $row->id }}">{{ $row->name }}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="monthPackage">Thời gian tháng</label>
                                <select class="form-control" id="monthPackageSearch" value="" name="monthPackage">
                                <option data-package-id="0">Vui lòng chọn thời gian gia hạn</option>
                                @foreach ($month as $mon)
                                <option data-package-id="{{ $mon->id }}">{{ $mon->name }}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="changePackageStatus">Thay đổi trạng thái kích hoạt của thành viên</label>
                                <select class="form-control" id="changeStatusSearch" value="" name="changePackageStatus">
                                <option data-user-status="1">Active</option>
                                <option data-user-status="3">Block</option>

                            <select>
                        </div>
                        <div class="form-group">
                            <label for="customer-start-date">Gia hạn ngày bắt đầu gói thành viên</label>
                            <input type="text" class="form-control checkCurrentCustomerDate checkEndCustomerDate" maxlength="16" required disabled="" style="background-color: #eeeeee" id="customer-start-date-search" name="customer-start-date">
                        </div>

                        <div class="form-group">
                            <label for="customer-end-date">Gia hạn ngày kết thúc gói thành viên</label>
                            <input type="text" class="form-control checkCurrentCustomerDate checkEndCustomerDate" maxlength="16" required disabled="" style="background-color: #eeeeee" id="customer-end-date-search" name="customer-end-date">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-pink btn-lg btn-admin-update-info" id="btn-admin-update-info-search">Lưu thay đổi</button>   
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" value="submit" data-dismiss="modal">Close</button>

                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        
    </tbody>
</table>
@if($count_page > 1)
    <ul class="pagination">
        <li style="cursor: pointer;" class="@if($current_page == 0) disabled @endif"><a onclick="@if($current_page == 0) # @else filterMerchantPagination({{$current_page - 1}}) @endif">«</a></li>
        @for($i = 0; $i < $count_page; $i++)

            <li style="cursor: pointer;" class="@if($i == $current_page) active pink @endif"><a onclick="filterMerchantPagination({{$i}})">{{ $i + 1 }}</a></li>

        @endfor
        <li style="cursor: pointer;" class="@if($current_page == $count_page - 1) disabled @endif"><a onclick="@if($current_page == $count_page - 1)  # @else filterAccountPagination({{$current_page + 1}}) @endif">»</a></li>
    </ul>
@endif

@endif
<script type="text/javascript">
    function filterMerchantPagination(page){
        var search_box = $("#searchIndexAdmin").val();
        console.log(search_box);
        
        
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
    $(".btn-admin-task").click(function(e) {
        e.preventDefault();
        $('#quickActionByAdminSearch').modal('show');
        var user_id = $(this).attr("data-user-id");
        var user_status = $(this).attr("data-user-status");
        var user_month = $(this).attr("data-user-package");
        var user_package = $(this).attr("data-user-package");
        var start_day = $(this).attr("data-start-day").split(" ");
        var end_day = $(this).attr("data-end-day").split(" ");
        $("#formUserIdSearch").attr("data-user-id",user_id);
        $("#changeStatusSearch").val(user_status);
        $("#changePackageSearch").val(user_package);
        $("#customer-start-date-search").val(start_day[0]);
        $("#customer-end-date-search").val(end_day[0]);
    });

    $("#monthPackageSearch").change(function(){
        d = new Date();
        $("#customer-start-date-search").val(d.yyyymmdd());
        var mounth = $('option:selected',$("#monthPackageSearch")).val();
        console.log(mounth);
        d = new Date();
        end_d = d.addMonths(parseInt(mounth));
        console.log(end_d);
        // console.log(d.yyyymmdd()); // Assuming you have an open console
        $("#customer-end-date-search").val(end_d.yyyymmdd());
    });

    $("#btn-admin-update-info-search").click(function(e) {
        e.preventDefault();
        var form = $("#formAdminTaskFormSearch");
        form.validate();
        if (form.valid() == true) {
            console.log("da valid");
        }
        // console.log('xxxx');
        // console.log($("#customer-start-date").valid());
        if (form.valid()) {
            // console.log("adflakjdf");
            var package_id = $('option:selected',$("#changePackageSearch")).attr("data-package-id");
            var active_id = $('option:selected',$("#changeStatusSearch")).attr("data-user-status");
            var mounth = $('option:selected',$("#monthPackageSearch")).val();
            var start_day = $("#customer-start-date-search").val();
            var end_day = $("#customer-end-date-search").val();
            // console.log(package_id);
            var formData = new FormData();
            formData.append("id",$("#formUserIdSearch").attr("data-user-id"));
            formData.append("active",active_id);
            formData.append("package",package_id);
            formData.append("mounth",mounth);
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
                    $.toaster({ priority : data.priority, message : data.messages });
                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                },
                error: function(){},
            });
        }
    });
    $(".admin-package-status").click(function(e) {
        e.preventDefault();
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
                    $.ajax({
                        url: 'admincp/update-package',                
                        method:'post',
                        data : {
                            "package_status" : package_status,
                            "user_id" : user_id,
                        },
                        success: function(data){
                            $.toaster({ priority : data.priority, message : data.messages });
                            setTimeout(function(){
                                location.reload();
                            }, 1000);
                            // location.reload();
                        },
                        error: function(){
                        },
                    });
            },
            cancel: function(){
            },
        });
    });

    $(".btn-admin-merchant-view-detail").click(function(){

        console.log('da click');
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
            $("#detail-merchant-cardtype-search").html('Level');
            $("#detail-merchant-model-search").html("Thẻ thành viên "+model);
        } else if(card_type == 4) {
            $("#detail-merchant-cardtype-search").html('Chops');
            $("#detail-merchant-model-search").html(model);
        }
        $("#detail-merchant-title-search").html(name);
        $("#detail-merchant-name-search").html(name);
        $("#detail-merchant-field-search").html(field);
        $("#detail-merchant-info-search").html(info);
        $("#detail-merchant-role-search").html(role);
        $("#detail-merchant-birthday-search").html(birthday);
        $("#detail-merchant-address-search").html(address);
        $("#detail-merchant-phone-search").html(phone);
        $("#detail-merchant-email-search").html(email);
    });

</script>
