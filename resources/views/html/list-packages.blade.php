<div class="packages">

	@foreach (getAllPackages() as $row)
	<!-- Foreach Packages -->
	<div class="col-lg-3 col-md-6 col-sm-6 gray">
		<div class="content-package @if(Auth::merchant()->get()->package == $row->id) active @endif">
			<h1>{{ $row->name }}</h1>
			<p>{{ validateStoreMax(json_decode($row->info,true)['stores_max']) }}</p>
			<div class="pricing circle">
				<p class="pay">{{ validateBudget(json_decode($row->info,true)['budget']) }}</p>
			</div>
			<ul>
				<li><strong>Miễn phí</strong> khởi tạo</li>
				<li><strong>Chạy</strong> chương trình</li>
				<li>{{ validateStoreMaxAccount(json_decode($row->info,true)['stores_max']) }} </li>
				<li><strong>{{ json_decode($row->info,true)['deals_max'] }}</strong> ưu đãi</li>
				<li><strong>{{ json_decode($row->info,true)['messages_max'] }}</strong> tin nhắn</li>
				<li>{{ checkFeedback(json_decode($row->info,true)['manager_feedback']) }}</li>
			</ul>
			<a href="{{ URL('merchant/upgrade?package='). $row->id }}"><button class="btn btn-sm register-package">@if (Auth::merchant()->get()->package == $row->id) <i class="fa fa-check"></i> @else Đăng ký @endif </button></a>
		</div>
	</div>
	<!-- end packages -->
	@endforeach

	
</div>
