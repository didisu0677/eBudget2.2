
function toLogin() {
	window.location = $('#login-page').attr('href');
}
$('#form').submit(function(e){
	e.preventDefault();
	if(validation()){
		$.ajax({
			url : $(this).attr('action'),
			data : $(this).serialize(),
			type : 'POST',
			dataType : 'json',
			success : function(response) {
				if(response.status == 'success') {
					cAlert.open(response.message,response.status,'toLogin');
				} else {
					cAlert.open(response.message,response.status);
				}
			}
		});
	}
});
