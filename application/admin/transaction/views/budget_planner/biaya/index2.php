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
			<select class="select2 infinity custom-select" id="filter_anggaran">
				<?php foreach ($tahun as $tahun) { ?>
                <option value="<?php echo $tahun->kode_anggaran; ?>"<?php if($tahun->kode_anggaran == user('kode_anggaran')) echo ' selected'; ?>><?php echo $tahun->keterangan; ?></option>
                <?php } ?>
			</select>		
			<label class=""><?php echo lang('cabang'); ?>  &nbsp</label>
			<select class="select2 custom-select" id="filter_cabang">
                <?php foreach($cabang as $b){ ?>
                <option value="<?php echo $b['kode_cabang']; ?>" <?php if($b['kode_cabang'] == user('kode_cabang')) echo ' selected'; ?>><?php echo $b['nama_cabang']; ?></option>
                <?php } ?>
			</select>   	
    		<?php
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
<div class="content-body m-t-column">
	<div class="main-container">
	<div class="row">
		<div class="col-sm-12 col-12">
			<br>
			<div class="card">
	    		<div class="card-header"><?php echo "Biaya"; ?></div>
				<div class="card-body">
					<div class="table-responsive tab-pane fade active show" id="result1" style="margin-top: 2%">
					<?php 
						$bulanMin = $tahun->bulan_terakhir_realisasi - 1;
						table_open('table table-bordered table-app table-hover table-1');
						thead();
							tr();
								th(lang('coa'),'','width="60" rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;"');
								th(lang('keterangan'),'','rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;display:block;width:auto;min-width:230px"');

								for ($i = 1; $i <= 12; $i++) { 
									th(month_lang($i),'','class="text-center" style="min-width:80px;background-color: #e64a19; color: white !important;"');		
								}
								th('','','class="text-center border-none" style="min-width:80px;background-color: #fff; color: white !important;"');
								th('Biaya Perbulan','','class="text-center" style="min-width:80px;background-color: #e64a19; color: white !important;"');
								th('Biaya Pertahun','','class="text-center" style="min-width:80px;background-color: #e64a19; color: white !important;"');
								th('','','class="text-center border-none" style="min-width:80px;background-color: #fff; color: white !important;"');
								th('Sistem','','class="text-center" style="min-width:80px;background-color: #e64a19; color: white !important;"');
								th('Real '.month_lang($bulanMin),'','class="text-center" style="min-width:80px;background-color: #e64a19; color: white !important;"');
								th('Real '.month_lang($tahun->bulan_terakhir_realisasi),'','class="text-center" style="min-width:80px;background-color: #e64a19; color: white !important;"');
						
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
	$('#result1 tbody').html('');	
    if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }
  	cLoader.open(lang.memuat_data + '...');
    var page = base_url + 'transaction/biaya/data/';
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
            console.log(res);
            console.log("test");
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
	$.ajax({
		url : base_url + 'transaction/biaya/save_perubahan',
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