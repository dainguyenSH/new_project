<?php
if (Auth::merchant()->get()) {
    $url = URL('merchant');
    header('Location:' . $url);
    exit;
}
?>

@extends('layouts-account.master')
@section('title')
    <title>AbbyCard - Register</title>
@stop

@section('header')
    <h2>Đăng nhập <img style="margin-top: -10px;" src="{{ URL('images/logo/logo30x30.png') }}"> <span class="abby-card-home">AbbyCard</span></h2>
@stop
@section('form')
    <div class="form-bottom">
        <form method="post" action="{{ URL('login/merchant') }}" class="login-form">
            @include('layouts.alert')
            @if (Request::input('session') == 'expired')

                <div class="alert alert-block alert-danger fade in">
                    <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                    Phiên đăng nhập của bạn đã hết hạn. Vui lòng đăng nhập lại
                </div>

            @endif
            {{ csrf_field() }}
            <div class="form-group">
                <label class="sr-only" for="form-username">Tài khoản</label>
                <input type="text" name="email" value="{{ old('email') }}" placeholder="Địa chỉ Email ..." class="form-username form-control" id="form-username">
            </div>
            <div class="form-group">
                <label class="sr-only" for="form-password">Mật khẩu</label>
                <input type="password" name="password" id="password" placeholder="Mật khẩu ..." class="form-password form-control">
            </div>
            <div class="form-group">
                <input type="checkbox" name="remember"> Ghi nhớ 
            </div>
            <button type="submit" class="btn">Đăng nhập</button>
            <center>
            <p>Nếu bạn chưa có tài khoản vui lòng <a href="{{ URL('register') }}">Đăng ký</a></p>
            <a href="{{ URL('password/email') }}"><p>Quên mật khẩu?</p></a>
        </center>
        </form>
    </div>
@stop
@section('js')
    <script src="{{ url('/assets/script/account-manage.js')}}"></script>
@stop


