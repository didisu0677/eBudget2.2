
var controller = 'monthly_performance_operasional';
$('.btn-search').click(function(){
	var cabang 	 = $('#filter_cabang option:selected').val();
	var bulan 	 = $('#filter_bulan option:selected').val();
	var classnya = 'd-'+cabang+'-'+bulan;
	var length = $('.div-content').find('#'+classnya).length;
	if(length>0){
		cLoader.open(lang.memuat_data + '...');
		$('.div-content').find('.d-content').hide();
		$('.div-content').find('#'+classnya).show();
		cLoader.close();
	}else{
		getData();
	}
});
$('.btn-refresh').click(function(){
	getData();
});
var xhr_ajax = null; 
function getData(){
	cLoader.open(lang.memuat_data + '...');
	var cabang 	 = $('#filter_cabang option:selected').val();
	var bulan 	 = $('#filter_bulan option:selected').val();
	var tahun 	 = $('#filter_anggaran option:selected').val();

	if(!cabang){
		return '';
	}

	var classnya = 'd-'+cabang+'-'+bulan;
	var page 	 = base_url + 'transaction/'+controller+'/data/'+tahun+'/'+cabang+'/'+bulan;
	if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }
    $('.div-content').find('#'+classnya).remove();
    $('.div-content').find('.d-content').hide();
	xhr_ajax = $.ajax({
		url 	: page,
		type	: 'post',
		dataType: 'json',
		success	: function(response) {
			xhr_ajax = null;
			cLoader.close();
			$('.div-content').append(response.view);
			checkSubData2(classnya);
			resize_window();
		}
	});
}
function checkSubData2(classnya){
	for (var i = 1; i <= 6; i++) {
		if($(document).find('#'+classnya+' .sb-'+i).length>0){
			var dt = $(document).find('.sb-'+i);
			$.each(dt,function(k,v){
				var text = $(v).html();
				text = text.replaceAll('|-----', "");
				$(v).html('|----- '+text);
			})
		}
	}
}
