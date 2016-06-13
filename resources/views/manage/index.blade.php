@extends('layouts-manage.master')
@section('css')

@stop

@section('title')
    <div class="title-pages">
        <h2>{{ Auth::store()->get()->store_name }}</h2>
    </div>
@stop

@section('content')
    <div class="manage-icards">
        <div class="row">
            <div class="col-lg-6">
                <h1>Nhập {{ checkTypeCardByStore(Auth::store()->get()->merchants_id) }}</h1>

                <!-- <div class="form-group has-feedback has-feedback-left">
                    <i class="form-control-feedback glyphicon glyphicon-search pink" style="float:left"></i>
                    <input type="text" class="form-control" id="id-account" placeholder="Nhập mã khách hàng" value="" />
                </div> -->
                <div class="form-group" style="position: relative;">
                    <i class="glyphicon glyphicon-search pink btn-search-manage find-customers-left"></i>
                    <input type="text" class="form-control" id="id-account" placeholder="Nhập mã khách hàng" value="">
                </div>

                <center>
                    <img id="loadding-progess" style="display:none; margin:auto" src="{{ URL('images/loadding/default.svg') }}">
                </center>
                <!-- Result search -->
    
                <div id="result-search"></div>
                <!-- End resut search -->
            </div>

            @if ( getKindByStore(Auth::store()->get()->id) == 1 )
                <div class="col-lg-6">
                    <h1>Đổi {{ checkTypeCardByStore(Auth::store()->get()->merchants_id) }}</h1>

                    <!-- <div class="form-group has-feedback has-feedback-left">
                        <i class="form-control-feedback glyphicon glyphicon-search pink" style="float:left"></i>
                        <input type="text" class="form-control" id="id-account-2" placeholder="Nhập mã khách hàng" />
                    </div> -->

                    <div class="form-group" style="position: relative;">
                        <i class="glyphicon glyphicon-search pink btn-search-manage find-customers-right"></i>
                        <input type="text" class="form-control" id="id-account-2" placeholder="Nhập mã khách hàng" value="">
                    </div>
                    
                    <center>
                        <img id="loadding-progess-2" style="display:none; margin:auto" src="{{ URL('images/loadding/default.svg') }}">
                    </center>

                    <div id="result-search-2"></div>                
                </div>
            @endif
        </div>
        <!-- List -->
        <div class="col-lg-12 list-track-by-account">
            <h1>Bảng tổng kết ngày {{ substr(date('d-m-Y H:i:s'),0,-8) }}</h1>
            <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th class="pink" style="width:20%">Ngày</th>
                    <th class="pink" style="width:10%">Mã HĐ </th>
                    <th class="pink" style="width:15%">ID khách hàng</th>
                    <th class="pink" style="width:20%">Tên KH</th>
                    <th class="pink" style="width:10%">Hình thức GD</th>
                    <th class="pink" style="width:10%">Giá trị</th>
                </tr>
                </thead>
                <tbody class="list-incentives">

                @if ($data->count() != 0)
                    @foreach ($data as $row)
                        <tr>
                            <td>{{ $row->created_at }}</td>
                            <td>{{ $row->order_id }}</td>
                            <td>{{ $row->customers_code }}</td>
                            <td>{{ $row->name }}</td>
                            <td>{{ checkTypeChangePoint($row->type) }}</td>
                            <td>{{ checkTypeChangePoint2($row->type) }} {{ $row->change_points }}</td>
                        </tr>

                    @endforeach
                @else
                    <tr style="background:none">
                        <td colspan="6" >
                            <div class="alert alert-success alert-block fade in">
                                <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                                <center>
                                    <p> Chưa có giao dịch trong ngày {{ substr(date('d-m-Y H:i:s'),0,-8) }}</p>
                                </center>
                            </div>
                        </td>
                    </tr>
                @endif

                    
                </tbody>
            </table>
            {!! $data->render() !!}
            </div>
        </div>


    </div>
@stop

@section('js')
    <script src="{{ URL('') }}/assets/script/manage.js"></script>
@stop
