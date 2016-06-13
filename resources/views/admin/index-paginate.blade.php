<table class="table table-striped list-merchant-adminpage" id="listAllMerchant" style="font-size:13px;">
                <thead>
                    <tr>
                        <th class="pink">ID</th>
                        <th class="pink">Thương hiệu</th>
                        <th class="pink">Trạng thái</th>
                        <th class="pink">Gói hiện tại</th>
                        <th class="pink">Nâng cấp</th>
                        <th class="pink">Ngày còn lại</th>
                        <th class="pink">Chi tiết</th>
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

                            <button type="button" id="" class="btn btn-info btn-xs btn-admin-merchant-view-detail" data-toggle="modal" data-merchant-model="@if($row->card_type == 3){{ getOptionValueById(json_decode($row->card_info,true)['value'][0]['id'])}} @elseif($row->card_type == 4) {{ getChops(json_decode($row->card_info,true)['value'][0]['id']) }}@endif" data-merchant-card-type="{{ $row->card_type }}" data-merchant-email="{{ json_decode($row->information,true)['email'] }}" data-merchant-phone="{{ json_decode($row->information,true)['phone'] }}" data-merchant-address="{{ json_decode($row->information,true)['address'] }}" data-merchant-birthday="{{ json_decode($row->information,true)['day'] . '/' . json_decode($row->information,true)['month'] .'/'. json_decode($row->information,true)['year'] }}" data-merchant-role="{{ json_decode($row->information,true)['role'] }}" data-merchant-info="{{ json_decode($row->information,true)['fullname'] }}" data-merchant-name="{{$row->name}}" data-merchant-field="{{ getField($row->field) }}" data-target="#modalMerchantDetail">Xem</button>
                            <!-- Modal -->
                            <div class="modal fade" id="modalMerchantDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title " id="myModalLabel">Thông tin <strong class="limit-name-admin" id="detail-merchant-title"></strong></h4>
                                        </div>
                                        <div class="modal-body">
                                            <fieldset class="create-brand">
                                                <div class="col-lg-12">
                                                    <div class="row">
                                                        <!-- LEFT -->
                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                            <h1 class="pink">Thông tin thương hiệu</h1>
                                                            <hr>
                                                            <p>Tên thương hiệu: <strong id="detail-merchant-name"> </strong></p>
                                                            <p>Lĩnh vực: <strong id="detail-merchant-field"></strong></p>
                                                            <p>Thông tin liên hệ: <strong id="detail-merchant-info"></strong></p>
                                                            <p>Vai trò:  <strong id="detail-merchant-role"></strong></p>
                                                            <p>Ngày sinh: <strong id="detail-merchant-birthday"></strong></p>
                                                            <p>Địa chỉ: <strong id="detail-merchant-address"></strong></p>
                                                            <p>Số điện thoại: <strong id="detail-merchant-phone"></strong></p>
                                                            <p>Email: <strong id="detail-merchant-email"></strong></p>
                                                        </div>
                                                        <!-- RIGHT -->
                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                            <h1 class="pink">Thông tin thẻ</h1>

                                                            <hr>
                                                            <p>
                                                                Loại thẻ thành viên: <strong id="detail-merchant-cardtype"></strong>
                                                            </p>
                                                            <p>
                                                                Hình thức: <strong id="detail-merchant-model"></strong>
                                                            </p>

                                                            <div id="infoCard-Admin">
                                                                {{ var_dump($row->card_info) }}
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </fieldset>
                                            <fieldset class="create-brand">
                                                <!-- INCLUDE HTML STORE -->
                                                <!-- END HTML STORE -->
                                            </fieldset>
                                            <!-- INCLUDE HTML INFO CHOICE CARD -->
                                            <fieldset class="create-brand">    
                                            </fieldset>
                                            <!-- INCLUDE HTML INFO CHOICE CARD -->
                                        </div>
                                        <div class="modal-footer">
                                            <!-- <button type="button" class="btn btn-info"  onclick="PrintElem('#modalMerchantDetail')"  >Print</button> -->
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
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
                                        <input type="text" class="form-control checkCurrentCustomerDate checkEndCustomerDate" maxlength="16" required id="customer-start-date" name="customer-start-date">
                                    </div>

                                    <div class="form-group">
                                        <label for="customer-end-date">Gia hạn ngày kết thúc gói thành viên</label>
                                        <input type="text" class="form-control checkCurrentCustomerDate checkEndCustomerDate" maxlength="16" required id="customer-end-date" name="customer-end-date">
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
