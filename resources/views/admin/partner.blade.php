@extends('layouts-admin.master')
@section('meta-title')
    <title>Sync-Account Merchant - Super Administrator AbbyCard</title>
@stop
@section('css')
@stop
@section('title')
<div class="title-pages">
    <h2>Sync-Account Merchant</h2>
</div>
@stop
@section('content')
<div class="account-manage-container">
<div class="col-lg-12">
    <h1 class="title-h1">Danh sách toàn bộ tài khoản Sync-Account Merchant</h1>
    <div class="clearfix"></div>
    <div class="col-lg-1">
        <a href="{{ URL('admincp/add-new-partner') }}"><button class="btn btn-pink"><i class="fa fa-plus"></i> Add new Sync-Account Merchant</button></a>
    </div>
    <div class="col-lg-3">
        <!-- <div class="form-group">
            <select class="form-control" id="object-apply">
                <option>Hạng thẻ</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
            </select>
            </div> -->
    </div>
    <div class="col-lg-3">
        <!-- <div class="form-group">
            <select class="form-control" id="object-apply">
                <option>Trạng thái</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
            </select>
            </div> -->
    </div>
    <div class="col-lg-5">
                <div class="input-group col-md-12">
                    <input type="text" class="form-control" id="searchAdminPartner" placeholder="Tìm kiếm tên thương hiệu" />
                    <span class="input-group-btn">
                        <button class="btn btn-pink" id="btnSearchAdminPartner" type="button">
                            <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </span>
                </div>
        {{-- <div class="form-group has-feedback has-feedback-left">
            <i class="form-control-feedback glyphicon glyphicon-search btn-search-partner" style="float:left"></i>
            <input type="text" class="form-control" id="searchAdminPartner" placeholder="Tìm kiếm" />
        </div> --}}
    </div>
    <br>
    <div class="col-lg-12">
        <div class="table-responsive content-partner">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th class="pink" width="2%">ID</th>
                    <th class="pink" width="10%">Ảnh</th>
                    <th class="pink" width="40%">Thương hiệu</th>
                    <th class="pink" width="20%">Trạng thái</th>
                    <th class="pink" width="20%">Tác vụ</th>
                </tr>
                </thead>
                <tbody class="list-partner">
                        @foreach ($partners as $row)
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td><img src="{{ URL('').'/'.$row->logo }}" width="50%" /> </td>
                                <td>{{ $row->name }}</td>
                                <td>{{ checkActive($row->active) }}</td>
                                <td><a href="{{ URL('admincp/edit-partner/'). '/'. $row->id }}"><button type="button" class="btn btn-xs btn-info">Xem/Sửa</button></a></td>
                            </tr>
                        @endforeach


                </tbody>
            </table>
            {!! $partners->render() !!}
        </div>
    </div>
</div>
</div>
@stop
@section('js')
@stop
