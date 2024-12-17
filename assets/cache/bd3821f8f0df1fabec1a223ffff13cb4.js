
$(document).ready(function(){
	resize_window();
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
	$('#result1 tbody').html('');	
    if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }
    var cabang = $('#filter_cabang').val();
    if(cabang){
    	cLoader.open(lang.memuat_data + '...');
	    var page = base_url + 'transaction/laba_rugi_op/data/';
	    page += '/'+ $('#filter_anggaran').val();
	    page += '/'+ $('#filter_cabang').val();
	  	xhr_ajax = $.ajax({
	        url: page,
	        type: 'post',
			data : $('#form-filter').serialize(),
	        dataType: 'json',
	        success: function(res){
	        	xhr_ajax = null;
	            $('#result1 tbody').html(res.table);	
	            cLoader.close();
	            checkSubData();
			}
	    });
    }
  	
}
