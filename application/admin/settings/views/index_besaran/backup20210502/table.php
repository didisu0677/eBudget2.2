<?php
	$item = '';
	foreach($cabang[0] as $m0){
		$item .= '<tr>';
		$item .= '<td>'.$m0->nama_cabang.'</td>';
		$item .= '<td colspan="12"><td>';
		$item .= '</tr>';
		foreach($cabang[$m0->getId] as $m1) {
			$item .= '<tr>';
			$item .= '<td class="sub-1">'.$m1->nama_cabang.'</td>';
			$item .= '<td colspan="12"><td>';
			$item .= '</tr>';
			foreach($cabang[$m1->getId] as $m2) {
				$item .= '<tr>';
				$item .= '<td class="sub-2">'.$m2->nama_cabang.'</td>';
				$item .= '<td colspan="12"><td>';
				$item .= '</tr>';

				foreach($cabang[$m2->getId] as $k => $m3) {
					$item .= '<tr>';
					$item .= '<td class="sub-3">'.$m3->nama_cabang.'</td>';

					if(!empty($cabang2[$m2->getId][$k])){
						for($a=$tahun[0]['bulan_terakhir_realisasi']+1;$a <= 12; $a++){
							$v_field = "bulan".$a;
							$content = $cabang2[$m2->getId][$k][$v_field];
							$item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right" data-name="'.$v_field.'" data-id="'.$m3->kode_cabang.'-2" data-value="'.$content.'">'.$content.'</div></td>';
						}
					}

					for($i = 1; $i <= 12; $i++){
						$v_field = "bulan".$i;
						$content = $m3->{$v_field};
						$item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right" data-name="'.$v_field.'" data-id="'.$m3->kode_cabang.'-3" data-value="'.$m3->$v_field.'">'.$content.'</div></td>';
					}
					$item .= '</tr>';
				}
			}
		}
	}
	echo $item;

?>