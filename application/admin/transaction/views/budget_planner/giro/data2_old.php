<?php 
    $no =0;
    foreach ($item_ba2 as $v => $u) { $no ++; ?>
        <?php  
            $v = '';
            $v1 = '';
            $j = '';
            $j1 = '';
            $r0 = '';
            $r1 = '';
            for ($i = 1; $i <= 12; $i++) { 
                $v = 'C_'. sprintf("%02d", $i);
                $v_akhir = 'C_'. sprintf("%02d", $bulan_terakhir);
                $v1 = 'P_'. sprintf("%02d", $i);
                $j = 'JM_'. sprintf("%02d", $i);
                $j1 = 'JM1_'. sprintf("%02d", $i);
                $$j = 0;
                $$j1 = 0;
                
                $r0 = 'REAL0_' . sprintf("%02d", $i);
                $r1 = 'REAL1_' . sprintf("%02d", $i);

                $r01 = 'REAL01_' . sprintf("%02d", $i);
                $r00 = 'REAL00_' . sprintf("%02d", $i);
                if($no==1){
                    $$j  = $kasda[$v];
                    $$j1 = $nonkasda[$v];

                    if($i >= ($bulan_terakhir + 1)) {
                        if($ks0[$v_akhir] != 0) $$j = ($ks0[$v] / $ks0[$v_akhir]) * $ks1[$v_akhir] ;

                        if($ksnon0[$v_akhir] != 0) $$j1 = ($ksnon0[$v] / $ksnon0[$v_akhir]) * $ksnon1[$v_akhir] ;
                    }


                    $$r0 = 0;
                    $$r00 = 0;
                    $$r01 = 0;
                    $$r0 = $u[$v1]; 
                    $$r01 = $u[$v1]; 
                    $$r00  = $item_ba0[0][$v1];
                }else{
                    $$j  = 0;
                    $$j1 = 0;

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
        foreach ($sub_item as $v => $u1) { ?>
        <?php if($u1['id'] == 1) {  ?> 
        <tr>
            <td width="60"></td>
            <td><?php echo $u1['nama'];?></td>                
            <td class="text-right"><?php echo custom_format($u1['rate'],false,2);?></td>
            
            <?php for ($i = 1; $i <= 12; $i++) { 

                $s_J = 'JM_' . sprintf("%02d", $i);
                if($no==2){
                    $bgedit ="#ffbb33";
                    $contentedit ="true"; 
                    $id = "id";     
                    $contentedit ="true" ;
                }else{
                    $bgedit ="";
                    $contentedit ="false"; 
                    $id = "id";     
                    $contentedit ="false" ;
                }

                $vfield = 'P_'. sprintf("%02d", $i);
                $j1 = 'JML_'.   sprintf("%02d", $i);
                $$j1 = 0;
                foreach ($list_tab as $kd => $vd) {                 
                    if($value['tahun'] == $vd->tahun_core && $v->coa == $vd->coa){      
                        $$j1 = $vd->$vfield;
                        if($v->coa =='412' && $value['tahun'] == user('tahun_anggaran')) {
                            $$j1 = $jml_plantab[$vfield] - $jml_nonbima[$vfield];
                        }
                    }
                }

                echo '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="'.$contentedit.'" contenteditable="true" class="edit-value text-right" data-name="'.$u1['sub_coa'].'|table2'.'|'.substr($j1,4).'|'.$u1['coa'].'|'.$value['id_tahun_anggaran'].'|'.$cabang.'" data-id="'.$u['id'].'" data-value=""></div></td>';          
            } ?>

        </tr>
    <?php }else{ ?>
         <tr>
            <td width="60"></td>
            <td><?php echo $u1['nama'];?></td>
            <td class="text-right"><?php echo custom_format($u1['rate'],false,2);?></td>
            <td class="text-right"><?php echo custom_format(view_report($JM1_01));?></td>
            <td class="text-right"><?php echo custom_format(view_report($JM1_02));?></td>
            <td class="text-right"><?php echo custom_format(view_report($JM1_03));?></td>
            <td class="text-right"><?php echo custom_format(view_report($JM1_04));?></td>
            <td class="text-right"><?php echo custom_format(view_report($JM1_05));?></td>
            <td class="text-right"><?php echo custom_format(view_report($JM1_06));?></td>
            <td class="text-right"><?php echo custom_format(view_report($JM1_07));?></td>
            <td class="text-right"><?php echo custom_format(view_report($JM1_08));?></td>
            <td class="text-right"><?php echo custom_format(view_report($JM1_09));?></td>   
            <td class="text-right"><?php echo custom_format(view_report($JM1_10));?></td> 
            <td class="text-right"><?php echo custom_format(view_report($JM1_11));?></td>   
            <td class="text-right"><?php echo custom_format(view_report($JM1_12));?></td>                                              
        </tr>       

    <?php } ?>

<?php } 
 ?>
 <?php if($no==2) { ?> 
        <tr>
            <td width="60"></td>
            <td class="sub-1"><?php echo 'Korporasi';?></td>
            <td class="text-right"></td>
            <?php
                $bgedit ="";
                $contentedit ="false"; 
                $id = "id";     
                $contentedit ="false" ;
            ?>
            <?php for ($i = 1; $i <= 12; $i++) { 
                echo '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="'.$contentedit.'" contenteditable="true" class="edit-value text-right" data-name="" data-value=""></div></td>';  
            }?>                                     
        </tr>
        <tr>
            <td width="60"></td>
            <td class="sub-1"><?php echo 'Retail';?></td>
            <td class="text-right"></td>
            <?php
                $bgedit ="#ffbb33";
                $contentedit ="true"; 
                $id = "id";     
                $contentedit ="true" ;
            ?>
            <?php for ($i = 1; $i <= 12; $i++) { 
                echo '<td style="background: '.$bgedit.'"><div style="background:#ffbb33" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="'.$contentedit.'" contenteditable="true" class="edit-value text-right" data-name="" data-value=""></div></td>';  
            } ?>                                             
        </tr>        
<?php }; 
?>      

        <?php if($no==1) { 
        $r00 = '';
        $r01 = '';
        $p1  = '';

        for ($i = 1; $i <= 12; $i++) { 
            $r00 = 'REAL00_'. sprintf("%02d", $i);
            $r01 = 'REAL01_'. sprintf("%02d", $i);
            $p1  = 'pert1_'. sprintf("%02d", $i);
            $$p1  = 0;
            if($$r00 != 0) {
                $$p1 = (($$r01 - $$r00) / $$r00) * 100;

            }
        } 

        ?>  
        <tr>
            <td width="60"></td>
            <td><?php echo 'Pert ' .  $u['tahun_core'] . ' (Total Giro)';?></td>
            <td class="text-right"></td>
            <td class="text-right"><?php echo custom_format($pert1_01,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert1_02,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert1_03,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert1_04,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert1_05,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert1_06,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert1_07,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert1_08,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert1_09,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert1_10,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert1_11,false,2);?></td>
            <td class="text-right"><?php echo custom_format($pert1_12,false,2);?></td>
                              
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
            <td><?php echo 'Pert ' .  $u['tahun_core'] . ' (Total Giro)';?></td>
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
    <?php }?>
       
<?php 
    }    
 