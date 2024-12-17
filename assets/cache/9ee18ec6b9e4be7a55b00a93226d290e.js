
var length_cabang = $('#cabang').length;
var xhr_ajax = null;
$('button[type="submit"]').on('click',function(){
	var kode_anggaran 	= $('#filter_anggaran option:selected').val();
	var cabang 		  	= $('#cabang option:selected').val();
	var rencana 	  	= $('#rencana option:selected').val();
	var tahapan 		= $('#tahapan option:selected').val();
	var jenis_kantor 	= $('#jenis_kantor option:selected').val();
	var keterangan 		= $('#keterangan option:selected').val();
	var status_kantor 		= $('#status_kantor option:selected').val();

	var data_post = {
		kode_anggaran 	: kode_anggaran,
		length_cabang 	: length_cabang,
		cabang 			: cabang,
		rencana 		: rencana,
		tahapan 		: tahapan,
		jenis_kantor 	: jenis_kantor,
		keterangan 		: keterangan,
		status_kantor 	: status_kantor,
	};

	if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }
    cLoader.open(lang.memuat_data + '...');
    var page = base_url + 'transaction/usulan_kantor_rekap/data';
    xhr_ajax = $.ajax({
		url 	: page,
		data 	: data_post,
		type	: 'post',
		dataType: 'json',
		success	: function(response) {
			xhr_ajax = null;
			$('#result1 tbody').html(response.table);
			cLoader.close();
		}
	});

})
$('button[type="reset"]').on('click',function(){
	$('.content-body select').val(0).trigger('change');
})
var btn_filter = true;
$('#btn-filter').on('click',function(){
	if(btn_filter){
		btn_filter = false;
		$('.div-filter').hide(300);
		$('#btn-filter').html('Tampilkan Filter');
	}else{
		btn_filter = true;
		$('.div-filter').show(300);
		$('#btn-filter').html('Sembunyikan Filter');
	}
})
$('#btn-export').on('click',function(){
	var hashids = new Hashids(encode_key);
    var x = hashids.decode($('meta[name="csrf-token"]').attr('content'));

	var kode_anggaran 	= $('#filter_anggaran option:selected').val();
	var cabang 		  	= $('#cabang option:selected').val();
	var rencana 	  	= $('#rencana option:selected').val();
	var tahapan 		= $('#tahapan option:selected').val();
	var jenis_kantor 	= $('#jenis_kantor option:selected').val();
	var keterangan 		= $('#keterangan option:selected').val();
	var status_kantor 		= $('#status_kantor option:selected').val();

	var data_post = {
		kode_anggaran 	: kode_anggaran,
		length_cabang 	: length_cabang,
		cabang 			: cabang,
		rencana 		: rencana,
		tahapan 		: tahapan,
		jenis_kantor 	: jenis_kantor,
		keterangan 		: keterangan,
		status_kantor 	: status_kantor,
		export 			: true,
		"csrf_token"    : x[0],
	};
    var url = base_url + 'transaction/usulan_kantor_rekap/data';
    $.redirect(url,data_post,"","_blank");
})
