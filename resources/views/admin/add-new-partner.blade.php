@extends('layouts-admin.master')
@section('meta-title')
    <title>Add New Sync-Account Merchant - Super Administrator AbbyCard</title>
@stop
@section('css')
@stop
@section('title')
<div class="title-pages">
    <h2>Tạo mới Sync account merchant</h2>
</div>
@stop
@section('content')
<div class="account-manage-container">

<form action="" id="newPartner">


    <h1 class="title-h1">Tạo mới tài khoản Sync-Account Merchant</h1>
    <div class="clearfix"></div>

        <div class="col-lg-6">
            <div class="form-group">
                <input class="form-control checkhtmltag merchantName" type="text" id="" name="trademark" placeholder="Tên thương hiệu" value="">
            </div>

            <div class="form-group">
                <label>Chọn mã màu cho Background</label>
                <div class="input-group">
                    <span class="input-group-btn" style="width:40%">
                        <input class="form-control background-color" id="background-color" type="color" value="#f94876">
                    </span>

                    <span class="input-group-btn" style="width:40%">
                        <input class="form-control choice-color" type="text" id="" name="color" value="#f94876">
                    </span>
                    <span class="input-group-btn" style="width:20%">
                        <input type="button" id="reset-setting-color" style="width:100%" class="btn" value="Reset">
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label>Chọn mã màu cho Text</label>
                <div class="input-group">
                    <span class="input-group-btn" style="width:40%">
                        <input class="form-control background-color text-color" id="text-color" type="color" name="text-color" value="#ffffff">
                    </span>

                    <span class="input-group-btn" style="width:40%">
                        <input class="form-control choice-color choice-text-color" type="text" id="" name="" value="#ffffff">
                    </span>
                    <span class="input-group-btn" style="width:20%">
                        <input type="button" id="reset-setting-text-color" class="btn" value="Reset">
                    </span>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <center>
                        <div class="col-md-12 contentImage">


                            <div class="img-logo">
                                <p class="position-text-avatar">Add logo <br> 300 x 300px</p>
                            </div>
                        </div>
                        <input type="file" name="logo" id="image_logo" value="" class="upload-img" />
                        <input type="hidden" id="checkLogoExist" value=""/>
                    </center>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            
        </div>

    
    <div class="clearfix"></div>
    <h1 class="title-h1">Cấu hình thẻ Sync-Account Merchant</h1>
    
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Vui lòng chọn</div>
            <div class="panel-body">
                <div class="checkbox">
                    <label><input type="checkbox" id="checked-point" checked="checked" value="">Point</label> &nbsp; &nbsp;&nbsp;
                    <label><input type="checkbox" id="checked-age" value="">Age</label>
                </div>
            </div>
        </div>
        

    </div>

    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Nhập thông tin</div>
            <div class="panel-body">
                <!-- FORM POINT -->
                <div class="panel panel-default form-point">
                    <div class="panel-heading">Point</div>
                    <div class="panel-body">
                        <div class="col-lg-6">

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="" id="" data-v-min="0" data-v-max="100000" class="form-control currentcy unitCard" aria-label="VND" value="">
                                    <span class="input-group-addon">VNĐ</span>
                                </div>
                            </div>
                            

                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                            <button type="button" class="btn btn-info" id="addInputs"><i class="fa fa-plus"></i> Thêm mới hạng thẻ theo điểm</button>
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="form inforCardLevel">


                        </div>
                        
                        <div class="orgrion">
                            <div class="elementCard commonCard" style="display:none;">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label>Tên hạng thẻ</label>
                                            <input type="text" name="" id="" class="form-control namecard" value="" placeholder="Hạng thẻ">
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label>Điểm từ</label>
                                            <input type="text" name="" id="" data-v-min="0" data-v-max="1000000" class="form-control currentcy fromCard" placeholder="Điểm" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label>đến</label>
                                            <input type="text" name="" id="" data-v-min="0" data-v-max="1000000" class="form-control currentcy toCard" placeholder="Điểm" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-1">
                                    <div class="form-group">
                                        <label>Giảm giá</label>
                                        <div class="input-group">
                                            <input type="text" name="" id="" data-v-min="0" data-v-max="100" class="form-control currentcy bonuscard" placeholder="%" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label>Màu nền</label>
                                        <div class="input-group">
                                            <span class="input-group-btn" style="width:30%">
                                                <input class="form-control backgroundCard background-color" id="" type="color" value="#f94876">
                                            </span>

                                            <span class="input-group-btn" style="width:70%">
                                                <input class="form-control choice-color" type="text" id="" name="color" value="#f94876">
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label>Màu chữ</label>
                                        <div class="input-group">
                                            <span class="input-group-btn" style="width:30%">
                                                <input class="form-control textCard background-color" id="" type="color" value="#ffffff">
                                            </span>

                                            <span class="input-group-btn" style="width:70%">
                                                <input class="form-control choice-color" type="text" id="" name="color" value="#ffffff">
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-1">
                                    <label>&nbsp;</label>
                                    <div class="input-group">
                                        <button class="btn btn-danger removeCard">Xóa</button>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                
                            </div>

                            {{-- AGE --}}
                            <div class="elementAge commonCard" style="display:none;">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label>Tên hạng thẻ</label>
                                            <input type="text" name="" id="" class="form-control namecard" value="" placeholder="Hạng thẻ">
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label>Tuổi từ</label>
                                            <input type="text" name="" id="" data-v-min="0" data-v-max="100" class="form-control currentcy fromCard" placeholder="tuổi" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label>đến</label>
                                            <input type="text" name="" id="" data-v-min="0" data-v-max="100" class="form-control currentcy toCard" placeholder="tuổi" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-1">
                                    <div class="form-group">
                                        <label>Giảm giá</label>
                                        <div class="input-group">
                                            <input type="text" name="" id="" data-v-min="0" data-v-max="100" class="form-control currentcy bonuscard" placeholder="%" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label>Màu nền</label>
                                        <div class="input-group">
                                            <span class="input-group-btn" style="width:30%">
                                                <input class="form-control backgroundCard background-color" id="" type="color" value="#f94876">
                                            </span>

                                            <span class="input-group-btn" style="width:70%">
                                                <input class="form-control choice-color" type="text" id="" name="color" value="#f94876">
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label>Màu chữ</label>
                                        <div class="input-group">
                                            <span class="input-group-btn" style="width:30%">
                                                <input class="form-control textCard background-color" id="" type="color" value="#ffffff">
                                            </span>

                                            <span class="input-group-btn" style="width:70%">
                                                <input class="form-control choice-color" type="text" id="" name="color" value="#ffffff">
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-1">
                                    <label>&nbsp;</label>
                                    <div class="input-group">
                                        <button class="btn btn-danger removeCard">Xóa</button>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                
                            </div>
                        </div>
                </div>
            </div>
                <!-- END FORM POINT -->
                
                <!-- FORM AGE -->
                <div class="panel panel-default form-age" style="display:none">
                    <div class="panel-heading">Age</div>
                    <div class="panel-body">

                    <div class="col-lg-3">
                        <div class="form-group">
                        <button type="button" class="btn btn-info" id="addInputsAge"><i class="fa fa-plus"></i> Thêm hạng thẻ theo tuổi</button>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="form inforCardAge">


                    </div>

                        
                    </div>
                </div>
                <button type="" class="btn pull-right btn-pink" id="createPartner">Khởi tạo tài khoản Sync-Account Merchant</button>
                <!-- END FORM AGE -->
            </div>
        </div>
            
        
    </div>

    </form>
</div>

@stop
@section('js')
<script src="{{ URL('') }}/assets/script/partner.js"></script>
@stop
