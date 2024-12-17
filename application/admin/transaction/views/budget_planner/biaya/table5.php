<?php
$item = "";

    foreach ($name  as $m0) {

    $item .= '<tr>';
        $item .= '<td>'.$m0->coa.'</td>';
        $item .= '<td>'.$m0->account_name.'</td>';
    
        foreach ($coa as $m1) {
            for($i = 1; $i <= 12; $i++){

                if($m1->coa == $m0->coa ){
                    if($m1->bulan == $i ){
                         $item .= "<td class = 'text-right'>".custom_format(view_report($m1->isi))."</td>";
                    } else {
                        // $item .= "<td>0</td>";
                    }                   
                }              
            }
        }
        

    $item .= '<td>0</td><td>0</td><td>0</td></tr>';
    }

    

    echo $item;

?>