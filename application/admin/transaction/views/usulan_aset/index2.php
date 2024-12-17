<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		
		<div class="float-right">
			<label class=""><?php echo lang('tahun'); ?>  &nbsp</label>

			<select class="select2 infinity custom-select" id="filter_tahun">
				<?php foreach ($tahun as $tahun) { ?>
                <option value="<?php echo $tahun->tahun; ?>"<?php if($tahun->tahun == user('tahun_anggaran')) echo ' selected'; ?>><?php echo $tahun->tahun; ?></option>
                <?php } ?>
			</select>					
			               		
			
			<label class=""><?php echo lang('cabang'); ?>  &nbsp</label>
				
			<select class="select2 infinity custom-select" id="filter_cabang">
                <?php foreach($cabang as $b){ ?>
                <option value="<?php echo $b['kode_cabang']; ?>"><?php echo $b['nama_cabang']; ?></option>
                <?php } ?>
			</select>   		
    		
    		<?php 
				$arr = [];
					$arr = [
						['btn-save','Save Data','fa-save'],
					    ['btn-export','Export Data','fa-upload'],
					    ['btn-import','Import Data','fa-download'],
					    ['btn-template','Template Import','fa-reg-file-alt']
					];
				
				
				echo access_button('',$arr); 
			?>
    		</div>
			<div class="clearfix"></div>
			
		</div>
	</div>

<div class="content-body">
	<?php
	$thn_sebelumnya = user('tahun_anggaran') -1;
	table_open('',true,'','','data-table="tbl_m_produk"');
		thead();
			tr();
				th(lang('no'),'','width="60" rowspan="2" class="text-center align-middle"');
				th(lang('keterangan'),'','rowspan="2" class="text-center align-middle"');
				th('Harga','','class="text-center"');
				th('Jumlah','','class="text-center"');
				th('Bulan','','rowspan="2" class="text-center align-middle"');
				th('Total','','rowspan="2" class="text-center align-middle"');
				th('&nbsp;','','width="30", rowspan="2" class="text-center align-middle"');
			tr();
				th('Di isi','','class="text-center"');
				th('Di isi','','class="text-center"');
		tbody();
	table_close();
	?>
</div>
<?php 

modal_open('modal-form','','modal-xl','data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('transaction/rencana_aset/save'),'post','form'); 
				col_init(2,4);
				input('hidden','id','id');

			input('text',lang('tahun'),'tahun_anggaran','',user('tahun_anggaran'),'disabled');
				col_init(2,9);
			?>
	

			<div class="form-group row">
				<label class="col-form-label col-md-2"><?php echo lang('cabang'); ?>  &nbsp</label>
				<div class="col-md-4 col-9 mb-1 mb-md-0">	
					<select class="select2 infinity custom-select" id="kode_cabang" name="kode_cabang">
		                <?php foreach($cabang as $b){ ?>
		                <option value="<?php echo $b['kode_cabang']; ?>"><?php echo $b['nama_cabang']; ?></option>
		                <?php } ?>
					</select>   
				</div>
			</div>

			<div class="card mb-2">
				<div class="card-header"><?php echo lang('aset_instalasi'); ?></div>
				<div class="card-body">
		            <div class="form-group row">
						<div class="col-md-6 col-9 mb-1 mb-md-0">
							<input type="text" name="keterangan[]" autocomplete="off" class="form-control keterangan" data-validation="required|max-length:255" placeholder="<?php echo lang('keterangan'); ?>" aria-label="<?php echo lang('keterangan'); ?>" id="keterangan">
						</div>

						<div class="col-md-3 col-9 mb-1 mb-md-0">
		                    <select id="grup_aset" class="form-control col-md-9 col-xs-9 grup_aset select2" name="grup_aset[]" data-validation="required" aria-label="<?php echo lang('grup_aset'); ?>">
								<option value=""></option>
								<?php foreach($opt_grup as $u) {
									echo '<option value="'.$u['kode'].'">'.$u['keterangan'].'</option>';
								} ?>
		                    </select>
		                </div>

		               	<div class="col-md-2 col-9 mb-1 mb-md-0">
		                    <select id="bulan_aset" class="form-control col-md-9 col-xs-9 bulan_aset select2" name="bulan_aset[]" data-validation="required" aria-label="<?php echo lang('bulan'); ?>">
							<?php for($i = 1; $i <= 12; $i++){ ?>
                			<option value="<?php echo $i; ?>"><?php echo month_lang($i); ?></option>
                			<?php } ?>
		                    </select>
		                </div>

						<div class="col-md-1 col-3 mb-1 mb-md-0">
							<button type="button" class="btn btn-block btn-success btn-icon-only btn-add-anggota"><i class="fa-plus"></i></button>
						</div>
					</div>	
					<div id="additional-anggota" class="mb-2"></div>
				</div>	
			</div>		

			<div class="card mb-2">
				<div class="card-header"><?php echo lang('inventaris_kel1'); ?></div>
				<div class="card-body">
		            <div class="form-group row">
						<div class="col-md-7 col-12 mb-1 mb-md-0">
		                   <select id="inv_kel1" class="form-control col-md-9 col-xs-9 inv_kel1 select2" name="inv_kel1[]" data-validation="required" aria-label="<?php echo lang('inventaris_kel1'); ?>">
								<option value=""></option>
								<?php foreach($opt_inv1 as $u) {
									echo '<option value="'.$u['kode_inventaris'].'" data-harga="'.$u['harga'].'">'.$u['nama_inventaris'].'</option>';
								} ?>
		                    </select>
						</div>

						<div class="col-md-2 col-9 mb-1 mb-md-0">
							<input type="text" name="harga_kel1[]" autocomplete="off" class="form-control harga_kel1 money" data-validation="required|max-length:25" placeholder="<?php echo lang('harga'); ?>" aria-label="<?php echo lang('harga'); ?>" id="harga_kel1">
						</div>

						<div class="col-md-2 col-9 mb-1 mb-md-0">
		                    <select id="bulan_kel1" class="form-control col-md-9 col-xs-9 bulan_kel1 select2" name="bulan_kel1[]" data-validation="required" aria-label="<?php echo lang('bulan'); ?>">
							<?php for($i = 1; $i <= 12; $i++){ ?>
                			<option value="<?php echo $i; ?>"><?php echo month_lang($i); ?></option>
                			<?php } ?>
		                    </select>
		                </div>

						<div class="col-md-1 col-3 mb-1 mb-md-0">
							<button type="button" class="btn btn-block btn-success btn-icon-only btn-add-kel1"><i class="fa-plus"></i></button>
						</div>
					</div>	
					<div id="additional-kel1" class="mb-2"></div>
					<div class="col-md-4 col-3 mb-1 mb-md-0">		
							<button type="button" class="btn btn-sm btn-success btn-icon-only btn-add-keterangan1"><i class="fa-plus"></i>Tambahan Aset Kel.1</button>
					</div>
					<br>
					<div id="tambahan-kel1" class="mb-2"></div>
				</div>	
			</div>		

			<div class="card mb-2">
				<div class="card-header"><?php echo lang('inventaris_kel2'); ?></div>
				<div class="card-body">
				<div class="form-group row">
					<div class="col-md-7 col-12 mb-1 mb-md-0">
	                   <select id="inv_kel2" class="form-control col-md-9 col-xs-9 inv_kel2 select2" name="inv_kel2[]" data-validation="required" aria-label="<?php echo lang('inventaris_kel2'); ?>">
							<option value=""></option>
							<?php foreach($opt_inv2 as $u) {
								echo '<option value="'.$u['kode_inventaris'].'" data-harga="'.$u['harga'].'">'.$u['nama_inventaris'].'</option>';
							} ?>
	                    </select>
					</div>

					<div class="col-md-2 col-9 mb-1 mb-md-0">
						<input type="text" name="harga_kel2[]" autocomplete="off" class="form-control harga_kel2 money" data-validation="required|max-length:25" placeholder="<?php echo lang('harga'); ?>" aria-label="<?php echo lang('harga'); ?>" id="harga_kel2">
					</div>

					<div class="col-md-2 col-9 mb-1 mb-md-0">
		                    <select id="bulan_kel2" class="form-control col-md-9 col-xs-9 bulan_kel2 select2" name="bulan_kel2[]" data-validation="required" aria-label="<?php echo lang('bulan'); ?>">
							<?php for($i = 1; $i <= 12; $i++){ ?>
                			<option value="<?php echo $i; ?>"><?php echo month_lang($i); ?></option>
                			<?php } ?>
		                    </select>
		                </div>
					<div class="col-md-1 col-3 mb-1 mb-md-0">
						<button type="button" class="btn btn-block btn-success btn-icon-only btn-add-kel2"><i class="fa-plus"></i></button>
					</div>
				</div>	
				<div id="additional-kel2" class="mb-2"></div>
				<div class="col-md-4 col-3 mb-1 mb-md-0">		
					<button type="button" class="btn btn-sm btn-success btn-icon-only btn-add-keterangan2"><i class="fa-plus"></i>Tambahan Aset Kel.2</button>
				</div>
				<br>
				<div id="tambahan-kel2" class="mb-2"></div>
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

$('#filter_tahun').change(function(){
	getData();
});

$('#filter_cabang').change(function(){
	getData();
});

$(document).ready(function () {

	getData();
	select_value = $('#grup_aset').html();
	select_kel1 = $('#inv_kel1').html();
	select_kel2 = $('#inv_kel2').html();
	select_bulan1 = $('#bulan_aset').html();
	select_bulan2 = $('#bulan_kel1').html();
	select_bulan3 = $('#bulan_kel2').html();
    $(document).on('keyup', '.calculate', function (e) {
        calculate();
    });
});	

$('#filter_tahun').change(function(){
	getData();
});

function getData() {
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/rencana_aset/data';
	page 	+= '/'+$('#filter_tahun').val();
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
				$(this)[0].innerHTML = $(this)[0].innerHTML.replace(/[^0-9\-]/g, '');
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
		data_edit[$(this).attr('data-id')][$(this).attr('data-name')] = $(this).text().replace(/[^0-9\-]/g,'');
		i++;
	});
	
	var jsonString = JSON.stringify(data_edit);		
	$.ajax({
		url : base_url + 'transaction/rencana_aset/save_perubahan',
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


$('.btn-import').click(function(){
	$('#form-import')[0].reset();
	$('#tahun').val($('#filter_tahun').val()).trigger("change")
	$('#kode_harga').val($('#filter_harga').val()).trigger("change");
	$('#bisunit').val($('#filter_divisi').val()).trigger("change");

    $('#modal-import .alert').hide();
    $('#modal-import').modal('show');

});


$(document).on('click','.btn-export',function(){
	var currentdate = new Date(); 
	var datetime = currentdate.getDate() + "/"
	                + (currentdate.getMonth()+1)  + "/" 
	                + currentdate.getFullYear() + " @ "  
	                + currentdate.getHours() + ":"  
	                + currentdate.getMinutes() + ":" 
	                + currentdate.getSeconds();
	
	$('.bg-grey-2').each(function(){
		$(this).attr('bgcolor','#f4f4f4');
	});
	$('.bg-grey-2').each(function(){
		$(this).attr('bgcolor','#dddddd');
	});
	$('.bg-grey-2-1').each(function(){
		$(this).attr('bgcolor','#b4b4b4');
	});
	$('.bg-grey-2-2').each(function(){
		$(this).attr('bgcolor','#aaaaaa');
	});
	$('.bg-grey-2').each(function(){
		$(this).attr('bgcolor','#888888');
	});
	var table	= '<table>';
	table += '<tr><td colspan="1">Bank Jateng</td></tr>';
	table += '<tr><td colspan="1"> Usulan Bottom Up Besaran Tertentu </td><td colspan="25">: '+$('#filter_tahun option:selected').text()+'</td></tr>';
	table += '<tr><td colspan="1"> Cabang </td><td colspan="25">: '+$('#filter_cabang option:selected').text()+'</td></tr>';
	table += '<tr><td colspan="1"> Print date </td><td colspan="25">: '+datetime+'</td></tr>';
	table += '</table><br />';
	table += '<table border="1">';
	table += $('.content-body').html();
	table += '</table>';
	var target = table;
	window.open('data:application/vnd.ms-excel,' + encodeURIComponent(target));
	$('.bg-grey-1,.bg-grey-2.bg-grey-2-1,.bg-grey-2-2,.bg-grey-3').each(function(){
		$(this).removeAttr('bgcolor');
	});
});

$(document).on('click','.btn-template',function(){
	var page = base_url + 'pl_sales/target_produk/template'
	   $.ajax({
		      url:page,
		      complete: function (response) {
		    	  window.open(page);
		      },
		  });
});

$('.btn-add-anggota').click(function(){
	add_row_anggota();
});
$(document).on('click','.btn-remove-anggota',function(){
	$(this).closest('.form-group').remove();
});
var select_value = '';
var select_bulan1 = '';
var select_bulan2 = '';
var select_bulan3 = '';
function add_row_anggota() {
	konten = '<div class="form-group row">'
			+ '<div class="col-md-6 col-9 mb-1 mb-md-0">'
			+ '<input type="text" name="keterangan[]" autocomplete="off" class="form-control keterangan" data-validation="required|max-length:255" placeholder="'+$('#keterangan').attr('placeholder')+'" aria-label="'+$('#keterangan').attr('placeholder')+'">'
			+ '</div>'
			+ '<div class="col-md-3 col-9 mb-1 mb-md-0">'
			+ '<select class="form-control grup_aset" name="grup_aset[]" data-validation="required" aria-label="'+$('#grup_aset').attr('aria-label')+'">'+select_value+'</select> '
			+ '</div>'
			+ '<div class="col-md-2 col-9 mb-1 mb-md-0">'
			+ '<select class="form-control bulan_aset" name="bulan_aset[]" data-validation="required" aria-label="'+$('#bulan').attr('aria-label')+'">'+select_bulan1+'</select> '
			+ '</div>' 
			+ '<div class="col-md-1 col-3 mb-1 mb-md-0">'
			+ '<button type="button" class="btn btn-block btn-danger btn-icon-only btn-remove-anggota"><i class="fa-times"></i></button>'
			+ '</div>'
			+ '</div>'
			$('#additional-anggota').append(konten);
			var $t = $('#additional-anggota .grup_aset:last-child');
			$t.select2({
				dropdownParent : $t.parent()
			});

			var $t = $('#additional-anggota .bulan_aset:last-child');
			$t.select2({
				dropdownParent : $t.parent()
			});
}

$('.btn-add-kel1').click(function(){
	add_row_kel1();
});
$(document).on('click','.btn-remove-kel1',function(){
	$(this).closest('.form-group').remove();
});

var select_kel1 = '';
function add_row_kel1() {
	konten = '<div class="form-group row">'
			+ '<div class="col-md-7 col-12 mb-1 mb-md-0">'
			+ '<select class="form-control inv_kel1" name="inv_kel1[]" data-validation="required" aria-label="'+$('#inv_kel1').attr('aria-label')+'">'+select_kel1+'</select> '
			+ '</div>'
			+ '<div class="col-md-2 col-9 mb-1 mb-md-0">'
			+ '<input type="text" name="harga_kel1[]" autocomplete="off" class="form-control harga_kel1 money" data-validation="required|max-length:25" placeholder="'+$('#harga_kel1').attr('placeholder')+'" aria-label="'+$('#harga_kel1').attr('placeholder')+'">'
			+ '</div>'
			+ '<div class="col-md-2 col-9 mb-1 mb-md-0">'
			+ '<select class="form-control bulan_kel1" name="bulan_kel1[]" data-validation="required" aria-label="'+$('#bulan').attr('aria-label')+'">'+select_bulan2+'</select> '
			+ '</div>' 
			+ '<div class="col-md-1 col-3 mb-1 mb-md-0">'
			+ '<button type="button" class="btn btn-block btn-danger btn-icon-only btn-remove-kel1"><i class="fa-times"></i></button>'
			+ '</div>'
			+ '</div>'
			$('#additional-kel1').append(konten);

			$(".money").maskMoney({allowNegative: true, thousands:'.', decimal:',', precision: 0});

			var $t = $('#additional-kel1 .inv_kel1:last-child');
			$t.select2({
				dropdownParent : $t.parent()
			});

			var $t = $('#additional-kel1 .bulan_kel1:last-child');
			$t.select2({
				dropdownParent : $t.parent()
			});

}

$(document).on('change','.inv_kel1',function(){
	if($(this).val() != '') {
		var jml = 0;
		var cur_val = $(this).val();
		$('.inv_kel1').each(function(){
			if( $(this).val() == cur_val) jml++;
		});
		if(jml > 1) {
			$(this).val('').trigger('change');
		} else {
			$(this).closest('.form-group').find('.harga_kel1').val($(this).find(':selected').attr('data-harga'));
		}
	}
});

$('.btn-add-kel2').click(function(){
	add_row_kel2();
});
$(document).on('click','.btn-remove-kel2',function(){
	$(this).closest('.form-group').remove();
});

var select_kel2 = '';
function add_row_kel2() {
	konten = '<div class="form-group row">'
			+ '<div class="col-md-7 col-12 mb-1 mb-md-0">'
			+ '<select class="form-control inv_kel2" name="inv_kel2[]" data-validation="required" aria-label="'+$('#inv_kel2').attr('aria-label')+'">'+select_kel2+'</select> '
			+ '</div>'
			+ '<div class="col-md-2 col-9 mb-1 mb-md-0">'
			+ '<input type="text" name="harga_kel2[]" autocomplete="off" class="form-control harga_kel2 money" data-validation="required|max-length:25" placeholder="'+$('#harga_kel2').attr('placeholder')+'" aria-label="'+$('#harga_kel2').attr('placeholder')+'">'
			+ '</div>'
			+ '<div class="col-md-2 col-9 mb-1 mb-md-0">'
			+ '<select class="form-control bulan_kel2" name="bulan_kel2[]" data-validation="required" aria-label="'+$('#bulan').attr('aria-label')+'">'+select_bulan3+'</select> '
			+ '</div>' 
			+ '<div class="col-md-1 col-3 mb-1 mb-md-0">'
			+ '<button type="button" class="btn btn-block btn-danger btn-icon-only btn-remove-kel2"><i class="fa-times"></i></button>'
			+ '</div>'
			+ '</div>'
			$('#additional-kel2').append(konten);

			$(".money").maskMoney({allowNegative: true, thousands:'.', decimal:',', precision: 0});

			var $t = $('#additional-kel2 .inv_kel2:last-child');
			$t.select2({
				dropdownParent : $t.parent()
			});

			var $t = $('#additional-kel2 .bulan_kel2:last-child');
			$t.select2({
				dropdownParent : $t.parent()
			});
}

$('.btn-add-keterangan1').click(function(){
	konten = '<div class="form-group row">'
			+ '<div class="col-md-7 col-12 mb-1 mb-md-0">'
			+ '<input type="text" name="keterangan1[]" autocomplete="off" class="form-control add1" data-validation="required|max-length:25" placeholder="Keterangan" aria-label="Keterangan">'
			+ '</div>'
			+ '<div class="col-md-2 col-9 mb-1 mb-md-0">'
			+ '<input type="text" name="harga_kel2[]" autocomplete="off" class="form-control harga_kel2 money" data-validation="required|max-length:25" placeholder="'+$('#harga_kel2').attr('placeholder')+'" aria-label="'+$('#harga_kel2').attr('placeholder')+'">'
			+ '</div>'
			+ '<div class="col-md-2 col-9 mb-1 mb-md-0">'
			+ '<select class="form-control bulan_kel2" name="bulan_kel2[]" data-validation="required" aria-label="'+$('#bulan').attr('aria-label')+'">'+select_bulan3+'</select> '
			+ '</div>' 
			+ '<div class="col-md-1 col-3 mb-1 mb-md-0">'
			+ '<button type="button" class="btn btn-block btn-danger btn-icon-only btn-remove-kel2"><i class="fa-times"></i></button>'
			+ '</div>'
			+ '</div>'
			$('#tambahan-kel1').append(konten);

			$(".money").maskMoney({allowNegative: true, thousands:'.', decimal:',', precision: 0});

			var $t = $('#tambahan-kel1 .inv_kel2:last-child');
			$t.select2({
				dropdownParent : $t.parent()
			});

			var $t = $('#tambahan-kel1 .bulan_kel2:last-child');
			$t.select2({
				dropdownParent : $t.parent()
			});
});

$('.btn-add-keterangan2').click(function(){
	konten = '<div class="form-group row">'
			+ '<div class="col-md-7 col-12 mb-1 mb-md-0">'
			+ '<input type="text" name="keterangan1[]" autocomplete="off" class="form-control add1" data-validation="required|max-length:25" placeholder="Keterangan" aria-label="Keterangan">'
			+ '</div>'
			+ '<div class="col-md-2 col-9 mb-1 mb-md-0">'
			+ '<input type="text" name="harga_kel2[]" autocomplete="off" class="form-control harga_kel2 money" data-validation="required|max-length:25" placeholder="'+$('#harga_kel2').attr('placeholder')+'" aria-label="'+$('#harga_kel2').attr('placeholder')+'">'
			+ '</div>'
			+ '<div class="col-md-2 col-9 mb-1 mb-md-0">'
			+ '<select class="form-control bulan_kel2" name="bulan_kel2[]" data-validation="required" aria-label="'+$('#bulan').attr('aria-label')+'">'+select_bulan3+'</select> '
			+ '</div>' 
			+ '<div class="col-md-1 col-3 mb-1 mb-md-0">'
			+ '<button type="button" class="btn btn-block btn-danger btn-icon-only btn-remove-kel2"><i class="fa-times"></i></button>'
			+ '</div>'
			+ '</div>'
			$('#tambahan-kel2').append(konten);

			$(".money").maskMoney({allowNegative: true, thousands:'.', decimal:',', precision: 0});

			var $t = $('#tambahan-kel2 .inv_kel2:last-child');
			$t.select2({
				dropdownParent : $t.parent()
			});

			var $t = $('#tambahan-kel2 .bulan_kel2:last-child');
			$t.select2({
				dropdownParent : $t.parent()
			});
});

$(document).on('change','.inv_kel2',function(){
	if($(this).val() != '') {
		var jml = 0;
		var cur_val = $(this).val();
		$('.inv_kel2').each(function(){
			if( $(this).val() == cur_val) jml++;
		});
		if(jml > 1) {
			$(this).val('').trigger('change');
		} else {
			$(this).closest('.form-group').find('.harga_kel2').val($(this).find(':selected').attr('data-harga'));
		}
	}
});

function formOpen() {
	$('#additional-anggota').html('');
	$('#additional-kel1').html('');
	$('#additional-kel2').html('');
	$('#tambahan-kel1').html('');
	$('#tambahan-kel2').html('');
	var response = response_edit;
	if(typeof response.id != 'undefined') {
		$.each(response.detail_ket,function(e,d){
			if(e == '0') {
				$('#keterangan').val(d.nama_inventaris);
				$('#grup_aset').val(d.grup).trigger('change');
				$('#bulan_aset').val(d.bulan).trigger('change');			
			} else {
				add_row_anggota();
				$('#additional-anggota .keterangan').last().val(d.nama_inventaris);
				$('#additional-anggota .grup_aset').last().val(d.grup).trigger('change');	
				$('#additional-anggota .bulan_aset').last().val(d.bulan).trigger('change');			
			}
		});

		$.each(response.detail_invk1,function(e,d){
			if(e == '0') {
				$('#inv_kel1').val(d.kode_inventaris).trigger('change');
				$('#bulan_kel1').val(d.bulan).trigger('change');
				$('#harga_kel1').val(numberFormat(d.harga,0,',','.'));
			} else {
				add_row_kel1();
				$('#additional-kel1 .inv_kel1').last().val(d.kode_inventaris).trigger('change');
				$('#additional-kel1 .bulan_kel1').last().val(d.bulan).trigger('change');
				$('#additional-kel1 .harga_kel1').last().val(numberFormat(d.harga,0,',','.'));

			}
		});

		$.each(response.detail_invk2,function(e,d){
			if(e == '0') {
				$('#inv_kel2').val(d.kode_inventaris).trigger('change');
				$('#bulan_kel2').val(d.bulan).trigger('change');
				$('#harga_kel2').val(numberFormat(d.harga,0,',','.'));
			} else {
				add_row_kel2();
				$('#additional-kel2 .inv_kel2').last().val(d.kode_inventaris).trigger('change');
				$('#additional-kel2 .bulan_kel2').last().val(d.bulan).trigger('change');
				$('#additional-kel2 .harga_kel2').last().val(numberFormat(d.harga,0,',','.'));

			}
		});
	}
}
</script>