<?php
	$item = '';

	$item2 = '';
	$arrAssetTotal = [];
	foreach ($arrAdditional as $addit) {
		$key_addit      = 'additional_'.$addit['id'];
		$item2 .= '<tr>';
		$item2 .= '<td></td>';
		$item2 .= '<td></td>';
		$item2 .= '<td></td>';
		$item2 .= '<td><strong>'.$addit['nama'].'</strong></td>';
		for ($i=1; $i <= 12 ; $i++) { 
			$field  = 'B_' . sprintf("%02d", $i);
			$val 	= ${$key_addit}[$field];
			$item2 .= '<td class="text-right"><strong>'.check_value($val).'</strong></td>';
			if(!isset($arrAssetTotal[$field])):
				$arrAssetTotal[$field] = $val;
			else:
				if($arrAssetTotal[$field] < $val):
					$arrAssetTotal[$field] = $val;
				endif;
			endif;

		}
		$item2 .= '<td></td>';
		$item2 .= '<td></td>';
		$item2 .= '</tr>';
	}

	$item .= '<tr>';
	$item .= '<td></td>';
	$item .= '<td></td>';
	$item .= '<td></td>';
	$item .= '<td><strong>ASSET NETTO CABANG</strong></td>';
	for ($i=1; $i <= 12 ; $i++) { 
		$field  = 'B_' . sprintf("%02d", $i);
		$val 	= $arrAssetTotal[$field];
		$item .= '<td class="text-right"><strong>'.check_value($val).'</strong></td>';
	}
	$item .= '<td></td>';
	$item .= '<td></td>';
	$item .= '</tr>';

	$item .= $item2;
	echo $item;
?>