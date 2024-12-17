
<tr>
	<th width="30" class="text-center align-middle"><?= lang('no') ?></th>
	<th class="mw-100 text-center align-middle"><?= lang('coa_description') ?></th>
	<th class="mw-150 text-center align-middle"><?= month_lang($bulan).' '.($tahun->tahun_anggaran-1) ?> <br>Real</th>
	<th class="mw-150 text-center align-middle"><?= month_lang(12).' '.($tahun->tahun_anggaran-1) ?><br>Real</th>
	<th class="mw-150 text-center align-middle">- <br>Real</th>
	<th class="mw-150 text-center align-middle"><?= month_lang($bulan).' '.($tahun->tahun_anggaran) ?> <br>Renc</th>
	<th class="mw-150 text-center align-middle"><?= month_lang($bulan).' '.($tahun->tahun_anggaran) ?> <br>Real</th>
	<th class="mw-150 text-center align-middle">Penc <br>(%)</th>
	<th class="mw-150 text-center align-middle">Pert <br>(%)</th>
</tr>
<?php 
	$no = 0;
    foreach ($coa as $v => $u) { $no++ ?>
        <?php 
            $tbul = 0;
            foreach ($dt_bulan as $b => $bu) {
                if($u->coa == $bu->glwnco) {
                    $tbul = $bu->total;
                }
            }

            $tdes = 0;
            foreach ($dt_des as $d => $du) {
                if($u->coa == $du->glwnco) {
                    $tdes = $du->total;
                }
            }

            $tdes = 0;
            foreach ($dt_des as $d => $du) {
                if($u->coa == $du->glwnco) {
                    $tdes = $du->total;
                }
            }

            $trenc_bul = 0;
            foreach ($dt_bul_renc as $r => $ru) {
                if($u->coa == $ru->glwnco) {
                    $trenc_bul = $ru->total;
                }
            }

            $treal_bul = 0;
            if(isset($dt_bulan_current )) {
                foreach ($dt_bulan_current as $real => $realu) {
                    if($u->coa == $realu->glwnco) {
                        $treal_bul = $realu->total;
                    }
                }
            }
        ?>


        <tr>
            <td><?php echo $no ;?></td>
            <td><?php echo $u->glwdes; ?> </td>
            <td class="text-right"><?php echo custom_format(view_report($tbul)) ?></td>
            <td class="text-right"><?php echo custom_format(view_report($tdes)) ?></td>
            <td class="text-right"></td>
            <td class="text-right"><?php echo custom_format(view_report($trenc_bul)) ?></td>
            <td class="text-right"><?php echo custom_format(view_report($treal_bul)) ?></td>
            <td class="text-right"></td>
            <td class="text-right"></td>
        </tr>
   <?php }
?>
