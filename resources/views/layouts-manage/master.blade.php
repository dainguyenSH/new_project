<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Quản trị thu ngân - AbbyCard</title>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300|Source+Sans+Pro:400,300' rel='stylesheet' type='text/css'>
    <link href="{{ URL('') }}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ URL('') }}/font/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ URL('') }}/assets/confirmation/css/jquery-confirm.min.css" rel="stylesheet">
    <link href="{{ URL('') }}/assets/dialog/bootstrap-dialog.min.css" rel="stylesheet">
    <!-- <link href="{{ URL('') }}/assets/datepicker/css/jquery.datetimepicker.css" rel="stylesheet"> -->
    <!-- Customize include css -->
    @yield('css')
    <link href="{{ URL('') }}/css/style.css" rel="stylesheet">
    <link href="{{ URL('') }}/css/style-manage.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{ URL('landingpage/images/favicon/favicon.png') }}">
    <input type="hidden" id="root" name="root" value="{{URL::asset('')}}" >
</head>

<body id="background-manage">
    <!-- GA Google Analytics -->
    <script type="text/javascript">
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-74076104-3', 'auto');
        ga('send', 'pageview');
    </script>

    <div id="loadding" style="display:none"></div>
    <nav class="navbar navbar-default no-margin position-nav" style="width:100%">
        <!-- NAV -->
        <div class="">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" id="menu-toggle">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </button>
        </div>
        <div class="title-pages nav-info-merchant">
            <div class="row">
                <div class="col-sm-6 info-merchant">
                    <ul>
                        <li><span class="pink"></span> {{ date('D m Y H:i', strtotime(date('Y-m-d H:i:s')) ) }}</li>

                        @if ( getKindByStore(Auth::store()->get()->id) == 1 )
                            <li><span class="pink">{{ plusChopManage(Auth::store()->get()->id) }}</span> Lượt Tích Chops</li>
                            <li><span class="pink">{{ exceptChopManage(Auth::store()->get()->id) }}</span> Lượt Đổi Chops</li>
                        @elseif ( getKindByStore(Auth::store()->get()->id) == 2 )
                            <li><span class="pink">{{ plusPointManage(Auth::store()->get()->id) }}</span> Lượt Tích Điểm</li>
                        @endif
                    </ul>
                </div>
                
                <div class="col-sm-6 nav-action">
                    <ul>
                        <li>Đăng xuất <a href="{{ URL('logout/manage') }}"><i class="fa fa-share-square-o fa-lg"></i></a></li>
                    </ul>
                </div>
            </div>

            
        </div>
        <hr>
        @yield('title')
        <!-- bs-example-navbar-collapse-1 -->
    </nav>

        <!-- Sidebar -->
        
        <!-- /#sidebar-wrapper -->
        <!-- Page Content -->

        <div class="container manage">
             <div class="container-fluid content">
                <!-- Content -->
                @yield('content')
            </div>
        </div>
        <!-- <div id="page-content-wrapper"> -->
           
        <!-- </div> -->

    <!-- /#page-content-wrapper -->
    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="{{ URL('') }}/js/jquery-1.11.2.min.js"></script>
    <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
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
    <!-- <script src="/node_modules/readmore-js/readmore.min.js"></script> -->

    <script type="text/javascript">
        $(document).ready(function(){
            $(".currentcy").autoNumeric('init');  //autoNumeric with defaults
        });
    </script>

    @yield('js')
    @include('dialog.message-01')

    
    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-toggle="popover"]').popover();
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip(); 
        });
    </script>

</body>

</html>
