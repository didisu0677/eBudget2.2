<?php
	$item = '';
	$dd = ['EFEKTIFE RATE','RATE','PERHITUNGAN BUNGA PD. BLN'];
	foreach ($coa as $k => $v) {
//			debug($v);die;
		$item .= '<tr>';
		$item .= '<td>'.$v->coa.'</td>';
		$item .= '<td>'.remove_spaces(strtoupper($v->nama_produk_kredit)).'</td>';	
				$i_bln = 0;
				$where = [
		   			'kode_anggaran' => $anggaran->kode_anggaran,
		   			'kode_cabang'	=> $kode_cabang,
		   			'tahun'			=> $anggaran->tahun_anggaran,
		   			'glwnco'		=> $v->coa,
		   		];
		   		$dataSaved = [];
				foreach ($detail_tahun as $k2 => $v2) {
					 $i_bln++;
					 $vfield = 'P_'. sprintf("%02d", $v2->bulan);
					 $T = 'TOTAL_' . $v2->tahun . sprintf("%02d", $v2->bulan);
					 $R0 = 'R0_' . $v2->tahun . sprintf("%02d", $v2->bulan);;
					 $R1 = 'R1_' . $v2->tahun . sprintf("%02d", $v2->bulan);
					 $BR2 = 'BR2_' . $v2->tahun . sprintf("%02d", $v2->bulan);
					 $rbefore = 'BEF_' . $v2->tahun . sprintf("%02d", $v2->bulan);

					 $$T = 0;
					 $$R1 = 0;
					 $$R0 = 0;
					 $$BR2 = 0;
					 $$rbefore = 0;

					 $totalb = 0;
				//	 debug($v);die;
					foreach ($rinc_kr as $r) {
						if($v2->tahun == $r->tahun_core && $v->coa == $r->coa){
							$$T   = $r->$vfield;
							$$R1  = ($r->$vfield * $r->rate) / 1200;
							$$R0  = $r->rate;   
							
					 		foreach ($B as $vB) {
								if($v->bunga_kredit == $vB->coa) {
									$$rbefore = $vB->hasil9;		
								}
							}
							
							if($i_bln > 1 && $v2->bulan > 1) {
								$rbefore = 'BR2_' . $v2->tahun . sprintf("%02d", ($v2->bulan - 1));
							}


							$$BR2 = $$R1 + $$rbefore;
						}
					}

					$item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right edited" data-name="bulan_'.$v2->bulan.'" data-id="'.$v2->tahun.'-'.$v->coa.'" data-value="'.$$T.'">'.custom_format($$T).'</div></td>';
					$dataSaved[$v2->tahun]['bulan_'.$v2->bulan] = $$T;
				}
				ckForSaved($where,$dataSaved);

				$item .= '<tr>';
				$item .= '<td>'.$v->bunga_kredit.'</td>';
				$item .= '<td>'.strtoupper(remove_spaces($v->nama)).'</td>';
				$where['glwnco'] = $v->bunga_kredit;
				$dataSaved = [];
				foreach ($detail_tahun as $k2 => $v2) {
					$XB = 'BR2_' . $v2->tahun . sprintf("%02d", $v2->bulan);
					$item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right edited" data-name="bulan_'.$v2->bulan.'" data-id="'.$v2->tahun.'-'.$v->bunga_kredit.'" data-value="'.$$XB.'">'.custom_format($$XB).'</div></td>';
					$dataSaved[$v2->tahun]['bulan_'.$v2->bulan] = $$XB;
				}
				ckForSaved($where,$dataSaved);

				$i_add = 0;
				foreach ($dd as $k3 => $v3) {
					$i_add++;
					$item .= '<tr>';
					$item .= '<td></td>';
					$item .= '<td>'.$v3.'</td>';
					$i_bln = 0;
					foreach ($detail_tahun as $k2 => $v2) {
						$i_bln++;
						$R1 = 'R1_' . $v2->tahun . sprintf("%02d", $v2->bulan);
						$R0 = 'R0_' . $v2->tahun . sprintf("%02d", $v2->bulan);
						$BR2 = 'BR2' . $v2->tahun . sprintf("%02d", $v2->bulan);

						$R2 = 'TR1_' . $v2->tahun . sprintf("%02d", $v2->bulan);
						$R2_ = 'TR1_' . $v2->tahun . sprintf("%02d", $v2->bulan);
						$$R2 = 0;
						$$R2_ = 0;
						switch ($i_add) {
						  case 1:
						  	if($i_bln ==1){
						    	$$R2 = '';
						    	$$R2_ = '';
						    } 
						    break;
						  case 2:
						    $$R2 = custom_format($$R0,false,2); 
						    $$R2_ = $$R0; 
						    break;
						  case 3:
						    $$R2 = custom_format($$R1); 
						    $$R2_ = $$R1; 
						    break;
						} 
						$item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right edited" data-name="bulan_'.$v2->bulan.'" data-id="'.$v2->tahun.'-'.$v->coa.'" data-value="'.$$R2_.'">'.$$R2.'</div></td>';
					}
				}


				$item .= '</tr>';
		$item .= '</tr>';
	}
	echo $item;
?>