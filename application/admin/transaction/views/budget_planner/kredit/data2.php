<?php 
    $no =0;
    foreach ($item_ba2 as $v => $u) { $no ++;

            $r0 = '';
            $r1 = '';
            $r00 = '';
            $r01 = '';            
            for ($i = 1; $i <= 12; $i++) { 
                
                $v1 = 'P_'. sprintf("%02d", $i);
                $r0 = 'REAL0_' . sprintf("%02d", $i);
                $r1 = 'REAL1_' . sprintf("%02d", $i);

                $r00 = 'REAL00_' . sprintf("%02d", $i);
                $r01 = 'REAL01_' . sprintf("%02d", $i);
                $$r00 = 0;
                $$r01 = 0;
                if($no==1){
                    $$r0 = 0;
                    $$r0 = $u[$v1]; 
                    $$r01 = $u[$v1];
                    $$r00 = $item_ba0[0][$v1];
                }else{
                    $$r1 = 0;                 
                    $$r1 = $u[$v1];
                }
            }        
        ?>
        <tr>
            <td width="60"><?php echo $no ;?></td>
            <td><?php echo $u['keterangan'] ;?></td>
            <td class="text-right"></td>
            <td class="text-right"><?php echo custom_format(view_report($u['P_01'])) ;?></td>
            <td class="text-right"><?php echo custom_format(view_report($u['P_02'])) ;?></td>
            <td class="text-right"><?php echo custom_format(view_report($u['P_03'])) ;?></td>
            <td class="text-right"><?php echo custom_format(view_report($u['P_04'])) ;?></td>
            <td class="text-right"><?php echo custom_format(view_report($u['P_05'])) ;?></td>
            <td class="text-right"><?php echo custom_format(view_report($u['P_06'])) ;?></td>
            <td class="text-right"><?php echo custom_format(view_report($u['P_07'])) ;?></td>
            <td class="text-right"><?php echo custom_format(view_report($u['P_08'])) ;?></td>
            <td class="text-right"><?php echo custom_format(view_report($u['P_09'])) ;?></td>   
            <td class="text-right"><?php echo custom_format(view_report($u['P_10'])) ;?></td>
            <td class="text-right"><?php echo custom_format(view_report($u['P_11'])) ;?></td>   
            <td class="text-right"><?php echo custom_format(view_report($u['P_12'])) ;?></td>                        
        </tr>
<?php        
        foreach ($sub_item as $v => $u1) {

                foreach ($hsl_core as $h => $hsl) {                  
                    if($u1['sub_coa'] == $hsl['glwnco']){     
                        $vfield = '';
                        $j1 = '';
                        for ($i = 1; $i <= 12; $i++) {
                            $vfield = 'C_'. sprintf("%02d", $i);
                            $j1 = 'hasil_'. $i ;
                            if($no == 1){
                                $$j1 = 0;
                                $$j1 = $hsl[$vfield] * -1;
                            }
                        }                        
                    }
                } 

         
                foreach ($hsl_sub as $h => $hsl) {                  
                    if($u1['sub_coa'] == $hsl['coa']){     
                        $vfield = '';
                        $j1 = '';
                        for ($i = 1; $i <= 12; $i++) {
                            $vfield = 'hasil'. $i;
                            $j1 = 'hasil_'. $i ;
                            if($no == 2){
                                $$j1 = 0;
                                $$j1 = $hsl[$vfield];
                            }
                        }                        
                    }
                }

                foreach ($hsl_sub0 as $h0 => $hsl0) {                  
                    if($u1['sub_coa'] == $hsl0['coa']){     
                        $vfield = '';
                        $j1 = '';

                        foreach ($bln_anggaran as $d) { 
                            if($d->sumber_data == 2) {
                                $vfield = 'hasil'. $d->bulan;
                                $j1 = 'hasil_'. $d->bulan ;
                                if($no == 1){
                                    $$j1 = 0;
                                    $$j1 = $hsl0[$vfield];
                                 }
                            }
                        }
                 
                    }
                }

            ?>
        <tr>
            <td width="60"></td>
            <td><?php echo $u1['nama'];?></td>
            <td class="text-right"><?php echo custom_format($u1['rate'],false,2);?></td>

            <?php 
            $j2 = '';
            for ($i = 1; $i <= 12; $i++) { 
                $j2 = 'hasil_'. $i ;
                echo '<td class="text-right">'.custom_format(view_report($$j2)).'</td>';
            } ?>                                     
        </tr>
<?php }  
//die;
?>
        <?php if($no==1) { 

            $r00 = '';
            $r01 = '';
            $p  = '';

            for ($i = 1; $i <= 12; $i++) { 
                $r00 = 'REAL00_'. sprintf("%02d", $i);
                $r01 = 'REAL01_'. sprintf("%02d", $i);
                $p01  = 'pert_'. sprintf("%02d", $i);
                $$p01  = 0;
                if($$r00 != 0) {
                    $$p01 = (($$r01 - $$r00) / $$r00) * 100;
                }
            }  
            ?>  
        <tr>
            <td width="60"></td>
            <td><?php echo 'Pert ' . $u['tahun_core'] . ' (Total Kredit)';?></td>
            <td class="text-right"></td>
            <td class="text-right"><?php echo custom_format($pert_01,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_02,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_03,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_04,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_05,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_06,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_07,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_08,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_09,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_10,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_11,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_12,false,2);?></td>                               
        </tr>
    <?php }else{

        $r0 = '';
        $r1 = '';
        $p  = '';

        for ($i = 1; $i <= 12; $i++) { 
            $r0 = 'REAL0_'. sprintf("%02d", $i);
            $r1 = 'REAL1_'. sprintf("%02d", $i);
            $p  = 'pert_'. sprintf("%02d", $i);
            $$p  = 0;
            if($$r0 != 0) {
                $$p = (($$r1 - $$r0) / $$r0) * 100;
            }
        }  
        ?>
        <tr>
            <td width="60"></td>
            <td><?php echo 'Pert ' . $u['tahun_core'] . ' (Total Kredit)';?></td>
            <td class="text-right"></td>
            <td class="text-right"><?php echo custom_format($pert_01,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_02,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_03,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_04,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_05,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_06,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_07,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_08,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_09,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_10,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_11,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert_12,false,2);?></td>
                              
        </tr>
    <?php }
    }    

     

 