
$(document).ready(function(){
	getData();
});
$('#filter_anggaran').change(function(){
	getData();
});
var xhr_ajax = null;
function getData(){
	cLoader.open(lang.memuat_data + '...');
	$('.table-app tbody').html('');
    if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }

    var page = base_url + 'settings/tarif_kolektibilitas/data';
    page += '/'+ $('#filter_anggaran').val();
  	xhr_ajax = $.ajax({
        url: page,
        type: 'post',
		data : $('#form-filter').serialize(),
        dataType: 'json',
        success: function(res){
        	xhr_ajax = null;
            $('.table-app tbody').html(res.table);
            cLoader.close();
		}
    });
}
function formOpen(){
	var response = response_edit;
	if(typeof response.id != 'undefined') {

	}else{
		var val = $('#filter_anggaran option:selected').val();
		$('#kode_anggaran').val(val).trigger('change');
		$('#form-import #kode_anggaran').val(val).trigger('change');
	}
}
$('.table-app thead input:checkbox').on('click',function(){
	var bool = $(this).is(':checked');
	if(bool){
		$('.table-app tbody input:checkbox').prop('checked',true);
	}else{
		$('.table-app tbody input:checkbox').prop('checked',false);
	}
})
