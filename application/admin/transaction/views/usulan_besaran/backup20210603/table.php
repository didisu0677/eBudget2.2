<?php 
//debug($dtx_core2018);die;
	$hno = 0;
	foreach($grup[0] as $m0) { 
	$hno++;
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
	$no=0;
    ?>
		<tr>
			<th colspan="14" style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;"><font color="#fff"><?php echo KonDecRomawi($hno) .'. '. $m0->grup; ?></font></th>
		</tr>		

	$mtahun = '';
	$cetakno ='';	
	<?php foreach($produk[$m0->grup] as $m1) { 
			$mtahun = $m1->data_core;
			if($m1->sumber_data !=4 and $m1->sumber_data !=5 )	$no++;
						
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
		$id = 'id';
//		if($current_cabang == user('kode_cabang') && $akses_ubah == 1) {
//			$bgedit ="#ffbb33";
//			$contentedit ="true" ;
//			$id = 'id' ;
//		}

		?>
		<tr>

			<td><?php if($m1->sumber_data !=4 && $m1->sumber_data !=5) echo $no; ?></td>
			<td><?php echo $m1->keterangan; ?></td>
			
			<?php
			if($m1->sumber_data == 5) {
				$cr = 'dt_core' . ($m1->data_core-1);
				if(isset($$cr)){
					foreach ($$cr as $key => $value) {
						$R = 'R' . $key;
						$$R = 0;
						$$R = $value ;
					}
				}


				$cr1 = 'dt_core' . ($m1->data_core);

				if(isset($$cr1)){
					foreach ($$cr1 as $key => $value) {
						$R1 = 'R1' . $key;
						$$R1 = 0;
						$$R1 = $value ;
					}
				}
			}

			for ($i = 1; $i <= 12; $i++) { 

				$rbulan = '';
				$rtahun = '';
				$bgedit ="";
				$contentedit ="false" ;
				$id ="";
				foreach ($rencana as $r) {
					$rbulan = $r->bulan;
					$rtahun = $r->tahun;
					if($mtahun == $rtahun && $akses_ubah == 1 && $rbulan == $i && ($m1->sumber_data ==2 or $m1->sumber_data ==3 or $m1->sumber_data == 1 )){
						$bgedit ="#ffbb33";
						$contentedit ="true"; 
						$id = "id";					}
				}
				$v_field  = 'B_' . sprintf("%02d", $i);

				$v_field2 = 'RB_' .  sprintf("%02d", $i);
				$v_field3 = 'R1B_' .  sprintf("%02d", $i);
				
				$gr = 'GR' . sprintf("%02d", $i);
				$$gr = 0;

				switch ($m1->sumber_data) {
				  case 5:
				 // 	debug($$v_field3);die;
				    $$gr = ((($$v_field3) - $$v_field2) / $$v_field2) * 100 ;
				    break;
				  case 4:
				    $$gr = 50000;
				    break;
				  case 7:
				    $$gr = 200000;
				    break;  
				  default:
				    $field_v = custom_format(view_report($m1->$v_field));
				} 

			//	if($m1->sumber_data == 5) {
			//		$$gr = ((($$v_field3) - $$v_field2) / $$v_field2) * 100 ;
			//	}else{	
			//		$field_v = custom_format(view_report($m1->$v_field));
			//	}

				$field_v = custom_format(view_report($m1->$v_field));
				if($m1->sumber_data == 5 ){$field_v = custom_format($m1->$v_field,false,2);} 
				    
					echo '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="'.$contentedit.'" contenteditable="true" class="edit-value text-right" data-name="'.$v_field.'" data-id="'.$m1->id.'" data-value="'.$m1->$v_field.'">'.$field_v.'</div></td>';
			}
			?>

		</tr>
	<?php 
	$t_tahun = 0;
	} ?>

<?php } ?>
			
