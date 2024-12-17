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
		    		<div class="card-header"><?php echo "Formula Aktiva Inv"; ?></div>
		    		<div class="card-body">
		    			<div class="table-responsive tab-pane fade active show">
							<div class="table-responsive tab-pane fade active show height-window" id="result1" data-height="10">
						<?php
						// $this->load->view($sub_menu);

						table_open('table table-bordered table-app table-1');
							thead();
								// tr();
								// 	th(get_view_report(1),'','width="60" colspan="'.(count($detail_tahun)+4).'" class="text-left"');
								tr();
									th('Coa','','class="text-center align-middle" style="width:auto;min-width:60px"');
									th('keterangan','','class="text-center align-middle" style="width:auto;min-width:330px"');
									$column1 = month_lang($tahun->bulan_terakhir_realisasi -1 ).' '.$tahun->tahun_terakhir_realisasi;
										$column1 .= '<br> (Real)';
									th($column1,'','class="text-center" style="min-width:150px"');
									$column2 = month_lang($tahun->bulan_terakhir_realisasi).' '.$tahun->tahun_terakhir_realisasi;
										$column2 .= '<br> (Real)';
									th($column2,'','class="text-center" style="min-width:150px"');
									foreach ($detail_tahun as $v) {
										$column = month_lang($v->bulan).' '.$v->tahun;
										$column .= '<br> ('.$v->singkatan.')';
										th($column,'','class="text-center" style="min-width:150px"');
									}
							
							tbody();
						table_close();
						?>
						</div>
		    		</div>
				</div>
			</div>
			<div class="card" style="margin-top: 20px">
		    		<div class="card-header"><?php echo "Formula Aset Sewa"; ?></div>
		    		<div class="card-body">
		    			<div class="table-responsive tab-pane fade active show">
							<div class="table-responsive tab-pane fade active show height-window" id="result2" data-height="10">
						<?php
						// $this->load->view($sub_menu);

						table_open('table table-bordered table-app table-2');
							thead();
								// tr();
								// 	th(get_view_report(1),'','width="60" colspan="'.(count($detail_tahun)+4).'" class="text-left"');
								tr();
									th('Coa','','class="text-center align-middle" style="width:auto;min-width:60px"');
									th('keterangan','','class="text-center align-middle" style="width:auto;min-width:330px"');
									$column1 = month_lang($tahun->bulan_terakhir_realisasi -1 ).' '.$tahun->tahun_terakhir_realisasi;
										$column1 .= '<br> (Real)';
									th($column1,'','class="text-center" style="min-width:150px"');
									$column2 = month_lang($tahun->bulan_terakhir_realisasi).' '.$tahun->tahun_terakhir_realisasi;
										$column2 .= '<br> (Real)';
									th($column2,'','class="text-center" style="min-width:150px"');
									foreach ($detail_tahun as $v) {
										$column = month_lang($v->bulan).' '.$v->tahun;
										$column .= '<br> ('.$v->singkatan.')';
										th($column,'','class="text-center" style="min-width:150px"');
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


$(document).on('focus','.edit-value',function(){
	$(this).parent().removeClass('edited');
});
$(document).on('blur','.edit-value',function(){
	var tr = $(this).closest('tr');
	var val = Math.round($(this).attr('data-value'));
	var txtInput = '';
	$.each($(this).text().split('.'),function(k,v){
		txtInput += v;
	});

	var minus = txtInput.includes("(");
	if(minus){
		txtInput = txtInput.replace('(','');
		txtInput = txtInput.replace(')','');
		txtInput = '-'+txtInput;
	}

	console.log('val '+val);
	console.log('text '+txtInput);

	if(txtInput != val) {
		$(this).addClass('edited');
	}else{
		$(this).removeClass('edited');
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



var xhr_ajax = null;
function loadData(){
	cLoader.open(lang.memuat_data + '...');
	$('#result1 tbody').html('');	
    if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }

    var page = base_url + 'transaction/formula_aktiva_inv/data/';
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
            loadData2();
		}
    });
}


var xhr_ajax2 = null;
function loadData2(){
	cLoader.open(lang.memuat_data + '...');
	$('#result2 tbody').html('');	
    if( xhr_ajax2 != null ) {
        xhr_ajax2.abort();
        xhr_ajax2 = null;
    }

    var page = base_url + 'transaction/formula_aktiva_inv/dataSewa/';
    page += '/'+ $('#filter_anggaran').val();
    page += '/'+ $('#filter_cabang').val();
  	xhr_ajax = $.ajax({
        url: page,
        type: 'post',
		data : $('#form-filter').serialize(),
        dataType: 'json',
        success: function(res){
        	xhr_ajax = null;
            $('#result2 tbody').html(res.table);
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
		data_edit[$(this).attr('data-id')][$(this).attr('data-name')] = $(this).text();
		i++;
	});
	
	var jsonString = JSON.stringify(data_edit);		
	var page = base_url + 'transaction/formula_aktiva_inv/save_perubahan';
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

$(document).on('click','.btn-remove',function(){
	var dt_id = $(this).attr('data-id');
	if(dt_id){
		del_id 		= dt_id+"-"+$('#filter_cabang option:selected').val();
		urlDelete 	= base_url+"transaction/formula_aktiva_inv/delete";
		cConfirm.open(lang.anda_yakin_menghapus_data_ini + '?','deleteData');
	}
})
</script>