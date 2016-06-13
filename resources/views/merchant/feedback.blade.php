@extends('layouts.master')
@section('css')
<link rel="stylesheet" href="{{ url('assets/rate-star') }}/css/star-rating.css" media="all" rel="stylesheet" type="text/css"/>
@stop
@section('title')
    <div class="title-pages">
        <h2>Quản trị phản hồi</h2>
    </div>
@stop

@section('content')
	<div class="feedback-container">
        <div class="col-lg-12">
            <h1 class="title-h1">Phản hồi về dịch vụ</h1>
            <p>Đánh giá của khách hàng được tổng hợp bằng cách tính trung bình điểm số của tất cả các lượt rate theo từng tuần. Và thể hiện trong 4 tuần gần nhất. Nếu muốn xem đầy đủ lịch  sử, vui lòng xem tại mục <a href="{{ URL('merchant/analytics')}}">Thống Kê</a></p>
        
			<div class="rate-start-feedback">

				<div class="row">
                @foreach($averages as $key => $value)
        			<div class="col-lg-3 col-sm-6">
        				<center>
        					<h2>Tuần {{$key  + 1}}</h2>
        					<p>{{ $weekend[$key] }}</p>
        					<h2 class="rate-lg">{{ $value["average"] }}</h2>
          					<input id="input-21b" value="{{ $value['average'] }}" data-readonly="true" type="number" class="rating pink" min=0 max=5 step=0.1 data-size="xs">
          					<p>{{ $value["count"] }} lượt đánh giá</p>
          				</center>
        			</div><!-- /.col-lg-3 -->

        		@endforeach	
      			</div>
			</div>
            
			<div class="list-feedback">
				<h1 class="title-h1">Danh sách phản hồi</h1>
                @if($package_merchant != "Free")
                <div class="table-responsive">
    				<table class="table table-striped list-send-messages">
                    <thead>
                    <tr>
                        <th class="pink" style="width:3%">ID</th>
                        
                        <th class="pink" style="width:15%">Tên Store</th>
                        <th class="pink" style="width:15%">Tên</th>
                        <th class="pink" style="width:30%">Nội dung phản hồi</th>
                        <th class="pink" style="width:17%">Rate</th>
                       
                         <th class="pink status-display" style="width:10%">Ngày</th>
                    </tr>
                    </thead>
                    <tbody class="list-incentives">

                    @if($feeds != null)
        
        
                    @foreach($feeds as $feed)
                        <tr>
                            <td> {{ $feed['id'] }}</td>
                            
                            <td>
                                @if (count(json_decode($feed['stores_name'],true)) != 0)
                                    {{ json_decode($feed['stores_name'],true)[0]['store_name'] }}
                                @endif
                            </td>
                            <td><a class="pink" style="cursor: pointer;" href="{{ URL::asset('')}}/merchant/detail/{{$feed['customers_code'] }}">{{ $feed['customers_name'] }}</a></td>
                            
                            <td  style="text-align:left; overflow: hidden; text-overflow: ellipsis; width: 350px;" >
                            	{{ $feed['content'] }}
                            </td>
                            <td>
                                <input id="input-21b" value="{{ $feed['rate']}}" data-readonly="true" type="number" class="rating pink" min=0 max=5 step=0.1 data-size="xs">
                            </td>
                            <td class="status-display">
                                {{ date('d.m.Y H:i:s',strtotime($feed['created_at'])) }}
                            </td>
                        </tr>
                    @endforeach
                    @endif 
                    </tbody>
                </table>
                </div>

            @else
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <p style="text-align:center">Gói dịch vụ của quý khách là Free nên ko thể xem chi tiết nội dung phản hồi cụ thể của từng khách hàng. Vui lòng nâng cấp lên gói <a href="#" class="upgrade-premium" data-toggle="modal" data-target="#showPackagesInfo"><i class="pink">Premium</i></a>  để xem được nội dung này</p>
                <div class="text-center" style="padding-top: 2%;">
                    <button class="btn btn-pink" data-toggle="modal" data-target="#showPackagesInfo">Chi tiết các gói dịch vụ</button>
                </div>
            </div>
            @endif

            @if ($feeds != null)
            {!! 

            $feeds->render() !!}
            @endif
			
			</div>
            
        </div>
    </div>
@stop

@section('js')
	<script src="{{ url('assets/rate-star') }}/js/star-rating.js" type="text/javascript"></script>
@stop
