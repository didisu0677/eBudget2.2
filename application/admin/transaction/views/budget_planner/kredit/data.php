<?php 
    $no =0;
    foreach ($item_ba as $v => $u) { $no ++;
        $v = '';
        $j0 = '';
        $j1 = '';
        for ($i = 1; $i <= 12; $i++) { 
            $v = 'P_'. sprintf("%02d", $i);
            $j0 = 'JM0_'. sprintf("%02d", $i);
            $j1 = 'JM1_'. sprintf("%02d", $i);
            if($no==1){
                $$j0 = 0;
                $$j0  = $u[$v];
            }else{
                $$j1 = 0;
                $$j1  = $u[$v];                 
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
    }

        $j0 = '';
        $j1 = '';
        $p  = '';

        for ($i = 1; $i <= 12; $i++) { 
            $j0 = 'JM0_'. sprintf("%02d", $i);
            $j1 = 'JM1_'. sprintf("%02d", $i);
            $p  = 'pert_'. sprintf("%02d", $i);
            $$p  = 0;
            if(isset($$j0) && $$j0 != 0) {
                $$p = (($$j1 - $$j0) / $$j0) * 100;
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