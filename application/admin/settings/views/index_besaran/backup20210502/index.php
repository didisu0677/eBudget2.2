<style type="text/css">
	.bg-c1{
		background-color: #ababab;
	}
	.bg-c2{
		background-color: #d0d0d0;
	}
	.bg-c3{
		background-color: #f5f5f5;
	}
</style>
<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		
		<div class="float-right">
			<label class=""><?php echo lang('anggaran'); ?>  &nbsp</label>

			<select class="select2 infinity custom-select" id="filter_anggaran">
				<?php foreach ($tahun as $tahun) { ?>
                <option value="<?php echo $tahun->kode_anggaran; ?>"<?php if($tahun->kode_anggaran == user('kode_anggaran')) echo ' selected'; ?>><?php echo $tahun->keterangan; ?></option>
                <?php } ?>
			</select>					
			               		
			<label class="">&nbsp <?php echo lang('coa'); ?>  &nbsp</label>

			<select class="select2 custom-select" id="filter_coa">
				<?php foreach ($coa as $v) {
					echo '<option value="'.$v->glwnco.'">'.trim($v->glwdes).'</option>';
				} ?>
			</select>

			<button class="btn btn-success btn-save" href="javascript:;" > Save <span class="fa-save"></span></button>
			
    		<?php 
				$arr = [];
					$arr = [
					    ['btn-export','Export Data','fa-upload'],
					];
				echo access_button('',$arr); 
			?>
		</div>
		<div class="clearfix"></div>	
	</div>
	<?php $this->load->view($sub_menu); ?>
</div>
<div class="content-body">
	<?php
	$this->load->view($sub_menu);
	table_open('table table-bordered table-app table-1');
		thead();
			tr();
				th(get_view_report(1),'','width="60" colspan="'.(count($detail_tahun)+1).'" class="text-left"');
			tr();
				th(lang('cabang'),'','class="text-center align-middle" style="width:auto;min-width:330px"');

				foreach ($detail_tahun as $v) {
					$column = month_lang($v->bulan).' '.$v->tahun;
					$column .= '<br> ('.$v->singkatan.')';
					th($column,'','class="text-center" style="min-width:150px"');
				}
		
		tbody();
	table_close();
	?>
</div>

<script type="text/javascript">
$( document ).ready(function() {
    getData();
});
$('#filter_coa').on('change',function(){
	getData();
});
$(document).on('click','.btn-export',function(){
	var currentdate = new Date(); 
	var datetime = currentdate.getDate() + "/"
	                + (currentdate.getMonth()+1)  + "/" 
	                + currentdate.getFullYear() + " @ "  
	                + currentdate.getHours() + ":"  
	                + currentdate.getMinutes() + ":" 
	                + currentdate.getSeconds();
	
	$('.bg-c1').each(function(){
		$(this).attr('bgcolor','#ababab');
	});
	$('.bg-c2').each(function(){
		$(this).attr('bgcolor','#d0d0d0');
	});
	$('.bg-c3').each(function(){
		$(this).attr('bgcolor','#f5f5f5');
	});
	var table	= '';
	table += '<table border="1">';
	table += $('.content-body').html();
	table += '</table>';
	var target = table;
	// window.open('data:application/vnd.ms-excel,' + encodeURIComponent(target));
	let file = new Blob([target], {type:"application/vnd.ms-excel"});
	let url = URL.createObjectURL(file);
	let a = $("<a />", {
	  href: url,
	  download: "rekap-usulan-besaran-"+formatDate(new Date())+".xlsx"
	})
	.appendTo("body")
	.get(0)
	.click();
	$('.bg-c1,.bg-c2,.bg-c3').each(function(){
		$(this).removeAttr('bgcolor');
	});
});
function getData(){
	var tahun_anggaran = $('#filter_anggaran option:selected').val();
	var coa = $('#filter_coa').val();
	var page = base_url + 'settings/index_besaran/data';
	page 	+= '/'+tahun_anggaran;
	page 	+= '/'+coa;

	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'settings/index_besaran/data';
	page 	+= '/'+tahun_anggaran;
	page 	+= '/'+coa;

	$.ajax({
		url 	: page,
		data 	: {},
		type	: 'get',
		dataType: 'json',
		success	: function(response) {
			$('.table-app tbody').html(response.table);
			cLoader.close();
			cek_autocode();
			fixedTable();
			var item_act	= {};
			if($('.table-app tbody .btn-input').length > 0) {
				item_act['edit'] 		= {name : lang.realisasi, icon : "edit"};					
			}

			var act_count = 0;
			for (var c in item_act) {
				act_count = act_count + 1;
			}
			if(act_count > 0) {
				$.contextMenu({
			        selector: '.table-app tbody tr', 
			        callback: function(key, options) {
			        	if($(this).find('[data-key="'+key+'"]').length > 0) {
				        	if(typeof $(this).find('[data-key="'+key+'"]').attr('href') != 'undefined') {
				        		window.location = $(this).find('[data-key="'+key+'"]').attr('href');
				        	} else {
					        	$(this).find('[data-key="'+key+'"]').trigger('click');
					        }
					    } 
			        },
			        items: item_act
			    });
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
	}
	if(tr.find('td.edited').length > 0) {
		tr.addClass('edited-row');
	} else {
		tr.removeClass('edited-row');
	}
});
// $(document).on('keyup','.edit-value',function(e){
// 	var wh 			= e.which;
// 	if((48 <= wh && wh <= 57) || (96 <= wh && wh <= 105) || wh == 8) {
// 		if($(this).text() == '') {
// 			$(this).text('');
// 		} else {
// 			var n = parseInt($(this).text().replace(/[^0-9\-]/g,''),10);
// 		    $(this).text(n.toLocaleString());
// 		    var selection = window.getSelection();
// 			var range = document.createRange();
// 			selection.removeAllRanges();
// 			range.selectNodeContents($(this)[0]);
// 			range.collapse(false);
// 			selection.addRange(range);
// 			$(this)[0].focus();
// 		}
// 	}
// });
// $(document).on('keypress','.edit-value',function(e){
// 	var wh 			= e.which;
// 	if (e.shiftKey) {
// 		if(wh == 0) return true;
// 	}
// 	if(e.metaKey || e.ctrlKey) {
// 		if(wh == 86 || wh == 118) {
// 			$(this)[0].onchange = function(){
// 				$(this)[0].innerHTML = $(this)[0].innerHTML.replace(/[^0-9\-]/g, '');
// 			}
// 		}
// 		return true;
// 	}
// 	if(wh == 0 || wh == 8 || wh == 45 || (48 <= wh && wh <= 57) || (96 <= wh && wh <= 105)) 
// 		return true;
// 	return false;
// });

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
	var tahun_anggaran = $('#filter_anggaran option:selected').val();
	var coa = $('#filter_coa').val();
	var page = base_url + 'settings/index_besaran/save_perubahan';
	page 	+= '/'+tahun_anggaran;
	page 	+= '/'+coa;	
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