
var select_value1 = '';
var select_val2 = '';
var grup = '';
var sumber_data = '';
function add_row_anggota() {
	konten = '<div class="form-group row">'
			+ '<label class="col-form-label col-md-3"></label>'
			+ '<div class="col-md-3 col-9 mb-1 mb-md-0">'
			+ '<select class="form-control bulan" name="bulan[]" data-validation="required" aria-label="'+$('#bulan').attr('aria-label')+'">'+select_value1+'</select> '
			+ '</div>'
			+ '<div class="col-md-2 col-9 mb-1 mb-md-0">'
			+ '<input type="text" name="tahun[]" autocomplete="off" class="form-control tahun" data-validation="required|max-length:4" placeholder="'+$('#tahun').attr('placeholder')+'" aria-label="'+$('#tahun').attr('placeholder')+'">'
			+ '</div>'
			+ '<div class="col-md-3 col-9 mb-1 mb-md-0">'
			+ '<select class="form-control sumber_data" name="sumber_data[]" data-validation="required" aria-label="'+$('#sumber_data').attr('aria-label')+'">'+select_val2+'</select>' 
			+ '</div>'
			+ '<div class="col-md-1 col-3 mb-1 mb-md-0">'
			+ '<button type="button" class="btn btn-block btn-danger btn-icon-only btn-remove-anggota"><i class="fa-times"></i></button>'
			+ '</div>'
			+ '</div>';
	$('#additional-anggota').append(konten);

	var $t = $('#additional-anggota .bulan:last-child');
	$t.select2({
		dropdownParent : $t.parent()
	});

	var $t1 = $('#additional-anggota .sumber_data:last-child');
	$t1.select2({
		dropdownParent : $t.parent()
	});
}

$('.btn-add-anggota').click(function(){
	add_row_anggota();
});

$(document).on('click','.btn-remove-anggota',function(){
	$(this).closest('.form-group').remove();
});	

var idx = 999;
$(document).on('click','.btn-add-item',function(){
	var konten  ='';
	if($(this).hasClass('add-sub')) {
	var konten = '<tr>'
		konten += '<td></td>';
		konten += '<td style="display:none;"><input type="hidden" autocomplete="off" class="form-control sub_nomor" name="sub_nomor[]" value ="" aria-label="" data-validation=""/></td>';
		konten += '<td><input type="text" autocomplete="off" class="form-control sub_keterangan" name="sub_keterangan[]" value ="" aria-label="" data-validation=""/></td>';

		konten += '<td width="150"><select " class="form-control pilihan sub_grup" name="sub_grup[]" id="" value ="" data-validation="required" aria-label="'+$('#grup').attr('aria-label')+'">'+grup+'</select></td>'

		konten += '<td><input type="text" autocomplete="off" class="form-control sub_coa" name="sub_coa[]" value =""aria-label="" data-validation=""/></td>';
		konten += '<td><input type="text" autocomplete="off" class="form-control sub_core" name="sub_core[]" value =""aria-label="" data-validation=""/></td>';

		konten += '<td width="150"><select " class="form-control pilihan sub_sdata" name="sub_sdata[]" id="" value ="" data-validation="required" aria-label="'+$('#sub_sdata').attr('aria-label')+'">'+sumber_data+'</select></td>'		

		konten += '<td width="60"><input type="text" autocomplete="off" class="form-control sub_urutan" name="sub_urutan[]" value ="" aria-label="" data-validation=""/></td>';
		konten += '<td><button type="button" class="btn btn-sm btn-icon-only btn-danger btn-remove"><i class="fa-times"></i></button></td>';
		konten += '</tr>';
	}	

	$('#result2 tbody').append(konten);
	var $t = $('#result2 .pilihan:last-child');
	$.each($t,function(k,o){
		var $o = $(o);
		$o.select2({
			dropdownParent : $o.parent(),
			placeholder : ''
		});
	})
	idx++;
});

$(document).on('click','.btn-remove',function(){
	$(this).closest('tr').remove();
	$('tr[data-parent="'+$(this).attr('data-idx')+'"]').remove();
});


$(document).ready(function(){
	get_grup();
	get_sumber_data();
	select_value1 = $('#bulan').html();
	select_val2 = $('#sumber_data').html();
});
function formOpen() {
	$('#additional-anggota').html('');
	var response = response_edit;
	if(typeof response.id != 'undefined') {
		if(response.id_coa_besaran > 0) {
			$.each(response.id_coa_besaran, function(k,v){
				$('#id_coa_besaran').find('[value="'+v+'"]').prop('selected',true);
			});
			$('#id_coa_besaran').trigger('change');
		}
		$.each(response.detail,function(e,d){
			if(e == '0') {
				$('#bulan').val(d.bulan).trigger('change');
				$('#tahun').val(d.tahun);
				$('#sumber_data').val(d.sumber_data).trigger('change');
			} else {
				add_row_anggota();
				$('#additional-anggota .bulan').last().val(d.bulan).trigger('change');
				$('#additional-anggota .tahun').last().val(d.tahun);
				$('#additional-anggota .sumber_data').last().val(d.sumber_data).trigger('change');
			}
		});
	}
}


$(document).on('click','.btn-usulan-besaran',function(){
//	get_grup()
	$('.table-ap tbody').html('');
	$('#modal-usulan-besaran').modal();
	$('#modal-usulan-besaran .modal-title').text('Edit template');
	$.ajax({
		url : base_url + 'settings/tahun_anggaran/get_data_usulan',
   		data : {'__id' : $(this).attr('data-id')},
			type : 'POST',
			success	: function(response) {
				$('#id_anggaran').val(response.id);
				var konten  ='';
				var konten1 ='';
				var no = 0;
					$.each(response.grup,function(e,d){					
						konten += '<tr>';
						konten += '<th colspan="7">'+d.grup+'</th>';
						konten += '</tr>';
						$.each(response.detail,function(e1,d1){
						if(d.grup == d1.grup) {
							no++
							konten += '<tr>';
							konten += '<td>'+no+'</td>';
							konten += '<td style="display:none;"><input type="hidden" autocomplete="off" class="form-control nomor" name="nomor['+d1.id+']" value ="'+d1.nomor+'"aria-label="" data-validation=""/></td>';
							konten += '<td style="min-width:350px"><input type="text" autocomplete="off" class="form-control keterangan" name="keterangan['+d1.id+']" value ="'+d1.keterangan+'"aria-label="" data-validation=""/></td>';

							konten += '<td width="150"><select " class="form-control pilihan grup" name="grup['+d1.id+']" id="grup'+d1.id+'" value ="'+d1.grup+'" data-validation="required" aria-label="'+$('#grup').attr('aria-label')+'">'+grup+'</select></td>'

							konten += '<td style="min-width:110px">'+d1.coa+'</td>';
							
							konten += '<td width="60"><input type="text" autocomplete="off" class="form-control core" name="core['+d1.id+']" value ="'+d1.data_core+'"aria-label="" data-validation=""/></td>';

							konten += '<td width="150"><select " class="form-control pilihan s_data" name="s_data['+d1.id+']" id="s_data'+d1.id+'" value ="'+d1.sumber_data+'" data-validation="required" aria-label="'+$('#s_data').attr('aria-label')+'">'+sumber_data+'</select></td>'	

							konten += '<td width="60"><input type="text" autocomplete="off" class="form-control urutan" name="urutan['+d1.id+']" value ="'+d1.urutan+'"aria-label="" data-validation=""/></td>';

							konten += '<td><button type="button" class="btn btn-success btn-icon-only btn-sm btn-add-item add-sub" data-id="'+d1.id+'" data-grup="'+d1.grup+'"><i class="fa-plus"></i></button></td>';

							konten += '</tr>';

							$('.table-ap tbody').html(konten);
							var $t = $('#result2 .pilihan:last-child');
							$t.select2({
								dropdownParent : $t.parent(),
								placeholder : ''
							});
							var f = $('#result2 tbody tr').last();
							$('.grup').val(d1.grup).trigger('change');
							$('.s_data').val(d1.sumber_data).trigger('change');

							$.each(response.sub_detail,function(e2,d2){
								if(d1.grup == d2.grup) {
									no++
									konten += '<tr>';
									konten += '<td>'+no+'</td>';
									konten += '<td style="display:none;"><input type="hidden" autocomplete="off" class="form-control sub_nomor" name="sub_nomor['+d2.id+']" value ="'+d2.nomor+'"aria-label="" data-validation=""/></td>';

									konten += '<td><input type="text" autocomplete="off" class="form-control sub_keterangan" name="sub_keterangan['+d2.id+']" value ="'+d2.keterangan+'"aria-label="" data-validation=""/></td>';

									konten += '<td width="150"><select " class="form-control pilihan sub_grup" name="sub_grup['+d2.id+']" id="sub_grup'+d2.id+'" value ="'+d2.grup+'" data-validation="required" aria-label="'+$('#sub_grup').attr('aria-label')+'">'+grup+'</select></td>'
									konten += '<td><input type="text" autocomplete="off" class="form-control sub_coa" name="sub_coa['+d2.id+']" value ="'+d2.coa+'"aria-label="" data-validation=""/></td>';
									konten += '<td width="60"><input type="text" autocomplete="off" class="form-control sub_core" name="sub_core['+d2.id+']" value ="'+d2.data_core+'"aria-label="" data-validation=""/></td>'

									konten += '<td width="150"><select " class="form-control pilihan sub_sdata" name="sub_sdata['+d2.id+']" id="sub_sdata'+d2.id+'" value ="'+d2.sumber_data+'" data-validation="required" aria-label="'+$('#sub_sdata').attr('aria-label')+'">'+sumber_data+'</select></td>'
										
									konten += '<td width="60"><input type="text" autocomplete="off" class="form-control sub_urutan" name="sub_urutan['+d2.id+']" value ="'+d2.urutan+'"aria-label="" data-validation=""/></td>';	
									konten += '<td><button type="button" class="btn btn-sm btn-icon-only btn-danger btn-remove"><i class="fa-times"></i></button></td>';									
									konten += '</tr>';
									$('.table-ap tbody').html(konten);
									var $t = $('#result2 .pilihan:last-child');
									$t.select2({
										dropdownParent : $t.parent(),
										placeholder : ''
									});
									var f = $('#result2 tbody tr').last();
									$('.sub_grup').val(d2.grup).trigger('change');

									var f = $('#result2 tbody tr').last();
									$('.sub_sdata').val(d2.sumber_data).trigger('change');
								}	
							})	
						}else{
							no = 0;
							
						}	

						});

					});

				$.each(response.detail,function(e1,d1){
					$('#grup'+d1.id).val(d1.grup).trigger('change');
					$('#s_data'+d1.id).val(d1.sumber_data).trigger('change');
				});

				$.each(response.sub_detail,function(e2,d2){
					$('#sub_grup'+d2.id).val(d2.grup).trigger('change');
					$('#sub_sdata'+d2.id).val(d2.sumber_data).trigger('change');
				});
            }
	});
});

function get_grup() {
	if(proccess) {
	//	readonly_ajax = false;
		$.ajax({
			url : base_url + 'settings/tahun_anggaran/get_grup',
			data : {},
			type : 'POST',
			success	: function(response) {
				grup = response;
	//			readonly_ajax = true;				
			}
		});
	}
}

function get_sumber_data() {
	if(proccess) {
	//	readonly_ajax = false;
		$.ajax({
			url : base_url + 'settings/tahun_anggaran/get_sumber_data',
			data : {},
			type : 'POST',
			success	: function(response) {
				sumber_data = response;
	//			readonly_ajax = true;				
			}
		});
	}
}

