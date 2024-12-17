
var dt_kebijakan_fungsi = '';
var dt_index = 0;
var response_data = [];
$(document).ready(function () {
	getData();
});
$('#filter_tahun').change(function(){getData();});
$('#filter_cabang').change(function(){getData();});
function getData() {
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/asumsi_kebijakan_fungsi/data';
	page 	+= '/'+$('#filter_anggaran').val();
	page 	+= '/'+$('#filter_cabang').val();

	$.ajax({
		url 	: page,
		data 	: {},
		type	: 'get',
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

			if(!response.access_edit) {	
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
	var c_cabang 		= $('#filter_cabang option:selected').val();
	var c_cabang_name 	= $('#filter_cabang option:selected').text();
	$('#kode_cabang').empty();
	$('#kode_cabang').append('<option value="'+c_cabang+'">'+c_cabang_name+'</option>').trigger('change');
	
	dt_index = 0;
	response_data = response_edit;
	$('#result2 tbody').html('');
	var cabang = $('#filter_cabang option:selected').val();
	$('#kode_cabang').val(cabang).trigger('change');
	get_kebijakan_fungsi();	
}
function get_kebijakan_fungsi(){
	if(proccess) {
		$.ajax({
			url : base_url + 'transaction/asumsi_kebijakan_fungsi/get_kebijakan_fungsi',
			data : {},
			type : 'POST',
			success	: function(response) {
				dt_kebijakan_fungsi = response;
				add_item();
				if(typeof response_data.detail != 'undefined') {
					var list = response_data.data;
					$('.btn-add-item').hide();
					$('#id').val(response_data.detail.id);
					$.each(list, function(k,v){
						if(k != 0){
							add_item();
						}
						var f = $('#result2 tbody tr').last();
						f.find('.kebijakan_fungsi').val(v.id_kebijakan_fungsi).trigger('change');
						f.find('.uraian').val(v.uraian);
						f.find('.anggaran').val(numberFormat(v.anggaran,0,',','.'));
						f.find('.kantor_cabang').val(v.kantor_cabang);
						f.find('.pelaksanaan').val(v.pelaksanaan);
						f.find('.dt_id').val(v.id);
						if(v.produk == 1){
							f.find('.produk').prop('checked',true);
						}else{
							f.find('.produk').prop('checked',false);
						}
					})
				}else{
					$('.btn-add-item').show();
				}
				$(".money").maskMoney({allowNegative: true, thousands:'.', decimal:',', precision: 0});
			}
		});
	}
}
$(document).on('click','.btn-add-item',function(){
	add_item();
});
$(document).on('click','.btn-remove',function(){
	$(this).closest('tr').remove();
});
function add_item(p1){
	dt_index += 1;
	var konten = '<tr>';
		konten += '<td><input type="hidden" class="dt_id" name="dt_id[]"/><select class="form-control pilihan kebijakan_fungsi" name="kebijakan_fungsi[]" data-validation="required">'+dt_kebijakan_fungsi+'</select></td>';
		konten += '<td><input type="text" autocomplete="off" class="form-control uraian" name="uraian[]" aria-label="" data-validation="required"/></td>';
		konten += '<td><input type="text" autocomplete="off" class="form-control money anggaran text-right" name="anggaran[]" aria-label="" data-validation="required"/></td>';
		konten += '<td><input type="text" autocomplete="off" class="form-control kantor_cabang" name="kantor_cabang[]" aria-label="" data-validation="required"/></td>';
		konten += '<td><input type="text" autocomplete="off" class="form-control pelaksanaan" name="pelaksanaan[]" aria-label="" data-validation="required"/></td>';
		konten += '<td><button type="button"class="btn btn-sm btn-icon-only btn-danger btn-remove"><i class="fa-times"></i></button></td>';
	konten += '</tr>';
	$('#result2 tbody').append(konten);
	var $t = $('#result2 .pilihan:last-child');
	$.each($t,function(k,o){
		var $o = $(o);
		$o.select2({
			dropdownParent : $o.parent(),
			placeholder : ''
		});
	})
}
