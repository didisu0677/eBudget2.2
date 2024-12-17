<?php
	$item = '<tr>';
	$item .= '<td class="border-none">.</td>';
	$item .= '</tr>';

	foreach ($data as $k => $val) {
		$hasil2 = $val->hasil2 * -1;
        $hasil = $val->hasil * -1;

        $keySaved = multidimensional_search($A_saved, array(
            'glwnco'     => $val->glwnco,
            'tahun_core' => $anggaran->tahun_anggaran
        ));
        if(strlen($keySaved)>0):
            $changed = json_decode($A_saved[$keySaved]['changed'],true);
            if(in_array('real_1', $changed)):
                $hasil = $A_saved[$keySaved]['real_1'];
            endif;

            if(in_array('real_2', $changed)):
                $hasil2 = $A_saved[$keySaved]['real_2'];
            endif;
        endif;

        // BIAYA PENYUSUTAN PD. BLN(Lama)
        $penyusutan = ($hasil - $hasil2);
        $arrItem4 = [];
        $item4 = '<tr>';
        $item4 .= '<td></td>';
        $item4 .= '<td>BIAYA PENYUSUTAN PD. BLN(Lama)</td>';
        $item4 .= '<td></td>';
        $item4 .= '<td class="text-right">'.custom_format(view_report($penyusutan)).'</td>';
        foreach ($detail_tahun as $v) {
        	if($v->singkatan != arrSumberData()['real']):
        		$arrItem4[$v->tahun][$v->bulan] = $penyusutan;
            	$item4 .= '<td class="text-right">'.custom_format(view_report($penyusutan)).'</td>';
        	endif;
        }
        $item4 .= '</tr>';

        // BIAYA PENYUSUTAN PD. BLN(Baru)
        $arrItem3= [];
        $item3 = '<tr>';
        $item3 .= '<td></td>';
        $item3 .= '<td>BIAYA PENYUSUTAN PD. BLN(Baru)</td>';
        $item3 .= '<td></td>';
        $item3 .= '<td></td>';
        foreach ($detail_tahun as $v) {
        	if($v->singkatan != arrSumberData()['real']):
        		$key = multidimensional_search($baru, array(
		            'tahun_core' => $v->tahun
		        ));
		        $value = 0;
		        if(strlen($key)>0):
		        	$value = $baru[$key]['bulan_'.$v->bulan];
		        endif;
		        $arrItem3[$v->tahun][$v->bulan] = $value;
		        $item3 .= '<td class="text-right">'.custom_format(view_report($value)).'</td>';
        	endif;
        }
        $item3 .= '</tr>';

        // BIAYA PENYUSUTAN PD. BLN
        $arrItem2= [];
        $item2 = '<tr>';
        $item2 .= '<td></td>';
        $item2 .= '<td>BIAYA PENYUSUTAN PD. BLN</td>';
        $item2 .= '<td></td>';
        $item2 .= '<td></td>';
        foreach ($detail_tahun as $v) {
        	if($v->singkatan != arrSumberData()['real']):
        		$value = ($arrItem4[$v->tahun][$v->bulan] + $arrItem3[$v->tahun][$v->bulan]);
	        	$value = round(view_report($value),-2);
	        	$arrItem2[$v->tahun][$v->bulan] = $value;
	        	$item2 .= '<td class="text-right">'.custom_format($value).'</td>';
        	endif;

        }
        $item2 .= '</tr>';

        // item COA
        $bln_before = view_report($hasil);
		$item .= '<tr style = "background: #FFF;">';
        $item .= '<td>'.$val->glwnco.'</td>';
        $item .= '<td>'.remove_spaces($val->glwdes).'</td>';

        $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right " data-name="real_2" data-id="'.(3).'-'.$val->glwnco.'-'.$anggaran->tahun_anggaran.'" data-value="'.view_report($hasil2).'">'.custom_format(view_report($hasil2)).'</div></td>';

		$item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right " data-name="real_1" data-id="'.(3).'-'.$val->glwnco.'-'.$anggaran->tahun_anggaran.'" data-value="'.view_report($hasil).'">'.custom_format(view_report($hasil)).'</div></td>';

		$dataSaved = [];
		foreach ($detail_tahun as $v) {
			if($v->singkatan != arrSumberData()['real']):
				if($v->bulan == 1):
					$bln_before = 0;
				endif;
				$value = $bln_before + $arrItem2[$v->tahun][$v->bulan];
				$value = round($value,-2);
				$bln_before = $value;
				$item .= '<td class="text-right">'.custom_format($value).'</td>';
				$dataSaved[$val->glwnco.'-'.$v->tahun.'-'.$cabang]['bulan_'.$v->bulan] = $value;
            elseif(isset($data_core[$v->tahun])):
                    $field  = 'B_' . sprintf("%02d", $v->bulan);
                    $k_core = multidimensional_search($data_core[$v->tahun], array(
                        'glwnco' => $val->glwnco,
                    ));
                    if(strlen($k_core)>0):
                        $d_core = $data_core[$v->tahun][$k_core];
                        $value  = view_report($d_core[$field]) *-1;
                        $dataSaved[$val->glwnco.'-'.$v->tahun.'-'.$cabang]['bulan_'.$v->bulan] = $value;
                    endif;
			endif;
		}

        $item .= '</tr>';
        $item .= $item2;
        $item .= $item3;
        $item .= $item4;
	}

	echo $item;
	checkSavedFormulaAkt($dataSaved,$anggaran);
?>