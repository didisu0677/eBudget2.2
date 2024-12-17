<?php
    error_reporting(0);
    $item = "<center>";

 
    $dataDpk = [];
    foreach ($rateDpk as $dpk) {

        // $item .= '<tr><td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="edit-value text-right edited" data-name="kode" data-id="A1" data-value="">A1</div></td>';
        // $item .- '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="edit-value text-right edited" data-name="keterangan" data-id="A1" data-value="">Effective Rate DPK</div></td>';
        $item .= "<tr><td>A1</td><td>Effective Rate DPK</td>";
        for($i = 1;$i<=12;$i++){
            $bulan = 'hasil'.$i;
            $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A1" data-value="'.$dpk[$bulan].'">'.custom_format($dpk[$bulan],false,2).'</div></td>';  
        }
        $item .= "</tr>";

        // $item .= '<tr><td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="edit-value text-right edited" data-name="kode" data-id="A2" data-value="">A2</div></td>';
        // $item .- '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="edit-value text-right edited" data-name="keterangan" data-id="A2" data-value="">- Biaya Bunga DPK</div></td>';
        $item .= "<tr><td>A2</td><td>- Biaya Bunga DPK</td>";
        for($i = 1;$i<=12;$i++){
            $bulan = 'bulan_'.$i;
            $item .='<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A2" data-value="'.$dpk[$bulan].'">'.custom_format(view_report($dpk[$bulan])).'</div></td>';  
        }
        $item .= "</tr>";

        //  $item .= '<tr><td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="edit-value text-right edited" data-name="kode" data-id="A3" data-value="">A3</div></td>';
        // $item .- '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="edit-value text-right edited" data-name="keterangan" data-id="A3" data-value="">- DPK</div></td>';
        $item .= "<tr><td>A3</td><td>- DPK</td>";
        for($i = 1;$i<=12;$i++){
            $bulan = 'B_'.sprintf("%02d",$i);
            $dataDpk[$i] = $dpk[$bulan];
             $item .='<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A3" data-value="'.$dpk[$bulan].'">'.custom_format(view_report($dpk[$bulan])).'</div></td>';  
        }
        $item .= "</tr>";
        
    }

 
    $item .= "<tr><td class = border-none>.</td></tr>";

    foreach ($rateKredit as $kredit) {

        // $item .= '<tr><td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="edit-value text-right edited" data-name="kode" data-id="A4" data-value="">A4</div></td>';
        // $item .- '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="edit-value text-right edited" data-name="keterangan" data-id="A4" data-value="">Effective Rate Kredit</div></td>';
        $item .= "<tr><td>A4</td><td>Effective Rate Kredit</td>";

        for($i = 1;$i<=12;$i++){
            $bulan = 'hasil'.$i;
            $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A4" data-value="'.$kredit[$bulan].'">'.custom_format($kredit[$bulan],false,2).'</div></td>';   
        }
        $item .= "</tr>";


        // $item .= '<tr><td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="edit-value text-right edited" data-name="kode" data-id="A5" data-value="">A5</div></td>';
        // $item .- '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="edit-value text-right edited" data-name="keterangan" data-id="A5" data-value="">- Biaya Bunga Kredit</div></td>';

        $item .= "<tr><td>A5</td><td>- Biaya Bunga Kredit</td>";
        for($i = 1;$i<=12;$i++){
            $bulan = 'bulan_'.$i;
             $item .='<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A5" data-value="'.$kredit[$bulan].'">'.custom_format(view_report($kredit[$bulan])).'</div></td>';  
        }
        $item .= "</tr>";
        // $item .= '<tr><td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="edit-value text-right edited" data-name="kode" data-id="A6" data-value="">A6</div></td>';
        // $item .- '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="edit-value text-right edited" data-name="keterangan" data-id="A6" data-value="">- Kredit</div></td>';
        $item .= "<tr><td>A6</td><td>- Kredit</td>";
        for($i = 1;$i<=12;$i++){
            $bulan = 'B_'.sprintf("%02d",$i);
            $item .='<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A6" data-value="'.$kredit[$bulan].'">'.custom_format(view_report($kredit[$bulan])).'</div></td>';  
        }
        $item .= "</tr>";
       
    }


    $item .= "<tr><td class = border-none>.</td></tr>";

    $port01 = array_search('122501', array_column($portofolioKredit, 'coa')); 
    $port02 = array_search('122502', array_column($portofolioKredit, 'coa')); 
    $port06 = array_search('122506', array_column($portofolioKredit, 'coa'));

    // $item .= '<tr><td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="edit-value text-right edited" data-name="kode" data-id="A7" data-value="">A7</div></td>';
    // $item .- '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="edit-value text-right edited" data-name="keterangan" data-id="A7" data-value="">Portofolio Kredit :</div></td>';

    $item .= "<tr><td>A7</td><td>Portofolio Kredit :</td>";
    for($i = 1;$i<=12;$i++){
        $item .= "<td class='text-right'></td>";
    }
    
    $item .= "</tr>";
    // $item .= '<tr><td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="edit-value text-right edited" data-name="kode" data-id="A8" data-value="">A8</div></td>';
    // $item .- '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="edit-value text-right edited" data-name="keterangan" data-id="A8" data-value="">Portofolio Kredit Produktif</div></td>';

    $item .= "<tr><td>A8</td><td>Portofolio Kredit Produktif</td>";

     for($i = 1;$i<=12;$i++){
        
        $bulan      = 'B_'.sprintf("%02d",$i);
        $hasil02 = 0;
        if(!empty($portofolioKredit)){
         $hasil02    =  ($portofolioKredit[$port02][$bulan]/$portofolioKredit[$port01][$bulan]) * 100;
        }
        $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A8" data-value="'.$hasil02.'">'.custom_format($hasil02,false,2).'</div></td>';   
        // $item       .= "<td class='text-right'>".custom_format($hasil02,false,2)."</td>";
        
    }
    $item .= "</tr>";

    // $item .= '<tr><td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="edit-value text-right edited" data-name="kode" data-id="A9" data-value="">A9</div></td>';
    // $item .- '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"  class="edit-value text-right edited" data-name="keterangan" data-id="A9" data-value="">Portofolio Kredit Konsumtif</div></td>';
     $item .= "<tr><td>A9</td><td>Portofolio Kredit Konsumtif</td>";

    for($i = 1;$i<=12;$i++){
        $bulan      = 'B_'.sprintf("%02d",$i);
        $hasil06 = 0;
        if(!empty($portofolioKredit)){
         $hasil06    =  ($portofolioKredit[$port06][$bulan]/$portofolioKredit[$port01][$bulan]) * 100;
        }
        $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A9" data-value="'.$hasil06.'">'.custom_format($hasil06,false,2).'</div></td>'; 
        // $item       .= "<td class='text-right'>".custom_format($hasil06,false,2)."</td>";
    }
    $item .= "</tr>";



    $item .= "<tr><td class = border-none>.</td></tr>";

    $kolNpl1 = array_search('1', array_column($kolektabilitasNpl, 'tipe')); 
    $kolNpl2 = array_search('2', array_column($kolektabilitasNpl, 'tipe')); 
    $kolNpl3 = array_search('3', array_column($kolektabilitasNpl, 'tipe')); 


    $item .= "<tr><td>A10</td><td>NPL Total Kredit</td>";

     for($i = 1;$i<=12;$i++){
        $bulan      = 'B_'.sprintf("%02d",$i);
        $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A10" data-value="'.$kolektabilitasNpl[$kolNpl3][$bulan].'">'.custom_format($kolektabilitasNpl[$kolNpl3][$bulan],false,2).'</div></td>'; 
        // $item       .= "<td class='text-right'>".custom_format($kolektabilitasNpl[$kolNpl3][$bulan],false,2)."</td>";
    }
    $item .= "</tr>";

    $item .= "<tr><td>A11</td><td>NPL Produktif</td>";

    for($i = 1;$i<=12;$i++){
        $bulan      = 'B_'.sprintf("%02d",$i);
        $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A11" data-value="'.$kolektabilitasNpl[$kolNpl1][$bulan].'">'.custom_format($kolektabilitasNpl[$kolNpl1][$bulan],false,2).'</div></td>'; 
        // $item       .= "<td class='text-right'>".custom_format($kolektabilitasNpl[$kolNpl1][$bulan],false,2)."</td>";
    }
    $item .= "</tr>";

    $item .= "<tr><td>A12</td><td>Total Krd Produktif</td>";

    $totKolProd = [];
    for($i = 1;$i<=12;$i++){
        $bulan          = 'B_'.sprintf("%02d",$i);
        $hasil          =  $kolektabilitasDetail1[0][$bulan];
        $totKolProd[$i] =  $kolektabilitasDetail1[0][$bulan];
        $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A12" data-value="'.$hasil.'">'.custom_format(view_report($hasil)).'</div></td>'; 
        // $item           .= "<td class='text-right'>".custom_format(view_report($hasil))."</td>";
    }
    $item .= "</tr>";

    $kode = 12 ;
    for($a = 1;$a<=5;$a++){
        $kode = $kode +1;

        $item .= "<tr><td>A".$kode."</td><td>Kol. ".$a."</td>";

        for($i = 1;$i<=12;$i++){
            $bulan      = 'B_'.sprintf("%02d",$i).'_'.$a;
            $hasil    =  $kolektabilitasDetail1[0][$bulan];
            $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A'.$kode.'" data-value="'.$hasil.'">'.custom_format(view_report($hasil)).'</div></td>'; 

            // $item       .= "<td class='text-right'>".custom_format(view_report($hasil))."</td>";
        }
        $item .= "</tr>";
   
    }


    $item .= "<tr><td class = border-none>.</td></tr>";

    $item .= "<tr><td>A18</td><td>NPL Konsumtif</td>";

    for($i = 1;$i<=12;$i++){
        $bulan      = 'B_'.sprintf("%02d",$i);
         $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A18" data-value="'.$kolektabilitasNpl[$kolNpl2][$bulan].'">'.custom_format($kolektabilitasNpl[$kolNpl2][$bulan],false,2).'</div></td>'; 
        // $item       .= "<td class='text-right'>".custom_format($kolektabilitasNpl[$kolNpl2][$bulan],false,2)."</td>";
    }
    $item .= "</tr>";

    $item .= "<tr><td>A19</td><td>Total Krd Konsumtif</td>";

    $totKolKons = [];
    for($i = 1;$i<=12;$i++){
        $bulan          = 'B_'.sprintf("%02d",$i);
        $hasil          =  $kolektabilitasDetail2[0][$bulan];
        $totKolKons[$i] =  $kolektabilitasDetail2[0][$bulan];
        $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A19" data-value="'.$hasil.'">'.custom_format(view_report($hasil)).'</div></td>'; 
        // $item           .= "<td class='text-right'>".custom_format(view_report($hasil))."</td>";
    }
    $item .= "</tr>";

    $kode = 19 ;
    for($a = 1;$a<=5;$a++){
        $kode = $kode+1;

        $item .= "<tr><td>A".$kode."</td><td>Kol. ".$a."</td>";

        for($i = 1;$i<=12;$i++){
            $bulan      = 'B_'.sprintf("%02d",$i).'_'.$a;
            $hasil    =  $kolektabilitasDetail2[0][$bulan];
            $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A'.$kode.'" data-value="'.$hasil.'">'.custom_format(view_report($hasil)).'</div></td>'; 
            // $item       .= "<td class='text-right'>".custom_format(view_report($hasil))."</td>";
        }
        $item .= "</tr>";
   
    }

    
    $item .= "<tr><td class = border-none>.</td></tr>";

    $item .= "<tr><td>A25</td><td>Loan to Deposit Ratio (LDR)</td>";
    for($i = 1;$i<=12;$i++){

        $hasil = 0;
        if(!empty($totKolProd) && !empty($dataDpk)){
             $hasil = (($totKolProd[$i] + $totKolKons[$i]) / $dataDpk[$i])*100;
        }

        $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A25" data-value="'.$hasil.'">'.custom_format($hasil,false,2).'</div></td>'; 
       
        // $item .= "<td class='text-right'>".custom_format($hasil,false,2)."</td>";  
    }
    $item .= "</tr>";

    $item .= "<tr><td class = border-none>.</td></tr>";

    $loan41 = array_search('4100000', array_column($loan, 'glwnco')); 
    $loan51 = array_search('5100000', array_column($loan, 'glwnco')); 
    $loan55 = array_search('5500000', array_column($loan, 'glwnco')); 

    $Biaya_opr  = [];
    $pend_opr   = [];
    $item .= "<tr><td>A26</td><td>Rasio Biaya Operasional thd Pend. Operasional (BOPO)</td>";
    for($i = 1;$i<=12;$i++){

        $bulan = 'bulan_'.$i;
        $hasil = 0;
        if(!empty($loan)){
            $Biaya_opr[$i] = $loan[$loan51][$bulan]+$loan[$loan55][$bulan];
            $pend_opr[$i] = $loan[$loan41][$bulan]+$loan[$loan51][$bulan];
            $hasil = ($Biaya_opr[$i]/$pend_opr[$i])*100;
        }
        
        $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A26" data-value="'.$hasil.'">'.custom_format($hasil,false,2).'</div></td>'; 
        // $item .= "<td class='text-right'>".custom_format($hasil,false,2)."</td>";  

    }
    $item .= "</tr>";

    $item .= "<tr><td>A27</td><td>- Biaya Opr</td>";
    for($i = 1;$i<=12;$i++){
        if(!empty($Biaya_opr)){
             $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A27" data-value="'.$Biaya_opr[$i].'">'.custom_format(view_report($Biaya_opr[$i])).'</div></td>'; 
            // $item .= "<td class='text-right'>".custom_format(view_report($Biaya_opr[$i]))."</td>"; 
        }else {
             $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A27" data-value="0">0</div></td>'; 
            // $item .= "<td class='text-right'>0</td>"; 
        }
         
    }
    $item .= "</tr>";

    $item .= "<tr><td>A28</td><td>- Pend Opr</td>";
    for($i = 1;$i<=12;$i++){
        if(!empty($pend_opr)){
             $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A28" data-value="'.$pend_opr[$i].'">'.custom_format(view_report($pend_opr[$i])).'</div></td>'; 
            // $item .= "<td class='text-right'>".custom_format(view_report($pend_opr[$i]))."</td>"; 
        }else {
             $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A289" data-value="0">0</div></td>'; 
            // $item .= "<td class='text-right'>0</td>"; 
        }
    }
    $item .= "</tr>";


    $item .= "<tr><td class = border-none>.</td></tr>";
    foreach ($roa as $dataroa) {

        $everoa = 0;
        $item .= "<tr><td>A29</td><td>Rasio ROA</td>";
        for($i = 1;$i<=12;$i++){
            $bulan = 'hasil'.$i;
            $bulanAss = 'B_'.sprintf("%02d",$i);

            $everoa = $everoa + $dataroa[$bulanAss];
            $everoaFix = $everoa / $i;

            $hasil = ($dataroa[$bulan]/$everoaFix)*100;
            $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A29" data-value="'.$hasil.'">'.custom_format($hasil,false,2).'</div></td>'; 

            // $item .= "<td class='text-right'>".custom_format($hasil,false,2)."</td>";  
        }
        $item .= "</tr>";

        $item .= "<tr><td>A30</td><td>- Laba</td>";
        for($i = 1;$i<=12;$i++){
            $bulan = 'bulan_'.$i;
            $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A30" data-value="'.$dataroa[$bulan].'">'.custom_format(view_report($dataroa[$bulan])).'</div></td>'; 
            // $item .= "<td class='text-right'>".custom_format(view_report($dataroa[$bulan]))."</td>";  
        }
        $item .= "</tr>";

        $item .= "<tr><td>A31</td><td>- Asset</td>";
        $everoa = 0;
        for($i = 1;$i<=12;$i++){
            $bulan = 'B_'.sprintf("%02d",$i);

            $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A31" data-value="'.$dataroa[$bulan].'">'.custom_format(view_report($dataroa[$bulan])).'</div></td>'; 
            // $item .= "<td class='text-right'>".custom_format(view_report($dataroa[$bulan]))."</td>";  
        }
        $item .= "</tr>";
        
    }


    $casa602 = array_search('602', array_column($casa, 'coa')); 
    $casa213 = array_search('2130000', array_column($casa, 'coa')); 
    $item .= "<tr><td class = border-none>.</td></tr>";

        $item .= "<tr><td>A32</td><td>Rasio Dana Murah (CASA)</td>";
        for($i = 1;$i<=12;$i++){
            $bulan = 'B_'.sprintf("%02d",$i);
            $hasil = 0;
            if(!empty($casa)){

                $hasil = (($casa[$casa602][$bulan] - $casa[$casa213][$bulan])/$casa[$casa602][$bulan])*100;
            }
             $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A32" data-value="'.$hasil.'">'.custom_format($hasil,false,2).'</div></td>'; 
            // $item .= "<td class='text-right'>".custom_format($hasil,false,2)."</td>";  
        }
        $item .= "</tr>";

        $item .= "<tr><td></td><td>Giro dan Tabungan</td>";
        for($i = 1;$i<=12;$i++){
            $bulan = 'B_'.sprintf("%02d",$i);
            $hasil = 0;
            if(!empty($casa)){
                $hasil = $casa[$casa602][$bulan] - $casa[$casa213][$bulan];
            }
             $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A32.1" data-value="'.$hasil.'">'.custom_format(view_report($hasil)).'</div></td>'; 
            // $item .= "<td class='text-right'>".custom_format(view_report($hasil))."</td>";  
        }
        $item .= "</tr>";

        $item .= "<tr><td></td><td>DPK</td>";
        for($i = 1;$i<=12;$i++){
            $bulan = 'B_'.sprintf("%02d",$i);
            if(!empty($casa)){
                 $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A32.2" data-value="'.$casa[$casa602][$bulan].'">'.custom_format(view_report($casa[$casa602][$bulan])).'</div></td>'; 
                // $item .= "<td class='text-right'>".custom_format(view_report($casa[$casa602][$bulan]))."</td>";
            }else {
                 $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A32.2" data-value="0">0</div></td>'; 
                // $item .= "<td class='text-right'>0</td>";
            } 
        }
        $item .= "</tr>";
        



    $nim51 = array_search('5100000', array_column($nim, 'glwnco')); 
    $nim41 = array_search('4100000', array_column($nim, 'glwnco'));  
    $item .= "<tr><td class = border-none>.</td></tr>";
    $eveNim = 0;
        $item .= "<tr><td>A33</td><td>Net Interest Margin (NIM)</td>";
        for($i = 1;$i<=12;$i++){
            $bulan = 'B_'.sprintf("%02d",$i);
            $bulanlaba = 'bulan_'.$i;
            $hasil = 0;
            
            if(!empty($nimAktifa) && !empty($nim)){
                 $eveNim = $eveNim + $nimAktifa[0][$bulan];
                if($eveNim == 0){
                    $eveNim = $nimAktifa[0][$bulan];
                }
                $eveNimFix = $eveNim /$i;

                $pendBunga = $nim[$nim41][$bulanlaba] - $nim[$nim51][$bulanlaba];

                $hasil = ((($pendBunga/$i)*12)/$eveNimFix)*100;
            }
           

             $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A33" data-value="'.$hasil.'">'.custom_format($hasil,false,2).'</div></td>'; 
            // $item .= "<td class='text-right'>".custom_format($hasil,false,2)."</td>";  
        }
        $item .= "</tr>";

        $item .= "<tr><td></td><td>- Pend Bunga Bersih</td>";
        for($i = 1;$i<=12;$i++){
            $bulan = 'bulan_'.$i;
            $hasil = 0;
            if(!empty($nim)){
                $hasil = $nim[$nim41][$bulan] - $nim[$nim51][$bulan];
            }
            // $hasil = ($hasil/ (float) $i )*12;
            $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A33.1" data-value="'.$hasil.'">'.custom_format(view_report($hasil)).'</div></td>'; 
            // $item .= "<td class='text-right'>".custom_format(view_report($hasil))."</td>";  
        }
        $item .= "</tr>";

        $item .= "<tr><td></td><td>- aktiva Produktif</td>";
        for($i = 1;$i<=12;$i++){
            $bulan = 'B_'.sprintf("%02d",$i);
            // if(!empty($nimAktifa)){

             $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A33.2" data-value="'.$nimAktifa[0][$bulan].'">'.custom_format(view_report($nimAktifa[0][$bulan])).'</div></td>'; 
                // $item .= "<td class='text-right'>".custom_format(view_report($nimAktifa[0][$bulan]))."</td>"; 
            // }
             // $item .= "<td class='text-right'>0</td>"; 
        }
        $item .= "</tr>";
        


    $rasio41 = array_search('4100000', array_column($rasiofee, 'glwnco')); 
    $rasio45 = array_search('4500000', array_column($rasiofee, 'glwnco')); 
    $rasio459 = array_search('4590000', array_column($rasiofee, 'glwnco')); 
    $item .= "<tr><td class = border-none>.</td></tr>";

    $feebase  = [];
    $po   = [];
    $item .= "<tr><td>A34</td><td>Rasio Fee Base Income</td>";
    for($i = 1;$i<=12;$i++){
         $bulan = 'bulan_'.$i;

         $hasil = 0;
         if(!empty($rasiofee)){
            $feebase[$i] = $rasiofee[$rasio459][$bulan];
            $po[$i] = $rasiofee[$rasio41][$bulan]+$rasiofee[$rasio45][$bulan];
            $hasil = ($feebase[$i]/$po[$i])*100;
         }
        $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A34" data-value="'.$hasil.'">'.custom_format($hasil,false,2).'</div></td>'; 
        // $item .= "<td class='text-right'>".custom_format($hasil,false,2)."</td>";  
    }
    $item .= "</tr>";

    $item .= "<tr><td></td><td>- Fee Base Income</td>";
    for($i = 1;$i<=12;$i++){
        if(!empty($feebase)){
            $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A34.1" data-value="'.$feebase[$i].'">'.custom_format(view_report($feebase[$i])).'</div></td>'; 
            // $item .= "<td class='text-right'>".custom_format(view_report($feebase[$i]))."</td>";  
        }else{
            $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A34.1" data-value="0">0</div></td>';
            // $item .= "<td class='text-right'>0</td>";  
        }
        
    }
    $item .= "</tr>";

    $item .= "<tr><td></td><td>- Pendapatan Operasional</td>";
    for($i = 1;$i<=12;$i++){
          if(!empty($po)){
             $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A34.2" data-value="'.$po[$i].'">'.custom_format(view_report($po[$i])).'</div></td>'; 
            // $item .= "<td class='text-right'>".custom_format(view_report($po[$i]))."</td>";  
        }else{
            $item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;"'.$edit.' class="edit-value text-right edited" data-name="bulan_'.$i.'" data-id="A34.2" data-value="0">0</div></td>';
            // $item .= "<td class='text-right'>0</td>";  
        }
    }
    $item .= "</tr>";
        
    
    
    $item .="</center>";
    echo $item;

?>