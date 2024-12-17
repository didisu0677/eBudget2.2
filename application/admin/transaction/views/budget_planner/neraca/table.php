<?php
$item = "<center>";
    foreach ($data as $val) {
        $item .= '<tr>';
        $item .= '<td>'.$val->glwsbi.'</td>';
        $item .= '<td>'.$val->glwnob.'</td>';
        $item .= '<td>'.$val->glwnco.'</td>';
        $item .= '<td>'.$val->glwdes.'</td>';
        if($val->kali_minus == 1){
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_1)* -1).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_2)* -1).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_3)* -1).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_4)* -1).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_5)* -1).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_6)* -1).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_7)* -1).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_8)* -1).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_9)* -1).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_10)* -1).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_11)* -1).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_12)* -1).'</td>';
        } else {
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_1)).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_2)).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_3)).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_4)).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_5)).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_6)).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_7)).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_8)).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_9)).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_10)).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_11)).'</td>';
            $item .= '<td class="text-right">'.custom_format(view_report($val->b_12)).'</td>';
        }
       
        $item .= '</tr>';
    }    
    $item .="</center>";
    echo $item;

?>