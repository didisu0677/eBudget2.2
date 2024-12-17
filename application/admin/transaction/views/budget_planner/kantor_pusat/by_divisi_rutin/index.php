<style type="text/css">
.mb-md-0 .select2-container--default .select2-selection--single{
	width: auto !important;
}
.mb-md-0 .select2-container--default .select2-selection--single {
     min-width: auto !important; 
}
.style-select2 .select2-container--default{
	width: 100% !important;
}
.style-select2 .select2-container--default .select2-selection--single{
	width: 100% !important;
}
.t-column{
	font-size:10px;
    color:#d80505;
}
</style>
<div class="content-header page-data" data-additional="<?= $access_additional ?>" data-type="divisi">
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
    			echo $option.' '; 
    			if($access_edit):
    			echo '<button class="btn btn-success btn-save" href="javascript:;" > Save <span class="fa-save"></span></button>';
				endif;
				$arr = [
					// ['btn-save','Save Data','fa-save'],
				    // ['btn-export','Export Data','fa-upload'],
				    // ['btn-import','Import Data','fa-download'],
				    // ['btn-template','Template Import','fa-reg-file-alt']
				];
				echo access_button('',$arr); 
			?>
    		</div>
			<div class="clearfix"></div>
	</div>
	<?php $this->load->view($sub_menu); ?>
</div>
<div class="content-body mt-6">
<?php $this->load->view($sub_menu); ?>
	<div class="main-container mt-2">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header text-center"><?= $title ?> <br>(<?= get_view_report() ?>)</div>
	    			<div class="card-body">
	    				<div class="table-responsive tab-pane fade active show height-window">
	    					<?php
							$thn_sebelumnya = user('tahun_anggaran') -1;
							table_open('table table-striped table-bordered table-app',false,'','','data-table="tbl_m_produk"');
								thead();
									tr();
										th(lang('no'),'','width="60" class="text-center align-middle"');
										th('Kegiatan','','style="min-width:250px" class="text-center align-middle"');
										th('COA','','style="min-width:200px" class="text-center align-middle"');
										th('Keterangan','','style="min-width:250px" class="text-center align-middle"');
										foreach ($detail_tahun as $k2 => $v2) {
											$column = month_lang($v2->bulan).' '.$v2->tahun;
											$column .= '('.$v2->singkatan.')';
											$column .= '<br> <span class="t-column">Biaya Pd Bulan</span>';
											th($column,'','style="min-width:200px" class="text-center align-middle"');
										}
										th('&nbsp;','','width="30" class="text-center align-middle"');
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
modal_open('modal-form','','modal-lg',' data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('transaction/plan_by_divisi_rutin/save'),'post','form'); 
			col_init(2,2);
				input('hidden','id','id');
				input('text',lang('tahun'),'tahun_anggaran','',user('tahun_anggaran'),'disabled');
				echo cabang($cabang);
			col_init(2,9);
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();

function cabang($cabang_input){
	$option = '';
	foreach($cabang_input as $b){
	if($b['kode_cabang'] == user('kode_cabang'))  $selected = ' selected'; else $selected = '';
	$option .= '<option value="'.$b['kode_cabang'].'"'.$selected.'>'.$b['nama_cabang'].'</option>';
	$item = '<div class="form-group row">
		<label class="col-form-label col-md-2">'.lang('cabang').' &nbsp</label>
		<div class="col-md-6 mb-1 mb-md-0">	
			<select class="select2 infinity custom-select" id="kode_cabang" name="kode_cabang">'.$option.'</select>   
		</div>
	</div>';
	$item .= '<div class="card mb-2">
				<div class="mb-3">	
				<div class="table-responsive">
				    <table class="table table-bordered" id="result2">
						<thead>
							<tr>
								<th width="10">
									<button type="button" class="btn btn-sm btn-icon-only btn-success btn-add-item"><i class="fa-plus"></i></button>
								</th>
								<th class="text-center">Kegiatan</th>
								<th class="text-center">COA</th>
								<th></th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				</div>
			</div>';
	}
	return $item;
}
?>
<script type="text/javascript">
var dt_coa 			  = '';
var dt_index = 0;
var response_data = [];
$(document).ready(function () {
	getData();
});
$('#filter_tahun').change(function(){getData();});
$('#filter_cabang').change(function(){getData();});
function getData() {
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/plan_by_divisi_rutin/data';
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
			dt_coa = response.coa;
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
	if(typeof response_data.detail != 'undefined') {
		var list = response_data.data;
		$('.btn-add-item').hide();
		$('#id').val(response_data.detail.id);
		$.each(list, function(x,v){
			if(x == 0){
				add_item(1);
			}else{
				add_item_activity(dt_index);
			}
			var f = $('#result2 tbody tr').last();
			f.find('.kegiatan').val(v.kegiatan);
			f.find('.coa'+dt_index).val(v.coa).trigger('change');
			f.find('.dt_id'+dt_index).val(v.id);
			if(v.produk == 1){
				f.find('.produk').prop('checked',true);
			}else{
				f.find('.produk').prop('checked',false);
			}
		})
	}else{
		$('.btn-add-item').show();
		add_item(0);
	}
}

$(document).on('click','.btn-add-item',function(){
	add_item(0);
});
$(document).on('click','.btn-remove',function(){
	key = $(this).data('id');
	$('#result2 tbody .dt'+key).remove();
});
function add_item(key){
	dt_index += 1;
	var item = '';
	var item_btn = '';
	var item_class = '';
	if(key == 0){
		item_btn = '<button type="button" data-id="'+dt_index+'" class="btn btn-sm btn-icon-only btn-info btn-add-item-activity"><i class="fa-plus"></i></button>';
	}else if(key == 1){
		item_btn == '';
	}else{
		item_class = ' mt-1';
		item_btn = '<button type="button" data-id="'+dt_index+'" class="btn btn-sm btn-icon-only btn-warning btn-delete-item-activity"><i class="fa-times"></i></button>';
	}
	var konten = '<tr class="dt'+dt_index+'">';
		konten += '<td class="remove_dt'+dt_index+'"><button data-id="'+dt_index+'" type="button"class="btn btn-sm btn-icon-only btn-danger btn-remove"><i class="fa-times"></i></button></td>';
		konten += '<td class="index_dt'+dt_index+'"><input type="text" autocomplete="off" class="form-control kegiatan" name="kegiatan[]" aria-label="" data-validation="required"/><input type="hidden" name="dt_index[]" class="dt_index" value='+dt_index+'></td>';
		konten += `<td class="style-select2"><select style="width:100%" class="form-control pilihan coa`+dt_index+`" name="coa`+dt_index+`[]" data-validation="required">`+dt_coa+`</select>
			<input type="hidden" name="dt_id`+dt_index+`[]" class="dt_id`+dt_index+`">
			</td>`;
		konten += '<td>'+item_btn+'</td>';
	konten += '</tr>';
	$('#result2 tbody').append(konten);
	var $t = $('#result2 .pilihan').last();
	$.each($t,function(k,o){
		var $o = $(o);
		$o.select2({
			dropdownParent : $o.parent(),
			placeholder : ''
		});
	})
}

$(document).on('click','.btn-add-item-activity',function(){
	add_item_activity($(this).data('id'),1);
});
$(document).on('click','.btn-delete-item-activity',function(){
	key = $(this).data('id');
	$(this).closest('tr').remove();

	var count = $('#result2 tbody .dt'+key).length;
	$('#result2 tbody .index_dt'+key).attr('rowspan',count);
	$('#result2 tbody .remove_dt'+key).attr('rowspan',count);
});
function add_item_activity(key,p1){
	var item = '';
	var item_btn = '';
	var item_class = '';
	if(p1 == 0){
		item_btn = '<button type="button" data-id="'+key+'" class="btn btn-sm btn-icon-only btn-info btn-add-item-activity"><i class="fa-plus"></i></button>';
	}else{
		item_class = ' mt-1';
		item_btn = '<button type="button" data-id="'+key+'" class="btn btn-sm btn-icon-only btn-warning btn-delete-item-activity"><i class="fa-times"></i></button>';
	}
	var konten = '<tr class="dt'+key+'">';
		konten += `<td class="style-select2"><select style="width:100%" class="form-control pilihan coa`+key+`" name="coa`+key+`[]" data-validation="required">`+dt_coa+`</select>
			<input type="hidden" name="dt_id`+key+`[]" class="dt_id`+key+`"></td>`;
		konten += '<td>'+item_btn+'</td>';
		konten += '</tr>';
		$('#result2 tbody .dt'+key).last().after(konten);

		var count = $('#result2 tbody .dt'+key).length;
		$('#result2 tbody .index_dt'+key).attr('rowspan',count);
		$('#result2 tbody .remove_dt'+key).attr('rowspan',count);

		var $t = $('#result2 .pilihan').last();
		$.each($t,function(k,o){
			var $o = $(o);
			$o.select2({
				dropdownParent : $o.parent(),
				placeholder : ''
			});
		})
	
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
		url : base_url + 'transaction/plan_by_divisi_rutin/save_perubahan',
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
</script>