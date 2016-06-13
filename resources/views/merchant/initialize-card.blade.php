@extends('layouts.master')
@section('css')
<link href="{{ URL('') }}/css/jquery.steps.css" rel="stylesheet">
@stop

@section('title')
    <div class="title-pages">
        <h2>
            @if(Auth::merchant()->get()->package == 0)
                KHỞI TẠO THẺ
            @elseif (Auth::merchant()->get()->active == 2)
                ĐANG CHỜ DUYỆT
            @elseif (Auth::merchant()->get()->active == 1)
                CẤU HÌNH THẺ
            @endif

        </h2>
    </div>
@stop

@section('content')

@if ($merchant->package == 0)

<form id="example-advanced-form" action="#">
<!-- step 1 -->
<h3>Tạo thương hiệu</h3>
<fieldset class="create-brand">
    <p class="step-title">Bước 1: Vui lòng cho chúng tôi biết thông tin về bạn và thương hiệu của bạn.</p>
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
                        <input class="form-control" id="background-color" type="color" value="{{ $merchant->color or '#f94876' }}">
                    </span>

                    <span class="input-group-btn">
                        <input class="form-control" type="text" id="choice-color" name="color" value="{{ $merchant->color or '#f94876' }}">
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
                <div class="row">
                    <center>
                        <div class="col-md-12 contentImage">
                            @if ($merchant->logo)
                            <img class="img-logo" id="image_logo_btn" src="{{ URL('').'/'.$merchant->logo }}">
                            @else
                            <div class="img-logo">
                                <p class="position-text-avatar">Add logo <br> 300 x 300px</p>
                            </div>
                            @endif
                        </div>
                        <input type="file" name="logo" id="image_logo" value="" class="upload-img" />
                        <input type="hidden" id="checkLogoExist" value="{{ $merchant->logo }}"/>
                        <input type="hidden" id="checkLogoExistFirst" name="checklogo" value="{{ $merchant->logo }}">
                        <input type="hidden" id="current_i_m_g_s" value="{{ $merchant->logo }}">
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
                <input class="form-control required checkhtmltag" type="text" id="" name="fullname" placeholder="Họ và tên" value="{{ $info['fullname'] or Auth::merchant()->get()->information }}">
            </div>
            <div class="form-group">
                <input class="form-control required checkhtmltag" type="text" id="" name="role" placeholder="Vai trò" value="{{ $info['role'] }}">
            </div>
            <div class="clearfix"></div>
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
                            @for ($i = 1900; $i <= 2015 ; $i++) { 
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
                <input class="form-control required checkhtmltag" type="text" id="email" name="email" placeholder="Email" value="{{ $info['email'] or Auth::merchant()->get()->email }}">
            </div>
        </div>
    </div>
    <hr>
</fieldset>
<!-- end steep 1 -->



<!-- Steep 2 -->
<h3>Tạo hạng thẻ &nbsp;&nbsp;</h3>
<fieldset class="create-brand">
    <p class="step-title">Bước 2: Tại đây vui lòng thiết lập hình thức thẻ thành viên cho thương hiệu của bạn</p>
    <div class="row">
        <!-- LEFT -->
        <div class="col-lg-6">
            <h1 class="pink">Chọn loại thẻ thành viên</h1>
            <hr>
            <p class="note-option no-margin">Vui lòng chọn 1 trong 2 loại thẻ bên dưới:</p>




            <ul class="select-type">
                <li class="type-chops" data-type="0"><span></span><a href="#">Loại thẻ Chops </a><a href="#" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="{!! trans('infomation.IF_01') !!}"><i class="fa fa-info-circle default"></i></a>
                </li>
                <li class="type-levels" data-type="1"><span class="current1"></span><a href="#">Loại thẻ Levels </a><a href="#" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="{{ trans('infomation.IF_02') }}"><i class="fa fa-info-circle default"></i></a>
                </li>
            </ul>
            <ul class="select-type type-levels-option">
                <p class="note-option">Vui lòng chọn 1 trong 3 hình thức đặt tên thẻ bên dưới</p>
                <li class="type-levels-1" data-level-option="1"><span class="current1"></span><a href="#">03 hạng thẻ: Vàng, Bạc, Đồng </a>
                </li>
                <li class="type-levels-2" data-level-option="2"><span></span><a href="#">03 hạng thẻ: VVIP, VIP, Thành viên </a>
                </li>
                <li class="type-levels-3" data-level-option="3"><span></span><a href="#">02 hạng thẻ: VIP, Thành viên </a>
                </li>
            </ul>




            <!-- CONTENT CHOPS -->
            
            @include('card.chops')

            <!-- END CONTENT CHOPS -->
            
            
            <!-- CONTENT CARD LEVEL -->

            @include('card.level')

            <!-- END CONTENT LEVEL -->


        </div>
        <!-- RIGHT -->
        <div class="col-lg-6 status-display">
            <h1 class="pink">Demo trên ứng dụng của thành viên</h1>
            <hr>
            <!-- Demo mobile  -->
            <div class="demo-mobile-content" id="level">
                <img src="{{ URL('') }}/images/demo.png" class="background-mobile">
                <div class="demo-mobile-head">
                    <a href="#" class="glyphicon glyphicon-chevron-left"></a>Đăng ký thành viên
                    <div class="demo-icon-trademark"><img src="{{ URL('/') }}/images/icon-cgv.png" alt="icon-trademark">
                    </div>
                </div>
                <div class="demo-mobile-main-content">
                    <p style="color:#f94876; font-weight:bold;">Đăng ký thành viên mới "New" và nhận ngay:</p>
                    <p style="text-align:center; font-size: 24px; font-weight:bold">5%</p>
                    <p style="text-align:center; margin-left: 15px; margin-right: 15px;">Giảm giá cho lần tiêu dùng tiếp theo tại cửa hàng</p>
                    <p style="color:#f94876; font-weight:bold;">Chương trình thẻ thành viên:</p>
                    Hạng thẻ: <span id="demo-level-vvip">Vàng</span><span id="demo-level-vip">Bạc</span><span id="demo-level-newbie">Đồng</span>
                    <p style="margin-top: 10px;">Điểm tích lũy: <span style="margin-left: 10px;">15%</span><span style="margin-left:20px;">10%</span><span style="margin-left:20px;">5%</span>
                    </p>
                    <p style="color:#f94876; font-weight:bold">Xem chi tiết chương trình tại đây <a href="#" style="color: #f94876;"><a href="#"><i class="fa fa-info-circle"></i></a></a>
                    </p>
                    <button type="button" class="btn">Đăng ký</button>
                </div>
            </div>
            <!-- Demo chops -->
            <div class="demo-chops-card" id="chops" style="display:none">

                <div class="box-show-info">
                    <div class="box-avatar">
                        <img src="{{ URL('') }}/{{ Auth::merchant()->get()->logo ? Auth::merchant()->get()->logo : 'upload/merchant-logo/default.png' }}" alt="logo" class="img-circle">
                    </div>
                    <i class="member-demo fa fa-user fa-4x"></i>
                    <p class="member-name">MEMBER NAME</p>
                </div>

                <div class="box-show-chop">
                    <div class="foreach-chop">
                    @for ($i = 1; $i <= 15 ; $i++)
                        <div class="sticker tick-{{ $i }}"></div>
                    @endfor
                    </div>
                </div>

                <!-- <div class="demo-front-chops-card">
                    <img src="{{ URL('') }}/images/logo-default.png" alt="logo" class="img-circle">
                    <i class="fa fa-user fa-4x"></i>
                    <p>member name</p>
                </div>
                <div class="demo-back-chops-card">
                    <div class="tick-star-1">
                        <span class="tick-1"></span>
                        <span class="tick-2"></span>
                        <span class="tick-3"></span>
                        <span class="tick-4"></span>
                        <span class="tick-5"></span>
                    </div>
                    <div class="tick-star-2">
                        <span class="tick-6"></span>
                        <span class="tick-7"></span>
                        <span class="tick-8"></span>
                        <span class="tick-9"></span>
                        <span class="tick-10"></span>
                    </div>
                    <div class="tick-star-3">
                        <span class="tick-11"></span>
                        <span class="tick-12"></span>
                        <span class="tick-13"></span>
                        <span class="tick-14"></span>
                        <span class="tick-15"></span>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</fieldset>
<!-- end Step 2 -->




<!-- step 3 -->
<h3>Tạo địa chỉ shop</h3>
<fieldset class="create-brand">
    <p class="step-title">Bước 3: Tạo địa chỉ cửa hàng và tài khoản đăng nhập của mỗi cửa hàng. Bạn có thể thay đổi nó về sau</p>
    
    <!-- INCLUDE HTML STORE -->
    
        @include('card.store')

    <!-- END HTML STORE -->

</fieldset>
<!-- end step 3 -->

<!-- steep 4 -->
<h3>Xác nhận &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h3>
<fieldset class="create-brand">
    <p class="step-title">Bước 4: Vui lòng kiểm tra lại thông tin liên hệ một lần nữa!</p>
    <div class="col-lg-6">
    <h1 class="pink">Đăng ký gói dịch vụ <span class="pricing-packages"></span></h1>
        <hr>

        <div class="status-packages">
            <p>Hệ thống kiểm tra hiện tại bạn đang có <span class="count-store pink"></span> cửa hàng. Và sẽ tự động đăng ký cho quý khách gói dịch vụ <span class="pricing-packages pink"></span></p>
            <p class="show-only-free"></p>
            

        </div>
        <center>    
        <h4>Tìm hiểu thêm về các <a href="#" class='pink' data-toggle="modal" data-target="#showPackagesInfo">Gói dịch vụ AbbyCard</a></h4>
        </center>
    </div>



    <div class="col-lg-6">
    <h1 class="pink">Xác nhận thông tin người liên hệ</h1>
        <hr>
            <div class="form-group">
                <input class="form-control" type="text" id="info-merchant-fullname" name="fullname" placeholder="Họ và tên" value="{{ json_decode(Auth::merchant()->get()->information,true)['fullname'] }}">
            </div>

            <div class="form-group">
                <input class="form-control" type="text" id="info-merchant-phone" name="phone" placeholder="Điện thoại" value="{{ json_decode(Auth::merchant()->get()->information,true)['phone'] }}">
            </div>

            <div class="form-group">
                <input class="form-control" type="text" id="info-merchant-email" name="email" placeholder="Email liên hệ" value="{{ json_decode(Auth::merchant()->get()->information,true)['email'] }}">
            </div>

            <div class="form-group">
                <textarea name="content" id="info-merchant-content" class="form-control" rows="5" placeholder="Nội dung yêu cầu bổ sung"></textarea>
            </div>
    </div>



</fieldset>
<!-- end steep 4 -->
</form>

@else

    

    @if ( $merchant->active == 2 )
    <!-- <div class="alert alert-info alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <p style="text-align:center">Hệ thống đang xử lý yêu cầu đăng ký gói dịch vụ <strong class="pink">[ {{ checkPackagePending(Auth::merchant()->get()->package_status) }} ]</strong> của quý khách. Vui lòng đợi trong vòng 3h làm việc hoặc liên hệ trực tiếp với chuyên viên của chúng tôi tại SDT 04362979933. Xin cảm ơn!</p>
    </div> -->
    <div class="alert alert-info alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <p style="text-align:center">Thương hiệu của bạn đang được duyệt bởi Administrator. Vui lòng đợi chúng tôi xử lý trong vòng 3h làm việc hoặc liên hệ trực tiếp với chuyên viên của chúng tôi tại SDT 0462592111. Xin cảm ơn!</p>
    </div>
        @include('html.welcome')

    @else
    

    <!-- Review info-->
    <fieldset class="create-brand">
        <div class="col-lg-12">
            <div class="row">
            <!-- LEFT -->
            <div class="col-lg-6">
                <h1 class="pink">Thông tin thương hiệu</h1>
                <hr>
                
                <p>Tên thương hiệu: <strong>{{ Auth::merchant()->get()->name }}</strong></p>
                <p>Lĩnh vực: <strong>{{ getField(Auth::merchant()->get()->field) }}</strong></p>
                <p>Thông tin liên hệ: <strong>{{ json_decode(Auth::merchant()->get()->information,true)['fullname'] }}</strong></p>
                <p>Vai trò:  <strong>{{ json_decode(Auth::merchant()->get()->information,true)['role'] }}</strong></p>
                <p>Ngày sinh: <strong>{{ formatBirthDay(json_decode(Auth::merchant()->get()->information,true)['day'], json_decode(Auth::merchant()->get()->information,true)['month'], json_decode(Auth::merchant()->get()->information,true)['year']) }}</strong></p>
                <p>Địa chỉ: <strong>{{ json_decode(Auth::merchant()->get()->information,true)['address'] }}</strong></p>
                <p>Số điện thoại: <strong>{{ json_decode(Auth::merchant()->get()->information,true)['phone'] }}</strong></p>
                <p>Email: <strong>{{ json_decode(Auth::merchant()->get()->information,true)['email'] }}</strong></p>

            </div>
            <!-- RIGHT -->
            <div class="col-lg-6">
                <h1 class="pink">Thông tin thẻ</h1>
                <hr>

                @if (Auth::merchant()->get()->card_type == 3)
                    <p>Loại thẻ thành viên: <strong>Level</strong></p>
                    <p>Hình thức thẻ thành viên: <strong>{{ getOptionValueById(json_decode(Auth::merchant()->get()->card_info,true)['value'][0]['id']) }}</strong></p>
                @elseif (Auth::merchant()->get()->card_type == 4)
                    <p>Loại thẻ thành viên: <strong>Chop</strong></p>
                    <p>Hình thức: <strong>{{ getChops(json_decode(Auth::merchant()->get()->card_info,true)['value'][0]['id']) }}</strong></p>
                @endif

                @if (Auth::merchant()->get()->card_type == 3)
                    @include('card.level')
                    
                    @if ( json_decode(Auth::merchant()->get()->card_info,true)['value'][0]['id'] == '10||11||12' )
                        @include('card.level1')
                    @elseif ( json_decode(Auth::merchant()->get()->card_info,true)['value'][0]['id'] == '23||24||25' )
                        @include('card.level2')
                    @elseif ( json_decode(Auth::merchant()->get()->card_info,true)['value'][0]['id'] == '13||14' )
                        @include('card.level3')
                    @endif

                    @include('card.ex')



        @elseif (Auth::merchant()->get()->card_type == 4)
            <h1 class="pink">Thanh toán bao nhiêu tiền thì được tặng 1 Chop?</h1>
            <hr>
            <div class="row">
                <div class="col-lg-7">
                        <p class="align">Giá trị hóa đơn tương ứng 1 Chops:</p>
                </div>
                <div class="col-lg-5">
                    <div class="input-group">
                        <input type="hidden" id="value-discount-1" value="">
                        <input type="text" name="point" data-v-min="0" data-v-max="1000000" id="value-discount" class="form-control currentcy" disabled="disabled" style="background: #eee" aria-label="Đơn vị VNĐ" placeholder="EX: 200,000" value="{{ $infoCard['value'][0]['value'][0]['unit'] or '' }}" >
                        <span class="input-group-addon">VNĐ</span>
                    </div>
                </div>
            </div>

            @if ( json_decode(Auth::merchant()->get()->card_info,true)['value'][0]['id'] == '15' )
                @include('card.chop1')


                    @foreach (json_decode(Auth::merchant()->get()->card_info,true)['value'][0]['value'] as $row)
                        <p>Tích đủ <span class='pink'>{{ $row['point'] }}</span> Chops. Tặng sản phẩm trị giá tối đa <span class='pink'>{{ price($row['bouns']) }}</span> VNĐ</p>
                    @endforeach


            @elseif ( json_decode(Auth::merchant()->get()->card_info,true)['value'][0]['id'] == '16' )
                @include('card.chop2')
                    @foreach (json_decode(Auth::merchant()->get()->card_info,true)['value'][0]['value'] as $row)
                        <p>Tích đủ <span class='pink'>{{ $row['point'] }}</span> Chops. Giảm giá <span class='pink'>{{ price($row['bouns']) }} </span> % cho lần mua tiếp</p>
                    @endforeach
            @endif


        @endif





                
                
            </div>
        </div>
        </div>
    </fieldset>


    <fieldset class="create-brand">    
    <!-- INCLUDE HTML STORE -->
    
        @include('card.store')

    <!-- END HTML STORE -->
    </fieldset>



    <!-- INCLUDE HTML INFO CHOICE CARD -->
    <fieldset class="create-brand">    
    
        

    </fieldset>
    <!-- INCLUDE HTML INFO CHOICE CARD -->


    


    @endif

    
    

@endif

@stop



@section('js')
<script src="{{ URL('') }}/build/jquery.steps.js"></script>
<script src="{{ URL('') }}/assets/script/initialize.js"></script>
<script type="text/javascript">
    jQuery(function($) {
    $('.currentcy').autoNumeric('init');
});
</script>
@stop
