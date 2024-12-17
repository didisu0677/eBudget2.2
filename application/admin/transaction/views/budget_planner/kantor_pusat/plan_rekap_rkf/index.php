<style type="text/css">
	.wd-100{
		width: 100px !important;
		min-width: 100px !important;
		max-width: 100px !important;
	}
	.wd-150{
		width: 150px !important;
		min-width: 150px !important;
		max-width: 150px !important;
	}
	.wd-230{
		width: 350px !important;
		min-width: 350px !important;
		max-width: 350px !important;
	}
	.d-bg-header th{
		/*background-color: #e64a19 !important;*/
	}
	.d-bg-header span{
		/*color: #fff !important;*/
	}
	.d-bg-header red{
		color: #f7f7f7 !important;
	}
</style>
<div class="content-header  page-data" data-additional="<?= $access_additional ?>" data-type="divisi" data-status_group="<?= $status_group ?>">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<label class=""><?php echo lang('anggaran'); ?>  &nbsp</label>
			<select class="select2 infinity custom-select" id="filter_anggaran">
				<?php foreach ($tahun as $tahun) { ?>
                <option value="<?php echo $tahun->kode_anggaran; ?>"<?php if($tahun->kode_anggaran == user('kode_anggaran')) echo ' selected'; ?>><?php echo $tahun->keterangan; ?></option>
                <?php } ?>
			</select>
			<?php
			input('hidden',lang('user'),'user_cabang','',user('kode_cabang'));
			echo $option;
			$arr = [
			    ['btn-export','Export Data','fa-upload'],
			];
			echo access_button('',$arr);
			?>
		</div>
		<div class="clearfix"></div>
	</div>
	<?php $this->load->view($sub_menu); ?>
</div>
<div class="content-body mt-6">
<?php $this->load->view($sub_menu); ?>
	<div class="main-container mt-2">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header text-center"><?= $title ?> <br>(<?= get_view_report() ?>)</div>
	    			<div class="card-body">
	    				<div class="table-responsive tab-pane fade active show height-window" id="result">
	    					<?php 
							table_open('',false);
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
var status_group = 0;
var controller = '<?= $controller ?>';
$(document).ready(function(){
	resize_window();
	var page_data = $('.page-data').data();
	if(page_data && page_data.status_group == 1){
		status_group = 1;
	}
	if(status_group == 1){
		$('.l-cabang').hide();
		$('#filter_cabang').next(".select2-container").hide();
	}
	getData();
})
$('#filter_tahun').change(function(){getData();});
$('#filter_cabang').change(function(){
	if(status_group == 0){
		getData();
	}
});
$('#filter_cabang_induk').change(function(){
	if(status_group == 1){
		getData();
	}
});
var xhr_ajax = null;
function getData(){
	var cabang = $('#filter_cabang_induk option:selected').val();
	if(status_group == 0){
		cabang = $('#filter_cabang option:selected').val();
	}
	if(!cabang){ return ''; }
	$('.table-app').empty('');	
    if( xhr_ajax != null ) {
        xhr_ajax.abort();
        xhr_ajax = null;
    }
    cLoader.open(lang.memuat_data + '...');
    var page = base_url + 'transaction/'+controller+'/data';
    page += '/'+ $('#filter_anggaran').val();
    page += '/'+ cabang
  	xhr_ajax = $.ajax({
        url: page,
        type: 'post',
		data : { status_group : status_group },
        dataType: 'json',
        success: function(res){
        	xhr_ajax = null;
        	$('.table-app').append(res.view);
        	cLoader.close();
        	checkSubData();
		}
    });
}
$(document).on('click','.btn-export',function(){
    var hashids = new Hashids(encode_key);
    var x = hashids.decode($('meta[name="csrf-token"]').attr('content'));
    var cabang = $('#filter_cabang_induk option:selected').val();
	if(status_group == 0){
		cabang = $('#filter_cabang option:selected').val();
	}
    var dt_neraca = get_data_table('#result');
    var arr_neraca = dt_neraca['arr'];
    var arr_neraca_header = dt_neraca['arr_header'];

    var post_data = {
        "neraca_header" : JSON.stringify(arr_neraca_header),
        "neraca"        : JSON.stringify(arr_neraca),
        "kode_anggaran" : $('#filter_anggaran option:selected').val(),
        "kode_anggaran_txt" : $('#filter_anggaran option:selected').text(),
        "csrf_token"    : x[0],
    }
    var url = base_url + 'transaction/'+controller+'/export';
    $.redirect(url,post_data,"","_blank");
});
function get_data_table(classnya){
    var arr = [];
    var arr_header = [];
    var no = 0;
    var index_cabang = 0;
    $(classnya+" table tr").each(function() {
        var arrayOfThisRowHeader = [];
        var tableDataHeader = $(this).find('th');
        if (tableDataHeader.length > 0) {
            if(no == 0){
                arrayOfThisRowHeader.push("");
                arrayOfThisRowHeader.push("");
                arrayOfThisRowHeader.push("");
                arrayOfThisRowHeader.push("");
                tableDataHeader.each(function(k,v) {
                    var val = $(this).text();
                    if(val && val != '-'){
                        if(index_cabang != 0){
                            arrayOfThisRowHeader.push("");
                        }
                        index_cabang++;
                        arrayOfThisRowHeader.push($(this).text());
                        for (var i = 1; i <= 11; i++) {
                            arrayOfThisRowHeader.push("");
                        }
                    }
                });
                arr_header.push(arrayOfThisRowHeader);
            }

            if(no == 1){
                tableDataHeader.each(function(k,v) {
                    var val = $(this).text();
                    arrayOfThisRowHeader.push($(this).text());
                });
                arr_header.push(arrayOfThisRowHeader);

                arr.push(arrayOfThisRowHeader);
            }
            no++; 
        }

        var arrayOfThisRow = [];
        var tableData = $(this).find('td');
        if (tableData.length > 0) {
            tableData.each(function() {
                var val = $(this).text();
                if($(this).hasClass('sb-1')){
                    val = '     '+$(this).text();
                }else if($(this).hasClass('sb-2')){
                    val = '          '+$(this).text();
                }else if($(this).hasClass('sb-3')){
                    val = '               '+$(this).text();
                }else if($(this).hasClass('sb-4')){
                    val = '                    '+$(this).text();
                }else if($(this).hasClass('sb-5')){
                    val = '                         '+$(this).text();
                }else if($(this).hasClass('sb-6')){
                    val = '                              '+$(this).text();
                }
                arrayOfThisRow.push(val); 
            });
            arr.push(arrayOfThisRow);
        }
    });
    return {'arr' : arr, 'arr_header' : arr_header};
}
</script>