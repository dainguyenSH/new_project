<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    @yield('meta-title')
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300|Source+Sans+Pro:400,300' rel='stylesheet' type='text/css'>
    <link href="{{ URL('') }}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ URL('') }}/font/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ URL('') }}/assets/confirmation/css/jquery-confirm.min.css" rel="stylesheet">
    <link href="{{ URL('') }}/assets/dialog/bootstrap-dialog.min.css" rel="stylesheet">
    <link href="{{ URL('') }}/assets/datepicker/css/jquery.datetimepicker.css" rel="stylesheet">
    <!-- Customize include css -->
    @yield('css')
    <link href="{{ URL('') }}/css/style.css" rel="stylesheet">
    <link href="{{ URL('') }}/css/style-manage.css" rel="stylesheet">
    <input type="hidden" id="root" name="root" value="{{URL::asset('')}}" >
</head>

<body onload="toggleNav()">
    <div id="loadding" style="display:none">
        <center>
            
            <!-- <img id="loadding-progess-2" style="position:absolute; top:40%; margin:auto; " src="{{ URL('images/loadding/default.svg') }}"> -->
        </center>
    </div>
    <nav class="navbar navbar-default no-margin position-nav toggle-navbar" style="width:100%">
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

                        
                    </ul>
                </div>
                
                <div class="col-sm-6 nav-action">
                    <ul>
                        <li>Đăng xuất <a href="{{ URL('logout/admin') }}"><i class="fa fa-share-square-o fa-lg"></i></a></li>
                    </ul>
                </div>
            </div>

            
        </div>
        <hr>
        @yield('title')
        <!-- bs-example-navbar-collapse-1 -->
    </nav>




    <div id="wrapper">

        <!-- Sidebar -->
        @include('layouts-admin.aside')
        
        <!-- /#sidebar-wrapper -->
        <!-- Page Content -->

        <div id="page-content-wrapper">
             <div class="container-fluid content">
                <!-- Content -->
                @yield('content')
            </div>
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
    <script src="{{ URL('') }}/assets/script/admin.js"></script>
    <script src="{{ URL('') }}/assets/datejs/date.js"></script>
    <script src="{{ URL('') }}/assets/datejs/moment.js"></script>



    <script type="text/javascript">
        // $(document).ready(function(){
        //     $(".currentcy").autoNumeric('init');  //autoNumeric with defaults
        // });

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

    <script type="text/javascript">
        function toggleNav() {
            $("#wrapper").toggleClass("toggled-2");
            $('#menu ul').hide();
            $('.coppy-right').toggle();
            $('.title-togle').removeClass();
            $('.navbar').toggleClass('toggle-navbar');
        }
        //Auto replace image die
        $(window).load(function() {
            $('img').each(function() {
                if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
                    this.src = 'http://placehold.it/300x300';
                }
            });
        });
    </script>
</body>

</html>
