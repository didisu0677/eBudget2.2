<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('settings/m_aset_tak_guna/data'),'tbl_m_aset_tak_guna');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('kode_anggaran'),'','width="150" data-content="kode_anggaran"');
				th(lang('kode_inventaris'),'','width="30" data-content="kode_inventaris"');
				th(lang('nama'),'','width="50%" data-content="nama"');
				th(lang('persen').' (%)','','data-content="persen" data-type="percent"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form');
	modal_body();
		form_open(base_url('settings/m_aset_tak_guna/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			input('text',lang('nama'),'nama');
			input('percent',lang('persen'),'persen');
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
modal_open('modal-import',lang('impor'));
	modal_body();
		form_open(base_url('settings/m_aset_tak_guna/import'),'post','form-import');
			col_init(3,9);
			fileupload('File Excel','fileimport','required','data-accept="xls|xlsx"');
			form_button(lang('impor'),lang('batal'));
		form_close();
modal_close();
?>
