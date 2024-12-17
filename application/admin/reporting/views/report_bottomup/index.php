<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
	
		<div class="float-right">
			<label class=""><?php echo lang('tahun'); ?>  &nbsp</label>

			<select class="select2 infinity custom-select" id="filter_tahun">
				<?php foreach ($tahun as $tahun) { ?>
                <option value="<?php echo $tahun->tahun; ?>"<?php if($tahun->tahun == user('tahun_anggaran')) echo ' selected'; ?>><?php echo $tahun->tahun; ?></option>
                <?php } ?>
			</select>					
			               		
			<label class=""><?php echo lang('cabang'); ?>  &nbsp</label>
				
			<select class="select2 infinity custom-select" id="filter_posisi">
                <?php foreach($cabang as $b){ ?>
                <option value="<?php echo $b['kode_cabang']; ?>"><?php echo $b['nama_cabang']; ?></option>
                <?php } ?>
			</select>   	

			<label class=""><?php echo lang('cabang'); ?>  &nbsp</label>
				
			<select style="width: 150px;" class="select2 infinity custom-select" id="filter_cabang">
			</select>   		
    		
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

	
		<ul class="nav nav-tabs" id="tab-wizard" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="step1-tab" data-toggle="tab" href="#step1" role="tab" aria-controls="step1" aria-selected="true"><?php echo lang('usulan_besaran_tertentu'); ?></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="step2-tab" data-toggle="tab" href="#step2" role="tab" aria-controls="step2" aria-selected="off"><?php echo lang('aset_tetap_dan_inventaris'); ?></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="step3-tab" data-toggle="tab" href="#step3" role="tab" aria-controls="step3" aria-selected="off"><?php echo lang('rencana_kegiatan_promosi'); ?></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="step4-tab" data-toggle="tab" href="#step4" role="tab" aria-controls="step4" aria-selected="off"><?php echo lang('pengembangan_jaringan_kantor'); ?></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="step5-tab" data-toggle="tab" href="#step5" role="tab" aria-controls="step5" aria-selected="off"><?php echo lang('pengembangan_layanan_digital'); ?></a>
				</li>
			</ul>


	</div>	



<div class="content-body">
	<br>
	<br>


		<div class="tab-content" id="tab-wizardContent">
				<div class="tab-pane show active" id="step1" role="tabpanel" aria-labelledby="step1-tab">	
					<br>
    <div class="table">
    <table id="result" class="table table-app table-bordered table-detail table-grid table mb-0">
            <thead>
 			<tr>
				<th width="60" rowspan="2" class="text-center align-middle"><?php echo lang('no');?></th>
				<th width="160" rowspan="2" class="text-center align-middle"><?php echo lang('keterangan');?></th>
				<th class="text-center"><?php echo 'Des' . user('tahun_anggaran');?></th>
				<th class="text-center"><?php echo 'Jan' . user('tahun_anggaran');?></th>
				<th class="text-center"><?php echo 'Feb' . user('tahun_anggaran');?></th>
				<th class="text-center"><?php echo 'Mar' . user('tahun_anggaran');?></th>
				<th class="text-center"><?php echo 'Apr' . user('tahun_anggaran');?></th>
				<th class="text-center"><?php echo 'May' . user('tahun_anggaran');?></th>
				<th class="text-center"><?php echo 'Jun' . user('tahun_anggaran');?></th>
				<th class="text-center"><?php echo 'Jul' . user('tahun_anggaran');?></th>
				<th class="text-center"><?php echo 'Agu' . user('tahun_anggaran');?></th>
				<th class="text-center"><?php echo 'Sep' . user('tahun_anggaran');?></th>
				<th class="text-center"><?php echo 'Okt' . user('tahun_anggaran');?></th>
				<th class="text-center"><?php echo 'Nov' . user('tahun_anggaran');?></th>
				<th class="text-center"><?php echo 'Des' . user('tahun_anggaran');?></th>
				<th rowspan="2" class="text-center align-middle"><?php echo lang('total');?></th>
			<tr>
				<th class="text-center">Realisasi</th>
				<th class="text-center">Bottom Up</th>
				<th class="text-center">Bottom Up</th>
				<th class="text-center">Bottom Up</th>
				<th class="text-center">Bottom Up</th>
				<th class="text-center">Bottom Up</th>
				<th class="text-center">Bottom Up</th>
				<th class="text-center">Bottom Up</th>
				<th class="text-center">Bottom Up</th>
				<th class="text-center">Bottom Up</th>
				<th class="text-center">Bottom Up</th>
				<th class="text-center">Bottom Up</th>
				<th class="text-center">Bottom Up</th>	
			</tr>	
            </thead>
            <tbody></tbody>

    </table>
            </div>
		</div>

		 <div class="tab-pane" id="step2" role="tabpanel" aria-labelledby="step2-tab">
		<div class="form-group row">
			<br>
					<label class="col-form-label col-sm-3"></label>
	
		
	
					<div class="col-md-2">
						<select name="zona_waktu[<?php echo $j['id']; ?>]" autocomplete="off" class="form-control select2 infinity zona">
							<option value="WIB">WIB</option>
							<option value="WITA">WITA</option>
							<option value="WIT">WIT</option>
						</select>
					</div>
				</div>
		</div>	
	</div>	
	</div>
</div>



<script type="text/javascript">

$('#filter_tahun').change(function(){
	getData();
});

$('#filter_cabang').change(function(){
	getData();
});

$(document).ready(function () {
	get_cabang();
	getData();
    $(document).on('keyup', '.calculate', function (e) {
        calculate();
    });
});	

$('#filter_tahun').change(function(){
	getData();
});

$('#filter_posisi').change(function(){
	get_cabang();
});

function get_cabang() {
	if(proccess) {
		readonly_ajax = false;
		$.ajax({
			url : base_url + 'reporting/report_bottomup/get_cabang',
			data : {level_cabang: $('#filter_posisi').val()},
			type : 'POST',
			success	: function(response) {
				rs = response;
				$('#filter_cabang').html(response)
			}
		});

		$('#filter_cabang').val('all').trigger('change')
	}
}

function getData() {
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'reporting/report_bottomup/data';
	page 	+= '/'+$('#filter_tahun').val();
	page 	+= '/'+$('#filter_cabang').val();
	page 	+= '/'+$('#filter_posisi').val();

	$.ajax({
		url 	: page,
		data 	: {},
		type	: 'get',
		dataType: 'json',
		success	: function(response) {
						$('.table-app tbody').html(response.table);
			$('#parent_id').html(response.option);
			cLoader.close();
			cek_autocode();
			fixedTable();
			var item_act	= {};

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
$(function(){
	getData();
});

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

function calculate() {
	var total_budget = 0;

	$('#result tbody tr').each(function(){
		if($(this).find('.budget').length == 1) {
			var subtotal_budget = moneyToNumber($(this).find('.budget').val());
			total_budget += subtotal_budget;
		}


	});

	$('#total_budget').val(total_budget);
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



$(document).on('click','.btn-export',function(){
	var currentdate = new Date(); 
	var datetime = currentdate.getDate() + "/"
	                + (currentdate.getMonth()+1)  + "/" 
	                + currentdate.getFullYear() + " @ "  
	                + currentdate.getHours() + ":"  
	                + currentdate.getMinutes() + ":" 
	                + currentdate.getSeconds();
	
	$('.bg-grey-2').each(function(){
		$(this).attr('bgcolor','#f4f4f4');
	});
	$('.bg-grey-2').each(function(){
		$(this).attr('bgcolor','#dddddd');
	});
	$('.bg-grey-2-1').each(function(){
		$(this).attr('bgcolor','#b4b4b4');
	});
	$('.bg-grey-2-2').each(function(){
		$(this).attr('bgcolor','#aaaaaa');
	});
	$('.bg-grey-2').each(function(){
		$(this).attr('bgcolor','#888888');
	});
	var table	= '<table>';
	table += '<tr><td colspan="1">Bank Jateng</td></tr>';
	table += '<tr><td colspan="1"> Usulan Bottom Up Besaran Tertentu </td><td colspan="25">: '+$('#filter_tahun option:selected').text()+'</td></tr>';
	table += '<tr><td colspan="1"> Cabang </td><td colspan="25">: '+$('#filter_cabang option:selected').text()+'</td></tr>';
	table += '<tr><td colspan="1"> Print date </td><td colspan="25">: '+datetime+'</td></tr>';
	table += '</table><br />';
	table += '<table border="1">';
	table += $('.content-body').html();
	table += '</table>';
	var target = table;
	window.open('data:application/vnd.ms-excel,' + encodeURIComponent(target));
	$('.bg-grey-1,.bg-grey-2.bg-grey-2-1,.bg-grey-2-2,.bg-grey-3').each(function(){
		$(this).removeAttr('bgcolor');
	});
});

$(document).on('click','.btn-template',function(){
	var page = base_url + 'pl_sales/target_produk/template'
	   $.ajax({
		      url:page,
		      complete: function (response) {
		    	  window.open(page);
		      },
		  });
});

</script>