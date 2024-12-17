<?php
    $item   = '';
    $no     = 0;
    foreach ($group as $k => $v) {
        $dt_total = [];
        foreach ($v as $k2 => $v2) {
            $no ++;
            $item .= '<tr>';
            $item .= '<td>'.$no.'</td>';
            $item .= '<td>'.remove_spaces($v2->glwdes).'</td>';
            $prev_val = 0;
            for ($i=3; $i >= 0 ; $i--) {
                $t      = ($anggaran->tahun_anggaran - $i);
                if(12 != $anggaran->bulan_terakhir_realisasi && $t == $anggaran->tahun_terakhir_realisasi):
                    $key = multidimensional_search($data[$t], array(
                        'coa'   => $v2->coa,
                        'bulan' => $anggaran->bulan_terakhir_realisasi,
                    ));
                    $val2 = 0;
                    if(strlen($key)>0):
                        $val2 = $data[$t][$key]['total'];
                        $val2 = kali_minus($val2,$v2->kali_minus);
                        $item .= '<td class="text-right">'.custom_format(view_report($val2)).'</td>';
                    else:
                        $item .= '<td class="text-right">0</td>';
                    endif;
                    $last = $anggaran->bulan_terakhir_realisasi;
                    if(isset($dt_total[$t][$last])): $dt_total[$t][$last] += $val2; else: $dt_total[$t][$last] = $val2; endif;
                endif;

                $key = null;
                if(isset($data[$t])):
                    $key = multidimensional_search($data[$t], array(
                            'coa'   => $v2->coa,
                            'bulan' => 12,
                    ));
                endif;

                $val = 0;
                $k_tahun = multidimensional_search($detail_tahun, array(
                    'tahun' => $t,
                    'bulan' => 12,
                ));
                if($t == $anggaran->tahun_anggaran):
                    $key = multidimensional_search($data['renc'], array(
                        'coa'   => $v2->coa,
                    ));
                    if(strlen($key)>0):
                        $val = $data['renc'][$key]['total'];
                    endif;
                elseif(strlen($k_tahun)>0):
                    $key = multidimensional_search($data['renc'], array(
                        'coa'           => $v2->coa,
                        'parent_id'     => $kode_cabang,
                    ));
                    if(strlen($key)>0):
                        $val = $data['renc'][$key]['total'];
                    endif;
                elseif(isset($data[$t]) && count($data[$t])>0):
                    $key = multidimensional_search($data[$t], array(
                        'coa'   => $v2->coa,
                        'bulan' => 12,
                    ));
                    if(strlen($key)>0):
                        $val = $data[$t][$key]['total'];
                        $val = kali_minus($val,$v2->kali_minus);
                    endif;
                endif;

                if(isset($dt_total[$t][12])): $dt_total[$t][12] += $val; else: $dt_total[$t][12] = $val; endif;
                $item .= '<td class="text-right">'.custom_format(view_report($val)).'</td>';
                if($i<3):
                    $pertumbuhan = 0;
                    if($prev_val):
                        $pertumbuhan = (($val-$prev_val)/$prev_val)*100;
                    endif;
                    $item .= '<td class="text-right">'.custom_format($pertumbuhan,false,2).'</td>';
                endif;
                $prev_val = $val;
            }
            $item .= '</tr>';
        }

        if(count($v)>1):
            $no++;
            $item .= '<tr>';
            $item .= '<td>'.$no.'</td>';
            $item .= '<td><b>Total '.$k.'</b></td>';
            $prev_val = 0;
            for ($i=3; $i >= 0 ; $i--) {
                $t      = ($anggaran->tahun_anggaran - $i);
                $last = $anggaran->bulan_terakhir_realisasi;
                if(12 != $last && $t == $anggaran->tahun_terakhir_realisasi):
                    $val2 = 0;
                    if(isset($dt_total[$t][$last])):
                        $val2 = $dt_total[$t][$last];
                    endif;
                    $item .= '<td class="text-right"><b>'.custom_format(view_report($val2)).'</b></td>';
                endif;
                $val = 0;
                if(isset($dt_total[$t][12])):
                    $val = $dt_total[$t][12];
                endif;
                $item .= '<td class="text-right"><b>'.custom_format(view_report($val)).'</b></td>';
                if($i<3):
                    $pertumbuhan = 0;
                    if($prev_val):
                        $pertumbuhan = (($val-$prev_val)/$prev_val)*100;
                    endif;
                    $item .= '<td class="text-right"><b>'.custom_format($pertumbuhan,false,2).'</b></td>';
                endif;
                $prev_val = $val;
            }
            $item .= '</tr>';
        endif;
    }
    echo $item;
?>