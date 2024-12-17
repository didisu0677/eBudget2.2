
var controller = 'rko_menu';
$(document).ready(function () {
	getData();
});
$('#filter_tahun').change(function(){getData();});
$('#filter_cabang').change(function(){getData();});
function getData() {
	var nama_cabang = $('#filter_cabang option:selected').text();
	$('.cabang-info .cabang').text(nama_cabang);

	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/'+controller+'/data';
	page 	+= '/'+$('#filter_anggaran').val();
	page 	+= '/'+$('#filter_cabang').val();

	$.ajax({
		url 	: page,
		data 	: {},
		type	: 'get',
		dataType: 'json',
		success	: function(response) {
			$.each(response,function(k,v){
				$(k).html(v);
			})
			cLoader.close();
		}
	});
}
