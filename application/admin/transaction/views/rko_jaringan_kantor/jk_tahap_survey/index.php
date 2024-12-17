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
	<?php $this->load->view($sub_menu); ?>
</div>
<div class="content-body">
	<?php
	$this->load->view($sub_menu);
	$thn_sebelumnya = user('tahun_anggaran') -1;
	$tahun_anggaran = user('tahun_anggaran');
	table_open('table table-striped table-bordered table-app',false,'','','data-table="tbl_m_produk"');
		thead();
			tr();
				th(lang('no'),'','rowspan="2" width="60" class="text-center align-middle"');
				th(lang('keterangan'),'','rowspan="2" style="min-width:250px" class="text-center align-middle"');
				th(lang('jenis_jaringan'),'','style="min-width:150px" rowspan="2" class="text-center align-middle"');
				th(lang('nama_cabang'),'','style="min-width:150px" rowspan="2" class="text-center align-middle"');
				th(lang('pic'),'','style="min-width:150px" rowspan="2" class="text-center align-middle"');
					
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
<?php
modal_open('modal-form','','modal-lg w-90-per',' data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('transaction/'.$controller.'/save'),'post','form'); 
			col_init(2,4);
				input('hidden','id','id');
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
								<th class="text-center min-200">'.lang('keterangan').'</th>
								<th class="text-center min-200">'.lang('jenis_jaringan').'</th>
								<th class="text-center min-200">'.lang('nama_cabang').'</th>
								<th class="text-center min-200">'.lang('pic').'</th>
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
var controller = "<?= $controller ?>";
var response_data = [];
$(document).ready(function () {
	getData();
});
$('#filter_tahun').change(function(){getData();});
$('#filter_cabang').change(function(){getData();});

function formOpen() {
	dt_index = 0;
	response_data = response_edit;
	$('#form_table tbody').html('');
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
			f.find('.jenis_jaringan').val(v.jenis_jaringan);
			f.find('.nama_cabang').val(v.nama_cabang);
			f.find('.pic').val(v.pic);
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
		<input type="text" class="form-control keterangan" name="keterangan[]" data-validation="required" />
		</td>`;
	item += '<td><input type="text" class="form-control jenis_jaringan" name="jenis_jaringan[]" data-validation="required" /></td>';
	item += '<td><input type="text" class="form-control nama_cabang" name="nama_cabang[]" data-validation="required" /></td>';
	item += '<td><input type="text" class="form-control pic" name="pic[]" data-validation="required" /></td>';
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
function getData() {
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/'+controller+'/data';
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
		data 	: {ID : ID, val : val},
		type	: 'post',
		dataType: 'json',
		success	: function(response) {
			cLoader.close();
			if(!response.status){
				cAlert.open(res.message,'failed');
			}
		}
	});
});
</script>