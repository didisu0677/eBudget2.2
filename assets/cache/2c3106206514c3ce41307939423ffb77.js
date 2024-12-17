
var controller 	= "rko_aset_dan_inventaris";
var last_id 	= 0;
$(document).ready(function () {
	resize_window();
	getSubMenu();
});
$('#filter_tahun').change(function(){getSubMenu();});
$('#filter_cabang').change(function(){getSubMenu();});

var xhr_ajax = null;
function getSubMenu(){
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/'+controller+'/get_sub_menu';
	if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }
    last_id = 0;
    $('.sub_menu').html('');
    $('#form_table tbody').html('');
	xhr_ajax = $.ajax({
		url 	: page,
		data 	: {},
		type	: 'get',
		dataType: 'json',
		success	: function(response) {
			if(response.status){
				$('.sub_menu').html(response.sub_menu);
				last_id = response.first;
				cLoader.close();
				getData();
			}else{
				cLoader.close();
				cAlert.open(response.message);
			}
		}
	});
}
$(document).on('click','.dt-sub-menu',function(){
	var tg_data = $(this).data();
	if(tg_data.id){
		last_id = tg_data.id;
		$('.dt-sub-menu').removeClass('active');
		$('.dt-sub-menu[data-id="'+tg_data.id+'"]').addClass('active');
		getData();
	}
});
function getData(){
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/'+controller+'/data';
	page 	+= '/'+$('#filter_anggaran').val();
	page 	+= '/'+$('#filter_cabang').val();

	$.ajax({
		url 	: page,
		data 	: {last_id:last_id},
		type	: 'post',
		dataType: 'json',
		success	: function(response) {
			response_data = [];
			$('.table-app tbody').html(response.table);
			cLoader.close();
			cek_autocode();
			fixedTable();
			var item_act	= {};
			if($('.table-app tbody .btn-input').length > 0) {
				item_act['edit'] 		= {name : lang.ubah, icon : "edit"};
			}

			var kode_cabang;
			var cabang ;

			kode_cabang = $('#user_cabang').val();
			cabang = $('#filter_cabang').val();

			if(!response.edit) {	
				$(".btn-add").prop("disabled", true);
				$(".btn-input").prop("disabled", true);
				$(".btn-save").prop("disabled", true);	
			}else{
				$(".btn-add").prop("disabled", false);
				$(".btn-input").prop("disabled", false);
				$(".btn-save").prop("disabled", false);	
			}
			
			var act_count = 0;
			for (var c in item_act) {
				act_count = act_count + 1;
			}
			if(act_count > 0) {
				$.contextMenu({
			        selector: '.table-app tbody tr', 
			        callback: function(key, options) {
			        	if($(this).find('[data-key="'+key+'"]').length > 0) {
				        	if(typeof $(this).find('[data-key="'+key+'"]').attr('href') != 'undefined') {
				        		window.location = $(this).find('[data-key="'+key+'"]').attr('href');
				        	} else {
					        	$(this).find('[data-key="'+key+'"]').trigger('click');
					        }
					    } 
			        },
			        items: item_act
			    });
			}
		}
	});
}
function formOpen() {
	dt_index = 0;
	response_data = response_edit;
	$('#form_table tbody').html('');
	$('#id_group_inventaris').val(last_id);
	var cabang = $('#filter_cabang option:selected').val();
	$('#kode_cabang').val(cabang).trigger('change');
	add_item();
	if(typeof response_data.detail != 'undefined') {
		$('.btn-add-item').hide();
		$('#id').val(response_data.detail.id);
		var list = response_data.data;
		$.each(list, function(k,v){
			if(k != 0){ add_item(); }
			var f = $('#form_table tbody tr').last();
			f.find('.dt_id').val(v.id);
			f.find('.keterangan').val(v.keterangan);
			f.find('.harga').val(v.harga);
			f.find('.nama_cabang').val(v.nama_cabang);
			f.find('.pic').val(v.pic);
			f.find('.nama_aset').val(v.nama_aset);
		});
	}else{
		$('.btn-add-item').show();
	}
}
$(document).on('click','.btn-add-item',function(){
	add_item();
});
$(document).on('click','.btn-remove',function(){
	$(this).closest('tr').remove();
});
function add_item(){
	var item = '<tr>';
	item += `<td>
		<input type="hidden" class="dt_id" name="dt_id[]"/><input type="hidden" class="dt_key" name="dt_key[]"/>
		<input type="text" class="form-control nama_aset" name="nama_aset[]" data-validation="required" autocomplete="off" />
		</td>`;
	item += '<td><input type="text" class="form-control harga money text-right" name="harga[]" data-validation="required" autocomplete="off" /></td>';
	item += '<td><input type="text" class="form-control nama_cabang" name="nama_cabang[]" data-validation="required" autocomplete="off" /></td>';
	item += '<td><input type="text" class="form-control pic" name="pic[]" data-validation="required" autocomplete="off" /></td>';
	item += '<td><input type="text" class="form-control keterangan" name="keterangan[]" data-validation="required" autocomplete="off" /></td>';
	item += '<td><button type="button"class="btn btn-sm btn-icon-only btn-danger btn-remove"><i class="fa-times"></i></button></td>';
	item += '</tr>';

	$('#form_table').append(item);
	var $t = $('#form_table .pilihan:last-child');
	$.each($t,function(k,o){
		var $o = $(o);
		$o.select2({
			dropdownParent : $o.parent(),
			placeholder : ''
		});
	});
	money_init();
}
$(document).on('click','.d-checkbox',function(){
	var ID = $(this).attr('id');
	var val = $(this).is(':checked');
	if(val){
		val = "1";
	}else{
		val = "0";
	}
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/'+controller+'/save_checkbox';
	$.ajax({
		url 	: page,
		data 	: {ID : ID, val : val,id_group_inventaris : last_id},
		type	: 'post',
		dataType: 'json',
		success	: function(response) {
			cLoader.close();
			if(!response.status){
				cAlert.open(res.message);
			}
		}
	});
});
