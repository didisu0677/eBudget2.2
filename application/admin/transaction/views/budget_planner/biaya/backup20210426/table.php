<?php
$getTahunMin = $tahun[0]['bulan_terakhir_realisasi'] - 1;
$table ='<tr>
            <th width="60" class="text-center align-middle headcol">COA</th>
            <th rowspan="2" class="text-center align-middle headcol" style="width:auto;min-width:230px">Keterangan</th>
            <th class="text-center" style="min-width:80px;">Januari</th>
            <th class="text-center" style="min-width:80px;">Februari</th>
            <th class="text-center" style="min-width:80px;">Maret</th>
            <th class="text-center" style="min-width:80px;">April</th>
            <th class="text-center" style="min-width:80px;">Mei</th>
            <th class="text-center" style="min-width:80px;">Juni</th>
            <th class="text-center" style="min-width:80px;">Juli</th>
            <th class="text-center" style="min-width:80px;">Agustus</th>
            <th class="text-center" style="min-width:80px;">September</th>
            <th class="text-center" style="min-width:80px;">Oktober</th>
            <th class="text-center" style="min-width:80px;">November</th>
            <th class="text-center" style="min-width:80px;">Desember</th>
            <th class="border-none"></th>
            <th class="text-center" style="min-width:80px;">Biaya Pertahun</th>
            <th class="text-center" style="min-width:80px;">Biaya Perbulan</th>
            <th class="border-none"></th>
            <th class="text-center" style="min-width:80px;">Sistem</th>
            <th class="text-center" style="min-width:80px;">Real '.month_lang($getTahunMin).'</th>
            <th class="text-center" style="min-width:80px;">Real '.month_lang($tahun[0]['bulan_terakhir_realisasi']).'</th>
        </tr>';
$item = "<center>";
    
       foreach ($A as $val) {
        $item .= '<tr>';
        $item .= '<td>'.$val->glwnco.'</td>';
        $item .= '<td>'.$val->glwdes.'</td>';

        $v_field = ($val->hasil * -1) / $tahun[0]['bulan_terakhir_realisasi'];
        $getTahun = $v_field * 12;
        $getTahunMin = $tahun[0]['bulan_terakhir_realisasi'] - 1;

        for ($i = 1; $i <= 12; $i++) {

            $field = 'bulan'.$i;

            if(!empty($val->$field)){
                $hasil = $v_field * $val->$field;
                $item .= '<td class="text-right">'.custom_format(view_report($v_field)).'</td>';
            }else {
                $item .= '<td class="text-right">'.custom_format(view_report($v_field)).'</td>';
            }
           
        }
        $item .= '<td class = "border-none"</td>';
        $item .= "<td>".custom_format(view_report($getTahun))."</td>";
        $item .= "<td>".custom_format(view_report($v_field))."</td>";
        $item .= '<td class = "border-none"</td>';
        $item .= "<td>".custom_format(view_report($v_field))."</td>";
        $item .= "<td>".custom_format(view_report($val->hasil9 * -1))."</td>";
        $item .= "<td>".custom_format(view_report($val->hasil * -1))."</td>";
    }
$item .="</tr>";
$item .="</table>";
$item .="<br><br>";
$item .="<table>";
$item .= $table;

$nilaiChild = [];
    foreach ($B as $val) {

        $item .= '<tr>';
        $item .= '<td>'.$val->glwnco.'</td>';
        $item .= '<td>'.$val->glwdes.'</td>';
        if($val->coa5 == "53303" || $val->coa5 == "53305" || $val->coa5 == "53306"){
               $v_field = ($val->hasil10 * -1) / $tahun[0]['bulan_terakhir_realisasi'];
        }else {
             $v_field = ($val->hasil10 - $val->hasil9 ) * -1;
        }
        
        $getTahun = $v_field * 12;
        $getTahunMin = $tahun[0]['bulan_terakhir_realisasi'] - 1;


        for ($i = 1; $i <= 12; $i++) {
            
           $field = 'bulan'.$i;

            if(!empty($val->$field)){
                $hasil = $v_field * $val->$field;
                if(!empty($val->level3)){
                    if(!empty($nilaiChild[$val->level3])){
                        $input = $nilaiChild[$val->level3] + $hasil;
                        $nilaiChild[$val->level3] = $input;
                    }else {
                         $nilaiChild[$val->level3] =  $hasil;
                    }                }
                else if(!empty($val->level2)){
                    if(!empty($nilaiChild[$val->level2])){
                        $input = $nilaiChild[$val->level2] + $hasil;
                        $nilaiChild[$val->level2] = $input;
                    }else {
                         $nilaiChild[$val->level2] =  $hasil;
                    }        
                }
                else if(!empty($val->level1)){
                     if(!empty($nilaiChild[$val->level1])){
                        $input = $nilaiChild[$val->level1] + $hasil;
                        $nilaiChild[$val->level1] = $input;
                    }else {
                         $nilaiChild[$val->level1] =  $hasil;
                    }      
                }
                // $item .= '<td class="text-right">'.json_encode($nilaiChild).'</td>';
                $item .= '<td class="text-right">'.custom_format(view_report($hasil)).'</td>';
            }else {
                $a = array_search($val->glwnco, $nilaiChild);
                $hasilTambah = $v_field;
                if(array_key_exists($val->glwnco, $nilaiChild)){
                    $hasilTambah = $v_field + $nilaiChild[$val->glwnco];
                }
                $item .= '<td class="text-right">'.custom_format(view_report($v_field)).'</td>';
            }
        }
        $item .= '<td class = "border-none"></td>';
        $item .= "<td>".custom_format(view_report($getTahun))."</td>";
        $item .= "<td>".custom_format(view_report($v_field))."</td>";
        $item .= '<td class = "border-none"</td>';
        $item .= "<td>".custom_format(view_report($v_field))."</td>";
        $item .= "<td>".custom_format(view_report($val->hasil9 * -1))."</td>";
        $item .= "<td>".custom_format(view_report($val->hasil10 * -1))."</td>";
    }

$item .="</tr>";
$item .="</table>";
$item .="<br><br>";
$item .="<table>";
$item .=$table;

    foreach ($C as $val) {
        $item .= '<tr>';
        $item .= '<td>'.$val->glwnco.'</td>';
        $item .= '<td>'.$val->glwdes.'</td>';
        for ($i = 1; $i <= 12; $i++) {
            if($val->coa == "54001" || $val->coa == "54101" || $val->coa == "53306"){
                $v_field = ($val->hasil10 - $val->hasil9 ) * -1;
            }else {
                $v_field = ($val->hasil10 * -1) / $tahun[0]['bulan_terakhir_realisasi'];
            }

        $getTahun = $v_field * 12;
        $getTahunMin = $tahun[0]['bulan_terakhir_realisasi'] - 1;

           

           
            $item .= '<td class="text-right">'.custom_format(view_report($v_field)).'</td>';

        }
        $item .= '<td class = "border-none"</td>';
        $item .= "<td>".custom_format(view_report($getTahun))."</td>";
        $item .= "<td>".custom_format(view_report($v_field))."</td>";
        $item .= '<td class = "border-none"</td>';
        $item .= "<td>".custom_format(view_report($v_field))."</td>";
        $item .= "<td>".custom_format(view_report($val->hasil9 * -1))."</td>";
        $item .= "<td>".custom_format(view_report($val->hasil10 * -1))."</td>";
    }

$item .="</tr>";
$item .="</table>";
$item .="<br><br>";
$item .="<table>";
$item .=$table;

    foreach ($D as $val) {
        $item .= '<tr>';
        $item .= '<td>'.$val->glwnco.'</td>';
        $item .= '<td>'.$val->glwdes.'</td>';
        $v_field = ($val->hasil * -1) / $tahun[0]['bulan_terakhir_realisasi']; 
        $getTahunMin = $tahun[0]['bulan_terakhir_realisasi'] - 1;
        
        $getTahun = $v_field * 12;
       for ($i = 1; $i <= 12; $i++) {
                     
            $item .= '<td class="text-right">'.custom_format(view_report($v_field)).'</td>';
        }
        $item .= '<td class = "border-none"</td>';
        $item .= "<td>".custom_format(view_report($getTahun))."</td>";
        $item .= "<td>".custom_format(view_report($v_field))."</td>";
        $item .= '<td class = "border-none"</td>';
        $item .= "<td>".custom_format(view_report($v_field))."</td>";
        $item .= "<td>".custom_format(view_report($val->hasil9 * -1))."</td>";
        $item .= "<td>".custom_format(view_report($val->hasil * -1))."</td>";
    }


$item .="</tr>";
$item .="</table>";

    $item .="</center>";
    echo $item;

?>