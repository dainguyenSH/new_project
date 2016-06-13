@extends('layouts-admin.master')
@section('meta-title')
    <title>Registered Merchant - Super Administrator AbbyCard</title>
@stop
@section('css')
@stop
@section('title')
<div class="title-pages">
    <h2>Registered Merchant</h2>
</div>
@stop
@section('content')
<div class="account-manage-container">
<div class="col-lg-12">
    <h1 class="title-h1">Danh sách toàn bộ Registered Merchant</h1>
    <div class="clearfix"></div>
    <div class="col-lg-1">
    </div>
    <div class="col-lg-3">
        <!-- <div class="form-group">
            <select class="form-control" id="object-apply">
                <option>Hạng thẻ</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
            </select>
            </div> -->
    </div>
    <div class="col-lg-3">
        <!-- <div class="form-group">
            <select class="form-control" id="object-apply">
                <option>Trạng thái</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
            </select>
            </div> -->
    </div>
    <div class="col-lg-5">
        {{-- <div class="form-group has-feedback has-feedback-left">
            <i class="form-control-feedback glyphicon glyphicon-search" style="float:left"></i>
            <input type="text" class="form-control" id="searchAdmin" placeholder="Tìm kiếm" />
        </div> --}}
        <div id="custom-search-input">
                <div class="input-group col-md-12">
                    <input type="text" class="form-control pink" id="searchIndexAdmin" placeholder="Tìm kiếm..." />
                    <span class="input-group-btn">
                        <button class="btn btn-info" type="button" id="searchAdminButton">
                            <i class="glyphicon glyphicon-search pink"></i>
                        </button>
                    </span>
                </div>
            </div>
    </div>
    <div class="col-lg-12">
        <div class="table-responsive content-merchant" id="divListAllMerchantAdmin">
            <table class="table table-striped list-merchant-adminpage" id="listAllMerchant" style="font-size:13px;">
                <thead>
                    <tr>
                        <th class="pink">ID</th>
                        <th class="pink">Thương hiệu</th>
                        <th class="pink">Trạng thái</th>
                        <th class="pink">Gói hiện tại</th>
                        <th class="pink">Nâng cấp</th>
                        <th class="pink">Ngày còn lại</th>
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
                    <div class="modal fade" id="quickActionByAdmin" tabindex="-1" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">TÁC VỤ XỬ LÝ MERCHANT</h4>
                                </div>
                                <div class="modal-body">
                                    <form id="formAdminTaskForm" class="col-lg-12" style="padding-top:5%;">
                                        <div class="form-groupop position-counter">
                                            <input class="hide" id="formUserId" data-user-id=""/>
                                        </div>
                                        <div class="form-group">
                                            <label for="changePackage">Thay đổi gói áp dụng của thành viên</label>
                                            <select class="form-control" id="changePackage" value="" name="changePackage">
                                            @foreach ($package as $row)
                                            <option data-package-id="{{ $row->id }}">{{ $row->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="monthPackage">Thời gian tháng</label>
                                            <select class="form-control" id="monthPackage" value="" name="monthPackage">
                                            <option data-package-id="0">Vui lòng chọn thời gian gia hạn</option>
                                            @foreach ($month as $mon)
                                            <option data-package-id="{{ $mon->id }}">{{ $mon->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="changePackageStatus">Thay đổi trạng thái kích hoạt của thành viên</label>
                                            <select class="form-control" id="changeStatus" value="" name="changePackageStatus">
                                            <option data-user-status="1">Active</option>
                                            <option data-user-status="3">Block</option>

                                        <select>
                                    </div>
                                    <div class="form-group">
                                        <label for="customer-start-date">Gia hạn ngày bắt đầu gói thành viên</label>
                                        <input type="text" class="form-control checkCurrentCustomerDate checkEndCustomerDate" disabled="disabled" style="background-color: #eeeeee " maxlength="16" required id="customer-start-date" name="customer-start-date">
                                    </div>

                                    <div class="form-group">
                                        <label for="customer-end-date">Gia hạn ngày kết thúc gói thành viên</label>
                                        <input type="text" class="form-control checkCurrentCustomerDate checkEndCustomerDate" disabled="disabled" style="background-color: #eeeeee   " maxlength="16" required id="customer-end-date" name="customer-end-date">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-pink btn-lg btn-admin-update-info" id="btn-admin-update-info">Lưu thay đổi</button>   
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
            {!! $merchant->render() !!}
        </div>
        <div id="divListSearchResultAdmin" class="hide">

        </div>
    </div>
</div>
</div>
@stop
@section('js')

<script type="text/javascript">

    function PrintElem(elem)
    {
        Popup($(elem).html());
    }

    function Popup(data) 
    {
        var mywindow = window.open('', 'my div', 'height=400,width=900');
        mywindow.document.write('<html><head><title>my div</title>');
        /*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        mywindow.print();
        mywindow.close();

        return true;
    }

</script>
@stop
