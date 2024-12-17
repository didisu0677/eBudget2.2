<?php
	$item = '';
	$x = explode('-', $arr_real[0]);
    $bln1 = $x[0];
    $bln1x = (int) $bln1;
    $x2 = explode('-', $arr_real[1]);
    $bln2 = $x2[0];
    $bln2x = (int) $bln2;

    $sum_kol  	= [];
    $individu 	= [];
    $arrSumPdBln  = [];
    $arrDataSaved = [];
    $arrDataInsert= [];
	$status_update= false;
	foreach ($arr_coa as $k => $c) {
		$bgedit ="";
		$contentedit ="false" ;
		$id = 'keterangan';
		if($akses_ubah == 1) {
			$bgedit ="#ffbb33";
			$contentedit ="true" ;
			$id = 'id' ;
		}
		$key = multidimensional_search($add_on, array(
			'coa' => $c,
		));
		if(strlen($key)>0):
			$status = true;
			$d = $add_on[$key];
			$core_1 = $d['core_'.$bln1];
			$core_2 = $d['core_'.$bln2];
			$coa_named 	= $d['name'];
			$gwlnco 	= $d['coa'];
			$changed= [];

			if(in_array($d['coa'], $to_month)):
				$item2 = '<tr>';
				$item2 .= '<td></td>';
				$item2 .= '<td>'.remove_spaces($coa_named).' PD BLN</td>';
				$item2 .= '<td></td>';
				$item2 .= '<td></td>';

				$arrPdBln = [];
				foreach ($detail_tahun as $k2 => $v2) {
					$field 	= 'B_' . sprintf("%02d", $v2->bulan);
					$fieldx = sprintf("%02d", $v2->bulan);
					$pd_bln = '01-'.$fieldx.'-'.$v2->tahun;
					$pd_bln = 'B_'.str_replace('-', '_', minusMonth($pd_bln,1));
					$bln_before = 0;
					$key2 = multidimensional_search($dt_total, array(
							'coa' 			=> $c.'_total',
							'fsumber_data' 	=> $v2->sumber_data,
							'ftahun_core' 	=> $v2->tahun
						));
					$changed = [];
					if(strlen($key2)>0):
						$d = $dt_total[$key2];
						$changed = json_decode($d['changed'],true);
					endif;
					if($v2->sumber_data == 3):
						if(isset($arr_sum_kol[$pd_bln])){ $bln_before = (float) $arr_sum_kol[$pd_bln]; }
						$val = $bln_before - $arr_sum_kol[$field.'_'.$v2->tahun];
						if($val>0){ $val = 0; }
						if(isset($changed[$field]) && $changed[$field] == 1):
							$val = $d['f'.$field];
						endif;
						$arrPdBln[$field.'_'.$v2->tahun] = $val;
						if($d['fID']):
							if($val != $d['f'.$field]):
								$status_update = true;
								$arrDataSaved[$d['fID'].'-'.$d['coa'].'_total-'.$v2->tahun.'-'.$v2->sumber_data.'-'.$cabang][$field] = $val;
							endif;
						else:
							$status_update = true;
							$arrDataInsert[$d['coa'].'_total-'.$v2->tahun.'-'.$v2->sumber_data.'-'.$cabang][$field] = $val;
						endif;
					else:
						$val = 0;
						if(isset($changed[$field]) && $changed[$field] == 1):
							$val = $d['f'.$field];
						endif;
					endif;
					$name = $gwlnco.'_total-'.$v2->tahun.'-'.$v2->sumber_data.'-'.$cabang;
					$item2 .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="'.$field.'" data-id="'.$name.'" data-value="'.$val.'">'.check_value($val).'</div></td>';
				}
				$item2 .= '</tr>';


				$d = $add_on[$key];
				$item .= '<tr>';
				$item .= '<td>'.$d['coa'].'</td>';
				$item .= '<td>'.remove_spaces($d['name']).' S.D BLN</td>';
				$item .= '<td></td>';
				$item .= '<td></td>';
				$bln_before = 0;
				foreach ($detail_tahun as $k2 => $v2) {
					$field 	= 'B_' . sprintf("%02d", $v2->bulan);
					$changed = [];
					if($d['fID']):
						$key2 = multidimensional_search($add_on, array(
							'coa' 			=> $c,
							'fsumber_data' 	=> $v2->sumber_data,
							'ftahun_core' 	=> $v2->tahun
						));
						if(strlen($key2)>0):
							$d = $add_on[$key2];
							$changed = json_decode($d['changed'],true);
						endif;
					endif;
					if($v2->sumber_data == 3):
						$val = 0;
						if($v2->bulan == 1):
							if(isset($arrPdBln[$field.'_'.$v2->tahun])): $val = (float) $arrPdBln[$field.'_'.$v2->tahun] * -1; endif;
						else:
							$val = $bln_before - $arrPdBln[$field.'_'.$v2->tahun];
						endif;
						if(isset($changed[$field]) && $changed[$field] == 1):
							$val = $d['f'.$field];
						endif;
						$bln_before = $val;
						// $item .= '<td>'.check_value($val).'</td>';
						if($d['fID']):
							if($val != $d['f'.$field]):
								$status_update = true;
								$arrDataSaved[$d['fID'].'-'.$d['coa'].'-'.$v2->tahun.'-'.$v2->sumber_data.'-'.$cabang][$field] = $val;
							endif;
						else:
							$status_update = true;
							$arrDataInsert[$d['coa'].'-'.$v2->tahun.'-'.$v2->sumber_data.'-'.$cabang][$field] = $val;
						endif;
					else:
						$val = 0;
						if(isset($changed[$field]) && $changed[$field] == 1):
							$val = $d['f'.$field];
						endif;
					endif;
					if(isset($arrSumPdBln[$field.'_'.$v2->tahun])){ $arrSumPdBln[$field.'_'.$v2->tahun] += $val; }
					else{ $arrSumPdBln[$field.'_'.$v2->tahun] = $val; }
					$name = $d['coa'].'-'.$v2->tahun.'-'.$v2->sumber_data.'-'.$cabang;
					$item .= '<td class="text-right">'.check_value($val).'</td>';
				}
				$item .= '</tr>';
				$item .= $item2;

				$status = false;
			elseif(in_array($d['coa'], $to_individu)):
				$item2 = '<tr>';
				$item2 .= '<td></td>';
				$item2 .= '<td>'.remove_spaces($d['name']).' PD BLN</td>';
				$item2 .= '<td></td>';
				$item2 .= '<td></td>';

				$arrPdBln = [];
				foreach ($detail_tahun as $k2 => $v2) {
					$field 	= 'B_' . sprintf("%02d", $v2->bulan);
					$fieldx = sprintf("%02d", $v2->bulan);
					$pd_bln = '01-'.$fieldx.'-'.$v2->tahun;
					$pd_bln = 'B_'.str_replace('-', '_', minusMonth($pd_bln,1));
					$bln_before = 0;
					$key2 = multidimensional_search($dt_total, array(
						'coa' 			=> $c.'_total',
						'fsumber_data' 	=> $v2->sumber_data,
						'ftahun_core' 	=> $v2->tahun
					));
					$changed = [];
					if(strlen($key2)>0):
						$d = $dt_total[$key2];
						$changed = json_decode($d['changed'],true);
					endif;
					if($v2->sumber_data == 3):
						if(isset($arr_individu[$pd_bln])){ $bln_before = (float) $arr_individu[$pd_bln]; }
						$val = $bln_before - $arr_individu[$field.'_'.$v2->tahun];
						if($val>0){ $val = 0; }
						if(isset($changed[$field]) && $changed[$field] == 1):
							$val = $d['f'.$field];
						endif;
						$arrPdBln[$field.'_'.$v2->tahun] = $val;
						if($d['fID']):
							if($val != $d['f'.$field]):
								$status_update = true;
								$arrDataSaved[$d['fID'].'-'.$d['coa'].'_total-'.$v2->tahun.'-'.$v2->sumber_data.'-'.$cabang][$field] = $val;
							endif;
						else:
							$status_update = true;
							$arrDataInsert[$d['coa'].'_total-'.$v2->tahun.'-'.$v2->sumber_data.'-'.$cabang][$field] = $val;
						endif;
					else:
						$val = 0;
						if(isset($changed[$field]) && $changed[$field] == 1):
							$val = $d['f'.$field];
						endif;
					endif;
					$name = $gwlnco.'_total-'.$v2->tahun.'-'.$v2->sumber_data.'-'.$cabang;
					$item2 .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="'.$field.'" data-id="'.$name.'" data-value="'.$val.'">'.check_value($val).'</div></td>';
				}
				$item2 .= '</tr>';

				$d = $add_on[$key];
				$item .= '<tr>';
				$item .= '<td>'.$d['coa'].'</td>';
				$item .= '<td>'.remove_spaces($d['name']).' S.D BLN</td>';
				$item .= '<td></td>';
				$item .= '<td></td>';
				foreach ($detail_tahun as $k2 => $v2) {
					$field 	= 'B_' . sprintf("%02d", $v2->bulan);
					if($d['fID']):
						$key2 = multidimensional_search($add_on, array(
							'coa' 			=> $c,
							'fsumber_data' 	=> $v2->sumber_data,
							'ftahun_core' 	=> $v2->tahun
						));
						if(strlen($key2)>0):
							$d = $add_on[$key2];
							$changed = json_decode($d['changed'],true);
						endif;
					endif;
					if($v2->sumber_data == 3):
						$val = 0;
						if(isset($arrPdBln[$field.'_'.$v2->tahun])): $val = (float) $arrPdBln[$field.'_'.$v2->tahun] * -1; endif;
						if(isset($changed[$field]) && $changed[$field] == 1):
							$val = $d['f'.$field];
						endif;
						// $item .= '<td>'.check_value($val).'</td>';
						if($d['fID']):
							if($val != $d['f'.$field]):
								$status_update = true;
								$arrDataSaved[$d['fID'].'-'.$d['coa'].'-'.$v2->tahun.'-'.$v2->sumber_data.'-'.$cabang][$field] = $val;
							endif;
						else:
							$status_update = true;
							$arrDataInsert[$d['coa'].'-'.$v2->tahun.'-'.$v2->sumber_data.'-'.$cabang][$field] = $val;
						endif;
					else:
						$val = 0;
						if(isset($changed[$field]) && $changed[$field] == 1):
							$val = $d['f'.$field];
						endif;
					endif;
					if(isset($arrSumPdBln[$field.'_'.$v2->tahun])){ $arrSumPdBln[$field.'_'.$v2->tahun] += $val; }
					else{ $arrSumPdBln[$field.'_'.$v2->tahun] = $val; }
					$name = $d['coa'].'-'.$v2->tahun.'-'.$v2->sumber_data.'-'.$cabang;
					$item .= '<td class="text-right">'.check_value($val).'</td>';
				}
				$item .= '</tr>';
				$item .= $item2;
				$status = false;
			elseif(in_array($d['coa'], $arr_sum_sd_bln)):
				$d = $add_on[$key];
				$item .= '<tr>';
				$item .= '<td>'.$d['coa'].'</td>';
				$item .= '<td>'.remove_spaces($d['name']).'</td>';
				$item .= '<td></td>';
				$item .= '<td></td>';
				$bln_before = 0;

				foreach ($detail_tahun as $k2 => $v2) {
					$field 	= 'B_' . sprintf("%02d", $v2->bulan);
					if($d['fID']):
						$key2 = multidimensional_search($add_on, array(
							'coa' 			=> $c,
							'fsumber_data' 	=> $v2->sumber_data,
							'ftahun_core' 	=> $v2->tahun
						));
						if(strlen($key2)>0):
							$d = $add_on[$key2];
						endif;
					endif;

					if(isset($arrSumPdBln[$field.'_'.$v2->tahun])){ $val = $arrSumPdBln[$field.'_'.$v2->tahun]; }
					else{ $val = 0; }

					if($d['fID']):
						if($val != $d['f'.$field]):
							$status_update = true;
							$arrDataSaved[$d['fID'].'-'.$d['coa'].'-'.$v2->tahun.'-'.$v2->sumber_data.'-'.$cabang][$field] = $val;
						endif;
					else:
						$status_update = true;
						$arrDataInsert[$d['coa'].'-'.$v2->tahun.'-'.$v2->sumber_data.'-'.$cabang][$field] = $val;
					endif;
					$item .= '<td class="text-right">'.check_value($val).'</td>';
				}

				$item .= '</tr>';
				// $item .= $item2;
				$status = false;
			endif;

			if($status):
				$item .= '<tr>';
				$item .= '<td>'.$d['coa'].'</td>';
				$item .= '<td>'.remove_spaces($d['name']).'</td>';
				$item .= '<td>'.check_value($core_1).'</td>';
				$item .= '<td>'.check_value($core_2).'</td>';
				foreach ($detail_tahun as $k2 => $v2) {
					if($d['fID']):
						$key = multidimensional_search($add_on, array(
								'coa' 			=> $c,
								'fsumber_data' 	=> $v2->sumber_data,
								'ftahun_core' 	=> $v2->tahun
							));
						$d = $add_on[$key];
						$changed = json_decode($d['changed'],true);
					endif;

					$field 	= 'B_' . sprintf("%02d", $v2->bulan);
					$val = 0;
					if(in_array($d['coa'],$all_core)):
						$val = $core_2;
						if(isset($changed[$field]) && $changed[$field] == 1){ $val = $d['f'.$field]; }
						// $item .= '<td>'.check_value($core_2).'</td>';
						$individu[$field.'_'.$v2->tahun] = $core_2;
					elseif(in_array($d['coa'], $kol_1)):
						$val 	= $for_kolek[1][$field.'_'.$v2->tahun];
						if(isset($changed[$field]) && $changed[$field] == 1){ $val = $d['f'.$field]; }
						// $item .= '<td>'.check_value($val).'</td>';
						if(isset($sum_kol[$field.'_'.$v2->tahun])){ $sum_kol[$field.'_'.$v2->tahun] += $val; }else { $sum_kol[$field.'_'.$v2->tahun] = $val; }
					elseif(in_array($d['coa'], $kol_2)):
						$val 	= $for_kolek[2][$field.'_'.$v2->tahun];
						if(isset($changed[$field]) && $changed[$field] == 1){ $val = $d['f'.$field]; }
						// $item .= '<td>'.check_value($val).'</td>';
						if(isset($sum_kol[$field.'_'.$v2->tahun])){ $sum_kol[$field.'_'.$v2->tahun] += $val; }else { $sum_kol[$field.'_'.$v2->tahun] = $val; }
					elseif(in_array($d['coa'], $kol_3)):
						$val 	= (float) $for_kolek[3][$field.'_'.$v2->tahun] + (float) $for_kolek[4][$field.'_'.$v2->tahun] + (float) $for_kolek[5][$field.'_'.$v2->tahun];
						if(isset($changed[$field]) && $changed[$field] == 1){ $val = $d['f'.$field]; }
						// $item .= '<td>'.check_value($val).'</td>';
						if(isset($sum_kol[$field.'_'.$v2->tahun])){ $sum_kol[$field.'_'.$v2->tahun] += $val; }else { $sum_kol[$field.'_'.$v2->tahun] = $val; }
					else:
						if(isset($changed[$field]) && $changed[$field] == 1){ $val = $d['f'.$field]; }
					endif;

					$named = '';
					if($d['fID']):
						$named = 'ID'.$d['fID'].'-'.$d['coa'].'-'.$v2->tahun.'-'.$v2->sumber_data.'-'.$cabang;
						if($val != $d['f'.$field]):
							$status_update = true;
							$arrDataSaved[$d['fID'].'-'.$d['coa'].'-'.$v2->tahun.'-'.$v2->sumber_data.'-'.$cabang][$field] = $val;
						endif;
					else:
						$status_update = true;
						$named = $d['coa'].'-'.$v2->tahun.'-'.$v2->sumber_data.'-'.$cabang;
						$arrDataInsert[$d['coa'].'-'.$v2->tahun.'-'.$v2->sumber_data.'-'.$cabang][$field] = $val;
					endif;
					$item .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="'.$field.'" data-id="'.$named.'" data-value="'.$val.'">'.check_value($val).'</div></td>';
				}
				$item .= '</tr>';
			endif;
		endif;
		if($c == 'sum_kol'):
			$item .= '<tr>';
			$item .= '<td></td>';
			$item .= '<th>'.lang('jmlh_stage').'</th>';
			$item .= '<td></td>';
			$item .= '<td></td>';
			foreach ($detail_tahun as $k2 => $v2) {
				$field 	= 'B_' . sprintf("%02d", $v2->bulan);
				$val 	= $sum_kol[$field.'_'.$v2->tahun];

				// $key = multidimensional_search($dt_total, array(
				// 	'coa' 			=> 'sumkol123_total',
				// 	'fsumber_data' 	=> $v2->sumber_data,
				// 	'ftahun_core' 	=> $v2->tahun
				// ));
				// if(strlen($key)>0):
				// 	$d = $dt_total[$key];
				// 	$changed = json_decode($d['changed'],true);
				// 	if(isset($changed[$field]) && $changed[$field] == 1):
				// 		$val = $d['f'.$field];
				// 	endif;
				// endif;

				$status_update = true;
				$named = 'sumkol123'.'_total-'.$v2->tahun.'-'.$v2->sumber_data.'-'.$cabang;
				$arrDataSaved['id'.'-'.'sumkol123'.'_total-'.$v2->tahun.'-'.$v2->sumber_data.'-'.$cabang][$field] = $val;

				$item .= '<td class="text-right">'.check_value($val).'</td>';
			}
			$item .= '</tr>';
		endif;
	}	
	echo $item;

	if($status_update):
		insert_formula_kolektibilitas($arrDataInsert,$anggaran);
		update_formula_kolektibilitas($arrDataSaved,$anggaran);
	endif;

	$h['arr_sum_kol']  = $sum_kol;
	$h['arr_individu'] = $individu;
    $this->session->set_userdata($h);
?>