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
		min-width: 350px !important;
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
	.mt-6{
		margin-top: 5em;
	}
	.select2-selection__rendered{
		text-align: left !important;
	}
</style>
<div class="content-header page-data" data-additional="<?= $access_additional ?>" style="height: auto;">
	<div class="main-container position-relative">
		<div class="header-info" style="position: relative;">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right mt-3 text-right">
			<?php
			input('hidden',lang('user'),'user_cabang','',user('kode_cabang'));
			select_custom(lang('anggaran'),'filter_tahun',$tahun,'kode_anggaran','keterangan', user('kode_anggaran'));
			select_custom(lang('coa'),'filter_coa',$coa,'coa','name',$p_coa);
			select_custom(lang('bulan'),'filter_bulan',$bulan,'value','name');
			echo '<div class="mt-2">'.filter_cabang_admin($access_additional,$cabang).'</div>';
			?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body mt-6">
	<div class="d-content mt-6"></div>
</div>
<script type="text/javascript">
var controller = '<?= $controller ?>';
$(document).ready(function () {
	getContent();
});
$('#filter_tahun').change(function(){getContent();});
$('#filter_coa').change(function(){getContent();});
$('#filter_bulan').change(function(){getContent();});
$('#filter_cabang').change(function(){getContent();});
function getContent(){
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/'+controller+'/get_content';
	
	var tahun 	= $('#filter_tahun option:selected').val();
	var bulan 	= $('#filter_bulan option:selected').val();
	var coa 	= $('#filter_coa option:selected').val();
	var cabang 	= $('#filter_cabang option:selected').val();

	if(!cabang){
		return '';
	}

	var classnya = 'd-'+bulan+'-'+coa+'-'+cabang;
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
				cabang 	: cabang,
			},
			type	: 'post',
			dataType: 'json',
			success	: function(response) {
				$('.d-content').append('<div class="d-content-body '+classnya+'"></div>');
				$('body').find('.'+classnya).html(response.view);
				cLoader.close();
				resize_window();
				getData(tahun,bulan,coa,cabang);
			}
		});
	}else{
		$('body').find('.'+classnya).show(300);
		cLoader.close();
	}
}
function getData(tahun,bulan,coa,cabang){
	cLoader.open(lang.memuat_data + '...');
	var page = base_url + 'transaction/'+controller+'/data';
	var cabang 	= $('#filter_cabang option:selected').val();
	var classnya = 'd-'+bulan+'-'+coa+'-'+cabang;
	$.ajax({
		url 	: page,
		data 	: {
			tahun 	: tahun,
			bulan 	: bulan,
			coa 	: coa,
			cabang 	: cabang,
		},
		type	: 'post',
		dataType: 'json',
		success	: function(response) {
			$('body').find('.'+classnya+' .table-app tbody').html(response.view);
			checkSubData2(classnya);
			cLoader.close();
			var length = $('body').find('.'+classnya).length;
			if(length>=2){
				$('body').find('.'+classnya).eq(0).remove();
			}
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