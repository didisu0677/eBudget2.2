<style type="text/css">
	red{
		color:red;
	}
	.mw-100{
		min-width: 100px !important;
	}
	.mw-150{
		min-width: 200px !important;
	}
	.mw-250{
		min-width: 550px !important;
	}
	.t-sb-1{
		background-color: #cacaca !important;
	}
	.r-45{
		transform: rotate(45deg);
	}
	.r-45-{
		transform: rotate(-45deg);
	}
	.text-bold{
		font-weight: 500;
	}
</style>

<div class="content-header page-data">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb($title); ?>
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

			<select class="select2 custom-select" id="filter_coa">
				<?php foreach ($coa as $v) { ?>
                <option value="<?= $v->coa ?>"><?= str_replace('_', '.', $v->coa).' - '.remove_spaces($v->name) ?></option>
                <?php } ?>
			</select>

			<?php
                echo '<button class="btn btn-info btn-refresh" href="javascript:;" title="Digunakan untuk mengambil data dari server secara realtime" >Refresh Data </button>';
				$arr = [
				    ['btn-export','Export Data','fa-upload']
				];
				echo access_button('',$arr);
			?>
		</div>
		<div class="clearfix"></div>
	</div>
	<?php $this->load->view($submenu); ?>
</div>
<div class="content-body mt-6">
	<?php $this->load->view($submenu); ?>
	
	<div class="d-content"></div>
</div>
<script type="text/javascript">
var controller 	= '<?= $controller ?>';
var s_page 		= '<?= $page ?>';
var xhr_ajax = null;
$(document).ready(function(){
	resize_window();
	getContent();
})
$(document).on('click','.btn-refresh',function(){
	var coa 	= $('#filter_coa option:selected').val();
	var classnya = 'd-'+coa;
	var length = $('body').find('.'+classnya).length;
	if(length>0){
		$('body').find('.'+classnya).remove();
	}
	getContent();
});

$('#filter_anggaran').change(function(){getContent();});
$('#filter_coa').change(function(){getContent();});
function getContent(){
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/'+controller+'/get_content';
	
	var tahun 	= $('#filter_anggaran option:selected').val();
	var coa 	= $('#filter_coa option:selected').val();

	var classnya = 'd-'+coa;
	var length = $('body').find('.'+classnya).length;
	var length_body = $('body').find('.d-content-body').length;

	if(length_body>0){
		$('body').find('.d-content-body').hide(300);
	}

	if(length<=0){
		if( xhr_ajax != null ) {
            xhr_ajax.abort();
            xhr_ajax = null;
        }
		xhr_ajax = $.ajax({
			url 	: page,
			data 	: {
				tahun 	: tahun,
				coa 	: coa,
				page 	: s_page,
			},
			type	: 'post',
			dataType: 'json',
			success	: function(response) {
				xhr_ajax = null;
				$('.d-content').append('<div class="d-content-body '+classnya+'"></div>');
				$('body').find('.'+classnya).html(response.view);
				cLoader.close();
				resize_window();
				getData(tahun,coa);
			}
		});
	}else{
		$('body').find('.'+classnya).show(300);
		cLoader.close();
	}
}
function getData(tahun,coa){
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/'+controller+'/data';
	var classnya = 'd-'+coa;
	$.ajax({
		url 	: page,
		data 	: {
			tahun 	: tahun,
			coa 	: coa,
			page 	: s_page,
		},
		type	: 'post',
		dataType: 'json',
		success	: function(response) {
			$('body').find('.'+classnya+' .table-app tbody').html(response.view);
			$('body').find('.'+classnya+' .tbl-total tbody').html(response.total);
			checkSubData2(classnya);
			cLoader.close();
		}
	});
}
function checkSubData2(classnya){
	for (var i = 1; i <= 6; i++) {
		if($(document).find('.'+classnya+' .sb-'+i).length>0){
			var dt = $(document).find('.sb-'+i);
			$.each(dt,function(k,v){
				var text = $(v).text();
				text = text.replaceAll('|-----', "");
				$(v).text('|----- '+text);
			})
		}
	}
}

$(document).on('click','.btn-export',function(){
	var coa 	= $('#filter_coa option:selected').val();
	var classnya = 'd-'+coa;
	
	var hashids = new Hashids(encode_key);
    var x = hashids.decode($('meta[name="csrf-token"]').attr('content'));
    var dt_data = get_data_table('.'+classnya);
    var arr_data = dt_data['arr'];
    var arr_data_header = dt_data['arr_header'];

    var post_data = {
        "arr_data_header" : JSON.stringify(arr_data_header),
        "arr_data"        : JSON.stringify(arr_data),
        "kode_anggaran" : $('#filter_anggaran option:selected').val(),
        "kode_anggaran_txt" : $('#filter_anggaran option:selected').text(),
        "coa"	: coa,
        "page"	: s_page,
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
                tableDataHeader.each(function(k,v) {
                    var val = $(this).text();
                    if(val && val != '-'){
                        arrayOfThisRowHeader.push($(this).text());
                        for (var i = 1; i <= 13; i++) {
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