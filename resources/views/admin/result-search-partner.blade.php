<table class="table table-striped">
    <thead>
        <tr>
            <th class="pink">ID</th>
            <th class="pink">Thương hiệu</th>
            <th class="pink">Trạng thái</th>
        </tr>
    </thead>
    <tbody class="list-partner">

    @if ($partners->count() != 0)
        @foreach ($partners as $row)
        <tr>
            <td>{{ $row->id }}</td>
            <td>{{ $row->name }}</td>
            <td>{{ checkActive($row->active) }}</td>
        </tr>
        @endforeach
    @else
        <tr style="background:none">
            <td colspan="100%" >
                <div class="alert alert-success alert-block fade in">
                    <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                    <center>
                        <p>Không có dữ liệu !</p>
                    </center>
                </div>
            </td>
        </tr>
    @endif


    </tbody>
</table>
{!! $partners->render() !!}
