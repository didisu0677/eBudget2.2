
$(function(){
	getData();
});
function getData() {
	cLoader.open(lang.memuat_data + '...');
	$.ajax({
		url 	: base_url + 'settings/master_coa/data',
		data 	: {},
		type	: 'get',
		dataType: 'json',
		success	: function(response) {
			$('.table-app tbody').html(response.table);
			$('#level').html(response.option);
			$('#tipe').html(response.option_tipe);
			checkSubData();
			fixedTable();
			cLoader.close();
		}
	});
}
function formOpen(){
	var response = response_edit;
	if(typeof response.id != 'undefined') {
		if(response.level){
			$('#level').val(response.level).trigger('change');
		}
	}
}
var tipe_coa = 1;
$('.btn-sort').click(function(){
	cLoader.open(lang.memuat_data + '...');
	$('#modal-sort .modal-body').html('');
	tipe_coa = $(this).attr('data-tipe');
	$.ajax({
		url : base_url + 'settings/master_coa/data/sortable',
		type : 'post',
		data : {
			tipe : $(this).attr('data-tipe'),
		},
		dataType : 'json',
		success : function(response) {
			$('#modal-sort .modal-body').html(response.content);
			$('#modal-sort').modal();
			$('ol.sortable').nestedSortable({
				forcePlaceholderSize: true,
				handle: 'div',
				helper:	'clone',
				items: 'li',
				opacity: .6,
				placeholder: 'placeholder',
				revert: 250,
				tabSize: 25,
				tolerance: 'pointer',
				toleranceElement: '> div',
				maxLevels: 4,
				isTree: true,
				expandOnHover: 700,
				isAllowed: function(item, parent, dragItem) {
					var x = true;
					if(dragItem.hasClass('module')) {
						if(typeof parent != 'undefined') x = false;
					} else {
						if(typeof parent == 'undefined') x = false;
						if(x && parent.closest('.module').attr('data-module') != dragItem.attr('data-module')) x = false;
					}
					return x;
				}
			});
			cLoader.close();
		}
	});
});
$('#save-posisi').click(function(e){
	e.preventDefault();
	var serialized = $('ol.sortable').nestedSortable('serialize');
	$.ajax({
		url : base_url + 'settings/master_coa/save_sortable/'+tipe_coa,
		type : 'post',
		data : serialized,
		dataType : 'json',
		success : function(response) {
			if(response.status == 'success') {
				cAlert.open(response.message,response.status,'refreshData');
			} else {
				cAlert.open(response.message,response.status);
			}
		}
	});
});
$(document).on('click','.btn-view',function(e){
	e.preventDefault();
	$.get(base_url + 'home//detail?t=tbl_m_coa&i='+ $(this).attr('data-id')+'&das=settings',function(res){
		cInfo.open(lang.detil,res,{modal_lg:true});
	});
})
