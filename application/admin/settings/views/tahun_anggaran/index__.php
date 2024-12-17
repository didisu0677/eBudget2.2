<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<?php echo access_button('delete,active,inactive,export,import'); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('settings/tahun_anggaran/data'),'tbl_tahun_anggaran');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('kode_anggaran'),'','data-content="kode_anggaran"');
				th(lang('keterangan'),'','data-content="keterangan"');
				th(lang('tahun_anggaran'),'','data-content="tahun_anggaran"');
				th(lang('aktif').'?','text-center','data-content="is_active" data-type="boolean"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','modal-lg','data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('settings/tahun_anggaran/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			input('text',lang('kode_anggaran'),'kode_anggaran');
			input('text',lang('keterangan'),'keterangan');
			input('text',lang('tahun_anggaran'),'tahun_anggaran');
				?>
		<div class="form-group row">
			<label class="col-form-label col-md-3 required"><?php echo lang('bulan_pengisian_budget'); ?></label>
			<div class="col-md-3 col-9 mb-1 mb-md-0">
				<select id="bulan" class="form-control col-md-9 col-xs-9 bulan select2" name="bulan[]" data-validation="required" aria-label="<?php echo lang('bulan'); ?>">
					<?php for($i = 1; $i <= 12; $i++){ ?>
        			<option value="<?php echo $i; ?>"><?php echo month_lang($i); ?></option>
        			<?php } ?>
				</select>
			</div>
			<div class="col-md-2 col-9 mb-1 mb-md-0">
				<input type="text" name="tahun[]" autocomplete="off" class="form-control tahun" data-validation="required|max-length:4" placeholder="<?php echo lang('tahun'); ?>" aria-label="<?php echo lang('tahun'); ?>" id="tahun">
			</div>
			<div class="col-md-3 col-9 mb-1 mb-md-0">
                <select id="sumber_data" class="form-control col-md-9 col-xs-9 sumber_data select2" name="sumber_data[]" data-validation="required" aria-label="<?php echo lang('sumber_data'); ?>">
					<option value=""></option>
					<?php foreach($opt_data as $u) {
						echo '<option value="'.$u['id'].'">'.$u['jenis_data'].'</option>';
					} ?>
                </select>
			</div>
			<div class="col-md-1 col-3 mb-1 mb-md-0">
				<button type="button" class="btn btn-block btn-success btn-icon-only btn-add-anggota"><i class="fa-plus"></i></button>
			</div>
		</div>
		<div id="additional-anggota" class="mb-2"></div>
		<div class="form-group row">
			<label class="col-form-label col-md-3 required"><?php echo lang('coa_usulan_besaran_tertentu'); ?></label>
                <div class="col-md-4 col-9 mb-1 mb-md-0">
                    <select id="id_coa_besaran" class="form-control col-md-9 col-xs-9 id_coa_besaran select2" name="id_coa_besaran[]" data-validation="required" aria-label="<?php echo lang('id_coa_besaran'); ?>" multiple>
						<option value=""></option>
						<?php foreach($coa as $u) {
							echo '<option value="'.$u['id'].'">'.$u['glwnco'] .' | '.$u['glwdes'].'</option>';
						} ?>
                    </select>
                </div>
        </div>        
		<?php 


			toggle(lang('aktif').'?','is_active');
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();

modal_open('modal-usulan-besaran','','modal-lg','data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('settings/tahun_anggaran/save_master_besaran'),'post','form-usulan-besaran');
			col_init(3,9);
			input('hidden','id_anggaran','id_anggaran');
		//	select2(lang('grup'),'grup','required',$opt_grup,'grup','grup');

		?>
		<div class="card mb-2">
    	<div class="card-header"><?php echo lang('template_besaran_tertentu'); ?></div>
    <div class="card-body p-1">
    		
			<div id="result2" class="mb-3">	
			<div class="table-responsive mb-2">
		    <table class="table table-bordered table-app table-detail table-ap table-normal">
		        <thead> 
		            <tr>
		                <th><?php echo lang('no'); ?></th>
		                <th class="text-c
		                enter"><?php echo lang('keterangan'); ?></th>
		               	<th class="text-c
		                enter"><?php echo lang('grup'); ?></th>
		                <th class="text-c
		                enter"><?php echo lang('coa'); ?></th>
		                <th class="text-c
		                enter"><?php echo lang('urutan'); ?></th>
		            </tr>
		        </thead>
		        <tbody>

		    	</tbody>
		    </table>  
		</div>      
		</div>
		</div>    
		<?php
		tbody();
	table_close();
				form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();


modal_open('modal-import',lang('impor'));
	modal_body();
		form_open(base_url('settings/tahun_anggaran/import'),'post','form-import');
			col_init(3,9);
			fileupload('File Excel','fileimport','required','data-accept="xls|xlsx"');
			form_button(lang('impor'),lang('batal'));
		form_close();
modal_close();
?>
<script type="text/javascript">
var select_value1 = '';
var select_val2 = '';
var grup = '';
function add_row_anggota() {
	konten = '<div class="form-group row">'
			+ '<label class="col-form-label col-md-3"></label>'
			+ '<div class="col-md-3 col-9 mb-1 mb-md-0">'
			+ '<select class="form-control bulan" name="bulan[]" data-validation="required" aria-label="'+$('#bulan').attr('aria-label')+'">'+select_value1+'</select> '
			+ '</div>'
			+ '<div class="col-md-2 col-9 mb-1 mb-md-0">'
			+ '<input type="text" name="tahun[]" autocomplete="off" class="form-control tahun" data-validation="required|max-length:4" placeholder="'+$('#tahun').attr('placeholder')+'" aria-label="'+$('#tahun').attr('placeholder')+'">'
			+ '</div>'
			+ '<div class="col-md-3 col-9 mb-1 mb-md-0">'
			+ '<select class="form-control sumber_data" name="sumber_data[]" data-validation="required" aria-label="'+$('#sumber_data').attr('aria-label')+'">'+select_val2+'</select>' 
			+ '</div>'
			+ '<div class="col-md-1 col-3 mb-1 mb-md-0">'
			+ '<button type="button" class="btn btn-block btn-danger btn-icon-only btn-remove-anggota"><i class="fa-times"></i></button>'
			+ '</div>'
			+ '</div>';
	$('#additional-anggota').append(konten);

	var $t = $('#additional-anggota .bulan:last-child');
	$t.select2({
		dropdownParent : $t.parent()
	});

	var $t1 = $('#additional-anggota .sumber_data:last-child');
	$t1.select2({
		dropdownParent : $t.parent()
	});
}

$('.btn-add-anggota').click(function(){
	add_row_anggota();
});
$(document).on('click','.btn-remove-anggota',function(){
	$(this).closest('.form-group').remove();
});	

$(document).ready(function(){
	get_grup();
	select_value1 = $('#bulan').html();
	select_val2 = $('#sumber_data').html();
});
function formOpen() {
	$('#additional-anggota').html('');
	var response = response_edit;
	if(typeof response.id != 'undefined') {
		if(response.id_coa_besaran > 0) {
			$.each(response.id_coa_besaran, function(k,v){
				$('#id_coa_besaran').find('[value="'+v+'"]').prop('selected',true);
			});
			$('#id_coa_besaran').trigger('change');
		}
		$.each(response.detail,function(e,d){
			if(e == '0') {
				$('#bulan').val(d.bulan).trigger('change');
				$('#tahun').val(d.tahun);
				$('#sumber_data').val(d.sumber_data).trigger('change');
			} else {
				add_row_anggota();
				$('#additional-anggota .bulan').last().val(d.bulan).trigger('change');
				$('#additional-anggota .tahun').last().val(d.tahun);
				$('#additional-anggota .sumber_data').last().val(d.sumber_data).trigger('change');
			}
		});
	}
}


$(document).on('click','.btn-usulan-besaran',function(){
//	get_grup()
	$('.table-ap tbody').html('');
	$('#modal-usulan-besaran').modal();
	$('#modal-usulan-besaran .modal-title').text('Edit template');
	$.ajax({
		url : base_url + 'settings/tahun_anggaran/get_data_usulan',
   		data : {'__id' : $(this).attr('data-id')},
			type : 'POST',
			success	: function(response) {
				$('#id_anggaran').val(response.id);
				var konten  ='';
				var konten1 ='';
				var no = 0;
					$.each(response.grup,function(e,d){

						$.each(response.detail,function(e1,d1){
						if(d.grup == d1.grup) {
							no++
							konten += '<tr>';
							konten += '<td>'+no+'</td>';
							konten += '<td><input type="text" autocomplete="off" class="form-control keterangan" name="keterangan['+d1.id+']" value ="'+d1.keterangan+'"aria-label="" data-validation=""/></td>';
							konten += '<td width="150"><select " class="form-control pilihan grup" name="grup['+d1.id+']" id="grup'+d1.id+'" value ="'+d1.grup+'" data-validation="required" aria-label="'+$('#grup').attr('aria-label')+'">'+grup+'</select></td>'
					//		konten += '<td><input type="text" autocomplete="off" class="form-control grup" name="grup['+d1.id+']" value ="'+d1.grup+'"aria-label="" data-validation=""/></td>';
							konten += '<td>'+d1.coa+'</td>';
							konten += '<td width="60"><input type="text" autocomplete="off" class="form-control urutan" name="urutan['+d1.id+']" value ="'+d1.urutan+'"aria-label="" data-validation=""/></td>';
							konten += '</tr>';

							$('.table-ap tbody').html(konten);
							var $t = $('#result2 .pilihan:last-child');
							$t.select2({
								dropdownParent : $t.parent(),
								placeholder : ''
							});
							var f = $('#result2 tbody tr').last();
							f.find('.grup').val('DPK').trigger('change');

						//	$('.grup').val(d1.grup).trigger('change');

	
						}	

						});
						konten += '<tr>';
						konten += '<th colspan="5" style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;"><font color="#fff">TOTAL '+d.grup+'</th>';	
						konten += '</tr>';
						$('.table-ap tbody').html(konten);
							var $t = $('#result2 .pilihan:last-child');
							$t.select2({
								dropdownParent : $t.parent(),
								placeholder : ''
							});
					//		var f = $('#result2 tbody tr').last();
					//		f.find('.grup').val(d.grup).trigger('change');

					});

				$.each(response.detail,function(e1,d1){
					$('#grup'+d1.id).val(d1.grup).trigger('change');
				});
            }
	});
});

function get_grup() {
	if(proccess) {
	//	readonly_ajax = false;
		$.ajax({
			url : base_url + 'settings/tahun_anggaran/get_grup',
			data : {},
			type : 'POST',
			success	: function(response) {
				grup = response;
	//			readonly_ajax = true;				
			}
		});
	}
}

</script>