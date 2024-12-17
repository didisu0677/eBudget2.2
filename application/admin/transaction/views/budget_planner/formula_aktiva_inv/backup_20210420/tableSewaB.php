<?php
$bac = 0;
$tambahBaru = 0;
$tambahBaruPeny = 0;
$tambahBaruPenyGlwn = 0;
$tambahBaruPenyGlwn56 = 0;
$tambahBaruGlwn1622 = 0;
$tambahBaruGlwn5621 = 0;
$item = "<center>.</center><center>.";
    $test = [];
       foreach ($B as $val) {
        // $item .= '<tr style = "background: #FF9800;">';
        $item .= '<tr style = "background: #FFF;">';
        $item .= '<td>'.$val->glwnco.'</td>';
        $item .= '<td>'.trim($val->glwdes).'</td>';
        $hasil2 = $val->hasil2 * -1;
        
        $item .= '<td  class="text-right">'.custom_format($hasil2).'</td>';
        $hasil = $val->hasil * -1;
        

        $item .= '<td class="text-right">'.custom_format($hasil).'</td>';

           if(substr($val->glwnco, 0,4) == "1621"){
                foreach ($detail_tahun as $v => $val2) {
                    $a = $v + 1;
                    $hasilTB = '0';
                    if(!empty($E4)){

                        foreach ($E4 as $key) {
                            if($key->bulan == $val2->bulan){
                                $hasilTB = $key->total;
                                $tambah = $hasil + $key->total;
                                $test[$val2->bulan] = $tambah;
                                $bac += $key->total;
                            }else{
                                $test[$val2->bulan] = $hasil + $bac;
                            }
                        }
                        if(isset($test[$val2->bulan])){
                            $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right edited" data-name="bulan_'.$val2->bulan.'" data-id="'.$val2->sumber_data.'-'.$val->glwnco.'" data-value="'.$test[$val2->bulan].'">'.custom_format($test[$val2->bulan]).'</div></td>'; 
                            // $item .= '<td class="text-right">'.custom_format($test[$val2->bulan]).'</td>';
                         }else {
                            $item .=  '<td class="text-right"></td>';
                         }

                    }else {
                        $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right edited" data-name="bulan_'.$val2->bulan.'" data-id="'.$val2->sumber_data.'-'.$val->glwnco.'" data-value="'.$hasil.'">'.custom_format($hasil).'</div></td>'; 
                        // $item .= '<td class="text-right">'.custom_format($hasil).'</td>';
                    }
                   
                   
                }
            }else if(substr($val->glwnco, 0,4) == "1622"){
                foreach ($detail_tahun as $v => $val2) {
                    $a = $v + 1;
                    $bulanLama = ($valB[0]['hasil'] * -1) - ($valB[0]['hasil2'] * -1); 
                    if(!empty($E4)){
                        foreach ($E4 as $key) {
                            if($key->bulan == $val2->bulan){
                                $tambah = $key->total/240;
                                $tambahBaruPenyGlwn += $tambah;
                                if($tambahBaruGlwn1622 == 0){
                                    $tampil =  ($val->hasil * -1) - ($tambah + $bulanLama);
                                    $tambahBaruGlwn1622 = $tampil;
                                }else {
                                    $tampil = $tambahBaruGlwn1622 - ($tambah + $bulanLama);
                                    $tambahBaruGlwn1622 = $tampil;
                                }
                                $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right edited" data-name="bulan_'.$val2->bulan.'" data-id="'.$val2->sumber_data.'-'.$val->glwnco.'" data-value="'.$tambahBaruGlwn1622.'">'.custom_format($tambahBaruGlwn1622).'</div></td>'; 
                                // $item .= '<td class="text-right">'.custom_format($tambahBaruGlwn1622).'</td>';
                            
                            }else{

                                // $tambah =  $val->hasil - ($tambahBaruPenyGlwn + $bulanLama) ;
                                if($tambahBaruGlwn1622 == 0){
                                    $tambah =  ($val->hasil * -1) - ($tambahBaruPenyGlwn + $bulanLama);
                                    $tambahBaruGlwn1622 = $tambah;
                                }else {
                                    $tambah =  $tambahBaruGlwn1622 - ($tambahBaruPenyGlwn + $bulanLama);
                                    $tambahBaruGlwn1622 = $tambah;
                                }
                                $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right edited" data-name="bulan_'.$val2->bulan.'" data-id="'.$val2->sumber_data.'-'.$val->glwnco.'" data-value="'.$tambahBaruGlwn1622.'">'.custom_format($tambahBaruGlwn1622).'</div></td>'; 
                                // $item .= '<td class="text-right">'.custom_format($tambahBaruGlwn1622).'</td>';
                            }
                        }
                    }else {
                         if($tambahBaruGlwn1622 == 0){
                                    $tambaha =  ($val->hasil * -1) -  $bulanLama;
                                    $tambahBaruGlwn1622 = $tambaha;
                            }else {
                                $tambaha =  $tambahBaruGlwn1622 - $bulanLama;
                                $tambahBaruGlwn1622 = $tambaha;
                            }
                              $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right edited" data-name="bulan_'.$val2->bulan.'" data-id="'.$val2->sumber_data.'-'.$val->glwnco.'" data-value="'.$tambahBaruGlwn1622.'">'.custom_format($tambahBaruGlwn1622).'</div></td>'; 
                         // $item .= '<td class="text-right">'.custom_format($tambahBaruGlwn1622).'</td>';
                    }
                   
                }
            }else if(substr($val->glwnco, 0,4) == "5621"){
                foreach ($detail_tahun as $v => $val2) {
                    $a = $v + 1;
                    $bulanLama = ($valB[0]['hasil'] * -1) - ($valB[0]['hasil2'] * -1); 
                    if(!empty($E4)){
                        foreach ($E4 as $key) {
                            if($key->bulan == $val2->bulan){
                                $tambah = $key->total/240;
                                $tambahBaruPenyGlwn56 += $tambah;
                                if($tambahBaruGlwn5621 == 0){
                                    $tampil =  ($val->hasil * -1) + ($tambah + $bulanLama);
                                    $tampilDef =  $tambah + $bulanLama;
                                    $tambahBaruGlwn5621 = $tampil;
                                    if($val2->bulan == '1'){
                                        $tambahBaruGlwn5621 = $tampilDef;
                                    }
                                }else {
                                    $tampil = $tambahBaruGlwn5621 + ($tambah + $bulanLama);
                                    $tampilDef =  $tambah + $bulanLama;
                                    $tambahBaruGlwn5621 = $tampil;
                                    if($val2->bulan == '1'){
                                        $tambahBaruGlwn5621 = $tampilDef;
                                    }
                                }
                                 $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right edited" data-name="bulan_'.$val2->bulan.'" data-id="'.$val2->sumber_data.'-'.$val->glwnco.'" data-value="'.$tambahBaruGlwn5621.'">'.custom_format($tambahBaruGlwn5621).'</div></td>'; 
                            
                            }else{

                                if($tambahBaruGlwn5621 == 0){
                                    $tambah =  ($val->hasil *-1) + ($tambahBaruPenyGlwn56 + $bulanLama);
                                    $tambahBaruGlwn5621 = $tambah;
                                    $tampilDef =  $tambahBaruPenyGlwn56 + $bulanLama;
                                    if($val2->bulan == '1'){
                                        $tambahBaruGlwn5621 = $tampilDef;
                                    }
                                }else {
                                    $tambah =  $tambahBaruGlwn5621 + ($tambahBaruPenyGlwn56 + $bulanLama);
                                    $tambahBaruGlwn5621 = $tambah;
                                    $tampilDef =  $tambahBaruPenyGlwn56 + $bulanLama;
                                    if($val2->bulan == '1'){
                                        $tambahBaruGlwn5621 = $tampilDef;
                                    }
                                }
                                  $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right edited" data-name="bulan_'.$val2->bulan.'" data-id="'.$val2->sumber_data.'-'.$val->glwnco.'" data-value="'.$tambahBaruGlwn5621.'">'.custom_format($tambahBaruGlwn5621).'</div></td>'; 
                            }
                        }

                    }else {
                        if($tambahBaruGlwn5621 == 0){
                                $tambahb =  $val->hasil +  $bulanLama;
                                $tambahBaruGlwn5621 = $tambahb;
                                $tampilDef =  $bulanLama;
                                if($val2->bulan == '1'){
                                    $tambahBaruGlwn5621 = $tampilDef;
                                }
                        }else {
                                
                                $tambahb =  $tambahBaruGlwn5621 + $bulanLama;
                                $tambahBaruGlwn5621 = $tambahb;
                                $tampilDef =  $bulanLama;
                                if($val2->bulan == '1'){
                                    $tambahBaruGlwn5621 = $tampilDef;
                                }
                        }
                         $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right edited" data-name="bulan_'.$val2->bulan.'" data-id="'.$val2->sumber_data.'-'.$val->glwnco.'" data-value="'.$tambahBaruGlwn5621.'">'.custom_format($tambahBaruGlwn5621).'</div></td>'; 
                    }
                   
                }
            }
            


        $item .= '</tr>';
        if($val->glwnco == '1621014'){
            $item .= '<tr>';
            $item .= '<td></td>';
            $item .= '<td>TAMBAHAN (BARU)</td>';
            $item .= '<td></td>';
            $item .= '<td></td>';
            foreach ($detail_tahun as $v => $val) {
                $a = $v + 1;
                $hasilTB = '0';
                foreach ($E4 as $key) {
                    if($key->bulan == $val->bulan){
                        $hasilTB = $key->total;
                    }
                }
                $item .= '<td class="text-right">'.custom_format($hasilTB).'</td>';
            }

            $item .= '</tr>';
        }else if($val->glwnco == '5621014' || $val->glwnco == '5791010' ){
            $item .= '<tr>';
            $item .= '<td></td>';
            $item .= '<td>BIAYA PENYUSUTAN PD. BLN</td>';
            $item .= '<td></td>';
            $item .= '<td></td>';
             foreach ($detail_tahun as $v => $val2) {
                $a = $v + 1;
                $bulanLama = ($valB[0]['hasil'] * -1) - ($valB[0]['hasil2'] * -1); 
                if(!empty($E4)){
                    foreach ($E4 as $key) {
                        if($key->bulan == $val2->bulan){
                            $tambah = $key->total/240;
                            $tambahBaruPeny += $tambah;
                            $tampil = $tambah + $bulanLama;
                            $item .= '<td class="text-right">'.custom_format($tampil).'</td>';
                        
                        }else{
                            $tambah = $tambahBaruPeny + $bulanLama;
                            $item .= '<td class="text-right">'.custom_format($tambah).'</td>';
                        }
                    }
                }else {
                     $item .= '<td class="text-right">'.custom_format($bulanLama).'</td>';
                }
                
            }

            $item .= '</tr>';
             $item .= '<tr>';
            $item .= '<td></td>';
            $item .= '<td>BIAYA PENYUSUTAN PD. BLN(Baru)</td>';
            $item .= '<td></td>';
            $item .= '<td></td>';
            foreach ($detail_tahun as $v => $val2) {
                $a = $v + 1;
                if(!empty($E4)){
                    foreach ($E4 as $key) {
                        if($key->bulan == $val2->bulan){
                            $tambah = $key->total/240;
                            $tambahBaru += $tambah;
                        }else{
                            $tambah = $tambahBaru;
                        }
                    }
                    if(isset($tambah)){
                        $item .= '<td class="text-right">'.custom_format($tambah).'</td>';
                    }else {
                        $item .= '<td class="text-right"></td>';
                    }

                }else {
                    $item .= '<td class="text-right">0</td>';
                }
               
                
            }

            $item .= '</tr>';
             $item .= '<tr>';
            $item .= '<td></td>';
            $item .= '<td>BIAYA PENYUSUTAN PD. BLN(Lama)</td>';
            $item .= '<td class="text-right"></td>';
            $bulanLama = ($valB[0]['hasil'] * -1) - ($valB[0]['hasil2'] * -1); 
            $item .= '<td class="text-right">'.custom_format($bulanLama).'</td>';
            foreach ($detail_tahun as $v) {
                
                $item .= '<td class="text-right">'.custom_format($bulanLama).'</td>';
            }

            $item .= '</tr>';

        }

    }

    $item .="</center>";
    echo $item;

?>