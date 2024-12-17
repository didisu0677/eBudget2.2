<style type="text/css">
.min-200{
	min-width: 250px;
}
</style>
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
	<div class="sub_menu"></div>
</div>
<div class="content-body">
	<div class="sub_menu"></div>
	<div class="main-container">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
	    			<div class="card-header text-center"><?= $title ?></div>
	    			<div class="card-body">
	    				<div class="table-responsive tab-pane fade active show height-window" id="result1" data-height="70">
	    				<?php
						$thn_sebelumnya = user('tahun_anggaran') -1;
						$tahun_anggaran = user('tahun_anggaran');
						table_open('table table-striped table-bordered table-app',false,'','','data-table="tbl_m_produk"');
							thead();
								tr();
									th(lang('no'),'','rowspan="2" width="60" class="text-center align-middle"');
									th(lang('nama_aset'),'','rowspan="2" style="min-width:250px" class="text-center align-middle"');
									th(lang('harga'),'','rowspan="2" style="min-width:250px" class="text-center align-middle"');
									th(lang('nama_cabang'),'','style="min-width:150px" rowspan="2" class="text-center align-middle"');
									th(lang('pic'),'','style="min-width:150px" rowspan="2" class="text-center align-middle"');
									th(lang('keterangan'),'','style="min-width:150px" rowspan="2" class="text-center align-middle"');
										
									foreach ($arrWeekOfMonth['month'] as $k => $v) {
										th(month_lang($k),'','colspan="'.$v.'" style="min-width:200px" class="text-center align-middle"');
									}

									th('&nbsp;','','rowspan="2" width="30" class="text-center align-middle"');
								tr();
									foreach ($arrWeekOfMonth['week'] as $k => $v) {
										$d = $arrWeekOfMonth['detail'][$v];
										$x = explode("-", $d);
										$date_string = $x[2] . 'W' . sprintf('%02d', $x[0]);
					    				$first_day = sprintf('%02d', date('j', strtotime($date_string)));
										th($first_day,'','class="text-center align-middle"');
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
if($access_additional) $cabang_input = $cabang;
modal_open('modal-form','','modal-lg w-90-per',' data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('transaction/'.$controller.'/save'),'post','form'); 
			col_init(2,4);
				input('hidden','id','id');
				input('hidden','id_group_inventaris','id_group_inventaris');
				input('text',lang('tahun'),'tahun_anggaran','',user('tahun_anggaran'),'disabled');
				echo cabang($cabang_input);
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
		<div class="col-md-4 col-9 mb-1 mb-md-0">	
			<select class="select2 infinity custom-select" id="kode_cabang" name="kode_cabang">'.$option.'</select>   
		</div>
	</div>';
	$item .= '<div class="card mb-2">
				<div class="mb-3">	
				<div class="table-responsive">
				    <table class="table table-bordered" id="form_table">
						<thead>
							<tr>
								<th class="text-center min-200">'.lang('nama_aset').'</th>
								<th class="text-center min-200">'.lang('harga').'</th>
								<th class="text-center min-200">'.lang('nama_cabang').'</th>
								<th class="text-center min-200">'.lang('pic').'</th>
								<th class="text-center min-200">'.lang('keterangan').'</th>
								<th width="10">
									<button type="button" class="btn btn-sm btn-icon-only btn-success btn-add-item"><i class="fa-plus"></i></button>
								</th>
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
var controller 	= "<?= $controller ?>";
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
</script>