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
	table_open('',true,base_url('settings/daftar_inventaris/data'),'tbl_kode_inventaris');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('kode_inventaris'),'','data-content="kode_inventaris"');
				th(lang('nama_inventaris'),'','data-content="nama_inventaris"');
				th(lang('nama_grup_aset'),'','data-content="nama_grup_aset"');
				th(lang('harga'),'text-right','data-content="harga" data-type="currency"');
				th(lang('aktif').'?','text-center','data-content="is_active" data-type="boolean"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form');
	modal_body();
		form_open(base_url('settings/daftar_inventaris/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			input('text',lang('kode_inventaris'),'kode_inventaris');
			input('text',lang('nama_inventaris'),'nama_inventaris');
			select2(lang('grup'),'grup','',$opt_grup,'kode','keterangan');
			input('money',lang('harga'),'harga');
			toggle(lang('aktif').'?','is_active');
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
modal_open('modal-import',lang('impor'));
	modal_body();
		form_open(base_url('settings/daftar_inventaris/import'),'post','form-import');
			col_init(3,9);
			fileupload('File Excel','fileimport','required','data-accept="xls|xlsx"');
			form_button(lang('impor'),lang('batal'));
		form_close();
modal_close();
?>
