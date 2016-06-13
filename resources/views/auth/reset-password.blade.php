<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf_token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AbbyCard - Reset Password</title>

        <!-- CSS -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="{{ URL('') }}/css/bootstrap.min.css">
        <link href="{{ URL('') }}/font/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ URL('') }}/assets/login/css/form-elements.css">
        <link rel="stylesheet" href="{{ URL('') }}/assets/login/css/style.css">

        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300|Source+Sans+Pro:400,300' rel='stylesheet' type='text/css'>
        <link href="{{ URL('') }}/css/bootstrap.min.css" rel="stylesheet">
        <link href="{{ URL('') }}/font/css/font-awesome.min.css" rel="stylesheet">
        <link href="{{ URL('') }}/assets/confirmation/css/jquery-confirm.min.css" rel="stylesheet">
        <link href="{{ URL('') }}/assets/dialog/bootstrap-dialog.min.css" rel="stylesheet">
        <link href="{{ URL('') }}/assets/datepicker/css/jquery.datetimepicker.css" rel="stylesheet">

    </head>

    <body style="overflow:hidden">
        <div class="top-content">
            
            <div class="inner-bg" style="background: url('{{ URL('') }}/assets/login/bg_all.png'); padding: 100px 0 1000px 0;">
                <div class="container">
                    
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">

                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">
                            <div class="form-top">
                                <h2>Khôi phục mật khẩu Merchant <img style="margin-top: -10px;" src="{{ URL('images/logo/logo30x30.png') }}"> <span class="abby-card-home">AbbyCard</span></h2>
                                
                            </div>
                            <div class="form-bottom">
                                <form class="form-signin" id="myForm" action="" method="post">
                                    @include('layouts.alert')
                                    {{ csrf_field() }}
                                   

                                    <div class="form-group">
                                        <label class="sr-only">Email reset mật khẩu</label>
                                        <input type="text" name="email" id="reset-password-input" value="{{ old('email') }}" placeholder="Email reset mật khẩu..." class="form-username form-control" id="form-email">
                                    </div>
                                    
                                    

                                    <button type="submit" class="btn" id="reset-password-button" data-post-url="{{ URL('password/email') }}"><i class="fa fa-key"></i> Gửi yêu cầu reset mật khẩu</button>

                                    <div class="form-group" style="margin-top:5px;">
                                        <p>Nếu bạn đã có tài khoản vui lòng <a href="{{ URL('login') }}">Đăng nhập</a></p>
                                    </div>

                                </form>
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
        <script src="{{ URL('') }}/assets/reset-password/js/script.js"></script>

        <script src="{{ URL('') }}/assets/jquery-validate/jquery.validate.min.js"></script>
        <script src="{{ URL('') }}/assets/jquery-validate/additional-methods.min.js"></script>
        <script src="{{ URL('') }}/js/bootstrap.min.js"></script>
        <script src="{{ URL('') }}/assets/toaster/jquery.toaster.js"></script>
        <script src="{{ URL('') }}/assets/confirmation/js/jquery-confirm.min.js"></script>
        <script src="{{ URL('') }}/assets/dialog/bootstrap-dialog.min.js"></script>
        <script src="{{ URL('') }}/assets/datepicker/js/jquery.datetimepicker.full.js"></script>
        <script src="{{ URL('') }}/js/sidebar_menu.js"></script>
        <script src="{{ URL('') }}/js/script.js"></script>
        <script src="{{ URL('') }}/assets/currentcy/autoNumeric.js"></script>
        <script src="{{ URL('') }}/js/change-password-merchant.js"></script>
        
        <!--[if lt IE 10]>
            <script src="assets/js/placeholder.js"></script>
        <![endif]-->

    </body>

</html>
