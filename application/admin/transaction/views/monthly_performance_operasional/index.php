<style type="text/css">
	red{
		color:red;
	}
	.mw-100{
		min-width: 100px !important;
	}
	.mw-150{
		min-width: 200px !important;
	}
	.mw-250{
		min-width: 400px !important;
	}
	.t-sb-1{
		background-color: #cacaca;
	}
	.r-45{
		transform: rotate(45deg);
	}
	.r-45-{
		transform: rotate(-45deg);
	}
	.mt-6{
		margin-top: 5em;
	}
	.select2-selection__rendered{
		text-align: left !important;
	}
</style>
<div class="content-header page-data" style="height: auto;">
	<div class="main-container position-relative">
		<div class="header-info" style="position: relative;">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right mt-3">
			<?php
			input('hidden',lang('user'),'user_cabang','',user('kode_cabang'));
			?>
			<label class=""><?php echo lang('anggaran'); ?>  &nbsp</label>
			<select class="select2 infinity number-select" id="filter_anggaran">
				<?php foreach ($tahun as $tahun) { ?>
                <option value="<?php echo $tahun->kode_anggaran; ?>"<?php if($tahun->kode_anggaran == user('kode_anggaran')) echo ' selected'; ?>><?php echo $tahun->keterangan; ?></option>
                <?php } ?>
			</select> 		

			<label class=""><?php echo lang('cabang'); ?>  &nbsp</label>
			<select class="select2 number-select" id="filter_cabang">
				<option value="konsolidasi">KONSOLIDASI</option>
                <?php foreach($cabang as $b){
	               echo '<option value="'.$b['kode_cabang'].'">'.$b['nama_cabang'].'</option>';
                }?>
			</select>
			<label class=""><?php echo lang('bulan'); ?>  &nbsp</label>
			<select class="select2 number-select" id="filter_bulan">
				<?php
				for ($i=1; $i <=12 ; $i++) { 
					echo '<option value="'.$i.'">'.month_lang($i).'</option>';
				}
				?>
			</select>
    		<?php
                echo '<button class="btn btn-info btn-search" href="javascript:;" title="Digunakan untuk mengambil data dari server secara realtime" > '.lang('pilih').' </button>';
                echo '<button class="btn btn-info btn-refresh" href="javascript:;" title="Digunakan untuk mengambil data dari server secara realtime" > Refresh Data </button>';
				$arr = [
				    ['btn-export','Export Data','fa-upload'],
				];
				echo access_button('',$arr); 
			?>
    		</div>
			<div class="clearfix"></div>
	</div>
</div>
<div class="content-body mt-6">
	<div class="main-container">
		<div class="row div-content">
			
		</div>
		<div class="row">
			<div class="col-sm-12 col-12 mt-3 mb-3">
				<div class="card">
					<div class="card-header text-center"><?= lang('keterangan') ?></div>
					<div class="card-body">
						<div class="row">
						<?php
							foreach ($nilai as $k => $v) {
								echo '<div class="col-sm-2">
									<span class="color" style="background-color:'.$v['warna'].'"></span> 
									<b>" '.$v['nama'].' "</b> '.$v['keterangan'].'
								</div>';
							}
						?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>
<script type="text/javascript">
var controller = '<?= $controller ?>';
$('.btn-search').click(function(){
	var cabang 	 = $('#filter_cabang option:selected').val();
	var bulan 	 = $('#filter_bulan option:selected').val();
	var classnya = 'd-'+cabang+'-'+bulan;
	var length = $('.div-content').find('#'+classnya).length;
	if(length>0){
		cLoader.open(lang.memuat_data + '...');
		$('.div-content').find('.d-content').hide();
		$('.div-content').find('#'+classnya).show();
		cLoader.close();
	}else{
		getData();
	}
});
$('.btn-refresh').click(function(){
	getData();
});
var xhr_ajax = null; 
function getData(){
	cLoader.open(lang.memuat_data + '...');
	var cabang 	 = $('#filter_cabang option:selected').val();
	var bulan 	 = $('#filter_bulan option:selected').val();
	var tahun 	 = $('#filter_anggaran option:selected').val();

	if(!cabang){
		return '';
	}

	var classnya = 'd-'+cabang+'-'+bulan;
	var page 	 = base_url + 'transaction/'+controller+'/data/'+tahun+'/'+cabang+'/'+bulan;
	if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }
    $('.div-content').find('#'+classnya).remove();
    $('.div-content').find('.d-content').hide();
	xhr_ajax = $.ajax({
		url 	: page,
		type	: 'post',
		dataType: 'json',
		success	: function(response) {
			xhr_ajax = null;
			cLoader.close();
			$('.div-content').append(response.view);
			checkSubData2(classnya);
			resize_window();
		}
	});
}
function checkSubData2(classnya){
	for (var i = 1; i <= 6; i++) {
		if($(document).find('#'+classnya+' .sb-'+i).length>0){
			var dt = $(document).find('.sb-'+i);
			$.each(dt,function(k,v){
				var text = $(v).html();
				text = text.replaceAll('|-----', "");
				$(v).html('|----- '+text);
			})
		}
	}
}
</script>