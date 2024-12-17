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
			<br>
			<div class="card">
	    		<div class="card-header"><?php echo "Neraca"; ?></div>
				<div class="card-body">
					<div class="row">
						<div class="col-sm-5 col-5" style="padding-right: 0px">
							<div class="table-responsive tab-pane fade active show" id="resultNeraca">
							<?php 
								table_open('',true);
								thead();
									tr();
										th(lang('sandi bi'),'','width="60" rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;"');
										th(lang('coa 5'),'','width="60" rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;"');
										th(lang('coa 7'),'','width="60" rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;"');
										th(lang('keterangan'),'','rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;display:block;width:auto;min-width:230px"');
								tbody();
									
							table_close();
							?>
						</div>
					</div>
					<div class="col-sm-7 col-7" style="padding-left: 0px">
							<div class="table-responsive tab-pane fade active show" id="resultCabangNeraca">
							<?php 
								table_open('',true);
								thead();
									tr();
										
										for ($i = 1; $i <= 12; $i++) { 
											// th('','','class="border-none bg-transparent" style="min-width:80px;"');
											th(month_lang($i),'','colspan=2 class="text-center" style="min-width:80px;background-color: #e64a19; color: white !important;"');	
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
	<div class="col-sm-12 col-12">
			<br>
			<div class="card">
	    		<div class="card-header"><?php echo "Laba Rugi"; ?></div>
				<div class="card-body">
					<div class="row">
						<div class="col-sm-5 col-5" style="padding-right: 0px">
							<div class="table-responsive tab-pane fade active show" id="resultLaba">
							<?php 
								table_open('',true);
								thead();
									tr();
										th(lang('sandi bi'),'','width="60" rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;"');
										th(lang('coa 5'),'','width="60" rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;"');
										th(lang('coa 7'),'','width="60" rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;"');
										th(lang('keterangan'),'','rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;display:block;width:auto;min-width:230px"');
								tbody();
									
							table_close();
							?>
						</div>
					</div>
					<div class="col-sm-7 col-7" style="padding-left: 0px">
							<div class="table-responsive tab-pane fade active show" id="resultCabangNeraca">
							<?php 
								table_open('',true);
								thead();
									tr();
										
										for ($i = 1; $i <= 12; $i++) { 
											// th('','','class="border-none bg-transparent" style="min-width:80px;"');
											th(month_lang($i),'','colspan=2 class="text-center" style="min-width:80px;background-color: #e64a19; color: white !important;"');	
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

</div>


	
</div>

<script type="text/javascript">
var xhr_ajax = null;
$(document).ready(function(){
	loadDataNeraca();
	loadDataLaba();
});	

$('#filter_anggaran').change(function(){
	loadDataNeraca();
	loadDataLaba();
	// loadData();
	// loadDataLaba();
});

$('#filter_cabang').change(function(){
	loadDataNeraca();
	loadDataLaba();
	// loadData();
	// loadDataLaba();
});

function loadDataLaba(){
	// cLoader.open(lang.memuat_data + '...');
	$('#resultLaba tbody').html('');	
    // if( xhr_ajax != null ) {
    //     xhr_ajax.abort();
    //     xhr_ajax = null;
    // }

    var page = base_url + 'transaction/budget_nett/getDataLaba/';
  	xhr_ajax = $.ajax({
        url: page,
        type: 'post',
		data : $('#form-filter').serialize(),
        dataType: 'json',
        success: function(res){
        	xhr_ajax = null;
            $('#resultLaba tbody').html(res.table);
            checkSubData();
            // cLoader.close();
		}
    });
}

function loadDataNeraca(){
	$('#resultNeraca tbody').html('');	
    // if( xhr_ajax != null ) {
    //     xhr_ajax.abort();
    //     xhr_ajax = null;
    // }
    cLoader.open(lang.memuat_data + '...');
    var page = base_url + 'transaction/budget_nett/getDataNeraca/';
  	xhr_ajax = $.ajax({
        url: page,
        type: 'post',
		data : $('#form-filter').serialize(),
        dataType: 'json',
        success: function(res){
        	xhr_ajax = null;
        	$('#resultNeraca tbody').append(res.table);
            checkSubData();
        	// if(res.status){
        	// 	loadMore(res.count);
        	// }else{
        		cLoader.close();
        	// }
		}
    });
}


function loadDataCabang(){
	// cLoader.open(lang.memuat_data + '...');
	$('#resultLaba tbody').html('');	
    // if( xhr_ajax != null ) {
    //     xhr_ajax.abort();
    //     xhr_ajax = null;
    // }

    var page = base_url + 'transaction/budget_nett/getDataCabang/';
  	xhr_ajax = $.ajax({
        url: page,
        type: 'post',
		data : $('#form-filter').serialize(),
        dataType: 'json',
        success: function(res){
        	xhr_ajax = null;
            $('#resultLaba tbody').html(res.table);
            checkSubData();
            // cLoader.close();
		}
    });
}

// function loadMore(count){
// 	var page = base_url + 'transaction/valas/loadMore';
//     page += '/'+ $('#filter_anggaran').val();
//     page += '/'+ $('#filter_cabang').val();
//     page += '/'+ count;
//     $.ajax({
//         url: page,
//         type: 'post',
// 		data : {count:count},
//         dataType: 'json',
//         success: function(res){
//         	xhr_ajax = null;
//         	$('#result1 tbody').append(res.view);
//         	if(res.status){
//         		loadMore(res.count);
//         	}else{
//         		cLoader.close();
//         		checkSubData();
//         	}
// 		}
//     });
// }
</script>