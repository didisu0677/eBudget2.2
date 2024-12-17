
var dt_coa 	 = '';
var dt_index = 0;
$(document).ready(function () {
	get_coa();
	getData();
});
function get_coa(){
	var url = base_url+"api/coa_option";
	cLoader.open(lang.memuat_data + '...');
	xhr_ajax = $.ajax({
		url 	: url,
		type	: 'get',
		dataType: 'json',
		success	: function(response) {
			xhr_ajax = null;
			dt_coa = response.data;
			cLoader.close();
		}
	});
}
var xhr_ajax_table = null;
function getData(){
	var url = base_url+"settings/m_coa_antar_kantor/data";
	cLoader.open(lang.memuat_data + '...');
	if( xhr_ajax_table != null ) {
        xhr_ajax_table.abort();
        xhr_ajax_table = null;
    }
	xhr_ajax_table = $.ajax({
		url 	: url,
		type	: 'get',
		dataType: 'json',
		success	: function(response) {
			xhr_ajax_table = null;
			$('#tbl-result tbody').html(response.table);
			cLoader.close();
		}
	});
}
function formOpen() {
	dt_index = 0;
	response_data = response_edit;
	$('#result2 tbody').html('');
	if(typeof response_data.detail != 'undefined') {
		var list = response_data.data;
		$('.btn-add-item').hide();
		$('#id').val(response_data.detail.id);
		$.each(list, function(x,v){
			if(x == 0){
				add_item(1);
			}else{
				add_item_activity(dt_index);
			}
			var f = $('#result2 tbody tr').last();
			f.find('.coa'+dt_index).val(v.coa).trigger('change');
			f.find('.coa_lawan'+dt_index).val(v.coa_lawan).trigger('change');
			f.find('.dt_id'+dt_index).val(v.id);
		})
	}else{
		$('.btn-add-item').show();
		add_item(0);
	}
}

$(document).on('click','.btn-add-item',function(){
	add_item(0);
});
$(document).on('click','.btn-remove',function(){
	key = $(this).data('id');
	$('#result2 tbody .dt'+key).remove();
});
function add_item(key){
	dt_index += 1;
	var item = '';
	var item_btn = '';
	var item_class = '';
	if(key == 0){
		item_btn = '<button type="button" data-id="'+dt_index+'" class="btn btn-sm btn-icon-only btn-info btn-add-item-activity"><i class="fa-plus"></i></button>';
	}else if(key == 1){
		item_btn == '';
	}else{
		item_class = ' mt-1';
		item_btn = '<button type="button" data-id="'+dt_index+'" class="btn btn-sm btn-icon-only btn-warning btn-delete-item-activity"><i class="fa-times"></i></button>';
	}
	var konten = '<tr class="dt'+dt_index+'">';
		konten += '<td class="remove_dt'+dt_index+'"><button data-id="'+dt_index+'" type="button"class="btn btn-sm btn-icon-only btn-danger btn-remove"><i class="fa-times"></i></button></td>';
		konten += `<td class="index_dt`+dt_index+` style-select2"><select style="width:100%" class="form-control pilihan coa`+dt_index+`" name="coa[]" data-validation="required">`+dt_coa+`</select>
			<input type="hidden" name="dt_index[]" class="dt_index" value=`+dt_index+`>
			</td>`;
		konten += `<td class="style-select2"><select style="width:100%" class="form-control pilihan2 coa_lawan`+dt_index+`" name="coa_lawan`+dt_index+`[]" data-validation="required">`+dt_coa+`</select>
			<input type="hidden" name="dt_id`+dt_index+`[]" class="dt_id`+dt_index+`">
			</td>`;
		konten += '<td>'+item_btn+'</td>';
	konten += '</tr>';
	$('#result2 tbody').append(konten);
	var $t = $('#result2 .pilihan').last();
	$.each($t,function(k,o){
		var $o = $(o);
		$o.select2({
			dropdownParent : $o.parent(),
			placeholder : ''
		});
	})
	var $t = $('#result2 .pilihan2').last();
	$.each($t,function(k,o){
		var $o = $(o);
		$o.select2({
			dropdownParent : $o.parent(),
			placeholder : ''
		});
	})
}
$(document).on('click','.btn-add-item-activity',function(){
	add_item_activity($(this).data('id'),1);
});
$(document).on('click','.btn-delete-item-activity',function(){
	key = $(this).data('id');
	$(this).closest('tr').remove();

	var count = $('#result2 tbody .dt'+key).length;
	$('#result2 tbody .index_dt'+key).attr('rowspan',count);
	$('#result2 tbody .remove_dt'+key).attr('rowspan',count);
});
function add_item_activity(key,p1){
	var item = '';
	var item_btn = '';
	var item_class = '';
	if(p1 == 0){
		item_btn = '<button type="button" data-id="'+key+'" class="btn btn-sm btn-icon-only btn-info btn-add-item-activity"><i class="fa-plus"></i></button>';
	}else{
		item_class = ' mt-1';
		item_btn = '<button type="button" data-id="'+key+'" class="btn btn-sm btn-icon-only btn-warning btn-delete-item-activity"><i class="fa-times"></i></button>';
	}
	var konten = '<tr class="dt'+key+'">';
		konten += `<td class="style-select2"><select style="width:100%" class="form-control pilihan2 coa_lawan`+key+`" name="coa_lawan`+key+`[]" data-validation="required">`+dt_coa+`</select>
			<input type="hidden" name="dt_id`+key+`[]" class="dt_id`+key+`"></td>`;
		konten += '<td>'+item_btn+'</td>';
		konten += '</tr>';
		$('#result2 tbody .dt'+key).last().after(konten);

		var count = $('#result2 tbody .dt'+key).length;
		$('#result2 tbody .index_dt'+key).attr('rowspan',count);
		$('#result2 tbody .remove_dt'+key).attr('rowspan',count);

		var $t = $('#result2 .pilihan2').last();
		$.each($t,function(k,o){
			var $o = $(o);
			$o.select2({
				dropdownParent : $o.parent(),
				placeholder : ''
			});
		})
	
}
