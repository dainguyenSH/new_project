<table>
	<tr>
		<td>
			<a href="{{ url() }}"><img src="{{ url('images/emails/banner1.png') }}" style="width: 100%;"></a>
		</td>
	</tr>
	<tr>
		<td style="padding: 25px 20px 0 20px;">
			<p>Chào {{ $data['name'] }},</p>
			<p>Chúng tôi đã nhận được yêu cầu thay đổi gói dịch vụ của bạn. Thông tin gói dịch vụ cần thay đổi:</p>
			<p>
				- Chương trình thẻ thành viên: {{ $data['trademark'] }}
				<br/>- Gói dịch vụ: {{ $data['package'] }}
				{{-- <br/>- Thời gian hiệu lực: Từ [Ngày bắt đầu] tới [Ngày kết thúc] --}}
			</p>
		</td>
	</tr>
	<tr>
		<td style="padding: 0 20px;">
			<p>Tìm hiểu thêm về các gói dịch vụ tại: <a href="{{ URL('merchant/upgrade') }}">Các gói dịch vụ</a></p>
		</td>
	</tr>
	<tr>
		<td style="padding: 0 20px;">
			<p>Chân thành,<br/>AbbyCard Team</p>
		</td>
	</tr>
</table>
