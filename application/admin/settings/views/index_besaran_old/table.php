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

				foreach($cabang[$m2->getId] as $m3) {
					$item .= '<tr>';
					$item .= '<td class="sub-3">'.$m3->nama_cabang.'</td>';
					for($i = 1; $i <= 12; $i++){
						$v_field = "bulan".$i;
						if($m3->$v_field != null){
							$content = $m3->$v_field;
						}else {
							$content = 1;
						}
						$item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right" data-name="'.$v_field.'" data-id="'.$m3->getId.'" data-value="'.$m3->$v_field.'">'.$content.'</div></td>';
					}
					$item .= '</tr>';
				}
			}
		}
	}
	echo $item;

?>