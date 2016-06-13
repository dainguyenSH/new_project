@extends('layouts.master')
@section('css')

@stop
@section('title')
    <div class="title-pages">
        <h2>Quản trị thành viên</h2>
        <div class="wizard-progress">
            <ul class="winzard">
            <li><a style="text-decoration:none" href="{{ URL('merchant/account-manage') }}"><i class="fa fa-circle"></i> Danh sách</a></li>
                <li class="active"><i class="fa fa-circle"></i> Chi tiết thành viên</li>
            </ul>
        </div>
    </div>
@stop

@section('content')
	<div class="account-manage-container">
	    <div class="col-lg-12">
	        <h1 class="title-h1">Thông tin thành viên</h1>

            <div class="row">

            
                <div class="col-lg-2">
                    <img src="{{ $infos->avatar }}" class="avatar-detail" width="100%">
                </div>

                <div class="col-lg-4">
                    <table class="table">
                        <thead>
                            <th colspan="2">Thông tin khách hàng</th>
                        </thead>
                    <tbody class="info-user-details">
                        <tr>
                            <td><strong>Email:</strong> </td>
                            <td>{{ $infos->email }}</td>
                        </tr>

                        <tr>
                            <td><strong>Điện thoại:</strong></td>
                            <td>{{ $infos->mobile }}</td>
                        </tr>

                        <tr>
                            <td><strong>Ngày sinh:</strong></td>
                            <td>{{ $infos->birthday }}</td>
                        </tr>

                        <tr>
                            <td><strong>Giới tính:</strong></td>
                            <td>{{ $infos->gender }}</td>
                        </tr>

                        <tr>
                            <td><strong>Địa chỉ:</strong></td>
                            <td>{{ $infos->location }}</td>
                        </tr>

                    </tbody>
                    </table>
                </div>

                <div class="col-lg-6">
                    <table class="table">
                        <thead>
                            <th colspan="2">Thông tin thẻ</th>
                        </thead>
                    <tbody class="info-user-details">
                        <tr>
                            <td width="30%"><strong>Hạng thẻ:</strong></td>
                            <td>{{ $infos->level }}</td>
                        </tr>

                        <tr>
                            <td width="30%"><strong>Mã thành viên:</strong></td>
                            <td>{{ $infos->customers_code }}</td>
                        </tr>

                        <tr>
                            <td width="30%"><strong>Ngày đăng ký:</strong></td>
                            <td>{{ $infos->created_at }}</td>
                        </tr>

                        <tr>
                            <td width="30%"><strong>Trạng thái:</strong></td>
                            <td>{{ checkActive($infos->status) }}</td>
                        </tr>

                        @if (Auth::merchant()->get()->kind == 1)
                            <tr>
                                <td width="30%"><strong>Chops tích lũy:</strong></td>
                                <td><span class='pink'>{{ price ( intval($infos->point) ) }}</span> Chops</td>
                            </tr>

                            <tr>
                                <td width="30%"><strong>Chops khả dụng:</strong></td>
                                <td><span class='pink'>{{ price ( intval($infos->current_point) ) }}</span> Chops</td>
                            </tr>
                        @elseif (Auth::merchant()->get()->kind == 2)
                            <tr>
                                <td width="30%"><strong>Số điểm đã tích:</strong></td>
                                <td><span class='pink'>{{ price( intval($infos->point) ) }}</span> Điểm</td>
                            </tr>
                        @endif

                        
                    </tbody>
                    </table>
                </div>
                
            </div>

	        

<!-- 	        <p>Hạng thẻ</p>
	        <p>Mã thành viên</p>
	        <p>Ngày đăng ký</p>
	        <p>Trạng thái</p>
	        <p>Số dư tem</p>

	        <p>Email</p>
	        <p>Số điện thoại</p>
	        <p>Giới tính</p>
	        <p>Địa chỉ</p> -->

	    </div>

	    <div class="col-lg-12">
	    <h1 class="title-h1">Lịch sử tích điểm</h1>
            
            <div class="table-responsive">

	    	<table class="table table-striped">
                <thead>
                <tr>
                    <th class="pink" style="width:3%">ID</th>
                    <th class="pink" style="width:10%">Ngày</th>
                    <th class="pink" style="width:15%">Hình thức</th>
                    <th class="pink" style="width:15%">Giá trị</th>
                    <th class="pink" style="width:17%">Tại cửa hàng</th>
                </tr>
                </thead>
                <tbody class="list-incentives">

                @if (count($history_infos) == 0) 
                    <tr style="background:none">
                        <td colspan="6" >
                            <div class="alert alert-success alert-block fade in">
                                <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                                <center>
                                    <p>Thành viên chưa có giao dịch nào</p>
                                </center>
                            </div>
                        </td>
                    </tr>
                @else
                
                     @foreach($history_infos as $key => $value)
                        <tr>
                            <td>{{$value->id}}</td>
                            <td>
                                {{$value->created_at}}
                            </td>
                            <td>{{ checkTypeChangePoint($value->type) }}</td>
                            <td>{{ checkTypeChangePoint2($value->type) }} {{ $value->change_points }} {{ checkTypeCard(Auth::merchant()->get()->card_type) }}</td>
                            <td>{{ $value->store_name }}</td>
                        </tr>
                    @endforeach
                @endif
                
                </tbody>
            </table>
            {!! $history_infos->render() !!}
        </div>
	    </div>
	</div>
@stop

@section('js')
@stop
