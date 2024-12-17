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
	table_open('',true,base_url('settings/m_monly_performance_nilai/data'),'tbl_m_monly_performance_nilai');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('foto'),'','data-content="image" data-type="image"');
				th(lang('nama'),'','data-content="nama"');
				th(lang('warna'),'text-center','data-content="warna" data-type="color"');
				th(lang('keterangan'),'','data-content="keterangan"');
				th(lang('aktif').'?','text-center','data-content="is_active" data-type="boolean"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','modal-xl');
	modal_body();
		form_open(base_url('settings/m_monly_performance_nilai/save'),'post','form');
			echo '<div class="row">';
			echo '<div class="col-md-4">
				Ketentuan membuat formula : </br>
				<ul>
					<li><span class="red"> < </span> adalah Kurang Dari</li>
					<li><span class="red"> <= </span> adalah Kurang Dari Sama Dengan</li>
					<li><span class="red"> > </span> adalah Lebih Dari</li>
					<li><span class="red"> >= </span> adalah Lebih Dari Sama Dengan</li>
					<li><span class="red"> == </span> adalah Sama Dengan</li>
				</ul>
				Contoh membuat formula : </br>
				<ul>
					<li>$$value <span class="red"> < </span> 80</li>
					<li>$$value <span class="red"> >= </span> 80 <span class="red"> && </span> $$value <span class="red"> < </span> 90</li>
				</ul>
				Keterangan : </br>
				<ul>
					<li><span class="red"> $$value </span> adalah nilai dari data</li>
					<li><span class="red"> && </span> untuk membuat lebih dari 1 kondisi</li>
				</ul>
				</div>';
			
			echo '<div class="col-md-8">';
				col_init(3,9);
				input('hidden','id','id');
				imageupload(lang('foto'),'image',30,30,'');
				input('text',lang('nama'),'nama','required');
				input('text',lang('formula'),'formula','required');
				input('color',lang('warna'),'warna','required');
				textarea(lang('keterangan'),'keterangan');
				toggle(lang('aktif').'?','is_active');
				form_button(lang('simpan'),lang('batal'));
			echo '</div>';
			echo '</div>';
		form_close();
	modal_footer();
modal_close();
modal_open('modal-import',lang('impor'));
	modal_body();
		form_open(base_url('settings/m_monly_performance_nilai/import'),'post','form-import');
			col_init(3,9);
			fileupload('File Excel','fileimport','required','data-accept="xls|xlsx"');
			form_button(lang('impor'),lang('batal'));
		form_close();
modal_close();
?>
