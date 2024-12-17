<?php
	$bgedit ="";
	$contentedit ="false" ;
	if($access_edit) {
		$bgedit = bgEdit();
		$contentedit ="true" ;
	}

	// tabungan
	$item = '<tr>';
	$item .= '<td>'.$no.'</td>';
	$item .= '<td>Tabungan</td>';
	$arr_tabungan = [];
	foreach ($detail_tahun as $k => $v) {
		$field  = 'P_' . sprintf("%02d", $v->bulan);
		$key 	= multidimensional_search($tabungan, array(
			'tahun_core' => $v->tahun,
			'coa' => '2120011',
		));
		$val = 0;
		if(strlen($key)>0):
			$val = $tabungan[$key][$field];
		endif;
		$arr_tabungan[$v->tahun][$field] = $val;
		$item .= '<td class="text-right">'.custom_format(view_report($val)).'</td>';
	}
	$item .= '</tr>';

	// korporasi
	$item .= '<tr>';
	$item .= '<td></td>';
	$item .= '<td>--| Korporasi</td>';
	foreach ($detail_tahun as $k => $v) {
		$field  = 'P_' . sprintf("%02d", $v->bulan);
		$val 	= $arr_tabungan[$v->tahun][$field];
		$key 	= multidimensional_search($tabungan, array(
			'tahun_core' => $v->tahun,
			'coa' => '421',
		));
		if(strlen($key)>0):
			$val -= $tabungan[$key][$field];
		endif;
		$item .= '<td class="text-right">'.custom_format(view_report($val)).'</td>';
	}
	$item .= '</tr>';

	$item .= '<tr>';
	$item .= '<td></td>';
	$item .= '<td>--| Retail</td>';
	foreach ($detail_tahun as $k => $v) {
		$field  = 'P_' . sprintf("%02d", $v->bulan);
		$bulan 	= sprintf("%02d", $v->bulan);
		$val 	= 0;
		$key 	= multidimensional_search($tabungan, array(
			'tahun_core' => $v->tahun,
			'coa' => '421',
		));
		if(strlen($key)>0):
			$val = $tabungan[$key][$field];
		endif;
		$item .= '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="421|tbl_segment|'.$v->tahun.$bulan.'|421|'.$anggaran->id.'|'.$cabang->kode_cabang.'" data-id="q0" data-value="'.view_report($val).'">'.custom_format(view_report($val)).'</div></td>';
	}
	$item .= '</tr>';

	echo $item;
?>