<?php
if (Auth::admin()->get()) {
    $url = URL('admincp');
    header('Location:' . $url);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AbbyCard - Login</title>

        <!-- CSS -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="{{ URL('') }}/css/bootstrap.min.css">
        <link href="{{ URL('') }}/font/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ URL('') }}/assets/login/css/form-elements.css">
        <link rel="stylesheet" href="{{ URL('') }}/assets/login/css/style.css">

    </head>

    <body>
        <div class="top-content">
            
            <div class="inner-bg" style="background: url('/assets/login/bg_all.png');">
                <div class="container">
                    
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">
                            <div class="form-top">
                                <h2><i class="fa fa-lock"></i> Admin <span>AbbyCard</span></h2>
                            </div>
                            <div class="form-bottom">
                                <form method="post" class="login-form">
                                    @include('layouts.alert')
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
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 social-login">
                            <h3>...or login with:</h3>
                            <div class="social-login-buttons">
                                <a class="btn btn-link-2" href="#">
                                    <i class="fa fa-facebook"></i> Facebook
                                </a>
                                <a class="btn btn-link-2" href="#">
                                    <i class="fa fa-twitter"></i> Twitter
                                </a>
                                <a class="btn btn-link-2" href="#">
                                    <i class="fa fa-google-plus"></i> Google Plus
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>


        <!-- Javascript -->
        <script src="{{ URL('') }}/js/jquery.min.js"></script>
        <script src="{{ URL('') }}/js/bootstrap.min.js"></script>
        <script src="{{ URL('') }}/assets/login/js/jquery.backstretch.min.js"></script>
        <script src="{{ URL('') }}/assets/login/js/scripts.js"></script>
        
        <!--[if lt IE 10]>
            <script src="assets/js/placeholder.js"></script>
        <![endif]-->

    </body>

</html>
