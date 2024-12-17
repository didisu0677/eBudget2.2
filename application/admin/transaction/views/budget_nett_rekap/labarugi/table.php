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
								$no++;
								$item .= '<tr>';
								$item .= '<td>'.$no.'</td>';
								$item .= '<td class="sb-3">'.remove_spaces($v4['nama_cabang']).'</td>';
								foreach ($detail_tahun as $i) {
									$bulan  = 'B_' . sprintf("%02d", $i->bulan);
									$item .= '<td class="text-right">'.custom_format(view_report($v4[$bulan])).'</td>';
								}
								$item .= '</tr>';
							}
						endif;

						$no++;
						$item .= '<tr>';
						$item .= '<td>'.$no.'</td>';
						$item .= '<td class="sb-2 text-bold">'.remove_spaces($v3['nama_cabang']).'</td>';
						foreach ($detail_tahun as $i) {
							$bulan  = 'B_' . sprintf("%02d", $i->bulan);
							$item .= '<td class="text-right">'.custom_format(view_report($v3[$bulan])).'</td>';
						}
						$item .= '</tr>';
					}
				endif;

				$no++;
				$item .= '<tr class="t-sb-1">';
				$item .= '<td>'.$no.'</td>';
				$item .= '<td class="sb-1">'.remove_spaces($v2['nama_cabang']).'</td>';
				foreach ($detail_tahun as $i) {
					$bulan  = 'B_' . sprintf("%02d", $i->bulan);
					$item .= '<td class="text-right">'.custom_format(view_report($v2[$bulan])).'</td>';
				}
				$item .= '</tr>';
			}
		endif;

		$no++;
		$item .= '<tr>';
		$item .= '<td>'.$no.'</td>';
		$item .= '<td>'.remove_spaces($v['nama_cabang']).'</td>';
		foreach ($detail_tahun as $i) {
			$bulan  = 'B_' . sprintf("%02d", $i->bulan);
			$item .= '<td class="text-right">'.custom_format(view_report($v[$bulan])).'</td>';
		}
		$item .= '</tr>';
	}

	foreach ($konsolidasi as $k => $v) {
		$title = '';
		if($v['kode_cabang'] == 'KONS'):
			$title = 'KONSOLIDASI';
		elseif($v['kode_cabang'] == 'KONV'):
			$title = 'KONVENSIONAL';
		endif;

		$no++;
		$item .= '<tr>';
		$item .= '<td>'.$no.'</td>';
		$item .= '<td>'.remove_spaces($title).'</td>';
		foreach ($detail_tahun as $i) {
			$bulan  = 'B_' . sprintf("%02d", $i->bulan);
			$item .= '<td class="text-right">'.custom_format(view_report($v[$bulan])).'</td>';
		}
		$item .= '</tr>';
	}
	echo $item;
?>