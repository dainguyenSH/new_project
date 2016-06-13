<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @yield('title')

        <!-- CSS -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="{{ URL('') }}/css/bootstrap.min.css">
        <link href="{{ URL('/') }}/font/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ URL('') }}/assets/login/css/form-elements.css">
        <link href="{{ URL('') }}/assets/dialog/bootstrap-dialog.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ URL('') }}/assets/login/css/style.css">

    </head>

    <body>
        <div class="top-content">
            
        <div class="full">
            
        </div>
            <div class="inner-bg">
                <div class="container">
                    
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">
                            <div class="form-top">
                                @yield('header')
                                <p class="register-note">Khởi tạo chương trình thẻ thành viên trong 10 phút</p>
                            </div>
                            @yield('form')
                        </div>
                    </div>
                    <!-- <div class="row">
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
                    </div> -->
                </div>
            </div>
            
        </div>


        <!-- Javascript -->
        <script src="{{ URL('') }}/js/jquery.min.js"></script>
        <script src="{{ URL('') }}/js/bootstrap.min.js"></script>
        <script src="{{ URL('') }}/assets/login/js/jquery.backstretch.min.js"></script>
        <script src="{{ URL('') }}/assets/dialog/bootstrap-dialog.min.js"></script>
        <script src="{{ URL('') }}/assets/login/js/scripts.js"></script>

        @include('dialog.message-01')
        
        <!--[if lt IE 10]>
            <script src="assets/js/placeholder.js"></script>
        <![endif]-->

    </body>

</html>
