
$(document).ready(function(){
	getData();
});
$('#filter_cabang').on('change',function(){
	getData();
});
function getData(){
	var kode_cabang = $('#filter_cabang option:selected').val();
	if(!kode_cabang){ return ''; }
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/plan_data_kantor/get_data';
	page 	+= '/'+kode_cabang;
	$.ajax({
		url 	: page,
		data 	: {},
		type	: 'get',
		dataType: 'json',
		success	: function(response) {
			cLoader.close();
			cek_autocode();
			if(response){
				v = response;
				$('#id').val(v.id);
				$('#kode_cabang').val(v.kode_cabang);
				$('#kode_cabang').val(v.kode_cabang);
				$('#nama_kantor').val(v.nama_kantor);
				$('#nama_pimpinan').val(v.nama_pimpinan);
				$('#tgl_mulai_menjabat').val(v.tgl_mulai_menjabat);
				$('#no_hp_cp').val(v.no_hp_cp);
				$('#email_Cp').val(v.email_Cp);
				$('#email_lainnya').val(v.email_lainnya);
			}else{
				$('#kode_cabang').val(kode_cabang);
			}
		}
	});
}
