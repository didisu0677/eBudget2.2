
$(document).ready(function(){
	resize_window();
	var cabang = $('#filter_cabang').val();
	if(cabang){
		loadData();
	}

});	

$('#filter_anggaran').change(function(){
	loadData();
});

$('#filter_cabang').change(function(){
	loadData();
});


$(document).on('focus','.edit-value',function(){
	$(this).parent().removeClass('edited');
});
$(document).on('blur','.edit-value',function(){
	var tr = $(this).closest('tr');
	var val = Math.round($(this).attr('data-value'));
	var txtInput = '';
	$.each($(this).text().split('.'),function(k,v){
		txtInput += v;
	});

	var minus = txtInput.includes("(");
	if(minus){
		txtInput = txtInput.replace('(','');
		txtInput = txtInput.replace(')','');
		txtInput = '-'+txtInput;
	}

	console.log('val '+val);
	console.log('text '+txtInput);

	if(txtInput != val) {
		$(this).addClass('edited');
	}else{
		$(this).removeClass('edited');
	}
	if(tr.find('td.edited').length > 0) {
		tr.addClass('edited-row');
	} else {
		tr.removeClass('edited-row');
	}
});



$(document).on('click','.btn-save',function(){
	var i = 0;
	$('.edited').each(function(){
		i++;
	});
	if(i == 0) {
		cAlert.open('tidak ada data yang di ubah');
	} else {
		var msg 	= lang.anda_yakin_menyetujui;
		if( i == 0) msg = lang.anda_yakin_menolak;
		cConfirm.open(msg,'save_perubahan');        
	}

});



var xhr_ajax = null;
function loadData(){
	cLoader.open(lang.memuat_data + '...');
	$('#result1 tbody').html('');	
    if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }

    var page = base_url + 'transaction/formula_aktiva_inv/data/';
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
            loadData2();
		}
    });
}


var xhr_ajax2 = null;
function loadData2(){
	cLoader.open(lang.memuat_data + '...');
	$('#result2 tbody').html('');	
    if( xhr_ajax2 != null ) {
        xhr_ajax2.abort();
        xhr_ajax2 = null;
    }

    var page = base_url + 'transaction/formula_aktiva_inv/dataSewa/';
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
            cLoader.close();
		}
    });
}

$(document).on('click','.btn-save',function(){
	var i = 0;
	$('.edited').each(function(){
		i++;
	});
	if(i == 0) {
		cAlert.open('tidak ada data yang di ubah');
	} else {
		var msg 	= lang.anda_yakin_menyetujui;
		if( i == 0) msg = lang.anda_yakin_menolak;
		cConfirm.open(msg,'save_perubahan');        
	}

});


function save_perubahan() {
	var data_edit = {};
	var i = 0;
	
	$('.edited').each(function(){
		var content = $(this).children('div');
		if(typeof data_edit[$(this).attr('data-id')] == 'undefined') {
			data_edit[$(this).attr('data-id')] = {};
		}
		data_edit[$(this).attr('data-id')][$(this).attr('data-name')] = $(this).text();
		i++;
	});
	
	var jsonString = JSON.stringify(data_edit);		
	var page = base_url + 'transaction/formula_aktiva_inv/save_perubahan';
    page += '/'+ $('#filter_anggaran').val();
    page += '/'+ $('#filter_cabang').val();
	$.ajax({
		url : page,
		data 	: {
			'json' : jsonString,
			verifikasi : i
		},
		type : 'post',
		success : function(response) {
			cAlert.open(response,'success','refreshData');
		}
	})
}

$(document).on('click','.btn-remove',function(){
	var dt_id = $(this).attr('data-id');
	if(dt_id){
		del_id 		= dt_id+"-"+$('#filter_cabang option:selected').val();
		urlDelete 	= base_url+"transaction/formula_aktiva_inv/delete";
		cConfirm.open(lang.anda_yakin_menghapus_data_ini + '?','deleteData');
	}
})
