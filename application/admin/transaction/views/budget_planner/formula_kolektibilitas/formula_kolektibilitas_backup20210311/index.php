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
    			if($akses_ubah == 1):
    				echo '<button class="btn btn-success btn-save" href="javascript:;" > Save <span class="fa-save"></span></button>';
    			endif;
			?>
    		</div>
			<div class="clearfix"></div>
	</div>
	<?php $this->load->view($path.'sub_menu'); ?>
</div>
<div class="content-body">
	<?php $this->load->view($path.'sub_menu'); ?>
	<div class="main-container">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
		    		<div class="card-header"><?php echo lang('krd_produktif'); ?></div>
		    		<div class="card-body">
		    			<div class="table-responsive tab-pane fade active show">
						<?php
							table_open('table table-striped table-bordered table-app table-hover table-1');
								thead();
									tr();
										th('Coa','','class="text-center align-middle" style="width:auto;min-width:60px"');
										th('keterangan','','class="text-center align-middle" style="width:auto;min-width:330px"');
										th('tarif (%)','','class="text-center align-middle" style="width:auto;min-width:100px"');

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
			<div class="col-sm-12 mt-3">
				<div class="card">
		    		<div class="card-header"><?php echo lang('krd_konsumtif'); ?></div>
		    		<div class="card-body">
		    			<div class="table-responsive tab-pane fade active show">
						<?php
							table_open('table table-striped table-bordered table-app table-hover table-2');
								thead();
									tr();
										th('Coa','','class="text-center align-middle" style="width:auto;min-width:60px"');
										th('keterangan','','class="text-center align-middle" style="width:auto;min-width:330px"');
										th('tarif (%)','','class="text-center align-middle" style="width:auto;min-width:100px"');

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
			<div class="col-sm-12 mt-3">
				<div class="card">
		    		<div class="card-header">Total Kredit Kolektibilitas</div>
		    		<div class="card-body">
		    			<div class="table-responsive tab-pane fade active show">
						<?php
							table_open('table table-striped table-bordered table-app table-hover table-3');
								thead();
									tr();
										th('keterangan','','class="text-center align-middle" style="width:auto;min-width:330px"');
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
			<div class="col-sm-12 mt-3">
				<div class="card">
		    		<div class="card-header"><?= lang('perhitungan_ckpn') ?></div>
		    		<div class="card-body">
		    			<div class="table-responsive tab-pane fade active show">
						<?php
							$x = explode('-', $arr_real[0]);
							$bln1 = bulan($x[0]);
							$x2 = explode('-', $arr_real[1]);
							$bln2 = bulan($x2[0]);
							table_open('table table-striped table-bordered table-app table-hover table-4');
								thead();
									tr();
										th('Coa','','class="text-center align-middle" style="width:auto;min-width:60px"');
										th('keterangan','','class="text-center align-middle" style="width:auto;min-width:330px"');
										th($bln1.' '.$x[1].'<br> (Real)','','class="text-center align-middle" style="width:auto;min-width:150px"');
										th($bln2.' '.$x2[1].'<br> (Real)','','class="text-center align-middle" style="min-width:150px"');
										foreach ($detail_tahun as $v) {
											$column = month_lang($v->bulan).' '.$v->tahun;
											$column .= '<br> ('.$v->singkatan.')';
											th($column,'','class="text-center align-middle" style="min-width:150px"');
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
	cLoader.open(lang.memuat_data + '...');
	$('.table-1 tbody').html('');	
	$('.table-2 tbody').html('');	
	$('.table-3 tbody').html('');	
	$('.table-4 tbody').html('');	
    if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }

    var page = base_url + 'transaction/formula_kolektibilitas/data/';
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
            $('.table-3 tbody').html(res.table3);
            $('.table-4 tbody').html(res.table4);
            cLoader.close();
            loadData2();
		}
    });
}

function loadData2(){
	cLoader.open(lang.memuat_data + '...');
	if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }

    $('.table-4 tbody').html('');
    var page = base_url + 'transaction/formula_kolektibilitas/data2';
    page += '/'+ $('#filter_anggaran').val();
    page += '/'+ $('#filter_cabang').val();
  	xhr_ajax = $.ajax({
        url: page,
        type: 'post',
		data : $('#form-filter').serialize(),
        dataType: 'json',
        success: function(res){
        	xhr_ajax = null;
            if(res.status){
            	$('.table-4 tbody').html(res.table);
            }else{
            	cAlert.open(res.message);
            }
            cLoader.close();
		}
    });
}
$(document).on('dblclick','.table-app tbody td .badge',function(){
	if($(this).closest('tr').find('.btn-input').length == 1) {
		var badge_status 	= '0';
		var data_id 		= $(this).closest('tr').find('.btn-input').attr('data-id');
		if( $(this).hasClass('badge-danger') ) {
			badge_status = '1';
		}
		active_inactive(data_id,badge_status);
	}
});


$(document).on('focus','.edit-value',function(){
	$(this).parent().removeClass('edited');
	var val = $(this).text();
	var minus = val.includes("(");
	if(minus){
		val = val.replace('(','');
		val = val.replace(')','');
		$(this).text('-'+val);
	}
	console.log(minus); 
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
	var val = $(this).text();
	var minus = val.includes("-");
	if(minus){
		val = val.replace('-','');
		$(this).text('('+val+')');
	}
});
$(document).on('keyup','.edit-value',function(e){
	var n = $(this).text();
	n = formatCurrency(n,'',2);
    $(this).text(n.toLocaleString());
    var selection = window.getSelection();
	var range = document.createRange();
	selection.removeAllRanges();
	range.selectNodeContents($(this)[0]);
	range.collapse(false);
	selection.addRange(range);
	$(this)[0].focus();
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
		data_edit[$(this).attr('data-id')][$(this).attr('data-name')] = $(this).text();
		i++;
	});
	
	var jsonString = JSON.stringify(data_edit);
	$.ajax({
		url : base_url + 'transaction/formula_kolektibilitas/save_perubahan',
		data 	: {
			'json' : jsonString,
			verifikasi : i,
			'kode_anggaran' : $('#filter_anggaran option:selected').val(),
		},
		type : 'post',
		success : function(response) {
			cAlert.open(response,'success','loadData2');
		}
	})
}
function formatCurrency(angka, prefix,decimal){
	min_txt     = angka.split("-");
    str_min_txt = '';
	var number_string = angka.replace(/[^,\d]/g, '').toString(),
	split   		= number_string.split(','),
	sisa     		= split[0].length % 3,
	rupiah     		= split[0].substr(0, sisa),
	ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

	// tambahkan titik jika yang di input sudah menjadi angka ribuan
	if(ribuan){
		separator = sisa ? '.' : '';
		rupiah += separator + ribuan.join('.');
	}
	if(split[1] != undefined && split[1].toString().length > decimal){
		console.log(split[1].toString().length);
		split[1] = split[1].substr(0,decimal);
	}
	if(min_txt.length == 2){
      str_min_txt = "-";
    }
	rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	// return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
	return str_min_txt+rupiah;
}
</script>