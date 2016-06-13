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

    <body style="overflow:hidden">
        <div class="top-content">
            
            <div class="inner-bg" style="background: url('{{ URL('') }}/assets/login/bg_all.png'); padding: 100px 0 1000px 0;">
                <div class="container">
                    
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">

                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 social-login">
                            <h3>Đăng ký tài khoản</h3>
                            <div class="social-login-buttons">
                                <a class="btn btn-link-2" href="{{ URL('login') }}">
                                    <i class="fa fa-user-plus"></i> Merchant
                                </a>
                                <a class="btn btn-link-2" href="{{ URL('login/admin') }}">
                                    <i class="fa fa-user-plus"></i> Administrator
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
