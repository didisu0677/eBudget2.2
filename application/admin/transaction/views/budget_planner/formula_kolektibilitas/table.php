<?php
	$item = '';
	$arrTotal = [];
	foreach ($list as $k => $v) {
		$item2 	= '';
		$dt2 	= [];
		for ($i=1; $i <=5 ; $i++) { 
			$rate = 0;
			$kol_name = 'kol_'.$i;
			if($v->{$kol_name}){ $rate = $v->{$kol_name}; }
			$item2 .= '<tr>';
			$item2 .= '<td></td>';
			$item2 .= '<td>Kol '.$i.'</td>';
			$item2 .= '<td class="text-right">'.custom_format($rate,false,4).'</td>';
			foreach ($detail_tahun as $k2 => $v2) {
				$field = 'B_' . sprintf("%02d", $v2->bulan);
				$key = multidimensional_search($detail, array(
					'tahun_core' => $v2->tahun,
					'id_kolektibilitas' => $v->id,
				));
				$d = $detail[$key];
				$val = $d[$field.'_'.$i];
				if(isset($dt2[$field.'_'.$v2->tahun])){ $dt2[$field.'_'.$v2->tahun] += $val; }
				else{ $dt2[$field.'_'.$v2->tahun] = $val; }
				$item2 .= '<td class="text-right">'.check_value($val).'</td>';
			}
			$item2 .= '</tr>';
		}

		$item .= '<tr>';
		$item .= '<td>'.$v->coa.'</td>';
		$item .= '<th>'.remove_spaces($v->nama_produk_kredit).'</th>';
		$item .= '<td></td>';
		foreach ($detail_tahun as $k2 => $v2) {
			$field 	= 'B_' . sprintf("%02d", $v2->bulan);
			$val 	= $dt2[$field.'_'.$v2->tahun];
			$item .= '<td class="text-right">'.check_value($val).'</td>';
		}
		$item .= '</tr>';
		$item .= $item2;

		$item2 	= '';
		$dt2 	= [];
		for ($i=1; $i <=5 ; $i++) { 
			$rate = 0;
			$kol_name = 'kol_'.$i;
			if($v->{$kol_name}){ $rate = $v->{$kol_name}; }
			$item2 .= '<tr>';
			$item2 .= '<td></td>';
			$item2 .= '<td>Kol '.$i.'</td>';
			$item2 .= '<td></td>';
			foreach ($detail_tahun as $k2 => $v2) {
				$field = 'B_' . sprintf("%02d", $v2->bulan);
				$key = multidimensional_search($detail, array(
					'tahun_core' => $v2->tahun,
					'id_kolektibilitas' => $v->id,
				));
				$d = $detail[$key];
				$val = $d[$field.'_'.$i];
				$val = ($rate*$val);
				if(isset($dt2[$field.'_'.$v2->tahun])){ $dt2[$field.'_'.$v2->tahun] += $val; }
				else{ $dt2[$field.'_'.$v2->tahun] = $val; }

				if(isset($for_kolek[$i][$field.'_'.$v2->tahun])):
					$for_kolek[$i][$field.'_'.$v2->tahun] += $val;
				else:
					$for_kolek[$i][$field.'_'.$v2->tahun] = $val;
				endif;

				$item2 .= '<td class="text-right">'.check_value($val).'</td>';
			}
			$item2 .= '</tr>';
		}

		$item .= '<tr>';
		$item .= '<td></td>';
		$item .= '<th>Total Tarif</th>';
		$item .= '<td></td>';
		foreach ($detail_tahun as $k2 => $v2) {
			$field 	= 'B_' . sprintf("%02d", $v2->bulan);
			$val 	= $dt2[$field.'_'.$v2->tahun];
			if(isset($for_kolek['total'][$field.'_'.$v2->tahun])):
				$for_kolek['total'][$field.'_'.$v2->tahun] += $val;
			else:
				$for_kolek['total'][$field.'_'.$v2->tahun] = $val;
			endif;
			$item .= '<td class="text-right">'.check_value($val).'</td>';
		}
		$item .= '</tr>';
		$item .= $item2;
	}

	if(count($list)<=0):
		$item .= '<tr>';
		$item .= '<th colspan="'.(3+count($detail_tahun)).'">'.lang('data_not_found').'</th>';
		$item .= '</tr>';
	endif;

	echo $item;


	$h['for_kolek'] = $for_kolek;
    $this->session->set_userdata($h);
?>