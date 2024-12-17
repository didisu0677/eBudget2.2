<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			   <?php 
				$arr = [];
					$arr = [
					    ['btn-import','Import Data','fa-download'],
					    // ['btn-template','Template Import','fa-reg-file-alt']
					];
				echo access_button('',$arr); 
			?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('settings/'.$controller.'/data'),'tbl_history_import_budget_nett_konsolidasi');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('kode_cabang'),'','data-content="kode_cabang"');
				th(lang('kode_anggaran'),'','data-content="kode_anggaran"');
				th(lang('import_oleh'),'','data-content="update_by"');
				th(lang('update_terakhir'),'','data-content="update_at"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php
modal_open('modal-import',lang('impor'));
	modal_body();
		form_open(base_url('settings/'.$controller.'/import'),'post','form-import');
			col_init(3,9);
			fileupload('File Excel','fileimport','required','data-accept="xls|xlsx"');
			select2('Dalam bentuk','currency','required');
			form_button(lang('impor'),lang('batal'));
		form_close();
modal_close();
?>
<script>
var controller = '<?= $controller ?>';
$('.btn-import').click(function(){
	$('#form-import')[0].reset();
	$('#form-import').find('.upl-file-0').empty();

    $('#modal-import .alert').hide();
    $('#modal-import').modal('show');
    var val = $('#currency option').eq(0).val();
    if(val){
    	$('#currency').val(val).trigger('change');
    }

});
$(document).ready(function(){
	get_currency();
});
$(document).on('click','.btn-detail',function(){
	$.get(base_url + 'settings/'+controller+'/detail/' + $(this).attr('data-id'),function(res){
		cInfo.open(lang.detil,res,{modal_lg:true});
	});
});
function get_currency(){
	$.ajax({
		url : base_url + 'api/currency_option',
		type : 'post',
		data : {},
		dataType : 'json',
		success : function(response) {
			$('#currency').html(response.data);
		}
	});
}
</script>	