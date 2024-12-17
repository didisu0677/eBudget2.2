
$(document).ready(function(){
	get_coa();
});
function get_coa(){
	$.ajax({
		url : base_url + 'api/coa_option',
		type : 'post',
		data : {},
		dataType : 'json',
		success : function(response) {
			$('#coa').html(response.data);
		}
	});
}
