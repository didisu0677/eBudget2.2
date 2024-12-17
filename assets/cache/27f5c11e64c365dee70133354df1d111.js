
function formOpen() {
	var response_data = response_edit;
	if(typeof response_data.id != 'undefined') {
		$('#id_provinsi').attr('data-temp_id',response_data.id_kota);
		$('#id_provinsi').val(response_data.id_provinsi).trigger('change');
	}else{
		$('#id_provinsi').attr('data-temp_id','');
	}
}
