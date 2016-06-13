@extends('layouts-account.master')
@section('title')
    <title>AbbyCard - Register</title>
@stop
@section('actions')
    <div class="form-group" style="margin-top:5px;">
        <p>Nếu bạn đã có tài khoản vui lòng <a href="{{ URL('login') }}">Đăng nhập</a></p>
    </div>
@stop

@section('header')
    <h2>Đăng ký đối tác <img style="margin-top: -10px;" src="{{ URL('images/logo/logo30x30.png') }}"> <span class="abby-card-home">AbbyCard</span></h2>
@stop
@section('form')
    <div class="form-bottom">
        <form class="form-signin" id="myForm" action="{{ URL('register/merchant') }}" method="post">
            @include('layouts.alert')
            {{ csrf_field() }}
            <div class="form-group">
                <label class="sr-only" >Họ tên</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Họ tên ..." class="form-name form-control" id="form-name">
            </div>

            <div class="form-group">
                <label class="sr-only">Email</label>
                <input type="text" name="email" value="{{ old('email') }}" placeholder="Email ..." class="form-username form-control" id="form-email">
            </div>
            
            <div class="form-group">
                <label class="sr-only">Mật khẩu</label>
                <input type="password" name="password" value="{{ old('password') }}" placeholder="Mật khẩu ..." class="form-username form-control" id="form-password">
            </div>
            
            <div class="form-group">
                <label class="sr-only" for="form-username">Xác nhận mật khẩu</label>
                <input type="password" name="repassword" value="{{ old('repassword') }}" placeholder="Xác nhận mật khẩu ..." class="form-username form-control" id="form-repassword">
            </div>

            <button type="submit" class="btn"><i class="fa fa-key"></i> Đăng ký</button>
            
            <div class="form-group" style="margin-top:5px;">
                <center>
                    <p>Nếu bạn đã có tài khoản vui lòng <a href="{{ URL('login') }}">Đăng nhập</a></p>
                </center>
            </div>
            

        </form>
    </div>
@stop
@section('js')
    <script src="{{ url('/assets/script/account-manage.js')}}"></script>
@stop
