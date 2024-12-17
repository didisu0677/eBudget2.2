<?php
$item = "<center>";
    
    $where = [
        'kode_cabang'   => $kode_cabang,
        'kode_anggaran' => $anggaran->kode_anggaran,
        'tahun'         => $anggaran->tahun_anggaran,
    ];    
    $dataSaved = [];

       foreach ($A1 as $val) {
        $item .= '<tr>';
        $item .= '<td>'.$val->glwnco.'</td>';
        $item .= '<td>'.strtoupper(remove_spaces($val->glwdes)).'</td>';
            $x= 11 - $bulan_real['jmlbulan'];
            foreach ($detail_tahun as $v) {
                $T = 'T_' . $v->tahun . sprintf("%02d", $v->bulan);
                $field = 'P_' . sprintf("%02d", $v->bulan); 
                $$T = 0;
                foreach ($A1_detail as $k => $Ad) {
                    if($v->tahun == $Ad->tahun_core && $Ad->coa == $val->glwnco) {
                        $$T = $Ad->$field ;
                    }

                    foreach ($A1_Real as $A1) {
                        if($v->sumber_data == 1 && $A1->glwnco == $val->glwnco){
                            $hasil = 'hasil' . $x;
                            $$T = $A1->$hasil;
                            $x++;
                        }
                    }

                }



                $item .= '<td class="text-right">'.custom_format(view_report($$T)).'</td>';
                $dataSaved[$v->tahun.'-'.$val->glwnco]['bulan_'.$v->bulan] = $$T;
            }

        $item .= '</tr>';
        if($val->glwnco == '5131011'){
            $item .= '<tr>';
            $item .= '<td></td>';
            $item .= '<td> EFECTIVE RATE</td>';
            foreach ($detail_tahun as $v) {
                $item .= '<td class="text-right">'.custom_format($rate_nonkasda['rate'],false,2).'</td>';
            } 

            $item .= '</tr>';
            $item .= '<tr>';
            $item .= '<td></td>';
            $item .= '<td> JASA GIRO PIHAK III PD BULAN</td>';
            foreach ($detail_tahun as $v) {
                $item .= '<td></td>';
            }

            $item .= '</tr>';
        }
    }

    $item .= "<tr><td></td></tr>";

        foreach ($A2 as $val) {
        $item .= '<tr>';
        $item .= '<td>'.$val->glwnco.'</td>';
        $item .= '<td>'.strtoupper(remove_spaces($val->glwdes)).'</td>';
            $x= 11 - $bulan_real['jmlbulan'];
            foreach ($detail_tahun as $v) {
                $T = 'T_' . $v->tahun . sprintf("%02d", $v->bulan);
                $field = 'P_' . sprintf("%02d", $v->bulan); 
                $$T = 0;
                foreach ($A2_detail as $k => $Ad) {
                    if($v->tahun == $Ad->tahun_core && $Ad->coa == $val->glwnco) {
                        $$T = $Ad->$field ;
                    }

                    foreach ($A2_Real as $A2) {
                        if($v->sumber_data == 1 && $A2->glwnco == $val->glwnco){
                            $hasil = 'hasil' . $x;
                            $$T = $A2->$hasil;
                            $x++;
                        }
                    }
                }


                $item .= '<td class="text-right">'.custom_format(view_report($$T)).'</td>';
                $dataSaved[$v->tahun.'-'.$val->glwnco]['bulan_'.$v->bulan] = $$T;
            }


        $item .= '</tr>';
        if($val->glwnco == '5131012'){
            $item .= '<tr>';
            $item .= '<td></td>';
            $item .= '<td> EFECTIVE RATE</td>';
            foreach ($detail_tahun as $v) {
                $item .= '<td class="text-right">'.custom_format($rate_kasda['rate'],false,2).'</td>';
            }

            $item .= '</tr>';
            $item .= '<tr>';
            $item .= '<td></td>';
            $item .= '<td> JASA GIRO PIHAK III PD BULAN</td>';
            foreach ($detail_tahun as $v) {
                $item .= '<td></td>';
            }

            $item .= '</tr>';
        }

    }

    $item .= "</table>";
    $item .= "<br>";
    $item .= "<br>";
    $item .= "<table>";
    $item .= '<tr  style = "background:#f0f0f0;">';
    $item .= "<th>Coa</th>";
    $item .= "<th>Keterangan1</th>";
    foreach ($detail_tahun as $v) {
                    $column = month_lang($v->bulan).' '.$v->tahun;
                    $column .= '<br> ('.$v->singkatan.')';
                    $item .= '<th><center>'.$column.'</center></th>';
            }
    $item .= "</tr>";


    foreach ($B as $val) {
        $item .= '<tr>';
        $item .= '<td>'.$val->glwnco.'</td>';
        $item .= '<td>'.strtoupper(remove_spaces($val->glwdes)).'</td>';
        $x= 11 - $bulan_real['jmlbulan'];
        foreach ($detail_tahun as $v) {
             $vfield = 'P_'. sprintf("%02d", $v->bulan);
             $T = 'TOTAL_' . $v->tahun . sprintf("%02d", $v->bulan);
             $$T = 0;
            foreach ($rinc_tab as $r) {
                if($v->sumber_data != 1 && $v->tahun == $r->tahun_core && $r->coa == $val->glwnco){
                    $$T   = $r->$vfield;
                }

                foreach ($Real_tab as $Rtab) {
                    if($v->sumber_data == 1 && $Rtab->glwnco == $val->glwnco){
                        $hasil = 'hasil' . $x;
                        $$T = $Rtab->$hasil;
                        $x++;
                    }
                }
            }
            $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right edited" data-name="bulan_'.$v->bulan.'" data-id="'.$v->tahun.'-'.$val->glwnco.'" data-value="'.$$T.'">'.custom_format(view_report($$T)).'</div></td>';
            $dataSaved[$v->tahun.'-'.$val->glwnco]['bulan_'.$v->bulan] = $$T;
        }
        $item .= '<tr>';
        $item .= '<td>'.$val->biaya_bunga.'</td>';
        $item .= '<td>BUNGA '.strtoupper(remove_spaces($val->acct_bunga)).'</td>';
        $i_bln = 0;
        $x= 11 - $bulan_real['jmlbulan'];
        foreach ($detail_tahun as $v) {
             $i_bln++;
             $vfield = 'P_'. sprintf("%02d", $v->bulan);
             $T = 'TOTAL_' . $v->tahun . sprintf("%02d", $v->bulan);
             $R = 'RATE_' . $v->tahun . sprintf("%02d", $v->bulan);
             $RD = 'TR_' . $v->tahun . sprintf("%02d", $v->bulan);
             $$T = 0;
             $$R = 0;
             $$RD = 0;
            foreach ($rinc_tab as $r) {
                if($v->sumber_data != 1 && $v->tahun == $r->tahun_core && $r->coa == $val->glwnco){
                    $$T   = $r->$vfield;
                    $$R   = ($r->$vfield * $r->rate) / (100 * 12);
                }

                    
                if($i_bln == 1 || $v->bulan == 1 ) {
                    $$RD = $$R;
                }

                if($i_bln > 1 && $v->bulan != 1 ) {
                    $rbefore = 'TR_' . $v->tahun . sprintf("%02d", ($v->bulan - 1));
                    $$RD = $$rbefore + $$R;
                }
            }

                foreach ($Real_tab as $Rtab) {
                    if($v->sumber_data == 1 && $Rtab->glwnco == $val->biaya_bunga){
                        $hasil = 'hasil' . $x;
                        $$RD = $Rtab->$hasil;
                        $x++;
                    }
                }

            $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right edited" data-name="bulan_'.$v->bulan.'" data-id="'.$v->tahun.'-'.$val->glwnco.'" data-value="'.$$RD.'">'.custom_format(view_report($$RD)).'</div></td>';
            $dataSaved[$v->tahun.'-'.$val->biaya_bunga]['bulan_'.$v->bulan] = $$RD;
        }

        $item .= '</tr>';
        $item .= '<tr>';
        $item .= '<td></td>';
        $item .= '<td> EFECTIVE RATE</td>';
        foreach ($detail_tahun as $v) {            
            $R = 'RATE_' . $v->tahun . sprintf("%02d", $v->bulan);
            $$R = 0;
            foreach ($rinc_tab as $r) {
                if($v->tahun == $r->tahun_core && $r->coa == $val->glwnco){
                    $$R   = $r->rate;
                }
            }    

            $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right edited" data-name="bulan_'.$v->bulan.'" data-id="'.$v->tahun.'-'.$val->glwnco.'" data-value="'.$$R.'">'.custom_format($$R,false,2).'</div></td>';
        }

        $item .= '</tr>';
         $item .= '<tr>';
        $item .= '<td></td>';
        $item .= '<td>  BUNGA PD BULAN</td>';
        foreach ($detail_tahun as $v) {
            $vfield = 'P_'. sprintf("%02d", $v->bulan);
            $T = 'TOTAL_' . $v->tahun . sprintf("%02d", $v->bulan);
            $$T = 0;
            foreach ($rinc_tab as $r) {
                if($v->tahun == $r->tahun_core && $r->coa == $val->glwnco){
                    $$T   = ($r->$vfield * $r->rate) / (100 * 12);
                }
            }     

            $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right edited" data-name="bulan_'.$v->bulan.'" data-id="'.$v->tahun.'-'.$val->glwnco.'" data-value="'.$$T.'">'.custom_format(view_report($$T)).'</div></td>';
        }

        $item .= '</tr>';
         $item .= '<tr>';
        $item .= '<td></td>';
        $item .= '<td>BIAYA HADIAH PD BULAN</td>';
        foreach ($detail_tahun as $v) {
            $item .= '<td></td>';
        }

        $item .= '</tr>';

    }

    $item .= "</table>";
    $item .= "<br>";
    $item .= "<br>";
    $item .= "<table>";
    $item .= '<tr  style = "background:#f0f0f0;">';
    $item .= "<th>Coa</th>";
    $item .= "<th>Keterangan2</th>";
    $item .= "<th>Rate</th>";

    foreach ($detail_tahun as $v) {
                 $column = month_lang($v->bulan).' '.$v->tahun;
                 $column .= '<br> ('.$v->singkatan.')';
                 $item .= '<th><center>'.$column.'</center></th>';
            }
    $item .= "</tr>";

    foreach ($C as $val) {
        $item .= '<tr>';
        $item .= '<td>'.$val->coa.'</td>';
        $item .= '<td>'.$val->nama.'</td>';
        $item .= '<td class="text-right">'.custom_format($val->rate,false,2).'</td>';
            $x = 11 - $bulan_real['jmlbulan'];
            foreach ($detail_tahun as $v) {
                $vfield = 'P_'. sprintf("%02d", $v->bulan);
                $T = 'TOTAL_' . $v->tahun . sprintf("%02d", $v->bulan);
                $$T = 0;
                foreach ($rinc_dep as $r) {
                    if($v->sumber_data != 1 && $v->tahun == $r->tahun_core && $r->coa == $val->coa){
                        $$T   = $r->$vfield;
                    }
                }

                foreach ($Real_dep as $Rdep) {
                    if($v->sumber_data == 1 && $Rtab->glwnco == $val->coa){
                        $hasil = 'hasil' . $x;
                        $$T = $Rtab->$hasil;
                        $x++;
                    }
                }


                $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right edited" data-name="bulan_'.$v->bulan.'" data-id="'.$v->tahun.'-'.$val->coa.'" data-value="'.$$T.'">'.custom_format(view_report($$T)).'</td>';
                $dataSaved[$v->tahun.'-'.$val->coa]['bulan_'.$v->bulan] = $$T;
            }


        $item .= '</tr>';
    }

        foreach ($detail_tahun as $v) {   
            $Jumlah = 'JUMLAH_' . $v->tahun . sprintf("%02d", $v->bulan);      
            $$Jumlah = 0;
        }

  $item .= "</table>";
    $item .= "<br>";
    $item .= "<br>";
    $item .= "<table>";
    $item .= '<tr  style = "background:#f0f0f0;">';
    $item .= "<th>Coa</th>";
    $item .= "<th>Keterangan3</th>";
    for ($i = $realisasi['bulan_terakhir_realisasi'] -1; $i <= $realisasi['bulan_terakhir_realisasi']; $i++) { 
        $x = 'Real';
        $column = month_lang($i).' '.$realisasi['tahun_terakhir_realisasi'];
        $column .= '<br> ('.$x.')';
        $item .= '<th><center>'.$column.'</center></th>';                  
    }

    foreach ($detail_tahun2 as $v) {
                    $column = month_lang($v->bulan).' '.$v->tahun;
                    $column .= '<br> ('.$v->singkatan.')';
                 $item .= '<th><center>'.$column.'</center></th>';
            }
    $item .= "</tr>";

    foreach ($C as $val) {
        $item .= '<tr>';
        $item .= '<td>'.$val->coa.'</td>';
        $item .= '<td>'.$val->nama.'</td>';
        // $item .= '<td></td>';
        // $item .= '<td></td>';
            for ($i = $realisasi['bulan_terakhir_realisasi'] -1; $i <= $realisasi['bulan_terakhir_realisasi']; $i++) { 
                    $vfield = 'P_'. sprintf("%02d", $v->bulan);
                    $T = 'TOTAL_' . $v->tahun . sprintf("%02d", $v->bulan);
                    $$T = 0;
                    $Jumlah = 'JUMLAH_' . $v->tahun . sprintf("%02d", $v->bulan); 
                    foreach ($rinc_dep as $r) {
                        if(($v->bulan == $realisasi['bulan_terakhir_realisasi'] or $v->bulan == $realisasi['bulan_terakhir_realisasi']-1) && $v->sumber_data == 1 && $v->tahun == $r->tahun_core && $r->coa == $val->coa){
                            // $$T   = ($r->$vfield * $r->rate) / 1200;
                            // $$Jumlah += $$T ;

                            foreach ($Real_dep as $Rdep) {
                                if($v->sumber_data == 1 && $Rtab->glwnco == $val->coa){
                                    $hasil = 'hasil' . $x;
                                    $$T = $Rtab->$hasil;
                                    $x++;
                                }
                            }
                        }
                    }

                    $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right edited" data-name="bulan_'.$v->bulan.'" data-id="'.$v->tahun.'-'.$val->coa.'" data-value="'.$$T.'">'.custom_format(view_report($$T)).'</td>';
            }

            foreach ($detail_tahun2 as $v) {
                $vfield = 'P_'. sprintf("%02d", $v->bulan);
                $T = 'TOTAL_' . $v->tahun . sprintf("%02d", $v->bulan);
                $$T = 0;
                $Jumlah = 'JUMLAH_' . $v->tahun . sprintf("%02d", $v->bulan); 
                foreach ($rinc_dep as $r) {
                    if($v->sumber_data != 1 && $v->tahun == $r->tahun_core && $r->coa == $val->coa){
                        $$T   = ($r->$vfield * $r->rate) / 1200;
                        $$Jumlah += $$T ;
                    }
                }


                $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right edited" data-name="bulan_'.$v->bulan.'" data-id="'.$v->tahun.'-'.$val->coa.'" data-value="'.$$T.'">'.custom_format(view_report($$T)).'</td>';
            }
        $item .= '</tr>';
    }


        $item .= '<tr>';   
        $item .= '<td style="background:#ffeb3b"></td>'; 
        $item .= '<td style="background:#ffeb3b">BUNGA PD BULAN</td>';
        $item .= '<td style="background:#ffeb3b"></td>';
        $item .= '<td style="background:#ffeb3b"></td>';
        foreach ($detail_tahun2 as $v) {   
            $Jumlah = 'JUMLAH_' . $v->tahun . sprintf("%02d", $v->bulan);      

            if($v->tahun==user('tahun_anggaran')) {

                    if($v->bulan == 1){
                         $Jumlah = 'JUMLAH_' . ($v->tahun-1) . sprintf("%02d", '12'); 
                    }else{
                         $Jumlah = 'JUMLAH_' . $v->tahun . sprintf("%02d", $v->bulan-1); 
                    }
                $val = 0;
                if(isset($$Jumlah)) $val = $$Jumlah;
                $item .= '<td class="text-right" style="background:#ffeb3b">'.custom_format(view_report($val)).'</td>';
            }else{
                $item .= '<td class="text-right" style="background:#ffeb3b"></td>';                
            }
        }
        $item .= '</tr>';

    $item .= '<tr>';   
    $item .= '<td style="background:#ffeb3b">'.$Bunga['glwnco'].'</td>';
    $item .= '<td style="background:#ffeb3b">'.$Bunga['account_name'].'</td>';
    $item .= '<td class = "text-right" style="background:#ffeb3b">'.custom_format(view_report($Bunga['hasil9'] *-1)).'</td>';
    $item .= '<td class  = "text-right" style="background:#ffeb3b">'.custom_format(view_report($Bunga['hasil10'] *-1)).'</td>';
   


    $x = $Bunga['hasil10'] - $Bunga['hasil9'];
    $default = $x ;
    $default0 = $Bunga['hasil10'];

    $n = 0 ;
    $v = '' ;
    foreach ($detail_tahun2 as $k2 => $v2) {
        $n++;
   
        if($v2->bulan == 1 && $v2->tahun == user('tahun_anggaran')){
            //$Jumlah = 'JUMLAH_' . $v2->tahun . sprintf("%02d", $v2->bulan); 
             $Jumlah = 'JUMLAH_' . ($v2->tahun-1) . sprintf("%02d", '12'); 

            $$v = $$Jumlah ;  
            $nv = custom_format(view_report($$v));
        }elseif ($v2->bulan > 1 && $v2->tahun == user('tahun_anggaran')) {
            $Jumlah = 'JUMLAH_' . $v2->tahun . sprintf("%02d", ($v2->bulan-1)); 
            if(!isset($$v)) $$v = 0;
            if(isset($$Jumlah)) $$v += $$Jumlah ;
              

            $nv = custom_format(view_report($$v));
        }else{
            $$v = 0;
            $nv = '';
        }
// $nv = '';
// $$v = 0;
        $item .= '<td class="text-right" style="background:#ffeb3b">'.$nv.'</td>';
        $dataSaved[$v2->tahun.'-'.$Bunga['glwnco']]['bulan_'.$v2->bulan] = $$v;
    }
    $item .= '</tr>'; 

    $item .="</center>";
    echo $item;
    checkForSave($dataSaved,$where);

    function checkForSave($data,$p1){
        foreach ($data as $k => $v) {
            $x = explode('-', $k);
            $tahun  = $x[0];
            $coa    = $x[1];

            $where = [
                'kode_anggaran' => $p1['kode_anggaran'],
                'kode_cabang'   => $p1['kode_cabang'],
                'glwnco'        => $coa,
                'parent_id'     => '0',
            ];
            if($tahun != $p1['tahun']):
                $where['parent_id'] = $p1['kode_cabang'];
            endif;

            $ck = get_data('tbl_formula_dpk',[
                'select'    => 'id',
                'where'     => $where
            ])->row();

            $dt = array_merge($v,$where);
            if($ck):
                $dt['id'] = $ck->id;
            endif;
            save_data('tbl_formula_dpk',$dt);
        }
    }
?>