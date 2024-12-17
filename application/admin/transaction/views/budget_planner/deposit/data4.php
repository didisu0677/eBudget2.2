<?php
    $item = '';
    $arrTotal = [];
    if(count($list)>0):
        foreach ($list as $k => $v) {
            $item .= '<tr>';
            $item .= '<td>'.($k+1).'</td>';
            $item .= '<td>'.$v->nama.'</td>';

            $bgedit =bgEdit();
            $contentedit ="true"; 
            $id = "id";     
            $contentedit ="true" ;

            foreach ($detail_tahun as $d => $value) { 
                $vfield = 'P_'. sprintf("%02d", $value['bulan']);
                $j1 = 'JML_'.   $value['tahun'] . sprintf("%02d", $value['bulan']);
                $$j1 = 0;
                $jindex = 0;
                foreach ($list_dep as $kd => $vd) {             
                    if($value['tahun'] == $vd->tahun_core && $v->coa == $vd->coa){  
                        $$j1 = $vd->$vfield;                        
                        $jindex = $vd->index_kali ;
                    }
                }

                $jml = 0;
                foreach ($jml_akhir_rek as $j) {
                    if($v->coa == $j->coa){
                        $jml = $j->jumlah;
                    }
                }

                if(isset($arrTotal[$value['tahun'].'-'.$vfield])):
                    $arrTotal[$value['tahun'].'-'.$vfield] += $$j1;
                else:
                    $arrTotal[$value['tahun'].'-'.$vfield] = $$j1;
                endif;

                $item .= '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="'.$contentedit.'" contenteditable="true" class="edit-value text-right" data-name="'.$v->coa.'|table4'.'|'.substr($j1,4).'|'.$v->coa.'|'.$value['id_tahun_anggaran'].'|'.$cabang.'" data-id="'.$v->id.'" data-value="'.$$j1.'">'.custom_format($$j1).'</div></td>';

                }

            $item .= '<td class="border-none"></td>' ;
            $item .= '<td class="edit-value text-right" data-name="'.$v->coa.'|table5'.'|'.substr($j1,4).'|'.$v->coa.'|'.$value['id_tahun_anggaran'].'|'.$cabang.'" data-id="'.$v->id.'" data-value="'.$jml.'">'.custom_format($jml).'</td>' ;

            $item .= '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="'.$contentedit.'" contenteditable="true" class="edit-value text-right" data-name="'.$v->coa.'|table6'.'|'.substr($j1,4).'|'.$v->coa.'|'.$value['id_tahun_anggaran'].'|'.$cabang.'" data-id="'.$v->id.'" data-value="'.$jindex.'">'.custom_format($jindex).'</div></td>';

    //  die;
        }
        $item .= '<tr>';
        $item .= '<th colspan="2" class="text-right">Total</th>';
        foreach ($detail_tahun as $d => $value) {   
            $vfield = 'P_'. sprintf("%02d", $value['bulan']);
            $total = $arrTotal[$value['tahun'].'-'.$vfield];
            $item .= '<th class="text-right">'.custom_format($total).'</th>';
        }
        $item .= '</tr>';
    else:
        $item .= '<tr><td>Data tidak ditemukan</td></tr>';
    endif;
    echo $item;

    $h['arr_total_rinc_deposito'] = $arrTotal;
    $this->session->set_userdata($h);
?>