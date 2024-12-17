

$(document).ready(function() {
	var url = base_url + 'transaction/file_sharing/data/' ;
	$('[data-serverside]').attr('data-serverside',url);
	
	refreshData();
});	

// $('#filter_cabang').change(function(){
// 	var url = base_url + 'transaction/file_sharing/data/' ;
// 		url 	+= '/'+$('#filter_cabang').val() 
// 	$('[data-serverside]').attr('data-serverside',url);
	
// 	refreshData();
// });

	var is_edit = false;
	var idx = 999;
	function formOpen() {
		var c_cabang 		= $('#filter_cabang option:selected').val();
		var c_cabang_name 	= $('#filter_cabang option:selected').text();
		$('#kode_cabang').empty();
		$('#kode_cabang').append('<option value="'+c_cabang+'">'+c_cabang_name+'</option>').trigger('change');

		var response = response_edit;
		$('#additional-file').html('');
		if(typeof response.id != 'undefined') {
			$.each(response.file,function(n,z){
				var konten = '<div class="form-group row">'
					+ '<div class="col-sm-3 col-4 offset-sm-3">'
					+ '<input type="text" class="form-control" autocomplete="off" value="'+n+'" name="keterangan_file[]" placeholder="'+lang.keterangan+'" data-validation="required" aria-label="'+lang.keterangan+'">'
					+ '</div>'
					+ '<div class="col-sm-4 col-5">'
					+ '<input type="hidden" class="form-control" name="file[]" autocomplete="off" value="exist:'+z+'">'
					+ '<div class="input-group">'
					+ '<input type="text" class="form-control" autocomplete="off" disabled value="'+z+'">'
					+ '<div class="input-group-append">'
					+ '<a href="'+base_url+'assets/uploads/aset/'+z+'" target="_blank" class="btn btn-info btn-icon-only"><i class="fa-download"></i></a>'
					+ '</div>'
					+ '</div>'
					+ '</div>'
					+ '<div class="col-sm-2 col-3">'
					+ '<button type="button" class="btn btn-danger btn-remove btn-block btn-icon-only"><i class="fa-times"></i></button>'
					+ '</div>'
					+ '</div>';
				$('#additional-file').append(konten);
			});

		}
		is_edit= false;
	}

	function detail_callback(id){
		$.get(base_url+'transaction/file_sharing/detail/'+id,function(result){
		cInfo.open(lang.detil,result);
		});
	}

	$(document).on('click','.detail-file',function(){
		$.get(base_url+'transaction/file_sharing/detail_file?nomor_dokumen='+$(this).attr('data-value'),function(result){
			cInfo.open(lang.detil,result);
		});
	});

	$(document).on('click','.btn-remove',function(){
		$(this).closest('.form-group').remove();
	});
	$('#add-file').click(function(){
		$('#upl-file').click();
	});
	var accept 	= Base64.decode(upl_alw);
	var regex 	= "(\.|\/)("+accept+")$";
	var re 		= accept == '*' ? '*' : new RegExp(regex,"i");
	$('#upl-file').fileupload({
		maxFileSize: upl_flsz,
		autoUpload: false,
		dataType: 'text',
		acceptFileTypes: re
	}).on('fileuploadadd', function(e, data) {
		$('#add-file').attr('disabled',true);
		data.process();
		is_autocomplete = true;
	}).on('fileuploadprocessalways', function (e, data) {
		if (data.files.error) {
			var explode = accept.split('|');
			var acc 	= '';
			$.each(explode,function(i){
				if(i == 0) {
					acc += '*.' + explode[i];
				} else if (i == explode.length - 1) {
					acc += ', ' + lang.atau + ' *.' + explode[i];
				} else {
					acc += ', *.' + explode[i];
				}
			});
			cAlert.open(lang.file_yang_diizinkan + ' ' + acc + '. ' + lang.ukuran_file_maks + ' : ' + (upl_flsz / 1024 / 1024) + 'MB');
			$('#add-file').text($('#add-file').attr('title')).removeAttr('disabled');
		} else {
			data.submit();
		}
		is_autocomplete = false;
	}).on('fileuploadprogressall', function (e, data) {
		var progress = parseInt(data.loaded / data.total * 100, 10);
		$('#add-file').text(progress + '%');
	}).on('fileuploaddone', function (e, data) {
		if(data.result == 'invalid' || data.result == '') {
			cAlert.open(lang.gagal_menunggah_file,'error');
		} else {
			var spl_result = data.result.split('/');
			if(spl_result.length == 1) spl_result = data.result.split('\\');
			if(spl_result.length > 1) {
				var spl_last_str = spl_result[spl_result.length - 1].split('.');
				if(spl_last_str.length == 2) {
					var filename = data.result;
					var f = filename.split('/');
					var fl = filename.split('temp');
					var fl_link = base_url + 'assets/uploads/temp' + fl[1];
					var konten = '<div class="form-group row">'
								+ '<div class="col-sm-3 col-4 offset-sm-3">'
								+ '<input type="text" class="form-control" autocomplete="off" value="" name="keterangan_file[]" placeholder="'+lang.keterangan+'" data-validation="required" aria-label="'+lang.keterangan+'">'
								+ '</div>'
								+ '<div class="col-sm-4 col-5">'
								+ '<input type="hidden" class="form-control" name="file[]" autocomplete="off" value="'+data.result+'">'
								+ '<div class="input-group">'
								+ '<input type="text" class="form-control" autocomplete="off" disabled value="'+f[f.length - 1]+'">'
								+ '<div class="input-group-append">'
								+ '<a href="'+fl_link+'" target="_blank" class="btn btn-info btn-icon-only"><i class="fa-download"></i></a>'
								+ '</div>'
								+ '</div>'
								+ '</div>'
								+ '<div class="col-sm-2 col-3">'
								+ '<button type="button" class="btn btn-danger btn-remove btn-block btn-icon-only"><i class="fa-times"></i></button>'
								+ '</div>'
								+ '</div>';
					$('#additional-file').append(konten);
				} else {
					cAlert.open(lang.file_gagal_diunggah,'error');
				}
			} else {
				cAlert.open(lang.file_gagal_diunggah,'error');						
			}
		}
		$('#add-file').text($('#add-file').attr('title')).removeAttr('disabled');
		is_autocomplete = false;
	}).on('fileuploadfail', function (e, data) {
		cAlert.open(lang.gagal_menunggah_file,'error');
		$('#add-file').text($('#add-file').attr('title')).removeAttr('disabled');
		is_autocomplete = false;
	}).on('fileuploadalways', function() {
	});

