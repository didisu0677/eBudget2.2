<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">

			<select class="select2 infinity custom-select" id="filter_anggaran">
				<?php foreach ($anggaran as $v) { ?>
                <option value="<?php echo $v['kode_anggaran']; ?>"<?php if($v['kode_anggaran'] == user('kode_anggaran')) echo ' selected'; ?>><?php echo $v['keterangan']; ?></option>
                <?php } ?>
			</select>	
			<?php 

			echo access_button('delete,active,inactive,export,import'); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,'','tbl_m_tarif_kolektibilitas');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('coa'),'','data-content="coa"');
				th(lang('nama_akun'),'','data-content="nama_produk_kredit" data-table="tbl_produk_kredit"');
				th(lang('kol_1'),'','data-content="kol_1"');
				th(lang('kol_2'),'','data-content="kol_2"');
				th(lang('kol_3'),'','data-content="kol_3"');
				th(lang('kol_4'),'','data-content="kol_4"');
				th(lang('kol_5'),'','data-content="kol_5"');
				th(lang('aktif').'?','text-center','data-content="is_active" data-type="boolean"');
				th('&nbsp;','','width="30" data-content="action_button"');
		tbody();
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','','data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('settings/tarif_kolektibilitas/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			select2(lang('tahun_anggaran'),'kode_anggaran','required',$anggaran,'kode_anggaran','keterangan');
			input('text',lang('coa'),'coa','required');
			input('text',lang('kol_1'),'kol_1');
			input('text',lang('kol_2'),'kol_2');
			input('text',lang('kol_3'),'kol_3');
			input('text',lang('kol_4'),'kol_4');
			input('text',lang('kol_5'),'kol_5');
			toggle(lang('aktif').'?','is_active');
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
modal_open('modal-import',lang('impor'),'','data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('settings/tarif_kolektibilitas/import'),'post','form-import');
			col_init(3,9);
			select2(lang('tahun_anggaran'),'kode_anggaran','required',$anggaran,'kode_anggaran','keterangan');
			fileupload('File Excel','fileimport','required','data-accept="xls|xlsx"');
			form_button(lang('impor'),lang('batal'));
		form_close();
modal_close();
?>
<script type="text/javascript">
$(document).ready(function(){
	getData();
});
$('#filter_anggaran').change(function(){
	getData();
});
var xhr_ajax = null;
function getData(){
	cLoader.open(lang.memuat_data + '...');
	$('.table-app tbody').html('');
    if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }

    var page = base_url + 'settings/tarif_kolektibilitas/data';
    page += '/'+ $('#filter_anggaran').val();
  	xhr_ajax = $.ajax({
        url: page,
        type: 'post',
		data : $('#form-filter').serialize(),
        dataType: 'json',
        success: function(res){
        	xhr_ajax = null;
            $('.table-app tbody').html(res.table);
            cLoader.close();
		}
    });
}
function formOpen(){
	var response = response_edit;
	if(typeof response.id != 'undefined') {

	}else{
		var val = $('#filter_anggaran option:selected').val();
		$('#kode_anggaran').val(val).trigger('change');
		$('#form-import #kode_anggaran').val(val).trigger('change');
	}
}
$('.table-app thead input:checkbox').on('click',function(){
	var bool = $(this).is(':checked');
	if(bool){
		$('.table-app tbody input:checkbox').prop('checked',true);
	}else{
		$('.table-app tbody input:checkbox').prop('checked',false);
	}
})
</script>