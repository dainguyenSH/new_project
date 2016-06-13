<div class="box-trackby-lv border-color-pink">
    <i id="show-checked-user" class="fa fa-check-square pink position-checked-search checked-user"></i>
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="pink">Ảnh</th>
                <th class="pink">Tên KH</th>
                <th class="pink">Chops còn lại</th>
            </tr>
        </thead>
        <tbody class="list-incentives">
            <tr>
                <td>
                    <img src="{{ $info->avatar }}" width="80">
                </td>
                <td class="name-customer"><br>{{ $info->name }}</td>
                <td><br>{{ price($info->current_point) }} chops</td>
            </tr>
        </tbody>
    </table>
</div>
<div class="show-chop-manage">
    <div class="box-show-chop">
        <div class="foreach-chop">
        @for ($i = 1; $i <= 15 ; $i++)
            <div class="sticker tick-{{ $i }}"></div>
        @endfor
        </div>
    </div>
</div>

<div class="button-add-point-track-by-id">
    <div class="form-group">
        <input type="text" class="form-control order_id" placeholder="Nhập mã hóa đơn" />
    </div>

    <div class="form-group">
        <select class="form-control choice-stick chop-change" id="select-chop-gift-2">
            <option value="">Chọn số lượng chops cần đổi</option>
            @foreach ($config['value'][0]['value'] as $row) { 
                <option value="{{ $row['point'] }}">{{ $row['point'] }} Chops</option>
            @endforeach

        </select>
    </div>


    <div class="clearfix"></div>

    <div class="form-group pull-right">
        <input type="button" class="btn btn-pink" id="change-chop-2" data-id="{{ $info->id }}" value="Xác nhận đổi chops">
    </div>

</div>
