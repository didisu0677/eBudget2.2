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
				$arr = [];
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
	</div>

<div class="content-body">
	<?php
	$thn_sebelumnya = user('tahun_anggaran') -1;
	table_open('table table-striped table-bordered table-app',false,'','','data-table="tbl_m_produk"');
		thead();
			tr();
				th(lang('no'),'','width="60" class="text-center align-middle"');
				th(lang('kebijakan_strategis'),'','width="250" class="text-center align-middle"');
				th(lang('aktivitas_kebijakan'),'','class="text-center"');
				th(lang('target'),'','class="text-center"');
				th(lang('deskripsi_kebijakan'),'','class="text-center"');
				th(lang('target_waktu_kebijakan'),'','class="text-center"');
				th(lang('goal'),'','class="text-center"');
				th('&nbsp;','','width="30", class="text-center align-middle"');
		tbody();
	table_close();
	?>
</div>
<?php 

modal_open('modal-form','','modal-xl','data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('transaction/kebijakan_strategis/save'),'post','form'); 
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
				    <table class="table table-bordered table-cabang">
						<thead>
							<tr>
								<th width="10">
									<button type="button" class="btn btn-sm btn-icon-only btn-success btn-add-item"><i class="fa-plus"></i></button>
								</th>
								<th class="text-center"><?= lang('kebijakan_strategis') ?></th>
								<th class="text-center"><?= lang('aktivitas_kebijakan') ?></th>
								<th class="text-center"><?= lang('target') ?></th>
								<th class="text-center"><?= lang('deskripsi_kebijakan') ?></th>
								<th class="text-center"><?= lang('target_waktu_kebijakan') ?></th>
								<th class="text-center"><?= lang('goal') ?></th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>	
		</div>
	<?php
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close(); ?>

<script type="text/javascript">
var response_data = [];
var dt_index = 0;
$(document).ready(function () {
	$('#result2 tbody').html('');
	$('#modal-form .modal-dialog').addClass('w-90-per');
	getData();
});

$('#filter_tahun').change(function(){getData();});
$('#filter_cabang').change(function(){getData();});
function getData() {
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/kebijakan_strategis/data';
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

$(document).on('click','.btn-add-item',function(){
	add_item(0);
});
$(document).on('click','.btn-remove',function(){
	key = $(this).data('id');
	$('#result2 tbody .dt'+key).remove();
});
function add_item(key) {
	var item = '';
	var item_btn = '';
	var item_class = '';
	dt_index += 1;
	if(key == 0){
		item_btn = '<button type="button" data-id="'+dt_index+'" class="btn btn-sm btn-icon-only btn-info btn-add-item-activity"><i class="fa-plus"></i></button>';
	}else if(key == 1){
		item_btn == '';
	}else{
		item_class = ' mt-1';
		item_btn = '<button type="button" data-id="'+dt_index+'" class="btn btn-sm btn-icon-only btn-warning btn-delete-item-activity"><i class="fa-times"></i></button>';
	}
	var konten = '<tr class="dt'+dt_index+'">';
		konten += '<td class="remove_dt'+dt_index+'"><button type="button" data-id="'+dt_index+'" class="btn btn-sm btn-icon-only btn-danger btn-remove"><i class="fa-times"></i></button></td>';
		konten += '<td class="index_dt'+dt_index+'"><input type="text" autocomplete="off" class="form-control kebijakan" name="kebijakan[]" aria-label="" data-validation="required"/><input type="hidden" name="dt_index[]" class="dt_index" value='+dt_index+'></td>';
		konten += `<td><div class="input-group"><input type="text" name="aktivitas`+dt_index+`[]" class="form-control aktivitas`+dt_index+`" autocomplete="off" data-validation="required" value="">
			<input type="hidden" name="id_kebijakan`+dt_index+`[]" class="id_kebijakan`+dt_index+`">
			<div class="input-group-append">`+item_btn+`</div>
			</div>
			</td>`;
		konten += `<td><input type="text" name="target`+dt_index+`[]" class="form-control target`+dt_index+`" autocomplete="off" data-validation="required" value=""></td>`;
		konten += `<td><textarea name="deskripsi`+dt_index+`[]" class="form-control deskripsi`+dt_index+`"  data-validation="required"/></textarea></td>`;
		konten += `<td><input type="date" name="tanggal_target`+dt_index+`[]" class="form-control tanggal_target`+dt_index+`" autocomplete="off" data-validation="required" value=""></td>`;
		konten += `<td><input type="text" name="goal`+dt_index+`[]" class="form-control goal`+dt_index+`" autocomplete="off" data-validation="required" value=""></td>`;
		konten += '</tr>';
	$('#result2 tbody').append(konten);	
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
		konten += `<td><div class="input-group"><input type="text" name="aktivitas`+key+`[]" class="form-control aktivitas`+key+`" autocomplete="off" data-validation="required" value="">
			<input type="hidden" name="id_kebijakan`+key+`[]" class="id_kebijakan`+key+`">
			<div class="input-group-append">`+item_btn+`</div>
			</div>
			</td>`;
		konten += `<td><input type="text" name="target`+key+`[]" class="form-control target`+key+`" autocomplete="off" data-validation="required" value=""></td>`;
		konten += `<td><textarea name="deskripsi`+key+`[]" class="form-control deskripsi`+key+`"  data-validation="required"/></textarea></td>`;
		konten += `<td><input type="date" name="tanggal_target`+key+`[]" class="form-control tanggal_target`+key+`" autocomplete="off" data-validation="required" value=""></td>`;
		konten += `<td><input type="text" name="goal`+key+`[]" class="form-control goal`+key+`" autocomplete="off" data-validation="required" value=""></td>`;
		konten += '</tr>';
		$('#result2 tbody .dt'+key).last().after(konten);

		var count = $('#result2 tbody .dt'+key).length;
		$('#result2 tbody .index_dt'+key).attr('rowspan',count);
		$('#result2 tbody .remove_dt'+key).attr('rowspan',count);
	
}
function formOpen() {
	$('#result2 tbody').html('');
	response_data = response_edit;
	if(typeof response_data.detail != 'undefined') {
		$('.btn-add-item').hide();
		$('#id').val(response_data.detail.id);
		$.each(response_data.data,function(x,y){
			if(x == 0){
				add_item(1);
			}else{
				add_item_activity(dt_index);
			}

			var f = $('#result2 tbody tr').last();
			f.find('.kebijakan').val(y.name);
			f.find('.id_kebijakan'+dt_index).val(y.id);
			f.find('.aktivitas'+dt_index).val(y.aktivitas);
			f.find('.target'+dt_index).val(y.target);
			f.find('.deskripsi'+dt_index).val(y.keterangan);
			f.find('.tanggal_target'+dt_index).val(y.tanggal_target).trigger('change');
			f.find('.goal'+dt_index).val(y.goal);

		});
	}else{
		dt_index = 0;
		add_item(0);
		$('.btn-add-item').show();
	}
}

</script>