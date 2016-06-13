<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>{{ $titlePage or 'AbbyCard' }}</title>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300|Source+Sans+Pro:400,300' rel='stylesheet' type='text/css'>
    <link href="{{ URL('') }}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ URL('') }}/font/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ URL('') }}/assets/confirmation/css/jquery-confirm.min.css" rel="stylesheet">
    <link href="{{ URL('') }}/assets/dialog/bootstrap-dialog.min.css" rel="stylesheet">
    <link href="{{ URL('') }}/assets/datepicker/css/jquery.datetimepicker.css" rel="stylesheet">
    <!-- Customize include css -->
    @yield('css')
    <link href="{{ URL('') }}/css/style.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{ URL('landingpage/images/favicon/favicon.png') }}">
    <input type="hidden" id="root" name="root" value="{{URL::asset('')}}" >


    <!-- Favicons -->
</head>

<body>
    <!-- GA Google Analytics -->
    <script type="text/javascript">
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-74076104-2', 'auto');
        ga('send', 'pageview');
    </script>

    <div id="loadding" style="display:none"></div>
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
                        <li><span class="pink">{{ countCustomerMerchant(Auth::merchant()->get()->id) }}</span> thành viên</li>
                        <li><span class="pink">{{ countMessageMerchant(Auth::merchant()->get()->id) }}</span> tin nhắn</li>
                        <li><span class="pink">{{ countDealMerchant(Auth::merchant()->get()->id) }}</span> ưu đãi</li>
                    
                    </ul>
                </div>
                
                <div class="col-sm-6 nav-action">
                    <ul>
                        <li>Đăng xuất <a href="{{ URL('logout') }}"><i class="fa fa-share-square-o fa-lg"></i></a></li>
                        <li>Đổi mật khẩu <a href="#" id="change-pass-btn" data-link-post="{{ URL('change-password-merchant   ')}}" data-toggle="modal" data-target="#showChangePassword"><i class="fa fa-key fa-lg" ></i></a></li>
                        <li>Gói {{ checkNowPackage(isset(Auth::merchant()->get()->id) ? Auth::merchant()->get()->package : 0) }} <a href="#" class="upgrade-premium" data-toggle="modal" data-target="#showPackagesInfo"><i class="fa fa-cloud-upload fa-lg"></i></a></li>
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
        @include('layouts.aside')
        <!-- /#sidebar-wrapper -->
        <!-- Page Content -->


        <div id="page-content-wrapper">
            <div class="container-fluid content">
                <!-- Content -->
                @yield('content')
            </div>
        </div>
    </div>
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

        //Auto replace image die
        $(window).load(function() {
            $('img').each(function() {
                if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
                    this.src = 'http://placehold.it/300x300';
                }
            });
        });
    </script>
    <!-- List packages -->
    <div class="modal fade" tabindex="-1" id="showPackagesInfo" role="dialog" aria-labelledby="gridSystemModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="closeShowPackagesInfo"data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="gridSystemModalLabel">Các gói dịch vụ AbbyCard</h4>
                </div>
                <div class="modal-body" >
                    <div class="row">
                        @include('html.list-packages')
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- end packages -->

    <!-- start change password -->
    <div class="modal fade" tabindex="-1" id="showChangePassword" role="dialog" aria-labelledby="gridSystemModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="gridSystemModalLabel">THAY ĐỔI MẬT KHẨU CỦA MERCHANT</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1">
                            <div class="form-bottom">
                                <form id="formChangePassword" class="form-password" action="#" method="post" style="width: 100%;">
                                @include('layouts.alert')
                                {{ csrf_field() }}

                                    <div class="form-group">
                                        <input type="password" name="old_password" id="old_password" required placeholder="Mật khẩu hiện tại ..." class="form-password form-control">
                                    </div>

                                    <div class="form-group">
                                        <input type="password" name="new_password" id="new_password" required placeholder="Mật khẩu mới ..." class="form-password form-control">
                                    </div>

                                    <div class="form-group">
                                        <input type="password" name="re_new_password" id="re_new_password" required placeholder="Xác nhận mật khẩu mới ..." class="form-password form-control checkTwoPasswordIsSame">
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-pink" id="changePassWordButton">Đổi mật khẩu</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
            <!-- /.modal-content
        </div>
    </div>
    </div>
    <!-- end change password -->

    <!-- Feedback Admin   -->
    <div class="modal fade" id="feedbackMerchant" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Đánh giá của quý khách về trải nghiệm dịch vụ <span class="abbycard">AbbyCard</span>?</h4>
            </div>
            <div class="modal-body" style="height:auto;">
                <form>
                    <div class="form-group">
                    <label for="message-text" class="control-label">Nội dung sẽ được gửi tới ban quản trị <span class="abbycard pink">AbbyCard</span></label>
                        <textarea class="form-control" id="message-text-feedback" rows="5" placeholder="Nhập nội dung phản hồi"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <button type="button" id="send-feedbacks-merchant" data-post-url="{{ URL() }}/merchant/merchant-feedbacks" class="btn btn-pink"><i class="fa fa-paper-plane"></i> Gửi phản hồi</button>
            </div>
        </div>
    </div>
</div>
    <!-- end Feedback Admin -->


</body>

</html>
