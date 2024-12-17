<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
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
	<?php $this->load->view($sub_menu); ?>
</div>
<div class="content-body m-t-column">
	<div class="m-t-tab-table">
	<?php
	$thn_sebelumnya = user('tahun_anggaran') -1;
	table_open('',true,'','','data-table="tbl_m_produk"');
		thead();
			tr();
				th('Sandi','','class="text-center align-middle"');
				th('COA','','class="text-center align-middle"');
				th('COA','','class="text-center align-middle"');
				th('KETERANGAN','','rowspan="2" class="text-center align-middle"');
				foreach ($detail_tahun as $v) {
					$column = month_lang($v->bulan).' '.$v->tahun;
					$column .= '<br> ('.$v->singkatan.')';
					th($column,'','rowspan="2" class="text-center" style="min-width:150px"');
				}
			tr();
				th('BI');
				th('5');
				th('7');
		tbody();
			tr();
				td('290');
				td(10000);
				td(1000000);
				td('AKTIVA');
				foreach ($detail_tahun as $v) {
					td(0);
				}
			tr();
				td('100');
				td(10100);
				td(1100000);
				td('KAS');
				foreach ($detail_tahun as $v) {
					td(0);
				}
			tr();
				td('120');
				td(11000);
				td(1150000);
				td('PENEMPATAN PADA BANK INDONESIA');
				foreach ($detail_tahun as $v) {
					td(0);
				}
			tr();
				td('130');
				td(12000);
				td(1200000);
				td('PENEMPATAN PADA BANK LAIN');
				foreach ($detail_tahun as $v) {
					td(0);
				}
			tr();
				td('');
				td(12001);
				td(1201011);
				td('GIRO');
				foreach ($detail_tahun as $v) {
					td(0);
				}
			tr();
				td('');
				td(12014);
				td(1201012);
				td('GIRO NOSTRO');
				foreach ($detail_tahun as $v) {
					td(0);
				}
			tr();
				td('');
				td(12002);
				td(1201013);
				td('INTERBANK CALL MONEY');
				foreach ($detail_tahun as $v) {
					td(0);
				}
			tr();
				td('');
				td(12003);
				td(1201014);
				td('TABUNGAN');
				foreach ($detail_tahun as $v) {
					td(0);
				}
	table_close();
	?>
	</div>
</div>