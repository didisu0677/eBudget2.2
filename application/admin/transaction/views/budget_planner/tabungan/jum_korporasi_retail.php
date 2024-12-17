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
		$field = 'P_'. sprintf("%02d", $v->bulan);
		$val = 0;
		if(isset($data[$v->tahun.'-'.$field])):
			$val = $data[$v->tahun.'-'.$field];
		endif;
		$item .= '<td class="text-right">'.custom_format($val).'</td>';
	}
	$item .= '<td></td>';
	$item .= '<td></td>';
	$item .= '<td></td>';
	$item .= '</tr>';

	// retail
	$arr_retail = [];
	$item2 = '<tr>';
	$item2 .= '<td></td>';
	$item2 .= '<td>--| Retail</td>';
	$key 	= multidimensional_search($import_jum_rek, array(
		'coa' => '421',
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
		$key 	= multidimensional_search($tabungan, array(
			'tahun_core' => $v->tahun,
			'coa' => '421',
		));
		if(strlen($key)>0):
			$index_kali = $tabungan[$key]['index_kali'];
		endif;
	}
	foreach ($detail_tahun as $k => $v) {
		$field  = 'P_' . sprintf("%02d", $v->bulan);
		$bulan 	= sprintf("%02d", $v->bulan);
		$val 	= $jum_trakhir;
		if($index_kali):
			$val = $bln_before + $index_kali;
		endif;
		$key 	= multidimensional_search($tabungan, array(
			'tahun_core' => $v->tahun,
			'coa' => '421',
		));
		if(strlen($key)>0):
			$is_edit = json_decode($tabungan[$key]['is_edit'],true);
			if(isset($is_edit[$v->tahun.$bulan])):
				$val = $is_edit[$v->tahun.$bulan];
				$jum_trakhir = $val;
			endif;
		endif;
		$bln_before = $val;
		$arr_retail[$v->tahun.$v->bulan] = $val;
		$item2 .= '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="421|tbl_jum_segment|'.$v->tahun.$bulan.'|421|'.$anggaran->id.'|'.$cabang->kode_cabang.'" data-id="q1" data-value="'.$val.'">'.custom_format($val).'</div></td>';
	}

	$item2 .= '<td></td>';
	$item2 .= '<td class="text-right">'.$jum_trakhir_temp.'</td>';
	$item2 .= '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="421|tbl_index_kali|000000|421|'.$anggaran->id.'|'.$cabang->kode_cabang.'" data-id="q1" data-value="'.$index_kali.'">'.custom_format($index_kali).'</div></td>';
	$item2 .= '</tr>';

	// korporasi
	$item .= '<tr>';
	$item .= '<td></td>';
	$item .= '<td>--| Korporasi</td>';
	foreach ($detail_tahun as $k => $v) {
		$field  = 'P_' . sprintf("%02d", $v->bulan);
		$val = 0;
		if(isset($data[$v->tahun.'-'.$field])):
			$val = $data[$v->tahun.'-'.$field];
		endif;
		if(isset($arr_retail[$v->tahun.$v->bulan])):
			$val -= $arr_retail[$v->tahun.$v->bulan];
		endif;
		$item .= '<td class="text-right">'.custom_format($val).'</td>';
	}

	$key 	= multidimensional_search($import_jum_rek, array(
		'coa' => '420',
	));
	$jum_trakhir = 0;
	if(strlen($key)>0):
		$jum_trakhir = $import_jum_rek[$key]['total'];
	endif;

	$item .= '<td></td>';
	$item .= '<td class="text-right">'.$jum_trakhir.'</td>';
	$item .= '<td></td>';
	$item .= '</tr>';

	$item .= $item2;
	echo $item;
?>