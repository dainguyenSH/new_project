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

    <!-- <div class="form-group has-feedback has-feedback-left">
        <p class="value-default">vnđ</p>
        <input type="number" name="point" id="order-price" placeholder="Nhập giá trị hóa đơn" class="form-control point point_change" value="">
    </div> -->

    <div class="form-group">
        <div class="input-group">
            <input type="text" name="point" id="order-price" data-v-min="0" data-v-max="5000000" class="form-control currentcy point point_change" aria-label="VND" placeholder="Nhập giá trị hóa đơn (tối đa 5 triệu đồng)" value="">
            <span class="input-group-addon">VNĐ</span>
        </div>
    </div>

    <p class="note">Điểm thưởng tương ứng = <span class="pink"><span id="point-changed">0</span> Chops</span></p>

    <div class="clearfix"></div>

    <div class="form-group pull-right">
        <input type="button" class="btn btn-pink" id="change-chop" data-id="{{ $info->id }}" value="Xác nhận tích {{ checkTypeCardByStore(Auth::store()->get()->merchants_id) }}">
    </div>

</div>
