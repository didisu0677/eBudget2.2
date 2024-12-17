<?php 

    foreach ($grup as $key => $value) {
    $no =0;
    $jm =0;
    $jm2 = 0;
    $jm3 = 0;
    $jm4 = 0;
    foreach ($item_ba as $v => $u) { $no ++;
        if ($u['grup'] == $key ) { ?>
        <tr>
            <td width="60"><?php echo $no ;?></td>
            <td><?php echo $u['nama'] ;?></td>
            <?php
                foreach ($v_02 as $k => $v) {
                    if($u['coa'] == $v['glwnco']) {
                        echo '<td class="text-right">'.custom_format(view_report($v['C_akhir'])).'</td>';
                        $jm += $v['C_akhir'];
                    }
                }

            ?>    

            <td></td>
            <?php
                foreach ($v_01 as $k => $v) {
                    if($u['coa'] == $v['glwnco']) {
                        echo '<td class="text-right">'.custom_format(view_report($v['C_01akhir'])).'</td>';
                        $jm2 += $v['C_01akhir'];
                    }
                }
            ?>


            <td class="text-right"></td>
            <td class="text-right"></td>
            <?php
                foreach ($v_01 as $k => $v) {
                    if($u['coa'] == $v['glwnco']) {
                        echo '<td class="text-right">'.custom_format(view_report($v['C_akhir'])).'</td>';
                        $jm3 += $v['C_02akhir'];

                    }
                }
            ?>    
            <td class="text-right"></td>
            <td class="text-right"></td>
            <?php
                foreach ($ba_sum as $k => $v) {
                    if($u['coa'] == $v['coa']) {
                        echo '<td class="text-right">'.custom_format(view_report($v['P_12'])).'</td>';
                        $jm4 += $v['P_12'];

                    }
                }
            ?>  

            <td class="text-right"></td>                        
        </tr>
<?php        
    }
    ?>
    <?php    
    }   
    if($key != "") {
    ?> 
        <tr>
            <th></th>
            <th colspan="1">TOTAL  <?php echo $key ;?></th>
            <th class="text-right"><?php echo custom_format(view_report($jm));?></th>  
            <th></th>  
            <th class="text-right"><?php echo custom_format(view_report($jm2));?></th>    
            <td></td>    
            <td></td>    
            <th class="text-right"><?php echo custom_format(view_report($jm3));?></th>    
            <td></td>    
            <td></td>    
            <th class="text-right"><?php echo custom_format(view_report($jm4));?></th>  
            <td></td>    
        </tr>
    <?php    
    }
}    
?>        