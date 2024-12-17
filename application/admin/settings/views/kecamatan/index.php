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
	table_open('',true,base_url('settings/kecamatan/data'),'kecamatan');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('provinsi'),'','data-content="name" data-table="provinsi provinsi"');
				th(lang('kota'),'','data-content="name" data-table="kota kota"');
				th(lang('nama_kecamatan'),'','data-content="name"');
				th(lang('aktif').'?','text-center','data-content="is_active" data-type="boolean"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','',' data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('settings/kecamatan/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			select2(lang('provinsi'),'id_provinsi','required',$opt_id_provinsi,'id','name','','data-to_id="id_kota" data-provinsi="active"');
			select2(lang('kota'),'id_kota','required',$opt_id_kota,'id','name');
			input('text',lang('nama_kecamatan'),'name','required');
			toggle(lang('aktif').'?','is_active');
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
modal_open('modal-import',lang('impor'));
	modal_body();
		form_open(base_url('settings/kecamatan/import'),'post','form-import');
			col_init(3,9);
			fileupload('File Excel','fileimport','required','data-accept="xls|xlsx"');
			form_button(lang('impor'),lang('batal'));
		form_close();
modal_close();
?>

<script type="text/javascript">
function formOpen() {
	var response_data = response_edit;
	if(typeof response_data.id != 'undefined') {
		$('#id_provinsi').attr('data-temp_id',response_data.id_kota);
		$('#id_provinsi').val(response_data.id_provinsi).trigger('change');
	}else{
		$('#id_provinsi').attr('data-temp_id','');
	}
}
</script>