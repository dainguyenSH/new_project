<?php
    $check = $className. "@". $actionName;
?>
<div id="sidebar-wrapper">
            <ul class="sidebar-nav nav-pills nav-stacked" id="menu">
                <button type="button" style="color:#fff" class="navbar-toggle collapsed" data-toggle="collapse" id="menu-toggle-3">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </button>
                <li class="hidde-togle">
                    <a href="#" data-toggle="collapse" id="menu-toggle-2"> <span class="fa-stack fa-lg pull-left"><i class="fa fa-th-large fa-stack-1x "></i></span>
                    </a>

                </li>

                <div style="clear:both"></div>
                <div class="logo">
                    <img src="{{ URL('') }}/{{ Auth::merchant()->get()->logo ? Auth::merchant()->get()->logo : 'upload/merchant-logo/default.png' }}" alt="logo" class="img-circle">
                    <h1>{{ Auth::merchant()->get()->name ? Auth::merchant()->get()->name : 'Tên thương hiệu'}}</h1>
                    <p>How are you today? <a href="#" style="color:#ffffff" data-toggle="modal" data-target="#feedbackMerchant"><i class="fa fa-envelope-o"></i></a> </p>
                </div>

                @if (Auth::merchant()->get())
                    <li @if ($className == 'InitializeCardController') class='current' @endif >
                        <a href="{{ URL('merchant/initialize-card') }}"><span class="fa-stack fa-lg pull-left"><i class="fa fa-cog fa-stack-1x"></i></span>  
                            @if(Auth::merchant()->get()->package == 0)
                                Khởi tạo thẻ
                            @elseif (Auth::merchant()->get()->active == 2)
                                Đang chờ duyệt
                            @elseif (Auth::merchant()->get()->active == 1)
                                Cấu hình thẻ
                            @endif
                        </a>
                    </li>

                    <li @if($className == 'MessagesController') class='current' @endif>
                        <a href="{{ URL('merchant/send-messages') }}"> <span class="fa-stack fa-lg pull-left"><i class="fa fa-paper-plane-o fa-stack-1x "></i></span> Gửi tin nhắn</a>
                    </li>

                    <li @if($className == 'IncentivesController') class='current' @endif>
                        <a href="{{ URL('merchant/create-incentives') }}"><span class="fa-stack fa-lg pull-left"><i class="fa fa-tag fa-stack-1x "></i></span> Tạo ưu đãi</a>
                    </li>

                    <li @if($className == 'FeedbackController') class='current' @endif>
                        <a href="{{ URL('merchant/feedback') }}"><span class="fa-stack fa-lg pull-left"><i class="fa fa-star fa-stack-1x "></i></span> Quản trị phản hồi</a>
                    </li>

                    <li @if($className == 'AccountManageController') class='current' @endif>
                        <a href="{{ URL('merchant/account-manage') }}"><span class="fa-stack fa-lg pull-left"><i class="fa fa-users fa-stack-1x "></i></span> Quản trị member</a>
                    </li>

                    <li>
                        <a href="{{ URL('merchant/transfer-history') }}"><span class="fa-stack fa-lg pull-left"><i class="fa fa-history fa-stack-1x "></i></span> Lịch sử giao dịch</a>
                    </li>

                    <li @if($className == 'AnalyticsController') class='current' @endif>
                        <a href="{{ URL('merchant/analytics') }}"><span class="fa-stack fa-lg pull-left"><i class="fa fa-line-chart fa-stack-1x "></i></span> Thống kê</a>
                    </li>
                @endif

                @if (Auth::admin())

                @endif

                


                <div class="coppy-right">
                    <img src="{{ URL('') }}/images/logo.png" title="logo" width="20%">
                    <h1>AbbyCard</h1>
                    <h2>A product of Shoppie Pte. Ltd. </h2>
                    <p>Copyright 2015 AbbyCard.</p>
                    <p>All rights reserved!</p>
                </div>
            </ul>
        </div>
