
var controller = 'konsolidasi_rekaprasio';
var xhr_ajax = null;
$(document).ready(function(){
	resize_window();
	
});
$('#filter_anggaran').change(function(){
	
});
$(document).on('click','.btn-refresh',function(){
	get_data('rekaprasio');
});

function get_data(p1){
    
    $('body').find('#div-result tbody').html('');	
    if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }
    cLoader.open(lang.memuat_data + '...');
    var page = base_url + 'transaction/'+controller+'/rekaprasio_column';
    page += '/'+ $('#filter_anggaran').val();
  	xhr_ajax = $.ajax({
        url: page,
        type: 'post',
		data : $('#form-filter').serialize(),
        dataType: 'json',
        success: function(res){
        	xhr_ajax = null;
        	if(res.status){
                $('body').find('#div-result .d-head').remove();
                $('body').find('#div-result .d-cabang-'+p1).append(res.cabang);
                $('body').find('#div-result .d-'+p1).append(res.month);
                $('body').find('#div-result tbody').append(res.view);

        		cLoader.close();
        		load_more_rekap(0);
        	}else{
        		cAlert.open(res.message);
        		cLoader.close();
        	}
        	checkSubData();
		}
    });
}

var xhr_ajax2 = null;
function load_more_rekap(count){
	if( xhr_ajax2 != null ) {
        xhr_ajax2.abort();
        xhr_ajax2 = null;
    }
    cLoader.open(lang.memuat_data + '...');
    var page = base_url + 'transaction/'+controller+'/load_more_rekap';
  	xhr_ajax2 = $.ajax({
        url: page,
        type: 'post',
		data : {count:count},
        dataType: 'json',
        success: function(res){
        	xhr_ajax2 = null;
        	console.log(count);
        	if(res.status){
        		$.each(res.view,function(k,v){
        			$('body').find('#div-result').find(k).append(v);
        		});
        		cLoader.close();
        		load_more_rekap(res.count);
        	}else{
        		if(res.total_gab){
        			$.each(res.total_gab,function(k,v){
        				$('body').find('#div-result').find('.'+k).after(v);
        			})
        		}
        		cLoader.close();
        	}
        	
		}
    });
}
$(document).on('click','.btn-export',function(){
    var hashids = new Hashids(encode_key);
    var x = hashids.decode($('meta[name="csrf-token"]').attr('content'));
    var cabang = $('#filter_cabang').val();
    var dt_neraca = get_data_table('#div-result');
    var arr_neraca = dt_neraca['arr'];
    var arr_neraca_header = dt_neraca['arr_header'];

    var post_data = {
        "neraca_header" : JSON.stringify(arr_neraca_header),
        "neraca"        : JSON.stringify(arr_neraca),
        "kode_anggaran" : $('#filter_anggaran option:selected').val(),
        "kode_anggaran_txt" : $('#filter_anggaran option:selected').text(),
        "csrf_token"    : x[0],
    }
    var url = base_url + 'transaction/'+controller+'/export';
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
            tableData.each(function() { arrayOfThisRow.push($(this).text()); });
            arr.push(arrayOfThisRow);
        }
    });
    return {'arr' : arr, 'arr_header' : arr_header};
}

// input
$(document).on('dblclick','.table-app tbody td .badge',function(){
    if($(this).closest('tr').find('.btn-input').length == 1) {
        var badge_status    = '0';
        var data_id         = $(this).closest('tr').find('.btn-input').attr('data-id');
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
    console.log(minus); 
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
    $(this)[0].focus();
});
$(document).on('click','.btn-save',function(){
    var i = 0;
    $('.edited').each(function(){
        i++;
    });
    if(i == 0) {
        cAlert.open('tidak ada data yang di ubah');
    } else {
        var msg     = lang.anda_yakin_menyetujui;
        if( i == 0) msg = lang.anda_yakin_menolak;
        cConfirm.open(msg,'save_perubahan');        
    }

});
var n_neraca    = 0;
var n_labarugi  = 0;
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
        var post_name = $(this).attr('data-id').split('-');
        if(post_name[0] == 'neraca'){
            n_neraca = 1;
        }else if(post_name[0] == 'labarugi'){
            n_labarugi = 1;
        }
    });
    
    var jsonString = JSON.stringify(data_edit);
    $.ajax({
        url : base_url + 'transaction/'+controller+'/save_perubahan',
        data    : {
            'json' : jsonString,
            verifikasi : i,
            'kode_anggaran' : $('#filter_anggaran option:selected').val(),
        },
        type : 'post',
        success : function(response) {
            cAlert.open(response,'success','checkReload');
        }
    })
}
function checkReload(){
    loadColumnNeraca('neraca');
}
function formatCurrency(angka, prefix,decimal){
    min_txt     = angka.split("-");
    str_min_txt = '';
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
    split           = number_string.split(','),
    sisa            = split[0].length % 3,
    rupiah          = split[0].substr(0, sisa),
    ribuan          = split[0].substr(sisa).match(/\d{3}/gi);

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
