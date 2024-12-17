
$(document).ready(function(){
	loadData();

});	

$('#filter_anggaran').change(function(){
	loadData();
});

$('#filter_cabang').change(function(){
	loadData();
});

var xhr_ajax = null;
function loadData(){
	var cabang = $('#filter_cabang').val();
	if(!cabang){
		return '';
	}
	cLoader.open(lang.memuat_data + '...');
	$('.d-content').html('');	
    if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }

    var page = base_url + 'transaction/rincian_kredit/data';
    page += '/'+ $('#filter_anggaran').val();
    page += '/'+ $('#filter_cabang').val();
  	xhr_ajax = $.ajax({
        url: page,
        type: 'post',
		data : $('#form-filter').serialize(),
        dataType: 'json',
        success: function(res){
        	xhr_ajax = null;
            if(res.status){
            	$('.d-content').html(res.view);
            	cLoader.close();
            }else{
            	cAlert.open(res.message);
            	cLoader.close();
            }
		}
    });
}

