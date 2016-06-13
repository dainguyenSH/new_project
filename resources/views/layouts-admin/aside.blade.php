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
            <img src="{{ URL('') }}/upload/merchant-logo/default.png" alt="logo" class="img-circle">
            <h1>SuperAdmin</h1>
            {{-- <p>How are you today? <a href="#" style="color:#ffffff" data-toggle="modal" data-target="#feedbackMerchant"><i class="fa fa-envelope-o"></i></a> </p> --}}
        </div>

        <li @if ($actionName == 'getIndex') class='current' @endif >
            <a href="{{ URL('admincp') }}"><span class="fa-stack fa-lg pull-left"><i class="fa fa-cog fa-stack-1x"></i></span> Registered Merchant</a>
        </li>

        <li @if ($actionName == 'getPartner') class='current' @endif >
            <a href="{{ URL('admincp/partner') }}"><span class="fa-stack fa-lg pull-left"><i class="fa fa-cog fa-stack-1x"></i></span> Sync-Account Merchant</a>
        </li>

        <li @if ($actionName == 'getNewMerchant') class='current' @endif >
            <a href="{{ URL('admincp/new-merchant') }}"><span class="fa-stack fa-lg pull-left"><i class="fa fa-cog fa-stack-1x"></i></span> Add-new Merchant</a>
        </li>

        <li @if ($actionName == 'getBoo') class='current' @endif>
            <a href="{{ URL('admincp/boo') }}"><span class="fa-stack fa-lg pull-left"><i class="fa fa-cog fa-stack-1x"></i></span> Boo</a>
        </li>

        <li>
            <a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-cog fa-stack-1x"></i></span> Merchant Feedback</a>
        </li>

        <li>
            <a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-cog fa-stack-1x"></i></span> KPIs</a>
        </li>
        
        <div class="coppy-right">
            <img src="{{ URL('') }}/images/logo.png" title="logo" width="20%">
            <h1>AbbyCard</h1>
            <h2>A product of Shoppie Pte. Ltd. </h2>
            <p>Copyright 2015 AbbyCard.</p>
            <p>All rights reserved!</p>
        </div>
    </ul>
</div>
