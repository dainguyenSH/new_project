@extends('layouts-admin.master')
@section('css')
<link href="{{ URL('') }}/css/jquery.steps.css" rel="stylesheet">
@stop
@section('title')
<div class="title-pages">
    <h2>
        CHỈNH SỬA THÔNG TIN MERCHANT
    </h2>
</div>
@stop
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">Vui lòng chọn nội dung cần sửa</div>
    <div class="panel-body">
        <div class="checkbox">
            <label class="radio-inline"><input type="radio" name="bedStatus" id="allot" checked="checked" value="infomation">Infomation</label>
            <label class="radio-inline"><input type="radio" name="bedStatus" id="transfer" value="store">Store</label>
            <label class="radio-inline"><input type="radio" name="bedStatus" id="transfer" value="card">Card Info</label>
        </div>
    </div>
</div>
{{-- Infomation --}}
<div class="edit-infomation">
    <div class="panel panel-default">
        <div class="panel-heading">Chỉnh sửa thông tin Merchant</div>
        {{-- FORM INFO --}}
        <div class="panel-body">
            <fieldset class="create-brand">
                <div class="row">
                    <!-- LEFT -->
                    <div class="col-lg-6">
                        <h1 class="pink">Thông tin thương hiệu</h1>
                        <hr>
                        <div class="form-group">
                            <input class="form-control required checkhtmltag" type="text" id="trademark" name="trademark" placeholder="Tên thương hiệu" value="{{ $merchant->name }}">
                        </div>
                        <div class="form-group">
                            <select class="form-control required" id="field" name="field">
                                <option value="-1">-- Lĩnh vực --</option>
                                @foreach($fields as $field)
                                <option @if($merchant->field == $field->id) selected @endif value="{{$field->id}}">{{$field->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Chọn mã màu cho Background</label>
                            <div class="input-group">
                                <span class="input-group-btn">
                                <input class="form-control" id="background-color" type="color" value="#f94876">
                                </span>
                                <span class="input-group-btn">
                                <input class="form-control" type="text" id="choice-color" name="color" value="#f94876">
                                </span>
                                <span class="input-group-btn">
                                <input type="button" id="reset-setting-color" class="btn" value="Reset">
                                </span>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <input class="form-control required" type="text" id="feild" name="feild" placeholder="Lĩnh vực" value="">
                            </div> -->
                        <div class="form-group">
                            <input type="hidden" id="nowLogo" value="{{ $merchant->logo }}"/>
                            <div class="row">
                                <center>
                                    <div class="col-md-12 contentImage">
                                        @if ($merchant->information == "")
                                        <div class="img-logo">
                                            <p class="position-text-avatar">Add logo <br> 300 x 300px</p>
                                        </div>
                                        @else
                                        <img class="img-logo" src="{{ URL('').'/'. $merchant->logo }}">
                                        @endif
                                    </div>
                                    <input type="file" id="edit-image_logo" value="" class="upload-img" />
                                    <input type="hidden" id="checkLogoExist" value="{{ $merchant->logo }}"/>
                                </center>
                            </div>
                        </div>
                    </div>
                    <!-- RIGHT -->
                    <div class="col-lg-6">
                        <h1 class="pink">Thông tin cá nhân</h1>
                        <hr>
                        <!--
                            <div class="form-group">
                                <input class="form-control required" type="text" id="" name="" placeholder="" value="">
                            </div>
                            -->
                        <div class="form-group">
                            <input class="form-control required checkhtmltag" type="text" id="" name="fullname" placeholder="Họ và tên" value="{{ $info['fullname'] or $merchant->information }}">
                        </div>
                        <div class="form-group">
                            <input class="form-control required checkhtmltag" type="text" id="" name="role" placeholder="Vai trò" value="{{ $info['role'] }}">
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <select class="form-control" name="day" id="day">
                                        <option value="">Ngày sinh</option>
                                        @for ($i = 1; $i <= 31 ; $i++) { 
                                        <option @if ( $info['day'] == $i) selected @endif value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </span>
                                <span class="input-group-btn">
                                    <select class="form-control" name="month" id="month">
                                        <option value="">Tháng</option>
                                        @for ($i = 1; $i <= 12 ; $i++) { 
                                        <option @if ( $info['month'] == $i) selected @endif value="{{ $i }}">Tháng {{ $i }}</option>
                                        @endfor
                                    </select>
                                </span>
                                <span class="input-group-btn">
                                    <select class="form-control" name="year" id="year">
                                        <option value="">Năm</option>
                                        @for ($i = 1970; $i <= 2015 ; $i++) { 
                                        <option @if ( $info['year'] == $i) selected @endif value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <input class="form-control checkhtmltag" type="text" id="address" name="address" placeholder="Địa chỉ" value="{{ $info['address'] }}">
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="province" id="province">
                                <option value="-1">-- Tỉnh/Thành --</option>
                                @foreach($provinces as $province)
                                <option @if( $info['province'] == $province->provinces_id) selected @endif value="{{$province->provinces_id}}">{{$province->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="district" id="district">
                                <option value="12">-- Quận/Huyện --</option>
                                <input type="hidden" id="exist_district" name="exist_district" value="@if( isset($info['district']) ){{$info['district']}}@endif">
                            </select>
                        </div>
                        <!-- <div class="form-group">
                            <input class="form-control required" type="text" id="country" name="country" placeholder="Quốc Gia" value="4">
                            </div> -->
                        <div class="form-group">
                            <input class="form-control required checkhtmltag" type="text" id="phone" name="phone" placeholder="Số điện thoại" value="{{ $info['phone'] }}">
                        </div>
                        <div class="form-group">
                            <input class="form-control required checkhtmltag" type="text" id="email" name="email" placeholder="Email" value="{{ $info['email'] or $merchant->email }}">
                        </div>
                    </div>
                </div>
                <hr>
            </fieldset>
            <center>
                <button type="button" class="btn btn-pink" data-id="{{ $merchant->id }}" id="save-edit-infomerchant">Chỉnh sửa thông tin</button>
            </center>
            {{-- END FORM INFO --}}
        </div>
    </div>
</div>
{{-- Infomation --}}
<div class="edit-store" style="display:none">
    <div class="panel panel-default">
        <div class="panel-heading">Thông tin cửa hàng</div>
        <div class="panel-body">
            {{-- FORM EDIT STORE --}}
            <div class="alert alert-success alert-block fade in">
                <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                <center>
                    <p>Admin không có tác vụ nào trong Panel này. Chỉ Review trạng thái và tình trạng hoạt động cửa hàng để tư vấn nâng cấp các gói Premium nếu cần thiết</p>
                </center>
            </div>
            <fieldset class="create-brand">
                <!-- INCLUDE HTML STORE -->
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
                                            <td>
                                                <h4 class="pink">{{ $row->store_name }}</h4>
                                            </td>
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
                <!-- END HTML STORE -->
            </fieldset>
            {{-- END FORM EDIT STORE --}}
        </div>
    </div>
</div>
{{-- Infomation --}}
<div class="edit-card" style="display:none">
    <div class="panel panel-default">
        <div class="panel-heading">Chỉnh sửa thông tin Card</div>
        <div class="panel-body">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Thông tin thẻ thành viên Merchant</div>
                    <div class="panel-body">
                        @if ($merchant->card_type == 3)
                        <p>Loại thẻ thành viên: <strong>Level</strong></p>
                        <p>Hình thức thẻ thành viên: <strong>{{ getOptionValueById(json_decode($merchant->card_info,true)['value'][0]['id']) }}</strong></p>
                        @elseif ($merchant->card_type == 4)
                        <p>Loại thẻ thành viên: <strong>Chop</strong></p>
                        <p>Hình thức: <strong>{{ getChops(json_decode($merchant->card_info,true)['value'][0]['id']) }}</strong></p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Thông tin thẻ thành viên Merchant</div>
                    <div class="panel-body">
                        @if ($merchant->card_type == 3)
                        <div class="row">
                            <div class="col-lg-7">
                                <p class="align">Giá trị hóa đơn tương ứng 1 điểm:</p>
                            </div>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <p id="value-discount-hidend" style="display:none"></p>
                                    <input type="hidden" id="value-discount-1" value="">
                                    <input type="text" name="valuediscountlevel" data-v-min="0" data-v-max="1000000" id="value-discount-level" class="form-control currentcy" aria-label="Đơn vị VNĐ" placeholder="EX: 1,000" value="{{ $infoCard['value'][0]['value'][0]['unit'] or '' }}">
                                    <span class="input-group-addon">VNĐ</span>
                                </div>
                            </div>
                        </div>
                        {{-- @include('card.level') --}}
                        @if ( json_decode($merchant->card_info,true)['value'][0]['id'] == '10||11||12' )
                        @include('card.level1-1')
                        @elseif ( json_decode($merchant->card_info,true)['value'][0]['id'] == '23||24||25' )
                        @include('card.level2-2')
                        @elseif ( json_decode($merchant->card_info,true)['value'][0]['id'] == '13||14' )
                        @include('card.level3-3')
                        @endif
                        @include('card.ex')
                        @elseif ($merchant->card_type == 4)
                        <div class="row">
                            <div class="col-lg-7">
                                <p class="align">Giá trị hóa đơn tương ứng 1 Chops:</p>
                            </div>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <input type="hidden" id="value-discount-1" value="">
                                    <input type="text" name="point" data-v-min="0" data-v-max="1000000" id="value-discount" class="form-control currentcy" disabled="disabled" aria-label="Đơn vị VNĐ" placeholder="EX: 200,000" value="{{ $infoCard['value'][0]['value'][0]['unit'] or '' }}" >
                                    <span class="input-group-addon">VNĐ</span>
                                </div>
                            </div>
                        </div>
                        @if ( json_decode($merchant->card_info,true)['value'][0]['id'] == '15' )
                        @foreach (json_decode($merchant->card_info,true)['value'][0]['value'] as $row)
                        <p>Tích đủ <span class='pink'>{{ $row['point'] }}</span> Chops. Tặng sản phẩm trị giá tối đa <span class='pink'>{{ price($row['bouns']) }}</span> VNĐ</p>
                        @endforeach
                        @elseif ( json_decode($merchant->card_info,true)['value'][0]['id'] == '16' )
                        @foreach (json_decode($merchant->card_info,true)['value'][0]['value'] as $row)
                        <p>Tích đủ <span class='pink'>{{ $row['point'] }}</span> Chops. Giảm giá <span class='pink'>{{ price($row['bouns']) }} </span> % cho lần mua tiếp</p>
                        @endforeach
                        @endif
                        @endif
                        {{-- 
                        <div class="row">
                            <div class="col-lg-7">
                                <p class="align">Giá trị hóa đơn tương ứng 1 điểm:</p>
                            </div>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <p id="value-discount-hidend" style="display:none"></p>
                                    <input type="hidden" id="value-discount-1" value="">
                                    <input type="text" name="valuediscountlevel" data-v-min="0" data-v-max="1000000" id="value-discount-level" class="form-control currentcy" aria-label="Đơn vị VNĐ" placeholder="EX: 1,000" value="{{ $infoCard['value'][0]['value'][0]['unit'] or '' }}">
                                    <span class="input-group-addon">VNĐ</span>
                                </div>
                            </div>
                        </div>
                        <br>
                        @if ( json_decode($merchant->card_info,true)['value'][0]['id'] == '15' )
                        @foreach (json_decode($merchant->card_info,true)['value'][0]['value'] as $row)
                        <p>Tích đủ <span class='pink'>{{ $row['point'] }}</span> Chops. Tặng sản phẩm trị giá tối đa <span class='pink'>{{ price($row['bouns']) }}</span> VNĐ</p>
                        @endforeach
                        @elseif ( json_decode($merchant->card_info,true)['value'][0]['id'] == '16' )
                        @foreach (json_decode($merchant->card_info,true)['value'][0]['value'] as $row)
                        <p>Tích đủ <span class='pink'>{{ $row['point'] }}</span> Chops. Giảm giá <span class='pink'>{{ price($row['bouns']) }} </span> % cho lần mua tiếp</p>
                        @endforeach
                        @endif --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Cấu hình lại thẻ cho thành viên</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-7">
                                <p class="align">Giá trị hóa đơn tương ứng 1 Chops:</p>
                            </div>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <input type="hidden" id="value-discount-1" value="">
                                    <input type="text" name="point" data-v-min="0" data-v-max="1000000" id="value-discount" class="form-control currentcy"  aria-label="Đơn vị VNĐ" placeholder="EX: 200,000" value="{{ $infoCard['value'][0]['value'][0]['unit'] or '' }}" >
                                    <span class="input-group-addon">VNĐ</span>
                                </div>
                            </div>
                        </div>
                        @if ( json_decode($merchant->card_info,true)['value'][0]['id'] == '15' )
                        <div class="chops-option-gift-1">
                            <hr>
                            <div class="table-responsive" style="overflow-y: hidden; ">
                                <table class="table" id="list-gift-1">
                                    <thead>
                                        <tr>
                                            <th style="width:40%">Số Chops cần tích lũy
                                            </th>
                                            <th style="width:40%">Giá trị SP được đổi
                                            </th>
                                            <th style="width:20%"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="customize-input-point">
                                        <tr>
                                            <td>
                                                <div class="form-group">
                                                    <select class="form-control choice-stick-1" id="select-chop-gift-1">
                                                        <option value="">-- Số lượng Chops --</option>
                                                        @for ($i = 1; $i <= 15 ; $i++) { 
                                                        <option value="{{ $i }}">{{ $i }} Chops</option>
                                                        @endfor
                                                    </select>
                                                    <input type="hidden" id="select-chop-gift-1-1" value="" >
                                                    <input type="hidden" id="select-chop-gift-1-2" value="" >
                                                    <input type="hidden" id="select-chop-gift-1-3" value="" >
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="hidden" id="value-discount-gift-1-1" value="" >
                                                    <input type="hidden" id="value-discount-gift-1-2" value="" >
                                                    <input type="hidden" id="value-discount-gift-1-3" value="" >
                                                    <input type="hidden" id="value-discount-1" value="">
                                                    <input type="text" name="point" data-v-min="0" data-v-max="10000000" id="value-discount-gift-1" class="form-control currentcy" aria-label="Đơn vị VNĐ" placeholder="EX: 100,000">
                                                    <span class="input-group-addon">VNĐ</span>
                                                </div>
                                            </td>
                                            <td style="position:relative">
                                                <input type="button" name="" id="create-option-gift-1" class="form-control btn btn-pink" value="Tạo">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <center>
                            <button type="button" data-merchant-id="{{$merchantId}}" data-card-type="4" class="btn btn-pink edit-card-info-admin-chop">Chỉnh sửa hạng thẻ</button>
                        </center>
                        @elseif ( json_decode($merchant->card_info,true)['value'][0]['id'] == '16' )
                        <div class="chops-option-gift-2">
                            <hr>
                            <div class="table-responsive" style="overflow-y: hidden; ">
                                <table class="table" id="list-gift-2">
                                    <thead>
                                        <tr>
                                            <th style="width:40%">Số Chops cần tích lũy
                                            </th>
                                            <th style="width:40%">Giá trị (%) giảm giá
                                            </th>
                                            <th style="width:20%"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="customize-input-point">
                                        <tr>
                                            <td>
                                                <div class="form-group">
                                                    <select class="form-control choice-stick" id="select-chop-gift-2">
                                                        <option value="">-- Số lượng Chops --</option>
                                                        @for ($i = 1; $i <= 15 ; $i++) { 
                                                        <option value="{{ $i }}">{{ $i }} Chops</option>
                                                        @endfor
                                                    </select>
                                                    <input type="hidden" id="select-chop-gift-2-1" value="" >
                                                    <input type="hidden" id="select-chop-gift-2-2" value="" >
                                                    <input type="hidden" id="select-chop-gift-2-3" value="" >
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="hidden" id="value-discount-gift-2-1" value="" >
                                                    <input type="hidden" id="value-discount-gift-2-2" value="" >
                                                    <input type="hidden" id="value-discount-gift-2-3" value="" >
                                                    <input type="text" name="point" data-v-min="0" data-v-max="100" id="value-discount-gift-2" class="form-control currentcy" aria-label="Đơn vị VNĐ" placeholder="EX: 10">
                                                    <span class="input-group-addon">%</span>
                                                </div>
                                            </td>
                                            <td style="position:relative">
                                                <input type="submit" name="" id="create-option-gift-2" class="form-control btn btn-pink" value="Tạo">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <center>
                            <button type="button" data-merchant-id="{{$merchantId}}" data-card-type="5" class="btn btn-pink edit-card-info-admin-chop">Chỉnh sửa hạng thẻ</button>
                        </center>
                        @endif
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
@stop
@section('js')
{{-- <script src="{{ URL('') }}/build/jquery.steps.js"></script>
<script src="{{ URL('') }}/assets/script/initialize.js"></script> --}}
<script type="text/javascript">
    jQuery(function($) {
    $('.currentcy').autoNumeric('init');
    });
</script>
@stop
