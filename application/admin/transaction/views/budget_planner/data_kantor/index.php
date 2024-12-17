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
	<?php $this->load->view($path.'sub_menu'); ?>
</div>
<div class="content-body mt-6">
<?php $this->load->view($path.'sub_menu'); ?>
	<div class="main-container">
	<div class="row">
		<div class="col-sm-12 col-12">
			<form id="form-command" action="<?php echo base_url('transaction/data_kantor_budget_planner/save'); ?>" data-callback="getData" method="post" data-submit="ajax">
			<br>
			<div class="card">
	    		<div class="card-header"><?php echo lang('data_kantor'); ?></div>
				<div class="card-body">
					<div class="row">
						<div class="col-sm-9">
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
						</div>
					</div>
					<br>
					<div class="form-group row">
						<div class="col-sm-9 offset-sm-2">
							<button type="submit" class="btn btn-info">Simpan Perubahan</button>
						</div>
					</div>

				</div>
			</div>

			<div class="card mt-3 mb-3">
				<div class="card-header">BERITA ACARA</div>
				<div class="card-body">
					<div class="table-responsive tab-pane fade active show" id="result2">
						<?php
						table_open('table table-bordered table-app table-hover');
							thead();
								tr();
									th(get_view_report(),'','colspan="'.(4+6).'"');
								tr();
									th(lang('no'),'align-middle text-center');
									th(lang('keterangan'),'align-middle text-center','');
									for ($i=3; $i >= 0 ; $i--) {
										$real = '<br> (Real)'; 
										$t = ($tahun->tahun_anggaran - $i);
										if(12 != $tahun->bulan_terakhir_realisasi && $t == $tahun->tahun_terakhir_realisasi):
											th(month_lang($tahun->bulan_terakhir_realisasi).' '.($tahun->tahun_terakhir_realisasi).$real,'align-middle text-center');
										endif;
										
										$key = multidimensional_search($detail_tahun, array(
					                        'tahun' => $t,
					                        'bulan' => 12,
					                    ));
					                    if(strlen($key)>0):
					                    	$real = '<br> ('.$detail_tahun[$key]['singkatan'].')';
					                    endif;

										th(month_lang(12).' '.$t.$real,'align-middle text-center');
										if($i<3):
											th('Pert','align-middle text-center');
										endif;
									}
							tbody();
								tr();
									td('Tidak ada data','text-left','colspan="9"');
						table_close();
						?>					
					</div>
				</div>
			</div>
			</form>
		</div>	
	</div>
	</div>
</div>

<script type="text/javascript">
var xhr_ajax = null;
$(document).ready(function(){
	getData();
	loadData2()
});
$('#filter_cabang').on('change',function(){
	getData();
	loadData2();
});
function getData(){
	var kode_cabang = $('#filter_cabang option:selected').val();
	var cabang = $('#filter_cabang').val();
	if(!cabang){ return ''; }
	var page = base_url + 'transaction/data_kantor_budget_planner/get_data';
	page 	+= '/'+$('#filter_anggaran').val();
	page 	+= '/'+$('#filter_cabang').val();
		
	if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }

	if(cabang){
		cLoader.open(lang.memuat_data + '...');
		xhr_ajax = $.ajax({
			url 	: page,
			data 	: {},
			type	: 'get',
			dataType: 'json',
			success	: function(response) {
				xhr_ajax = null;
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
}

var xhr_ajax2 = null;
function loadData2(){

    if( xhr_ajax2 != null ) {
        xhr_ajax2.abort();
        xhr_ajax2 = null;
    }

    var cabang = $('#filter_cabang').val();
    if(!cabang){ return ''; }
    var page = base_url + 'transaction/data_kantor_budget_planner/data2/';
    page += '/'+ $('#filter_anggaran').val();
    page += '/'+ $('#filter_cabang').val();
  	if(cabang){
  		xhr_ajax2 = $.ajax({
	        url: page,
	        type: 'post',
			data : $('#form-filter').serialize(),
	        dataType: 'json',
	        success: function(res){
	        	xhr_ajax2 = null;
	            $('#result2 tbody').html(res.data);				
	        }
	    });
  	}
}

$('#create-berita-acara').click(function(e){
	e.preventDefault();
	$('#modal-berita-acara').modal();
});
</script>