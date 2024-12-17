<style type="text/css">
form .select2-container--default .select2-selection--single{
	min-width: auto !important;
	width: auto !important;
}
</style>
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
					    ['btn-template','Template Import','fa-reg-file-alt']
					];
				
				
				echo access_button('',$arr); 
			?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('settings/import/data'),'tbl_trx_import_core');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('periode'),'','data-content="periode_import"');
				th(lang('tanggal_import'),'','data-content="tanggal_import" data-type="daterange"');
				th(lang('import_oleh'),'','data-content="update_by"');
				th(lang('update_terakhir'),'','data-content="update_at"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 

modal_open('modal-import',lang('impor'));
	modal_body();
		form_open(base_url('settings/import/import'),'post','form-import');
			col_init(3,9);
			?>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required"><?php echo lang('periode'); ?></label>
				<div class="col-md-4 col-6">
					<select id="periode_import" class="form-control select2" name="periode_import" data-validation=""  >
						<option value=""></option>
						<?php for($i = 1; $i <= 12; $i++){ ?>
                        <option value="<?php echo $i; ?>"<?php if($i == date('m')) echo ' selected'; ?>><?php echo month_lang($i); ?></option>
                        <?php } ?>
					</select>
				</div>
				<label class="col-form-label col-sm-2 required"><?php echo lang('tahun'); ?></label>
				<div class="col-md-3 col-4">
					<select id="tahun_import" class="form-control select2" name="tahun_import" data-validation=""  >
						<option value=""></option>
						<?php for($i = date('Y'); $i >= date('Y')-3; $i--){ ?>
                        <option value="<?php echo $i; ?>"<?php if($i == date('Y')) echo ' selected'; ?>><?php echo $i; ?></option>
                        <?php } ?>
					</select>
				</div>
			</div>
			<?php
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
$(document).on('click','.btn-detail',function(){
	$.get(base_url + 'settings/'+controller+'/detail/' + $(this).attr('data-id'),function(res){
		cInfo.open(lang.detil,res,{modal_lg:true});
	});
});
</script>	