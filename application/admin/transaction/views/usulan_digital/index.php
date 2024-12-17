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
			               		  		
    		<?php 
    		echo filter_cabang_admin($access_additional,$cabang);
			if (in_array(user('id_group'), id_group_access('usulan_besaran'), TRUE)){
    			// echo '<button class="btn btn-success btn-save" href="javascript:;" > Save <span class="fa-save"></span></button>';

				$arr = [];
					$arr = [
						['btn-save','Save Data','fa-save'],
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
	<div class="main-container">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
	    			<div class="card-header text-center"><?= $title ?></div>
	    			<div class="card-body">
	    				<div class="table-responsive tab-pane fade active show height-window" id="result1">
	    				<?php
						$thn_sebelumnya = user('tahun_anggaran') -1;
						table_open('',false,'','','data-table="tbl_m_produk"');
							thead();
							
								tr();
									th(lang('no'),'','width="60" rowspan="3" class="text-center align-middle"');
									th(lang('customer'),'','width="250" rowspan="3" class="text-center align-middle"');
									th(lang('kategori'),'','rowspan="3" class="text-center align-middle"class="text-center"');
									th(lang('nama'),'','class="text-center"');
									th(lang('bulan'),'','class="text-center"');
									th(lang('status'),'','class="text-center"');
									th('&nbsp;','','width="30", rowspan="3" class="text-center align-middle"');
								
							tbody();
						table_close();
						?>
	    				</div>
	    			</div>
	    		</div>
	    	</div>
	    </div>
	</div>
</div>
<?php 
if($access_additional):
	$cabang_input = $cabang;
endif;
modal_open('modal-form','','modal-xl','data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('transaction/usulan_digital/save'),'post','form'); 
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
								<th><?php echo lang('customer'); ?></th>
								<th><?php echo lang('kategori'); ?></th>
								<th><?php echo lang('nama_lokasi');?></th>
								<th><?php echo lang('bulan');?></th>
								<th><?php echo lang('status'); ?></th>
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

<script type="text/javascript">

var status_ket="";
var bulan ="";
var index = 0;
function formOpen() {
	$('#result2 tbody').html('');
	var cabang = $('#filter_cabang option:selected').val();
	$('#kode_cabang').val(cabang).trigger('change');
	get_bulan();
	get_status_ket();
	var response = response_edit;
	if(typeof response.id != 'undefined') {
		$('.btn-add-item').hide();
		$.each(response.detail_ket,function(x,y){
			add_item();
			var f = $('#result2 tbody tr').last();
			f.find('.customer').val(y.customer);
			f.find('.kategori').val(y.kategori_layanan);
			f.find('.nama_lokasi').val(y.nama_lokasi);
			f.find('.bulan').val(y.bulan).trigger('change');
			f.find('.status_ket').val(y.id_status_kantor).trigger('change');
		});
	}else {
		$('.btn-add-item').show();
	}
}

$('#filter_tahun').change(function(){
	getData();
});

$('#filter_cabang').change(function(){
	getData();
});

$(document).ready(function () {
	resize_window();
	$('#result2 tbody').html('');	
	getData();
	get_bulan()
	get_status_ket();
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
	var page = base_url + 'transaction/usulan_digital/data';
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
		url : base_url + 'transaction/usulan_digital/save_perubahan',
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
			+ '<td><input type="text" autocomplete="off" class="form-control customer" name="customer[]" aria-label="" data-validation=""/></td>';
				konten += '<td><input type="text" autocomplete="off" class="form-control kategori" name="kategori[]" aria-label="" data-validation=""/></td>';
				konten += '<td><input type="text" autocomplete="off" class="form-control nama_lokasi" name="nama_lokasi[]" aria-label="" data-validation=""/></td>';
				konten += '<td><select class="form-control pilihan bulan select2" name="bulan[]" aria-label="" data-validation="required">'+bulan+'</select></td>';
				konten += '<td><select class="form-control pilihan select2 status_ket" name="status_ket[]" aria-label="" data-validation="required">'+status_ket+'</select></td>';
				konten += '<td><button type="button" class="btn btn-sm btn-icon-only btn-danger btn-remove"><i class="fa-times"></i></button></td>';
		+ '</tr>';
	$('#result2 tbody').append(konten);
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

function get_bulan() {
	if(proccess) {
	//	readonly_ajax = false;
		$.ajax({
			url : base_url + 'transaction/usulan_digital/get_bulan',
			data : {},
			type : 'POST',
			success	: function(response) {
				bulan = response;
	//			readonly_ajax = true;				
			}
		});
	}
}

function get_status_ket() {
	if(proccess) {
	//	readonly_ajax = false;
		$.ajax({
			url : base_url + 'transaction/usulan_digital/get_status_ket',
			data : {},
			type : 'POST',
			success	: function(response) {
				status_ket = response;
	//			readonly_ajax = true;				
			}
		});
	}
}

</script>