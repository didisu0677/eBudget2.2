<?php

$item = '<tr><th colspan = 4>Nama Cabang</th></tr>';
$bg = ' class="bg-grey"';
$bd = ' class="border-none bg-white"';
$tr = ' class="text-right"';
foreach ($coa as $k => $v) {
	$item2 = '';
	if(isset($detail['1'][$v->glwnco])){
		foreach ($detail['1'][$v->glwnco] as $k2 => $v2) {
			$item3 = '';
			if(isset($detail['2'][$v2->glwnco])){
				foreach ($detail['2'][$v2->glwnco] as $k3 => $v3) {
					$item4 = '';
					if(isset($detail['3'][$v3->glwnco])){
						foreach ($detail['3'][$v3->glwnco] as $k4 => $v4) {
							$item5 = '';
							if(isset($detail['4'][$v4->glwnco])){
								foreach ($detail['4'][$v4->glwnco] as $k5 => $v5) {
									$item6 = '';
									if(isset($detail['5'][$v5->glwnco])){
										foreach ($detail['5'][$v5->glwnco] as $k6 => $v6) {
											$item6 .= '<tr>';
											$item6 .= '<td>'.$v6->glwsbi.'</td>';
											$item6 .= '<td>'.$v6->glwcoa.'</td>';
											$item6 .= '<td>'.$v6->glwnco.'</td>';
											$item6 .= '<td class="sb-6">'.remove_spaces($v6->glwdes).'</td>';
											$item6 .= '</tr>';
										}
									}

									$item5 .= '<tr>';
									$item5 .= '<td>'.$v5->glwsbi.'</td>';
									$item5 .= '<td>'.$v5->glwcoa.'</td>';
									$item5 .= '<td>'.$v5->glwnco.'</td>';
									$item5 .= '<td class="sb-5">'.remove_spaces($v5->glwdes).'</td>';
									$item5 .= '</tr>';
									$item5 .= $item6;
								}	
							}
							$item4 .= '<tr>';
							$item4 .= '<td>'.$v4->glwsbi.'</td>';
							$item4 .= '<td>'.$v4->glwcoa.'</td>';
							$item4 .= '<td>'.$v4->glwnco.'</td>';
							$item4 .= '<td class="sb-4">'.remove_spaces($v4->glwdes).'</td>';
							$item4 .= '</tr>';
							$item4 .= $item5;
						}
						
					}

					$item3 .= '<tr>';
					$item3 .= '<td>'.$v3->glwsbi.'</td>';
					$item3 .= '<td>'.$v3->glwcoa.'</td>';
					$item3 .= '<td>'.$v3->glwnco.'</td>';
					$item3 .= '<td class="sb-3">'.remove_spaces($v3->glwdes).'</td>';
					$item3 .= '</tr>';
					$item3 .= $item4;
				}
				
			}
			$item2 .= '<tr>';
			$item2 .= '<td>'.$v2->glwsbi.'</td>';
			$item2 .= '<td>'.$v2->glwcoa.'</td>';
			$item2 .= '<td>'.$v2->glwnco.'</td>';
			$item2 .= '<td class="sb-2">'.remove_spaces($v2->glwdes).'</td>';
			$item2 .= '</tr>';
			$item2 .= $item3;
		}	
	}

	$item .= '<tr>';
	$item .= '<td>'.$v->glwsbi.'</td>';
	$item .= '<td>'.$v->glwcoa.'</td>';
	$item .= '<td>'.$v->glwnco.'</td>';
	$item .= '<td>'.remove_spaces($v->glwdes).'</td>';
	$item .= '</tr>';
	$item .= $item2;
}
echo $item;
function check_data($v,$x){
	$val = kali_minus($v,$x);
	$val = custom_format($val);
	// $val = custom_format(view_report($val));
	return $val;
}
?>