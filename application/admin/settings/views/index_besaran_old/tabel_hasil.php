<?php
	$item = '';

	foreach($cabang[0] as $m0){
		$dt1 = [];
		$item1 = '';
		$item0 = '';
		foreach($cabang[$m0->getId] as $m1) {
			$dt2 = [];
			$item2 = '';
			foreach($cabang[$m1->getId] as $m2) {
				$item3 	= '';
				$dt3 	= [];
				foreach($cabang[$m2->getId] as $m3 => $val) {
					
					if(empty($val->parent_id)){
						$item3 .= '<tr>|';
						$item3 .= '<td class="sub-3">'.$val->nama_cabang.'</td>|';
						// if(!empty($cabang2[$m2->getId][$m3])){
						// 	for($a=$tahun[0]['bulan_terakhir_realisasi']+1;$a <= 12; $a++){
						// 		$v_field = "hasil".$a;
						// 		if($val->$v_field != null){
						// 			$content = $cabang2[$m2->getId][$m3][$v_field];
						// 		}else {
						// 			$content = 0;
						// 		}
						// 		$i = $a.'a';
						// 		if(isset($dt3[$i])){ $dt3[$i] += $content; }else{ $dt3[$i] = $content; }
						// 		$item3 .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right" data-name="'.$v_field.'" data-id="2-'.$val->kode_cabang.'" data-value="'.$content.'">'.custom_format(view_report($content)).'</div></td>|';
						// 	}
						// }					

					for($i = 1; $i <= 12; $i++){
						$v_field = "hasil".$i;
						if($val->$v_field != null){
							$content = $val->$v_field;
						}else {
							$content = 0;
						}
						if(isset($dt3[$i])){ $dt3[$i] += $content; }else{ $dt3[$i] = $content; }
						$item3 .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right" data-name="1-'.$v_field.'" data-id="1-'.$val->kode_cabang.'" data-value="'.$val->$v_field.'">'.custom_format(view_report($content)).'</div></td>|';
					}
					$item3 .= '</tr>|';		
				}
				}
				if(empty($m2->parent_id)){
					$item2 .= '<tr   style="background:#00bcd482;">|';
					$item2 .= '<td class="sub-2">'.$m2->nama_cabang.'</td>|';
					// for($a=$tahun[0]['bulan_terakhir_realisasi']+1;$a <= 12; $a++){
					// 	$i = $a.'a';
					// 	if(count($dt3) > 0){
					// 		$val = $dt3[$i];
					// 	}else{
					// 		$val = 0;
					// 	}
						
					// 	$item2 .= '<td class = "text-right">'.custom_format(view_report($val)).'</td>|';
					// 	if(isset($dt2[$i])){ $dt2[$i] += $val; }else{ $dt2[$i] = $val; }
					// 	}
					for($i = 1; $i <= 12; $i++){
						if(count($dt3) > 0){
							$val = $dt3[$i];
						}else{
							$val = 0;
						}
						
						$item2 .='<td class = "text-right">'.custom_format(view_report($val)).'</td>|';
						if(isset($dt2[$i])){ $dt2[$i] += $val; }else{ $dt2[$i] = $val; }
					}
					$item2 .= '</tr>|';
					$item2 .= $item3;


				}
			}
			$item1 .= '';
			$item1 .= '<td class="sub-1">'.$m1->nama_cabang.'</td>|';
			// for($a=$tahun[0]['bulan_terakhir_realisasi']+1;$a <= 12; $a++){
			// 	$i = $a.'a';
			// 	if(count($dt2) > 0){
			// 		$val = $dt2[$i];
			// 	}else{
			// 		$val = 0;
			// 	}
			// 	$item1 .= '<td class = "text-right">'.custom_format(view_report($val)).'</td>|';
			// 	if(isset($dt1[$i])){ $dt1[$i] += $val; }else{ $dt1[$i] = $val; }
			// }
			for($i = 1; $i <= 12; $i++){
				if(count($dt2) > 0){
					$val = $dt2[$i];
				}else{
					$val = 0;
				}
				$item1 .='<td class = "text-right">'.custom_format(view_report($val)).'</td>|';
				if(isset($dt1[$i])){ $dt1[$i] += $val; }else{ $dt1[$i] = $val; }
			}
			$item1 .= '</tr>|';
			$item1 .= $item2;
		}
		$item0 .= '<tr >|';
		$item0 .= '<td class="">'.$m0->nama_cabang.'</td>|';
		// for($a=$tahun[0]['bulan_terakhir_realisasi']+1;$a <= 12; $a++){
		// 	$i = $a.'a';
		// 	if(count($dt1) > 0){
		// 		$val = $dt1[$i];
		// 	}else{
		// 		$val = 0;
		// 	}
		// 	$item0 .= '<td class = "text-right">'.custom_format(view_report($val)).'</td>|';
		// }
		for($i = 1; $i <= 12; $i++){
			if(count($dt1) > 0){
				$val = $dt1[$i];
			}else{
				$val = 0;
			}
			$item0 .= '<td class = "text-right">'.custom_format(view_report($val)).'</td>|';
		}
			$item0 .= '</tr>|';
			$item0 .= $item1;
		$item .= $item0;
	}
	// echo $item;

	$itemExp = explode("|", $item);
	foreach ($itemExp as $key => $value) {
		echo $value."\n";
	}


?>