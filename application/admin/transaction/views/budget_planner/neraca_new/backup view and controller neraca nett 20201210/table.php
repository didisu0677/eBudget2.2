<?php
	$item = '';
	$td_transparnt = '<td class="border-none bg-transparent"></td>';
	foreach ($coa as $k => $v) {
		$item2 = '';
		$dt2 = [];
		foreach ($detail[$v->glwnco] as $k2 => $v2) {
			$item3 = '';
			$dt3   = [];
			foreach ($detail[$v2->glwnco] as $k3 => $v3) {
				$item4 	= '';
				$dt4 	= [];
				foreach ($detail[$v3->glwnco] as $k4 => $v4) {
					$item5  = '';
					$dt5 	= [];
					foreach ($detail[$v4->glwnco] as $k5 => $v5) {
						$item6  = '';
						$dt6 = [];
						foreach ($detail[$v5->glwnco] as $k6 => $v6) {
							$item6 .= '<tr>';
							$item6 .= '<td>'.$v6->glwsbi.'</td>';
							$item6 .= '<td>'.$v6->glwcoa.'</td>';
							$item6 .= '<td>'.$v6->glwnco.'</td>';
							$item6 .= '<td class="sub-6">'.$v6->glwdes.'</td>';
							$value = $v6->{'TOT_'.$cabang};
							$val = kali_minus($value,$v6->kali_minus);
							$val = custom_format(view_report($val));
							for ($i=1; $i <= 12 ; $i++) { 
								if(isset($dt6[$i])){ $dt6[$i] += $value; }else{ $dt6[$i] = $value; }
								$item6 .= '<td>'.$val.'</td>';
							}
							$item6 .= $td_transparnt;
							$item6 .= '<td>'.$val.'</td>';
							$item6 .= '</tr>';
						}

						$item5 .= '<tr>';
						$item5 .= '<td>'.$v5->glwsbi.'</td>';
						$item5 .= '<td>'.$v5->glwcoa.'</td>';
						$item5 .= '<td>'.$v5->glwnco.'</td>';
						$item5 .= '<td class="sub-5">'.$v5->glwdes.'</td>';
						$bln_trakhir5 = $v5->{'TOT_'.$cabang};
						if(count($detail[$v5->glwnco])>0){ $bln_trakhir5 = ''; }
						for ($i=1; $i <= 12 ; $i++) {
							if(count($detail[$v5->glwnco])>0){ $value = $dt6[$i]; }
							else{ $value = $v5->{'TOT_'.$cabang}; }
							$val = kali_minus($value,$v5->kali_minus);
							$val = custom_format(view_report($val));
							$item5 .= '<td>'.$val.'</td>';
							if(isset($dt5[$i])){ $dt5[$i] += $value; }else{ $dt5[$i] = $value; }
						}
						$val = kali_minus($bln_trakhir5,$v5->kali_minus);
						$val = custom_format(view_report($val));
						$item5 .= $td_transparnt;
						$item5 .= '<td>'.$val.'</td>';
						$item5 .= '</tr>';
						$item5 .= $item6;
					}
					$item4 .= '<tr>';
					$item4 .= '<td>'.$v4->glwsbi.'</td>';
					$item4 .= '<td>'.$v4->glwcoa.'</td>';
					$item4 .= '<td>'.$v4->glwnco.'</td>';
					$item4 .= '<td class="sub-4">'.$v4->glwdes.'</td>';
					$bln_trakhir4 = $v4->{'TOT_'.$cabang};
					if(count($detail[$v4->glwnco])>0){ $bln_trakhir4 = ''; }
					for ($i=1; $i <= 12 ; $i++) {
						if(count($detail[$v4->glwnco])>0){ $value = $dt5[$i]; }
						else{ $value = $v4->{'TOT_'.$cabang}; }
						$val = kali_minus($value,$v4->kali_minus);
						$val = custom_format(view_report($val));
						$item4 .= '<td>'.$val.'</td>';
						if(isset($dt4[$i])){ $dt4[$i] += $value; }else{ $dt4[$i] = $value; }
					}
					$val = kali_minus($bln_trakhir4,$v4->kali_minus);
					$val = custom_format(view_report($val));
					$item4 .= $td_transparnt;
					$item4 .= '<td>'.$val.'</td>';
					$item4 .= '</tr>';
					$item4 .= $item5;
				}
				$item3 .= '<tr>';
				$item3 .= '<td>'.$v3->glwsbi.'</td>';
				$item3 .= '<td>'.$v3->glwcoa.'</td>';
				$item3 .= '<td>'.$v3->glwnco.'</td>';
				$item3 .= '<td class="sub-3">'.$v3->glwdes.'</td>';
				$bln_trakhir3 = $v3->{'TOT_'.$cabang};
				if(count($detail[$v3->glwnco])>0){ $bln_trakhir3 = ''; }
				for ($i=1; $i <= 12 ; $i++) {
					if(count($detail[$v3->glwnco])>0){ $value = $dt4[$i]; }
					else{ $value = $v3->{'TOT_'.$cabang}; }
					$val = kali_minus($value,$v3->kali_minus);
					$val = custom_format(view_report($val));
					$item3 .= '<td>'.$val.'</td>';
					if(isset($dt3[$i])){ $dt3[$i] += $value; }else{ $dt3[$i] = $value; }
				}
				$val = kali_minus($bln_trakhir3,$v3->kali_minus);
				$val = custom_format(view_report($val));
				$item3 .= $td_transparnt;
				$item3 .= '<td>'.$val.'</td>';
				$item3 .= '</tr>';
				$item3 .= $item4;
			}

			$item2 .= '<tr>';
			$item2 .= '<td>'.$v2->glwsbi.'</td>';
			$item2 .= '<td>'.$v2->glwcoa.'</td>';
			$item2 .= '<td>'.$v2->glwnco.'</td>';
			$item2 .= '<td class="sub-2">'.$v2->glwdes.'</td>';
			$bln_trakhir2 = $v2->{'TOT_'.$cabang};
			if(count($detail[$v2->glwnco])>0){ $bln_trakhir2 = ''; }
			for ($i=1; $i <= 12 ; $i++) {
				if(count($detail[$v2->glwnco])>0){ $value = $dt3[$i]; }
				else{ $value = $v2->{'TOT_'.$cabang}; }
				$val = kali_minus($value,$v2->kali_minus);
				$val = custom_format(view_report($val));
				$item2 .= '<td>'.$val.'</td>';
				if(isset($dt2[$i])){ $dt2[$i] += $value; }else{ $dt2[$i] = $value; }
			}
			$val = kali_minus($bln_trakhir2,$v2->kali_minus);
			$val = custom_format(view_report($val));
			$item2 .= $td_transparnt;
			$item2 .= '<td>'.$val.'</td>';
			$item2 .= '</tr>';
			$item2 .= $item3;
		}
		$item .= '<tr>';
		$item .= '<td>'.$v->glwsbi.'</td>';
		$item .= '<td>'.$v->glwcoa.'</td>';
		$item .= '<td>'.$v->glwnco.'</td>';
		$item .= '<td>'.$v->glwdes.'</td>';
		$bln_trakhir = $v->{'TOT_'.$cabang};
		if(count($detail[$v->glwnco])>0){ $bln_trakhir = ''; }
		for ($i=1; $i <= 12 ; $i++) {
			if(count($detail[$v->glwnco])>0){ $value = $dt2[$i]; }
			else{ $value = $v->{'TOT_'.$cabang}; }
			$val = kali_minus($value,$v->kali_minus);
			$val = custom_format(view_report($val));
			$item .= '<td>'.$val.'</td>';
		}
		$val = kali_minus($bln_trakhir,$v->kali_minus);
		$val = custom_format(view_report($val));
		$item .= $td_transparnt;
		$item .= '<td>'.$val.'</td>';
		$item .= '</tr>';
		$item .= $item2;
	}
	echo $item;
?>