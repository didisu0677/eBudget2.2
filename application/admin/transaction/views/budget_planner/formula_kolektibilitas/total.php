<?php
	$for_kolek = $this->session->for_kolek;
	$item = '';

	if(isset($for_kolek['total'])):
		$item .= '<tr>';
		$item .= '<th>Total Tarif</th>';
		foreach ($detail_tahun as $k2 => $v2) {
			$field 	= 'B_' . sprintf("%02d", $v2->bulan);
			$val 	= $for_kolek['total'][$field.'_'.$v2->tahun];
			$item .= '<td class="text-right">'.check_value($val).'</td>';
		}
		$item .= '</tr>';
	endif;

	for ($i=1; $i<=5 ; $i++) { 
		if(isset($for_kolek[$i])):
			$item .= '<tr>';
			$item .= '<td>Kol '.($i).'</td>';
			foreach ($detail_tahun as $k2 => $v2) {
				$field 	= 'B_' . sprintf("%02d", $v2->bulan);
				$val 	= $for_kolek[$i][$field.'_'.$v2->tahun];
				$item .= '<td class="text-right">'.check_value($val).'</td>';
			}
			$item .= '</tr>';
		endif;
	}

	if(count($for_kolek)<=0):
		$item .= '<tr>';
		$item .= '<th colspan="'.(3+count($detail_tahun)).'">'.lang('data_not_found').'</th>';
		$item .= '</tr>';
	endif;

	echo $item;
?>