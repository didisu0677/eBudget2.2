<style type="text/css">
form .select2-container--default .select2-selection--single{
	min-width: auto !important;
	width: auto !important;
}
</style>
<link rel="stylesheet" href="<?php echo base_url('assets/css/jquery.sortable.css'); ?>" />
<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<?php if($menu_access['access_edit']) { ?>
			<button type="button" class="btn btn-success btn-sm btn-sort" data-tipe="1"><i class="fa-align-right"></i><?php echo lang('atur_posisi').' Neraca'; ?></button>
			<button type="button" class="btn btn-success btn-sm btn-sort" data-tipe="2"><i class="fa-align-right"></i><?php echo lang('atur_posisi').' Laba Rugi'; ?></button>
			<?php } ?>
			<?php echo access_button('delete,active,inactive,export,import'); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',false);
		thead();
			tr();
				th(lang('glwsbi'));
				th(lang('glwnob'));
				// th(lang('glwcoa'));
				th(lang('glwnco'));
				th(lang('glwdes'));
				th(lang('kali minus').'?','text-center');
				th(lang('aktif').'?','text-center');
				th('&nbsp;','','width="30"');
		tbody();
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','',' data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('settings/master_coa/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			?>
			<?php
			select2(lang('sub_dari'),'level','',[],'id','glwdes');
			input('text',lang('glwsbi'),'glwsbi');
			input('text',lang('glwnob'),'glwnob');
			input('text',lang('glwcoa'),'glwcoa');
			input('text',lang('glwnco'),'glwnco');
			input('text',lang('glwdes'),'glwdes');
			select2(lang('tipe'),'tipe','required',[]);
			toggle(lang('kali minus').'?','kali_minus');
			toggle(lang('kantor_pusat').'?','kantor_pusat');
			toggle(lang('aktif').'?','is_active');
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
modal_open('modal-sort',lang('atur_posisi'),'modal-lg','modal-info');
	modal_body();
	modal_footer();
		echo '<form><button type="submit" class="btn btn-success" id="save-posisi">'.lang('simpan').'</button></form>';
modal_close();
?>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.sortable.min.js'); ?>"></script>
<script type="text/javascript">
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
</script>