@extends('layouts-admin.master')
@section('meta-title')
    <title>Update Boo Merchant - Super Administrator AbbyCard</title>
@stop
@section('css')
@stop
@section('title')
<div class="title-pages">
    <h2>Update Boo Merchant</h2>
</div>
@stop
@section('content')
<div class="account-manage-container">

<form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="">

    <input type="hidden" name="_token" value="{{ csrf_token()}}">
    <h1 class="title-h1">Cập nhật tài khoản {{ $data->name }}</h1>
    <div class="clearfix"></div>

        <div class="col-lg-6">
            <div class="form-group">
                <label>Tên thương hiệu</label>
                <input class="form-control checkhtmltag merchantName" readonly type="text" id="" name="trademark" placeholder="Tên thương hiệu" value="{{ $data->name }}">
            </div>

            <div class="form-group">
                <label>Chọn mã màu cho Background</label>
                <div class="input-group">
                    <span class="input-group-btn" style="width:40%">
                        <input class="form-control background-color" id="background-color" type="color" name="background-color" value="{{ $data->color }}">
                    </span>

                    <span class="input-group-btn" style="width:40%">
                        <input class="form-control choice-color" type="text" id="" name="" value="{{ $data->color }}">
                    </span>
                    <span class="input-group-btn" style="width:20%">
                        <input type="button" id="reset-setting-color" class="btn" value="Reset">
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label>Chọn mã màu cho Text</label>
                <div class="input-group">
                    <span class="input-group-btn" style="width:40%">
                        <input class="form-control background-color text-color" id="text-color" type="color" name="text-color" value="{{ $data->text_color }}">
                    </span>

                    <span class="input-group-btn" style="width:40%">
                        <input class="form-control choice-color choice-text-color" type="text" id="" name="" value="{{ $data->text_color }}">
                    </span>
                    <span class="input-group-btn" style="width:20%">
                        <input type="button" id="reset-setting-text-color" class="btn" value="Reset">
                    </span>
                </div>
            </div>

            <div class="form-group">
                <input type="hidden" id="nowLogo" value="{{ $data->logo }}"/>
                <div class="row">
                    <center>
                        <div class="col-md-12 contentImage">

                            @if($data->logo != "")
                                <img class="img-logo" src="{{ URL('').'/'. $data->logo }}">
                            @else
                                <div class="img-logo">
                                    <p class="position-text-avatar">Add logo <br> 300 x 300px</p>
                                </div>
                            @endif
                        </div>
                        <input type="file" name="logo" id="image_logo" value="" class="upload-img" />
                        <input type="hidden" id="checkLogoExist" value="{{ $data->logo }}"/>
                    </center>
                </div>
            </div>
        
        </div>


        <div class="col-lg-6">
            <div class="form-group">
                <label>Mô tả hạng thẻ</label>
                <textarea name="" rows="10" id="boo-card-option" class="form-control" placeholder="Mô tả hạng thẻ">{{ $data->card_info }}</textarea>
            </div>

            <div class="form-group">
                <select class="form-control" id="changeStatus" value="">
                    <option @if($data->active == 1) selected @endif value="1">Active</option>
                    <option @if($data->active == 3) selected @endif value="3">Inactive</option>
                </select>
            </div>


        </div>
        <center>
            <button type="submit" class="btn btn-pink" data-id="{{ $data->id }}" id="updateBooMerchant">Update Boo Merchant</button>
        </center>


    </form>
</div>

@stop
@section('js')
<script src="{{ URL('') }}/assets/script/partner.js"></script>
@stop
