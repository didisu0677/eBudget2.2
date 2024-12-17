
$(function(){
	resize_window();
	getData();
})
$('#filter_cabang').change(function(){
	getData();
});
var xhr_ajax = null;
function getData() {
	var cabang = $('#filter_cabang').val();
	if(!cabang){ return ''; }
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/rko_jaringan_kantor_new/data';
	page 	+= '/'+$('#filter_anggaran').val();
	page 	+= '/'+$('#filter_cabang').val();

	if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }

	xhr_ajax = $.ajax({
		url 	: page,
		data 	: {},
		type	: 'get',
		dataType: 'json',
		success	: function(response) {
			xhr_ajax = null;
			$('.table-app tbody').html(response.table);
			cLoader.close();
		}
	});
}
$('.btn-export').on('click',function(){
	var cabang = $('#filter_cabang').val();
	if(!cabang){ return ''; }
	var hashids = new Hashids(encode_key);
    var x = hashids.decode($('meta[name="csrf-token"]').attr('content'));
	var url = base_url + 'transaction/rko_jaringan_kantor_new/data';
	url 	+= '/'+$('#filter_anggaran').val();
	url 	+= '/'+$('#filter_cabang').val();
	data_post = {
		export : true,
		"csrf_token"    : x[0],
	}
    $.redirect(url,data_post,"","_blank");
})
