
var xhr_ajax 	= null;
var dt_coa 		= [];
$(document).ready(function(){
	get_coa();
});
var status_select = true;
function formOpen(){
	$('#table_coa tbody').html('');
	response_data = response_edit;
	var length = jQuery.isEmptyObject(response_data);
	if(!length){
		status_select = false;
		add_item();
		var f = $('#table_coa tbody tr').last();
		f.find('.coa').val(response_data.coa).trigger('change');
		f.find('.dt_id').val(response_data.id);
		f.find('.nama').val(response_data.nama);
		$('.btn-add-item, .btn-delete-item').hide();
		status_select = true;
	}else{
		add_item();
		$('.btn-add-item, .btn-delete-item').show();
	}
}
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
$(document).on('click','.btn-add-item',function(){
	add_item();
});
$(document).on('click','.btn-delete-item',function(){
	$(this).closest('tr').remove();
});
function add_item(){
	item = `<tr>`;
	item += `<td class="style-select2"><input class="dt_id" type="hidden" name="dt_id[]" /><select style="width:100%" class="form-control pilihan coa" name="coa[]" data-validation="required">`+dt_coa+`</select></td>`;
	item += '<td><input type="text" class="form-control nama" name="nama[]"/></td>';
	item += '<td><button type="button" class="btn btn-sm btn-icon-only btn-danger btn-delete-item"><i class="fa-times"></i></button></td>';
	item += '</tr>';
	$('#table_coa tbody').append(item);
	var $t = $('#table_coa .pilihan').last();
	$.each($t,function(k,o){
		var $o = $(o);
		$o.select2({
			dropdownParent : $o.parent(),
			placeholder : ''
		});
	});
}
$(document).on('change','.coa',function(){
	if(status_select){
		var text = $(this).find(":selected").text();
		var indek = $('.coa').index(this);

		$(document).find('.nama').eq(indek).val(text);
	}
});
