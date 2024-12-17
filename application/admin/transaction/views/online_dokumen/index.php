<div class="content-header page-data" data-additional="<?= $access_additional ?>">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<?php 
			echo filter_cabang_admin($access_additional,$cabang,['no-align' => true]).' ';
			echo access_button('delete,active,inactive,export,import');
			?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('transaction/online_dokumen/data'),'tbl_dokumen_online');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('nama_cabang'),'','data-content="nama_cabang"');
				th(lang('link'),'','data-content="link" data-link="link_file"');
				th(lang('aktif').'?','text-center','data-content="is_active" data-type="boolean"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','modal-lg','data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('transaction/online_dokumen/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			?>
			<div class="form-group row">
				<label class="col-form-label col-md-3"><?php echo lang('cabang'); ?>  &nbsp</label>
				<div class="col-md-4 col-9 mb-1 mb-md-0">	
					<select class="select2 infinity custom-select" id="kode_cabang" name="kode_cabang">
		                
					</select>   
				</div>
			</div>
			<?php
			input('text',lang('link'),'link');
			toggle(lang('aktif').'?','is_active');
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
modal_open('modal-import',lang('impor'));
	modal_body();
		form_open(base_url('transaction/online_dokumen/import'),'post','form-import');
			col_init(3,9);
			fileupload('File Excel','fileimport','required','data-accept="xls|xlsx"');
			form_button(lang('impor'),lang('batal'));
		form_close();
modal_close();
?>

<script type="text/javascript">

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
</script>