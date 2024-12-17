<?php
	$item = '';
    $arrUpdateBuffer = [];
	if(count($list_t)>0):
		$rate = 0;
        foreach ($list_t as $k => $v) {
			$item .= '<tr>';
			$item .= '<td>'.($k+1).'</td>';
			$item .= '<td>'.$v->nama.'</td>';
			
            $rate = $v->rate; 

            $bgedit =bgEdit();
            $contentedit ="true"; 
            $id = "id";     
            $contentedit ="true" ;


            $item .= '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; $vd->$vfield: 50px; overflow: hidden;" contenteditable="'.$contentedit.'" contenteditable="true" class="edit-value text-right" data-name="'.$v->coa.'|table0'.'|'.'000000'.'|'.$v->coa.'|'.$tahun[0]['id'].'|'.$cabang.'" data-id="'.$v->id.'" data-value="'.$rate.'">'.custom_format($rate,false,2).'</div></td>';

			if ($v->coa != '412'){
				$bgedit =bgEdit();
            	$contentedit ="true"; 
            	$id = "id";     
            	$contentedit ="true" ;
        	}else{
                $bgedit ="";
                $contentedit ="false"; 
                $id = "id";     
                $contentedit ="false" ;
        	}

	         foreach ($detail_tahun as $d => $value) {	
            	$vfield = 'P_'. sprintf("%02d", $value['bulan']);
				$j1 = 'JML_'.   $value['tahun'] . sprintf("%02d", $value['bulan']);
				$$j1 = 0;
                foreach ($list_tab as $kd => $vd) {            		
            		if($value['tahun'] == $vd->tahun_core && $v->coa == $vd->coa){		
            			$$j1 = $vd->$vfield;
                        if($v->coa =='412') {
                            $keyNonBima = multidimensional_search($jml_nonbima, array(
                                'tahun_core'  => $value['tahun'],
                            ));
                            $pengurang = 0;
                            if(strlen($keyNonBima)>0):
                                $dt_non_bima = $jml_nonbima[$keyNonBima];
                                $pengurang = $dt_non_bima[$vfield];
                            endif;
                            $keyPlanTab = multidimensional_search($jml_plantab, array(
                                'tahun_core'  => $value['tahun'],
                            ));
                            $nilai = 0;
                            if(strlen($keyPlanTab)>0):
                                $dt_plan_tab = $jml_plantab[$keyPlanTab];
                                $nilai = $dt_plan_tab[$vfield];
                            endif;
                            // $$j1 = $jml_plantab[$vfield] - $jml_nonbima[$vfield];
                            $$j1 = $nilai - $pengurang;
                        }
            		}
            	}


                $item .= '<td data-tes="'.$v->id.'" style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; $vd->$vfield: 50px; overflow: hidden;" contenteditable="'.$contentedit.'" contenteditable="true" class="edit-value text-right" data-name="'.$v->coa.'|table3'.'|'.substr($j1,4).'|'.$v->coa.'|'.$value['id_tahun_anggaran'].'|'.$cabang.'" data-id="'.$v->id.'" data-value="'.$$j1.'">'.custom_format(view_report($$j1)).'</div></td>';

                //MW update buffer
                if($v->coa == '412'):
                    $arrUpdateBuffer[$value['tahun']][$vfield] = $$j1;
                endif;

            }

            $item .= '<td class="border-none"></td>' ;	
            $vnetto = 0;
            $bulan0 = 0;
            $bulan01 = 0;
    		foreach ($B as $val) {
            	if($val->glwnco == $v->coa){
                    $bulan0 = $val->hasil9 * -1;
                    $bulan01 = $val->hasil10 * -1;
            	}
            }
            $item .= '<td class="text-right">'.custom_format($v->prsn,false,2).'</td>' ;
			$item .= '</tr>';
		}
        foreach ($arrUpdateBuffer as $k => $v) {
            $where = [
                'kode_anggaran' => $kode_anggaran,
                'kode_cabang'   => $cabang,
                'tahun_core'    => $k,
                'coa'           => '412',
            ];
            update_data('tbl_budget_plan_tabungan',$v,$where);
        }
	else:
		$item .= '<tr><td>Data tidak ditemukan</td></tr>';
	endif;
	echo $item;
?>

