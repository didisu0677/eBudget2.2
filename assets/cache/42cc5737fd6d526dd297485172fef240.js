
	$('.btn-import').click(function(){
		$('#form-import')[0].reset();

	    $('#modal-import .alert').hide();
	    $('#modal-import').modal('show');
	});
	
    $(document).on('click','.btn-template',function(){
		console.log('masul');
		var a = 'https://ebudget2.aplikasinusa.com/assets/templateExcel/templateRekapTarget.xlsx';
		window.open(a);
	});
