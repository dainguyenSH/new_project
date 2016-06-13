
@if($infos->count() == 0)
<table class="table table-striped list-customer-panel"> 
    <thead class="list-customer-header">

        <th class="text-center pink">Không tìm thấy kết quả phù hợp</th>
        
    </thead>
</table>
@else
<table class="table table-striped list-customer-panel"> 
    <thead class="list-customer-header">
        <tr>
            <th class="pink">ID</th>
            <th class="pink status-display" >Ảnh</th>
            <th class="pink" >Tên thành viên</th>
            <th class="pink" >Hạng thẻ</th>
            <th class="pink" >@if (Auth::merchant()->get()->kind == 1) Chops @elseif (Auth::merchant()->get()->kind == 2) Điểm @endif</th>
            <th class="pink status-display" style="width:15%">Ngày đăng ký</th>
            <th class="pink" >Trạng thái</th>
            <th class="pink" >Chi tiết</th>
        </tr>
    </thead>
    <tbody class="list-customer-content">
        
        @foreach($infos as $key=>$value)
        <tr>
            <td>{{ $value['id']}}</td>
            <td class="status-display">
            	<img src="{{ $value['avatar']}}" width="50">
            </td>
            <td>{{ $value["name"]}}</td>
                @if($value["level"] != null)
                <td>{{ $value["level"]}}</td>
                @else
                <td>Không có hạng thẻ</td>
            @endif
            <td class="status-display">{{ $value["point"]}}</td>
            <td>{{ $value["created_at"]}}</td>
            
            <td>{{ checkActive($value["status"]) }}</td>
            <td>
               <a href="/merchant/detail/{{ $value['customers_code']}}">
                     <button type="" class="btn btn-sm btn-danger">Xem chi tiết</button>
               </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@if($count_page > 1)
    <ul class="pagination">
        <li style="cursor: pointer;" class="@if($current_page == 0) disabled @endif"><a onclick="@if($current_page == 0) # @else filterAccountPagination({{$current_page - 1}}) @endif"><</a></li>
        @for($i = 0; $i < $count_page; $i++)

            <li style="cursor: pointer;" class="@if($i == $current_page) active pink @endif"><a onclick="filterAccountPagination({{$i}})">{{ $i + 1 }}</a></li>

        @endfor
        <li style="cursor: pointer;" class="@if($current_page == $count_page - 1) disabled @endif"><a onclick="@if($current_page == $count_page - 1)  # @else filterAccountPagination({{$current_page + 1}}) @endif">></a></li>
    </ul>
@endif

@endif
<script type="text/javascript">
    function filterAccountPagination(page){
        console.log("da vao tai current page");
        var status = $('option:selected',$("#filter-account-status")).attr("data-status");
        console.log(status);
        var card_id = $('option:selected',$("#filter-account-type-card")).attr("data-card-id");
        var search_box = $("#account-search-box").val();
        console.log(search_box);
        if(status == 2) {
            // location.reload();
        }
        
        $.loadding();

        $.ajax({
            url: 'filter-account',
            // url: 'filter-account',
            method:'post',
            data : {
                "status" : status,
                "id" : card_id,
                "search_box" : search_box,
                "page" : page
            },
        
            success: function(data){
                $.endload();
                $(".list-customer-panel").empty();
                $('#account_paginator').empty();
                $(".list-customer-panel").append(data.result);
                
            },
            error: function(){},
        });
    }

</script>
