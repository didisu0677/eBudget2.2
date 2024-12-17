<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb($title); ?>
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
			<label class=""><?php echo lang('cabang'); ?>  &nbsp</label>
			<select class="select2 custom-select" id="filter_cabang">
                <?php foreach($cabang as $b){ ?>
                <option value="<?php echo $b['kode_cabang']; ?>" <?php if($b['kode_cabang'] == user('kode_cabang')) echo ' selected'; ?>><?php echo $b['nama_cabang']; ?></option>
                <?php } ?>
			</select>   	
    		<?php
    		// echo '<button class="btn btn-success btn-save" href="javascript:;" > Save <span class="fa-save"></span></button>';
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
	<?php // $this->load->view($path.'sub_menu'); ?>
</div>
<div class="content-body m-t-column">
	<div class="table-responsive tab-pane fade active show" id="result1" style="margin-top: 2%">
	<?php 

		table_open('table table-bordered table-app table-hover table-1');
		thead();
			tr();
				th(lang('coa'),'','width="60" rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;"');
				th(lang('keterangan'),'','rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;display:block;width:auto;min-width:230px"');

				for ($i = 1; $i <= 12; $i++) { 
					th(month_lang($i),'','class="text-center" style="min-width:80px;background-color: #e64a19; color: white !important;"');		
				}
		
		tbody();
	table_close();

	?>
</div>
</div>
<script type="text/javascript">

</script>