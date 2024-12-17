
var controller = 'rekap_mac_group';
$(document).ready(function () {
	getContent();
});
$('#filter_tahun').change(function(){getContent();});
$('#filter_coa').change(function(){getContent();});
$('#filter_bulan').change(function(){getContent();});
$('#filter_cabang').change(function(){getContent();});
function getContent(){
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/'+controller+'/get_content';
	
	var tahun 	= $('#filter_tahun option:selected').val();
	var bulan 	= $('#filter_bulan option:selected').val();
	var coa 	= $('#filter_coa option:selected').val();
	var cabang 	= $('#filter_cabang option:selected').val();

	if(!cabang){
		return '';
	}

	var classnya = 'd-'+bulan+'-'+coa+'-'+cabang;
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
				cabang 	: cabang,
			},
			type	: 'post',
			dataType: 'json',
			success	: function(response) {
				$('.d-content').append('<div class="d-content-body '+classnya+'"></div>');
				$('body').find('.'+classnya).html(response.view);
				cLoader.close();
				resize_window();
				getData(tahun,bulan,coa,cabang);
			}
		});
	}else{
		$('body').find('.'+classnya).show(300);
		cLoader.close();
	}
}
function getData(tahun,bulan,coa,cabang){
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/'+controller+'/data';
	var cabang 	= $('#filter_cabang option:selected').val();
	var classnya = 'd-'+bulan+'-'+coa+'-'+cabang;
	$.ajax({
		url 	: page,
		data 	: {
			tahun 	: tahun,
			bulan 	: bulan,
			coa 	: coa,
			cabang 	: cabang,
		},
		type	: 'post',
		dataType: 'json',
		success	: function(response) {
			$('body').find('.'+classnya+' .table-app tbody').html(response.view);
			checkSubData2(classnya);
			cLoader.close();
			var length = $('body').find('.'+classnya).length;
			if(length>=2){
				$('body').find('.'+classnya).eq(0).remove();
			}
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
