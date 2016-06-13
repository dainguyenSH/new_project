@if (isset($errors) && $errors != null)
@if ($errors->any())
	<script type="text/javascript">
		// BootstrapDialog.show({
  //           title: 'Lỗi',
  //           message: 'Có lỗi. Vui lòng thử lại. Xin cảm ơn !',
  //           buttons: [{
  //               label: 'Đóng',
  //               cssClass: 'btn-pink',
  //               action: function(dialogItself){
  //                   dialogItself.close();
  //               }
  //           }]
  //       });
	</script>
@elseif (session('messages'))
<script type="text/javascript">
	BootstrapDialog.show({
        title: '{{ session('messages')['title'] }}',
        message: "{!! session('messages')['messages'] !!}",
        buttons: [{
            label: 'Đóng',
            cssClass: 'btn-pink',
            action: function(dialogItself){
                dialogItself.close();
            }
        }]
    });
</script>
@endif
@endif

