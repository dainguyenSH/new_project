@extends('layouts-admin.master')
@section('css')
@stop
@section('title')
<div class="title-pages">
    <h2>Sync account merchant</h2>
</div>
@stop
@section('content')
<div class="account-manage-container">

<form action="" id="newPartner">


    <h1 class="title-h1">Cập nhật thông tin tài khoản {{ $merchant->name }}</h1>
    <div class="clearfix"></div>

        <div class="col-lg-6">
            <div class="form-group">
                <input class="form-control checkhtmltag merchantName" readonly type="text" id="" name="trademark" placeholder="Tên thương hiệu" value="{{ $merchant->name }}">
            </div>

            <div class="form-group">
                <label>Chọn mã màu cho Background</label>
                <div class="input-group">
                    <span class="input-group-btn" style="width:40%">
                        <input class="form-control background-color" id="background-color" type="color" value="{{ $merchant->color }}">
                    </span>

                    <span class="input-group-btn" style="width:40%">
                        <input class="form-control choice-color" type="text" id="" name="color" value="{{ $merchant->color }}">
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
                        <input class="form-control background-color text-color" id="text-color" type="color" name="text-color" value="{{ $merchant->text_color }}">
                    </span>

                    <span class="input-group-btn" style="width:40%">
                        <input class="form-control choice-color choice-text-color" type="text" id="" name="" value="{{ $merchant->text_color }}">
                    </span>
                    <span class="input-group-btn" style="width:20%">
                        <input type="button" id="reset-setting-text-color" class="btn" value="Reset">
                    </span>
                </div>
            </div>

            <div class="form-group">
                <input type="hidden" id="currentLogo" value="{{ $merchant->logo }}"/>
                <div class="row">
                    <center>
                        <div class="col-md-12 contentImage">
							@if($merchant->logo != "")
								<img class="img-logo" src="{{ URL('').'/'. $merchant->logo }}">
							@else
								<div class="img-logo">
	                                <p class="position-text-avatar">Add logo <br> 300 x 300px</p>
	                            </div>
							@endif

                            
                        </div>
                        <input type="file" name="logo" id="image_logo" value="" class="upload-img" />
                        <input type="hidden" id="checkLogoExist" value="{{ $merchant->logo }}"/>

                    </center>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <select class="form-control" id="changeStatus" value="">
                    <option @if($merchant->active == 1) selected @endif value="1">Active</option>
                    <option @if($merchant->active == 3) selected @endif value="3">Inactive</option>
                </select>
            </div>
        </div>

    
    <div class="clearfix"></div>
    <h1 class="title-h1">Cấu hình thẻ</h1>
    
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Vui lòng chọn</div>
            <div class="panel-body">
                <div class="checkbox">
                    <label><input type="checkbox" id="checked-point" checked="checked" value="">Point</label> &nbsp; &nbsp;&nbsp;
                    <label><input type="checkbox" id="checked-age" @if(json_decode($merchant->card_info,true)['level'] != null) checked="checked" @endif value="">Age</label>
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
                                    <input type="text" name="" id="" data-v-min="0" data-v-max="100000" class="form-control currentcy unitCard" aria-label="VND" value="{{ json_decode($merchant->card_info,true)['unit'] }}">
                                    <span class="input-group-addon">Điểm</span>
                                </div>
                            </div>
                            

                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <button type="button" class="btn btn-pink" id="addInputs">Thêm hạng thẻ</button>
                            </div>
                        </div>

                        <div class="clearfix"></div>
		                        <div class="form inforCardLevel">
                                    @if (count(json_decode($merchant->card_info,true)['level'] != 0))
    									@foreach (json_decode($merchant->card_info,true)['level'] as $row)
    										<div class="elementCard commonCard">
    			                                <div class="col-lg-2">
    			                                    <div class="form-group">
    			                                        <div class="input-group">
    			                                            <label>Tên hạng thẻ</label>
    			                                            <input type="text" name="" id="" class="form-control namecard" value="{{ $row[0] }}" placeholder="Hạng thẻ">
    			                                        </div>
    			                                    </div>
    			                                </div>


    			                                <div class="col-lg-2">
    			                                    <div class="form-group">
    			                                        <div class="input-group">
    			                                            <label>Điểm từ</label>
    			                                            <input type="text" name="" id="" data-v-min="0" data-v-max="1000000" class="form-control currentcy fromCard" placeholder="Điểm" value="{{ $row[1] }}">
    			                                        </div>
    			                                    </div>
    			                                </div>

    			                                <div class="col-lg-2">
    			                                    <div class="form-group">
    			                                        <div class="input-group">
    			                                            <label>đến</label>
    			                                            <input type="text" name="" id="" data-v-min="0" data-v-max="1000000" class="form-control currentcy toCard" placeholder="Điểm" value="{{ $row[2] }}">
    			                                        </div>
    			                                    </div>
    			                                </div>

    			                                <div class="col-lg-1">
    			                                    <div class="form-group">
    			                                        <label>Giảm giá</label>
    			                                        <div class="input-group">
    			                                            <input type="text" name="" id="" data-v-min="0" data-v-max="100" class="form-control currentcy bonuscard" placeholder="%" value="{{ $row[3] }}">
    			                                        </div>
    			                                    </div>
    			                                </div>

    			                                <div class="col-lg-2">
    			                                    <div class="form-group">
    			                                        <label>Màu nền</label>
    			                                        <div class="input-group">
    			                                            <span class="input-group-btn" style="width:30%">
    			                                                <input class="form-control backgroundCard background-color" id="" type="color" value="{{ $row[4] }}">
    			                                            </span>

    			                                            <span class="input-group-btn" style="width:70%">
    			                                                <input class="form-control choice-color" type="text" id="" name="color" value="{{ $row[4] }}">
    			                                            </span>
    			                                        </div>
    			                                    </div>
    			                                </div>

    			                                <div class="col-lg-2">
    			                                    <div class="form-group">
    			                                        <label>Màu chữ</label>
    			                                        <div class="input-group">
    			                                            <span class="input-group-btn" style="width:30%">
    			                                                <input class="form-control textCard background-color" id="" type="color" value="{{ $row[5] }}">
    			                                            </span>

    			                                            <span class="input-group-btn" style="width:70%">
    			                                                <input class="form-control choice-color" type="text" id="" name="color" value="{{ $row[5] }}">
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
                            			@endforeach
                                    @endif
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
                <div class="panel panel-default form-age" @if(json_decode($merchant->card_info,true)['level'] == null) style="display:none" @endif >
                    <div class="panel-heading">Age</div>
                    <div class="panel-body">

                    <div class="col-lg-3">
                        <div class="form-group">
                            <button type="button" class="btn btn-pink" id="addInputsAge">Thêm hạng thẻ theo tuổi</button>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="form inforCardAge">
						@if (count(json_decode($merchant->card_info,true)['age']) != 0)
    						@foreach(json_decode($merchant->card_info,true)['age'] as $row)
    							<div class="elementAge commonCard">
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label>Tên hạng thẻ</label>
                                                <input type="text" name="" id="" class="form-control namecard" value="{{ $row[0] }}" placeholder="Hạng thẻ">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label>Tuổi từ</label>
                                                <input type="text" name="" id="" data-v-min="0" data-v-max="100" class="form-control currentcy fromCard" placeholder="tuổi" value="{{ $row[1] }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label>đến</label>
                                                <input type="text" name="" id="" data-v-min="0" data-v-max="100" class="form-control currentcy toCard" placeholder="tuổi" value="{{ $row[2] }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-1">
                                        <div class="form-group">
                                            <label>Giảm giá</label>
                                            <div class="input-group">
                                                <input type="text" name{{ $row[0] }}="" id="" data-v-min="0" data-v-max="100" class="form-control currentcy bonuscard" placeholder="%" value="{{ $row[3] }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>Màu nền</label>
                                            <div class="input-group">
                                                <span class="input-group-btn" style="width:30%">
                                                    <input class="form-control backgroundCard background-color" id="" type="color" value="{{ $row[4] }}">
                                                </span>

                                                <span class="input-group-btn" style="width:70%">
                                                    <input class="form-control choice-color" type="text" id="" name="color" value="{{ $row[4] }}">
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>Màu chữ</label>
                                            <div class="input-group">
                                                <span class="input-group-btn" style="width:30%">
                                                    <input class="form-control textCard background-color" id="" type="color" value="{{ $row[5] }}">
                                                </span>

                                                <span class="input-group-btn" style="width:70%">
                                                    <input class="form-control choice-color" type="text" id="" name="color" value="{{ $row[5] }}">
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
    						@endforeach
                        @endif

                    </div>

                        
                    </div>
                </div>
                <button type="" class="btn pull-right btn-pink" data-id="{{ $merchant->id }}" id="updatePartner">Cập nhật cấu hình thẻ</button>
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
