<?php 
    $no = 0;
    foreach ($sub_item as $v => $u1) { $no++; ?>
        <tr>
            <td width="60"><?php echo $no;?></td>
            <td><?php echo $u1['nama'];?></td>

            <?php 
            $bgedit =bgEdit();
            $contentedit ="true"; 
            $id = "id";     
            $contentedit ="true" ;

            foreach ($detail_tahun as $d => $value) { 
                $vfield = 'P_'. sprintf("%02d", $value['bulan']);
                $j1 = 'JML_'.   $value['tahun'] . sprintf("%02d", $value['bulan']);
                $$j1 = 0;
                $jindex = 0;

                foreach ($list_gr as $kd => $vd) {             
                    if($value['tahun'] == $vd->tahun_core && $u1['sub_coa'] == $vd->coa){  
                        $$j1 = $vd->$vfield;
                        $jindex = $vd->index_kali ;
                    }
                }

                $jml = 0;
                foreach ($jml_akhir_rek as $j) {
                    if($u1['sub_coa'] == $j->coa){
                        $jml = $j->jumlah;
                    }
                }

           //     debug($jindex);die;

            echo '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="'.$contentedit.'" contenteditable="true" class="edit-value text-right" data-name="'.str_replace(' ', "_", $u1['nama']).'_'.$u1['sub_coa'].'-table3'.'-'.substr($j1,4).'-'.$u1['sub_coa'].'-'.$value['id_tahun_anggaran'].'-'.$cabang.'" data-id="'.$u1['id'].'" data-value="'.$$j1.'">'.custom_format($$j1).'</div></td>';                                             
            }

            echo '<td class="border-none"></td>' ;
            echo '<td class="edit-value text-right" data-name="'.str_replace(' ', "_", $u1['nama']).'_'.$u1['sub_coa'].'-table5'.'-'.substr($j1,4).'-'.$u1['sub_coa'].'-'.$value['id_tahun_anggaran'].'-'.$cabang.'" data-id="'.$u1['id'].'" data-value="'.$jml.'">'.custom_format($jml).'</td>' ;

            echo '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="'.$contentedit.'" contenteditable="true" class="edit-value text-right" data-name="'.str_replace(' ', "_", $u1['nama']).'_'.$u1['sub_coa'].'-table6'.'-'.substr($j1,4).'-'.$u1['sub_coa'].'-'.$value['id_tahun_anggaran'].'-'.$cabang.'" data-id="'.$u1['id'].'" data-value="'.$jindex.'">'.custom_format($jindex).'</div></td>';
     //       debug($jindex);die;
            ?>    
        </tr>
<?php } ?>

        <tr>
        <td width="60"></td>
        <td><font><b><?php echo 'JUMLAH';?></b></font></td>

        <?php foreach ($detail_tahun as $d => $value) {  
            $tot = 'total_'. $value['tahun'] . sprintf("%02d", $value['bulan']);
            $field = 'P_' . sprintf("%02d", $value['bulan']);
            $$tot = 0;
            foreach ($sum_rek as $vsum) {
              if($value['tahun'] == $vsum->tahun_core){
                  $$tot = $vsum->$field ;
              }
            }

            ?>
            <th class="text-right"><?php echo custom_format($$tot) ;?></th>
         <?php } ?>   
        </tr>
