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
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	$thn_sebelumnya = user('tahun_anggaran') -1;
	table_open('',true,'','','data-table="tbl_m_produk"');
		thead();
			tr();
				th('No','','class="text-center align-middle"');
				th('RKF ID','','class="text-center align-middle"');
				th('Program Kerja','','class="text-center align-middle"');
				th('Aktivitas Penjelasan','','class="text-center align-middle"');
				th('Status','','class="text-center align-middle"');
				th('Estimasi s/d Desember','','class="text-center align-middle"');
		tbody();
	table_close();
	?>
</div>
<script type="text/javascript">
$(document).ready(function () {
	getData();
});
$('#filter_tahun').change(function(){getData();});
$('#filter_cabang').change(function(){getData();});
function getData() {
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/monthly_performance_pkf/data';
	page 	+= '/'+$('#filter_anggaran').val();
	page 	+= '/'+$('#filter_cabang').val();

	$.ajax({
		url 	: page,
		data 	: {},
		type	: 'get',
		dataType: 'json',
		success	: function(response) {
			response_data = [];
			$('.table-app tbody').html(response.table);
			cLoader.close();
			cek_autocode();
			fixedTable();
		}
	});
}
</script>