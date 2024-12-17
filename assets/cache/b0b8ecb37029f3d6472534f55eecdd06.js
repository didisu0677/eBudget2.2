
var controller = 'rekap_mac_';
$(document).ready(function () {
	getContent();
});
$('#filter_tahun').change(function(){getContent();});
$('#filter_coa').change(function(){getContent();});
$('#filter_bulan').change(function(){getContent();});
function getContent(){
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/'+controller+'/get_content';
	
	var tahun 	= $('#filter_tahun option:selected').val();
	var bulan 	= $('#filter_bulan option:selected').val();
	var coa 	= $('#filter_coa option:selected').val();

	var classnya = 'd-'+bulan+'-'+coa;
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
				bulan 	: bulan,
				coa 	: coa,
			},
			type	: 'post',
			dataType: 'json',
			success	: function(response) {
				$('.d-content').append('<div class="d-content-body '+classnya+'"></div>');
				$('body').find('.'+classnya).html(response.view);
				cLoader.close();
				resize_window();
				getData(tahun,bulan,coa);
			}
		});
	}else{
		$('body').find('.'+classnya).show(300);
		cLoader.close();
	}
}
function getData(tahun,bulan,coa){
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/'+controller+'/data';
	var classnya = 'd-'+bulan+'-'+coa;
	$.ajax({
		url 	: page,
		data 	: {
			tahun 	: tahun,
			bulan 	: bulan,
			coa 	: coa,
		},
		type	: 'post',
		dataType: 'json',
		success	: function(response) {
			$('body').find('.'+classnya+' .table-app tbody').html(response.view);
			$('body').find('.'+classnya+' .tbl-total tbody').html(response.total);
			checkSubData2(classnya);
			cLoader.close();
		}
	});
}
function checkSubData2(classnya){
	for (var i = 1; i <= 6; i++) {
		if($(document).find('.'+classnya+' .sb-'+i).length>0){
			var dt = $(document).find('.sb-'+i);
			$.each(dt,function(k,v){
				var text = $(v).text();
				text = text.replaceAll('|-----', "");
				$(v).text('|----- '+text);
			})
		}
	}
}
