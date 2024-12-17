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
	table_open('',true,base_url('settings/import_rko_rekap_target/data'),'tbl_history_import_target_rekap');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('nama'),'','data-content="nama"');
				th(lang('kode_anggaran'),'','data-content="kode_anggaran"');
				th(lang('tahun'),'','data-content="tahun"');
				// th(lang('aktif').'?','text-center','data-content="is_active" data-type="boolean"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 


modal_open('modal-import',lang('impor'));
	modal_body();
		form_open(base_url('settings/import_rko_rekap_target/import'),'post','form-import');
			col_init(3,9);
			fileupload('File Excel','fileimport','required','data-accept="xls|xlsx"');
			form_button(lang('impor'),lang('batal'));
		form_close();
modal_close();
?>

<script>
	$('.btn-import').click(function(){
		$('#form-import')[0].reset();

	    $('#modal-import .alert').hide();
	    $('#modal-import').modal('show');
	});
	
    $(document).on('click','.btn-template',function(){
		console.log('masul');
		var a = '<?=base_url()."assets/templateExcel/templateRekapTarget.xlsx";?>';
		window.open(a);
	});
</script>	
