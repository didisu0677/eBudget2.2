<div class="content-header page-data" data-additional="<?= $access_additional ?>">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<?php
			input('hidden',lang('user'),'user_cabang','',user('kode_cabang'));
			?>
			<label class=""><?php echo lang('anggaran'); ?>  &nbsp</label>
			<select class="select2 infinity custom-select" id="filter_anggaran">
				<?php foreach ($tahun as $tahun) { ?>
                <option value="<?php echo $tahun->kode_anggaran; ?>"<?php if($tahun->kode_anggaran == user('kode_anggaran')) echo ' selected'; ?>><?php echo $tahun->keterangan; ?></option>
                <?php } ?>
			</select> 		

    		<?php 
    			echo filter_cabang_admin($access_additional,$cabang);
				$arr = [
					// ['btn-save','Save Data','fa-save'],
				    // ['btn-export','Export Data','fa-upload'],
				    // ['btn-import','Import Data','fa-download'],
				    // ['btn-template','Template Import','fa-reg-file-alt']
				];
				echo access_button('',$arr); 
			?>
    		</div>
			<div class="clearfix"></div>
	</div>
	<?php $this->load->view($path.'sub_menu'); ?>
</div>
<div class="content-body mt-6">
<?php $this->load->view($path.'sub_menu'); ?>
	<div class="main-container mt-2">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
	    			<div class="card-header text-center"><?= $title ?></div>
	    			<div class="card-body">
	    				<div class="table-responsive tab-pane fade active show height-window" id="result1" data-height="10">
						<?php 
							table_open('table table-bordered table-app table-hover table-1');
							thead();
								tr();
									th(get_view_report(),'','colspan="'.(4+12).'"');
								tr();
									th(lang('sandi bi'),'','width="60"  class="text-center align-middle headcol"');
									th(lang('coa 5'),'','width="60"  class="text-center align-middle headcol"');
									th(lang('coa 7'),'','width="60"  class="text-center align-middle headcol"');
									th(lang('keterangan'),'',' class="text-center align-middle headcol" style="width:auto;min-width:230px"');
									for ($i = 1; $i <= 12; $i++) { 
										$column = month_lang($i).' '.($tahun->tahun_anggaran-1);
										$column .= '<br> ('.arrSumberData()['real'].')';
										th($column,'','class="text-center" style="min-width:150px"');		
									}
							
							tbody();
						table_close();
						?>
						</div>
	    			</div>
	    		</div>
	    	</div>
	    </div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	resize_window();
	loadData();

});	

$('#filter_anggaran').change(function(){
	loadData();
});

$('#filter_cabang').change(function(){
	loadData();
});

var xhr_ajax = null;
function loadData(){
	$('#result1 tbody').html('');	
    if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }
    var cabang = $('#filter_cabang').val();
    if(cabang){
    	cLoader.open(lang.memuat_data + '...');
	    var page = base_url + 'transaction/laba_rugi_op/data/';
	    page += '/'+ $('#filter_anggaran').val();
	    page += '/'+ $('#filter_cabang').val();
	  	xhr_ajax = $.ajax({
	        url: page,
	        type: 'post',
			data : $('#form-filter').serialize(),
	        dataType: 'json',
	        success: function(res){
	        	xhr_ajax = null;
	            $('#result1 tbody').html(res.table);	
	            cLoader.close();
	            checkSubData();
			}
	    });
    }
  	
}
</script>