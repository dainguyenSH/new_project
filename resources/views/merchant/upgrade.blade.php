@extends('layouts.master')
@section('css')

@stop

@section('title')
    <div class="title-pages">
        <h2>Gửi yêu cầu nâng cấp</h2>
    </div>
@stop

@section('content')

@if ( Auth::merchant()->get()->package_status )
	<div class="alert alert-info alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<p style="text-align:center">Chúng tôi đã nhận được yêu cầu chuyển gói dịch vụ của bạn thành gói <span class="pink">{{ checkPackagePending(Auth::merchant()->get()->package_status) }}</span> của quý khách. Vui lòng đợi chúng tôi xử lý. Xin cảm ơn!</p>
	</div>
@endif

<form action="" method="POST" id="upgrade-request-package" accept-charset="utf-8">

	<div class="row">
		<div class="col-lg-12 blue upgrade">
			<h1>ĐĂNG KÝ GÓI DỊCH VỤ</h1>
			<p>Vui lòng kiểm tra lại thông tin dưới đây. Chúng tôi sẽ liên hệ lại với quý khách sau 3 giờ làm việc</p>

			<div class="col-lg-6">
				<h2>Gói dịch vụ đã chọn</h2>
				<div class="col-lg-11 pull-right">
					<div class="form-group">
		                <select class="form-control required" id="packages" name="packages">
		                    <option value="-1" data-budget="0">-- Vui lòng chọn gói --</option>
		                    	@foreach (getAllPackagesNotIn(Auth::merchant()->get()->package) as $row)
		                        	<option data-budget="{{ json_decode($row->info,true)['budget'] }}" @if($idPackage == $row->id) selected @endif value="{{ $row->id }}">{{ $row->name }} @if (Auth::merchant()->get()->package_status == $row->id) (đang chờ duyệt) @endif </option>
		                        @endforeach
		                </select>
	            	</div>

					<div class="form-group">
		                <select class="form-control required" id="time" name="time">
		                    <option value="-1">-- Vui lòng chọn thời gian --</option>
		                    	@foreach (getAllMonth() as $row)
			                    	<option data-sale="{{ json_decode($row->info,true)['sale'] }}" value="{{ $row->name }}">{{ $row->name }} tháng {{ valueSale(json_decode($row->info,true)['sale']) }}</option> 
			                    @endforeach
		                </select>
	            	</div>

	            	<p>Tổng phí dịch vụ : <span class='pink price'><span id="count-pay">0 VNĐ</span></span> <span id="saving"></span></p>
	            	<p class="note">( Chưa bao gồm VAT )</p>
				</div>
			</div>

			<div class="col-lg-6">
				<h2>Thông tin người liên hệ</h2>
				<div class="col-lg-11 pull-right">
					<div class="form-group">
		                <input class="form-control" type="text" id="" name="fullname" placeholder="Họ và tên" value="{{ json_decode(Auth::merchant()->get()->information,true)['fullname'] }}">
		            </div>

		            <div class="form-group">
		                <input class="form-control" type="text" id="" name="phone" placeholder="Điện thoại" value="{{ json_decode(Auth::merchant()->get()->information,true)['phone'] }}">
		            </div>

		            <div class="form-group">
		                <input class="form-control" type="text" id="" name="email" placeholder="Email liên hệ" value="{{ json_decode(Auth::merchant()->get()->information,true)['email'] }}">
		            </div>

		            <div class="form-group">
		                <textarea name="content" class="form-control" rows="5" placeholder="Nội dung yêu cầu bổ sung"></textarea>
		            </div>

		            <div class="form-group">
		            	<button type="submit" id="saveRequestUpgrade" class="btn btn-pink pull-right">Đăng ký</button>
		            </div>
				</div>
			</div>


		</div>
	</div>

</form>

@stop

@section('js')
@stop
