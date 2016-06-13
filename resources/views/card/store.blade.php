<div class="col-lg-12">
<h1><span class="pink">Tạo cửa hàng mới</span> <a href="#" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="{!! trans('infomation.IF_06') !!}"><i class="fa fa-info-circle default"></i></a></h1>
        <hr>
        <div class="col-lg-12">
            <div class="create-shop-address">
                <div class="table-responsive">
                    <table class="table" id="list-shop">
                        <thead>
                            <tr>
                            <th>Tên cửa hàng</th>
                            <th>Địa chỉ</th>
                            <th></th>
                            <th></th>
                            </tr>
                        </thead>
                        <tbody class="customize-input-point">
                                <tr>
                                    <td>
                                        <input type="text" style="padding-right: 10px;" name="nameshop" id="name-shop" class="form-control point" placeholder="Nhập tên cửa hàng">
                                    </td>
                                    <td>
                                        <input type="text" style="padding-right: 10px;" name="addressshop" id="address-shop" class="form-control point" placeholder="Nhập địa chỉ">
                                    </td>
                                    <td style="position:relative">
                                        <input type="submit" name="" id="" class="form-control btn btn-pink button-create-store-address" value="Tạo">
                                    </td>
                                    <td></td>
                                </tr>

                                <?php
                                    if ( $store->count() != 0 ) {
                                    foreach ($store as $row) {
                                ?>

                                <tr>
                                    <td>
                                        <label>{{ $row->store_name }}</label>
                                        <input type="text" name="" value="{{ $row->store_name }}" class="form-control sr-only">
                                         
                                    </td>


                                    <td>
                                        <label>{{ $row->address }}</label>
                                        <input type="text" name="" value="{{ $row->address }}" class="form-control sr-only">
                                    </td>


                                    <td>
                                        <!-- <i data-id="{{ $row->id }}" class="fa fa-pencil" data-toggle="tooltip" title="Sửa thông tin"></i> -->

                                        <i class="fa fa-pencil gray edit-name-store" data-toggle="tooltip" title="Sửa thông tin"></i>
                                        <i data-id="{{ $row->id }}" class="fa fa-check pink save-name-store sr-only" data-toggle="tooltip" title="Lưu thay đổi thông tin cửa hàng"></i>


                                        <i data-id="{{ $row->id }}" class="fa fa-trash  destroy-store" data-toggle="tooltip" title="Xóa cửa hàng (Đồng nghĩa với xóa tài khoản thu ngân)"></i>
                                        @if ($row->active != 1)
                                            <i data-id="{{ $row->id }}" class="fa fa-play pink active-store" data-toggle="tooltip" title="Kích hoạt cửa hàng"></i>
                                        @else
                                            <i data-id="{{ $row->id }}" class="fa fa-pause unactive-store" data-toggle="tooltip" title="Tạm dừng cửa hàng"></i>
                                        @endif
                                    </td>


                                    <td>
                                        @if ($row->active != 1)
                                            <span class="pink">Đang tạm dừng</span>
                                        @elseif ($row->active == 1)
                                            Đang hoạt động
                                        @endif
                                    </td>
                                </tr>
                                
                                <?php } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <h1><span class="pink">Tạo tài khoản đăng nhập dành cho THU NGÂN của các cửa hàng trên</span> <a href="#" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{!! trans('infomation.IF_05') !!}"><i class="fa fa-info-circle default"></i></a></h1>
            <hr>
            <div class="col-lg-10 col-sm-offset-1">
                <div class="create-shop-account">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="30%">TÊN CỬA HÀNG
                                    </th>
                                    <th width="25%">Tên đăng nhập
                                    </th>
                                    <th width="25%">Mật khẩu
                                    </th>
                                    <th width="20%">Chỉnh Sửa</th>
                                </tr>
                            </thead>
                            <tbody class="table-create-account-shop">


                                @if ($store->count() != 0)

                                    @foreach ($store as $row)

                                        <tr>
                                            <td><h4 class="pink">{{ $row->store_name }}</h4></td>
                                            <td>
                                                <label>
                                                    @if ($row->user_name == null)
                                                        <span class='pink'>(Tên đăng nhập)</span>
                                                    @else
                                                        {{ $row->user_name }}
                                                    @endif
                                                </label>
                                                <input type="text" name="" value="{{ $row->user_name }}" class="form-control sr-only">
                                            </td>
                                            <td>
                                                <label>
                                                    @if ($row->user_name == null)
                                                        <span class='pink'>(Mật khẩu đăng nhập)</span>
                                                    @else
                                                        ***********
                                                    @endif
                                                </label>
                                                <input type="password" name="" value="" class="form-control sr-only">
                                            </td>
                                            <td>
                                                <i class="fa fa-pencil gray edit-store" data-toggle="tooltip" title="Sửa tài khoản thu ngân"></i>
                                                <i data-id="{{ $row->id }}" class="fa fa-check pink save-store sr-only" data-toggle="tooltip" title="Lưu tài khoản thu ngân"></i>
                                            </td>
                                        </tr>

                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <div class="clearfix"></div>
    </div>
