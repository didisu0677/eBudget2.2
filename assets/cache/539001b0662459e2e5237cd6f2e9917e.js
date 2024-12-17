

	$(document).ready(function() {
		var url = base_url + 'transaction/online_dokumen/data/' ;
			url 	+= '/'+$('#filter_cabang').val() 
		$('[data-serverside]').attr('data-serverside',url);
		
		refreshData();
	});	

	$('#filter_cabang').change(function(){
		var url = base_url + 'transaction/online_dokumen/data/' ;
			url 	+= '/'+$('#filter_cabang').val() 
		$('[data-serverside]').attr('data-serverside',url);
		
		refreshData();
	});

	var is_edit = false;
	var idx = 999;
	function formOpen() {
		var c_cabang 		= $('#filter_cabang option:selected').val();
		var c_cabang_name 	= $('#filter_cabang option:selected').text();
		$('#kode_cabang').empty();
		$('#kode_cabang').append('<option value="'+c_cabang+'">'+c_cabang_name+'</option>').trigger('change');

		var response = response_edit;
		if(typeof response.id != 'undefined') {
			$.each(response.file,function(n,z){
				
			});

		}
		is_edit= false;
	}

	function detail_callback(id){
		$.get(base_url+'transaction/online_dokumen/detail/'+id,function(result){
		cInfo.open(lang.detil,result);
		});
	}

	$(document).on('click','.link_file',function(){
		var url = $(this).attr('data-value');
		window.open(url, "_blank"); 
	});
