<?php
	$item = '';
	foreach($coa as $m0){
		$item .= '<tr>';
		$item .= '<td>'.$m0->glwnco.'</td>';
		$item .= '<td>'.$m0->glwdes.'</td>';
		for($i = 1; $i <= 12; $i++){
			$v_field = "bulan".$i;
			if($m0->$v_field != null){
				$content = $m0->$v_field;
			}else {
				$content = 1;
			}
			$item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right" data-name="'.$v_field.'" data-id="'.$m0->glwnco.'" data-value="'.$m0->$v_field.'">'.$content.'</div></td>';
		}
		$item .= '</tr>';		
	}
	echo $item;

?>