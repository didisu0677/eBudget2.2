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

				if(empty($m2->parent_id)){
					$item .= '<tr>';
					$item .= '<td class="sub-2">'.$m2->nama_cabang.'</td>';
					$item .= '<td colspan="12"><td>';
					$item .= '</tr>';
				}

				foreach($cabang[$m2->getId] as $m3 => $val) {
					// if(empty($val->parent_id)){
					if(empty($val->parent_id)){
						$item .= '<tr>';
						$item .= '<td class="sub-3">'.$val->nama_cabang.'</td>';
					// }else{
						// $item .= '<tr>';
						// $item .= '<td class="sub-3"></td>';
					// }
					// if(!empty($val->parent_id)){
						if(!empty($cabang2[$m2->getId][$m3])){
							for($a=$tahun[0]['bulan_terakhir_realisasi']+1;$a <= 12; $a++){
								$v_field = "hasil".$a;
								if($val->$v_field != null){
									$content = $cabang2[$m2->getId][$m3][$v_field];
								}else {
									$content = 0;
								}
								$item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right" data-name="'.$v_field.'" data-id="2-'.$val->kode_cabang.'" data-value="'.$content.'">'.custom_format(view_report($content)).'</div></td>';
							}
						}
						
					// }else {
					// 	for($a=$tahun[0]['bulan_terakhir_realisasi']+1;$a <= 12; $a++){
					// 		$item .= '<td></td>';
					// 	}
					// }
					

					for($i = 1; $i <= 12; $i++){
						$v_field = "hasil".$i;
						if($val->$v_field != null){
							$content = $val->$v_field;
						}else {
							$content = 0;
						}
						$item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right" data-name="1-'.$v_field.'" data-id="1-'.$val->kode_cabang.'" data-value="'.$val->$v_field.'">'.custom_format(view_report($content)).'</div></td>';
					}
					$item .= '</tr>';		
				}
				}
			}
		}
	}
	echo $item;

?>