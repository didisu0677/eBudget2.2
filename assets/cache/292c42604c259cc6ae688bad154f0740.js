
var xhr_ajax = null;
$(document).ready(function(){
	getData();
	loadData2()
});
$('#filter_cabang').on('change',function(){
	getData();
	loadData2();
});
function getData(){
	var kode_cabang = $('#filter_cabang option:selected').val();
	var cabang = $('#filter_cabang').val();
	if(!cabang){ return ''; }
	var page = base_url + 'transaction/data_kantor_budget_planner/get_data';
	page 	+= '/'+$('#filter_anggaran').val();
	page 	+= '/'+$('#filter_cabang').val();
		
	if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }

	if(cabang){
		cLoader.open(lang.memuat_data + '...');
		xhr_ajax = $.ajax({
			url 	: page,
			data 	: {},
			type	: 'get',
			dataType: 'json',
			success	: function(response) {
				xhr_ajax = null;
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
}

var xhr_ajax2 = null;
function loadData2(){

    if( xhr_ajax2 != null ) {
        xhr_ajax2.abort();
        xhr_ajax2 = null;
    }

    var cabang = $('#filter_cabang').val();
    if(!cabang){ return ''; }
    var page = base_url + 'transaction/data_kantor_budget_planner/data2/';
    page += '/'+ $('#filter_anggaran').val();
    page += '/'+ $('#filter_cabang').val();
  	if(cabang){
  		xhr_ajax2 = $.ajax({
	        url: page,
	        type: 'post',
			data : $('#form-filter').serialize(),
	        dataType: 'json',
	        success: function(res){
	        	xhr_ajax2 = null;
	            $('#result2 tbody').html(res.data);				
	        }
	    });
  	}
}

$('#create-berita-acara').click(function(e){
	e.preventDefault();
	$('#modal-berita-acara').modal();
});
