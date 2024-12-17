<?php
	$item = '';
	foreach ($cabang[0] as $m0) {
		$item .= '<tr>';
		$item .= '<td colspan="3" class="bg-c1">'.$m0->nama_cabang.'</td>';
		
		$item_m0 = '';
		$jumlah_m0 = 0;
		for ($i=1; $i <=12 ; $i++) { ${'m0'.$i} = 0;}
		foreach($cabang[$m0->id] as $m1){
			$item_m1   = '';
			$jumlah_m1 = 0;
			for ($i=1; $i <=12 ; $i++) { ${'m1'.$i} = 0;}
			foreach($cabang[$m1->id] as $m2){
				$item_m2   = '';
				$jumlah_m2 = 0;
				for ($i=1; $i <=12 ; $i++) { ${'m2'.$i} = 0;}
				foreach($cabang[$m2->id] as $m3){
					$filter = array_filter($dKey, function ($var) use ($m3) {
					    return ($var['kode_cabang'] == $m3->kode_cabang);
					});
					foreach ($filter as $k => $v) {
						$item_m2 .= '<tr>';
						$item_m2 .= '<td class="sub-3">'.$m3->nama_cabang.'</td>';
						$item_m2 .= '<td>'.$v['nama_inventaris'].'</td>';

						${'harga'.$m3->kode_cabang} = 0;
						${'jumlah'.$m3->kode_cabang} = 0;
						$item_m3 = '';
						for ($i=1; $i <=12 ; $i++) { 
							$key = multidimensional_search($dSum, array(
								'kode_cabang'		=> $m3->kode_cabang,
								'nama_inventaris' 	=> $v['nama_inventaris'],
								'bulan'				=> $i,
							));
							if(strlen($key)>0):
								$jumlah  = $dSum[$key]['jumlah'];
								$harga   = $dSum[$key]['harga'];
								$total 	 = $jumlah * $harga;
								${'harga'.$m3->kode_cabang} += $harga;
								${'jumlah'.$m3->kode_cabang} += $jumlah;
								${'m2'.$i} += $total;
								$item_m3 .= '<td class="text-right">'.custom_format($total).'</td>';
							else:
								$item_m3 .= '<td>-</td>';
							endif;
						}
						$jumlah_m2 += ${'jumlah'.$m3->kode_cabang};
						$item_m2 .= '<td class="text-right">'.custom_format(${'harga'.$m3->kode_cabang}).'</td>';
						$item_m2 .= '<td class="text-right">'.custom_format(${'jumlah'.$m3->kode_cabang}).'</td>';
						$item_m2 .= $item_m3;
						$item_m2 .= '</tr>';
					}
					if(count($filter)<=0):
						$item_m2 .= '<tr>';
						$item_m2 .= '<td class="sub-3">'.$m3->nama_cabang.'</td>';
						$item_m2 .= '<td>-</td>';
						$item_m2 .= '<td>-</td>';
						$item_m2 .= '<td>-</td>';
						for ($i=1; $i <=12 ; $i++) { 
							$item_m2 .= '<td>-</td>';
						}
						$item_m2 .= '</tr>';
						$item_m2;
					endif;
				}
				$jumlah_m1 += $jumlah_m2;
				$item_m1 .= '<tr>';
				$item_m1 .= '<td class="sub-2 bg-c3" colspan="3">'.$m2->nama_cabang.'</td>';
				$item_m1 .= '<td class="text-right bg-c3">'.custom_format($jumlah_m2).'</td>';
				for ($i=1; $i <=12 ; $i++) { 
					$x = ${'m2'.$i};
					${'m1'.$i} += $x;
					$item_m1 .= '<td class="text-right bg-c3">'.custom_format($x).'</td>';
				}
				$item_m1 .= '</tr>';
				$item_m1 .= $item_m2;
			}

			$jumlah_m0 += $jumlah_m1;
			$item_m0 .= '<tr>';
			$item_m0 .= '<td class="sub-1 bg-c2" colspan="3">'.$m1->nama_cabang.'</td>';
			$item_m0 .= '<td class="text-right bg-c2">'.custom_format($jumlah_m1).'</td>';
			for ($i=1; $i <=12 ; $i++) { 
				$x = ${'m1'.$i};
				${'m0'.$i} += $x;
				$item_m0 .= '<td class="text-right bg-c2">'.custom_format($x).'</td>';
			}
			$item_m0 .= '<tr>';
			$item_m0 .= $item_m1;
		}
		$item .= '<td class="text-right bg-c1">'.custom_format($jumlah_m0).'</td>';
		for ($i=1; $i <=12 ; $i++) { 
			$x = ${'m0'.$i};
			$item .= '<td class="text-right bg-c1">'.custom_format($x).'</td>';
		}
		$item .= '</tr>';
		$item .= $item_m0;
	}
	echo $item;
?>