<style type="text/css">
	.wd-100{
		width: 100px !important;
		min-width: 100px !important;
		max-width: 100px !important;
	}
	.wd-150{
		width: 150px !important;
		min-width: 150px !important;
		max-width: 150px !important;
	}
	.wd-230{
		width: 350px !important;
		min-width: 350px !important;
		max-width: 350px !important;
	}
	.d-bg-header th{
		background-color: #e64a19 !important;
	}
	.d-bg-header span{
		color: #fff !important;
	}
	.d-bg-header red{
		color: #e64a19 !important;
	}
</style>
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
			<label class=""><?php echo lang('anggaran'); ?>  &nbsp</label>
			<select class="select2 infinity number-select" id="filter_anggaran">
				<?php foreach ($tahun as $tahun) { ?>
                <option value="<?php echo $tahun->kode_anggaran; ?>"<?php if($tahun->kode_anggaran == user('kode_anggaran')) echo ' selected'; ?>><?php echo $tahun->keterangan; ?></option>
                <?php } ?>
			</select> 		

			<label class=""><?php echo lang('cabang'); ?>  &nbsp</label>
			<select class="select2 number-select" id="filter_cabang">

                <?php foreach($cabang as $b){
	            	if($b['level_cabang'] != '1'){
	             ?>

                <option value="<?php echo $b['kode_cabang']; ?>" <?php if($b['kode_cabang'] == user('kode_cabang')) echo ' selected'; ?>><?php echo $b['nama_cabang']; ?></option>

                <?php } }?>

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
</div>
<div class="content-body">
	<div class="main-container">
		<div class="row">
			<div class="col-sm-12 col-12">
				<div class="card">
		    		<div class="card-header"><?php echo "Neraca"; ?></div>
					<div class="card-body">
						<div class="ContenedorTabla" id="neraca">
							<?php
							// echo '<pre>';
							// echo print_r($neraca['coa0']['1000000']);
							// echo '</pre>';
							table_open('',false);
								thead();
									tr('d-cabang-neraca d-bg-header');
										th('<red>-</red>','','colspan="4"');
										th('<red>-</red>','d-head','colspan="12"');
									tr('d-neraca d-bg-header');
										th('<span>'.lang('sandi bi').'</span>','','width="60"  class="text-center align-middle wd-100"');
										th('<span>'.lang('coa 5').'</span>','','width="60"  class="text-center align-middle wd-100"');
										th('<span>'.lang('coa 7').'</span>','','width="60"  class="text-center align-middle wd-100"');
										th('<span>'.lang('keterangan').'</span>','',' class="text-center align-middle wd-230"');
										for ($i=1; $i <=12 ; $i++) { 
											th('<red>-</red>','d-head','');
										}
								tbody();
							table_close();
							?>
						</div>
					</div>	
				</div>
			</div>
			<div class="col-sm-12 col-12">
				<br>
				<br>
				<div class="card">
		    		<div class="card-header"><?php echo "laba Rugi"; ?></div>
					<div class="card-body">
						<div class="ContenedorTabla" id="labarugi">
							<?php
							// echo '<pre>';
							// echo print_r($neraca['coa0']['1000000']);
							// echo '</pre>';
							table_open('',false);
								thead();
									tr('d-cabang-labarugi d-bg-header');
										th('<red>-</red>','','colspan="4"');
										th('<red>-</red>','d-head','colspan="12"');
									tr('d-labarugi d-bg-header');
										th('<span>'.lang('Kode').'</span>','','width="60"  class="text-center align-middle wd-100"');
										th('<span>'.lang('Keterangan').'</span>','','width="60"  class="text-center align-middle wd-100"');
										th('<span>'.lang('coa 7').'</span>','','width="60"  class="text-center align-middle wd-100"');
										th('<span>'.lang('keterangan').'</span>','',' class="text-center align-middle wd-230"');
										for ($i=1; $i <=12 ; $i++) { 
											th('<red>-</red>','d-head','');
										}
								tbody();
							table_close();
							?>
						</div>
					</div>	
				</div>
			</div>
			<div class="col-sm-12 col-12">
				<br>
				<br>
				<div class="card">
		    		<div class="card-header"><?php echo "Rekap Rasio"; ?></div>
					<div class="card-body">
						<div class="ContenedorTabla" id="rekaprasio">
							<?php
							// echo '<pre>';
							// echo print_r($neraca['coa0']['1000000']);
							// echo '</pre>';
							table_open('',false);
								thead();
									tr('d-cabang-rekaprasio d-bg-header');
										th('<red>-</red>','','colspan="2"');
										th('<red>-</red>','d-head','colspan="12"');
									tr('d-rekaprasio d-bg-header');
										th('<span>Kode</span>','','width="60"  class="text-center align-middle wd-100"');
										th('<span>keterangan</span>','','width="60"  class="text-center align-middle wd-230"');
										for ($i=1; $i <=12 ; $i++) { 
											th('<red>-</red>','d-head','');
										}
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
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/freeze_table/').'ScrollTabla.css' ?>">
<script type="text/javascript" src="<?= base_url('assets/plugins/freeze_table/').'jquery.CongelarFilaColumna.js' ?>"></script>
<script type="text/javascript">
// $("#neraca .table-app").CongelarFilaColumna({Columnas:4,coloreacelda:false,colorcelda:'#5882fa0a'});
// var xhr_ajax = null;
$(document).ready(function(){
	loadColumnNeraca('neraca');
	loadColumnNeraca('labarugi');
	loadColumnRekap();
});
$('#filter_anggaran').change(function(){
	loadColumnNeraca('neraca');
	loadColumnNeraca('labarugi');
	loadColumnRekap();
	// loadColumnNeraca('neraca');
	// loadColumnNeraca('labarugi');
});

$('#filter_cabang').change(function(){
	loadColumnNeraca('neraca');
	loadColumnNeraca('labarugi');
	loadColumnRekap();
	// loadColumnNeraca('neraca');
	// loadColumnNeraca('labarugi');
});
function loadColumnNeraca(p1){
	$('#'+p1+' tbody').html('');	
    // if( xhr_ajax != null ) {
    //     xhr_ajax.abort();
    //     xhr_ajax = null;
    // }
    cLoader.open(lang.memuat_data + '...');
    var page = base_url + 'transaction/budget_nett/neraca_column';
    page += '/'+ $('#filter_anggaran').val();
    page += '/'+ $('#filter_cabang').val();
    page += '/'+ p1;
  	xhr_ajax = $.ajax({
        url: page,
        type: 'post',
		data : $('#form-filter').serialize(),
        dataType: 'json',
        success: function(res){
        	xhr_ajax = null;
        	if(res.status){
        		$('#'+p1+' .d-head').remove();
        		$('#'+p1+' .d-cabang-'+p1).append(res.cabang);
        		$('#'+p1+' .d-'+p1).append(res.month);
        		$('#'+p1+' tbody').append(res.view);
        		cLoader.close();
        		loadMore(p1,0);
        	}else{
        		cAlert.open(res.message);
        		cLoader.close();
        	}
        	checkSubData();
		}
    });
}


var xhr_ajax3 = null;
function loadColumnRekap(){
	$('#rekaprasio tbody').html('');	
    if( xhr_ajax3 != null ) {
        xhr_ajax3.abort();
        xhr_ajax3 = null;
    }
    cLoader.open(lang.memuat_data + '...');
    var page = base_url + 'transaction/budget_nett/rekaprasio_column';
    page += '/'+ $('#filter_anggaran').val();
    page += '/'+ $('#filter_cabang').val();
    // page += '/'+ p1;
  	xhr_ajax = $.ajax({
        url: page,
        type: 'post',
		data : $('#form-filter').serialize(),
        dataType: 'json',
        success: function(res){
        	xhr_ajax = null;
        	if(res.status){
        		$('#rekaprasio .d-head').remove();
        		$('#rekaprasio .d-cabang-rekaprasio').append(res.cabang);
        		$('#rekaprasio .d-rekaprasio').append(res.month);
        		$('#rekaprasio tbody').append(res.view);
        		cLoader.close();
        		loadMoreRekap(0);
        	}else{
        		cAlert.open(res.message);
        		cLoader.close();
        	}
        	checkSubData();
		}
    });
}
var xhr_ajax2 = null;
function loadMore(p1,count){
	if( xhr_ajax2 != null ) {
        xhr_ajax2.abort();
        xhr_ajax2 = null;
    }
    cLoader.open(lang.memuat_data + '...');
    var page = base_url + 'transaction/budget_nett/load_more';
  	xhr_ajax = $.ajax({
        url: page,
        type: 'post',
		data : {page:p1,count:count},
        dataType: 'json',
        success: function(res){
        	xhr_ajax2 = null;
        	if(res.status){
        		$.each(res.view,function(k,v){
        			$(res.classnya).find(k).append(v);
        		});
        		cLoader.close();
        		loadMore(p1,res.count);
        	}else{
        		cLoader.close();
        	}
        	
		}
    });
}


var xhr_ajax4 = null;
function loadMoreRekap(count){
	if( xhr_ajax4 != null ) {
        xhr_ajax4.abort();
        xhr_ajax4 = null;
    }
    cLoader.open(lang.memuat_data + '...');
    var page = base_url + 'transaction/budget_nett/load_more_rekap';
  	xhr_ajax = $.ajax({
        url: page,
        type: 'post',
		data : {count:count},
        dataType: 'json',
        success: function(res){
        	xhr_ajax2 = null;
        	if(res.status){
        		$.each(res.view,function(k,v){
        			$(res.classnya).find(k).append(v);
        		});
        		cLoader.close();
        		loadMoreRekap(res.count);
        	}else{
        		cLoader.close();
        	}
        	
		}
    });
}
</script>