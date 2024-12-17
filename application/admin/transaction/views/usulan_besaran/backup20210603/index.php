<div class="content-header page-data" data-additional="<?= $access_additional ?>">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		
		<div class="float-right">
			<label class=""><?php echo lang('anggaran'); ?>  &nbsp</label>

			<select class="select2 infinity custom-select" id="filter_anggaran">
				<?php foreach ($tahun as $tahun) { ?>
                <option value="<?php echo $tahun->kode_anggaran; ?>"<?php if($tahun->kode_anggaran == user('kode_anggaran')) echo ' selected'; ?>><?php echo $tahun->keterangan; ?></option>
                <?php } ?>
			</select>
    		
    		<?php 
    		echo filter_cabang_admin($access_additional,$cabang);
    		/*
			echo '<button type="button" class="btn btn-primary btn-sm btn-proses"><i class="fa-calculator"></i>Core</button>';    	
			*/

//			if(user('id_group') == 5 ) {

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

<div class="content-body mt-6">
	<div class="main-container mt-2">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
	    			<div class="card-header"><?= $title ?></div>
	    			<div class="card-body">
	    				<div class="table-responsive tab-pane fade active show height-window">
	    				<?php
						$thn_sebelumnya = user('tahun_anggaran') -1;
						table_open('table table-bordered table-app table-hover table-1');
							thead();
								tr();
									th(get_view_report(),'','width="60" colspan="14" class="text-left"');
								tr();
									th(lang('no'),'','width="60" rowspan="2" class="text-center align-middle headcol"');
									th(lang('keterangan'),'','rowspan="2" class="text-center align-middle headcol" style="display:block;width:auto;min-width:230px"');

									for ($i = 1; $i <= 12; $i++) { 
										th(month_lang($i),'','class="text-center" style="min-width:80px"');		
									}
							
							tbody();
						table_close();
						?>
	    				</div>
	    			</div>
	    		</div>
	    	</div>
	    </div>
	</div>
	
	<div class="overlay-wrap hidden">
		<div class="overlay-shadow"></div>
		<div class="overlay-content">
			<div class="spinner"></div>
			<p class="text-center">Please wait ... </p>
		</div>
	</div>

	<div class="main-container mt-3">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
	    			<div class="card-header">DANA PIHAK KETIGA</div>
	    			<div class="card-body">
	    				<div class="table-responsive tab-pane fade active show height-window" id="result1">
	    				<?php
						table_open('table table-bordered table-app table-hover table-2');
							thead();
								tr();
									th(get_view_report(),'','width="60" colspan="14" class="text-left"');
								tr();
									th(lang('no'),'','width="60" rowspan="2" class="text-center align-middle"');
									th(lang('keterangan'),'','width="160" rowspan="2" class="text-center align-middle" style="display:block;width:auto;min-width:230px"');

									for ($i = 1; $i <= 12; $i++) { 
										th(month_lang($i),'','class="text-center" style="min-width:80px"');		
									}
							
							tbody();
						table_close();
						?>
	    				</div>
	    			</div>
	    		</div>
	    	</div>
	    </div>
	</div>
	<div class="main-container mt-3">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
	    			<div class="card-header">TOTAL KREDIT</div>
	    			<div class="card-body">
	    				<div class="table-responsive tab-pane fade active show height-window" id="result1">
	    				<?php
						table_open('table table-bordered table-app table-hover table-3');
							thead();
								tr();
									th(get_view_report(),'','width="60" colspan="14" class="text-left"');
								tr();
									th(lang('no'),'','width="60" rowspan="2" class="text-center align-middle"');
									th(lang('keterangan'),'','width="160" rowspan="2" class="text-center align-middle" style="display:block;width:auto;min-width:230px"');

									for ($i = 1; $i <= 12; $i++) { 
										th(month_lang($i),'','class="text-center"  style="min-width:80px"');		
									}
							
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
modal_open('modal-import',lang('impor'));
	modal_body();
		form_open(base_url('transaction/usulan_besaran/import_core'),'post','form-import');
			col_init(3,9);

			form_button(lang('impor'),lang('batal'));
		form_close();
modal_close();
?>
<script type="text/javascript">

$('#filter_anggaran').change(function(){
	getData();
	getData_dpk();
	getData_kredit();
});

$('#filter_cabang').change(function(){
	getData();
	getData_dpk();
	getData_kredit();
});

$(document).ready(function () {

	var cabang = $('#filter_cabang').val();
	if(cabang){
		getData();
		getData_dpk();
		getData_kredit();
	}

    $(document).on('keyup', '.calculate', function (e) {
        calculate();
    });
});	

$('#filter_tahun').change(function(){
	getData();
	getData_dpk();
	getData_kredit();
});

function getData() {
	var cabang = $('#filter_cabang').val();
	if(cabang){
		cLoader.open(lang.memuat_data + '...');
		$('.overlay-wrap').removeClass('hidden');
		var page = base_url + 'transaction/usulan_besaran/data';
		page 	+= '/'+$('#filter_anggaran').val();
		page 	+= '/'+$('#filter_cabang').val();

		$.ajax({
			url 	: page,
			data 	: {},
			type	: 'get',
			dataType: 'json',
			success	: function(response) {
				$('.table-1 tbody').html(response.table);
				$('#parent_id').html(response.option);
				cLoader.close();
				cek_autocode();
				fixedTable();
				var item_act	= {};
				if($('.table-1 tbody .btn-input').length > 0) {
					item_act['edit'] 		= {name : lang.realisasi, icon : "edit"};					
				}

				var act_count = 0;
				for (var c in item_act) {
					act_count = act_count + 1;
				}
				if(act_count > 0) {
					$.contextMenu({
				        selector: '.table-1 tbody tr', 
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
			$('.overlay-wrap').addClass('hidden');	
			}
		});
	}
}

function getData_dpk() {
	var cabang = $('#filter_cabang').val();
	if(cabang){
		cLoader.open(lang.memuat_data + '...');
		$('.overlay-wrap').removeClass('hidden');
		var page = base_url + 'transaction/usulan_besaran/data_dpk';
		page 	+= '/'+$('#filter_anggaran').val();
		page 	+= '/'+$('#filter_cabang').val();

		$.ajax({
			url 	: page,
			data 	: {},
			type	: 'get',
			dataType: 'json',
			success	: function(response) {
				$('.table-2 tbody').html(response.table);
				$('#parent_id').html(response.option);
				cLoader.close();
				cek_autocode();
				fixedTable();
				var item_act	= {};
				if($('.table-2 tbody .btn-input').length > 0) {
					item_act['edit'] 		= {name : lang.realisasi, icon : "edit"};					
				}

				var act_count = 0;
				for (var c in item_act) {
					act_count = act_count + 1;
				}
				if(act_count > 0) {
					$.contextMenu({
				        selector: '.table-2 tbody tr', 
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
			$('.overlay-wrap').addClass('hidden');	
			}
		});
	}
}


function getData_kredit() {
	var cabang = $('#filter_cabang').val();
	if(cabang){
		cLoader.open(lang.memuat_data + '...');
		$('.overlay-wrap').removeClass('hidden');
		var page = base_url + 'transaction/usulan_besaran/data_kredit';
		page 	+= '/'+$('#filter_anggaran').val();
		page 	+= '/'+$('#filter_cabang').val();

		$.ajax({
			url 	: page,
			data 	: {},
			type	: 'get',
			dataType: 'json',
			success	: function(response) {
				$('.table-3 tbody').html(response.table);
				$('#parent_id').html(response.option);
				cLoader.close();
				cek_autocode();
				fixedTable();
				var item_act	= {};
				if($('.table-3 tbody .btn-input').length > 0) {
					item_act['edit'] 		= {name : lang.realisasi, icon : "edit"};					
				}

				var act_count = 0;
				for (var c in item_act) {
					act_count = act_count + 1;
				}
				if(act_count > 0) {
					$.contextMenu({
				        selector: '.table-3 tbody tr', 
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
			$('.overlay-wrap').addClass('hidden');	
			}
		});	
	}
	
}


$(function(){
	getData();
	getData_dpk();
	getData_kredit();
});

$(document).on('dblclick','.table-1 tbody td .badge',function(){
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
		url : base_url + 'transaction/usulan_besaran/save_perubahan',
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

  //  $('#modal-import .alert').hide();
  //  $('#modal-import').modal('show');

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


var id_proses = '';
var kode_anggaran = '';
var kode_cabang = '';
$(document).on('click','.btn-proses',function(e){
	e.preventDefault();
	id_proses = 'proses';
	kode_anggaran = $('#filter_anggaran').val();
	kode_cabang = $('#filter_cabang').val();
	cConfirm.open(lang.apakah_anda_yakin + '?','lanjut');
});

function lanjut() {
	$.ajax({
			url : base_url + 'transaction/usulan_besaran/proses_core',
			data : {id:id_proses,kode_anggaran : kode_anggaran,kode_cabang:kode_cabang},
			type : 'post',
			dataType : 'json',
			success : function(res) {
				cAlert.open(res.message,res.status,'refreshData');
			}
		});
	}

</script>