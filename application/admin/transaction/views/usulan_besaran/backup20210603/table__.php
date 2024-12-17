<?php 
	$no=0;
	foreach($grup[0] as $m0) { 
	
	$v = '';
	$vt = '';
	for ($i = 1; $i <= 12; $i++) { 
		$v = 't_'. 'b'.sprintf("%02d", $i);
		$vt = 'tdpk' . 'b'.sprintf("%02d", $i);
		$$v = 0;
		$$vt = 0;
	}	


	$t_tahun=0;
	$t_d01=0;

	$total =0;
    ?>
	<?php foreach($produk[$m0->grup] as $m1) { $no++;	
			
			$B = '';
			for ($i = 1; $i <= 12; $i++) { 
				$v = 't_'. 'b'.sprintf("%02d", $i);
				$B = 'B_'. sprintf("%02d", $i);
				$$v += $m1->$B;
			}
				
	
	
    	$total = 0;
		for ($i = 1; $i <= 12; $i++) { 
    		$v_field  = 'B_' . sprintf("%02d", $i);
    		$t_tahun += $m1->$v_field;
			
			$_fieldtotal = 't_b' . sprintf("%02d", $i); 

    		$total += $$_fieldtotal;
		}


		$bgedit ="";
		$contentedit ="false" ;
		$id = 'keterangan';
		if($current_cabang == user('kode_cabang') && $akses_ubah == 1) {
			$bgedit ="#ffbb33";
			$contentedit ="true" ;
			$id = 'id' ;
		}

		?>
		<tr>
			<td><?php echo $no; ?></td>
			<td><?php echo $m1->keterangan; ?></td>
			
			<?php
			$real = 0;
			foreach ($bulan as $b) {

				$vreal = 'data' . $b->tahun . sprintf("%02d", $b->bulan);

				switch ($b->singkatan) {
				  case 'Real':
				  	foreach ($$vreal as $k) {
				    	if($k->glwnco == $m1->coa) $real = $k->total; 
				    }
				    echo '<td class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;"><FONT COLOR="#ffffff">'.custom_format(view_report($real)).'</td>';
				    break;
				  case 'Est.':
				    echo '<td></td>';
				    break;
				  case 'Renc':
				    $v_field  = 'B_' . sprintf("%02d", $b->bulan);
					echo '<td style="background: '.$bgedit.';"><div style="background: '.$bgedit.';" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="'.$contentedit.'" contenteditable="true" class="edit-value text-right" data-name="'.$v_field.'" data-id="'.$m1->$id.'" data-value="'.custom_format($m1->$v_field).'">'.custom_format(view_report($m1->$v_field)).'</div></td>';
				    break;
				} 			
			}
			?>

		</tr>
	<?php 
	$t_tahun = 0;
	} ?>

		<tr>
			<th colspan="2" style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;"><font color="#fff">TOTAL <?php echo $m0->grup; ?></th>

			<?php
			foreach ($bulan as $b) {

				switch ($b->singkatan) {
				  case 'Real':
				  	echo '<th class="text-right" class="text-right" style=" background: #757575; min-height: 10px; width: 50px; overflow: hidden;"></th>';
				    break;
				  case 'Est.':
				    echo '<th class="text-right" class="text-right" style=" background: #757575; min-height: 10px; width: 50px; overflow: hidden;"></th>';
				    break;
				  case 'Renc':
				  	$v_total  = 't_b' . sprintf("%02d", $b->bulan);
					echo '<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;"><font color="#fff">'.custom_format(view_report($$v_total)).'</th>';
					break;
				}					  	
			}
			?>

		</tr>	

<?php } ?>
			
