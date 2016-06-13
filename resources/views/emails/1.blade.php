<table>
	<tr>
		<td>
			<a href="{{ url() }}"><img src="{{ url('images/emails/banner2.png') }}" style="width: 100%;"></a>
		</td>
	</tr>
	<tr>
		<td style="padding: 25px 20px 0 20px;">
			<p>Chào {{$data['name']}},</p>
			<p>Chúng tôi nhận được thông báo tạo tài khoản quản trị chương trình thẻ thành viên AbbyCard qua địa chỉ Email này, vui lòng xác nhận bằng cách click vào nút xác nhận bên dưới</p>
			<p>Nếu không phải bạn. Vui lòng bỏ qua tin nhắn này.</p>
		</td>
	</tr>
	<tr>
		<td style="text-align: center; padding: 0 20px;">
			<a href="{{$data['slug']}}"><img src="{{ url('images/emails/xacnhanemail.png') }}"></a>
		</td>
	</tr>
	<tr>
		<td style="padding: 0 20px;">
			<p>Chân thành,<br/>AbbyCard Team</p>
		</td>
	</tr>
</table>
