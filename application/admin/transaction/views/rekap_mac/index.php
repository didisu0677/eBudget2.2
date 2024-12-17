<?php
	function select_custom($label,$id,$data,$opt_key,$opt_name,$value=""){
		echo '<label>'.$label.' &nbsp</label>';
		$select = '<select class="select2 custom-select" id="'.$id.'">';
		foreach ($data as $v) {
			$selected = '';if($v[$opt_key] == $value): $selected = ' selected'; endif;
			$select .= '<option value="'.$v[$opt_key].'"'.$selected.'>'.remove_spaces($v[$opt_name]).'</option>';
		}
		$select .= '</select> &nbsp';
		echo $select;
	}
?>
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
		background-color: #cacaca;
	}
	.r-45{
		transform: rotate(45deg);
	}
	.r-45-{
		transform: rotate(-45deg);
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
			input('hidden',lang('user'),'user_cabang','',user('kode_cabang'));
			select_custom(lang('anggaran'),'filter_tahun',$tahun,'kode_anggaran','keterangan', user('kode_anggaran'));
			select_custom(lang('coa'),'filter_coa',$coa,'coa','name');
			select_custom(lang('bulan'),'filter_bulan',$bulan,'value','name');
			?>
			
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<div class="d-content"></div>
	<div class="main-container mb-3">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header">
						<?= lang('keterangan') ?>
					</div>
					<div class="card-body">
						<div class="row">
							<?php
								foreach ($keterangan as $k => $v) {
									echo '<div class="col-sm-2">
										<img height="12" src="'.$path_file.$v['image'].'"/> '.$v['nama'].'
									</div>';
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
var controller = '<?= $controller ?>';
$(document).ready(function () {
	getContent();
});
$('#filter_tahun').change(function(){getContent();});
$('#filter_coa').change(function(){getContent();});
$('#filter_bulan').change(function(){getContent();});
function getContent(){
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/'+controller+'/get_content';
	
	var tahun 	= $('#filter_tahun option:selected').val();
	var bulan 	= $('#filter_bulan option:selected').val();
	var coa 	= $('#filter_coa option:selected').val();

	var classnya = 'd-'+bulan+'-'+coa;
	var length = $('body').find('.'+classnya).length;
	var length_body = $('body').find('.d-content-body').length;

	if(length_body>0){
		$('body').find('.d-content-body').hide(300);
	}

	if(length<=0){
		$.ajax({
			url 	: page,
			data 	: {
				tahun 	: tahun,
				bulan 	: bulan,
				coa 	: coa,
			},
			type	: 'post',
			dataType: 'json',
			success	: function(response) {
				$('.d-content').append('<div class="d-content-body '+classnya+'"></div>');
				$('body').find('.'+classnya).html(response.view);
				cLoader.close();
				resize_window();
				getData(tahun,bulan,coa);
			}
		});
	}else{
		$('body').find('.'+classnya).show(300);
		cLoader.close();
	}
}
function getData(tahun,bulan,coa){
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/'+controller+'/data';
	var classnya = 'd-'+bulan+'-'+coa;
	$.ajax({
		url 	: page,
		data 	: {
			tahun 	: tahun,
			bulan 	: bulan,
			coa 	: coa,
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
</script>