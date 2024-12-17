<?php
	$item = '';
    $arrTotal = [];
    $arrUpdateBuffer = [];
	if(count($list)>0):
		foreach ($list as $k => $v) {
			$item .= '<tr>';
			$item .= '<td>'.($k+1).'</td>';
			$item .= '<td>'.$v->nama.'</td>';


            if ($v->coa != '311'){
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
                
                    foreach ($list_dep as $kd => $vd) {                 
                        if($value['tahun'] == $vd->tahun_core && $v->coa == $vd->coa){      
                            $$j1 = $vd->$vfield;
                            if($v->coa =='311' && $value['tahun'] == user('tahun_anggaran')) {
                                $$j1 = $jml_plandep[$vfield] - $jml_non5[$vfield];
                            }
                        }
                    }


                if(isset($arrTotal[$value['tahun'].'-'.$vfield])):
                    $arrTotal[$value['tahun'].'-'.$vfield] += $$j1;
                else:
                    $arrTotal[$value['tahun'].'-'.$vfield] = $$j1;
                endif;

                $item .= '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="'.$contentedit.'" contenteditable="true" class="edit-value text-right" data-name="'.$v->coa.'|table3'.'|'.substr($j1,4).'|'.$v->coa.'|'.$value['id_tahun_anggaran'].'|'.$cabang.'" data-id="'.$v->id.'" data-value="'.$$j1.'">'.custom_format(view_report($$j1)).'</div></td>';

                //MW update buffer
                if($v->coa == '311'):
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

        // $item .= '<tr>';
        // $item .= '<th colspan="2" class="text-right">Total</th>';
        // foreach ($detail_tahun as $d => $value) {   
        //     $vfield = 'P_'. sprintf("%02d", $value['bulan']);
        //     $total = $arrTotal[$value['tahun'].'-'.$vfield];
        //     $item .= '<th class="text-right">'.custom_format(view_report($total)).'</th>';
        // }
        // $item .= '</tr>';
        foreach ($arrUpdateBuffer as $k => $v) {
            $where = [
                'kode_anggaran' => $kode_anggaran,
                'kode_cabang'   => $cabang,
                'tahun_core'    => $k,
                'coa'           => '311',
            ];
            update_data('tbl_budget_plan_deposito',$v,$where);
        }
	else:
		$item .= '<tr><td>Data tidak ditemukan</td></tr>';
	endif;
	echo $item;
?>