<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<?php echo access_button('delete,active,inactive,export,import'); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('settings/m_biaya_rkf/data'),'tbl_m_biaya_rkf');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('coa'),'','data-content="coa"');
				th(lang('aktif').'?','text-center','data-content="is_active" data-type="boolean"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','',' data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('settings/m_biaya_rkf/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			echo '<div class="table-responsive">
				    <table class="table table-bordered" id="table_coa">
						<thead>
							<tr>
								<th class="text-center">'.lang('akun_coa').'</th>
								<th width="10">
									<button type="button" class="btn btn-sm btn-icon-only btn-success btn-add-item"><i class="fa-plus"></i></button>
								</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>';
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
modal_open('modal-import',lang('impor'));
	modal_body();
		form_open(base_url('settings/m_biaya_rkf/import'),'post','form-import');
			col_init(3,9);
			fileupload('File Excel','fileimport','required','data-accept="xls|xlsx"');
			form_button(lang('impor'),lang('batal'));
		form_close();
modal_close();
?>

<script type="text/javascript">
var xhr_ajax 	= null;
var dt_coa 		= [];
$(document).ready(function(){
	get_coa();
})
function formOpen(){
	$('#table_coa tbody').html('');
	response_data = response_edit;
	var length = jQuery.isEmptyObject(response_data);
	if(!length){
		add_item();
		var f = $('#table_coa tbody tr').last();
		f.find('.coa').val(response_data.coa).trigger('change');
		f.find('.dt_id').val(response_data.id);
		$('.btn-add-item, .btn-delete-item').hide();
	}else{
		add_item();
		$('.btn-add-item, .btn-delete-item').show();
	}
}
function get_coa(){
	var url = base_url+"api/coa_option";
	cLoader.open(lang.memuat_data + '...');
	xhr_ajax = $.ajax({
		url 	: url,
		type	: 'get',
		dataType: 'json',
		success	: function(response) {
			xhr_ajax = null;
			dt_coa = response.data;
			cLoader.close();
		}
	});
}
$(document).on('click','.btn-add-item',function(){
	add_item();
});
$(document).on('click','.btn-delete-item',function(){
	$(this).closest('tr').remove();
});
function add_item(){
	item = `<tr>`;
	item += `<td class="style-select2"><input class="dt_id" type="hidden" name="dt_id[]" /><select style="width:100%" class="form-control pilihan coa" name="coa[]" data-validation="required">`+dt_coa+`</select></td>`;
	item += '<td><button type="button" class="btn btn-sm btn-icon-only btn-danger btn-delete-item"><i class="fa-times"></i></button></td>';
	item += '</tr>';
	$('#table_coa tbody').append(item);
	var $t = $('#table_coa .pilihan').last();
	$.each($t,function(k,o){
		var $o = $(o);
		$o.select2({
			dropdownParent : $o.parent(),
			placeholder : ''
		});
	});
}
</script>