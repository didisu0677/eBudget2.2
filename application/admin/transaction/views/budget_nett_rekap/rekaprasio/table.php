<?php
	$item 	= '';
	$no 	= 0;
	foreach ($cabang['l1'] as $k => $v) {

		if(isset($cabang['l2'][$v['level1']])):
			foreach ($cabang['l2'][$v['level1']] as $k2 => $v2) {

				if(isset($cabang['l3'][$v2['level2']])):
					foreach ($cabang['l3'][$v2['level2']] as $k3 => $v3) {

						if(isset($cabang['l4'][$v3['level3']])):
							foreach ($cabang['l4'][$v3['level3']] as $k4 => $v4) {
								$dt_4 = hitung_rekap_rasio($v4['kode_cabang'],$coa,$anggaran);
								$no++;
								$item .= '<tr>';
								$item .= '<td>'.$no.'</td>';
								$item .= '<td class="sb-3">'.remove_spaces($v4['nama_cabang']).'</td>';
								foreach ($detail_tahun as $i) {
									$bulan  = 'B_' . sprintf("%02d", $i->bulan);
									$item .= '<td class="text-right">'.$dt_4[$bulan].'</td>';
								}
								$item .= '</tr>';
							}
						endif;

						$dt_3 = hitung_rekap_rasio($v3['kode_cabang'],$coa,$anggaran);
						$no++;
						$item .= '<tr>';
						$item .= '<td>'.$no.'</td>';
						$item .= '<td class="sb-2 text-bold">'.remove_spaces($v3['nama_cabang']).'</td>';
						foreach ($detail_tahun as $i) {
							$bulan  = 'B_' . sprintf("%02d", $i->bulan);
							$item .= '<td class="text-right">'.$dt_3[$bulan].'</td>';
						}
						$item .= '</tr>';
					}
				endif;

				$dt_2 = hitung_rekap_rasio($v2['kode_cabang'],$coa,$anggaran);
				$no++;
				$item .= '<tr class="t-sb-1">';
				$item .= '<td>'.$no.'</td>';
				$item .= '<td class="sb-1">'.remove_spaces($v2['nama_cabang']).'</td>';
				foreach ($detail_tahun as $i) {
					$bulan  = 'B_' . sprintf("%02d", $i->bulan);
					$item .= '<td class="text-right">'.$dt_2[$bulan].'</td>';
				}
				$item .= '</tr>';
			}
		endif;

		$dt_1 = hitung_rekap_rasio($v['kode_cabang'],$coa,$anggaran);

		$no++;
		$item .= '<tr>';
		$item .= '<td>'.$no.'</td>';
		$item .= '<td>'.remove_spaces($v['nama_cabang']).'</td>';
		foreach ($detail_tahun as $i) {
			$bulan  = 'B_' . sprintf("%02d", $i->bulan);
			$item .= '<td class="text-right">'.$dt_1[$bulan].'</td>';
		}
		$item .= '</tr>';
	}

	// KONVENSIONAL
	$dt_konv = hitung_rekap_rasio('KONV',$coa,$anggaran);
	$no++;
	$item .= '<tr>';
	$item .= '<td>'.$no.'</td>';
	$item .= '<td>'.remove_spaces('KONVENSIONAL').'</td>';
	foreach ($detail_tahun as $i) {
		$bulan  = 'B_' . sprintf("%02d", $i->bulan);
		$item .= '<td class="text-right">'.$dt_konv[$bulan].'</td>';
	}
	$item .= '</tr>'; 

	// KONSOLIDASI
	$dt_konv = hitung_rekap_rasio('KONS',$coa,$anggaran);
	$no++;
	$item .= '<tr>';
	$item .= '<td>'.$no.'</td>';
	$item .= '<td>'.remove_spaces('KONSOLIDASI').'</td>';
	foreach ($detail_tahun as $i) {
		$bulan  = 'B_' . sprintf("%02d", $i->bulan);
		$item .= '<td class="text-right">'.$dt_konv[$bulan].'</td>';
	}
	$item .= '</tr>'; 

	echo $item;
?>