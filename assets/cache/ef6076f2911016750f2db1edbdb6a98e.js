
var controller = 'import';
$('.btn-import').click(function(){
	$('#form-import')[0].reset();

    $('#modal-import .alert').hide();
    $('#modal-import').modal('show');

    var val = $('#currency option').eq(0).val();
    if(val){
    	$('#currency').val(val).trigger('change');
    }
});
$(document).ready(function(){
	get_currency();
});
function get_currency(){
	$.ajax({
		url : base_url + 'api/currency_option',
		type : 'post',
		data : {},
		dataType : 'json',
		success : function(response) {
			$('#currency').html(response.data);
		}
	});
}
$(document).on('click','.btn-detail',function(){
	$.get(base_url + 'settings/'+controller+'/detail/' + $(this).attr('data-id'),function(res){
		cInfo.open(lang.detil,res,{modal_lg:true});
	});
});
