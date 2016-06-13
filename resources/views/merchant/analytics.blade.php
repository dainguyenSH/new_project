@extends('layouts.master')
@section('css')

@stop
@section('title')
    <div class="title-pages">
        <h2>Số liệu thống kê</h2>
    </div>
@stop

@section('content')
	<div class="feedback-container">
        <div class="col-lg-12">
            <h1 class="title-h1">Thống kê báo cáo</h1>
            <p>Báo cáo dưới đây thể hiện số lượng thành viên từng hạng thẻ. Cập nhật lúc <span class="pink">{{ substr(date('D-m-y H:i:s'), -8) }}</span> ngày <span class="pink">{{ substr(date('Y-m-d H:i:s'),0,-8) }}</span></p>
        
			<div class="rate-start-feedback">

				<div class="row">
                @foreach($datas as $data)


        			<div class="col-lg-3 col-sm-3">
                    
        				<center>
                        @if($type_merchant == 3)
        					<h2>Thẻ {{ $data['card_type_name']}}</h2>
                            <h2 class="rate-lg">{{ price($data['count']) }}</h2>
                        @endif
          				</center>
                    
                        
                    
        			</div><!-- /.col-lg-4 -->

                @endforeach

                @if($type_merchant == 4)
                    <center>
                            <h2>Số lượng thành viên</h2>
                            <h2 class="rate-lg">{{ $data['count_customer'] }}</h2>
                        @endif
                    </center>
        			

        			
      			</div>
			</div>

			<div class="list-feedback">
				<h1 class="title-h1">Biểu đồ</h1>
				<p>Báo cáo dưới đây thể hiện số lượng thành viên từng hạng thẻ. Cập nhật lúc <span class="pink">{{ substr($analystic_info['updated_at'], -8) }}</span> ngày <span class="pink">{{ substr($analystic_info['updated_at'],0,-8) }}</span></p>

                <div class="col-lg-6">
                    <h4 class="pink center">ĐÁNH GIÁ DỊCH VỤ</h4>
                    <div style="width:100%;">
                        <div>
                            <canvas id="canvas_1" data-feedback="@if($analystic_info != null) {{ $analystic_info->feedbacks }} @endif" height="450" width="600"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <h4 class="pink center">TIN NHẮN GỬI THÀNH CÔNG</h4>
                    <div style="width:100%;">
                        <div>
                            <canvas id="canvas_2" data-message="@if($analystic_info != null) {{ $analystic_info->messages }} @endif" height="450" width="600"></canvas>
                        </div>
                    </div>
                </div>
			 
			</div>
        </div>
    </div>

@stop

@section('js')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>


    <script type="text/javascript">
        drawChart("canvas_1","data-feedback");
        drawChart("canvas_2","data-message");
    </script>


@stop
