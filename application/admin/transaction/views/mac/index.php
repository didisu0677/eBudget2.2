<?php
	function select_custom($label,$id,$data,$opt_key,$opt_name,$value=""){
		echo '<label>'.$label.' &nbsp</label>';
		$select = '<select class="select2 custom-select" id="'.$id.'">';
		foreach ($data as $v) {
			$selected = '';if($v[$opt_key] == $value): $selected = ' selected'; endif;
			$select .= '<option value="'.$v[$opt_key].'"'.$selected.'>'.remove_spaces($v[$opt_name]).'</option>';
		}
		$select .= '</select> &nbsp';
		echo $select;
	}
?>
<style type="text/css">
red{
	color:red;
}
.mw-100{
	min-width: 100px !important;
}
.mw-150{
	min-width: 200px !important;
}
.mw-250{
	min-width: 550px !important;
}
.t-sb-1{
	background-color: #cacaca;
}
.r-45{
	transform: rotate(45deg);
}
.r-45-{
	transform: rotate(-45deg);
}
.table-detail{
	font-size: 20px;
}
canvas{
	height: 350px !important;
}
</style>
<div class="content-header page-data" data-additional="<?= $access_additional ?>">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<?php
			input('hidden',lang('user'),'user_cabang','',user('kode_cabang'));
			select_custom(lang('anggaran'),'filter_tahun',$tahun,'kode_anggaran','keterangan', user('kode_anggaran'));
			echo filter_cabang_admin($access_additional,$cabang);
			select_custom(lang('bulan'),'filter_bulan',$bulan,'value','name');
			?>
			
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body mt-6">
	<div class="d-content mt-2"></div>

	<div class="col-sm-12">
		<div class="card">
			<div class="card-header pl-3 pr-3">
				<ul class="nav nav-pills card-header-pills">
					<li class="nav-item">
						<a class="nav-link active" href="#overall" data-toggle="pill" role="tab" aria-controls="pills-overall" aria-selected="true">Dashboard</a>
					</li>
				</ul>
			</div>
			<div class="card-body tab-content">
				<div class="table-responsive tab-pane fade active show" id="overall">
					<div class="card">
				    	<div class="card-header text-center"><?php echo lang('dana_pihak_ketiga'); ?></div>
						<div class="row">
							<div class="col-sm-6 dana-pihak-3">
							  	<div class="card-body">
						            <table class="table table-bordered table-app table-detail table-normal">
						                <tr>
						                    <th class="text-center" width="200">PENGHIMPUNAN</th>
						                    <th class="text-center">PERT (YOY)</th>
						                    <th class="text-center">DEVIASI</th>
						                </tr>
						                <tr>
						                    <th width="200" class="text-center" id="penghimpunan">&nbsp</th>      
						                    <th width="200" class="text-center" id="pertumbuhan">&nbsp</th>   
						                    <th width="200" class="text-center" id="deviasi">&nbsp</th>            
						                </tr>
						            </table>
						        </div>
						        <div class="card-body">
						            <table class="table table-bordered table-app table-detail table-normal">
						                <tr>
						                    <th class="text-center" width="200">GIRO (PENCAPAIAN)</th>
						                    <th class="text-center">TAB (PENCAPAIAN)</th>
						                    <th class="text-center">SIMP BJK (PENC)</th>
						                </tr>
						                <tr>
						                    <th width="200" class="text-center" id="2100000">&nbsp</th>   
						                    <th width="200" class="text-center" id="2120011">&nbsp</th>   
						                    <th width="200" class="text-center" id="2130000">&nbsp</th>              
						                </tr>
						            </table>
						        </div>

						        <div class="card-body">
						            <table class="table table-bordered table-app table-detail table-normal">
						                <tr>
						                	<th class="text-center" width="200">GIRO (RENC)</th>
						                    <th class="text-center" width="200">GIRO (REAL)</th>
						                </tr>
						                <tr>
						                	<th width="200" class="text-center" id="renc_giro">&nbsp</th> 
						                    <th width="200" class="text-center" id="real_giro">&nbsp</th>                     
						                </tr>
						            </table>
						        </div>
						        <div class="card-body">
						            <table class="table table-bordered table-app table-detail table-normal">
						                <tr>
						                	<th class="text-center" width="200">TAB (RENC)</th>
						                    <th class="text-center" width="200">TAB (REAL)</th>
						                </tr>
						                <tr>
						                	<th width="200" class="text-center" id="renc_tab">&nbsp</th>
						                    <th width="200" class="text-center" id="real_tab">&nbsp</th>
						                </tr>
						            </table>
						        </div>
						        <div class="card-body">
						            <table class="table table-bordered table-app table-detail table-normal">
						                <tr>
						                	<th class="text-center" width="200">SIMP BJK (RENC)</th>
						                    <th class="text-center" width="200">SIMP BJK (REAL)</th>
						                </tr>
						                <tr>
						                	<th width="200" class="text-center" id="renc_simp_bjk">&nbsp</th>  
						                    <th width="200" class="text-center" id="real_simp_bjk">&nbsp</th>
						                </tr>
						            </table>
						        </div>
							</div>
							<div class="col-sm-6">
								<div class="card-body">
									<div class="col-sm-12">
										<canvas id="chart_pie_dpk"></canvas>
									</div>
								</div>

								<div class="card-body">
									<div class="col-sm-12">
										<canvas id="chart_bar_dpk"></canvas>
									</div>
								</div>
							</div>
						</div>
					</div>	

					<div class="card mt-3">
						<div class="card-header text-center"><?php echo lang('total_kredit_n_k'); ?></div>
						<div class="row">
							<div class="col-sm-6 d-kredit">							       
							  	<div class="card-body">
						            <table class="table table-bordered table-app table-detail table-normal">
						                <tr>
						                    <th class="text-center" width="200">EXPANSI</th>
						                    <th class="text-center">PERT (YOY)</th>
						                    <th class="text-center">DEVIASI</th>
						                </tr>
						                <tr>
						                    <th class="text-center" id="ekspansi" width="200">&nbsp</th>      
						                    <th class="text-center" id="pertumbuhan" width="200">&nbsp</th>   
						                    <th class="text-center" id="deviasi" width="200">&nbsp</th>            
						                </tr>
						            </table>
						        </div>
						        <div class="card-body">
						            <table class="table table-bordered table-app table-detail table-normal">
						                <tr>
						                    <th class="text-center" width="200">KRD PROD (%)</th>
						                    <th class="text-center">KRD KONS (%)</th>
						                </tr>
						                <tr>
						                    <th class="text-center" id="122502" width="200">&nbsp</th>   
						                    <th class="text-center" id="122506" width="200">&nbsp</th>            
						                </tr>
						            </table>
						        </div>
						        <div class="card-body">
						            <table class="table table-bordered table-app table-detail table-normal">
						                <tr>
						                	<th class="text-center" width="200">KRD PROD (RENC)</th>
						                    <th class="text-center" width="200">KRD PROD (REAL)</th>
						                </tr>
						                <tr>
						                	<th width="200" class="text-center" id="renc_krd_prod">&nbsp</th>
						                    <th width="200" class="text-center" id="real_krd_prod">&nbsp</th>
						                </tr>
						            </table>
						        </div>
						        <div class="card-body">
						            <table class="table table-bordered table-app table-detail table-normal">
						                <tr>
						                	<th class="text-center" width="200">KRD KONS (RENC)</th>
						                    <th class="text-center" width="200">KRD KONS (REAL)</th>
						                </tr>
						                <tr>
						                	<th width="200" class="text-center" id="renc_krd_kons">&nbsp</th>
						                    <th width="200" class="text-center" id="real_krd_kons">&nbsp</th>
						                </tr>
						            </table>
						        </div>	       
							</div>
							<div class="col-sm-6">
								<div class="card-body">
									<div class="col-sm-12">
										<canvas id="chart_pie_kredit"></canvas>
									</div>
								</div>
								<div class="card-body">
									<div class="col-sm-12">
										<canvas id="chart_bar_kredit"></canvas>
									</div>
								</div>
							</div>
						</div>
					</div>	

					<div class="card mt-3">
						<div class="card-header text-center"><?php echo lang('laba_usaha'); ?></div>
						<div class="row">
							<div class="col-sm-6 laba">
							  <div class="card-body">
						            <table class="table table-bordered table-app table-detail table-normal">
						                <tr>
						                    <th class="text-center" width="200">PENCAPAIAN</th>
						                    <th class="text-center">PERT (YOY)</th>
						                    <th class="text-center">DEVIASI</th>
						                </tr>
						                <tr>
						                    <th class="text-center" id="pencapaian" width="200">&nbsp</th>      
						                    <th class="text-center" id="pertumbuhan" width="200">&nbsp</th>   
						                    <th class="text-center" id="deviasi" width="200">&nbsp</th>            
						                </tr>
						            </table>
						        </div>		
						        <div class="card-body">
						            <table class="table table-bordered table-app table-detail table-normal">
						                <tr>
						                    <th class="text-center" width="200">PENDAPATAN</th>
						                    <th class="text-center">BEBAN</th>
						                </tr>
						                <tr>
						                    <th class="text-center" id="pendapatan" width="200">&nbsp</th>   
						                    <th class="text-center" id="beban" width="200">&nbsp</th>          
						                </tr>
						            </table>
						        </div>
						        <?php foreach ($coa_laba as $v) {
						        echo '<div class="card-body">
						            <table class="table table-bordered table-app table-detail table-normal">
						            	<tr><th class="text-center" colspan="2">'.remove_spaces($v['glwdes']).'</th></tr>
						                <tr>
						                	<th class="text-center">RENC</th>
						                    <th class="text-center" width="200">REAL</th>
						                </tr>
						                <tr>
						                	<th class="text-center" id="renc_'.$v['glwnco'].'" width="200">&nbsp</th>
						                    <th class="text-center" id="real_'.$v['glwnco'].'" width="200">&nbsp</th>
						                </tr>
						            </table>
						        </div>';
						        } ?>				       
							</div>
							<div class="col-sm-6">
									<div class="card-body">
										<div class="col-sm-12">
											<canvas id="chart_bar_pendapatan"></canvas>
										</div>
									</div>

									<div class="card-body">
										<div class="col-sm-12">
											<canvas id="chart_bar_beban"></canvas>
										</div>
									</div>
							</div>
						</div>
					</div>	


					<div class="card mt-3">
						<div class="card-header text-center">PENDAPATAN (<?= get_view_report() ?>)</div>
						<div class="card-body">
							<div class="col-sm-12">
								<div class="form-group row">	
									<div class="col-sm-12">
										<canvas id="chart_bar_pendapatan_core" height="200"></canvas>
									</div>

								</div>
							</div>
						</div>
					</div>

					<div class="card mt-3">
						<div class="card-header text-center">BEBAN (<?= get_view_report() ?>)</div>	
						<div class="card-body">
							<div class="col-sm-12">
								<div class="form-group row">	
									<div class="col-sm-12">
										<canvas id="chart_bar_beban_core" height="200"></canvas>
									</div>

								</div>
							</div>
						</div>
					</div>

					<div class="row">
					<?php
						foreach ($arr_coa_other as $k=> $v) {
							$right = '';
							$left  = '';

							$table = '<div class="col-sm-5">';
							$table .= '<table class="table table-bordered table-app table-detail table-normal">
						                <tr>
						                    <th class="text-center" width="100">PENC</th>
						                    <th class="text-center" id="pencapaian"></th>
						                </tr>
						                <tr>
						                    <th class="text-center" width="100">DEVIASI</th>
						                    <th class="text-center" id="hemat"></th>
						                </tr>
						            </table></div>';
						    $chart = '<div class="col-sm-7">';
						    $chart .= '<canvas class="pointer" id="chart_bar_'.$v.'" height="100"></canvas></div>';
							if($k % 2 == 0):
								$left 	= $table;
								$right 	= $chart;
							else:
								$left 	= $chart;
								$right 	= $table;
							endif;

							$item = '<div class="mt-3 col-sm-6 v-'.$v.'">';
							$item .= '<div class="card">';
							$item .= '<div class="card-header text-center"></div>';
							$item .= '<div class="card-body row">';
								$item .= $left;
								$item .= $right;
							$item .= '</div></div></div>';
							echo $item;
						}
					?>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>	


<script type="text/javascript" src="<?php echo base_url('assets/js/Chart.bundle.min.js'); ?>"></script>
<script type="text/javascript">
var serialize_color = [
    '#404E67',
    '#22C2DC',
    '#ff6384',
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

var chart_pie_dpk,chart_pie_kredit;
var chart_bar_dpk,chart_bar_pendapatan,chart_bar_beban,chart_bar_pendapatan_core,chart_bar_beban_core;
var controller = '<?= $controller ?>';
var arr_coa_other;
var content_status = false;
$(document).ready(function(){
	get_arr_coa_other();
});
function get_arr_coa_other(){
	var page = base_url +'transaction/'+controller+'/get_arr_coa_other';
	$.ajax({
			url 	: page,
			type	: 'post',
			dataType: 'json',
			success	: function(response) {
				$.each(response,function(k,v){
					var val = 'chart_bar_'+v;
					var ctx = document.getElementById(val).getContext('2d');
					window[val] = new Chart(ctx, {
						type: 'bar',
						options : option_bar,
					});

					$("#"+val).click(function(evt){
					   	var coa = v;
					   	var activePoints = window[val].getElementsAtEvent(evt);
					   	if(activePoints.length>0){
					   		window.open(base_url+'transaction/rekap_mac_group?coa='+coa, '_blank');
					   	}
				    });
				});
				content_status = true;
				getContent();
			}
		});
}
$('#filter_tahun').change(function(){getContent();});
$('#filter_cabang').change(function(){getContent();});
$('#filter_bulan').change(function(){getContent();});

function getContent(){
	if(!content_status){
		return '';
	}
	var cabang = $('#filter_cabang option:selected').val();
	if(!cabang){
		return '';
	}

	cLoader.open(lang.memuat_data + '...');

	var page = base_url +'transaction/'+controller+'/get_content';
	
	var tahun 	= $('#filter_tahun option:selected').val();
	var cabang	= $('#filter_cabang option:selected').val();
	var bulan 	= $('#filter_bulan option:selected').val();

	var classnya = 'd-'+cabang+'-'+bulan;
	var length = $('body').find('.'+classnya).length;
	var length_body = $('body').find('.d-content-body').length;

	if(length_body>0){
		$('body').find('.d-content-body').hide(300);
	}

	if(length<=0){
		$.ajax({
			url 	: page,
			data 	: {
				tahun 	: tahun,
				cabang 	: cabang,
				bulan 	: bulan,
			},
			type	: 'post',
			dataType: 'json',
			success	: function(response) {
				$('.d-content').append('<div class="d-content-body '+classnya+'"></div>');
				$('body').find('.'+classnya).html(response.view);
				cLoader.close();
			}
		});
	}else{
		$('body').find('.'+classnya).show(300);
		cLoader.close();
	}
	loadData();
}
var xhr_ajax = null;
function loadData(){
	cLoader.open(lang.memuat_data + '...');
    if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }

    var page = base_url + 'transaction/mac/data2';
    page += '/'+ $('#filter_tahun').val();
    page += '/'+ $('#filter_cabang').val();
    page += '/'+ $('#filter_bulan').val();

  	xhr_ajax = $.ajax({
        url: page,
        type: 'post',
		data : $('#form-filter').serialize(),
        dataType: 'json',
        success: function(res){
        	xhr_ajax = null;

        	// DPK
        	var dpk = res.chart_dpk;
        	set_pie_chart(chart_pie_dpk,'Jumlah',dpk.data,dpk.title,colors_dpk);
        	set_pie_chart(chart_bar_dpk,'Jumlah',dpk.data2,dpk.title,colors_dpk);
        	$.each(res.dpk,function(k,v){
        		$('.dana-pihak-3 #'+k).html(v);
        	});

        	// Kredit
        	var kredit = res.chart_kredit;
        	set_pie_chart(chart_pie_kredit,'Jumlah',kredit.data,kredit.title);
        	set_pie_chart(chart_bar_kredit,'Jumlah',kredit.data2,kredit.title);
        	$.each(res.kredit,function(k,v){
        		$('.d-kredit #'+k).html(v);
        	});

        	// laba
        	var pendapatan = res.chart_pendapatan;
        	chart_bar_pendapatan.options.legend.display = true;
        	set_pie_chart(chart_bar_pendapatan,'Pendapatan',pendapatan.data,pendapatan.title);

        	var beban  = res.chart_beban;
        	var colors = [];
        	$.each(beban.data,function(k,v){
        		var count = 3;
        		colors.push(serialize_color[count-k]);
        	})
        	chart_bar_beban.options.legend.display = true;
        	set_pie_chart(chart_bar_beban,'Beban',beban.data,beban.title,colors);
        	$.each(res.laba,function(k,v){
        		$('.laba #'+k).html(v);
        	})

        	// chart bar pendapatan core
        	var pendapatan_core = res.chart_pendapatan_core;
        	chart_bar_pendapatan_core.options.legend.display = true;
        	set_bar_chart2(chart_bar_pendapatan_core,pendapatan_core.labels,pendapatan_core.data);

        	// chart bar beban core
        	var beban_core = res.chart_beban_core;
        	chart_bar_beban_core.options.legend.display = true;
        	set_bar_chart2(chart_bar_beban_core,beban_core.labels,beban_core.data,3);

        	// chart other
        	var chart_other = res.chart_other;
        	var no = 0;
        	$.each(chart_other,function(k,v){
        		var val = '.v-'+k;
        		$(val+' .card-header').html(v.label);
        		$(val+' #pencapaian').html(v.pencapaian);
        		$(val+' #hemat').html(v.hemat);

        		var val_chart = 'chart_bar_'+k;
        		window[val_chart].options.legend.display = true;
        		set_bar_chart2(window[val_chart],[],v.data,0);
        		no += 2;
        	});

        	cLoader.close();
        }
    });
}

function set_pie_chart(chart,label,data,title,colors){
	if(!colors){
		colors = [];
		$.each(data,function(k,v){
			colors.push(serialize_color[k]);
		})
	}
	chart.data = {
        datasets: [{
            label: label,
            data: data,
            backgroundColor: colors,
        },
        ],
		labels: title,
	};

	chart.update();
}
function set_bar_chart(chart,label,data,title,colors){
	var datasets = [];
	$.each(data,function(k,v){
		datasets.push({
			label: title[k],
		  	data: [5427,5427,5427],
		  	backgroundColor: colors[k],
		  	borderWidth: 0,
		});
	})
	chart.data = {
		labels : label,
        datasets: datasets,
	};

	chart.update();
}
var ss;
function set_bar_chart2(chart,label,data,no){
	if(!no){ no = 0; }
	var datasets = [];
	$.each(data,function(k,v){
		datasets.push({
			label: k,
		  	data: v,
		  	backgroundColor: serialize_color[no],
		  	borderWidth: 0,
		});
		no++;
	});
	ss = {
		labels : label,
        datasets: datasets,
	};

	chart.data = {
		labels : label,
        datasets: datasets,
	};

	chart.update();
}

// option chart
var option_pie = {
	title: {
        display: false,
        text: 'PROGRESS (%)',
        fontSize: 14,
        padding: 10
    },
	maintainAspectRatio: false,
	responsive: true,
	legend: {
		display: true,
		position: 'bottom',
		labels: {
			boxWidth: 15,
			generateLabels: function(chart) {
				var data = chart.data;
				if (data.labels.length && data.datasets.length) {
					return data.labels.map(function(label, i) {
						var meta = chart.getDatasetMeta(0);
						var ds = data.datasets[0];
						var arc = meta.data[i];
						var custom = arc && arc.custom || {};
						var getValueAtIndexOrDefault = Chart.helpers.getValueAtIndexOrDefault;
						var arcOpts = chart.options.elements.arc;
						var fill = custom.backgroundColor ? custom.backgroundColor : getValueAtIndexOrDefault(ds.backgroundColor, i, arcOpts.backgroundColor);
						var stroke = custom.borderColor ? custom.borderColor : getValueAtIndexOrDefault(ds.borderColor, i, arcOpts.borderColor);
						var bw = custom.borderWidth ? custom.borderWidth : getValueAtIndexOrDefault(ds.borderWidth, i, arcOpts.borderWidth);

						var value = chart.config.data.datasets[arc._datasetIndex].data[arc._index];

						return {
							text: label + " : " + value+" %",
							fillStyle: fill,
							strokeStyle: stroke,
							lineWidth: bw,
							hidden: isNaN(ds.data[i]) || meta.data[i].hidden,
							index: i
						};
					});
				} else {
					return [];
				}
			}
		}
	}
}

var option_bar = {
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
	        ctx.fillStyle = '#000';

	        this.data.datasets.forEach(function (dataset, i) {
	            var meta = chartInstance.controller.getDatasetMeta(i);
	            meta.data.forEach(function (bar, index) {
	                var data = dataset.data[index];
	                ctx.fillText(numberFormat(data / 1,0), bar._model.x, bar._model.y - 5);
	            });
	        });
	    }
	},
	tooltips: {
	    "enabled": true,
	    callbacks: {
	        label: function(tooltipItem, data) {
	            var label = data.datasets[tooltipItem.datasetIndex].label || '';

	            if (label) {
	                label += ': ';
	            }
	            label += numberFormat(tooltipItem.yLabel / 1,0);
	            return label;
	        }
	    }
	},
	maintainAspectRatio: false,
	responsive: true,
    scales: {
		xAxes: [{
		    beginAtZero: true,
		    ticks: {
		        autoSkip: false
		    },
		    gridLines: {
                display:false
            },
		}],
		yAxes: [{
	        display: true,
	        gridLines: {
                display:false
            },
	        ticks: {
            	beginAtZero: true,
        	// Abbreviate the millions
        		callback: function(value, index, values) {
            	return numberFormat(value / 1,0);
        		}
    		}
	    }],
    },

	legend: {
		display: false,
		position: 'bottom',
			labels: {
			boxWidth: 15,
		}
	}
}

// colors
colors_dpk = ['#0099CC','#FF8800','#e5e5e5'];

$(document).ready(function(){
	var ctx = document.getElementById('chart_pie_dpk').getContext('2d');
	chart_pie_dpk = new Chart(ctx, {
		type: 'pie',
		options : option_pie,
	});

	var ctx = document.getElementById('chart_bar_dpk').getContext('2d');
	chart_bar_dpk = new Chart(ctx, {
		type: 'bar',
		options : option_bar,
	});

	var ctx = document.getElementById('chart_pie_kredit').getContext('2d');
	chart_pie_kredit = new Chart(ctx, {
		type: 'pie',
		options : option_pie,
	});

	var ctx = document.getElementById('chart_bar_kredit').getContext('2d');
	chart_bar_kredit = new Chart(ctx, {
		type: 'bar',
		options : option_bar,
	});

	var ctx = document.getElementById('chart_bar_pendapatan').getContext('2d');
	chart_bar_pendapatan = new Chart(ctx, {
		type: 'bar',
		options : option_bar,
	});

	var ctx = document.getElementById('chart_bar_beban').getContext('2d');
	chart_bar_beban = new Chart(ctx, {
		type: 'bar',
		options : option_bar,
	});

	var ctx = document.getElementById('chart_bar_pendapatan_core').getContext('2d');
	chart_bar_pendapatan_core = new Chart(ctx, {
		type: 'bar',
		options : option_bar,
	});

	
	var ctx = document.getElementById('chart_bar_beban_core').getContext('2d');
	chart_bar_beban_core = new Chart(ctx, {
		type: 'bar',
		options : option_bar,
	});
	
});
</script>