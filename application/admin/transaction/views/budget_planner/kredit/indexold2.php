<div class="content-header">
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
				//echo access_button('',$arr); 
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
	    		<div class="card-header"><?php echo lang('kredit'); ?></div>
				<div class="row">
					<div class="col-sm-12 mb-12 mb-sm-4">
						<div class="card">
							<div class="card-body p-2">
								<canvas id="chartbar" height="300"></canvas>
							</div>
						</div>
					</div>	
				</div>	
				<div class="card-body">
					<div class="table-responsive tab-pane fade active show" id="result1">
						<?php
						table_open('table table-bordered table-app table-hover');
							thead();
								?>
								<tr>
									<th style="background-color: #e64a19; color: white;"><font color="#fff">No</font></th>
									<th style="background-color: #e64a19; color: white;min-width:200px;"><font color="#fff">Keterangan</font></th>
									<th style="background-color: #e64a19; color: white;"><font color="#fff">Rate %</font></th>
									<?php
									for ($i = 1; $i <= 12; $i++) { 

										echo '<th style="background-color: #e64a19; color: white;"><font color="#fff">'.month_lang($i).'</font></th>';
									}
									?>

									
								</tr>
							<?php		
							tbody();
								tr();
									td('Tidak ada data','text-left','colspan="7"');
						table_close();
						?>					
					</div>

					<br>
					<div class="table-responsive tab-pane fade active show" id="result2">
						<?php
						table_open('table table-bordered table-app table-hover');
							thead();
								?>
								<tr>
									<th style="background-color: #e64a19; color: white;"><font color="#fff">No</font></th>
									<th style="background-color: #e64a19; color: white;min-width:200px;"><font color="#fff">Keterangan</font></th>
									<th style="background-color: #e64a19; color: white;"><font color="#fff">Rate %</font></th>
									<?php
									for ($i = 1; $i <= 12; $i++) { 

										echo '<th style="background-color: #e64a19; color: white;"><font color="#fff">'.month_lang($i).'</font></th>';
									}
									?>

									
								</tr>
							<?php		
							tbody();
								tr();
									td('Tidak ada data','text-left','colspan="7"');
						table_close();
						?>					
					</div>

					<br>
					<div class="card-header"><?php echo 'Kredit per Produk'; ?></div>
					<div class="table-responsive tab-pane fade active show" id="result3">
						<?php
						table_open('table table-bordered table-app table-hover');
							thead();
								?>
								<tr>
									<th style="background-color: #e64a19; color: white;"><font color="#fff">No</font></th>
									<th style="background-color: #e64a19; color: white;min-width:200px;"><font color="#fff">Keterangan</font></th>
									<th style="background-color: #e64a19; color: white;"><font color="#fff">Rate %</font></th>
									<?php

									foreach ($detail_tahun as $d) {

										echo '<th style="background-color: #e64a19; color: white;min-width:80px;"><font color="#fff">'.substr(month_lang($d['bulan']),0,3) . ' ' . $d['tahun'].'</font></th>';
									}

										echo '<th class="border-none"</th>';

										echo '<th style="background-color: #e64a19; color: white;min-width:80px;"><font color="#fff">Netto</font></th>';
										echo '<th class="border-none"</th>';
										echo '<th style="background-color: #e64a19; color: white;min-width:80px;"><font color="#fff">'.substr(month_lang($tahun->bulan_terakhir_realisasi),0,3). ' ' . $tahun->tahun_terakhir_realisasi.'</font></th>';
										echo '<th style="background-color: #e64a19; color: white;min-width:80px;"><font color="#fff">'.substr(month_lang($tahun->bulan_terakhir_realisasi -1),0,3). ' ' . $tahun->tahun_terakhir_realisasi.'</font></th>';
									?>

									
								</tr>
							<?php		
							tbody();
								tr();
									td('Tidak ada data','text-left','colspan="7"');
						table_close();
						?>					
					</div>

					<br>
					<div class="card-header"><?php echo 'Jumlah Rekening'; ?></div>
					<div class="table-responsive tab-pane fade active show" id="result4">
						<?php
						table_open('table table-bordered table-app table-hover');
							thead();
								?>
								<tr>
									<th style="background-color: #e64a19; color: white;"><font color="#fff">No</font></th>
									<th style="background-color: #e64a19; color: white;min-width:200px;"><font color="#fff">Keterangan</font></th>
									<?php
									foreach ($detail_tahun as $d) {

										echo '<th style="background-color: #e64a19; color: white;min-width:80px;"><font color="#fff">'.substr(month_lang($d['bulan']),0,3) . ' ' . $d['tahun'].'</font></th>';
									}
										echo '<th class="border-none"</th>';
										echo '<th style="background-color: #e64a19; color: white;min-width:80px;"><font color="#fff">Jumlah Terakhir</font></th>';
										echo '<th style="background-color: #e64a19; color: white;min-width:80px;"><font color="#fff">Tambahan</font></th>';
									?>

									
								</tr>
							<?php		
							tbody();
								tr();
									td('Tidak ada data','text-left','colspan="7"');
						table_close();
						?>					
					</div>
				</div>
			</div>
		</div>	
	</div>
</div>



<script type="text/javascript" src="<?php echo base_url('assets/js/Chart.bundle.min.js'); ?>"></script>

<script type="text/javascript">

var myChart;
var serialize_color = [
    '#404E67',
    '#22C2DC',
    '#00897b',
    '#ff9f40',
    '#ffcd56',
    '#4bc0c0',
    '#9966ff',
    '#36a2eb',
    '#848484',
    '#e8b892',
    '#bcefa0',
    '#4dc9f6',
    '#a0e4ef',
    '#c9cbcf',
    '#00A5A8',
    '#10C888',
    '#7d3cff',
    '#f2d53c',
    '#c80e13',
    '#e1b382',
    '#c89666',
    '#2d545e',
    '#12343b',
    '#9bc400',
    '#8076a3',
    '#f9c5bd',
    '#7c677f'
];
$(document).ready(function(){
	initchart();
	loadData();
	loadData2();
	loadData3();
	loadData4();
	loadChart();

});	

$('#filter_anggaran').change(function(){
	loadData();
	loadData2();
	loadData3();
	loadData4();
	loadChart();
});

$('#filter_cabang').change(function(){
	loadData();
	loadData2();
	loadData3();
	loadData4();
	loadChart();
});
	
function initchart(){
	var ctxBar = document.getElementById('chartbar').getContext('2d');
	myChart = new Chart(ctxBar, {
		type: 'bar',
		options: {
        "hover": {
            "animationDuration": 0
        },
          "hover": {
            "animationDuration": 0
        },
        "animation": {
            "duration": 1,
            "onComplete": function () {
                var chartInstance = this.chart,
                ctx = chartInstance.ctx;

                ctx.font = Chart.helpers.fontString(8, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                ctx.textAlign = 'center';
                ctx.textBaseline = 'bottom';

                this.data.datasets.forEach(function (dataset, i) {
                    var meta = chartInstance.controller.getDatasetMeta(i);
                    meta.data.forEach(function (bar, index) {
                        var data = dataset.data[index];
                        data = (parseFloat(data) / 1000);
                        data = toFixedIfNecessary(data,1);
                        ctx.fillText(data, bar._model.x, bar._model.y - 5);
                    });
                });
            }
        },
        legend: {
            "display": false
        },
        tooltips: {
            "enabled": false
        },

			title: {
                display: true,
                text: 'KREDIT',
                fontSize: 14,
                padding: 10
            },
			maintainAspectRatio: false,
			responsive: true,
		    scales: {
			  xAxes: [{
			      beginAtZero: true,
			      ticks: {
			         autoSkip: false
			      }
			  }],
	            yAxes: [{
	                    display: true,
	                    scaleLabel: {
	                        display: true,
	                        labelString: 'Jumlah'
	                    },
	                    ticks: {
                    	// Abbreviate the millions
                    		callback: function(value, index, values) {
                        	return numberFormat(value / 1,0);
                    		}
                		}
	                }],
	        },

			legend: {
				display: true,
				position: 'bottom',
					labels: {
					boxWidth: 15,
				}
			}
		}
	});
};	
var xhr_ajax = null;
function loadData(){
	$('#result1 tbody').html('');	
    if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }
	cLoader.open(lang.memuat_data + '...');
    var page = base_url + 'transaction/kredit/data/';
    page += '/'+ $('#filter_anggaran').val();
    page += '/'+ $('#filter_cabang').val();
  	xhr_ajax = $.ajax({
        url: page,
        type: 'post',
		data : $('#form-filter').serialize(),
        dataType: 'json',
        success: function(res){
        	xhr_ajax = null;
            $('#result1 tbody').html(res.data);	
            cLoader.close();
		}
    });
}

var xhr_ajax2 = null;
function loadData2(){
	$('#result2 tbody').html('');	
    if( xhr_ajax2 != null ) {
        xhr_ajax2.abort();
        xhr_ajax2 = null;
    }
	cLoader.open(lang.memuat_data + '...');
    var page = base_url + 'transaction/kredit/data2/';
    page += '/'+ $('#filter_anggaran').val();
    page += '/'+ $('#filter_cabang').val();
  	xhr_ajax2 = $.ajax({
        url: page,
        type: 'post',
		data : $('#form-filter').serialize(),
        dataType: 'json',
        success: function(res){
        	xhr_ajax2 = null;
            $('#result2 tbody').html(res.data);			
			cLoader.close();
        }
    });
}

var xhr_ajax4= null;
function loadData3(){
	$('#result3 tbody').html('');	
    if( xhr_ajax4 != null ) {
        xhr_ajax4.abort();
        xhr_ajax4 = null;
    }
	cLoader.open(lang.memuat_data + '...');
    var page = base_url + 'transaction/kredit/data3/';
    page += '/'+ $('#filter_anggaran').val();
    page += '/'+ $('#filter_cabang').val();
  	xhr_ajax4 = $.ajax({
        url: page,
        type: 'post',
		data : $('#form-filter').serialize(),
        dataType: 'json',
        success: function(res){
        	xhr_ajax4 = null;
            $('#result3 tbody').html(res.data);
        	cLoader.close();
        }
    });
}

var xhr_ajax5=null;
function loadData4(){
	$('#result4 tbody').html('');	
    if( xhr_ajax5 != null ) {
        xhr_ajax5.abort();
        xhr_ajax5 = null;
    }
	cLoader.open(lang.memuat_data + '...');
    var page = base_url + 'transaction/kredit/data4/';
    page += '/'+ $('#filter_anggaran').val();
    page += '/'+ $('#filter_cabang').val();
  	xhr_ajax5 = $.ajax({
        url: page,
        type: 'post',
		data : $('#form-filter').serialize(),
        dataType: 'json',
        success: function(res){
        	xhr_ajax5 = null;
            $('#result4 tbody').html(res.data);
            cLoader.close();
        }
    });
}

var xhr_ajax3 = null;
function loadChart(){
    if( xhr_ajax3 != null ) {
        xhr_ajax3.abort();
        xhr_ajax3 = null;
    }
	cLoader.open(lang.memuat_data + '...');
    var page = base_url + 'transaction/kredit/data2/';
    page += '/'+ $('#filter_anggaran').val();
    page += '/'+ $('#filter_cabang').val();
  	xhr_ajax2 = $.ajax({
        url: page,
        type: 'post',
		data : $('#form-filter').serialize(),
        dataType: 'json',
        success: function(res){
        	xhr_ajax3 = null;		


        	var data_tahun1 = [];
			var data_tahun2 = [];
			var data_tahun3 = [];
			var data_tahun4 = [];

			var label_chart 		= [];
			var no = 0;
			var no2 = 0;
			var a = '';
			var b = '' ;
			var c = '' ; 
			var label1 ='';
			var label2 ='';
			var label3 ='';
			var label4 ='';
			var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
				$.each(res.item_chart,function(x,y){	
					no++;
					if(no == 1) {
						label1 = y.keterangan;
						data_tahun1.push(y.P_01/10000000);
						data_tahun1.push(y.P_02/10000000);
						data_tahun1.push(y.P_03/10000000);
						data_tahun1.push(y.P_04/10000000);
						data_tahun1.push(y.P_05/10000000);
						data_tahun1.push(y.P_06/10000000);
						data_tahun1.push(y.P_07/10000000);
						data_tahun1.push(y.P_08/10000000);
						data_tahun1.push(y.P_09/10000000);
						data_tahun1.push(y.P_10/10000000);
						data_tahun1.push(y.P_11/10000000);
						data_tahun1.push(y.P_12/10000000);												
					}	

					if(no == 2) {
						label2 = y.keterangan;
						data_tahun2.push(y.P_01/10000000);
						data_tahun2.push(y.P_02/10000000);
						data_tahun2.push(y.P_03/10000000);
						data_tahun2.push(y.P_04/10000000);
						data_tahun2.push(y.P_05/10000000);
						data_tahun2.push(y.P_06/10000000);
						data_tahun2.push(y.P_07/10000000);
						data_tahun2.push(y.P_08/10000000);
						data_tahun2.push(y.P_09/10000000);
						data_tahun2.push(y.P_10/10000000);
						data_tahun2.push(y.P_11/10000000);
						data_tahun2.push(y.P_12/10000000);												
					}	

					if(no == 3) {
						label3 = y.keterangan;
						data_tahun3.push(y.P_01/10000000);
						data_tahun3.push(y.P_02/10000000);
						data_tahun3.push(y.P_03/10000000);
						data_tahun3.push(y.P_04/10000000);
						data_tahun3.push(y.P_05/10000000);
						data_tahun3.push(y.P_06/10000000);
						data_tahun3.push(y.P_07/10000000);
						data_tahun3.push(y.P_08/10000000);
						data_tahun3.push(y.P_09/10000000);
						data_tahun3.push(y.P_10/10000000);
						data_tahun3.push(y.P_11/10000000);
						data_tahun3.push(y.P_12/10000000);												
					}	

					if(no == 4) {
						label4 = y.keterangan;
						data_tahun4.push(y.P_01/10000000);
						data_tahun4.push(y.P_02/10000000);
						data_tahun4.push(y.P_03/10000000);
						data_tahun4.push(y.P_04/10000000);
						data_tahun4.push(y.P_05/10000000);
						data_tahun4.push(y.P_06/10000000);
						data_tahun4.push(y.P_07/10000000);
						data_tahun4.push(y.P_08/10000000);
						data_tahun4.push(y.P_09/10000000);
						data_tahun4.push(y.P_10/10000000);
						data_tahun4.push(y.P_11/10000000);
						data_tahun4.push(y.P_12/10000000);												
					}	
				});	

				for (var i = 1; i <= monthNames.length; i++) {
			    	if(i<10) {
			    		b = "P_" + "0"+i;
			    	}else{	
						b="P_"+i;
					}

					label_chart.push(i)
			    	
				}
				
				myChart.data = {
					datasets: [
			        {
			          label: label1,
			          type: "bar",
					  backgroundColor: "#0288d1",
					  data: data_tahun1,

			        }, 
			        {
			          label: label2,
			          type: "bar",
					  backgroundColor: "#ef6c00",
					  data: data_tahun2,
			        }, 
			       	
			       	{
			          label: label3,
			          type: "bar",
					  backgroundColor: "#00897b",
					  data: data_tahun3,
			        }, 
			        			        {
			          label: label4,
			          type: "bar",
					  backgroundColor: "#eeff41",
					  data: data_tahun4,
			        }, 
			      ],


			      labels: label_chart,
				};



				myChart.update();
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

//$(document).on('keyup','.edit-value',function(e){
//	var wh 			= e.which;
//	if((48 <= wh && wh <= 57) || (96 <= wh && wh <= 105) || wh == 8) {
//		if($(this).text() == '') {
//			$(this).text('');
//		} else {
//			var n = parseInt($(this).text().replace(/[^0-9\-]/g,''),10);
//		    $(this).text(n.toLocaleString());
//		    var selection = window.getSelection();
//			var range = document.createRange();
//			selection.removeAllRanges();
//			range.selectNodeContents($(this)[0]);
//			range.collapse(false);
//			selection.addRange(range);
//			$(this)[0].focus();
//		}
//	}
//});

//$(document).on('keypress','.edit-value',function(e){
//	var wh 			= e.which;
//	if (e.shiftKey) {
//		if(wh == 0) return true;
//	}
//	if(e.metaKey || e.ctrlKey) {
//		if(wh == 86 || wh == 118) {
//			$(this)[0].onchange = function(){
//				$(this)[0].innerHTML = $(this)[0].innerHTML.replace(/[^0-9\-]/g, '');
//			}
//		}
//		return true;
//	}
//	if(wh == 0 || wh == 8 || wh == 45 || (48 <= wh && wh <= 57) || (96 <= wh && wh <= 105)) 
//		return true;
//	return false;
//});

$(document).on('click','.btn-save',function(){
	var i = 0;
	i += $(document).find('.edited2').length;
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
	//	data_edit[$(this).attr('data-id')][$(this).attr('data-name')] = $(this).text().replace(/[^0-9\-]/g,'');
		i++;
	});

	$('.edited2').each(function(){
		var content = $(this).children('div');
		if(typeof data_edit[$(this).attr('data-id')] == 'undefined') {
			data_edit[$(this).attr('data-id')] = {};
		}
		data_edit[$(this).attr('data-id')][$(this).attr('data-name')] = $(this).attr('data-value');
	//	console.log($(this).attr('data-name')+":"+$(this).attr('data-value'));
		i++;
	});
	
	var jsonString = JSON.stringify(data_edit);		
	 $.ajax({
	 	url : base_url + 'transaction/kredit/save_perubahan',
	 	data 	: {
	 		'json' : jsonString,
	 		verifikasi : i
	 	},
	 	type : 'post',
	 	success : function(response) {
	 		console.log(response);
	 		//cAlert.open('','success','');
	 		cAlert.open('','success','refreshData');
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