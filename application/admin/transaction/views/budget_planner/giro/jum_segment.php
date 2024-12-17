<?php
	$bgedit ="";
	$contentedit ="false" ;
	if($access_edit) {
		$bgedit = bgEdit();
		$contentedit ="true" ;
	}
	$arr_total;

	// Giro Kasda
	$item = '<tr>';
	$item .= '<td>'.$no.'</td>';
	$item .= '<td>2101012 - GIRO KASDA</td>';
	$key 	= multidimensional_search($import_jum_rek, array(
		'coa' => '2101012',
	));
	$jum_trakhir = 0;
	$jum_trakhir_temp = 0;
	if(strlen($key)>0):
		$jum_trakhir = $import_jum_rek[$key]['total'];
		$jum_trakhir_temp = $import_jum_rek[$key]['total'];
	endif;
	$index_kali = 0;
	$bln_before = $jum_trakhir;
	foreach ($detail_tahun as $k => $v) {
		$key 	= multidimensional_search($giro, array(
			'tahun_core' => $v->tahun,
			'coa' => '2101012',
		));
		if(strlen($key)>0):
			$index_kali = $giro[$key]['index_kali'];
		endif;
	}
	foreach ($detail_tahun as $k => $v) {
		$field  = 'P_' . sprintf("%02d", $v->bulan);
		$bulan 	= sprintf("%02d", $v->bulan);
		$val 	= $jum_trakhir;
		if($index_kali):
			$val = $bln_before + $index_kali;
		endif;
		$key 	= multidimensional_search($giro, array(
			'tahun_core' => $v->tahun,
			'coa' => '2101012',
		));
		if(strlen($key)>0):
			$is_edit = json_decode($giro[$key]['is_edit'],true);
			if(isset($is_edit[$v->tahun.$bulan])):
				$val = $is_edit[$v->tahun.$bulan];
				$jum_trakhir = $val;
			endif;
		endif;
		$bln_before = $val;
		$arr_total[$v->tahun.$v->bulan] = $val;
		$item .= '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="GIRO_KASDA_2101012-tbl_jum_segment-'.$v->tahun.$bulan.'-2101012-'.$anggaran->id.'-'.$cabang->kode_cabang.'" data-id="2101012" data-value="'.$val.'">'.custom_format($val).'</div></td>';
	}
	$item .= '<td></td>';
	$item .= '<td class="text-right">'.$jum_trakhir_temp.'</td>';
	$item .= '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="GIRO_KASDA_2101012-tbl_index_kali-000000-2101012-'.$anggaran->id.'-'.$cabang->kode_cabang.'" data-id="2101012" data-value="'.$index_kali.'">'.custom_format($index_kali).'</div></td>';
	$item .= '</tr>';

	$item2 = '';
	$arr_non_kasda = [];
	foreach (['211' => 'Korporasi','212' => 'Retail'] as $coa => $name) {
		$item2 .= '<tr>';
		$item2 .= '<td></td>';
		$item2 .= '<td>--| '.$name.'</td>';
		
		$key 	= multidimensional_search($import_jum_rek, array(
			'coa' => $coa,
		));
		$jum_trakhir = 0;
		$jum_trakhir_temp = 0;
		if(strlen($key)>0):
			$jum_trakhir = $import_jum_rek[$key]['total'];
			$jum_trakhir_temp = $import_jum_rek[$key]['total'];
		endif;
		$index_kali = 0;
		$bln_before = $jum_trakhir;
		foreach ($detail_tahun as $k => $v) {
			$key 	= multidimensional_search($giro, array(
				'tahun_core' => $v->tahun,
				'coa' => $coa,
			));
			if(strlen($key)>0):
				$index_kali = $giro[$key]['index_kali'];
			endif;
		}
		foreach ($detail_tahun as $k => $v) {
			$id = rand(10, 50);
			$field  = 'P_' . sprintf("%02d", $v->bulan);
			$bulan 	= sprintf("%02d", $v->bulan);
			$val 	= $jum_trakhir;
			if($index_kali):
				$val = $bln_before + $index_kali;
			endif;
			$key 	= multidimensional_search($giro, array(
				'tahun_core' => $v->tahun,
				'coa' => $coa,
			));
			if(strlen($key)>0):
				$is_edit = json_decode($giro[$key]['is_edit'],true);
				if(isset($is_edit[$v->tahun.$bulan])):
					$val = $is_edit[$v->tahun.$bulan];
					$jum_trakhir = $val;
				endif;
			endif;
			$bln_before = $val;
			if(isset($arr_non_kasda[$v->tahun.$v->bulan])):
				$arr_non_kasda[$v->tahun.$v->bulan] += $val;
			else:
				$arr_non_kasda[$v->tahun.$v->bulan] = $val;
			endif;
			$item2 .= '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="'.$coa.'-tbl_jum_segment-'.$v->tahun.$bulan.'-'.$coa.'-'.$anggaran->id.'-'.$cabang->kode_cabang.'" data-id="'.$coa.'" data-value="'.$val.'">'.custom_format($val).'</div></td>';
		}
		$item2 .= '<td></td>';
		$item2 .= '<td class="text-right">'.$jum_trakhir_temp.'</td>';
		$item2 .= '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="'.$coa.'-tbl_index_kali-000000-'.$coa.'-'.$anggaran->id.'-'.$cabang->kode_cabang.'" data-id="'.$coa.'" data-value="'.$index_kali.'">'.custom_format($index_kali).'</div></td>';

		$item2 .= '</tr>';
	}

	// Giro Non Kasda
	$item .= '<tr>';
	$item .= '<td>'.($no+1).'</td>';
	$item .= '<td>2101011 - GIRO NON KASDA</td>';
	foreach ($detail_tahun as $k => $v) {
		$val = $arr_non_kasda[$v->tahun.$v->bulan];
		$arr_total[$v->tahun.$v->bulan] += $val;
		$item .= '<td class="text-right">'.custom_format($val).'</td>';
	}
	$item .= '<td></td>';
	$item .= '<td></td>';
	$item .= '<td></td>';
	$item .= '</tr>';
	$item .= $item2;

	// Total
	$item .= '<tr>';
	$item .= '<td></td>';
	$item .= '<td><b>Jumlah Giro</b></td>';
	foreach ($detail_tahun as $k => $v) {
		$val = $arr_total[$v->tahun.$v->bulan];
		$item .= '<td class="text-right"><b>'.custom_format($val).'</b></td>';
	}
	$item .= '<td></td>';
	$item .= '<td></td>';
	$item .= '<td></td>';
	$item .= '</tr>';

	echo $item;
?>