<style type="text/css">
	.bg-1{
		background: #f2f9ff;
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
							table_open('table table-bordered table-app',false);
								thead();
									tr();
										tr();
										th(lang('no'),'','width="60" class="text-center align-middle"');
										th('Kebijakan Fungsi','','class="text-center align-middle"');
										th('Uraian','','class="text-center align-middle"');
										th('Anggaran','','class="text-center align-middle"');
										th('Kantor Cabang','','class="text-center align-middle"');
										th('Pelaksanaan','','class="text-center align-middle"');
										th('&nbsp;','','width="30", rowspan="2" class="text-center align-middle"');
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
modal_open('modal-form','','modal-lg w-90-per',' data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('transaction/asumsi_kebijakan_fungsi/save'),'post','form'); 
			col_init(2,4);
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
		<div class="col-md-4 col-9 mb-1 mb-md-0">	
			<select class="select2 infinity custom-select" id="kode_cabang" name="kode_cabang">'.$option.'</select>   
		</div>
	</div>';
	$item .= '<div class="card mb-2">
				<div id="result2" class="mb-3">	
				<div class="table-responsive">
				    <table class="table table-bordered" id="result2">
						<thead>
							<tr>
								<th class="text-center">KEBIJAKAN FUNGSI</th>
								<th class="text-center">URAIAN</th>
								<th class="text-center">ANGGARAN</th>
								<th class="text-center">KANTOR CABANG</th>
								<th class="text-center">PELAKSANAAN</th>
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
<script type="text/javascript" src="<?php echo base_url('assets/js/maskMoney.js') ?>"></script>
<script type="text/javascript">
var dt_kebijakan_fungsi = '';
var dt_index = 0;
var response_data = [];
$(document).ready(function () {
	getData();
});
$('#filter_tahun').change(function(){getData();});
$('#filter_cabang').change(function(){getData();});
function getData() {
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/asumsi_kebijakan_fungsi/data';
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
	get_kebijakan_fungsi();	
}
function get_kebijakan_fungsi(){
	if(proccess) {
		$.ajax({
			url : base_url + 'transaction/asumsi_kebijakan_fungsi/get_kebijakan_fungsi',
			data : {},
			type : 'POST',
			success	: function(response) {
				dt_kebijakan_fungsi = response;
				add_item();
				if(typeof response_data.detail != 'undefined') {
					var list = response_data.data;
					$('.btn-add-item').hide();
					$('#id').val(response_data.detail.id);
					$.each(list, function(k,v){
						if(k != 0){
							add_item();
						}
						var f = $('#result2 tbody tr').last();
						f.find('.kebijakan_fungsi').val(v.id_kebijakan_fungsi).trigger('change');
						f.find('.uraian').val(v.uraian);
						f.find('.anggaran').val(numberFormat(v.anggaran,0,',','.'));
						f.find('.kantor_cabang').val(v.kantor_cabang);
						f.find('.pelaksanaan').val(v.pelaksanaan);
						f.find('.dt_id').val(v.id);
						if(v.produk == 1){
							f.find('.produk').prop('checked',true);
						}else{
							f.find('.produk').prop('checked',false);
						}
					})
				}else{
					$('.btn-add-item').show();
				}
				$(".money").maskMoney({allowNegative: true, thousands:'.', decimal:',', precision: 0});
			}
		});
	}
}
$(document).on('click','.btn-add-item',function(){
	add_item();
});
$(document).on('click','.btn-remove',function(){
	$(this).closest('tr').remove();
});
function add_item(p1){
	dt_index += 1;
	var konten = '<tr>';
		konten += '<td><input type="hidden" class="dt_id" name="dt_id[]"/><select class="form-control pilihan kebijakan_fungsi" name="kebijakan_fungsi[]" data-validation="required">'+dt_kebijakan_fungsi+'</select></td>';
		konten += '<td><input type="text" autocomplete="off" class="form-control uraian" name="uraian[]" aria-label="" data-validation="required"/></td>';
		konten += '<td><input type="text" autocomplete="off" class="form-control money anggaran text-right" name="anggaran[]" aria-label="" data-validation="required"/></td>';
		konten += '<td><input type="text" autocomplete="off" class="form-control kantor_cabang" name="kantor_cabang[]" aria-label="" data-validation="required"/></td>';
		konten += '<td><input type="text" autocomplete="off" class="form-control pelaksanaan" name="pelaksanaan[]" aria-label="" data-validation="required"/></td>';
		konten += '<td><button type="button"class="btn btn-sm btn-icon-only btn-danger btn-remove"><i class="fa-times"></i></button></td>';
	konten += '</tr>';
	$('#result2 tbody').append(konten);
	var $t = $('#result2 .pilihan:last-child');
	$.each($t,function(k,o){
		var $o = $(o);
		$o.select2({
			dropdownParent : $o.parent(),
			placeholder : ''
		});
	})
}
</script>