<div class="content-header page-data" data-additional="<?= $access_additional ?>">
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
				echo access_button('',$arr); 
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
	    				<div class="table-responsive tab-pane fade active show">
							<?php
							table_open('table table-bordered table-app table-1');
								thead();
									tr();
										th('Coa','','class="text-center align-middle" style="width:auto;min-width:60px"');
										th('keterangan','','class="text-center align-middle" style="width:auto;min-width:330px"');

										for ($i = $realisasi['bulan_terakhir_realisasi'] -1; $i <= $realisasi['bulan_terakhir_realisasi']; $i++) { 
											$x = 'Real';
											$column = month_lang($i).' '.$realisasi['tahun_terakhir_realisasi'];
											$column .= '<br> ('.$x.')';
											th($column,'','class="text-center" style="min-width:150px"');					
										}
												
										foreach ($detail_tahun as $v) {
											$column = month_lang($v->bulan).' '.$v->tahun;
											$column .= '<br> ('.$v->singkatan.')';
											th($column,'','class="text-center" style="min-width:150px"');
										};
								
								tbody();
							table_close();
							?>
						</div>
	    			</div>
	    		</div>
	    	</div>
	    </div>
	</div>

	<div class="main-container">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
	    			<div class="card-body">
	    				<div class="table-responsive tab-pane fade active show height-window" id="result1">
	    					<?php
							table_open('table table-bordered table-app table-2');
								thead();
									tr();
										th('Coa','','class="text-center align-middle" style="width:auto;min-width:60px"');
										th('keterangan','','class="text-center align-middle" style="width:auto;min-width:330px"');

										foreach ($detail_tahun as $v) {
											$column = month_lang($v->bulan).' '.$v->tahun;
											$column .= '<br> ('.$v->singkatan.')';
											th($column,'','class="text-center" style="min-width:150px"');
										}

									//	th('','','class="text-center border-none" style="min-width:20px"');
									//	th('SEP','','class="text-center" style="min-width:150px"');
									//	th('AGU','','class="text-center" style="min-width:150px"');
								
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
	loadData();

});	

$('#filter_anggaran').change(function(){
	loadData();
});

$('#filter_cabang').change(function(){
	loadData();
});
var xhr_ajax = null;
function loadData(){
	var cabang = $('#filter_cabang').val();
	if(!cabang) return '';
	$('.table-1 tbody').html('');	
	$('.table-2 tbody').html('');	
    if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }
	cLoader.open(lang.memuat_data + '...');
    var page = base_url + 'transaction/formula_kredit/data/';
        page += '/'+ $('#filter_anggaran').val();
    	page += '/'+ $('#filter_cabang').val();
  	xhr_ajax = $.ajax({
        url: page,
        type: 'post',
		data : $('#form-filter').serialize(),
        dataType: 'json',
        success: function(res){
        	xhr_ajax = null;
            $('.table-1 tbody').html(res.table);
            $('.table-2 tbody').html(res.table2);
            cLoader.close();
		}
    });
}

$(document).on('focus','.edit-value',function(){
	$(this).parent().removeClass('edited');
});
$(document).on('blur','.edit-value',function(){
	var tr = $(this).closest('tr');
	if($(this).text() != $(this).attr('data-value')) {
		$(this).addClass('edited');
	}
	if(tr.find('td.edited').length > 0) {
		tr.addClass('edited-row');
	} else {
		tr.removeClass('edited-row');
	}
});


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
	var page = base_url + 'transaction/formula_kredit/save_perubahan';
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