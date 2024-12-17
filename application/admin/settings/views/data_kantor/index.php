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
	table_open('',true,base_url('settings/data_kantor/data'),'tbl_m_data_kantor');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('kode_cabang'),'','data-content="kode_cabang"');
				th(lang('nama_kantor'),'','data-content="nama_kantor"');
				th(lang('nama_pimpinan'),'','data-content="nama_pimpinan"');
				th(lang('no_hp'),'','data-content="no_hp"');
				th(lang('tgl_mulai_menjabat'),'','data-content="tgl_mulai_menjabat" data-type="daterange"');
				th(lang('nama_cp'),'','data-content="nama_cp"');
				th(lang('no_hp_cp'),'','data-content="no_hp_cp"');
				th(lang('email_cp'),'','data-content="email_Cp"');
				th(lang('pemeriksa_kp'),'','data-content="pemeriksa_kp"');
				th(lang('no_hp_pemeriksa'),'','data-content="no_hp_pemeriksa"');
				th(lang('aktif').'?','text-center','data-content="is_active" data-type="boolean"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form');
	modal_body();
		form_open(base_url('settings/data_kantor/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			select2(lang('cabang'),'kode_cabang','',$opt_cabang,'kode_cabang','nama_cabang');
			input('text',lang('nama_kantor'),'nama_kantor');
			input('text',lang('nama_pimpinan'),'nama_pimpinan');
			input('text',lang('no_hp'),'no_hp');
			input('date',lang('tgl_mulai_menjabat'),'tgl_mulai_menjabat');
			input('text',lang('nama_cp'),'nama_cp');
			input('text',lang('no_hp_cp'),'no_hp_cp');
			input('text',lang('email_cp'),'email_Cp','email');
			input('text',lang('pemeriksa_kp'),'pemeriksa_kp');
			input('text',lang('no_hp_pemeriksa'),'no_hp_pemeriksa');
			toggle(lang('aktif').'?','is_active');
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
modal_open('modal-import',lang('impor'));
	modal_body();
		form_open(base_url('settings/data_kantor/import'),'post','form-import');
			col_init(3,9);
			fileupload('File Excel','fileimport','required','data-accept="xls|xlsx"');
			form_button(lang('impor'),lang('batal'));
		form_close();
modal_close();
?>
