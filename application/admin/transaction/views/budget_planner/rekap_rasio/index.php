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
    			echo '<button class="btn btn-success btn-save" href="javascript:;" > Save <span class="fa-save"></span></button>';
				$arr = [
					// ['btn-save','Save Data','fa-save'],
				    // ['btn-export','Export Data','fa-upload'],
				    // ['btn-import','Import Data','fa-download'],
				    // ['btn-template','Template Import','fa-reg-file-alt']
				];
				// echo access_button('',$arr); 
			?>
    		</div>
			<div class="clearfix"></div>
	</div>
	<?php $this->load->view($path.'sub_menu'); ?>
</div>
<div class="content-body mt-6">
<?php $this->load->view($path.'sub_menu'); ?>
	<div class="main-container mt-2">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header text-center"><?= $title ?></div>
	    			<div class="card-body">
	    				<div class="table-responsive tab-pane fade active show  height-window" id="result1">
						<?php 
							table_open('table table-bordered table-app table-hover table-1');
							thead();
								tr();
									th(get_view_report(),'','colspan="'.(2+12).'"');
								tr();
									th(lang('sandi'),'','width="60" class="text-center align-middle headcol"');
									th(lang('keterangan'),'','class="text-center align-middle headcol" style=width:auto;min-width:230px;min-height: 50px;"');

									for ($i = 1; $i <= 12; $i++) {
										$a = array_search($i, array_column($detail_tahun, 'bulan'));
										$column = month_lang($i);
										$column .= "<br> (".$detail_tahun[$a]['singkatan'].")";
										th($column,'','class="text-center" style="min-width:80px;"');
									}
									// for ($i = 1; $i <= 12; $i++) { 
									// 	th(month_lang($i),'','class="text-center" style="min-width:80px;background-color: #e64a19; color: white !important;"');		
									// }
							
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
$(document).ready(function(){
	resize_window();
	var cabang = $('#filter_cabang').val();
	if(cabang){
		loadData();
	}

});	

$('#filter_anggaran').change(function(){
	loadData();
});

$('#filter_cabang').change(function(){
	loadData();
});

var xhr_ajax = null;
function loadData(){
	$('#result1 tbody').html('');	
    if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }

    cLoader.open(lang.memuat_data + '...');
    var page = base_url + 'transaction/rekap_rasio/data/';
    page += '/'+ $('#filter_anggaran').val();
    page += '/'+ $('#filter_cabang').val();
  	xhr_ajax = $.ajax({
        url: page,
        type: 'post',
		data : $('#form-filter').serialize(),
        dataType: 'json',
        success: function(res){
        	xhr_ajax = null;
            $('#result1 tbody').html(res.table);	

            cLoader.close();
		}
    });
}
$(document).on('click','.btn-save',function(){
	var i = 0;
	$('.edited').each(function(){
		i++;
	});
	if(i == 0) {
		cAlert.open('tidak ada data yang di ubah');
	} else {
		var msg 	= lang.anda_yakin_menyetujui;
		if( i == 0) msg = lang.anda_yakin_menolak;
		cConfirm.open(msg,'save_perubahan');        
	}

});

function save_perubahan() {
	var data_edit = {};
	var i = 0;
	
	$('.edited').each(function(){
		var content = $(this).children('div');
		if(typeof data_edit[$(this).attr('data-id')] == 'undefined') {
			data_edit[$(this).attr('data-id')] = {};
		}
		data_edit[$(this).attr('data-id')][$(this).attr('data-name')] = $(this).text().replace(/[^0-9\-]/g,'');
		i++;
	});
	
	var jsonString = JSON.stringify(data_edit);	
	// var tahun_anggaran = $('#filter_anggaran option:selected').val();
	// var coa = $('#filter_coa').val();
	var page = base_url + 'transaction/rekap_rasio/save_perubahan';
    page += '/'+ $('#filter_anggaran').val();
    page += '/'+ $('#filter_cabang').val();
	$.ajax({
		url : page,
		data 	: {
			'json' : jsonString,
			verifikasi : i
		},
		type : 'post',
		success : function(response) {
			cAlert.open(response,'success','refreshData');
		}
	})
}
</script>