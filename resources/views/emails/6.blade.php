<table>
	<tr>
		<td>
			<a href="{{ url() }}"><img src="{{ url('images/emails/banner1.png') }}" style="width: 100%;"></a>
		</td>
	</tr>
	<tr>
		<td style="padding: 25px 20px 0 20px;">
			<p>Chào {{ $data['name'] }},</p>
			<p>Gói dịch vụ {{ $data['package'] }} dành cho {{ $data['trademark'] }} của bạn đã được kích hoạt thành công.</p>
		</td>
	</tr>
	<tr>
		<td style="padding: 0 20px;">
			<div style="text-align: right; border: 2px solid #f2728c; padding: 5px 20px 15px; background-color: #f9cedf; color: #f2728c;">
				<p style="text-align: initial;">Khuyến khích khách hàng của bạn đăng ký thành viên thông qua ứng dụng “Abby”</p>
				<a href="{{ url() }}"><img src="{{ url('images/emails/apple.png') }}" style="width: 100px; height: 33px;"></a>
				<a href="{{ url() }}"><img src="{{ url('images/emails/googleplay.png') }}" style="width: 100px; height: 33px;"></a>
			</div>
		</td>
	</tr>
	<tr>
		<td style="padding: 0 20px;">
			<p>
				- Chương trình thẻ thành viên: {{ $data['trademark'] }}
				<br/>- Gói dịch vụ: {{ $data['package'] }}
				{{-- <br/>- Thời gian hiệu lực: Từ [Ngày bắt đầu] tới [Ngày kết thúc] --}}
			</p>
		</td>
	</tr>
	<tr>
		<td style="padding: 0 20px;">
			<p>Các tiện ích dịch vụ của bạn bao gồm: </p>
			<hr style="border-color: #f9cedf; background-color: #f9cedf; color: #f9cedf; "/>
		</td>
	</tr>
	<tr>
		<td style="text-align: center; padding: 25px 20px 0 20px;">
			<a style="display: inline-table; text-decoration: none; color: #000000;" href="{{ url() }}"><img src="{{ url('images/emails/tichdiem.png') }}"><p style="margin-top: 7px;">Tích & đổi thưởng</p></a>
			<a style="display: inline-table; text-decoration: none; color: #000000;" href="{{ url() }}"><img src="{{ url('images/emails/kiemsoat.png') }}" style="padding: 0 30px;"><p style="margin-top: 7px;">Kiểm soát theo cửa hàng</p></a>
			<a style="display: inline-table; text-decoration: none; color: #000000;" href="{{ url() }}"><img src="{{ url('images/emails/uudai.png') }}"><p style="margin-top: 7px;">Gửi [1] ưu đãi/tháng</p></a>
		</td>
	</tr>
	<tr>
		<td style="text-align: center; padding: 0 20px;">
			<a style="display: inline-table; text-decoration: none; color: #000000;" href="{{ url() }}"><img src="{{ url('images/emails/hailong.png') }}" style="padding: 0 15px;"><p style="margin-top: 7px;">Mức độ hài lòng dịch vụ</p></a>
			<a style="display: inline-table; text-decoration: none; color: #000000;" href="{{ url() }}"><img src="{{ url('images/emails/quanlythanhvien.png') }}" style="padding: 0 0 0 15px;"><p style="margin-top: 7px;">Quản lý thành viên</p></a>
		</td>
	</tr>
	<tr>
		<td style="padding: 0 20px;">
			<hr style="border-color: #f9cedf; background-color: #f9cedf; color: #f9cedf; "/>
			<p>Tìm hiểu thêm về dịch vụ tại:  [Website icon] [Facebook icon]</p>
		</td>
	</tr>
	<tr>
		<td style="padding: 0 20px;">
			<p>Chân thành,<br/>AbbyCard Team</p>
		</td>
	</tr>
</table>
