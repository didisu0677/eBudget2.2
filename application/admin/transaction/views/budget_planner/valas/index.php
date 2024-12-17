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
<div class="content-body">
<?php $this->load->view($path.'sub_menu'); ?>
	<div class="main-container">
	<div class="row">
	<div class="col-sm-12 col-12">
			<br>
			<div class="card">
	    		<div class="card-header"><?php echo "Laba Rugi"; ?></div>
				<div class="card-body">
					<div class="table-responsive tab-pane fade active show" id="result2">
					<?php 
						table_open('',true);
						thead();
							tr();
								th(lang('sandi bi'),'','width="60" rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;"');
								th(lang('coa 5'),'','width="60" rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;"');
								th(lang('coa 7'),'','width="60" rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;"');
								th(lang('keterangan'),'','rowspan="2" class="text-center align-middle headcol" style="background-color: #e64a19; color: white !important;display:block;width:auto;min-width:230px"');

								for ($i = 1; $i <= 12; $i++) { 
									th(month_lang($i),'','class="text-center" style="min-width:80px;background-color: #e64a19; color: white !important;"');		
								}
								th('','','class="border-none bg-transparent" style="min-width:80px;"');
								th('Real '.$bulan_terakhir);
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
var xhr_ajax = null;
$(document).ready(function(){
	// loadData();
	loadDataLaba();
});	

$('#filter_anggaran').change(function(){
	// loadData();
	loadDataLaba();
});

$('#filter_cabang').change(function(){
	// loadData();
	loadDataLaba();
});

function loadDataLaba(){
	// cLoader.open(lang.memuat_data + '...');
	$('#result2 tbody').html('');	
    // if( xhr_ajax != null ) {
    //     xhr_ajax.abort();
    //     xhr_ajax = null;
    // }

    cLoader.open(lang.memuat_data + '...');
    var page = base_url + 'transaction/valas_laba_rugi/dataLaba/';
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
            checkSubData();
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

$(document).on('keyup','.edit-value',function(e){
	var wh 			= e.which;
	if((48 <= wh && wh <= 57) || (96 <= wh && wh <= 105) || wh == 8) {
		if($(this).text() == '') {
			$(this).text('');
		} else {
			var n = parseInt($(this).text().replace(/[^0-9\-]/g,''),10);
		    $(this).text(n.toLocaleString());
		    var selection = window.getSelection();
			var range = document.createRange();
			selection.removeAllRanges();
			range.selectNodeContents($(this)[0]);
			range.collapse(false);
			selection.addRange(range);
			$(this)[0].focus();
		}
	}
});
$(document).on('keypress','.edit-value',function(e){
	var wh 			= e.which;
	if (e.shiftKey) {
		if(wh == 0) return true;
	}
	if(e.metaKey || e.ctrlKey) {
		if(wh == 86 || wh == 118) {
			$(this)[0].onchange = function(){
				$(this)[0].innerHTML = $(this)[0].innerHTML.replace(/[^0-9\-]/g, '');
			}
		}
		return true;
	}
	if(wh == 0 || wh == 8 || wh == 45 || (48 <= wh && wh <= 57) || (96 <= wh && wh <= 105)) 
		return true;
	return false;
});


function save_perubahan() {
	var data_edit = {};
	var i = 0;
	
	$('.edited').each(function(){
		var content = $(this).children('div');
		if(typeof data_edit[$(this).attr('data-id')] == 'undefined') {
			data_edit[$(this).attr('data-id')] = {};
		}
		var split = $(this).attr('data-id').split("|");

		if(split[1] > 0){
			data_edit[$(this).attr('data-id')][$(this).attr('data-name')] = $(this).text().replace(/[^0-9\-]/g,'') * -1;
		}else {
			data_edit[$(this).attr('data-id')][$(this).attr('data-name')] = $(this).text().replace(/[^0-9\-]/g,'');
		}
		
		i++;
	});
	
	var jsonString = JSON.stringify(data_edit);	
	// var tahun_anggaran = $('#filter_anggaran option:selected').val();
	// var coa = $('#filter_coa').val();
	var page = base_url + 'transaction/valas_laba_rugi/save_perubahan';
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