<?php
$dtE2 = json_encode($E5); $dtE2 = json_decode($dtE2,true);
$E2 = $E5;
$dataSaved = [];
$bac = 0;
$tambahBaru = 0;
$tambahBaruPeny = 0;
$tambahBaruPenyGlwn = 0;
$tambahBaruPenyGlwn56 = 0;
$tambahBaruGlwn1622 = 0;
$tambahBaruGlwn5621 = 0;
$item = "<center>.</center><center>.";
    $test = [];
       foreach ($C as $val) {
        // $item .= '<tr style = "background: #FF9800;">';
        $item .= '<tr style = "background: #FFF;">';
        $item .= '<td>'.$val->glwnco.'</td>';
        $item .= '<td>'.remove_spaces($val->glwdes).'</td>';
        $hasil2 = $val->hasil2 * -1;
        $hasil = $val->hasil * -1;

        $keySaved = checkFormulaAkt2(['glwnco' => '5621015', 'tahun_core' => $anggaran->tahun_anggaran],$A_saved);
        if($keySaved['status']):
            $changed = json_decode($keySaved['data']['changed'],true);
            if(in_array('real_1', $changed)):
                $valC[0]['hasil'] = $keySaved['data']['real_1'];
            endif;
            if(in_array('real_2', $changed)):
                $valC[0]['hasil2'] = $keySaved['data']['real_2'];
            endif;
        endif;

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

        
        // $item .= '<td  class="text-right">AAAA'.custom_format($hasil2).'</td>';
        $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right " data-name="real_2" data-id="'.(3).'-'.$val->glwnco.'-'.$anggaran->tahun_anggaran.'" data-value="'.view_report($hasil2).'">'.custom_format(view_report($hasil2)).'</div></td>';
        
        // $item .= '<td class="text-right">AAAA'.custom_format($hasil).'</td>';
        $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right " data-name="real_1" data-id="'.(3).'-'.$val->glwnco.'-'.$anggaran->tahun_anggaran.'" data-value="'.view_report($hasil).'">'.custom_format(view_report($hasil)).'</div></td>';

        $dataSaved[$val->glwnco.'-'.$anggaran->tahun_anggaran.'-'.$cabang] = [
            'real_1' => view_report($hasil),
            'real_2' => view_report($hasil2),
        ];


        if(substr($val->glwnco, 0,4) == "1621"){
            $totalIf1 = $hasil;
            foreach ($detail_tahun as $v => $val2) {
                $a = $v + 1;
                $keySaved = multidimensional_search($A_saved, array(
                    'glwnco'     => $val->glwnco.'_baru',
                    'tahun_core' => $val2->tahun
                ));
                $status = false;
                if(strlen($keySaved)>0):
                    $changed = json_decode($A_saved[$keySaved]['changed']);
                    if(in_array('bulan_'.$val2->bulan, $changed)):
                        $status  = true;
                        $totalIf1 += $A_saved[$keySaved]['bulan_'.$val2->bulan];
                    endif;
                endif;
                if(!$status):
                    $key = multidimensional_search($dtE2, array(
                        'bulan' => $val2->bulan,
                        'tahun' => $val2->tahun
                    ));
                    if(strlen($key)>0):
                        $dtKey = $dtE2[$key];
                        $totalIf1 += $dtKey['total'];
                    endif;
                endif;
                $result = round(view_report($totalIf1),-2);
                $item .= '<td class="text-right">'.custom_format($result).'</td>';

                $dataSaved[$val->glwnco.'-'.$val2->tahun.'-'.$cabang]['bulan_'.$val2->bulan] = $result;

                // $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right " data-name="bulan_'.$val2->bulan.'" data-id="'.$val2->sumber_data.'-'.$val->glwnco.'-'.$val2->tahun.'" data-value="'.$totalIf1.'">'.custom_format($totalIf1).'</div></td>';
            }
        }else if(substr($val->glwnco, 0,4) == "1622"){
            $tHasil = $hasil;
            $tPenyusutanBaru = 0;
            foreach ($detail_tahun as $v => $val2) {
                $a = $v + 1;
                $bulanLama = ($valC[0]['hasil']) - ($valC[0]['hasil2']); 
                $keySaved = checkFormulaAkt(['glwnco' => '5621015_baru','tahun_core' => $val2->tahun],$A_saved,'bulan_'.$val2->bulan);
                if($keySaved['status']):
                    $tPenyusutanBaru = $keySaved['data']['bulan_'.$val2->bulan];
                else:
                    $keySaved = checkFormulaAkt(['glwnco' => '1621015'.'_baru','tahun_core' => $val2->tahun],$A_saved,'bulan_'.$val2->bulan);
                    if($keySaved['status']):
                        $tPenyusutanBaru += ($keySaved['data']['bulan_'.$val2->bulan]*0.25)/12;
                    else:
                        $key = checkFormulaAkt2(['bulan' => $val2->bulan,'tahun' => $val2->tahun],$dtE2);
                        if($key['status']):
                            $tPenyusutanBaru += ($key['data']['total']*0.25)/12;
                        endif;
                    endif;
                endif;
                $tHasil -= ($bulanLama + $tPenyusutanBaru);
                $result = round(view_report($tHasil),-2);
                $item .= '<td class="text-right">'.custom_format($result).'</td>';
                $dataSaved[$val->glwnco.'-'.$val2->tahun.'-'.$cabang]['bulan_'.$val2->bulan] = $result;
                // $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right " data-name="bulan_'.$val2->bulan.'" data-id="'.$val2->sumber_data.'-'.$val->glwnco.'-'.$val2->tahun.'" data-value="'.$tHasil.'">'.custom_format($tHasil).'</div></td>';
            }
        }else if(substr($val->glwnco, 0,4) == "5621"){
            $tambahBaruPenyGlwn56 = $hasil;
            $statusEnd = false;
            $tPenyusutanBaru = 0;
            $n_core = 0;
            foreach ($detail_tahun as $v => $val2) {
                $a = $v + 1;
                $bulanLama = ($valC[0]['hasil']) - ($valC[0]['hasil2']);
                if($val2->tahun == $anggaran->tahun_anggaran && !$statusEnd):
                    $statusEnd = true; $tambahBaruPenyGlwn56 = 0;
                endif;
                $keySaved = checkFormulaAkt(['glwnco' => $val->glwnco.'_baru','tahun_core' => $val2->tahun],$A_saved,'bulan_'.$val2->bulan);
                if($keySaved['status']):
                    $tPenyusutanBaru = $keySaved['data']['bulan_'.$val2->bulan];
                else:
                    $keySaved = checkFormulaAkt(['glwnco' => '1621015'.'_baru','tahun_core' => $val2->tahun],$A_saved,'bulan_'.$val2->bulan);
                    if($keySaved['status']):
                        $tPenyusutanBaru += ($keySaved['data']['bulan_'.$val2->bulan]*0.25)/12;
                    else:
                        $key = checkFormulaAkt2(['bulan' => $val2->bulan,'tahun' => $val2->tahun],$dtE2);
                        if($key['status']):
                            $tPenyusutanBaru += ($key['data']['total']*0.25)/12;
                        endif;
                    endif;
                endif;
                if(count($arr_tahun_core) == 1 && $n_core == 0):
                    $n_core ++;
                    $tambahBaruPenyGlwn56 += ($bulanLama + $tPenyusutanBaru) + $hasil;
                else:
                    $tambahBaruPenyGlwn56 += ($bulanLama + $tPenyusutanBaru);
                endif;
                $result = round(view_report($tambahBaruPenyGlwn56),-2);
                $item .= '<td class="text-right">'.custom_format($result).'</td>';
                $dataSaved[$val->glwnco.'-'.$val2->tahun.'-'.$cabang]['bulan_'.$val2->bulan] = $result;
                // $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right " data-name="bulan_'.$val2->bulan.'" data-id="'.$val2->sumber_data.'-'.$val->glwnco.'-'.$val2->tahun.'" data-value="'.$tambahBaruPenyGlwn56.'">'.custom_format($tambahBaruPenyGlwn56).'</div></td>';
            }
        }
            


        $item .= '</tr>';
        if($val->glwnco == '1621015'){
            $coa = $val->glwnco;
            $item .= '<tr>';
            $item .= '<td class="button"><button type="button" class="btn btn-danger btn-remove" data-id="1621015_baru" title="Hapus"><i class="fa-times"></i></button></td>';
            $item .= '<td>TAMBAHAN (BARU)</td>';
            $item .= '<td></td>';
            $item .= '<td></td>';
            foreach ($detail_tahun as $v => $val) {
                $a = $v + 1;
                $hasilTB = 0;

                $keySaved = multidimensional_search($A_saved, array(
                    'glwnco'     => $coa.'_baru',
                    'tahun_core' => $val->tahun
                ));
                $status = false;
                if(strlen($keySaved)>0):
                    $changed = json_decode($A_saved[$keySaved]['changed']);
                    if(in_array('bulan_'.$val->bulan, $changed)):
                        $status  = true;
                        $hasilTB = $A_saved[$keySaved]['bulan_'.$val->bulan];
                    endif;
                endif;
                
                if(!$status):
                    foreach ($E2 as $key) {
                        if($key->bulan == $val->bulan && $key->tahun == $val->tahun){
                            $hasilTB = $key->total;
                        }
                    }
                endif;
                
                // $item .= '<td class="text-right">'.custom_format($hasilTB).'</td>';
                $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right " data-name="bulan_'.$val->bulan.'" data-id="'.($val->sumber_data).'-'.$coa.'_baru'.'-'.$val->tahun.'" data-value="'.view_report($hasilTB).'">'.custom_format(view_report($hasilTB)).'</div></td>';
            }

            $item .= '</tr>';
        }else if($val->glwnco == '5621015'){
            $item .= '<tr>';
            $item .= '<td></td>';
            $item .= '<td>BIAYA PENYUSUTAN PD. BLN</td>';
            $item .= '<td></td>';
            $item .= '<td></td>';
            $tPenyusutanBaru = 0;
             foreach ($detail_tahun as $v => $val2) {
                $bulanLama = ($valC[0]['hasil']) - ($valC[0]['hasil2']);
                $keySaved = checkFormulaAkt(['glwnco' => $val->glwnco.'_baru','tahun_core' => $val2->tahun],$A_saved,'bulan_'.$val2->bulan);
                if($keySaved['status']):
                    $tPenyusutanBaru = $keySaved['data']['bulan_'.$val2->bulan];
                else:
                    $keySaved = checkFormulaAkt(['glwnco' => '1621015'.'_baru','tahun_core' => $val2->tahun],$A_saved,'bulan_'.$val2->bulan);
                    if($keySaved['status']):
                        $tPenyusutanBaru += ($keySaved['data']['bulan_'.$val2->bulan]*0.25)/12;
                    else:
                        $key = checkFormulaAkt2(['bulan' => $val2->bulan,'tahun' => $val2->tahun],$dtE2);
                        if($key['status']):
                            $tPenyusutanBaru += ($key['data']['total']*0.25)/12;
                        endif;
                    endif;
                endif; 
                $result = round(view_report($bulanLama + $tPenyusutanBaru),-2);
                $item .= '<td class="text-right">'.custom_format($result).'</td>'; 
            }

            $item .= '</tr>';
             $item .= '<tr>';
            $item .= '<td class="button"><button type="button" class="btn btn-danger btn-remove" data-id="5621015_baru" title="Hapus"><i class="fa-times"></i></button></td>';
            $item .= '<td>BIAYA PENYUSUTAN PD. BLN(Baru)</td>';
            $item .= '<td></td>';
            $item .= '<td></td>';
            $tPenyusutanBaru = 0;
            foreach ($detail_tahun as $v => $val2) {
                $keySaved = checkFormulaAkt(['glwnco' => $val->glwnco.'_baru','tahun_core' => $val2->tahun],$A_saved,'bulan_'.$val2->bulan);
                if($keySaved['status']):
                    $tPenyusutanBaru = $keySaved['data']['bulan_'.$val2->bulan];
                else:
                    $keySaved = checkFormulaAkt(['glwnco' => '1621015'.'_baru','tahun_core' => $val2->tahun],$A_saved,'bulan_'.$val2->bulan);
                    if($keySaved['status']):
                        $tPenyusutanBaru += ($keySaved['data']['bulan_'.$val2->bulan]*0.25)/12;
                    else:
                        $key = checkFormulaAkt2(['bulan' => $val2->bulan,'tahun' => $val2->tahun],$dtE2);
                        if($key['status']):
                            $tPenyusutanBaru += ($key['data']['total']*0.25)/12;
                        endif;
                    endif;
                endif;
                $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right " data-name="bulan_'.$val2->bulan.'" data-id="'.($val2->sumber_data).'-'.$val->glwnco.'_baru'.'-'.$val2->tahun.'" data-value="'.view_report($tPenyusutanBaru).'">'.custom_format(view_report($tPenyusutanBaru)).'</div></td>';
                // $item .= '<td class="text-right">'.custom_format($tPenyusutanBaru).'</td>';
            }

            $item .= '</tr>';
             $item .= '<tr>';
            $item .= '<td></td>';
            $item .= '<td>BIAYA PENYUSUTAN PD. BLN(Lama)</td>';
            $item .= '<td class="text-right"></td>';
            $bulanLama = ($valC[0]['hasil']) - ($valC[0]['hasil2']); 
            $item .= '<td class="text-right">'.custom_format(view_report($bulanLama)).'</td>';

            foreach ($detail_tahun as $v) {
                $bulanLama = round($bulanLama,-2);
                $item .= '<td class="text-right">'.custom_format(view_report($bulanLama)).'</td>';
            }

            $item .= '</tr>';

        }

    }

    $item .="</center>";
    echo $item;

    checkSavedFormulaAkt($dataSaved,$anggaran);
?>