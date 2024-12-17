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
	table_open('',true,base_url('settings/kredit_kreatif/data'),'tbl_produk_kredit');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('coa'),'','data-content="coa"');
				th(lang('nama_produk_kredit'),'','data-content="nama_produk_kredit"');
				th(lang('bunga_kredit'),'','data-content="bunga_kredit"');
				th(lang('grup'),'','data-content="grup"');
				th(lang('aktif').'?','text-center','data-content="is_active" data-type="boolean"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form');
	modal_body();
		form_open(base_url('settings/kredit_kreatif/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			input('text',lang('coa'),'coa');
			input('text',lang('nama_produk_kredit'),'nama_produk_kredit');
			input('text',lang('bunga_kredit'),'bunga_kredit');
			input('text',lang('grup'),'grup');
			toggle(lang('aktif').'?','is_active');
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
modal_open('modal-import',lang('impor'));
	modal_body();
		form_open(base_url('settings/kredit_kreatif/import'),'post','form-import');
			col_init(3,9);
			fileupload('File Excel','fileimport','required','data-accept="xls|xlsx"');
			form_button(lang('impor'),lang('batal'));
		form_close();
modal_close();
?>
