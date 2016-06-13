<table>
	<tr>
		<td>
			<a href="{{ url() }}"><img src="{{ url('images/emails/banner1.png') }}" style="width: 100%;"></a>
		</td>
	</tr>
	<tr>
		<td style="padding: 25px 20px 0 20px;">
			<p>Chào {{ $data['name'] }},</p>
			<p>Chúc mừng bạn đã thay đổi thành công mật khẩu. Mật khẩu mới là {{ $data['new_password'] }}</p>
		</td>
	</tr>
	<tr>
		<td style="padding: 0 20px;">
			<p>Tìm hiểu thêm về dịch vụ tại:  [Website icon] [Facebook icon]</p>
		</td>
	</tr>
	<tr>
		<td style="padding: 0 20px;">
			<p>Chân thành,<br/>AbbyCard Team</p>
		</td>
	</tr>
</table>
