<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<?php 
				$arr = [];
					$arr = [
					    ['btn-import','Import Data','fa-download'],
					    ['btn-template','Template Import','fa-reg-file-alt']
					];
				
				
				echo access_button('',$arr); 
			?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('settings/import_rincian_kredit/data'),'tbl_trx_import_rincian_kredit');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('tanggal'),'','data-content="tanggal_import" data-type="daterange"');
				th(lang('nama'),'','data-content="nama_db"');
				th(lang('aktif').'?','text-center','data-content="is_active" data-type="boolean"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form');
	modal_body();
		form_open(base_url('settings/import_rincian_kredit/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			input('datetime',lang('tanggal'),'tanggal_import','required');
			input('text',lang('nama'),'nama_db','required');
			toggle(lang('aktif').'?','is_active');
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
modal_open('modal-import',lang('impor'));
	modal_body();
		form_open(base_url('settings/import_rincian_kredit/import'),'post','form-import');
			col_init(3,9);
			fileupload('File Excel','fileimport','required','data-accept="xls|xlsx"');
			form_button(lang('impor'),lang('batal'));
		form_close();
modal_close();
?>
<script type="text/javascript">
	$('.btn-import').click(function(){
		$('#form-import')[0].reset();

	    $('#modal-import .alert').hide();
	    $('#modal-import').modal('show');
	});
</script>
