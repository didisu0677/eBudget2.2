<?php

$getTahunMin = $bulan_terakhir - 1;
$table ='<tr><td class="border-none">.</td></tr><tr><td class="border-none">.</td></tr>
		<tr style="margin-top:30px" class="tr-gray">
            <th width="60" class="text-center align-middle headcol">COA</th>
            <th class="text-center align-middle headcol" style="width:auto;min-width:230px;min-height: 50px;">KETERANGAN</th>
            ';
            for ($i = 1; $i <= 12; $i++) {
				$a = array_search($i, array_column($detail_tahun, 'bulan'));
				$column = month_lang($i);
				$column .= "<br> (".$detail_tahun[$a]['singkatan'].")";
				$table .= '<th class="text-center" style="min-width:80px;">'.strtoupper($column).'</th>';
			}
  $table .='<th class="border-none"></th>
            <th class="text-center" style="min-width:80px;">BIAYA PERBULAN</th>
            <th class="text-center" style="min-width:80px;">BIAYA PERTAHUN</th>
            <th class="border-none"></th>
            <th class="text-center" style="min-width:80px;">SISTEM</th>
            <th class="text-center" style="min-width:80px;">REAL '.strtoupper(month_lang($getTahunMin)).'</th>
            <th class="text-center" style="min-width:80px;">REAL '.strtoupper(month_lang($bulan_terakhir)).'</th>
        </tr>';
$item = $table;
$bg = ' class="text-right  bg-grey"';
$bd = ' class="text-right  border-none bg-white"';
$tr = ' class="text-right "';
foreach ($coa as $k => $v) {
	$item2 = '';
	$dt2 = [];
	$minus = $v->kali_minus;

	if(isset($detail['1'][$v->glwnco])){
		foreach ($detail['1'][$v->glwnco] as $k2 => $v2) {
			$item3 = '';
			$dt3 = [];
			$minus2 = $v2->kali_minus;

			if($v->glwnco == '5680000' || $v->glwnco == '5700000' || $v->glwnco == '5710000'  || $v->glwnco == '5720000' ){
				$editBulan2 = 'contenteditable="true"';
			}else {

				$editBulan2 = 'contenteditable="false"';
			}

			if($akses_ubah == 1){
				$editBulan2 = 'contenteditable="true"';
			}
			
			if(isset($detail['2'][$v2->glwnco])){
				foreach ($detail['2'][$v2->glwnco] as $k3 => $v3) {
					$item4 = '';
					$dt4   = [];
					$minus3 = $v3->kali_minus;
					// if($v3->glwnco == '5573012' || $v3->glwnco == '5573013' || $v3->glwnco == '5679012' || $v3->glwnco == '5679014' || $v3->glwnco == '5752030'){
					// 	$edit = 'contenteditable="true"';
					// }else {
					// 	$edit = 'contenteditable="false"';
					// }
					if($v2->glwnco == '5671000' || $v2->glwnco == '5751000' || $v2->glwnco == '5752000'){
						$editBulan3 = 'contenteditable="true"';
						$edit2 = 'contenteditable="false"';
						$editBulan = 'contenteditable="false"';
					}else {

						$edit2 = 'contenteditable="false"';
						$editBulan3 = 'contenteditable="false"';
						$editBulan = 'contenteditable="false"';
					}
					if($akses_ubah == 1){
						$editBulan3 = 'contenteditable="true"';
						$edit2 = 'contenteditable="true"';
						$editBulan = 'contenteditable="true"';
					}
					if(isset($detail['3'][$v3->glwnco])){
						foreach ($detail['3'][$v3->glwnco] as $k4 => $v4) {
							$item5 = '';
							$dt5   = [];
							$minus4 = $v4->kali_minus;
							$edit = 'contenteditable="false"';
							if($akses_ubah == 1){
								$edit = 'contenteditable="true"';
							}

							if(isset($detail['4'][$v4->glwnco])){
								foreach ($detail['4'][$v4->glwnco] as $k5 => $v5) {
									$item6 	= '';
									$dt6 	= [];
									$minus5 = $v5->kali_minus;
									$edit = 'contenteditable="false"';
									if($akses_ubah == 1){
										$edit = 'contenteditable="true"';
									}

									if(isset($detail['5'][$v5->glwnco])){
										foreach ($detail['5'][$v5->glwnco] as $k6 => $v6) {
											$item6 .= '<tr>';
											$item6 .= '<td>'.$v6->glwnco.'</td>';
											$item6 .= '<td class="sb-6">'.$v6->glwdes.'</td>';
											$bln_trakhir = $v6->{'TOT_'.$cabang};
											$value = (float) $bln_trakhir/10;
											$minus6 = $v6->kali_minus;
											for ($i=1; $i <=12 ; $i++) {
												$val = $value * $i;
												if(isset($dt6[$i])){ $dt6[$i] += $val; }else{ $dt6[$i] = $val; }
												$item6 .= '<td>'.check_data($val,$minus6).'</td>';
											}
											$item6 .= '<td'.$bd.'></td>';
											if(!empty($v6->biaya_bulan)){
												$valueTxt = $v6->biaya_bulan;
											}
											if(!empty($v6->biaya_tahun)){
												$bln_trakhir = $v6->biaya_tahun;
											}
											$item6 .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" '.$edit.' class="edit-value text-right edited" data-name="biaya_bulan" data-id="'.$v6->glwnco.'" data-value="'.$valueTxt.'">'.$valueTxt.'</div></td>';
											$item6 .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" '.$edit.' class="edit-value text-right edited" data-name="biaya_tahun" data-id="'.$v6->glwnco.'" data-value="'.$bln_trakhir.'">'.$bln_trakhir.'</div></td>';
											
											$item6 .= '</tr>';
										}
										$bln_trakhir = '';
										$value = '';
										$valueTxt = '';
									}else{
										$bln_trakhir = $v5->{'TOT_'.$cabang};
										$value 		 = (float) $bln_trakhir/10;
										$valueTxt 	 = check_data($value,$minus5);
										$bln_trakhir = check_data($bln_trakhir,$minus5);
									}

									$item5 .= '<tr>';
									$item5 .= '<td>'.$v5->glwnco.'</td>';
									$item5 .= '<td class="sb-5">'.$v5->glwdes.'</td>';
									for ($i=1; $i <= 12 ; $i++) { 
										if(count($dt6)>0){ 
											$val = $dt6[$i]; 
											$item5 .= '<td '.$tr.'>'.check_data($val,$minus5).'</td>';
										}
										else{ 
											
											$val = $value ; 
											if(!empty($v5->{'bulan'.$i})){
												$val = $val * $v5->{'bulan'.$i};
											}
											$item5 .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" '.$edit.' class="edit-value text-right edited" data-name="bulan_b'.$i.'" data-id="'.$v5->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus5).'</div></td>'; 
										} 
										if(isset($dt5[$i])){ $dt5[$i] += $val; }else{ $dt5[$i] = $val; }
									}
									$item5 .= '<td'.$bd.'></td>';
									if(!empty($v5->biaya_bulan)){
										$valueTxt = $v5->biaya_bulan;
									}
									if(!empty($v5->biaya_tahun)){
										$bln_trakhir = $v5->biaya_tahun;
									}
									$item5 .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" '.$edit.' class="edit-value text-right edited" data-name="biaya_bulan" data-id="'.$v6->glwnco.'" data-value="'.$valueTxt.'">'.check_data($valueTxt,0).'</div></td>';
									$item5 .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" '.$edit.' class="edit-value text-right edited" data-name="biaya_tahun" data-id="'.$v6->glwnco.'" data-value="'.$bln_trakhir.'">'.check_data($bln_trakhir,0).'</div></td>';
									$item5 .= '</tr>';
									$item5 .= $item6;
								}
								$bln_trakhir = '';
								$value = '';
								$valueTxt = '';
							}else{
								// $bln_trakhir = $v4->{'TOT_'.$cabang};
								$bln_trakhir = $v4->hasil;
								if($v3->glwnob == "53303" || $v3->glwnob == "53305" || $v3->glwnob == "53306" || $v3->glwnob == "54001" || $v3->glwnob == "54101" || $v3->glwnob == "53306"){
									$value 		 = (float) $bln_trakhir - $v4->hasil2;
								}else {
									$value 		 = (float) $bln_trakhir/ $bulan_terakhir;
								}
								if(!empty($v4->biaya_bulan)){
									$value = $v4->biaya_bulan;
								}
								$valueTxt 	 = check_data($value,$minus4);
								$getTahun	 = check_data($value*12, $minus4);
								$bln_trakhir = check_data($bln_trakhir,$minus4);
							}

							$item4 .= '<tr>';
							$item4 .= '<td>'.$v4->glwnco.'</td>';
							$item4 .= '<td class="sb-4">'.$v4->glwdes.'</td>';
							for ($i=1; $i <= 12 ; $i++) {
								if(count($dt5)>0){ 
									$val = $dt5[$i]; 
									$bulanb = "bulan_b".$i;
									if(!empty($v4->$bulanb)){
										$val = $v4->$bulanb ;
									}
									if($akses_ubah == 1){
										$edit= 'contenteditable="true"';
									}
									$item4 .= '<td '.$tr.'><div style="min-height: 10px; width: 100%; overflow: hidden;" '.$edit.' class="edit-value text-right edited" data-name="bulan_b'.$i.'" data-id="'.$v4->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus4).'</div></td>';
								}
								else{ 
									
									$val = $value ; 
									if(!empty($v4->{'bulan'.$i})){
										$val = $val * $v4->{'bulan'.$i};
									}
									$bulanb = "bulan_b".$i;
									if(!empty($v4->$bulanb)){
										$val = $v4->$bulanb ;
									}
									if($akses_ubah == 1){
										$edit= 'contenteditable="true"';
									}
									$item4 .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" '.$edit.' class="edit-value text-right edited" data-name="bulan_b'.$i.'" data-id="'.$v4->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus4).'</div></td>'; 
								} 
								if(isset($dt4[$i])){ $dt4[$i] += $val; }else{ $dt4[$i] = $val; }
							}
							$item4 .= '<td'.$bd.'></td>';
							if(!empty($v4->biaya_bulan)){
								$valueTxt = $v4->biaya_bulan;
							}
							if(!empty($v4->biaya_tahun)){
								$bln_trakhir = $v4->biaya_tahun;
							}
							if($akses_ubah == 1){
								$edit= 'contenteditable="true"';
							}
							$item4 .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" '.$edit.' class="edit-value text-right edited" data-name="biaya_bulan" data-id="'.$v4->glwnco.'" data-value="'.check_data($valueTxt,$minus4).'">'.$valueTxt.'</div></td>';
							$item4 .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" '.$edit.' class="edit-value text-right edited" data-name="biaya_tahun" data-id="'.$v4->glwnco.'" data-value="'.check_data($bln_trakhir,$minus4).'">'.$bln_trakhir.'</div></td>';
							$item4 .= '<td'.$bd.'></td>';
							$item4 .= '<td'.$bg.'>'.check_data($val,$minus4).'</td>';
							$item4 .= '<td'.$bg.'>'.$v4->hasil2.'</td>';
							$item4 .= '<td'.$bg.'>'.$v4->hasil.'</td>';
							$item4 .= '</tr>';
							$item4 .= $item5;
						}
						$bln_trakhir = '';
						$value = '';
						$valueTxt = '';
					}else{
						// $bln_trakhir = $v3->{'TOT_'.$cabang};

						$bln_trakhir = $v3->hasil;
						if($v3->glwnob == "53303" || $v3->glwnob == "53305" || $v3->glwnob == "53306" || $v3->glwnob == "54001" || $v3->glwnob == "54101" || $v3->glwnob == "53306"){
									$value 		 = (float) $bln_trakhir - $v3->hasil2;
									$sistem 		 = (float) $bln_trakhir - $v3->hasil2;
								}else {
									$value 		 = (float) $bln_trakhir/ $bulan_terakhir;
									$sistem 		 = (float) $bln_trakhir/ $bulan_terakhir;
								}

						for ($jum = 5671011; $jum < 5671017;$jum++){
						if($v3->glwnco == $jum || $v3->glwnco == '5679024'){
								$sistem 		 = (float) $bln_trakhir - $v3->hasil2;
							}
						}		

						if(!empty($v3->biaya_bulan)){
							$value = $v3->biaya_bulan;
						}		
						$valueTxt 	 = check_data($value,$minus3);
						$getTahun	 = check_data($value*12, $minus3);
						$bln_trakhir = check_data($bln_trakhir,$minus3);
					}

					$item3 .= '<tr>';
					$item3 .= '<td>'.$v3->glwnco.'</td>';
					$item3 .= '<td class="sb-3">'.$v3->glwdes.'</td>';
					$biaya_tahun3 = 0;
					for ($i=1; $i <= 12 ; $i++) {
						$a = array_search($i, array_column($detail_tahun, 'bulan'));
						if(count($dt4)>0){ 

							$val = $dt4[$i]; 
							if($detail_tahun[$a]['sumber_data'] == '1'){
								$val = $v3->{'core'.$i};
								$b = $i-1;
								if($detail_tahun[$a-1]['sumber_data'] == '1'){
									$val = $val - $v3->{'core'.$b};
								}	
							}
							$bulanb = "bulan_b".$i;
							if(!empty($v3->$bulanb)){
								$val = $v3->$bulanb ;
							}


							if($v3->last_edit == '1'){
								$val = $val; 
							}else {
								if(!empty($v3->biaya_bulan)){
									$val = $v3->biaya_bulan;
								}
							}


							if($akses_ubah == 1){
								$edit= 'contenteditable="true"';
							}
							$item3 .=  '<td '.$tr.'><div style="min-height: 10px; width: 100%; overflow: hidden;" '.$edit.' class="edit-value text-right edited" data-name="bulan_b'.$i.'" data-id="'.$v3->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus3).'</div></td>';
						}
						else{ 
							
							$val = $value ; 
							
							if(!empty($v3->{'bulan'.$i})){
								$val = $val * $v3->{'bulan'.$i};
							}
							$bulanb = "bulan_b".$i;
							if(!empty($v3->$bulanb)){
								$val = $v3->$bulanb ;
							}
							if(!empty($v3->biaya_bulan)){
								$val = $v3->biaya_bulan;
								if(!empty($v3->{'bulan'.$i})){
									$val = $val * $v3->{'bulan'.$i};
								}
							}

							if($v3->last_edit == '1'){
								$val = $val; 
							}else {
								if(!empty($v3->biaya_bulan)){
									$val = $v3->biaya_bulan;
								}
							}

							if($detail_tahun[$a]['sumber_data'] == '1'){
								$val = $v3->{'core'.$i};
								$b = $i-1;
								if($detail_tahun[$a-1]['sumber_data'] == '1'){
									$val = $val - $v3->{'core'.$b};
								}	
							}

							if($akses_ubah == 1){
								$edit= 'contenteditable="true"';
							}
							$item3 .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" '.$edit.' class="edit-value text-right edited" data-name="bulan_b'.$i.'" data-id="'.$v3->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus3).'</div></td>'; 
						}
						$biaya_tahun3 += $val;
						if(isset($dt3[$i])){ $dt3[$i] += $val; }else{ $dt3[$i] = $val; }
					}
					$item3 .= '<td'.$bd.'></td>';
					$valueTxt = $biaya_tahun3 / 12;
					if(!empty($v3->biaya_bulan)){
						$valueTxt = $v3->biaya_bulan;
					}

					if($akses_ubah == 1){
						$editBulan3 = 'contenteditable="true"';
					}
					
					$item3 .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" '.$editBulan3.' class="edit-value text-right edited" data-name="biaya_bulan" data-id="'.$v3->glwnco.'" data-value="'.$valueTxt.'">'.check_data($valueTxt,$minus3).'</div></td>';
					$item3 .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" '.$editBulan3.' class="edit-value text-right edited" data-name="biaya_tahun" data-id="'.$v3->glwnco.'" data-value="'.$biaya_tahun3.'">'.check_data($biaya_tahun3,$minus3).'</div></td>';
					$item3 .= '<td'.$bd.'></td>';
					// $vale = check_data($val,$minus3);
					// for ($jum = 5671011; $jum < 5671017;$jum++){
					// 	if($v3->glwnco == $jum || $v3->glwnco == '5679024'){
					// 		$vale = ($v3->hasil * -1) - ($v3->hasil2 * -1);

					// 		$vale = custom_format(view_report($vale));	
					// 	}
					// }
					
					$item3 .= '<td'.$bg.'>'.check_data($sistem,$minus3).'</td>';
					$item3 .= '<td'.$bg.'>'.check_data($v3->hasil2,$minus3).'</td>';
					$item3 .= '<td'.$bg.'>'.check_data($v3->hasil,$minus3).'</td>';
					$item3 .= '</tr>';
					$item3 .= $item4;
				}
				$bln_trakhir = '';
				$value = '';
				$valueTxt = '';
			}else{
				
				$bln_trakhir = $v2->hasil;
				$value 		 = (float) $bln_trakhir/$bulan_terakhir;
				// $sistem 	 = (float) $bln_trakhir/$bulan_terakhir;
				if(!empty($v2->biaya_bulan)){
					$value = $v2->biaya_bulan;
				}

				$valueTxt 	 = $value;
				$getTahun	 = $value*12;
				$bln_trakhir = $getTahun;



				

			}

			$item2 .= '<tr>';
			$item2 .= '<td>'.$v2->glwnco.'</td>';
			$item2 .= '<td class="sb-2">'.$v2->glwdes.'</td>';
			$biaya_tahun2 = 0;
			for ($i=1; $i <=12 ; $i++) { 
				$a = array_search($i, array_column($detail_tahun, 'bulan'));
				if(count($dt3)>0){ 
					$val = $dt3[$i]; 
					
					$bulanb = "bulan_b".$i;
					if(!empty($v2->biaya_bulan)){
						$val = $v2->biaya_bulan ;
					}

					if($v2->last_edit == '1'){
						$val = $val; 
					}else {
						if(!empty($v2->biaya_bulan)){
							$val = $v2->biaya_bulan;
						}
					}

					if($detail_tahun[$a]['sumber_data'] == '1'){
						$val = $v2->{'core'.$i};
						$b = $i-1;
						if($detail_tahun[$a-1]['sumber_data'] == '1'){
							$val = $val - $v2->{'core'.$b};
						}	
					}

					if($v2->glwnco == '5721012'){
						if(!empty($sumPromosi)){
							$val = $sumPromosi[0][$bulanb];
						}else{
							$val = 0;
						}
						$minus2 = 0;
					}
					if($akses_ubah == 1){
						$edit = 'contenteditable="true"';
					}
					$item2 .= '<td '.$tr.'><div style="min-height: 10px; width: 100%; overflow: hidden;" '.$edit.' class="edit-value text-right edited" data-name="bulan_b'.$i.'" data-id="'.$v2->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus2).'</div></td>';
				}
				else{ 
					if(!empty($v2->{'bulan'.$i})){
						$val = $val * $v2->{'bulan'.$i};
					}
					$val = $value ; 
					
					$bulanb = "bulan_b".$i;
					if(!empty($v2->biaya_bulan)){
						$val = $v2->biaya_bulan ;
					}
					if($v2->last_edit == '1'){
						$val = $val; 
					}else {
						if(!empty($v2->biaya_bulan)){
							$val = $v2->biaya_bulan;
						}
					}
					if($v2->glwnco == '5721012'){
						if(!empty($sumPromosi)){
							$val = $sumPromosi[0][$bulanb];
						}else{
							$val = 0;
						}
						$minus2 = 0;
					}
					if($akses_ubah == 1){
						$edit = 'contenteditable="true"';
					}
					if($detail_tahun[$a]['sumber_data'] == '1'){
						$val = $v2->{'core'.$i};
						$b = $i-1;
						if($detail_tahun[$a-1]['sumber_data'] == '1'){
							$val = $val - $v2->{'core'.$b};
						}	
					}
					$item2 .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" '.$edit.' class="edit-value text-right edited" data-name="bulan_b'.$i.'" data-id="'.$v2->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus2).'</div></td>'; 
				}
				$biaya_tahun2 += $val;
				if(isset($dt2[$i])){ $dt2[$i] += $val; }else{ $dt2[$i] = $val; }
			}

			$item2 .= '<td'.$bd.'></td>';
			$valueTxt = $biaya_tahun2 / 12;
			if(!empty($v2->biaya_bulan)){
				$valueTxt = $v2->biaya_bulan;
			}
			
			if($akses_ubah == 1){
				$editBulan2 = 'contenteditable="true"';
			}
			
			$item2 .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"bulan2 '.$editBulan2.' class="edit-value text-right edited" data-name="biaya_bulan" data-id="'.$v2->glwnco.'" data-value="'.$valueTxt.'">'.check_data($valueTxt,$minus2).'</div></td>';
			$item2 .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" '.$editBulan2.' class="edit-value text-right edited" data-name="biaya_tahun" data-id="'.$v2->glwnco.'" data-value="'.$biaya_tahun2.'">'.check_data($biaya_tahun2,$minus2).'</div></td>';
			$item2 .= '<td'.$bd.'></td>';
			$sistem 	 = (float) $v2->hasil/$bulan_terakhir;
			$item2 .= '<td'.$bg.'>'.check_data($sistem,$minus2).'</td>';
			$item2 .= '<td'.$bg.'>'.check_data($v2->hasil2,$minus2).'</td>';
			$item2 .= '<td'.$bg.'>'.check_data($v2->hasil,$minus2).'</td>';
			$item2 .= '</tr>';

			if($v2->glwnco == '5721012'){

				for($a=1;$a<=5;$a++){

					$c = $a - 1;
					$keterangan = '';
					if(isset($promosi[$c])){
						$keterangan = $promosi[$c]['keterangan'];
					}
					$bgEdit = bgEdit();
					$item2 .= '<tr style="background-color: '.$bgEdit.'">';
					$item2 .= '<td>'.$a.'</td>';
					$item2 .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="promosi" data-name="keterangan" data-id="'.$a.'" data-value="">'.$keterangan.'</div></td>';
					for($i=1;$i<=12;$i++){
						$hasil = 0;
						$b = $i;
						$bulan = "bulan_".$b;
						if(isset($promosi[$c])){
							$hasil = check_data($promosi[$c][$bulan],0);
						}
						$item2 .= '<td class="sb-2"><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="text-right promosi textpromosi" data-name="bulan_'.$i.'" data-id="'.$a.'" data-value="">'.$hasil.'</div></td>';
					}
					$item2 .= '</tr>';
				}
			}

			$item2 .= $item3;
		}
		$bln_trakhir = '';
		$value = '';
		$valueTxt = '';
	}else{
		// $bln_trakhir = $v->{'TOT_'.$cabang};
		$bln_trakhir = $v->hasil;
		$value 		 = (float) $bln_trakhir/$bulan_terakhir;
		if(!empty($v->biaya_bulan)){
			$value = $v->biaya_bulan;
		}
		$valueTxt 	 = check_data($value,$minus);
		$getTahun	 = check_data($value*12, $minus);
		$bln_trakhir = check_data($bln_trakhir,$minus);
	}
	$item .= '<tr>';
	$item .= '<td>'.$v->glwnco.'</td>';
	$item .= '<td>'.$v->glwdes.'</td>';
	$biaya_tahun1 = 0;
	for ($i=1; $i <= 12 ; $i++) { 
		$a = array_search($i, array_column($detail_tahun, 'bulan'));
		if(count($dt2)>0){ 
			$val = $dt2[$i]; 
			
			$bulanb = "bulan_b".$i;
			if(!empty($v->$bulanb)){
				$val = $v->$bulanb;
			}

			if($v->last_edit == '1'){
				$val = $val; 
			}else {
				if(!empty($v->biaya_bulan)){
					$val = $v->biaya_bulan;
				}
			}

			if($detail_tahun[$a]['sumber_data'] == '1'){
				$val = $v->{'core'.$i};
				$b = $i-1;
				if($detail_tahun[$a-1]['sumber_data'] == '1'){
					$val = $val - $v->{'core'.$b};
				}	
			}
		}
		else{ 
			$val = $value; 
			
			$bulanb = "bulan_b".$i;
			if(!empty($v->$bulanb)){
				$val = $v->$bulanb;
			}

			if($v->last_edit == '1'){
				$val = $val; 
			}else {
				if(!empty($v->biaya_bulan)){
					$val = $v->biaya_bulan;
				}
			}

			if($detail_tahun[$a]['sumber_data'] == '1'){
				$val = $v->{'core'.$i};
				$b = $i-1;
				if($detail_tahun[$a-1]['sumber_data'] == '1'){
					$val = $val - $v->{'core'.$b};
				}	
			}
		}
		if($akses_ubah == 1){
			$edit = 'contenteditable="true"';
		}
		$item .= '<td '.$tr.'><div style="min-height: 10px; width: 100%; overflow: hidden;" '.$edit.' class="edit-value text-right edited" data-name="bulan_b'.$i.'" data-id="'.$v->glwnco.'" data-value="'.$val.'">'.check_data($val,$minus).'</div></td>';
		$biaya_tahun1 += $val;
	}
	$item .= '<td'.$bd.'></td>';
	$biaya_bulan = $biaya_tahun1 / 12;
	if(!empty($v->biaya_bulan)){
		$biaya_bulan = $v->biaya_bulan;
	}
	if($akses_ubah == 1){
		$edit = 'contenteditable="true"';
	}

	$item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" '.$edit.' class="edit-value text-right edited" data-name="biaya_bulan" data-id="'.$v->glwnco.'" data-value="'.$biaya_bulan.'">'.check_data($biaya_bulan,$minus).'</div></td>';
	$item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" '.$edit.' class="edit-value text-right edited" data-name="biaya_tahun" data-id="'.$v->glwnco.'" data-value="'.$biaya_tahun1.'">'.check_data($biaya_tahun1,$minus).'</div></td>';
	$item .= '<td'.$bd.'></td>';

	$sistem 	 = (float) $v->hasil/$bulan_terakhir;
	$item .= '<td'.$bg.'>'.check_data($sistem,$minus).'</td>';
	$item .= '<td'.$bg.'>'.check_data($v->hasil2,$minus).'</td>';
	$item .= '<td'.$bg.'>'.check_data($v->hasil,$minus).'</td>';
	$item .= '</tr>';
	$item .= $item2;
}
echo $item;
// function check_data($v,$x){
// 	$val = kali_minus($v,$x);
// 	$val = custom_format($val);
// 	// $val = custom_format(view_report($val));
// 	return $val;
// }
?>