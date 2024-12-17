<div class="content-header  page-data" data-additional="<?= $access_additional ?>" data-type="divisi">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<?php
			input('hidden',lang('user'),'user_cabang','',user('kode_cabang'));
			echo $option;
			?>
		</div>
		<div class="clearfix"></div>
	</div>
	<?php $this->load->view($sub_menu); ?>
</div>
<div class="content-body mt-6"> 
	<?php $this->load->view($sub_menu); ?>
	<div class="main-container container">
		<div class="row mt-3">
			<div class="col-sm-9">
				<h2 class="text-center">Data Kantor</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-9">
				<form id="form-command" action="<?php echo base_url('transaction/plan_data_kantor/save'); ?>" data-callback="getData" method="post" data-submit="ajax">
					<input type="hidden" id="id" name="id">
					<div class="form-group row">
						<label class="col-sm-2 col-form-label required" for="kode_cabang">Kode Cabang</label>
						<div class="col-sm-8">
							<input type="text" name="kode_cabang" id="kode_cabang" class="form-control" autocomplete="off" data-validation="required|unique" readonly>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label required" for="nama_kantor">Nama Kantor</label>
						<div class="col-sm-8">
							<input type="text" name="nama_kantor" id="nama_kantor" class="form-control" autocomplete="off" data-validation="required" readonly>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label required" for="nama_pimpinan">Pimpinan</label>
						<div class="col-sm-8">
							<input type="text" name="nama_pimpinan" id="nama_pimpinan" class="form-control" autocomplete="off" data-validation="required">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label required" for="tgl_mulai_menjabat">Mulai Menjabat</label>
						<div class="col-sm-8">
							<input type="text" name="tgl_mulai_menjabat" id="tgl_mulai_menjabat" class="form-control dp" autocomplete="off" data-validation="required">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label required" for="no_hp_cp">No Telp CP</label>
						<div class="col-sm-8">
							<input type="text" name="no_hp_cp" id="no_hp_cp" class="form-control" autocomplete="off" data-validation="required">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label required" for="email_Cp">Email Kantor</label>
						<div class="col-sm-8">
							<input type="text" name="email_Cp" id="email_Cp" class="form-control" autocomplete="off" data-validation="required|email">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label required" for="email_lainnya">Email Lainnya</label>
						<div class="col-sm-8">
							<input type="text" name="email_lainnya" id="email_lainnya" class="form-control" autocomplete="off" data-validation="email">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-sm-10 offset-sm-2">
							<button type="submit" class="btn btn-info">Simpan Perubahan</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	getData();
});
$('#filter_cabang').on('change',function(){
	getData();
});
function getData(){
	var kode_cabang = $('#filter_cabang option:selected').val();
	if(!kode_cabang){ return ''; }
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/plan_data_kantor/get_data';
	page 	+= '/'+kode_cabang;
	$.ajax({
		url 	: page,
		data 	: {},
		type	: 'get',
		dataType: 'json',
		success	: function(response) {
			cLoader.close();
			cek_autocode();
			if(response){
				v = response;
				$('#id').val(v.id);
				$('#kode_cabang').val(v.kode_cabang);
				$('#kode_cabang').val(v.kode_cabang);
				$('#nama_kantor').val(v.nama_kantor);
				$('#nama_pimpinan').val(v.nama_pimpinan);
				$('#tgl_mulai_menjabat').val(v.tgl_mulai_menjabat);
				$('#no_hp_cp').val(v.no_hp_cp);
				$('#email_Cp').val(v.email_Cp);
				$('#email_lainnya').val(v.email_lainnya);
			}else{
				$('#kode_cabang').val(kode_cabang);
			}
		}
	});
}
</script>