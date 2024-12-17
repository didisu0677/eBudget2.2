
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

var xhr_ajax = null;
function loadData(){
	
	$('#result1 tbody').html('');	
    if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }
	cLoader.open(lang.memuat_data + '...');
    var page = base_url + 'transaction/laba_rugi_new/data/';
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
            checkSubData();

			kurangSelisih();
            cLoader.close();
		}
    });
}

$(document).on('dblclick','.table-app tbody td .badge',function(){
	if($(this).closest('tr').find('.btn-input').length == 1) {
		var badge_status 	= '0';
		var data_id 		= $(this).closest('tr').find('.btn-input').attr('data-id');
		if( $(this).hasClass('badge-danger') ) {
			badge_status = '1';
		}
		active_inactive(data_id,badge_status);
	}
});

$(document).on('focus','.edit-value',function(){
	$(this).parent().removeClass('edited');
	var val = $(this).text();
	var minus = val.includes("(");
	if(minus){
		val = val.replace('(','');
		val = val.replace(')','');
		$(this).text('-'+val);
	}
});
$(document).on('blur','.edit-value',function(){
	var tr = $(this).closest('tr');
	if($(this).text() != $(this).attr('data-value')) {
		$(this).addClass('edited');
	}
	if(tr.find('td.edited').length > 0) {
		tr.addClass('edited-row');
	} else {
		tr.removeClass('edited-row');
	}
	var val = $(this).text();
	var minus = val.includes("-");
	if(minus){
		val = val.replace('-','');
		$(this).text('('+val+')');
	}
});

$(document).on('blur','.edit-bulan',function(){
	var tr = $(this).closest('tr');
	if($(this).text() != $(this).attr('data-value')) {
		$(this).addClass('editedBulan');
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

$(document).on('click','.btn-remove',function(){
	var dt_id = $(this).attr('data-id');
	if(dt_id){
		del_id 		= $('#filter_cabang option:selected').val();
		urlDelete 	= base_url+"transaction/laba_rugi_new/delete_adj";
		cConfirm.open(lang.anda_yakin_menghapus_data_ini + '?','deleteData');
	}
})

$(document).on('click','.btn-adj',function(){
	var i = 0;
	$('.sdbulan').each(function(){
		i++;
	});
	if(i == 0) {
		cAlert.open('tidak ada data yang di ubah');
	} else {
		var msg 	= lang.anda_yakin_menyetujui;
		if( i == 0) msg = lang.anda_yakin_menolak;
		cConfirm.open(msg,'save_adj');        
	}

});

$(document).on('keyup','.edit-value',function(e){
	var n = $(this).text();
	n = formatCurrency(n,'',2);
    $(this).text(n.toLocaleString());
    var selection = window.getSelection();
	var range = document.createRange();
	selection.removeAllRanges();
	range.selectNodeContents($(this)[0]);
	range.collapse(false);
	selection.addRange(range);
	// $(this)[0].focus();
});
function formatCurrency(angka, prefix,decimal){
	min_txt     = angka.split("-");
    str_min_txt = '';
	var number_string = angka.replace(/[^,\d]/g, '').toString(),
	split   		= number_string.split(','),
	sisa     		= split[0].length % 3,
	rupiah     		= split[0].substr(0, sisa),
	ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

	// tambahkan titik jika yang di input sudah menjadi angka ribuan
	if(ribuan){
		separator = sisa ? '.' : '';
		rupiah += separator + ribuan.join('.');
	}
	if(split[1] != undefined && split[1].toString().length > decimal){
		console.log(split[1].toString().length);
		split[1] = split[1].substr(0,decimal);
	}
	if(min_txt.length == 2){
      str_min_txt = "-";
    }
	rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	// return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
	return str_min_txt+rupiah;
}
// $(document).on('keypress','.edit-value',function(e){
// 	var wh 			= e.which;
// 	if (e.shiftKey) {
// 		if(wh == 0) return true;
// 	}
// 	if(e.metaKey || e.ctrlKey) {
// 		if(wh == 86 || wh == 118) {
// 			$(this)[0].onchange = function(){
// 				$(this)[0].innerHTML = $(this)[0].innerHTML.replace(/[^0-9\-]/g, '');
// 			}
// 		}
// 		return true;
// 	}
// 	if(wh == 0 || wh == 8 || wh == 45 || (48 <= wh && wh <= 57) || (96 <= wh && wh <= 105)) 
// 		return true;
// 	return false;
// });

function kurangSelisih(){
	var b;
    for(var i=1;i<=12;i++){
	    var data 	= $("#input"+i).text();
	    var laba 	= $("#labarugi_"+i).text();

	    var minus = data.includes("(");
		if(minus){
			data = data.replace('(','');
			data = data.replace(')','');
			data = '-'+data;
		}
		var datax  	= formatCurrency(data,'',2);

		var minus = laba.includes("(");
		if(minus){
			laba = laba.replace('(','');
			laba = laba.replace(')','');
			laba = '-'+laba;
		}
		var labax 	= formatCurrency(laba,'',2);

	    data = '';
	    laba = '';

	    $.each(datax.split('.'),function(k,v){
	    	data += ''+v;
	    });
	    $.each(labax.split('.'),function(k,v){
	    	laba += ''+v;
	    });
	    
        if(data != ''){
            b  = parseInt(b) + parseInt(data);
        }else {
            b = b;
        }

        if(i == 1){
            b = data;
        }
	    
	    var c = parseInt(b);
	    var d = parseInt(laba) - parseInt(b);

	    // console.log('laba '+ laba+' || data inpu '+b+' || hasil '+d);

	    var e = parseInt(d);
	    $("#hasil"+i).text(numberFormat(c,0,',','.'));


	    $("#selisih"+i).text(numberFormat(e,0,',','.'));

        
	}
}

$(document).on('keyup','.cuan',function(e){
	var b;
    for(var i=1;i<=12;i++){
	    var data 	= $("#input"+i).text();
	    var laba 	= $("#labarugi_"+i).text();

	    var minus = data.includes("(");
		if(minus){
			data = data.replace('(','');
			data = data.replace(')','');
			data = '-'+data;
		}
		var datax  	= formatCurrency(data,'',2);

		var minus = laba.includes("(");
		if(minus){
			laba = laba.replace('(','');
			laba = laba.replace(')','');
			laba = '-'+laba;
		}
		var labax 	= formatCurrency(laba,'',2);

	    data = '';
	    laba = '';

	    $.each(datax.split('.'),function(k,v){
	    	data += ''+v;
	    });
	    $.each(labax.split('.'),function(k,v){
	    	laba += ''+v;
	    });
	    
        if(data != ''){
            b  = parseInt(b) + parseInt(data);
        }else {
            b = b;
        }

        if(i == 1){
            b = data;
        }
	    
	    var c = parseInt(b)
	    var d = parseInt(laba) - parseInt(b);

	    // console.log('laba '+ laba+' || data inpu '+b+' || hasil '+d);

	    var e = parseInt(d)
	    $("#hasil"+i).text(numberFormat(c,0,',','.'));
	    $("#selisih"+i).text(numberFormat(e,0,',','.'));
        
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

	// var tahun_anggaran = $('#filter_anggaran option:selected').val();
	// var coa = $('#filter_coa').val();
	var page = base_url + 'transaction/laba_rugi_new/save_perubahan';
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



function save_adj() {
	var data_edit = {};
	var i = 0;
	
	$('.adj').each(function(){
		var content = $(this).children('div');
		if(typeof data_edit[$(this).attr('data-id')] == 'undefined') {
			data_edit[$(this).attr('data-id')] = {};
		}
		data_edit[$(this).attr('data-id')][$(this).attr('data-name')] = $(this).text();
		i++;
	});

	$('.pdbulan').each(function(){
		var content = $(this).children('div');
		if(typeof data_edit[$(this).attr('data-id')] == 'undefined') {
			data_edit[$(this).attr('data-id')] = {};
		}
		data_edit[$(this).attr('data-id')][$(this).attr('data-name')] = $(this).text();
		i++;
	});


	$('.sdbulan').each(function(){
		var content = $(this).children('div');
		if(typeof data_edit[$(this).attr('data-id')] == 'undefined') {
			data_edit[$(this).attr('data-id')] = {};
		}
		data_edit[$(this).attr('data-id')][$(this).attr('data-name')] = $(this).text();
		i++;
	});
	
	var jsonString = JSON.stringify(data_edit);	
	// var tahun_anggaran = $('#filter_anggaran option:selected').val();
	// var coa = $('#filter_coa').val();
	var page = base_url + 'transaction/laba_rugi_new/save_adj';
    page += '/'+ $('#filter_anggaran').val();
    page += '/'+ $('#filter_cabang').val();
    page += '/'+ $('#ke').val();
    page += '/'+ $('#di').val();
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
$(document).on('click','.btn-export',function(){
    var hashids = new Hashids(encode_key);
    var x = hashids.decode($('meta[name="csrf-token"]').attr('content'));
    var cabang = $('#filter_cabang').val();
    var dt_neraca = get_data_table('#result1');
    var arr_neraca = dt_neraca['arr'];
    var arr_neraca_header = dt_neraca['arr_header'];

    var post_data = {
        "neraca_header" : JSON.stringify(arr_neraca_header),
        "neraca"        : JSON.stringify(arr_neraca),
        "kode_anggaran" : $('#filter_anggaran option:selected').val(),
        "kode_anggaran_txt" : $('#filter_anggaran option:selected').text(),
        "kode_cabang"   : $('#filter_cabang option:selected').val(),
        "kode_cabang_txt"   : $('#filter_cabang option:selected').text(),
        "csrf_token"    : x[0],
    }
    var url = base_url + 'transaction/laba_rugi_new/export';
    $.redirect(url,post_data,"","_blank");
});
function get_data_table(classnya){
    var arr = [];
    var arr_header = [];
    var no = 0;
    var index_cabang = 0;
    $(classnya+" table tr").each(function() {
        var arrayOfThisRowHeader = [];
        var tableDataHeader = $(this).find('th');
        if (tableDataHeader.length > 0) {
            if(no == 0){
                arrayOfThisRowHeader.push("");
                arrayOfThisRowHeader.push("");
                arrayOfThisRowHeader.push("");
                arrayOfThisRowHeader.push("");
                tableDataHeader.each(function(k,v) {
                    var val = $(this).text();
                    if(val && val != '-'){
                        if(index_cabang != 0){
                            arrayOfThisRowHeader.push("");
                        }
                        index_cabang++;
                        arrayOfThisRowHeader.push($(this).text());
                        for (var i = 1; i <= 11; i++) {
                            arrayOfThisRowHeader.push("");
                        }
                    }
                });
                arr_header.push(arrayOfThisRowHeader);
            }

            if(no == 1){
                tableDataHeader.each(function(k,v) {
                    var val = $(this).text();
                    arrayOfThisRowHeader.push($(this).text());
                });
                arr_header.push(arrayOfThisRowHeader);

                arr.push(arrayOfThisRowHeader);
            }
            no++; 
        }

        var arrayOfThisRow = [];
        var tableData = $(this).find('td');
        if (tableData.length > 0) {
            tableData.each(function() {
                var val = $(this).text();
                if($(this).hasClass('sb-1')){
                    val = '     '+$(this).text();
                }else if($(this).hasClass('sb-2')){
                    val = '          '+$(this).text();
                }else if($(this).hasClass('sb-3')){
                    val = '               '+$(this).text();
                }else if($(this).hasClass('sb-4')){
                    val = '                    '+$(this).text();
                }else if($(this).hasClass('sb-5')){
                    val = '                         '+$(this).text();
                }else if($(this).hasClass('sb-6')){
                    val = '                              '+$(this).text();
                }
                arrayOfThisRow.push(val); 
            });
            arr.push(arrayOfThisRow);
        }
    });
    return {'arr' : arr, 'arr_header' : arr_header};
}
$(document).on('click','.btn-warning',function(){
	cAlert.open('Terdapat Nilai yang lebih kecil dari bulan sebelumnya. Silahkan dicek kembali.','warning');
});
$(document).on('click','.btn-info-coa',function(){
	cAlert.open('Terdapat Aksi edit dan aksi selisih pada coa yang sama. silahkan cek kembali.','warning');
});
