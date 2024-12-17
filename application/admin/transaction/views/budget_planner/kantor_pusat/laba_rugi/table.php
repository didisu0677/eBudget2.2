<?php
    $item = '';
    if(count($A)>0):
        $item .= '<tr style = "background:#deeaf6">';
        $item .= '<td>'.$nameA[0]['gwlsbi'].'</td>';
        $item .= '<td></td>';
        $item .= '<td></td>';
        $item .= '<td>'.$nameA[0]['account_name'].'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameA[0]['b_1'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameA[0]['b_2'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameA[0]['b_3'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameA[0]['b_4'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameA[0]['b_5'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameA[0]['b_6'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameA[0]['b_7'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameA[0]['b_8'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameA[0]['b_9'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameA[0]['b_10'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameA[0]['b_11'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameA[0]['b_12'])).'</td>';
        $item .= '</tr>';
        foreach ($A as $val) {
            $item .= '<tr>';
            $item .= '<td>'.$val->gwlsbi.'</td>';
            $item .= '<td>'.$val->coa.'</td>';
            $item .= '<td>'.$val->glwnco.'</td>';
            $item .= '<td>'.$val->account_name.'</td>';
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
            $item .= '</tr>';
        }
    endif;

    if(count($B)>0):
        $item .= '<tr style = "background:#deeaf6">';
        $item .= '<td>'.$nameB[0]['gwlsbi'].'</td>';
        $item .= '<td></td>';
        $item .= '<td></td>';
        $item .= '<td>'.$nameB[0]['account_name'].'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameB[0]['b_1'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameB[0]['b_2'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameB[0]['b_3'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameB[0]['b_4'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameB[0]['b_5'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameB[0]['b_6'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameB[0]['b_7'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameB[0]['b_8'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameB[0]['b_9'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameB[0]['b_10'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameB[0]['b_11'])).'</td>';
        $item .= '<td class="text-right">'.custom_format(view_report($nameB[0]['b_12'])).'</td>';
        $item .= '</tr>';
         foreach ($B as $val) {
            $item .= '<tr>';
            $item .= '<td>'.$val->gwlsbi.'</td>';
            $item .= '<td>'.$val->coa.'</td>';
            $item .= '<td>'.$val->glwnco.'</td>';
            $item .= '<td>'.$val->account_name.'</td>';
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
            $item .= '</tr>';
        }
    endif;

    if(count($A)<=0 && count($B)<=0):
        $item .= '<tr><td class="text-center" colspan="16">Data Not Found</td></tr>';
    endif;
    echo $item;
?>