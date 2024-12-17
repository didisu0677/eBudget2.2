<?php 
	$no=0;
	foreach($grup[0] as $m0) { 
	
    $t_b01=0;
    $t_b02=0;
    $t_b03=0;
    $t_b04=0;
    $t_b05=0;
    $t_b06=0;
    $t_b07=0;
    $t_b08=0;
    $t_b09=0;
    $t_b10=0;
    $t_b11=0;
    $t_b12=0;
	$t_tahun=0;
	$t_d01=0;
	$tdpk_b02=0;
	$tdpk_b03=0;
	$tdpk_b04=0;
	$tdpk_b05=0;
	$tdpk_b06=0;
	$tdpk_b07=0;
	$tdpk_b08=0;
	$tdpk_b09=0;
	$tdpk_b10=0;
	$tdpk_b11=0;
	$tdpk_b12=0;

    ?>
	<?php foreach($produk[$m0->grup] as $m1) { $no++;	
    	$t_b01 += $m1->B_01;
    	$t_b02 += $m1->B_02;
    	$t_b03 += $m1->B_03;
    	$t_b04 += $m1->B_04;
    	$t_b05 += $m1->B_05;
    	$t_b06 += $m1->B_06;
    	$t_b07 += $m1->B_07;
    	$t_b08 += $m1->B_08;
    	$t_b08 += $m1->B_09;
    	$t_b10 += $m1->B_10;
    	$t_b11 += $m1->B_11;
    	$t_b12 += $m1->B_12;
		
	
    	$total = 0;
		for ($i = 1; $i <= 12; $i++) { 
    		$v_field  = 'B_' . sprintf("%02d", $i);
    		$t_tahun += $m1->$v_field;
			
			$_fieldtotal = 't_b' . sprintf("%02d", $i); 

    		$total += $$_fieldtotal;
		}


		?>
		<tr>
			<td><?php echo $no; ?></td>
			<td><?php echo $m1->keterangan; ?></td>
			
			<?php
			foreach ($bulan as $b) {
				if($b->singkatan == 'Real') {
					echo '<td></td>';
				}else{
					$v_field  = 'B_' . sprintf("%02d", $b->bulan);
					echo '<td style="background: #ffbb33;"><div style="background: #ffbb33;" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="true" contenteditable="true" class="edit-value text-right" data-name="'.$v_field.'" data-id="'.$m1->id.'" data-produk ="'.$m1->kode_produk.'" data-value="'.number_format($m1->$v_field).'">'.number_format($m1->$v_field).'</div></td>';
				}			
			}
			?>

			<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($t_tahun); ?></th>
			
			<td class="button">
				<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="<?php echo $m1->id; ?>" title="<?php echo lang('realisasi'); ?>"><i class="fa-edit"></i></button>
				<!--
				<button type="button" class="btn btn-danger btn-delete" data-key="delete" data-id="<?php echo $m1->id; ?>" title="<?php echo lang('hapus'); ?>"><i class="fa-trash-alt"></i></button>
				!-->
			</td>			
		</tr>
	<?php 
	$t_tahun = 0;
	} ?>

		<tr>
			<th colspan="2" style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;">TOTAL <?php echo $m0->grup; ?></th>

			<?php
			foreach ($bulan as $b) {
				if($b->singkatan == 'Real') {
					echo '<th class="text-right" class="text-right" style=" background: #757575; min-height: 10px; width: 50px; overflow: hidden;"></th>';
				}else{
					$v_total  = 't_b' . sprintf("%02d", $b->bulan);
					echo '<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;">'.custom_format($$v_total).'</th>';
				}			
			}
			?>


			<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;"><?php echo custom_format($total); ?></th>		
		</tr>	

<?php } ?>
		
		<?php
		foreach ($total_grup as $t) {
			switch ($t->grup) {
			  case 'DPK':
				for ($i = 1; $i <= 12; $i++) { 
		    		$v_field  = 'B_' . sprintf("%02d", $i);
		    		$DPK = 'DPK' . sprintf("%02d", $i);
		    		$$DPK = $t->$v_field;
    				$totaldpk += $t->$v_field;
				}

			    break;
			  case 'KRD N.K':
				for ($i = 1; $i <= 12; $i++) { 
		    		$v_field  = 'B_' . sprintf("%02d", $i);
		    		$KRD = 'KRD' . sprintf("%02d", $i);
		    		$$KRD = $t->$v_field;
		    		$totalkrd += $t->$v_field;
				}

				break;
			} 
		}
		echo '<tr>';
			echo '<th colspan="2" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;">LABA</th>';	
			echo '<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;"></th>';
			echo '<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;">'.number_format($DPK01-$KRD01).'</th>';
			echo '<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;">'.number_format($DPK02-$KRD02).'</th>';
			echo '<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;">'.number_format($DPK03-$KRD03).'</th>';
			echo '<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;">'.number_format($DPK04-$KRD04).'</th>';
			echo '<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;">'.number_format($DPK05-$KRD05).'</th>';
			echo '<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;">'.number_format($DPK06-$KRD06).'</th>';
			echo '<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;">'.number_format($DPK07-$KRD07).'</th>';
			echo '<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;">'.number_format($DPK08-$KRD08).'</th>';
			echo '<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;">'.number_format($DPK09-$KRD09).'</th>';
			echo '<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;">'.number_format($DPK10-$KRD10).'</th>';
			echo '<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;">'.number_format($DPK11-$KRD11).'</th>';
			echo '<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;">'.number_format($DPK12-$KRD12).'</th>';
			echo '<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;">'.number_format($totaldpk-$totalkrd).'</th>';
		echo '</tr>';	

		?>	

			
