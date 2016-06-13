<div class="box-trackby-lv">
    <i id="show-checked-user" class="fa fa-check-square pink position-checked-search"></i>
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="pink">Ảnh đại diện</th>
                <th class="pink">Tên KH</th>
                <th class="pink">Hạng thẻ </th>
                <th class="pink">Điểm còn lại</th>
            </tr>
        </thead>
        <tbody class="list-incentives">
            <tr>
                <td>
                    <img src="{{ $info->avatar }}" width="80">
                </td>
                <td class="name-customer"><br>{{ $info->name }}</td>
                <td><br>{{ checkLevelCard($info->level) }}</td>
                <td><br>{{ price($info->current_point) }}</td>
            </tr>
        </tbody>
    </table>
</div>


<div class="button-add-point-track-by-id show-box-add-point">
    <div class="form-group">
        <input type="text" class="form-control order_id" placeholder="Nhập mã hóa đơn giảm giá" />
    </div>

    <div class="form-group has-feedback has-feedback-left">
        <p class="value-default">điểm</p>
        <input type="number" name="point" id="order-price" placeholder="Nhập số điểm cần đổi" class="form-control point point_change" value="">
    </div>


    <div class="clearfix"></div>

    <div class="form-group pull-right">
        <input type="button" class="btn btn-pink" id="change-discount-point" data-id="{{ $info->id }}" value="Xác nhận đổi điểm">
    </div>

</div>
