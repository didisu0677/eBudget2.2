$(document).on('change','[data-provinsi="active"]',function(e){
	e.preventDefault();
	var val  		= $(this).val();
	var temp_id 	= $(this).attr('data-temp_id');
	var to_id 		= $(this).attr('data-to_id');
	kota_option(val,to_id,temp_id);
})

var xhr_ajax_kota 	= null;
var select2_readonly 	= false;
function kota_option(parent,to_id,temp_id){
	if(!parent){
		return '';
	}

	if( xhr_ajax_kota != null ) {
        xhr_ajax_kota.abort();
        xhr_ajax_kota = null;
    }

	$.ajax({
		url 	: base_url+'api/kota_option',
		data 	: {
			parent 	: parent,
		},
		type	: 'post',
		dataType: 'json',
		success	: function(response) {
			select2_readonly = true;
			xhr_ajax_kota = null;
			$(document).find('#'+to_id).html(response.data);
			if(typeof temp_id != 'undefined' && temp_id && $(document).find("#"+to_id+" option[value='"+temp_id+"']").length>0){
				$(document).find('#'+to_id).val(temp_id).trigger('change');
			}

		}
	});
}

$(document).on('change','[data-kota="active"]',function(e){
	e.preventDefault();
	var val  		= $(this).val();
	var temp_id 	= $(this).attr('data-temp_id');
	var to_id 		= $(this).attr('data-to_id');
	kecamatan_option(val,to_id,temp_id);
})
var xhr_ajax_kecamatan 	= null;
var select2_readonly 	= false;
function kecamatan_option(parent,to_id,temp_id){
	if(!parent){
		return '';
	}

	if( xhr_ajax_kecamatan != null ) {
        xhr_ajax_kecamatan.abort();
        xhr_ajax_kecamatan = null;
    }

	$.ajax({
		url 	: base_url+'api/kecamatan_option',
		data 	: {
			parent 	: parent,
		},
		type	: 'post',
		dataType: 'json',
		success	: function(response) {
			select2_readonly = true;
			xhr_ajax_kecamatan = null;
			$(document).find('#'+to_id).html(response.data);
			if(typeof temp_id != 'undefined' && temp_id && $(document).find("#"+to_id+" option[value='"+temp_id+"']").length>0){
				$(document).find('#'+to_id).val(temp_id).trigger('change');
			}

		}
	});
}

setInterval(function(){
	$(document).find('.select2-search__field').attr('readonly',false);
}, 3000);