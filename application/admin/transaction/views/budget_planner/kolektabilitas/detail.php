<h5 class="mt-3"><?= $title_[$tipe].$title[$tipe] ?></h5>

<!-- Total -->
<?php 
$arrTotal = [];
$arrDataSaved = [];
$status_update= false;
foreach ($listTotal as $k => $v) {  $no = 0; if($v->tipe == $tipe): $no++; ?>
<div class="card mt-3">
	<div class="card-header"><?= 'Total '.$title[$tipe]; ?></div>
	<div class="card-body">
		<div class="table-responsive tab-pane fade active show" id="result2">
			<table class="table table-striped table-bordered table-app table-hover">
				<thead>
					<tr>
						<th colspan="10"><?= get_view_report() ?></th>
					</tr>
					<tr>
						<th rowspan="2" width="30" class="text-center align-middle"><?= lang('no') ?></th>
						<th rowspan="2" class="text-center align-middle"><?= lang('bulan') ?></th>
						<th rowspan="2" class="text-center align-middle"><?= lang('total_krd') ?></th>
						<th colspan="5" class="text-center align-middle"><?= lang('kolektabilitas') ?></th>
						<th rowspan="2" width="150px" class="text-center align-middle">KRD BERMASALAH</th>
						<th rowspan="2" width="150px" class="text-center align-middle"><?= lang('npl').' (%)' ?></th>
					</tr>
					<tr>
						<th width="150px" class="text-center align-middle">1</th>
						<th width="150px" class="text-center align-middle">2</th>
						<th width="150px" class="text-center align-middle">3</th>
						<th width="150px" class="text-center align-middle">4</th>
						<th width="150px" class="text-center align-middle">5</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach ($detail_tahun as $k2 => $v2) {
					$bgedit ="";
					$contentedit ="false" ;
					$id = 'keterangan';
					if($akses_ubah == 1) {
						$bgedit = bgEdit();
						$contentedit ="true" ;
						$id = 'id' ;
					}

					$v_field  = 'B_' . sprintf("%02d", $v2->bulan);
					$v_field2 = 'hasil'.$v2->bulan;
					$column = month_lang($v2->bulan).' '.$v2->tahun;
					$column .= '('.$v2->singkatan.')';

					$p1 = [
						'id' => $v->id, 
						'cabang' => $current_cabang, 
						'tahun_core' => $v2->tahun, 
						'tahun' => $anggaran->tahun_anggaran
					];
					$d = checkRealisasiKolektibilitas($p1,$listTotalDetail);

					$kredit = $d[$v_field2];

					$changed = [];
					if($d['changed']):
						$changed = json_decode($d['changed']);
					endif;

					if($v2->singkatan == arrSumberData()['real']):
						$kredit = 0;
						if(isset($data_core[$v2->tahun])):
							$core_key = multidimensional_search($data_core[$v2->tahun], array(
								'glwnco' => $v->coa_produk_kredit,
							));
							if(strlen($core_key)>0):
								$kredit = $data_core[$v2->tahun][$core_key][$v_field];
								$minus  = $data_core[$v2->tahun][$core_key]['kali_minus'];
								$kredit = kali_minus($kredit,$minus);
							endif;
						endif;
					endif;
					if(!$kredit): $kredit = 0; endif;

					// $kredit = 3000000 + (100000*$k2);

					$item = '<tr>';
					$item .= '<td>'.($k2+1).'</td>';
					$item .= '<td>'.$column.'</td>';
					$item .= '<td class="text-right">'.check_value($kredit).'</td>';
					
					// get npl
					$keyNpl = multidimensional_search($listNpl, array(
						'tahun_core' => $v2->tahun,
						'tipe'	=> $v->tipe,
					));
					$dNpl = $listNpl[$keyNpl];
					$npl  = ($kredit*$dNpl[$v_field])/100;
					// end get npl

					$item_1_temp = 0;
					$item_2= '';
					$item_4= '';
					$item_5= '';
					$total = 0;
					$total_npl = 0;
					$col_2 = 0;
					$col_3 = 0;
					$col_4 = 0;
					$col_5 = 0;
					$id = $d['id']."-tbl_kolektibilitas_detail";
					for ($i=1; $i <=5 ; $i++) { 
						if(in_array($i, array(3,4,5))):
							$total_npl += $d[$v_field.'_'.$i];
						endif;

						if($i == 2):
							$col_2 = $d[$v_field.'_'.$i];
							if($col_2 == 0):
								$col_2 = $kredit * 0.05;
							endif;
							$item_2 .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="'.$v_field.'_'.$i.'" data-id="'.$id.'" data-value="'.$d[$v_field.'_'.$i].'">'.check_value($d[$v_field.'_'.$i]).'</div></td>';
						elseif($i == 4):
							$col_4 = ($npl*0.3);
							if(in_array($v_field.'_'.$i, $changed)):
								$col_4 = $d[$v_field.'_'.$i];
							endif;
							$item_4 .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="'.$v_field.'_'.$i.'" data-id="'.$id.'" data-value="'.$col_4.'">'.check_value($col_4).'</div></td>';
						elseif($i == 5):
							$col_5 = ($npl*0.2);
							if(in_array($v_field.'_'.$i, $changed)):
								$col_5 = $d[$v_field.'_'.$i];
							endif;
							$item_5 .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="'.$v_field.'_'.$i.'" data-id="'.$id.'" data-value="'.$col_5.'">'.check_value($col_5).'</div></td>';
						endif;
					}

					$col_3 		= $npl-($col_4+$col_5);
					$item_1 	= $kredit - ($col_2+$col_3+$col_4+$col_5);
					$total_npl = ($kredit!=0)?($npl/$kredit)*100:0;

					if($kredit != $d[$v_field]):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field] = $kredit;
					endif;
					if($item_1 != $d[$v_field.'_1']):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field.'_1'] = $item_1;
					endif;
					if($col_2 != $d[$v_field.'_2']):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field.'_2'] = $col_2;
					endif;
					if($col_3 != $d[$v_field.'_3']):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field.'_3'] = $col_3;
					endif;
					if($col_4 != $d[$v_field.'_4']):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field.'_4'] = $col_4;
					endif;
					if($col_5 != $d[$v_field.'_5']):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field.'_5'] = $col_5;
					endif;
					if($npl != $d[$v_field.'_bermasalah']):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field.'_bermasalah'] = $npl;
					endif;
					if($total_npl != $d[$v_field.'_npl']):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field.'_npl'] = $total_npl;
					endif;

					$item .= '<td class="text-right">'.check_value($item_1).'</td>';
					$item .= $item_2;
					$item .= '<td class="text-right">'.check_value($col_3).'</td>';
					$item .= $item_4;
					$item .= $item_5;
					$item .= '<td class="text-right">'.check_value($npl).'</td>';
					$item .= '<td class="text-right">'.custom_format($total_npl,false,2).'</td>';
					$item .= '</tr>';

					$arrTotal[$v_field.'_'.$v2->tahun] = [
						'kredit' 	=> $kredit,
						'col_1' 	=> $item_1,
						'col_2' 	=> $col_2,
						'col_3'		=> $col_3,
						'col_4'		=> $col_4,
						'col_5'		=> $col_5,
						'npl'		=> $npl,
						'total_npl'	=> $total_npl,
					];

					echo $item;
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php endif; }
save_kolektibilitas_detail($arrDataSaved,$status_update);
?>

<!-- Detail -->
<?php 
$arrDetail = [];
$item_detail = '';
$arrDataSaved= [];
$status_update= false;
$no = 1; foreach ($list as $k => $v) { if($v->tipe == $tipe): $no++;
$item_detail .= '<div class="card mt-3">
	<div class="card-header">'.$no.'. '.remove_spaces($v->nama_produk_kredit).' ( '.$v->coa.' )</div>
	<div class="card-body">
		<div class="table-responsive tab-pane fade active show" id="result2">
			<table class="table table-striped table-bordered table-app table-hover">
				<thead>
					<tr>
						<th colspan="10">'.get_view_report().'</th>
					</tr>
					<tr>
						<th rowspan="2" width="30" class="text-center align-middle">'.lang('no').'</th>
						<th rowspan="2" class="text-center align-middle">'.lang('bulan').'</th>
						<th rowspan="2" class="text-center align-middle">'.lang('total_krd').'</th>
						<th colspan="5" class="text-center align-middle">'.lang('kolektabilitas').'</th>
						<th rowspan="2" width="150px" class="text-center align-middle">KRD BERMASALAH</th>
						<th rowspan="2" width="150px" class="text-center align-middle">'.lang('npl').' (%)</th>
					</tr>
					<tr>
						<th width="150px" class="text-center align-middle">1</th>
						<th width="150px" class="text-center align-middle">2</th>
						<th width="150px" class="text-center align-middle">3</th>
						<th width="150px" class="text-center align-middle">4</th>
						<th width="150px" class="text-center align-middle">5</th>
					</tr>
				</thead>
				<tbody>';

				foreach ($detail_tahun as $k2 => $v2) {
					$bgedit ="";
					$contentedit ="false" ;
					$id = 'keterangan';
					if($akses_ubah == 1) {
						$bgedit = bgEdit();
						$contentedit ="true" ;
						$id = 'id' ;
					}

					$v_field  = 'B_' . sprintf("%02d", $v2->bulan);
					$v_field2  = 'P_' . sprintf("%02d", $v2->bulan);
					$column = month_lang($v2->bulan).' '.$v2->tahun;
					$column .= '('.$v2->singkatan.')';

					$key = multidimensional_search($detail, array(
						'tahun_core' => $v2->tahun,
						'id_kolektibilitas' => $v->id,
					));
					$d = $detail[$key];
					$kredit = $d[$v_field2];
					if($v2->singkatan == arrSumberData()['real']):
						$kredit = 0;
						if(isset($data_core[$v2->tahun])):
							$core_key = multidimensional_search($data_core[$v2->tahun], array(
								'glwnco' => $v->coa,
							));
							if(strlen($core_key)>0):
								$kredit = $data_core[$v2->tahun][$core_key][$v_field];
								$minus  = $data_core[$v2->tahun][$core_key]['kali_minus'];
								$kredit = kali_minus($kredit,$minus);
							endif;
						endif;
					endif;

					$changed = [];
					if($d['changed']):
						$changed = json_decode($d['changed']);
					endif;

					$item = '<tr>';
					$item .= '<td>'.($k2+1).'</td>';
					$item .= '<td>'.$column.'</td>';
					$item .= '<td class="text-right">'.check_value($kredit).'</td>';
					
					$keyTotal = $v_field.'_'.$v2->tahun;
					// $npl = ($kredit/$arrTotal[$keyTotal]['kredit'])*$arrTotal[$keyTotal]['npl'];
					$npl = ($arrTotal[$keyTotal]['kredit']!=0)?($kredit/$arrTotal[$keyTotal]['kredit'])*$arrTotal[$keyTotal]['npl']:0;

					$item_1_temp = 0;
					$item_2= '';
					$item_4= '';
					$item_5= '';
					$total = 0;
					$total_npl = 0;
					$col_2 = 0;
					$col_3 = 0;
					$col_4 = 0;
					$col_5 = 0;
					for ($i=1; $i <=5 ; $i++) { 
						$id 	= $d['id']."-tbl_kolektibilitas_detail";
						if($i == 2):
							$total += $d[$v_field.'_'.$i];
							if(in_array($i, array(3,4,5))):
								$total_npl += $d[$v_field.'_'.$i];
							endif;
							$col_2 = ($kredit - $npl) * 0.25;
							if(in_array($v_field.'_'.$i, $changed)):
								$col_2 	= $d[$v_field.'_'.$i];
							endif;
							$item_2 .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="'.$v_field.'_'.$i.'" data-id="'.$id.'" data-value="'.$d[$v_field.'_'.$i].'">'.check_value($d[$v_field.'_'.$i]).'</div></td>';
						elseif($i == 3):
						elseif($i == 4):
							$col_4 = $npl*0.3;
							if(in_array($v_field.'_'.$i, $changed)):
								$col_4 = $d[$v_field.'_'.$i];
							endif;
							$item_4 .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="'.$v_field.'_'.$i.'" data-id="'.$id.'" data-value="'.$col_4.'">'.check_value($col_4).'</div></td>';
						elseif($i == 5):
							$col_5 = $npl*0.2;
							if(in_array($v_field.'_'.$i, $changed)):
								$col_5 = $d[$v_field.'_'.$i];
							endif;
							$item_5 .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="'.$v_field.'_'.$i.'" data-id="'.$id.'" data-value="'.$col_5.'">'.check_value($col_5).'</div></td>';
						endif;

						if($i == 1):
							$item_1_temp = $d[$v_field.'_'.$i];
						endif;
					}

					$col_3 		= $npl - ($col_4+$col_5);

					$item_1 	= $kredit - ($col_2+$col_3+$col_4+$col_5);
					$total_npl = ($kredit!=0)?($npl/$kredit)*100:0;

					if(isset($arrDetail[$keyTotal])):
						$arrDetail[$keyTotal]['kredit'] += $kredit;
						$arrDetail[$keyTotal]['col_1']  += $item_1;
						$arrDetail[$keyTotal]['col_2']  += $col_2;
						$arrDetail[$keyTotal]['col_3']  += $col_3;
						$arrDetail[$keyTotal]['col_4']  += $col_4;
						$arrDetail[$keyTotal]['col_5']  += $col_5;
						$arrDetail[$keyTotal]['col_5']  += $col_5;
						$arrDetail[$keyTotal]['npl']  	+= $npl;
					else:
						$arrDetail[$keyTotal]['kredit'] = $kredit;
						$arrDetail[$keyTotal]['col_1']  = $item_1;
						$arrDetail[$keyTotal]['col_2']  = $col_2;
						$arrDetail[$keyTotal]['col_3']  = $col_3;
						$arrDetail[$keyTotal]['col_4']  = $col_4;
						$arrDetail[$keyTotal]['col_5']  = $col_5;
						$arrDetail[$keyTotal]['col_5']  = $col_5;
						$arrDetail[$keyTotal]['npl']  	= $npl;
					endif;

					if($kredit != $d[$v_field]):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field] = $kredit;
					endif;
					if($item_1 != $d[$v_field.'_1']):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field.'_1'] = $item_1;
					endif;
					if($col_2 != $d[$v_field.'_2']):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field.'_2'] = $col_2;
					endif;
					if($col_3 != $d[$v_field.'_3']):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field.'_3'] = $col_3;
					endif;
					if($col_4 != $d[$v_field.'_4']):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field.'_4'] = $col_4;
					endif;
					if($col_5 != $d[$v_field.'_5']):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field.'_5'] = $col_5;
					endif;
					if($npl != $d[$v_field.'_bermasalah']):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field.'_bermasalah'] = $npl;
					endif;
					if($total_npl != $d[$v_field.'_npl']):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field.'_npl'] = $total_npl;
					endif;

					$item .= '<td class="text-right">'.check_value($item_1).'</td>';
					$item .= $item_2;
					$item .= '<td class="text-right">'.check_value($col_3).'</td>';
					$item .= $item_4;
					$item .= $item_5;
					$item .= '<td class="text-right">'.check_value($npl).'</td>';
					$item .= '<td class="text-right">'.custom_format($total_npl,false,2).'</td>';
					$item .= '</tr>';

					$item_detail .= $item;
				}
				$item_detail .= '</tbody>
			</table>
		</div>
	</div>
</div>';
endif; }
save_kolektibilitas_detail($arrDataSaved,$status_update);
?>
<!-- End Detail -->

<!-- Default -->
<?php
$status_update = false;
$arrDataSaved = [];
foreach ($listDefault as $k => $v) {  $no = 0; if($v->tipe == $tipe): $no++; ?>
<div class="card mt-3">
	<div class="card-header"><?= $no.'. '.remove_spaces($v->nama_produk_kredit).' ( '.$v->coa.' ) ' ?><span class="badge badge-success">Default</span></div>
	<div class="card-body">
		<div class="table-responsive tab-pane fade active show" id="result2">
			<table class="table table-striped table-bordered table-app table-hover">
				<thead>
					<tr>
						<th colspan="10"><?= get_view_report() ?></th>
					</tr>
					<tr>
						<th rowspan="2" width="30" class="text-center align-middle"><?= lang('no') ?></th>
						<th rowspan="2" class="text-center align-middle"><?= lang('bulan') ?></th>
						<th rowspan="2" class="text-center align-middle"><?= lang('total_krd') ?></th>
						<th colspan="5" class="text-center align-middle"><?= lang('kolektabilitas') ?></th>
						<th rowspan="2" width="150px" class="text-center align-middle">KRD BERMASALAH</th>
						<th rowspan="2" width="150px" class="text-center align-middle"><?= lang('npl').' (%)' ?></th>
					</tr>
					<tr>
						<th width="150px" class="text-center align-middle">1</th>
						<th width="150px" class="text-center align-middle">2</th>
						<th width="150px" class="text-center align-middle">3</th>
						<th width="150px" class="text-center align-middle">4</th>
						<th width="150px" class="text-center align-middle">5</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach ($detail_tahun as $k2 => $v2) {
					$bgedit ="";
					$contentedit ="false" ;
					$id = 'keterangan';
					if($akses_ubah == 1) {
						$bgedit = bgEdit();
						$contentedit ="true" ;
						$id = 'id' ;
					}

					$v_field   = 'B_' . sprintf("%02d", $v2->bulan);
					$v_field2  = 'P_' . sprintf("%02d", $v2->bulan);
					$column = month_lang($v2->bulan).' '.$v2->tahun;
					$column .= '('.$v2->singkatan.')';

					$key = multidimensional_search($detail, array(
						'tahun_core' => $v2->tahun,
						'id_kolektibilitas' => $v->id,
					));
					$d = $detail[$key];

					$changed = [];
					if($d['changed']):
						$changed = json_decode($d['changed']);
					endif;

					$keyTotal = $v_field.'_'.$v2->tahun;
					$kredit = $d[$v_field2];
					if($v2->singkatan == arrSumberData()['real']):
						$kredit = 0;
						if(isset($data_core[$v2->tahun])):
							$core_key = multidimensional_search($data_core[$v2->tahun], array(
								'glwnco' => $v->coa,
							));
							if(strlen($core_key)>0):
								$kredit = $data_core[$v2->tahun][$core_key][$v_field];
								$minus  = $data_core[$v2->tahun][$core_key]['kali_minus'];
								$kredit = kali_minus($kredit,$minus);
							endif;
						endif;
					endif;
					$col_1 	= $arrTotal[$keyTotal]['col_1'];
					$col_2 	= $arrTotal[$keyTotal]['col_2'];
					// $col_2 	= $d[$v_field.'_2'];
					$col_3 	= $arrTotal[$keyTotal]['col_3'];
					$col_4 	= $arrTotal[$keyTotal]['col_4'];
					$col_5 	= $arrTotal[$keyTotal]['col_5'];
					$npl 	= $arrTotal[$keyTotal]['npl'];

					if(isset($arrDetail[$keyTotal])):
						// $kredit -= $arrDetail[$keyTotal]['kredit'];
						$col_1 	-= $arrDetail[$keyTotal]['col_1'];
						$col_2 	-= $arrDetail[$keyTotal]['col_2'];
						$col_3 	-= $arrDetail[$keyTotal]['col_3'];
						$col_4 	-= $arrDetail[$keyTotal]['col_4'];
						$col_5 	-= $arrDetail[$keyTotal]['col_5'];
						$npl  	-= $arrDetail[$keyTotal]['npl'];
					endif;

					$total_npl = ($kredit!=0)?($npl/$kredit)*100:0;

					if($kredit != $d[$v_field]):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field] = $kredit;
					endif;
					if($col_1 != $d[$v_field.'_1']):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field.'_1'] = $col_1;
					endif;
					if($col_2 != $d[$v_field.'_2']):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field.'_2'] = $col_2;
					endif;
					if($col_3 != $d[$v_field.'_3']):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field.'_3'] = $col_3;
					endif;
					if($col_4 != $d[$v_field.'_4']):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field.'_4'] = $col_4;
					endif;
					if($col_5 != $d[$v_field.'_5']):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field.'_5'] = $col_5;
					endif;
					if($npl != $d[$v_field.'_bermasalah']):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field.'_bermasalah'] = $npl;
					endif;
					if($total_npl != $d[$v_field.'_npl']):
						$status_update = true;
						$arrDataSaved[$d['id']][$v_field.'_npl'] = $total_npl;
					endif;

					$item = '<tr>';
					$item .= '<td>'.($k2+1).'</td>';
					$item .= '<td>'.$column.'</td>';
					$item .= '<td class="text-right">'.check_value($kredit).'</td>';
					$item .= '<td class="text-right">'.check_value($col_1).'</td>';
					$item .= '<td class="text-right">'.check_value($col_2).'</td>';
					// $id 	= $d['id']."-tbl_kolektibilitas_detail";
					// $item .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="'.$v_field.'_2" data-id="'.$id.'" data-value="'.custom_format($d[$v_field.'_2']).'">'.custom_format($d[$v_field.'_2']).'</div></td>';
					$item .= '<td class="text-right">'.check_value($col_3).'</td>';
					$item .= '<td class="text-right">'.check_value($col_4).'</td>';
					$item .= '<td class="text-right">'.check_value($col_5).'</td>';
					$item .= '<td class="text-right">'.check_value($npl).'</td>';
					$item .= '<td class="text-right">'.custom_format($total_npl,false,2).'</td>';
					$item .= '</tr>';

					echo $item;
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php endif; }
save_kolektibilitas_detail($arrDataSaved,$status_update);
?>
<!-- Default -->

<?= $item_detail; ?>

