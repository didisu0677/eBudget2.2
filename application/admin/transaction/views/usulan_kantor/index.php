<style type="text/css">
.w-200{
	min-width: 200px;
}
.w-150{
	min-width: 150px;
}
</style>
<div class="content-header page-data" data-additional="<?= $access_additional ?>">
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
    		if($access_edit):
    			echo '<button class="btn btn-success btn-save" href="javascript:;" > Save <span class="fa-save"></span></button>';
    		endif;
			if (in_array(user('id_group'), id_group_access('usulan_besaran'), TRUE)){
    				
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

<div class="content-body mt-6 ">
	<div class="main-container mt-2">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
	    			<div class="card-header text-center"><?= $title ?></div>
	    			<div class="card-body">
	    				<div class="table-responsive tab-pane fade active show height-window" id="result1">
	    				<?php
						$thn_sebelumnya = user('tahun_anggaran') -1;
						table_open('table table-striped table-bordered table-app table-hover tableFixHead',false,'','','data-table="tbl_m_produk"','');
							thead();
								tr();
									th(lang('no'),'','width="60" class="text-center align-middle"');
									th(lang('cabang_induk'),'','class="text-center align-middle w-200"');
									th(lang('rencana'),'','class="text-center align-middle w-200"');
									th(lang('tahapan'),'','class="text-center align-middle w-150"');
									th(lang('jenis_kantor'),'','class="text-center align-middle w-150"');
									th(lang('biaya_perkiraan').' ('.get_view_report().')','','class="text-center align-middle w-150"');
									th(lang('nama_kantor'),'','class="text-center align-middle w-150"');
									th(lang('jadwal'),'','class="text-center align-middle w-150"');
									th(lang('status'),'','class="text-center align-middle w-150"');
									th(lang('kecamatan'),'','class="text-center align-middle w-200"');
									th(lang('penjelasan'),'','class="text-center align-middle"');
									th(lang('keterangan'),'','class="text-center align-middle w-150"');
									th(lang('warna_keterangan'),'','class="text-center align-middle"');
									th('&nbsp;','','width="30", class="text-center align-middle"');
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
		form_open(base_url('transaction/usulan_kantor/save'),'post','form'); 
				col_init(2,4);
				input('hidden','id','id');

			input('text',lang('tahun'),'tahun_anggaran','',user('tahun_anggaran'),'disabled');
				col_init(2,9);
			?>
	

			<div class="form-group row">
				<label class="col-form-label col-md-2"><?php echo lang('cabang'); ?>  &nbsp</label>
				<div class="col-md-4 col-9 mb-1 mb-md-0">	
					<select class="select2 custom-select" id="kode_cabang" name="kode_cabang">
		                <?php foreach($cabang_input as $b){ ?>
		                <option value="<?php echo $b['kode_cabang']; ?>" <?php if($b['kode_cabang'] == user('kode_cabang')) echo ' selected'; ?>><?php echo $b['nama_cabang']; ?></option>
		                <?php } ?>
					</select>   
				</div>
			</div>

			<div class="card mb-2">

				<div id="result2" class="mb-3">	
				<div class="table-responsive">
				    <table class="table table-bordered table-cabang tableFixHead">
						<thead>
							<tr>
								<th><?= lang('cabang_induk') ?></th>
								<th><?= lang('nama_kantor') ?></th>
								<th><?php echo 'Rencana'; ?></th>
								<th><?php echo 'Tahapan'; ?></th>
								<th><?php echo 'Jenis Kantor'; ?></th>
								<th class="w-200"><?= lang('biaya_perkiraan') ?></th>
								<th>Jadwal <?php echo str_repeat('&nbsp;', 5);?></th>
								<th>Status</th>
								<th>Provinsi</th>
								<th>Kota/Kabupaten</th>
								<th>Kecamatan</th>
								<th>Keterangan</th>
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
modal_close();

?>

<script>
var rencana = '';
var status = '';
var tahapan1 = '';
var jenis_kantor = '';
var jadwal1 = '';
var index = 0;
var dt_provinsi = [];
var dt_keterangan = '';
var dt_cabang = '';
var status_harga = false;
function formOpen() {
	dt_index = 0;
	$('#result2 tbody').html('');
	var cabang = $('#filter_cabang option:selected').val();
	$('#kode_cabang').val(cabang).trigger('change');
	get_jenis_kantor();
	get_tahapan();
	get_jadwal();
	get_status();
	var response = response_edit;
	status_harga = false;
	if(typeof response.id != 'undefined') {
		$('#id').val(response.id);
		status_harga = true;
		$.each(response.detail,function(x,y){
			add_item();
			var f = $('#result2 tbody tr').last();
			f.find('.cabang').val(y.cabang_induk).trigger('change');
			f.find('.nama_kantor').val(y.nama_kantor).trigger('change');
			f.find('.renc').val(y.id_rencana).trigger('change');
			f.find('.tah').val(y.id_tahapan).trigger('change');
			f.find('.jenis').val(y.id_kategori_kantor).trigger('change');
			f.find('.jadwal').val(y.jadwal).trigger('change');
			f.find('.status_ket').val(y.id_status_kantor).trigger('change');

			f.find('.provinsi').attr('data-temp_id',y.id_kota);
			f.find('.provinsi').val(y.id_provinsi).trigger('change');

			f.find('.kota').attr('data-temp_id',y.id_kecamatan);
			f.find('.kota').val(y.id_kota).trigger('change');

			f.find('.keterangan').val(y.id_keterangan).trigger('change');
			f.find('.harga').val(y.harga);
			
		});
		status_harga = false;
	}else{
		add_item();
	}
}

$('#filter_cabang').change(function(){
	getData();
});

$(document).ready(function () {
	resize_window();
	$('#result2 tbody').html('');	
	getData(); 
	get_jenis_kantor();
	get_tahapan();
	get_jadwal();
	get_status();
	get_provinsi();
	get_keterangan();
});	

var xhr_ajax = null;
function getData() {
	var cabang = $('#filter_cabang').val();
	if(!cabang){ return ''; }
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/usulan_kantor/data';
	page 	+= '/'+$('#filter_anggaran').val();
	page 	+= '/'+$('#filter_cabang').val();

	if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }

	xhr_ajax = $.ajax({
		url 	: page,
		data 	: {},
		type	: 'get',
		dataType: 'json',
		success	: function(response) {
			xhr_ajax = null;
			$('.table-app tbody').html(response.table);
			$('#parent_id').html(response.option);
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
				$(this)[0].innerHTML = $(this)[0].innerHTML.replace(/[^0-9\-]/g, '');
			}
		}
		return true;
	}
	if(wh == 0 || wh == 8 || wh == 45 || (48 <= wh && wh <= 57) || (96 <= wh && wh <= 105)) 
		return true;
	return false;
});


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

		var vfield = ['nama_kantor','kecamatan','keterangan','penjelasan'];

		if (jQuery.inArray($(this).attr('data-name'),vfield) != -1) {
			data_edit[$(this).attr('data-id')][$(this).attr('data-name')] = $(this).text();
		}else{
			data_edit[$(this).attr('data-id')][$(this).attr('data-name')] = $(this).text().replace(/[^0-9\-]/g,'');
		}

		i++;
	});
	
	var jsonString = JSON.stringify(data_edit);		
	$.ajax({
		url : base_url + 'transaction/usulan_kantor/save_perubahan',
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
var dt_index = 0;
function add_item() {
	dt_index++;
	var konten = '<tr>';
				konten += '<td width="350"><input type="text" class="form-control cabang w-200" name="cabang[]" data-validation="required" autocomplete="off"/></td>';
				konten += '<td width="350"><input type="text" class="form-control nama_kantor w-200" name="nama_kantor[]" data-validation="required" autocomplete="off"/></td>';

				konten += '<td width="350"><select class="form-control pilihan renc" name="rencana[]" aria-label="" data-validation="required">'+rencana+'</select></td>';
				konten += '<td width="350"><select class="form-control pilihan tah" width ="200" name="tahapan[]" aria-label="" data-validation="required">'+tahapan1+'</select></td>';
				
				// jenis kantor
				konten += '<td width="350"><select class="form-control pilihan jenis" name="jenis_kantor[]" aria-label="" data-validation="required" data-key="'+dt_index+'">'+jenis_kantor+'</select></td>';
				konten += '<td><input id="harga'+dt_index+'" class="money form-control w-200 text-right harga" name="harga[]" data-validation="required"/></td>';
				

				konten += '<td width="350"><select class="form-control pilihan jadwal" name="jadwal[]" aria-label="" data-validation="required">'+jadwal1+'</select></td>';
				konten += '<td width="350"><select class="form-control pilihan status_ket" name="status_ket[]" aria-label="" data-validation="required">'+status+'</select></td>';

				// kecamatan
				konten += '<td width="350"><select class="form-control pilihan provinsi" name="provinsi[]" aria-label="" data-validation="required" data-to_id="kota'+dt_index+'" data-provinsi="active">'+dt_provinsi+'</select></td>';
				konten += '<td width="350"><select class="form-control pilihan kota" name="kota[]" aria-label="" data-validation="required" id="kota'+dt_index+'" data-to_id="kecamatan'+dt_index+'" data-kota="active"></select></td>';
				konten += '<td width="350"><select class="form-control pilihan kecamatan" name="kecamatan[]" aria-label="" data-validation="required" id="kecamatan'+dt_index+'"></select></td>';

				// keterangan
				konten += '<td width="350"><select class="form-control pilihan keterangan" name="keterangan[]" aria-label="" data-validation="required">'+dt_keterangan+'</select></td>';
				
				konten += '<td><button type="button" class="btn btn-sm btn-icon-only btn-danger btn-remove"><i class="fa-times"></i></button></td>';
		+ '</tr>';
	$('#result2 tbody').append(konten);
	var $t = $('#result2 .pilihan:last-child');
	money_init();
	$.each($t,function(k,o){
		var $o = $(o);
		$o.select2({
			dropdownParent : $o.parent(),
			placeholder : ''
		});
	})
	index++;
}

var relokasi = 'not';
function get_rencana() {
	var cabang = $('#kode_cabang option:selected').val();
	if(proccess) {
		$.ajax({
			url : base_url + 'transaction/usulan_kantor/get_rencana/echo/'+cabang,
			data : {},
			type : 'POST',
			success	: function(response) {
				rencana = response.data;
				if(relokasi != response.relokasi){
					relokasi = response.relokasi;
					$(document).find('.renc').html(rencana);
				}
				var response = response_edit;
				if(typeof response.id != 'undefined') {
					$.each(response.detail,function(x,y){
						$(document).find('.renc').eq(x).val(y.id_rencana).trigger('change');
					});
				}

			}
		});
	}
}

function get_status() {
	if(proccess) {
	//	readonly_ajax = false;
		$.ajax({
			url : base_url + 'transaction/usulan_kantor/get_status',
			data : {},
			type : 'POST',
			success	: function(response) {
				status = response;
	//			readonly_ajax = true;				
			}
		});
	}
}

function get_tahapan() {
	if(proccess) {
	//	readonly_ajax = false;
		$.ajax({
			url : base_url + 'transaction/usulan_kantor/get_tahapan',
			data : {},
			type : 'POST',
			success	: function(response) {
				tahapan1 = response;
	//			readonly_ajax = true;				
			}
		});
	}
}

function get_jenis_kantor() {
	if(proccess) {
	//	readonly_ajax = false;
		$.ajax({
			url : base_url + 'transaction/usulan_kantor/get_jenis_kantor',
			data : {},
			type : 'POST',
			success	: function(response) {
				jenis_kantor = response;
	//			readonly_ajax = true;				
			}
		});
	}
}

function get_jadwal() {
	if(proccess) {
	//	readonly_ajax = false;
		$.ajax({
			url : base_url + 'transaction/usulan_kantor/get_jadwal',
			data : {},
			type : 'POST',
			success	: function(response) {
				jadwal1 = response;
	//			readonly_ajax = true;				
			}
		});
	}
}
function get_provinsi(){
	if(proccess){
		cLoader.open(lang.memuat_data + '...');
		$.ajax({
			url : base_url + 'api/provinsi_option',
			data : {},
			type : 'POST',
			success	: function(response) {
				dt_provinsi = response.data;
				cLoader.close();		
			}
		});
	}
}
function get_keterangan(){
	if(proccess){
		cLoader.open(lang.memuat_data + '...');
		$.ajax({
			url : base_url + 'api/kategori_kantor_keterangan_option',
			data : {},
			type : 'POST',
			success	: function(response) {
				dt_keterangan = response.data;
				cLoader.close();		
			}
		});
	}
}

$(document).on('change','.jenis',function(){
	if(!status_harga){
		var key 	= $(this).attr('data-key');
		var harga 	= $('option:selected', this).attr('data-harga');
		if(harga == 0 || harga == '0'){
			harga = '';
		}
		$(document).find('#harga'+key).val(harga).trigger('change');
	}
});
$(document).on('click','.btn-detail',function(){
	var page = base_url + 'transaction/usulan_kantor/detail/' + $(this).attr('data-id');
	page += '/'+$('#filter_cabang').val();
	$.get(page,function(res){
		cInfo.open(lang.detil,res,{modal_lg:true});
	});
});
$(document).on('change','#kode_cabang',function(){
	get_rencana();
})
</script>