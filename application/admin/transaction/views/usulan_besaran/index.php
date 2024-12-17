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
			    ['btn-export','Export Data','fa-upload'],
			];
			echo access_button('',$arr);
			?>
    		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body mt-6">
	<div class="main-container mt-2 div-content">

	</div>
</div>
<script type="text/javascript">
var controller = '<?= $controller ?>';
$(function(){
	getData();
})
$('#filter_tahun').change(function(){
	getData();
});

$('#filter_cabang').change(function(){
	getData();
});

var xhr_ajax = null;
function getData(){
	var cabang = $('#filter_cabang').val();
	if(!cabang){
		return '';
	}

	if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }

    $('.div-content').html();
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/'+controller+'/data';
	page 	+= '/'+$('#filter_anggaran').val();
	page 	+= '/'+$('#filter_cabang').val();
	xhr_ajax = $.ajax({
		url 	: page,
		type	: 'get',
		dataType: 'json',
		success	: function(response) {
			$('.div-content').html(response.view);
			xhr_ajax = null;
			cLoader.close();
			money_init();
			if(response.access_edit){
				$('.btn-save').show();
			}else{
				$('.btn-save').hide();
			}
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
	var page = base_url + 'transaction/'+controller+'/save_perubahan';
	page 	+= '/'+$('#filter_anggaran').val();
	page 	+= '/'+$('#filter_cabang').val();
	$.ajax({
		url : page,
		data 	: {
			'json' : jsonString,
			verifikasi : i,
		},
		type : 'post',
		success : function(response) {
			cAlert.open(response,'success','getData');
		}
	})
}
</script>