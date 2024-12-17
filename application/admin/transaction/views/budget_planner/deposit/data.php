<?php 
    $no =0;
    foreach ($item_ba as $v => $u) { $no ++;?>
        <tr>
            <td width="60"><?php echo $no ;?></td>
            <td><?php echo $u['keterangan'] ;?></td>
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
      