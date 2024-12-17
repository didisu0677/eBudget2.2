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
			<label class=""><?php echo 'Akun'; ?>  &nbsp</label>
			<select class="select2 custom-select" id="filter_cabang">
               
                <option value="" >Giro</option>
                <option value="" >Dana Pihak Ketiga</option>
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
				th('Kode Cabang','','width="60" rowspan="2"  class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;"');
				th('Coa Baru','',' rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;width:auto;"');
				th('Jenis Kantor','',' rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;width:auto;"');
				th('','',' rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;width:auto;"');
				th('','',' rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;width:auto;"');
				th('Nama Kantor','',' rowspan="2" colspan = "2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;display:block;width:auto;min-width:230px"');
				th('Agu 19 Real.','',' rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;width:auto;"');
				th('Des 19 Real.','',' rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;width:auto;"');
				th('Jul 20 Real.','',' rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;width:auto;"');
				th('Agu 20 Renc.','',' rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;width:auto;"');
				th('Agu 20 Real.','',' rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;width:auto;"');
				th('Renc %.','',' rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;width:auto;"');
				th('Pert %.','',' rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;width:auto;"');
		
		tbody();
			tr();
				td('1');
				td('2');
				td('KC');
				td('2-149999l');
				td('TOT_002');
				td('Surakarta');
				td('1.421.212.210');
				td('1.421.212.210');
				td('0');
				td('21.212.210');
				td('1.312.210');
				td('0.00');
				td('11.2');

			tr();
				td('3');
				td('3');
				td('KC');
				td('2-149999l');
				td('TOT_003');
				td('Purwokerto');
				td('1.421.212.210');
				td('1.421.212.210');
				td('0');
				td('21.212.210');
				td('1.312.210');
				td('0.00');
				td('11.2');
		
			tr();
				td('1');
				td('2');
				td('Capem');
				td('2-149999l');
				td('TOT_009');
				td('Capem Manahan');
				td('1.421.212.210');
				td('1.421.212.210');
				td('0');
				td('21.212.210');
				td('1.312.210');
				td('0.00');
				td('11.2');

	table_close();

	?>
</div>
</div>
<script type="text/javascript">

</script>