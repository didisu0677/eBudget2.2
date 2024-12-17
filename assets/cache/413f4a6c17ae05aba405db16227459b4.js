
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
var controller = 'mac';
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
