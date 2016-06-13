@extends('layouts.master')
@section('css')
 

@stop

@section('title')
    <div class="title-pages">
        <h2>Tạo chương trình ưu đãi</h2>
    </div>
@stop

@section('content')
<div class="incentives-container">
    <div class="col-lg-6">
        <form class="cmxform" id="formCreateDeal" method="post">
            <h1 class="title-h1">Tạo chương trình ưu đãi</h1>
            <p>Nhập nội dung chương trình vào ô bên dưới & gửi tới tất cả các thành viên active của thương hiệu :</p>
            
            <div class="form-group position-counter">
                <p id="counter-title" class="counter">60/60</p>
                <input type="text" class="form-control checkhtmltag checkMaxLength" required id="titleIncentives" name="titleIncentives" maxbyte="60" rows="5" placeholder="Tên chương trình">
            </div>
    
            <div class="form-group position-counter">
                <p id="counter-content" class="counter">500/500</p>
                <textarea class="form-control checkhtmltag" rows="5" required id="contentIncentives" name="contentIncentives" maxbyte="500" placeholder="Nội dung chương trình"></textarea>
                
            </div>

            <div class="row info-incentives">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="start-date">Ngày bắt đầu</label>
                        <input type="text" maxlength="16" class="form-control checkCurrentDate checkStartDate" placeholder="Ngày giờ bắt đầu" required id="start-date" name="start-date">
                        <!-- <input type="text" class="form-control" id="checkDatePicker"> -->
                    </div>
                    @if($check_level == 1)
                    <div class="form-group">
                        <label for="object-apply">Đối tượng áp dụng</label>
                        <p class="font-sm">(Ctrl + Click để chọn nhiều đối tượng)</p>
                        <select class="form-control" required id="object-apply" multiple name="object-apply">
                            
                            @foreach($object_apply as $key => $value)
                                
                                <option object_apply_id="{{ $value['id'] }}"> {{ $value['name'] }}</option>
                                
                            @endforeach
                        </select>
                    </div>
                    @endif
                    
                    <div class="form-group">
                        <button type="button" class="btn btn-pink img-avatar" id="img-avatar-btn"><i class="fa fa-plus"></i> &nbsp; Add ảnh đại diện</button>
                        <input type="file" required name="image_avatar" value="" id="logo" class="upload-avartar" />

                        <div id="count-image-avatar">
                            
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-pink img-content" id="img-content-btn"><i class="fa fa-plus"></i> &nbsp; Add ảnh nội dung</button>
                        <input type="file"  required multiple="" name="image-content[]" value="" data-ignore-image="" id="image-content" class="upload-content checkMaxUploadFile" />
                    </div>

                    <div class="form-group">
                        <input type="hidden" data-avatar="" data-image-content=""/>
                        <div id="count-image-content">
                            
                        </div>
                        <button type="submit" class="btn btn-pink btn-lg btn-create-incentives">Tạo ưu đãi</button>
                    </div>

                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="end-date">Ngày kết thúc</label>
                        <input type="text" maxlength="16" required class="form-control checkCurrentDate checkEndDate" placeholder="Ngày giờ kết thúc"  id="end-date" name="end-date">
                    </div>
                    <p>Nội dung ảnh đại diện hiển thị trên máy thành viên :</p>
                    <div class="showImagesAvatar">
                    <div id="avatar-demo">
                    <img class="img-content-avatar" src="{{ URL('images/demo-avatar.png') }}">
                        
                    </div>
                    <p class="limit-text"><strong class="title-demo-incentives-3">Tên chương trình</strong></p>
                    <img width="20%" class="img-circle border logo-merchant" src="{{ URL('') }}/{{ Auth::merchant()->get()->logo }}">
                    <p class="demo-title">Nội dung chương trình ...</p>
                    <div class="clear-fix"></div>
                    </div>
                    <input class="hide" id="array-image-content" data-array-image-content=""/>
                </div>

            </div>
        </form>
    </div>
    <!-- end column lg 6 -->
    
    <div class="col-lg-6">
        <h1 class="title-h1">Hiển thị trên máy thành viên</h1>
        <p>Nội dung chương trình của bạn sẽ hiển thị như bên dưới. Phần chi tiết ưu đãi:</p>
        <div class="fixDemoMobile" style="position:relative">
            <ul class="list-image-incentives-content hide" id="listContentImage">
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                   <!--  <ol class="carousel-indicators" id="carousel_indicators">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    </ol>
 -->
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" id="carousel_inner" role="listbox">
                        <div class="item active" id="item_active"></div>
                    </div>
                    <!-- Left and right controls -->
                    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </ul>

            <p class="title-demo-incentives" data-toggle="tooltip" data-placement="left" title="{{ Auth::merchant()->get()->name }}">{{ Auth::merchant()->get()->name }}</p>
            <p class="title-demo-incentives-2">Tên chương trình</p>
            <p class="content-demo-incentives">Nội dung chương trình ...</p>
            <img class="demo-messages" src="{{ URL() }}/images/demo-incentives.png">
        </div>     
    </div>
    <!-- end column lg 6 -->
    <div class="clearfix"></div>

    <div class="col-lg-12">
        <h1 class="title-h1">Lịch sử chương trình ưu đãi</h1>
        <table class="table table-striped list-send-messages">
            <thead>
            <tr>
                <th class="pink" style="width:5%">ID</th>
                <th class="pink name-of-deal" style="width:30%" >Tên nội dung chương trình</th>
                <th class="pink status-display" style="width:17%">Ảnh</th>
                <th class="pink" style="width:15%">Thời gian</th>
                <th class="pink" style="width:20%">Chức năng</th>
                <th class="pink" style="width:15%">Trạng thái</th>
            </tr>
            </thead>
            <tbody class="list-incentives">
                @if($deals->count() > 0)
                @foreach ($deals as $deal)
            

                <tr>
                    <td>{{ $deal->id }}</td>
                    <td style="text-align:justify; overflow: hidden; text-overflow: ellipsis; ">
                        <div style="overflow: hidden; text-overflow: ellipsis; width: 350px;" class="div-name-of-deal">
                            <span > {{ $deal->name }}</span><br>
                            <span style="font-weight:normal;" id="short-detail-{{ $deal->id }}" class="">
                                {{ mb_substr($deal->description,0,50)."..." }}
                            </span>
                            <span id="full-detail-{{ $deal->id }}"  style="font-weight:normal;" class="hide">
                                {{ $deal->description }}
                            </span>
                            <p id="show-more-{{$deal->id}}" onclick="showMore( {{$deal->id }} );"style="cursor:pointer" class="pink">Xem thêm</p>
                            <p id="show-less-{{$deal->id}}" onclick="showLess( {{$deal->id }} );"style="cursor:pointer" class="pink hide">Rút gọn</p>
                        </div>
                    </td>

                    <td class="status-display"><img src="{{URL::asset('')}}{{$deal->image_avatar}}" width="50"></td>

                    <td><?php echo($deal->time_message); ?></td>
                    @if ($deal->status == 3)
                    <td>

                        <span>
                            <button class="btn btn-pink" id="showDealTasks"  data-toggle="modal" data-target="#dealTaskModal_{{ $deal->id }}" data-deal-name="{{ $deal->name }}">Tác vụ</button>
                        </span>

                        <!-- Modal -->
                        <div id="dealTaskModal_{{ $deal->id }}" class="modal fade" role="dialog">
                          <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">{{ $deal->name }}</h4>
                              </div>
                              <div class="modal-body">
                                 <div class="row">
                                    <span>
                                        <button class="btn btn-primary btn-active-deal" data-deal-id="{{ $deal->id }}">Kích hoạt</button>
                                    </span>
                                    <span>
                                        <button class="btn btn-success btn-edit-deal" data-end-date="{{$deal->end_day}}"  @if($check_level == 1) data-object-apply="{{  getTypeCardName($deal->apply_objects)  }}" @endif data-start-date="{{$deal->start_day}}" data-deal-id="{{$deal->id}}" data-deal-name="{{ $deal->name }}" data-deal-description="{{ $deal->description }}" data-deal-avatar="{{ $deal->image_avatar }}" data-deal-image-content="{{ $deal->image_content }}">Chỉnh sửa</button>
                                    </span>
                                    <span>
                                        <button class="btn btn-danger btn-delete-deal" data-deal-id="{{$deal->id}}">Hủy</button>
                                    </span>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>
                            </div>

                          </div>
                        </div>
                        

                        
                        
                    </td>
                    <td>
                        <span>{{$deal->deal_status}}</span>
                    </td>
                    @elseif ($deal->status == 0)
                    <td>
                        
                    </td>
                    <td>
                        <span>{{$deal->deal_status}} </span>
                    </td>
                    @elseif ($deal->status == 2)
                    <td>
                        
                    </td>
                    <td>
                        <span>{{$deal->deal_status}} &nbsp;
                            
                        </span>
                    </td>
                    @elseif ($deal->status == 1)
                    <td>
                        
                    </td>
                    <td>
                        <span>{{$deal->deal_status}} &nbsp;
                            
                        </span>
                    </td>
                    @endif
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
        {!! $deals->render() !!}
    </div>
    <!-- end column lg 12 -->
    <!-- Modal -->
    <div id="myModal" class="modal fade " role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="min-height: 55px;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"  data-deal-id=""></h4>
                </div>
                <!-- end modal header -->
                <div class="modal-body">
                    <form id="formEditDeal" class="col-lg-12" style="padding-top: 5%;">
                        <div class="col-lg-6">
                            <div class="form-group position-counter">
                                <label for="titleIncentives">Tên chương trình ưu đãi</label>
                                <p id="edit-counter-title" class="counter">60/60</p>
                                <input type="text" class="form-control" required id="editTitleIncentives" name="titleIncentives" maxbyte="60" rows="5" placeholder="Tên chương trình">
                            </div>
                            <div class="form-group position-counter">
                                <label for="contentIncentives">Mô tả chương trình ưu đãi</label>
                                <p id="edit-counter-content" class="counter">500/500</p>
                                <textarea class="form-control" rows="5" required id="editContentIncentives" name="contentIncentives" maxbyte="500" placeholder="Nội dung chương trình"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="start-date">Ngày bắt đầu</label>
                                <input type="text" maxlength="16" class="form-control checkCurrentDate checkEditStartDate" placeholder="Ngày giờ bắt đầu" required id="edit-start-date" name="start-date">
                                <!-- <input type="date" class="form-control checkCurrentDate "  required id="edit-start-date" name="start-date"> -->
                            </div>
                            <div class="form-group">
                                <label for="end-date">Ngày kết thúc</label>
                                
                                 <input type="text" maxlength="16" required class="form-control checkCurrentDate checkEditEndDate" placeholder="Ngày giờ kết thúc"  id="edit-end-date" name="end-date">
                                <!-- <input type="date" required class="form-control checkCurrentDate checkEditEndDate" id="edit-end-date" name="end-date"> -->
                            </div>
                            @if($check_level == 1)
                            <div class="form-group">
                                <label for="edit-object-apply">Đối tượng áp dụng</label>
                                <p class="font-sm">(Ctrl + Click để chọn nhiều đối tượng)</p>
                                <select class="form-control" multiple required id="edit-object-apply" name="object-apply">
                                    @foreach($object_apply as $key => $value)
                                        <option object_apply_id="{{ $value['id'] }}"> {{ $value['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>    
                            @endif
                        </div>
                        <!-- end first column -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <button type="button" class="btn btn-pink img-avatar" id="edit-img-avatar-btn"><i class="fa fa-plus"></i> &nbsp; Thay ảnh đại diện</button>
                                <input type="file" name="image_avatar" value="" id="edit-logo" class="upload-avartar" />
                                <div id="count-edit-image-avatar">
                        
                                </div>
                                <div class="showEditImagesAvatar" id="edit-show-image"></div>
                            </div>   
                            <div class="form-group">
                                <button type="button" class="btn btn-pink img-content" id="edit-img-content-btn"><i class="fa fa-plus"></i> &nbsp; Thay ảnh nội dung</button>
                                <p style="padding-top: 3%;"class="font-sm">(Ctrl + Click để chọn nhiều ảnh)</p>
                                <input type="file"  multiple="" name="image-content[]" value="" id="edit-image-content" class="upload-content checkMaxUploadEditFile" />
                                <ul class="list-image-incentives-content hide" id="listEditContentImage" style="padding-left: 0px;">
                                    <div id="myEditCarousel" class="carousel slide" data-ride="carousel">


                                        <!-- Wrapper for slides -->
                                        <div class="carousel-inner" id="edit_carousel_inner" role="listbox">
                                            <div class="item active"></div>
                                        </div>
                                        <!-- Left and right controls -->
                                        <a class="left carousel-control" href="#myEditCarousel" role="button" data-slide="prev">
                                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="right carousel-control" href="#myEditCarousel" role="button" data-slide="next">
                                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </ul>
                            </div>    
                        </div>
                        <!-- end second column -->
                        
                    </form>
                    <div class="col-lg-12" style="padding-top: 5%;">
                         <input type="button" class="btn btn-pink btn-lg btn-edit-incentives" id="btn-edit-incentives" value="Chỉnh sửa ưu đãi" ></input> 
                    </div>
                </div>
               
            
                <!-- end modal body -->
                <div class="modal-footer">
                </div>
                <!-- end modal footer -->
            </div>
            <!-- end modal content -->
        </div>
    <!-- End modal dialog -->     
    </div>
    <!-- end modal -->
</div>
<!-- end container -->
@stop


@section('js')


<!-- main js for this page -->
<script src="{{ url('/assets/script/create-incentives.js')}}"></script>
<script type="text/javascript">
    
    $( window ).resize(function() {
        $(".div-name-of-deal").attr("style", "overflow: hidden; text-overflow: ellipsis; width:" + $(".name-of-deal").width()*0.95 + "px;");
    });
</script>
<script>
function showMore(id) {
    if(($("#short-detail-"+id).attr("class") == "") && ($("#full-detail-"+id).attr("class") == "hide")) {
        $("#short-detail-"+id).attr("class","hide");
        $("#full-detail-"+id).attr("class","");
    }

    
    $("#show-more-"+id).addClass("hide");
    $("#show-less-"+id).removeClass("hide");

    
}
function showLess(id){
    if(($("#short-detail-"+id).attr("class") == "hide") && ($("#full-detail-"+id).attr("class") == "")) {
        $("#short-detail-"+id).attr("class","");
        $("#full-detail-"+id).attr("class","hide");
    }

    
    $("#show-less-"+id).addClass("hide");
    $("#show-more-"+id).removeClass("hide");
}
</script>


@stop
