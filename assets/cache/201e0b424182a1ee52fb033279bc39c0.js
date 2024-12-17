
$( document ).ready(function() {
	resize_window();
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
	var page = base_url + 'transaction/rekap_usulan_besaran/data';
	page 	+= '/'+tahun_anggaran;
	page 	+= '/'+coa;

	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/rekap_usulan_besaran/data';
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
			checkSubData();
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
