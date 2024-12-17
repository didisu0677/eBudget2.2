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
			<label class=""><?php echo lang('bulan'); ?>  &nbsp</label>
			<select class="select2 infinity custom-select" id="filter_anggaran">
				<?php for ($i = 1; $i <= 12; $i++) {  ?>
                <option value="<?php echo $i; ?>"><?php echo month_lang($i); ?></option>
                <?php } ?>
			</select>		
			<label class=""><?php echo lang('tahun'); ?>  &nbsp</label>
			<select class="select2 custom-select" id="filter_cabang">
                <?php for($i = 2018; $i <= 2020; $i++){ ?>
                <option value="<?php echo $i ?>" ><?php echo $i; ?></option>
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
	<div class="table-responsive tab-pane fade active show" id="result1" >
	<?php 

		table_open('table table-bordered table-app table-hover table-1');
		thead();
			tr();
				th('No','','width="60"  class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;"');
				th('Kode Cabang','',' class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;width:auto;"');
				th('Nama Cabang','',' class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;width:auto;"');
				th('GL','',' class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;width:auto;"');
				th('Sub GL','',' class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;width:auto;"');
				th('Analisa','',' class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;width:auto;"');

			
		tbody();
			tr();
				td('1');
				td('002');
				td('KC Surakarta');
				td('Non Operasional');
				td('Lainya');
				td('Biaya peresmian kantor kas UNS');

			tr();
				td('2');
				td('003');
				td('KC Purwokerto');
				td('Non Operasional');
				td('BMPD');
				td('Biaya BMPD tahun 2020 dibiayakan januari 2020');

			tr();
				td('2');
				td('012');
				td('KC Cilacap');
				td('Non Operasional');
				td('BMPD');
				td('Biaya BMPD tahun 2020 dibiayakan januari 2020');

	table_close();

	?>
</div>
</div>
<script type="text/javascript">

</script>