@extends('layouts-admin.master')
@section('css')
@stop
@section('title')
<div class="title-pages">
    <h2>Sync-Account Merchant</h2>
</div>
@stop
@section('content')
<div class="account-manage-container">

<form enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="">

    <input type="hidden" name="_token" value="{{ csrf_token()}}">
    <h1 class="title-h1">Tạo mới</h1>
    <div class="clearfix"></div>

        <div class="col-lg-6">
            <div class="form-group">
                <input class="form-control checkhtmltag merchantName" type="text" id="" name="trademark" placeholder="Tên thương hiệu" value="">
            </div>

            <div class="form-group">
                <label>Chọn mã màu cho Background</label>
                <div class="input-group">
                    <span class="input-group-btn" style="width:40%">
                        <input class="form-control background-color" id="background-color" type="color" name="background-color" value="#f94876">
                    </span>

                    <span class="input-group-btn" style="width:40%">
                        <input class="form-control choice-color" type="text" id="" name="" value="#f94876">
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
        
        <button type="submit" class="btn pull-right btn-pink" id="addNewMerchant">Khởi tạo New Merchant</button>
        </div>

        <div class="col-lg-6">
            
        </div>


    </form>
</div>

@stop
@section('js')
<script src="{{ URL('') }}/assets/script/partner.js"></script>
@stop
