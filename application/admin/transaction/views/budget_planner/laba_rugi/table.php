<?php
$item = '';
$tr = ' class="text-right"';
foreach ($coa as $k => $v) {
    $item .= '<tr>';
    $item .= '<td>'.$v->glwsbi.'</td>'; 
    $item .= '<td>'.$v->glwnob.'</td>'; 
    $item .= '<td>'.$v->glwnco.'</td>'; 
    $item .= '<td>'.remove_spaces($v->glwdes).'</td>';
    for ($i=1; $i <= 12 ; $i++) { 
        $field = 'b_'.$i;
        $val   = kali_minus($v->{$field},$v->kali_minus);
        $item .= '<td class="text-right">'.check_value($val).'</td>';
    }
    $item .= '</tr>';
    if(isset($detail['1'][$v->glwnco])){
        foreach ($detail['1'][$v->glwnco] as $k2 => $v2) {
            $item .= '<tr>';
            $item .= '<td>'.$v2->glwsbi.'</td>'; 
            $item .= '<td>'.$v2->glwnob.'</td>'; 
            $item .= '<td>'.$v2->glwnco.'</td>';
            $item .= '<td class="sb-1">'.remove_spaces($v2->glwdes).'</td>';
            for ($i=1; $i <= 12 ; $i++) { 
                $field = 'b_'.$i;
                $val   = kali_minus($v2->{$field},$v2->kali_minus);
                $item .= '<td class="text-right">'.check_value($val).'</td>';
            }
            $item .= '</tr>';

            if(isset($detail['2'][$v2->glwnco])){
                foreach ($detail['2'][$v2->glwnco] as $k3 => $v3) {
                    $item .= '<tr>';
                    $item .= '<td>'.$v3->glwsbi.'</td>'; 
                    $item .= '<td>'.$v3->glwnob.'</td>'; 
                    $item .= '<td>'.$v3->glwnco.'</td>';
                    $item .= '<td class="sb-2">'.remove_spaces($v3->glwdes).'</td>';
                    for ($i=1; $i <= 12 ; $i++) { 
                        $field = 'b_'.$i;
                        $val   = kali_minus($v3->{$field},$v3->kali_minus);
                        $item .= '<td class="text-right">'.check_value($val).'</td>';
                    }
                    $item .= '</tr>';

                    if(isset($detail['3'][$v3->glwnco])){
                        foreach ($detail['3'][$v3->glwnco] as $k4 => $v4) {
                            $item .= '<tr>';
                            $item .= '<td>'.$v4->glwsbi.'</td>'; 
                            $item .= '<td>'.$v4->glwnob.'</td>'; 
                            $item .= '<td>'.$v4->glwnco.'</td>';
                            $item .= '<td class="sb-3">'.remove_spaces($v4->glwdes).'</td>';
                            for ($i=1; $i <= 12 ; $i++) { 
                                $field = 'b_'.$i;
                                $val   = kali_minus($v4->{$field},$v4->kali_minus);
                                $item .= '<td class="text-right">'.check_value($val).'</td>';
                            }
                            $item .= '</tr>';

                            if(isset($detail['4'][$v4->glwnco])){
                                foreach ($detail['4'][$v4->glwnco] as $k5 => $v5) {
                                    $item .= '<tr>';
                                    $item .= '<td>'.$v5->glwsbi.'</td>'; 
                                    $item .= '<td>'.$v5->glwnob.'</td>'; 
                                    $item .= '<td>'.$v5->glwnco.'</td>';
                                    $item .= '<td class="sb-4">'.remove_spaces($v5->glwdes).'</td>';
                                    for ($i=1; $i <= 12 ; $i++) { 
                                        $field = 'b_'.$i;
                                        $val   = kali_minus($v5->{$field},$v5->kali_minus);
                                        $item .= '<td class="text-right">'.check_value($val).'</td>';
                                    }
                                    $item .= '</tr>';

                                    if(isset($detail['5'][$v5->glwnco])){
                                        foreach ($detail['5'][$v5->glwnco] as $k6 => $v6) {
                                            $item .= '<tr>';
                                            $item .= '<td>'.$v6->glwsbi.'</td>'; 
                                            $item .= '<td>'.$v6->glwnob.'</td>'; 
                                            $item .= '<td>'.$v6->glwnco.'</td>';
                                            $item .= '<td class="sb-4">'.remove_spaces($v6->glwdes).'</td>';
                                            for ($i=1; $i <= 12 ; $i++) { 
                                                $field = 'b_'.$i;
                                                $val   = kali_minus($v6->{$field},$v6->kali_minus);
                                                $item .= '<td class="text-right">'.check_value($val).'</td>';
                                            }
                                            $item .= '</tr>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
echo $item;
?>