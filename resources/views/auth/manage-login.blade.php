<?php
if (Auth::store()->get()) {
    $url = URL('manage');
    header('Location:' . $url);
    exit;
}
?>

@extends('layouts-account.master')
@section('title')
    <title>AbbyCard - Register</title>
@stop

@section('header')
    <h2>Đăng nhập thu ngân <img style="margin-top: -10px;" src="{{ URL('images/logo/logo30x30.png') }}"> <span class="abby-card-home">AbbyCard</span></h2>
@stop
@section('form')
    <div class="form-bottom">
        <form method="post" class="login-form">
            @include('layouts.alert')
            {{ csrf_field() }}
            <div class="form-group">
                <label class="sr-only" for="form-username">Tài khoản</label>
                <input type="text" name="email" value="{{ old('email') }}" placeholder="Tài khoản cửa hàng ..." class="form-username form-control" id="form-username">
            </div>
            <div class="form-group">
                <label class="sr-only" for="form-password">Mật khẩu</label>
                <input type="password" name="password" id="password" placeholder="Mật khẩu cửa hàng ..." class="form-password form-control">
            </div>
            <div class="form-group">
                <input type="checkbox" name="remember"> Ghi nhớ 
            </div>
            <button type="submit" class="btn">Đăng nhập</button>
        </form>
    </div>
@stop
@section('js')
    <script src="{{ url('/assets/script/account-manage.js')}}"></script>
@stop
