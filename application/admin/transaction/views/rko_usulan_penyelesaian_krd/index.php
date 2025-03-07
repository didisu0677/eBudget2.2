<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		
		<div class="float-right">
			<?php
			input('hidden',lang('user'),'user_cabang','',user('kode_cabang'));
			?>
			<label class=""><?php echo lang('anggaran'); ?>  &nbsp</label>

			<select class="select2 infinity custom-select" id="filter_anggaran">
				<?php foreach ($tahun as $tahun) { ?>
                <option value="<?php echo $tahun->kode_anggaran; ?>"<?php if($tahun->kode_anggaran == user('kode_anggaran')) echo ' selected'; ?>><?php echo $tahun->keterangan; ?></option>
                <?php } ?>
			</select>						
			               		
			
			<label class=""><?php echo lang('cabang'); ?>  &nbsp</label>
				
			<select class="select2 custom-select" id="filter_cabang">
                <?php foreach($cabang as $b){ ?>
                <option value="<?php echo $b['kode_cabang']; ?>" <?php if($b['kode_cabang'] == user('kode_cabang')) echo ' selected'; ?>><?php echo $b['nama_cabang']; ?></option>
                <?php } ?>
			</select>   	
   

    		<?php 
			if (in_array(user('id_group'), id_group_access('usulan_besaran'), TRUE)){    		
    			echo '<button class="btn btn-success btn-save" href="javascript:;" > Save <span class="fa-save"></span></button>';
				$arr = [];
					$arr = [
						// ['btn-save','Save Data','fa-save'],
					    ['btn-export','Export Data','fa-upload'],
					    ['btn-import','Import Data','fa-download'],
					    ['btn-template','Template Import','fa-reg-file-alt']
					];
				
				
				echo access_button('',$arr); 
			}	
			?>
    		</div>
			<div class="clearfix"></div>
			
		</div>
	</div>

<div class="content-body">
	<?php
	$thn_sebelumnya = user('tahun_anggaran') -1;
	table_open('',false,'','','data-table="tbl_m_produk"');
		thead();
			tr();
				th(lang('no'),'','rowspan="2" class="text-center align-middle"');
				th(lang('nama_debitur'),'','rowspan="2" style="min-width:250px" class="text-center align-middle"');
				th('Produk Kredit','','rowspan="2" class="text-center align-middle"');
				th('Sisa Outstanding','','rowspan="2" class="text-center align-middle"');
				th('Posisi Kolek','','rowspan="2" class="text-center align-middle"');
				th('Tgl Jatuh Tempo','','rowspan="2" class="text-center align-middle" style="min-width:90px"');
				th('Deskripsi Penyelesaian','','rowspan="2" class="text-center align-middle"');
				th('Target Waktu','','class="text-center" style="min-width:90px"');
				th('&nbsp;','','rowspan="2" class="text-center align-middle"');
			tr();
				th('Penyelesaian','','class="text-center" style="min-width:90px"');
				
		tbody();


	table_close();
	?>
</div>
<?php 

modal_open('modal-form','','modal-xl w-90-per','data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('transaction/rko_usulan_penyelesaian_krd/save'),'post','form'); 
				col_init(2,4);
				input('hidden','id','id');

			input('text',lang('tahun'),'tahun_anggaran','',user('tahun_anggaran'),'disabled');
				col_init(2,9);
			?>
	

			<div class="form-group row">
				<label class="col-form-label col-md-2"><?php echo lang('cabang'); ?>  &nbsp</label>
				<div class="col-md-4 col-9 mb-1 mb-md-0">	
					<select class="select2 infinity custom-select" id="kode_cabang" name="kode_cabang">
		                <?php foreach($cabang_input as $b){ ?>
		                <option value="<?php echo $b['kode_cabang']; ?>" <?php if($b['kode_cabang'] == user('kode_cabang')) echo ' selected'; ?>><?php echo $b['nama_cabang']; ?></option>
		                <?php } ?>
					</select> 
				</div>
			</div>

		<div class="card mb-2">

				<div id="result2" class="mb-3">	
				<div class="table-responsive">
				    <table class="table table-bordered table-app table-cabang">
						<thead>
							<tr>
								<th><?php echo lang('nama_debitur'); ?></th>
								<th><?php echo lang('produk_kredit'); ?></th>
								<th><?php echo lang('sisa_outstanding'); ?></th>
								<th>posisi_kolek</th>
								<th>tgl_jatuh_tempo <?php echo str_repeat('&nbsp;', 5);?></th>
								<th><?php echo lang('deskripsi_penyelesaian'); ?></th>
								<th><?php echo lang('target_waktu_penyelesaian'); ?></th>
								<th width="10">
									<button type="button" class="btn btn-sm btn-icon-only btn-success btn-add-item"><i class="fa-plus"></i></button>
								</th>
							</tr>
						</thead>
					<tbody>

					</tbody>
					</table>
				</div>
				</div>	
			</div>			

	<?php

				form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close(); ?>

?>
<script type="text/javascript" src="<?php echo base_url('assets/js/maskMoney.js') ?>"></script>
<script type="text/javascript">
var index = 0;
var data_produk = '';
function formOpen() {
	$('#result2 tbody').html('');
	get_produk_kredit();
	var response = response_edit;
	if(typeof response.id != 'undefined') {

		$(".money").maskMoney({allowNegative: true, thousands:'.', decimal:',', precision: 0});
		$('.btn-add-item').hide();
		$.each(response.detail_ket,function(x,y){
			add_item();
			var f = $('#result2 tbody tr').last();
			f.find('.nama_debitur').val(y.nama_debitur);
			f.find('.produk_kredit').val(y.produk_kredit).trigger('change');
			f.find('.posisi_kolek').val(y.posisi_kolek);
			f.find('.tgl_jatuh_tempo').val(y.tgl_jatuh_tempo).trigger('change');
			f.find('.deskripsi_penyelesaian').val(y.deskripsi_penyelesaian);
			f.find('.sisa_outstanding').val(numberFormat(y.sisa_outstanding,0,',','.'));
			f.find('.target_waktu_penyelesaian').val(y.target_waktu_penyelesaian).trigger('change');
		});
	}else {
		$('.btn-add-item').show();
	}
}

$(document).ready(function () {
	$('#result2 tbody').html('');	
	get_produk_kredit();
});	

$('#filter_tahun').change(function(){
	getData();
});

$('#filter_cabang').change(function(){
	getData();
});

$(document).ready(function () {

	$('#result2 tbody').html('');	
	getData();
    $(document).on('keyup', '.calculate', function (e) {
        calculate();
    });

    $('.sisa_outstanding').maskMoney();
});	


$('#filter_tahun').change(function(){
	getData();
});

function getData() {
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/rko_usulan_penyelesaian_krd/data';
	page 	+= '/'+$('#filter_anggaran').val();
	page 	+= '/'+$('#filter_cabang').val();

	$.ajax({
		url 	: page,
		data 	: {},
		type	: 'get',
		dataType: 'json',
		success	: function(response) {
			$('.table-app tbody').html(response.table);
			$('#parent_id').html(response.option);
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


			if(kode_cabang != cabang) {	
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
$(function(){
	getData();
});

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
});
$(document).on('blur','.edit-value, .edit-text',function(){
	var tr = $(this).closest('tr');
	if($(this).text() != $(this).attr('data-value')) {
		$(this).addClass('edited');
	}
	if(tr.find('td.edited').length > 0) {
		tr.addClass('edited-row');
	} else {
		tr.removeClass('edited-row');
	}
});
$(document).on('keyup','.edit-value',function(e){
	var wh 			= e.which;
	if((48 <= wh && wh <= 57) || (96 <= wh && wh <= 105) || wh == 8) {
		if($(this).text() == '') {
			$(this).text('');
		} else {
			var n = parseInt($(this).text().replace(/[^0-9\-]/g,''),10);
		    $(this).text(n.toLocaleString());
		    var selection = window.getSelection();
			var range = document.createRange();
			selection.removeAllRanges();
			range.selectNodeContents($(this)[0]);
			range.collapse(false);
			selection.addRange(range);
			$(this)[0].focus();
		}
	}
});
$(document).on('keypress','.edit-value',function(e){
	var wh 			= e.which;
	if (e.shiftKey) {
		if(wh == 0) return true;
	}
	if(e.metaKey || e.ctrlKey) {
		if(wh == 86 || wh == 118) {
			$(this)[0].onchange = function(){
				$(this)[0].val() = $(this)[0].val().replace(/[^0-9\-]/g, '');
			}
		}
		return true;
	}
	if(wh == 0 || wh == 8 || wh == 45 || (48 <= wh && wh <= 57) || (96 <= wh && wh <= 105)) 
		return true;
	return false;
});

function calculate() {
	var total_budget = 0;

	$('#result tbody tr').each(function(){
		if($(this).find('.budget').length == 1) {
			var subtotal_budget = moneyToNumber($(this).find('.budget').val());
			total_budget += subtotal_budget;
		}


	});

	$('#total_budget').val(total_budget);
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

		if ($(this).attr('data-name') == 'sisa_outstanding') {
		data_edit[$(this).attr('data-id')][$(this).attr('data-name')] = $(this).text().replace(/[^0-9\-]/g,'');
		}else{
			data_edit[$(this).attr('data-id')][$(this).attr('data-name')] = $(this).text();
		}

		i++;
	});
	
	var jsonString = JSON.stringify(data_edit);		
	$.ajax({
		url : base_url + 'transaction/rko_usulan_penyelesaian_krd/save_perubahan',
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

$(document).on('click','.btn-add-item',function(){
	add_item();
});
$(document).on('click','.btn-remove',function(){
	$(this).closest('tr').remove();
});

function add_item() {
	var konten = '<tr>'
			+ '<td><input type="text" autocomplete="off" class="form-control nama_debitur" name="nama_debitur[]" aria-label="" data-validation=""/></td>';
				konten += '<td><select class="form-control pilihan produk_kredit" width ="200" name="produk_kredit[]" aria-label="" data-validation="required">'+data_produk+'</select></td>';
				konten += '<td><input type="text" autocomplete="off" class="form-control money sisa_outstanding text-right" name="sisa_outstanding[]" aria-label="" data-validation=""/></td>';
				konten += '<td><input type="text" autocomplete="off" class="form-control posisi_kolek" name="posisi_kolek[]" aria-label="" data-validation=""/></td>';
				konten += '<td><input type="date" autocomplete="off" class="form-control tgl_jatuh_tempo" name="tgl_jatuh_tempo[]" aria-label="" data-validation="required"/></td>';
				konten += '<td><textarea name="deskripsi_penyelesaian[]" class="form-control deskripsi_penyelesaian"  data-validation="required"/></textarea></td>';
				konten += '<td><input type="date" autocomplete="off" class="form-control target_waktu_penyelesaian" name="target_waktu_penyelesaian[]" aria-label="" data-validation="required"/></td>';
				konten += '<td><button type="button" class="btn btn-sm btn-icon-only btn-danger btn-remove"><i class="fa-times"></i></button></td>';
		+ '</tr>';
	$('#result2 tbody').append(konten);


	$(".money").maskMoney({allowNegative: true, thousands:'.', decimal:',', precision: 0});

	var $t = $('#result2 .pilihan:last-child');
	$.each($t,function(k,o){
		var $o = $(o);
		$o.select2({
			dropdownParent : $o.parent(),
			placeholder : ''
		});
	})
	
	index++;
}
function get_produk_kredit(){
	if(proccess) {
		$.ajax({
			url : base_url + 'transaction/rko_usulan_penyelesaian_krd/get_produk_kredit',
			data : {},
			type : 'POST',
			success	: function(response) {
				data_produk = response;		
			}
		});
	}
}

</script>