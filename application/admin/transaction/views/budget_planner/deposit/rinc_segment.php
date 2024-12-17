<?php
	$bgedit ="";
	$contentedit ="false" ;
	if($access_edit) {
		$bgedit = bgEdit();
		$contentedit ="true" ;
	}
	
	// deposit
	$item = '<tr>';
	$item .= '<td>'.$no.'</td>';
	$item .= '<td>DEPOSITO</td>';
	$arr_deposito = [];
	foreach ($detail_tahun as $k => $v) {
		$field  = 'P_' . sprintf("%02d", $v->bulan);
		$key 	= multidimensional_search($deposito, array(
			'tahun_core' => $v->tahun,
			'coa' => '2130000',
		));
		$val = 0;
		if(strlen($key)>0):
			$val = $deposito[$key][$field];
		endif;
		$arr_deposito[$v->tahun][$field] = $val;
		$item .= '<td class="text-right">'.custom_format(view_report($val)).'</td>';
	}
	$item .= '</tr>';

	// korporasi
	$item .= '<tr>';
	$item .= '<td></td>';
	$item .= '<td>--| Korporasi</td>';
	foreach ($detail_tahun as $k => $v) {
		$field  = 'P_' . sprintf("%02d", $v->bulan);
		$val 	= $arr_deposito[$v->tahun][$field];
		$key 	= multidimensional_search($deposito, array(
			'tahun_core' => $v->tahun,
			'coa' => '317',
		));
		if(strlen($key)>0):
			$val -= $deposito[$key][$field];
		endif;
		$item .= '<td class="text-right">'.custom_format(view_report($val)).'</td>';
	}
	$item .= '</tr>';

	// Retail
	$item .= '<tr>';
	$item .= '<td></td>';
	$item .= '<td>--| Retail</td>';
	foreach ($detail_tahun as $k => $v) {
		$field  = 'P_' . sprintf("%02d", $v->bulan);
		$bulan 	= sprintf("%02d", $v->bulan);
		$val 	= 0;
		$key 	= multidimensional_search($deposito, array(
			'tahun_core' => $v->tahun,
			'coa' => '317',
		));
		if(strlen($key)>0):
			$val = $deposito[$key][$field];
		endif;
		$item .= '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="317|tbl_segment|'.$v->tahun.$bulan.'|317|'.$anggaran->id.'|'.$cabang->kode_cabang.'" data-id="q0" data-value="'.view_report($val).'">'.custom_format(view_report($val)).'</div></td>';
	}
	$item .= '</tr>';

	echo $item;
?>