<?php 
    $no =0;
    foreach ($item_ba2 as $v => $u) { $no ++; ?>
        <tr>
            <td width="60"><?php echo $no ;?></td>
            <td><?php echo $u['keterangan'] ;?></td>
            <td class="text-right"></td>
            <?php 
            $field = '';
            
            $v = '';
            $v1 = '';
            $r0 = '';
            $r1 = '';

            for ($i = 1; $i <= 12; $i++) {  
                $field = 'P_'. sprintf("%02d", $i);
                
                $v = 'C_'. sprintf("%02d", $i);
                $v_akhir = 'C_'. sprintf("%02d", $bulan_terakhir);
                $v1 = 'P_'. sprintf("%02d", $i);

                
                $r0 = 'REAL0_' . sprintf("%02d", $i);
                $r1 = 'REAL1_' . sprintf("%02d", $i);

                $r01 = 'REAL01_' . sprintf("%02d", $i);
                $r00 = 'REAL00_' . sprintf("%02d", $i);

               if($no==1){
                    $$r0 = 0;
                    $$r00 = 0;
                    $$r01 = 0;
                    $$r0 = $u[$v1]; 
                    $$r01 = $u[$v1]; 
                    $$r00  = $item_ba0[0][$v1];
                }else{

                    $$r1 = 0;                 
                    $$r1 = $u[$v1];
                }
                echo '<td class="text-right">'.custom_format(view_report($u[$field])).'</td>';
            }?>                        
        </tr>
     <?php        

        foreach ($sub_item as $v => $u1) { ?>
        <?php if($u1['tahun_core']==$u['tahun_core'] && $u1['grup_coa'] == '2100000') {  ?> 
        <tr>
            <td width="60"></td>
            <td><?php echo $u1['account_name'];?></td>                
            

            <?php
                $bgedit ="";
                $contentedit ="false"; 
                $id = "id";     
                $contentedit ="false" ; 
                $stedit = ""; 
                if($access_edit && $u1['tahun_core'] == $anggaran->tahun_anggaran):
                    $bgedit =bgEdit();
                    $contentedit    ="true"; 
                    $id             = "id";     
                    $contentedit    = "true" ; 
                endif;
            ?>
            <td style="background: <?= $bgedit ?>" data-k="<?= $v ?>"><div style="background:<?= $bgedit ?>" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="<?= $contentedit ?>" class="edit-value text-right <?= $stedit ?>" data-name="<?= 'TOT_'.$cabang ?>" data-id="<?= $u1['coa'].'-rate' ?>" data-value="<?= $u1['rate'] ?>"><?= custom_format($u1['rate'],false,2) ?></div></td>'; 
            
            <?php for ($i = 1; $i <= 12; $i++) { 

                $s_J = 'JM_' . sprintf("%02d", $i);


                $vfield = '';
                $j1 = '';
                for ($i = 1; $i <= 12; $i++) {  
                    $vfield = 'C_'. sprintf("%02d", $i);
                    $j1 = 'JML_'.   sprintf("%02d", $i);
                    $$j1 = 0;

                    if($u1['coa'] == '2101012'){ 
                        $ks_v = 'kasda' .$u1['tahun_core'];

                        if(isset($$ks_v)) {
                            foreach ($$ks_v as $key => $value) {
                                $$j1 = $value[$vfield]; 
                            }
                        }
                    }

                    if($u1['coa'] == '2101011'){ 
                        $ks_v = 'nonkasda' .$u1['tahun_core'];
                         if(isset($$ks_v)) {
                            foreach ($$ks_v as $key => $value) {
                                $$j1 = $value[$vfield]; 
                            }
                        }    
                    }
                    
                    $stedit ='';
                    $status_u1 = true;
                    foreach ($detail_tahun as $key => $value) {
                        if($value['sumber_data'] ==2){
                            $vfield = 'P_'. sprintf("%02d", $i);
                            $j2 = 'JML1_'.   $value['tahun'] . sprintf("%02d", $i);
                        
                            $$j2 = $u1[$vfield];
                        }

                        if($u1['tahun_core'] == $value['tahun'] && $value['sumber_data'] == 3 && $u1['coa'] == '2101011'){
                            $status_u1 = false;
                        }

                        $bgedit ="";
                        $contentedit ="false"; 
                        $id = "id";     
                        $contentedit ="false" ; 
                        $stedit = ""; 

                        if($u1['tahun_core'] == $value['tahun'] && $value['sumber_data'] == 3 && $u1['coa'] == '2101012'){
                            $status_u1 = false;
                            $vfield = 'P_'. sprintf("%02d", $i);
                            $j2 = 'JML1_'.   $value['tahun'] . sprintf("%02d", $i);
                            $$j2 = $u1[$vfield];

                            $bgedit =bgEdit();
                            $contentedit ="true"; 
                            $id = "id";     
                            $contentedit ="true" ; 
                            $stedit = "edited" ;
                        }


                    }


                    if(!$status_u1) {
                        $$j1 = $$j2 ;
                    }


            //    debug($bgedit);die;
                echo '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="'.$contentedit.'" contenteditable="true" class="edit-value text-right '.$stedit.'" data-name="'.$u1['coa'].'-table2'.'-'.substr($j2,5).'-'.$u1['coa'].'-'.$value['id_tahun_anggaran'].'-'.$cabang.'" data-id="'.$u['id'].'" data-value="'.$$j1.'">'.custom_format(view_report($$j1)).'</div></td>'; 
                }         


            } ?>

        </tr>
    <?php }

 }
 ?>
 <?php if($no==2) { 
        foreach ($sub_item as $si2 => $v_si2) {
            if($v_si2['grup_coa'] == '2101011' && $v_si2['tahun_core'] == user('tahun_anggaran')) {
            ?>         
                <tr>
                    <td width="60"></td>
                    <td class="sub-1"><?php echo $v_si2['account_name'];?></td>
                    <td class="text-right"></td>
                    <?php
                    if($v_si2['coa'] == '2101011|B') {   
                        $bgedit =bgEdit();
                        $contentedit ="true"; 
                        $id = "id";     
                        $contentedit ="true" ; 
                        $stedit = "edited" ;
                    }
                        
                    ?>


                    <?php for ($i = 1; $i <= 12; $i++) { 

                        $vfield = 'P_'. sprintf("%02d", $i);
                        $j2 = 'JML1_'.   $value['tahun'] . sprintf("%02d", $i);
                        $$j2 = 0;
                        $$j2 = $v_si2[$vfield];

                    echo '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="'.$contentedit.'" contenteditable="true" class="edit-value text-right '.$stedit.'" data-name="'.$v_si2['coa'].'-table2'.'-'.substr($j2,5).'-'.$v_si2['coa'].'-'.$value['id_tahun_anggaran'].'-'.$cabang.'" data-id="'.$u['id'].'" data-value="'.$$j2.'">'.custom_format(view_report($$j2)).'</div></td>'; 
                    }?>                                     
                </tr>
<?php   }}}  
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
            $p ='';
            <?php 
            for ($i = 1; $i <= 12; $i++) { 
               $p = 'pert1_' . sprintf("%02d", $i);
                echo '<td class="text-right">'.custom_format($$p,false,2).'</td>';
            }    
            ?>                  
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
            $p ='';
            <?php 
            for ($i = 1; $i <= 12; $i++) { 
               $p = 'pert_' . sprintf("%02d", $i);
                echo '<td class="text-right">'.custom_format($$p,false,2).'</td>';
            }    
            ?>  
                              
        </tr>
    <?php }?>
       
<?php 
    }    
 