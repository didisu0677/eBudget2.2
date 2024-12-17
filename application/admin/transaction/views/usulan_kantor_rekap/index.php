<?php
	$key = '';
	foreach ($cabang as $k => $v) {
		if($v['kode_cabang'] == 'G001') $key = $k;
	}
	if(strlen($key)>0) unset($cabang[$key]);

	$rencana 		= array_merge([array('id' => 0,'status_jaringan' => lang('all'))],$rencana);
	$tahapan 		= array_merge([array('id' => 0,'tahapan' => lang('all'))],$tahapan);
	$jenis_kantor 	= array_merge([array('id' => 0,'kategori' => lang('all'))],$jenis_kantor);
	$jenis_kantor_ket 	= array_merge([array('id' => 0,'nama' => lang('all'))],$jenis_kantor_ket);
	$status_kantor 	= array_merge([array('id' => 0,'status_ket' => lang('all'))],$status_kantor);
	$cabang 		= array_merge([array('kode_cabang' => 0,'nama_cabang' => lang('all'))],$cabang);
?>
<style type="text/css">
.content-body .select2-container--default .select2-selection--single{
	min-width: auto !important;
	width: auto !important;
}
.w-200{
	min-width: 200px;
}
.w-150{
	min-width: 150px;
}
</style>
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
    	</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<div class="main-container">
		<div class="row">
			<div class="col-sm-6">
				<div class="card">
					<div class="card-header text-center div-filter">Filter Data</div>
					<div class="card-body div-filter">
						<?php
							if(count($cabang)>2):
								col_init(3,6);
								select2(lang('cabang'),'cabang','',$cabang,'kode_cabang','nama_cabang');
							endif;
							col_init(3,4);
							select2(lang('rencana'),'rencana','',$rencana,'id','status_jaringan');
							select2(lang('tahapan'),'tahapan','',$tahapan,'id','tahapan');
							select2(lang('jenis_kantor'),'jenis_kantor','',$jenis_kantor,'id','kategori');
							select2(lang('status'),'status_kantor','',$status_kantor,'id','status_ket');
							select2(lang('keterangan'),'keterangan','',$jenis_kantor_ket,'id','nama');
						?>
						<div class="form-group row">
							<div class="col-sm-4 offset-sm-3">
								<button type="submit" class="btn btn-info"><?= lang('cari') ?></button>
								<button type="reset" class="btn btn-secondary">Reset</button>
								<button type="button" id="btn-export" class="btn btn-success">Export</button>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="text-right"><a id="btn-filter" href="javascript:;">Sembunyikan Filter</a></div>
					</div>
				</div>
			</div>
		</div>

		<div class="row mt-3">
			<div class="col-sm-12">
				<div class="card">
					<div class="table-responsive tab-pane fade active show height-window" id="result1">
						<?php
						table_open('table table-striped table-bordered table-app table-hover',false);
							thead();
								tr();
									th(lang('no'),'','width="60" class="text-center align-middle"');
									th(lang('cabang_induk'),'','class="text-center align-middle w-200"');
									th(lang('cabang'),'','class="text-center align-middle w-200"');
									th(lang('rencana'),'','class="text-center align-middle w-200"');
									th(lang('tahapan'),'','class="text-center align-middle w-150"');
									th(lang('jenis_kantor'),'','class="text-center align-middle w-150"');
									th(lang('biaya_perkiraan').' ('.get_view_report().')','','class="text-center align-middle w-150"');
									th(lang('nama_kantor'),'','class="text-center align-middle w-150"');
									th(lang('jadwal'),'','class="text-center align-middle w-150"');
									th(lang('status'),'','class="text-center align-middle w-150"');
									th(lang('kecamatan'),'','class="text-center align-middle w-200"');
									th(lang('penjelasan'),'','class="text-center align-middle"');
									th(lang('keterangan'),'','class="text-center align-middle w-150"');
									th(lang('warna_keterangan'),'','class="text-center align-middle"');
							tbody();
						table_close();
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
var length_cabang = $('#cabang').length;
var xhr_ajax = null;
$('button[type="submit"]').on('click',function(){
	var kode_anggaran 	= $('#filter_anggaran option:selected').val();
	var cabang 		  	= $('#cabang option:selected').val();
	var rencana 	  	= $('#rencana option:selected').val();
	var tahapan 		= $('#tahapan option:selected').val();
	var jenis_kantor 	= $('#jenis_kantor option:selected').val();
	var keterangan 		= $('#keterangan option:selected').val();
	var status_kantor 		= $('#status_kantor option:selected').val();

	var data_post = {
		kode_anggaran 	: kode_anggaran,
		length_cabang 	: length_cabang,
		cabang 			: cabang,
		rencana 		: rencana,
		tahapan 		: tahapan,
		jenis_kantor 	: jenis_kantor,
		keterangan 		: keterangan,
		status_kantor 	: status_kantor,
	};

	if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }
    cLoader.open(lang.memuat_data + '...');
    var page = base_url + 'transaction/usulan_kantor_rekap/data';
    xhr_ajax = $.ajax({
		url 	: page,
		data 	: data_post,
		type	: 'post',
		dataType: 'json',
		success	: function(response) {
			xhr_ajax = null;
			$('#result1 tbody').html(response.table);
			cLoader.close();
		}
	});

})
$('button[type="reset"]').on('click',function(){
	$('.content-body select').val(0).trigger('change');
})
var btn_filter = true;
$('#btn-filter').on('click',function(){
	if(btn_filter){
		btn_filter = false;
		$('.div-filter').hide(300);
		$('#btn-filter').html('Tampilkan Filter');
	}else{
		btn_filter = true;
		$('.div-filter').show(300);
		$('#btn-filter').html('Sembunyikan Filter');
	}
})
$('#btn-export').on('click',function(){
	var hashids = new Hashids(encode_key);
    var x = hashids.decode($('meta[name="csrf-token"]').attr('content'));

	var kode_anggaran 	= $('#filter_anggaran option:selected').val();
	var cabang 		  	= $('#cabang option:selected').val();
	var rencana 	  	= $('#rencana option:selected').val();
	var tahapan 		= $('#tahapan option:selected').val();
	var jenis_kantor 	= $('#jenis_kantor option:selected').val();
	var keterangan 		= $('#keterangan option:selected').val();
	var status_kantor 		= $('#status_kantor option:selected').val();

	var data_post = {
		kode_anggaran 	: kode_anggaran,
		length_cabang 	: length_cabang,
		cabang 			: cabang,
		rencana 		: rencana,
		tahapan 		: tahapan,
		jenis_kantor 	: jenis_kantor,
		keterangan 		: keterangan,
		status_kantor 	: status_kantor,
		export 			: true,
		"csrf_token"    : x[0],
	};
    var url = base_url + 'transaction/usulan_kantor_rekap/data';
    $.redirect(url,data_post,"","_blank");
})
</script>