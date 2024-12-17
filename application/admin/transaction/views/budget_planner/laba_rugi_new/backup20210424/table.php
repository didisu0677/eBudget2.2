<?php
$item = '';
$bg = ' class="bg-grey"';
$bd = ' class="border-none bg-white"';
$tr = ' class="text-right"';
$totalPend = [];
$totalBiaya = [];
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
											// $item6 .= '<td>'.$v6->glwcoa.'</td>';
											$item6 .= '<td>'.$v6->glwnco.'</td>';
											$item6 .= '<td class="sb-6">'.remove_spaces($v6->glwdes).'</td>';
											$bln_trakhir = $v6->{'TOT_'.$cabang};
											$value = (float) $bln_trakhir/$bulan_terakhir;
											$minus6 = $v6->kali_minus;
											$tambah = 0;
											$tambah2 = 0;
											for ($i=1; $i <=12 ; $i++) {
												// $val = $value * $i;
												$tambah2 = $tambah2 + $value;
												$val =  $tambah2;
												$getStored = array_search($v6->glwnco, array_column($stored, 'glwnco')); 
												if($getStored > 0){
													$bulan = "bulan_".$i;
													$val = $stored[$getStored][$bulan];

													if($stored[$getStored]['tipe'] == 'biaya'){
														$tambah = $tambah + $val;

														$val =  $tambah;
													}
												}
												if(substr($v6->glwnco ,0,3) == '5' and  $val > 0 ){
													$val = $val * -1;
												}

												if(isset($dt6[$i])){ $dt6[$i] += $val; }else{ $dt6[$i] = $val; }
												$item6 .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value edited text-right" data-name="bulan_'.$i.'" data-id="'.$v6->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus6).'</div></td>';

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
										$bln_trakhir = $v5->{'TOT_'.$cabang};
										$value 		 = (float) $bln_trakhir/$bulan_terakhir;
										$valueTxt 	 = check_data($value,$minus5);
										$bln_trakhir = check_data($bln_trakhir,$minus5);
									}

									$item5 .= '<tr>';
									$item5 .= '<td>'.$v5->glwsbi.'</td>';
									// $item5 .= '<td>'.$v5->glwcoa.'</td>';
									$item5 .= '<td>'.$v5->glwnco.'</td>';
									$item5 .= '<td class="sb-5">'.remove_spaces($v5->glwdes).'</td>';
									$tambah = 0;$tambah2=0;
									for ($i=1; $i <= 12 ; $i++) { 
										if(count($dt6)>0){ 
											$val = $dt6[$i]; 
											$getStored = array_search($v5->glwnco, array_column($stored, 'glwnco')); 
											if($getStored > 0){
												$bulan = "bulan_".$i;
												$val = $stored[$getStored][$bulan];
												if($stored[$getStored]['tipe'] == 'biaya'){
													$tambah = $tambah + $val;

													$val =  $tambah;
												}
											}
											if(substr($v5->glwnco ,0,1) == '5' and $val > 0 ){
													$val = $val * -1;
												}

											$item5.= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="edited edit-value text-right" data-name="bulan_'.$i.'" data-id="'.$v5->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus5).'</div></td>';
											// $item5 .= '<td '.$tr.'>'.check_data($val,$minus5).'</td>';
										}
										else{ 
											$tambah2 = $tambah2 + $value;
											$val =  $tambah2; 
											$getStored = array_search($v5->glwnco, array_column($stored, 'glwnco')); 
												if($getStored > 0){
													$bulan = "bulan_".$i;
													$val = $stored[$getStored][$bulan];
													if($stored[$getStored]['tipe'] == 'biaya'){
														$tambah = $tambah + $val;

														$val =  $tambah;
													}
												}
												if(substr($v5->glwnco ,0,1) == '5' and $val > 0 ){
													$val = $val * -1;
												}
											$item5.= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value edited text-right" data-name="bulan_'.$i.'" data-id="'.$v5->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus5).'</div></td>';
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
								$bln_trakhir = $v4->{'TOT_'.$cabang};
								$value 		 = (float) $bln_trakhir/$bulan_terakhir;
								$valueTxt 	 = check_data($value,$minus4);
								$bln_trakhir = check_data($bln_trakhir,$minus4);
							}

							$item4 .= '<tr>';
							$item4 .= '<td>'.$v4->glwsbi.'</td>';
							// $item4 .= '<td>'.$v4->glwcoa.'</td>';
							$item4 .= '<td>'.$v4->glwnco.'</td>';
							$item4 .= '<td class="sb-4">'.remove_spaces($v4->glwdes).'</td>';
							$tambah = 0;$tambah2=0;
							for ($i=1; $i <= 12 ; $i++) {
								if(count($dt5)>0){ 
									$val = $dt5[$i]; 

									$getStored = array_search($v4->glwnco, array_column($stored, 'glwnco')); 
									if($getStored > 0){
										$bulan = "bulan_".$i;
										$val = $stored[$getStored][$bulan];
										if($stored[$getStored]['tipe'] == 'biaya'){
											$tambah = $tambah + $val;

											$val =  $tambah;
										}
										$cek415 = multidimensional_search($stored, array(
											'glwnco' => $v4->glwnco,
											'tipe'	=> 'adj',
										));
										
										if(strlen($cek415)>0){
											$val = $stored[$cek415][$bulan];
										}
									}
									
									// $getStored = array_keys(array_column($stored, 'glwnco'),$v4->glwnco); 
									// $count = count($getStored);
									// if($count > 0){
									// 	$bulan = "bulan_".$i;
									// 	$val = $stored[$count - 1][$bulan];
									// 	if($stored[$count - 1]['tipe'] == 'biaya'){
									// 		$tambah = $tambah + $val;

									// 		$val =  $tambah;
									// 	}
									// 	// $val = $getStored * 1000000;
									// }
									if(substr($v4->glwnco ,0,1) == '5' and $val > 0 ){
										$val = $val * -1;
									}
									$item4.= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="edit-value edited text-right" data-name="bulan_'.$i.'" data-id="'.$v4->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus4).'</div></td>';
									// $item4 .= '<td '.$tr.'>'.check_data($val,$minus4).'</td>';
								}
								else{ 
									$tambah2 = $tambah2 + $value;
									$val =  $tambah2; 

									$getStored = array_search($v4->glwnco, array_column($stored, 'glwnco')); 
									if($getStored > 0){
										$bulan = "bulan_".$i;
										$val = $stored[$getStored][$bulan];
										if($stored[$getStored]['tipe'] == 'biaya'){
											$tambah = $tambah + $val;

											$val =  $tambah;
										}
										$cek415 = multidimensional_search($stored, array(
													'glwnco' => $v4->glwnco,
													'tipe'	=> 'adj',
												));
										
										if(strlen($cek415)>0){
											$val = $stored[$cek415][$bulan];
										}
							
									}
									// $getStored = array_keys(array_column($stored, 'glwnco'),$v4->glwnco); 
									// $count = count($getStored);
									// if($count > 0){
									// 	$bulan = "bulan_".$i;
									// 	$val = $stored[$count - 1][$bulan];
									// 	if($stored[$count - 1]['tipe'] == 'biaya'){
									// 		$tambah = $tambah + $val;

									// 		$val =  $tambah;
									// 	}
									// 	// $val = $getStored * 1000000;
									// }
									if(substr($v4->glwnco ,0,1) == '5' and $val > 0 ){
										$val = $val * -1;
									}
									$item4.= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edited edit-value text-right" data-name="bulan_'.$i.'" data-id="'.$v4->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus4).'</div></td>';
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
						$bln_trakhir = $v3->{'TOT_'.$cabang};
						$value 		 = (float) $bln_trakhir/$bulan_terakhir;
						$valueTxt 	 = check_data($value,$minus3);
						$bln_trakhir = check_data($bln_trakhir,$minus3);
					}

					$item3 .= '<tr>';
					$item3 .= '<td>'.$v3->glwsbi.'</td>';
					// $item3 .= '<td>'.$v3->glwcoa.'</td>';
					$item3 .= '<td>'.$v3->glwnco.'</td>';
					$item3 .= '<td class="sb-3">'.remove_spaces($v3->glwdes).'</td>';
					$tambah = 0;$tambah2=0;
					for ($i=1; $i <= 12 ; $i++) {
						if(count($dt4)>0){ 
							$val = $dt4[$i]; 
							$getStored = array_search($v3->glwnco, array_column($stored, 'glwnco')); 
							if($getStored > 0){
								$bulan = "bulan_".$i;
								$val = $stored[$getStored][$bulan];
								if($stored[$getStored]['tipe'] == 'biaya'){
									$tambah = $tambah + $val;

									$val =  $tambah;
								}
							}
							if(substr($v3->glwnco ,0,1) == '5' and $val > 0 ){
								$val = $val * -1;
							}
							$item3.= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="edited edit-value text-right" data-name="bulan_'.$i.'" data-id="'.$v3->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus3).'</div></td>';
							// $item3 .= '<td '.$tr.'>'.check_data($val,$minus3).'</td>';
						}
						else{ 
							$tambah2 = $tambah2 + $value;
							$val =  $tambah2; 
							$getStored = array_search($v3->glwnco, array_column($stored, 'glwnco')); 
							if($getStored > 0){
								$bulan = "bulan_".$i;
								$val = $stored[$getStored][$bulan];
								if($stored[$getStored]['tipe'] == 'biaya'){
									$tambah = $tambah + $val;

									$val =  $tambah;
								}
							}
							if(substr($v3->glwnco ,0,1) == '5' and $val > 0 ){
								$val = $val * -1;
							}
							$item3.= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value edited text-right" data-name="bulan_'.$i.'" data-id="'.$v3->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus3).'</div></td>';
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
				$bln_trakhir = $v2->{'TOT_'.$cabang};
				$value 		 = (float) $bln_trakhir/$bulan_terakhir;
				$valueTxt 	 = check_data($value,$minus2);
				$bln_trakhir = check_data($bln_trakhir,$minus2);
			}

			$item2 .= '<tr>';
			$item2 .= '<td>'.$v2->glwsbi.'</td>';
			// $item2 .= '<td>'.$v2->glwcoa.'</td>';
			$item2 .= '<td>'.$v2->glwnco.'</td>';
			$item2 .= '<td class="sb-2">'.remove_spaces($v2->glwdes).'</td>';
			$tambah = 0;$tambah2=0;
			for ($i=1; $i <=12 ; $i++) { 
				if(count($dt3)>0){ 
					$val = $dt3[$i]; 
					$getStored = array_search($v2->glwnco, array_column($stored, 'glwnco')); 
					if($getStored > 0){
						$bulan = "bulan_".$i;
						$val = $stored[$getStored][$bulan];
						if($stored[$getStored]['tipe'] == 'biaya'){
							$tambah = $tambah + $val;

							$val =  $tambah;
						}
					}
					if(substr($v2->glwnco ,0,1) == '5' and $val > 0 ){
						$val = $val * -1;
					}
					$item2.= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="text-right edited" data-name="bulan_'.$i.'" data-id="'.$v2->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus2).'</div></td>';
					// $item2 .= '<td '.$tr.'>'.check_data($val,$minus2).'</td>';
				}
				else{ 
					$tambah2 = $tambah2 + $value;
					$val =  $tambah2; 
					$getStored = array_search($v2->glwnco, array_column($stored, 'glwnco')); 
					if($getStored > 0){
						$bulan = "bulan_".$i;
						$val = $stored[$getStored][$bulan];
						if($stored[$getStored]['tipe'] == 'biaya'){
							$tambah = $tambah + $val;

							$val =  $tambah;
						}
					}
					if(substr($v2->glwnco ,0,1) == '5' and $val > 0 ){
						$val = $val * -1;
					}
					$item2.= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value edited text-right" data-name="bulan_'.$i.'" data-id="'.$v2->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus2).'</div></td>';
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
		$bln_trakhir = $v->{'TOT_'.$cabang};
		$value 		 = (float) $bln_trakhir/$bulan_terakhir;
		$valueTxt 	 = check_data($value,$minus);
		$bln_trakhir = check_data($bln_trakhir,$minus);
	}

	$item .= '<tr>';
	$item .= '<td>'.$v->glwsbi.'</td>';
	// $item .= '<td>'.$v->glwcoa.'</td>';
	$item .= '<td>'.$v->glwnco.'</td>';
	$item .= '<td>'.remove_spaces($v->glwdes).'</td>';
	$tambah = 0;
	$tambah2=0;
	for ($i=1; $i <= 12 ; $i++) { 
		if(count($dt2)>0){ 
			$val = $dt2[$i]; 
			$getStored = array_search($v->glwnco, array_column($stored, 'glwnco')); 
			if($getStored > 0){
				$bulan = "bulan_".$i;
				$val = $stored[$getStored][$bulan];
				if($stored[$getStored]['tipe'] == 'biaya'){
					$tambah = $tambah + $val;

					$val =  $tambah;
				}
			}
			if(substr($v->glwnco ,0,1) == '5' and $val > 0 ){
					$val = $val * -1;
				}
			$idTr = '';
			if($v->glwnco == '59999'){$idTr = "id = labarugi_".$i;$val = $totalPend[$i] - $totalBiaya[$i];}	
			$item .= '<td><div '.$idTr.' style="min-height: 10px; width: 100%; overflow: hidden;"  class="edited edit-value text-right" data-name="bulan_'.$i.'" data-id="'.$v->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus).'</div></td>';

			if($v->glwnco == '4100000' || $v->glwnco == '4500000' || $v->glwnco == '4800000'){
				if(isset($totalPend[$i])){ $totalPend[$i] += $val * 1; }else{ $totalPend[$i] = $val * 1; }
			}
			else if($v->glwnco == '5100000' || $v->glwnco == '5500000' || $v->glwnco == '5800000'){
				if(isset($totalBiaya[$i])){ $totalBiaya[$i] += $val* -1; }else{ $totalBiaya[$i] = $val* -1; }
			}
			
		}
		else{ 
			$tambah2 = $tambah2 + $value;
			$val =  $tambah2; 
			$getStored = array_search($v->glwnco, array_column($stored, 'glwnco')); 
			if($getStored > 0){
				$bulan = "bulan_".$i;
				$val = $stored[$getStored][$bulan];
				if($stored[$getStored]['tipe'] == 'biaya'){
					$tambah = $tambah + $val;

					$val =  $tambah;
				}
				if(substr($v->glwnco ,0,1) == '5' and $val > 0 ){
					$val = $val * -1;
				}
			}
			$idTr = '';
			$n_pend = 0; if(isset($totalPend[$i])) $n_pend = $totalPend[$i];
			$n_biaya= 0; if(isset($totalBiaya[$i])) $n_biaya = $totalBiaya[$i];
			if($v->glwnco == '59999'){$idTr = "id = labarugi_".$i;$val = $n_pend - $n_biaya;}	
			$item .= '<td><div data-test="test"  '.$idTr.' style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value edited text-right" data-name="bulan_'.$i.'" data-id="'.$v->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus).'</div></td>';

			if($v->glwnco == '4100000' || $v->glwnco == '4500000' || $v->glwnco == '4800000'){
				if(isset($totalPend[$i])){ $totalPend[$i] += $val * 1; }else{ $totalPend[$i] = $val * 1; }
			}
			else if($v->glwnco == '5100000' || $v->glwnco == '5500000' || $v->glwnco == '5800000'){
				if(isset($totalBiaya[$i])){ $totalBiaya[$i] += $val * -1; }else{ $totalBiaya[$i] = $val* -1; }
			}
		}
		// $item .= '<td>'.check_data($val,$minus).'</td>';
	}
	$item .= '<td'.$bd.'></td>';
	$item .= '<td'.$bg.'>'.$valueTxt.'</td>';
	$item .= '<td'.$bg.'>'.$bln_trakhir.'</td>';
	$item .= '</tr>';
	$item .= $item2;
}

	$item .= "<tr><td class = border-none>.</td></tr>";
	$item .= "<tr><td class = border-none>.</td></tr>";
	$item .= "<center>.</center><center>.";

	$item .= '<tr style = "background: #FFF;">';
	$item .= '<td class = border-none></td>';
	$item .= '<td class = border-none></td>';
	$item .= '<td class = border-none><b style =font-size:12px>Laba rugi yang di inginkan</b></td>';
	$item .= '</tr>';
	$item .= '<tr style = "background: #FFF;">';
	$item .= '<td class = border-none>.</td>';
	$item .= '</tr>';
	// $hasil2 = $val->hasil2 * -1;

	$getAdjPd = array_search('pdbulan', array_column($adj, 'type'));

	$btn = '';
	if($access_additional):
		$btn = '<button type="button" class="btn btn-danger btn-remove" data-id="'.$cabang.'" title="Hapus"><i class="fa-times"></i></button>';
	endif;
	
	$item .= '<tr style = "background: #FFF;">';
	$item .= '<td></td>';
	$item .= '<td class="button">'.$btn.'</td>';
	$item .= '<td>Laba rugi pd bulan</td>';
	// $hasil2 = $val->hasil2 * -1;

	for ($i=1; $i <= 12 ; $i++) { 
		$nilaiAdjPd = "0";
		if(strlen($getAdjPd)>0){
			$bulan = 'bulan_'.$i;
			$nilaiAdjPd = $adj[$getAdjPd][$bulan];
		}
		$item .= '<td style="background:'.bgEdit().'"><div id="input'.$i.'" style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value edited text-right cuan pdbulan" data-name="bulan_'.$i.'" data-id="pdbulan" data-value="">'.check_data($nilaiAdjPd,0).'</div></td>';
	}

	$item .= '</tr>';


	$item .= '<tr style = "background: #FFF;">';
	$item .= '<td></td>';
	$item .= '<td></td>';
	$item .= '<td>Laba rugi s.d bulan</td>';
	// $hasil2 = $val->hasil2 * -1;
	$getAdjSel = array_search('sdbulan', array_column($adj, 'type'));
	for ($i=1; $i <= 12 ; $i++) { 
		$nilaiAdjSel = "0";
		if(strlen($getAdjSel)>0){
			$bulan = 'bulan_'.$i;
			$nilaiAdjSel = $adj[$getAdjSel][$bulan];
		}
	$item .= '<td><div id="hasil'.$i.'" style="min-height: 10px; width: 100%; overflow: hidden;" class="edit-value edited text-right sdbulan" data-name="bulan_'.$i.'" data-id="sdbulan" data-value="">'.check_data($nilaiAdjSel,0).'</div></td>';
	}

	$item .= '</tr>';
	
	if($access_additional):
		$item .= "<tr><td class = border-none>.</td></tr>";
		$item .= '<tr style = "background: #FFF;">';
		$item .= '<td></td>';
		$item .= '<td></td>';
		$item .= '<td>Selisih</td>';
		// $hasil2 = $val->hasil2 * -1;
		for ($i=1; $i <= 12 ; $i++) { 
		$item .= '<td><div  id="selisih'.$i.'" style="min-height: 10px; width: 100%; overflow: hidden;"  class="edit-value edited text-right adj" data-name="bulan_'.$i.'" data-id="selisih" data-value=""></div></td>';
		}
		$item .= '</tr>';
		$item .= '</tr>';

		$item .= '<tr style = "background: #FFF;hover:none">';
		$item .= '<td class = border-none></td>';
		$item .= '<td class = border-none></td>';
		$item .= '<td class = border-none>Di :
					<select id = "di">
						<option value = "t"> + </option>
						<option value = "k"> - </option>
					</select>
					Ke : 
					<select id = "ke">
						<option value = "4152128"> 4152128 </option>
						<option value = "4195011"> 4195011 </option>
						<option value = "5132012"> 5132012 </option>
						<option value = "5195011"> 5195011 </option>
					</select>
				</td>';
		$item .= '<td class = border-none><button class = "btn btn-primary btn-adj" style="width:100%;max-height:20px;padding: 0px;">Lakukan</button></td>';
		$item .= '</tr>';
	else:
		$item .= '<tr style = "background: #FFF;hover:none">';
		$item .= '<td class = border-none></td>';
		$item .= '<td class = border-none></td>';
		$item .= '<td class = border-none></td>';
		$item .= '<td class = border-none><button class = "btn btn-primary btn-adj" style="width:100%;max-height:20px;padding: 0px;">Lakukan</button></td>';
		$item .= '</tr>';
	endif;
	
	// $hasil2 = $val->hasil2 * -1;
	

	$item .= "<tr><td class = border-none>.</td></tr>";
	$item .= "<tr><td class = border-none>.</td></tr>";
	$item .= "<tr><td class = border-none>.</td></tr>";
	$item .="</center>";

echo $item;
function check_data($v,$x){


	$val = kali_minus($v,$x);
	// $val = custom_format($val);

	// $val = round($val,5);
	$val = custom_format(view_report($val));
	return $val;
}
?>