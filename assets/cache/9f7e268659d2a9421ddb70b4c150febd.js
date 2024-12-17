
var xhr_ajax = null;
$(document).ready(function(){
	loadData();
	loadDataLaba();
});	

$('#filter_anggaran').change(function(){
	loadData();
	loadDataLaba();
});

$('#filter_cabang').change(function(){
	loadData();
	loadDataLaba();
});

function loadDataLaba(){
	// cLoader.open(lang.memuat_data + '...');
	$('#result2 tbody').html('');	
    // if( xhr_ajax != null ) {
    //     xhr_ajax.abort();
    //     xhr_ajax = null;
    // }

    var page = base_url + 'transaction/valas/dataLaba/';
    page += '/'+ $('#filter_anggaran').val();
    page += '/'+ $('#filter_cabang').val();
  	xhr_ajax = $.ajax({
        url: page,
        type: 'post',
		data : $('#form-filter').serialize(),
        dataType: 'json',
        success: function(res){
        	xhr_ajax = null;
            $('#result2 tbody').html(res.table);
            checkSubData();
            // cLoader.close();
		}
    });
}

function loadData(){
	$('#result1 tbody').html('');	
    // if( xhr_ajax != null ) {
    //     xhr_ajax.abort();
    //     xhr_ajax = null;
    // }
    cLoader.open(lang.memuat_data + '...');
    var page = base_url + 'transaction/valas/dataNeraca/';
    page += '/'+ $('#filter_anggaran').val();
    page += '/'+ $('#filter_cabang').val();
  	xhr_ajax = $.ajax({
        url: page,
        type: 'post',
		data : $('#form-filter').serialize(),
        dataType: 'json',
        success: function(res){
        	xhr_ajax = null;
        	$('#result1 tbody').append(res.view);
        	if(res.status){
        		loadMore(res.count);
        	}else{
        		cLoader.close();
        	}
		}
    });


}

function loadMore(count){
	var page = base_url + 'transaction/valas/loadMore';
    page += '/'+ $('#filter_anggaran').val();
    page += '/'+ $('#filter_cabang').val();
    page += '/'+ count;
    $.ajax({
        url: page,
        type: 'post',
		data : {count:count},
        dataType: 'json',
        success: function(res){
        	xhr_ajax = null;
        	$('#result1 tbody').append(res.view);
        	if(res.status){
        		loadMore(res.count);
        	}else{
        		cLoader.close();
        		checkSubData();
        	}
		}
    });
}
