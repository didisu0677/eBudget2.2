<?php
$item = '';
$bg = ' class="bg-grey"';
$bd = ' class="border-none bg-white"';
$tr = ' class="text-right"';
foreach ($coa as $k => $v) {
	$item2 = '';
	$dt2 = [];
	$minus = $v->kali_minus;
	if(isset($detail['1'][$v->glwnco])){
		foreach ($detail['1'][$v->glwnco] as $k2 => $v2) {
			$item3 = '';
			$dt3 = [];
			$minus2 = $v2->kali_minus;
			if(isset($detail['2'][$v2->glwnco])){
				foreach ($detail['2'][$v2->glwnco] as $k3 => $v3) {
					$item4 = '';
					$dt4   = [];
					$minus3 = $v3->kali_minus;
					if(isset($detail['3'][$v3->glwnco])){
						foreach ($detail['3'][$v3->glwnco] as $k4 => $v4) {
							$item5 = '';
							$dt5   = [];
							$minus4 = $v4->kali_minus;
							if(isset($detail['4'][$v4->glwnco])){
								foreach ($detail['4'][$v4->glwnco] as $k5 => $v5) {
									$item6 	= '';
									$dt6 	= [];
									$minus5 = $v5->kali_minus;
									if(isset($detail['5'][$v5->glwnco])){
										foreach ($detail['5'][$v5->glwnco] as $k6 => $v6) {
											$item6 .= '<tr>';
											$item6 .= '<td>'.$v6->glwsbi.'</td>';
											$item6 .= '<td>'.$v6->glwcoa.'</td>';
											$item6 .= '<td>'.$v6->glwnco.'</td>';
											$item6 .= '<td class="sb-6">'.remove_spaces($v6->glwdes).'</td>';
											$bln_trakhir = $v6->{'VAL_'.$cabang};
											$value = (float) $bln_trakhir/10;
											$minus6 = $v6->kali_minus;
											for ($i=1; $i <=12 ; $i++) {
												$val = $value * $i;
												if(isset($dt6[$i])){ $dt6[$i] += $val; }else{ $dt6[$i] = $val; }
												$item6 .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right" data-name="'.$v6->{'VAL_'.$cabang}.'" data-id="'.$v6->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus6).'</div></td>';

												// $item6 .= '<td>'.check_data($val,$minus6).'</td>';
											}
											$item6 .= '<td'.$bd.'></td>';
											$item6 .= '<td'.$bg.'>'.check_data($value,$minus6).'</td>';
											$item6 .= '<td'.$bg.'>'.check_data($bln_trakhir,$minus6).'</td>';
											$item6 .= '</tr>';
										}
										$bln_trakhir = '';
										$value = '';
										$valueTxt = '';
									}else{
										$bln_trakhir = $v5->{'VAL_'.$cabang};
										$value 		 = (float) $bln_trakhir/10;
										$valueTxt 	 = check_data($value,$minus5);
										$bln_trakhir = check_data($bln_trakhir,$minus5);
									}

									$item5 .= '<tr>';
									$item5 .= '<td>'.$v5->glwsbi.'</td>';
									$item5 .= '<td>'.$v5->glwcoa.'</td>';
									$item5 .= '<td>'.$v5->glwnco.'</td>';
									$item5 .= '<td class="sb-5">'.remove_spaces($v5->glwdes).'</td>';
									for ($i=1; $i <= 12 ; $i++) { 
										if(count($dt6)>0){ 
											$val = $dt6[$i]; 
											$item5 .= '<td '.$tr.'>'.check_data($val,$minus5).'</td>';
										}
										else{ 
											$val = $value * $i; 
											$item5.= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right" data-name="'.$v5->{'VAL_'.$cabang}.'" data-id="'.$v5->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus5).'</div></td>';
										}
										if(isset($dt5[$i])){ $dt5[$i] += $val; }else{ $dt5[$i] = $val; }
									}
									$item5 .= '<td'.$bd.'></td>';
									$item5 .= '<td'.$bg.'>'.$valueTxt.'</td>';
									$item5 .= '<td'.$bg.'>'.$bln_trakhir.'</td>';
									$item5 .= '</tr>';
									$item5 .= $item6;
								}
								$bln_trakhir = '';
								$value = '';
								$valueTxt = '';
							}else{
								$bln_trakhir = $v4->{'VAL_'.$cabang};
								$value 		 = (float) $bln_trakhir/10;
								$valueTxt 	 = check_data($value,$minus4);
								$bln_trakhir = check_data($bln_trakhir,$minus4);
							}

							$item4 .= '<tr>';
							$item4 .= '<td>'.$v4->glwsbi.'</td>';
							$item4 .= '<td>'.$v4->glwcoa.'</td>';
							$item4 .= '<td>'.$v4->glwnco.'</td>';
							$item4 .= '<td class="sb-4">'.remove_spaces($v4->glwdes).'</td>';
							for ($i=1; $i <= 12 ; $i++) {
								if(count($dt5)>0){ 
									$val = $dt5[$i]; 
									$item4 .= '<td '.$tr.'>'.check_data($val,$minus4).'</td>';
								}
								else{ 
									$val = $value * $i; 
									$item4.= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right" data-name="'.$v4->{'VAL_'.$cabang}.'" data-id="'.$v4->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus4).'</div></td>';
								}
								if(isset($dt4[$i])){ $dt4[$i] += $val; }else{ $dt4[$i] = $val; }
							}
							$item4 .= '<td'.$bd.'></td>';
							$item4 .= '<td'.$bg.'>'.$valueTxt.'</td>';
							$item4 .= '<td'.$bg.'>'.$bln_trakhir.'</td>';
							$item4 .= '</tr>';
							$item4 .= $item5;
						}
						$bln_trakhir = '';
						$value = '';
						$valueTxt = '';
					}else{
						$bln_trakhir = $v3->{'VAL_'.$cabang};
						$value 		 = (float) $bln_trakhir/10;
						$valueTxt 	 = check_data($value,$minus3);
						$bln_trakhir = check_data($bln_trakhir,$minus3);
					}

					$item3 .= '<tr>';
					$item3 .= '<td>'.$v3->glwsbi.'</td>';
					$item3 .= '<td>'.$v3->glwcoa.'</td>';
					$item3 .= '<td>'.$v3->glwnco.'</td>';
					$item3 .= '<td class="sb-3">'.remove_spaces($v3->glwdes).'</td>';
					for ($i=1; $i <= 12 ; $i++) {
						if(count($dt4)>0){ 
							$val = $dt4[$i]; 
							$item3 .= '<td '.$tr.'>'.check_data($val,$minus3).'</td>';
						}
						else{ 
							$val = $value * $i; 
							$item3.= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right" data-name="'.$v3->{'VAL_'.$cabang}.'" data-id="'.$v3->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus3).'</div></td>';
						}
						if(isset($dt3[$i])){ $dt3[$i] += $val; }else{ $dt3[$i] = $val; }
					}
					$item3 .= '<td'.$bd.'></td>';
					$item3 .= '<td'.$bg.'>'.$valueTxt.'</td>';
					$item3 .= '<td'.$bg.'>'.$bln_trakhir.'</td>';
					$item3 .= '</tr>';
					$item3 .= $item4;
				}
				$bln_trakhir = '';
				$value = '';
				$valueTxt = '';
			}else{
				$bln_trakhir = $v2->{'VAL_'.$cabang};
				$value 		 = (float) $bln_trakhir/10;
				$valueTxt 	 = check_data($value,$minus2);
				$bln_trakhir = check_data($bln_trakhir,$minus2);
			}

			$item2 .= '<tr>';
			$item2 .= '<td>'.$v2->glwsbi.'</td>';
			$item2 .= '<td>'.$v2->glwcoa.'</td>';
			$item2 .= '<td>'.$v2->glwnco.'</td>';
			$item2 .= '<td class="sb-2">'.remove_spaces($v2->glwdes).'</td>';
			for ($i=1; $i <=12 ; $i++) { 
				if(count($dt3)>0){ 
					$val = $dt3[$i]; 
					$item2 .= '<td '.$tr.'>'.check_data($val,$minus2).'</td>';
				}
				else{ 
					$val = $value * $i; 
					$item2.= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right" data-name="'.$v2->{'VAL_'.$cabang}.'" data-id="'.$v2->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus2).'</div></td>';
				}
				if(isset($dt2[$i])){ $dt2[$i] += $val; }else{ $dt2[$i] = $val; }
			}
			$item2 .= '<td'.$bd.'></td>';
			$item2 .= '<td'.$bg.'>'.$valueTxt.'</td>';
			$item2 .= '<td'.$bg.'>'.$bln_trakhir.'</td>';
			$item2 .= '</tr>';
			$item2 .= $item3;
		}
		$bln_trakhir = '';
		$value = '';
		$valueTxt = '';
	}else{
		$bln_trakhir = $v->{'VAL_'.$cabang};
		$value 		 = (float) $bln_trakhir/10;
		$valueTxt 	 = check_data($value,$minus);
		$bln_trakhir = check_data($bln_trakhir,$minus);
	}

	$item .= '<tr>';
	$item .= '<td>'.$v->glwsbi.'</td>';
	$item .= '<td>'.$v->glwcoa.'</td>';
	$item .= '<td>'.$v->glwnco.'</td>';
	$item .= '<td>'.remove_spaces($v->glwdes).'</td>';
	for ($i=1; $i <= 12 ; $i++) { 
		if(count($dt2)>0){ 
			$val = $dt2[$i]; 
			$item .= '<td '.$tr.'>'.check_data($val,$minus).'</td>';
		}
		else{ 
			$val = $value * $i; 
			$item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right" data-name="'.$v->{'VAL_'.$cabang}.'" data-id="'.$v->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus).'</div></td>';
		}
		$item .= '<td>'.check_data($val,$minus).'</td>';
	}
	$item .= '<td'.$bd.'></td>';
	$item .= '<td'.$bg.'>'.$valueTxt.'</td>';
	$item .= '<td'.$bg.'>'.$bln_trakhir.'</td>';
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