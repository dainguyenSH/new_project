@extends('layouts.master')
@section('css')

@stop
@section('title')
    <div class="title-pages">
        <h2>Lịch sử giao dịch</h2>
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
            <h1 class="title-h1">Lịch sử giao dịch</h1>
            <div class="table-responsive" id="table-account-detail">
            <table class="table table-striped list-customer-panel">
                <thead class="list-customer-header">
                <tr>
                    <th class="pink">Mã KH</th>
                    <th class="pink">Tên KH</th>
                    <th class="pink">Ngày</th>
                    <th class="pink">Hình thức</th>
                    <th class="pink">Giá trị</th>
                    <th class="pink">Tại cửa hàng</th>

                </tr>
                </thead>
                <tbody class="list-customer-content">
                    @if (count($data) == 0) 
                    <tr style="background:none">
                            <td colspan="6" >
                                <div class="alert alert-success alert-block fade in">
                                    <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                                    <center>
                                        <p>Tất cả các cửa hàng chưa có lịch sử giao dịch</p>
                                    </center>
                                </div>
                            </td>
                        </tr>
                    @else
                    
                         @foreach($data as $key => $value)
                            <tr>
                                <td>{{$value->customers_code}}</td>
                                <td><a class="pink" style="cursor: pointer;" href="{{ URL::asset('')}}/merchant/detail/{{ $value->customers_code }}">{{ $value["name"]}}</a></td>
                        
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
            {!! $data->render() !!}

            
            </div>
            </div>
    </div>
@stop

@section('js')
    <script src="{{ url('/assets/script/account-manage.js')}}"></script>
@stop
