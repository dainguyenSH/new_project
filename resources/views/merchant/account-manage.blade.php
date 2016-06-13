@extends('layouts.master')
@section('css')

@stop
@section('title')
    <div class="title-pages">
        <h2>Quản trị thành viên</h2>
        <div class="wizard-progress">
            <!-- <ul class="winzard">
                <li class="active"><i class="fa fa-circle"></i> Danh sách</li>
                <li><i class="fa fa-circle"></i> Chi tiết thành viên</li>
            </ul> -->
        </div>
    </div>

@stop

@section('content')
	<div class="account-manage-container">
        <div class="col-lg-12">
            <h1 class="title-h1">Danh sách thành viên</h1>

            <div class="clearfix"></div>
            	<div class="col-lg-1" style="padding-top: 8px;"> 
            		Bộ lọc
            	</div>
               
            	<div class="col-lg-3">
            		<div class="form-group">
		                <select class="form-control" id="filter-account-type-card">
		                    <option data-card-id="0">Hạng thẻ</option>
                            @if ($type_merchant == 3)
    		                    @foreach($type_card_list as $key => $value)
                                    <option data-card-id="{{ $value['id']}}">{{ $value['name']}}</option>
                                @endforeach
                            @endif
		                </select>
		            </div>
            	</div>
                
            	<div class="col-lg-3">
            		<div class="form-group">
		                <select class="form-control" id="filter-account-status">
		                    <option data-status="2">Trạng thái</option>
		                    <option data-status="1">Active</option>
		                    <option data-status="0">Inactive</option>
		                    
		                </select>
		            </div>
            	</div>

            	<div class="col-lg-5">
            		<div class="form-group has-feedback has-feedback-left">
            			<i class="form-control-feedback glyphicon glyphicon-search" style="float:left"></i>
    					<input type="text" id="account-search-box" class="form-control" placeholder="Tìm kiếm" />
  					</div>
            	</div>
            <div class="col-lg-12">
            <div class="table-responsive" id="table-account-detail">
            <table class="table table-striped list-customer-panel">
                <thead class="list-customer-header">
                <tr>
                    <th class="pink">ID</th>
                    <th class="pink status-display" >Ảnh</th>
                    <th class="pink">Mã thành viên</th>
                    <th class="pink" >Tên thành viên</th>
                    <th class="pink" >Hạng thẻ</th>
                    <th class="pink" >@if (Auth::merchant()->get()->kind == 1) Chops @elseif (Auth::merchant()->get()->kind == 2) Điểm @endif</th>
                    <th class="pink status-display" style="width:15%">Ngày đăng ký</th>
                    <th class="pink">Trạng thái</th>
                    <th class="pink" >Chi tiết</th>
                </tr>
                </thead>
                <tbody class="list-customer-content">
                @foreach($infos as $key=>$value)
                    <tr>
                        <td>{{ $value["id"]}}</td>
                        <td class="status-display">
                            <img src="{{ $value['avatar']}}" width="50">
                        </td>

                        <td> {{ $value->customers_code }} </td>
                        
                        <td><a class="pink" style="cursor: pointer;" href="{{ URL::asset('')}}/merchant/detail/{{ $value->customers_code }}">{{ $value["name"]}}</a></td>
                        
                        @if($value["level"] != null)
                            <td>{{ $value["level"]}}</td>
                        @else
                            <td>Không có hạng thẻ</td>
                        @endif
                        <td>{{ price ( intval( $value["point"] ) ) }}</td>
                        <td class="status-display">{{ $value["created_at"]}}</td>
                        
                        <td>{{ checkActive($value["status"]) }}</td>
                        <td>
                           <a href="/merchant/detail/{{ $value['customers_code']}}">
                                 <button type="" class="btn btn-xs btn-danger">Xem chi tiết</button>
                           </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
            <div id="account_paginator">
                {!! $infos->render() !!}
            </div>
            
            </div>
            </div>

        </div>
    </div>
@stop

@section('js')
<script src="{{ url('/assets/script/account-manage.js')}}"></script>
@stop
