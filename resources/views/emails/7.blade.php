 <table>
 	<tr>
 		<td>
 			<a href="{{ url() }}"><img src="{{ url('images/emails/banner1.png') }}" style="width: 100%;"></a>
 		</td>
	</tr>
 	<tr>
 		<td style="padding: 25px 20px 0 20px;">
 			<p>Chào {{ $data['name'] }},</p>
 			<p>Chúng tôi đã nhận được yêu cầu thay đổi mật khẩu của bạn. Vui lòng click vào “Đổi mật khẩu” để  tạo  mới.</p>
		</td>
	</tr>
 	<tr>
 		<td style="text-align: center; padding: 25px 20px 0 20px;">
 			<a href="{{ $data['link'] }}"><img src="{{ url('images/emails/doimatkhau.png') }}"></a>
 		</td>
 	</tr>
 	<tr>
 		<td style="padding: 25px 20px 0 20px;">
 			<p>Tìm hiểu thêm về dịch vụ tại:  [Website icon] [Facebook icon]</p>
 		</td>
 	</tr>
 	<tr>
 		<td style="padding: 25px 20px 0 20px;">
 			<p>Chân thành,<br/>AbbyCard Team</p>
 		</td>
 	</tr>
 </table>  
