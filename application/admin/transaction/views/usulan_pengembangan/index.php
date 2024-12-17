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
                <option value="<?php echo $tahun->tahun_anggaran; ?>"<?php if($tahun->tahun_anggaran == user('tahun_anggaran')) echo ' selected'; ?>><?php echo $tahun->tahun_anggaran; ?></option>
                <?php } ?>
			</select>					
			               		
			
			<label class=""><?php echo lang('cabang'); ?>  &nbsp</label>
				
			<select class="select2 infinity custom-select" id="filter_cabang">
                <?php foreach($cabang as $b){ ?>
                <option value="<?php echo $b['kode_cabang']; ?>"><?php echo $b['nama_cabang']; ?></option>
                <?php } ?>
			</select>   		
    		
    		<button class="btn btn-success btn-save" href="javascript:;" > Save <span class="fa-save"></span></button>

    		<?php 
				$arr = [];
					$arr = [
						// ['btn-save','Save Data','fa-save'],
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
				th(lang('no'),'','width="60" rowspan="3" class="text-center align-middle"');
				th(lang('status_jaringan_kantor'),'','width="250" rowspan="3" class="text-center align-middle"');
				th(lang('kategori'),'','rowspan="3" class="text-center align-middle"class="text-center"');
				th(lang('nama'),'','class="text-center"');
				th(lang('bulan'),'','class="text-center"');
				th(lang('status'),'','class="text-center"');
				th(lang('rencana_biaya_investasi'),'','colspan="8" class="text-center align-middle"');
				th('&nbsp;','','width="30", rowspan="3" class="text-center align-middle"');
			tr();
				th('lokasi','','class="text-center"');
				th('1-12','','class="text-center"');
				th('ket','','class="text-center"');
				th(lang('b_gaji'),'','class="text-center"');
				th(lang('b_promosi'),'','class="text-center"');
				th(lang('b_adm_umum'),'','class="text-center"');
				th(lang('b_non_opr'),'','class="text-center"');
				th(lang('inv_gdg'),'','class="text-center"');
				th(lang('inst_bang'),'','class="text-center"');
				th(lang('akt_kel1'),'','class="text-center"');
				th(lang('akt_kel2'),'','class="text-center"');
			tr();	
				th();
				th();
				th();
				th(lang('per_tahun'),'','class="text-center"');
				th(lang('per_tahun'),'','class="text-center"');
				th(lang('per_tahun'),'','class="text-center"');
				th(lang('per_tahun'),'','class="text-center"');
				th(lang('per_tahun'),'','class="text-center"');
				th(lang('per_tahun'),'','class="text-center"');
				th(lang('per_tahun'),'','class="text-center"');
				th(lang('per_tahun'),'','class="text-center"');

		tbody();
	table_close();
	?>
</div>
<?php 

modal_open('modal-form','','modal-xl','data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('transaction/usulan_pengembangan/save'),'post','form'); 
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
				<div class="card-header"><?php echo lang('nama_kegiatan'); ?></div>
				<div class="card-body">

		            <div class="form-group row">

					<div class="col-md-2 col-9 mb-1 mb-md-0">
		                    <select id="status_jaringan_kantor" class="form-control col-md-9 col-xs-9 status_jaringan_kantor select2" name="status_jaringan_kantor[]" data-validation="required" aria-label="<?php echo lang('status_jaringan_kantor'); ?>">
								<option value=""></option>
								<?php foreach($opt_jaringan as $u) {
									echo '<option value="'.$u['id'].'">'.$u['status_jaringan'].'</option>';
								} ?>
		                    </select>
		                </div>

						<div class="col-md-2 col-9 mb-1 mb-md-0">
		                    <select id="kategori" class="form-control col-md-9 col-xs-9 kategori select2" name="kategori[]" data-validation="required" aria-label="<?php echo lang('kategori'); ?>">
								<option value=""></option>
								<?php foreach($opt_kategori as $u) {
									echo '<option value="'.$u['id'].'">'.$u['kategori'].'</option>';
								} ?>
		                    </select>
		                </div>
						<div class="col-md-3 col-9 mb-1 mb-md-0">
							<input type="text" name="nama_lokasi[]" autocomplete="off" class="form-control nama_lokasi" data-validation="required|max-length:255" placeholder="<?php echo lang('nama_lokasi'); ?>" aria-label="<?php echo lang('nama_lokasi'); ?>" id="nama_lokasi">
						</div>

		                <div class="col-md-2 col-9 mb-1 mb-md-0">
		                    <select id="bulan" class="form-control col-md-9 col-xs-9 grup_aset3 select2" name="bulan[]" data-validation="required" aria-label="<?php echo lang('bulan'); ?>">
							<?php for($i = 1; $i <= 12; $i++){ ?>
                			<option value="<?php echo $i; ?>"><?php echo month_lang($i); ?></option>
                			<?php } ?>
		                    </select>
		                </div>

						<div class="col-md-2 col-9 mb-1 mb-md-0">
		                    <select id="status_ket" class="form-control col-md-9 col-xs-9 status_ket select2" name="status_ket[]" data-validation="required" aria-label="<?php echo lang('status_ket'); ?>">
								<option value=""></option>
								<?php foreach($opt_status as $u) {
									echo '<option value="'.$u['id'].'">'.$u['status_ket'].'</option>';
								} ?>
		                    </select>
		                </div>

						<div class="col-md-1 col-3 mb-1 mb-md-0">
							<button type="button" class="btn btn-block btn-success btn-icon-only btn-add-anggota"><i class="fa-plus"></i></button>
						</div>
					</div>	
					<div id="additional-anggota" class="mb-2"></div>
				</div>	
			</div>		

	<?php

				form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close(); ?>

?>

<script type="text/javascript">

$('#filter_tahun').change(function(){
	getData();
});

$('#filter_cabang').change(function(){
	getData();
});

$(document).ready(function () {

	getData();

	select_jaringan = $('#status_jaringan_kantor').html();
	select_kategori = $('#kategori').html();
	select_bulan = $('#bulan').html();
	select_status = $('#status_ket').html();
    
    $(document).on('keyup', '.calculate', function (e) {
        calculate();
    });
});	

$('#filter_tahun').change(function(){
	getData();
});

function getData() {
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/usulan_pengembangan/data';
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
		url : base_url + 'transaction/usulan_pengembangan/save_perubahan',
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
	table += '<tr><td colspan="1"> Usulan PD.BULAN Besaran Tertentu </td><td colspan="25">: '+$('#filter_tahun option:selected').text()+'</td></tr>';
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

var select_jaringan = '';
var select_kategori = '';
var select_bulan = '';
var select_status = '';
function add_row_anggota() {
	konten = '<div class="form-group row">'
			+ '<div class="col-md-2 col-9 mb-1 mb-md-0">'
			+ '<select class="form-control status_jaringan_kantor" name="status_jaringan_kantor[]" data-validation="required" aria-label="'+$('#status_jaringan_kantor').attr('aria-label')+'">'+select_jaringan+'</select> '
			+ '</div>' 
			+ '<div class="col-md-2 col-9 mb-1 mb-md-0">'
			+ '<select class="form-control kategori" name="kategori[]" data-validation="required" aria-label="'+$('#kategori').attr('aria-label')+'">'+select_kategori+'</select> '
			+ '</div>' 
			+ '<div class="col-md-3 col-12 mb-1 mb-md-0">'
			+ '<input type="text" name="nama_lokasi[]" autocomplete="off" class="form-control nama_lokasi" data-validation="required|max-length:255" placeholder="'+$('#nama_lokasi').attr('placeholder')+'" aria-label="'+$('#nama_lokasi').attr('placeholder')+'">'
			+ '</div>'
			+ '<div class="col-md-2 col-9 mb-1 mb-md-0">'
			+ '<select class="form-control bulan" name="bulan[]" data-validation="required" aria-label="'+$('#bulan').attr('aria-label')+'">'+select_bulan+'</select> '
			+ '</div>' 
			+ '<div class="col-md-2 col-9 mb-1 mb-md-0">'
			+ '<select class="form-control status_ket" name="status_ket[]" data-validation="required" aria-label="'+$('#status_ket').attr('aria-label')+'">'+select_status+'</select> '
			+ '</div>'
			+ '<div class="col-md-1 col-3 mb-1 mb-md-0">'
			+ '<button type="button" class="btn btn-block btn-danger btn-icon-only btn-remove-anggota"><i class="fa-times"></i></button>'
			+ '</div>'
			+ '</div>'
			$('#additional-anggota').append(konten);
			var $t = $('#additional-anggota .status_jaringan_kantor:last-child');
			$t.select2({
				dropdownParent : $t.parent()
			});

			var $t = $('#additional-anggota .kategori:last-child');
			$t.select2({
				dropdownParent : $t.parent()
			});

			var $t = $('#additional-anggota .bulan:last-child');
			$t.select2({
				dropdownParent : $t.parent()
			});

			var $t = $('#additional-anggota .status_ket:last-child');
			$t.select2({
				dropdownParent : $t.parent()
			});
}

function formOpen() {
	$('#additional-anggota').html('');
	var response = response_edit;
	if(typeof response.id != 'undefined') {
		$.each(response.detail_ket,function(e,d){
			if(e == '0') {
				$('#nama_lokasi').val(d.nama_lokasi);	
				$('#status_jaringan_kantor').val(d.id_status_jaringan).trigger('change');	
				$('#kategori').val(d.id_kategori_kantor).trigger('change');	
				$('#bulan').val(d.bulan).trigger('change');	
				$('#status_ket').val(d.id_status_kantor).trigger('change');	
			} else {
				add_row_anggota();
				$('#additional-anggota .nama_lokasi').last().val(d.nama_lokasi);
				$('#additional-anggota .status_jaringan_kantor').last().val(d.id_status_jaringan).trigger('change');
				$('#additional-anggota .kategori').last().val(d.id_kategori_kantor).trigger('change');	
				$('#additional-anggota .bulan').last().val(d.bulan).trigger('change');	
				$('#additional-anggota .status_ket').last().val(d.id_status_kantor).trigger('change');	
		
			}
		});

	}
}
</script>