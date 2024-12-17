<?php
	$item = '';
	foreach ($cabang[0] as $m0) {
		$item .= '<tr>';
		$item .= '<td class="bg-c1">'.$m0->nama_cabang.'</td>';
		
		foreach ($detail_tahun as $k => $v) {
			$x = $v->bulan; if($v->bulan<10): $x = '0'.$v->bulan; endif;
			${'m0'.$v->tahun.'B_'.$x} = 0;
		}
		
		$item_m0 = '';
		foreach($cabang[$m0->id] as $m1){
			$item_m0 .= '<tr>';
			$item_m0 .= '<td class="sub-1 bg-c2">'.$m1->nama_cabang.'</td>';

			foreach ($detail_tahun as $k => $v) {
				$x = $v->bulan; if($v->bulan<10): $x = '0'.$v->bulan; endif;
				${'m1'.$v->tahun.'B_'.$x} = 0;
			}

			$item_m1 = '';
			foreach($cabang[$m1->id] as $m2){
				$item_m1 .= '<tr>';
				$item_m1 .= '<td class="sub-2 bg-c3">'.$m2->nama_cabang.'</td>';
				
				foreach ($detail_tahun as $k => $v) {
					$x = $v->bulan; if($v->bulan<10): $x = '0'.$v->bulan; endif;
					${'m2'.$v->tahun.'B_'.$x} = 0;
				}

				$item_m2 = '';
				foreach($cabang[$m2->id] as $m3){
					$item_m2 .= '<tr>';
					$item_m2 .= '<td class="sub-3">'.$m3->nama_cabang.'</td>';

					foreach ($detail_tahun as $k1 => $v1) {
						$key = multidimensional_search($dSum, array(
							'kode_cabang'=>$m3->kode_cabang,
							'sumber_data'=> $v1->sumber_data,
							'data_core'	=> $v1->tahun
						));
						if($v1->sumber_data == 2):
							if(strlen($key)<=0):
								$key = multidimensional_search($dSum, array(
									'kode_cabang'=>$m3->kode_cabang,
									'sumber_data'=> 1,
									'data_core'	=> $v1->tahun
								));
							endif;
						endif;
						if(strlen($key)>0):
							$x = $v1->bulan; if($v1->bulan<10): $x = '0'.$v1->bulan; endif;
							$k = 'B_'.$x;
							$kn = 'ori'.$v1->bulan;
							$detail = $dSum[$key];
							$value = checkInputNumber($dSum[$key][$k]);
							${'m2'.$v1->tahun.'B_'.$x} += $value;
							$item_m2 .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right edited" data-id = "'.$detail['kode_cabang'].'-'.$v1->sumber_data.'" data-name = "'.$kn.'" data-value = "'.$value.'">'.custom_format(view_report($value)).'</div></td>';
						else:
							$x = $v1->bulan; if($v1->bulan<10): $x = '0'.$v1->bulan; endif;
							$k = 'B_'.$x;
							$kn = 'ori'.$v1->bulan;
							$item_m2 .= '<td class="text-right"><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right edited" data-id = "'.$m3->kode_cabang.'-'.$v1->sumber_data.'" data-name = "'.$kn.'" data-value = "0">0</div></td>';
						endif;
					}

					$item_m2 .= '<tr>';
				}

				foreach ($detail_tahun as $k => $v) {
					$x = $v->bulan; if($v->bulan<10): $x = '0'.$v->bulan; endif;
					$value = ${'m2'.$v->tahun.'B_'.$x};
					${'m1'.$v->tahun.'B_'.$x} += $value;
					$item_m1 .= '<td class="text-right bg-c3">'.custom_format(view_report($value)).'</td>';
				}

				$item_m1 .= '</tr>';
				$item_m1 .= $item_m2;
			}

			foreach ($detail_tahun as $k => $v) {
				$x = $v->bulan; if($v->bulan<10): $x = '0'.$v->bulan; endif;
				$value = ${'m1'.$v->tahun.'B_'.$x};
				${'m0'.$v->tahun.'B_'.$x} += $value;
				$item_m0 .= '<td class="text-right bg-c2">'.custom_format(view_report($value)).'</td>';
			}

			$item_m0 .= '<tr>';
			$item_m0 .= $item_m1;
		}
		foreach ($detail_tahun as $k => $v) {
			$x = $v->bulan; if($v->bulan<10): $x = '0'.$v->bulan; endif;
			$value = ${'m0'.$v->tahun.'B_'.$x};
			$item .= '<td class="text-right bg-c1">'.custom_format(view_report($value)).'</td>';
		}

		$item .= '</tr>';
		$item .= $item_m0;
	}
	

	echo $item;

?>