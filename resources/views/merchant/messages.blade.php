@extends('layouts.master')
@section('css')

@stop
@section('title')
    <div class="title-pages">
        <h2>Gửi tin nhắn</h2>
    </div>
@stop
@section('content')
    <div class="messages-container">
        <div class="col-lg-6">
            <h1 class="title-h1">Nhập nội dung gửi tin nhắn</h1>
            <p>Nhập nội dung tin nhắn vào ô bên dưới & gửi tới tất cả các thành viên active của thương hiệu :</p>
            <form id="formPushMessage">
            <div class="form-group">
                <div class="form-group position-counter">
                    <p id="counter-message" class="counter">140/140</p>
                    <textarea class="form-control" rows="5" required id="content-message" name="content-message" maxbyte="140" placeholder="Nội dung chương trình"></textarea> 
                </div>
            </div>

            <div class="form-group">
                <select class="form-control" id="messageDealApply">
                    <option data-deal-id="0"> -- Liên kết chương trình ưu đãi --</option>
                    @foreach($deals as $key=>$value)
                        <option data-deal-id="{{ $value['id']}}">{{ $value["name"]}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
            <button type="button" class="form-control btn btn-pink" id="btn-push-message"><i class="fa fa-paper-plane"></i> &nbsp; Gửi tới <span id="count-customer">{{$count_customer}}</span> thành viên</button>
            </div>

            </form>
        </div>


        <div class="col-lg-6">
            <h1 class="title-h1">Hiển thị trên máy thành viên</h1>
            <p>Dưới đây là nội dung hiển thị được gửi tới máy thành viên. Các hệ điều hành iOS & Android :</p>
            <center>
                <img class="demo-messages" src="{{ URL('') }}/images/demo-message.png">
                <p class="text-messages">The Kafe: Nhân đôi điểm thưởng cho tất cả thành viên The Kafe duy nhất 01 ngày Hallowen trên toàn quốc...</p>
            </center>
        </div>
        <div class="clearfix"></div>

        <div class="col-lg-12">
            <h1 class="title-h1">Lịch sử gửi tin nhắn</h1>
            <table class="table table-striped list-send-messages">
                <thead>
                <tr>
                    <th class="pink" style="width:3%">ID</th>
                    <th class="pink" style="overflow: hidden; text-overflow: ellipsis; width:332.5px;width:35%">Nội dung tin nhắn</th>
                    <th class="pink status-display" style="width:19%">Ngày gửi</th>
                    <th class="pink" style="width:15%">Gửi thành công</th>
                    <th class="pink" style="width:15%">Tỷ lệ thay đổi</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($infos as $key=>$value)

                    
                    <tr>
                        <td>{{ $value["id"]}}</td>
                        <td style="text-align:left; overflow: hidden; text-overflow: ellipsis; width:332.5px;">
                            <div style="overflow: hidden; text-overflow: ellipsis; width: 350px;" class="div-name-of-deal">
                            <span>{{ $value["content"]}}</span>
                            </div>
                        </td>
                        <td class="status-display">{{ $value["created_at"]}}</td>
                        <td>{{ $value["count"]}} &nbsp; &nbsp;</td>
                        <td>{{ $value['string_percentage']}} &nbsp; &nbsp;</td>
                    </tr>
                    @endforeach
                    

                    
                </tbody>
            </table>
        {!! $infos->render() !!}
        </div>
    </div>
@stop

@section('js')



<script src="{{ url('/assets/script/message.js')}}"></script>

<script language="javascript" type="text/javascript">
function limitText(limitField, limitCount, limitNum) {
    if (limitField.value.length > limitNum) {
        limitField.value = limitField.value.substring(0, limitNum);
    } else {
        limitCount.value = limitNum - limitField.value.length;
    }
}
</script>

@stop

