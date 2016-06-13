<div class="welcome">
	<div class="row">

		<div class="col-md-4 col-md-offset-4">
			<img class="img-welcome" src="{{ URL('images/logo/welcome.png') }}">
			<hr class="style-two">
		</div>

		<div class="col-md-8 col-md-offset-2">
			<h1>Chương trình thẻ thành viên thông minh</h1>
				<h2><i class="fa fa-angle-double-down"></i></h2>
			<div class="row">
				<div class="col-md-4 align-box">
					<div class="box img-circle welcome-circle">
						<img src="{{ URL('images/logo/welcome-left.png') }}">
					</div>
					<h1>Thu ngân</h1>
				</div>

				<div class="col-md-4 align-box">
					<div class="box img-circle welcome-circle active">
						<img src="{{ URL('images/logo/welcome-center.png') }}">
					</div>
					<h2><i class="fa fa-angle-double-up"></i></h2>
				</div>

				<div class="col-md-4 align-box">
					<div class="box img-circle welcome-circle">
						<img src="{{ URL('images/logo/welcome-right.png') }}">
					</div>
					<h1>Mobile App</h1>
				</div>

			</div>
			<h1>Quản trị</h1>
		</div>

		@if (Auth::merchant()->get()->package == 0)
		<div class="col-md-4 col-md-offset-4">
			<center>
				<a href="{{ URL('merchant/initialize-card') }}"><button class="btn btn-pink">KHỞI TẠO THẺ</button></a>
			</center>
		</div>
		@endif


	</div>
</div>
