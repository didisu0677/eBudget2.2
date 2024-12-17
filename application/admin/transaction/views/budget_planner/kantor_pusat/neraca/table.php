<?php
    $item = "";
    if(count($data)>0):
        foreach ($data as $val) {
        	if($val->glwnco == 1000000):
        		for ($i=1; $i <=12 ; $i++) { 
        			$val->{'b_'.$i} = ($val->{'b_'.$i} * -1);
        		}
        	endif;
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
    else:
        $item .= '<tr><td class="text-center" colspan="16">Data Not Found</td></tr>';
    endif;    
    echo $item;

?>