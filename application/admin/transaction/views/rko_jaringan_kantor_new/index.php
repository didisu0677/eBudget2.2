<style type="text/css">
.w-200{
	min-width: 200px;
}
.w-150{
	min-width: 150px;
}
</style>
<div class="content-header page-data" data-additional="<?= $access_additional ?>">
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
			
    		<?php
    		echo filter_cabang_admin($access_additional,$cabang);
			$arr = [
			    ['btn-export','Export Data','fa-upload'],
			];
			echo ' '. access_button('',$arr);
			?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body mt-6">
	<div class="main-container mt-2">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
	    			<div class="card-header text-center"><?= $title ?></div>
	    			<div class="card-body">
	    				<div class="table-responsive tab-pane fade active show height-window" id="result1" data-height="50">
	    				<?php
						$thn_sebelumnya = user('tahun_anggaran') -1;
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
</div>
<script type="text/javascript">
$(function(){
	resize_window();
	getData();
})
$('#filter_cabang').change(function(){
	getData();
});
var xhr_ajax = null;
function getData() {
	var cabang = $('#filter_cabang').val();
	if(!cabang){ return ''; }
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/rko_jaringan_kantor_new/data';
	page 	+= '/'+$('#filter_anggaran').val();
	page 	+= '/'+$('#filter_cabang').val();

	if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }

	xhr_ajax = $.ajax({
		url 	: page,
		data 	: {},
		type	: 'get',
		dataType: 'json',
		success	: function(response) {
			xhr_ajax = null;
			$('.table-app tbody').html(response.table);
			cLoader.close();
		}
	});
}
$('.btn-export').on('click',function(){
	var cabang = $('#filter_cabang').val();
	if(!cabang){ return ''; }
	var hashids = new Hashids(encode_key);
    var x = hashids.decode($('meta[name="csrf-token"]').attr('content'));
	var url = base_url + 'transaction/rko_jaringan_kantor_new/data';
	url 	+= '/'+$('#filter_anggaran').val();
	url 	+= '/'+$('#filter_cabang').val();
	data_post = {
		export : true,
		"csrf_token"    : x[0],
	}
    $.redirect(url,data_post,"","_blank");
})
</script>