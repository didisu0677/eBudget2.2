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
				td('1750');
				td('');
				td('A');
				td('A. PENDAPATAN DAN BEBAN BUNGA');
				foreach ($detail_tahun as $v) {
					td(0);
				} 
			tr();
				td('1000');
				td('');
				td(4100000);
				td('1. PENDAPATAN BUNGA');
				foreach ($detail_tahun as $v) {
					td(0);
				} 
			tr();
				td('1020');
				td('');
				td(4110000);
				td('A. DARI BANK INDONESIA');
				foreach ($detail_tahun as $v) {
					td(0);
				}
			tr();
				td('');
				td('');
				td(4120000);
				td('PENEMPATAN PADA BANK LAIN');
				foreach ($detail_tahun as $v) {
					td(0);
				} 
			tr();
				td('');
				td('');
				td(4121000);
				td('I. GIRO');
				foreach ($detail_tahun as $v) {
					td(0);
				}
			tr();
				td('');
				td('');
				td(4122000);
				td('II. INTERBANK CALL MONEY');
				foreach ($detail_tahun as $v) {
					td(0);
				}
			tr();
				td('');
				td('');
				td(4123000);
				td('III. TABUNGAN');
				foreach ($detail_tahun as $v) {
					td(0);
				}
			tr();
				td('');
				td('');
				td(4124000);
				td('IV. SIMPANAN BERJANGKA');
				foreach ($detail_tahun as $v) {
					td(0);
				}
	table_close();
	?>
	</div>
</div>