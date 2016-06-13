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
                <td>
                    @if ($row->kind == 4)
                        <a href="{{ URL('admincp/edit-partner/'). '/'. $row->id }}"><button type="button" class="btn btn-xs btn-info">Xem/Sửa</button></a>
                    @elseif ($row->kind == 5)
                        <a href="{{ URL('admincp/edit-new-merchant/'). '/'. $row->id }}"><button type="button" class="btn btn-xs btn-info">Xem/Sửa</button></a>
                    @elseif ($row->kind == 3)
                        <a href="{{ URL('admincp/edit-boo-merchant/'). '/'. $row->id }}"><button type="button" class="btn btn-xs btn-info">Xem/Sửa</button></a>
                    @endif
                </td>
            </tr>
        @endforeach


</tbody>
</table>
{!! $partners->render() !!}
