<?php 
	$hno = 0;
	$t_tahun=0;
	$t_d01=0;

	$total =0;
	$no=0;
    ?>

	<?php foreach($produk as $m1) { 
			
		if($m1->sumber_data !=4 and $m1->sumber_data !=5 )	$no++;

		$bgedit ="";
		$contentedit ="false" ;
		$id = 'id';
	//	$cr = 'core' . $m1->data_core-1;

		?>
		<tr>
			<?php
				$keterangan ='';

				switch ($m1->sumber_data) {
				  case 1:
				    $keterangan = 'DANA PIHAK KETIGA ' . $m1->data_core;
				    break;
				  case 4:
					$keterangan = 'Pencapaian ' .' (%)';
				    break;
				  case 5:
				    $keterangan = 'Pert ' . $m1->data_core .' (%)';
				    break;
				  default:
    				$keterangan = 'DANA PIHAK KETIGA ' . $m1->data_core .' (Renc.)';
				} 

			?>	
			<td><?php if($m1->sumber_data !=4 && $m1->sumber_data !=5) echo $no; ?></td>
			<td><?php echo $keterangan; ?></td>
			
			<?php

			if($m1->sumber_data == 5) {
				$cr = 'dpk_core' . ($m1->data_core-1);

				if(isset($$cr)){
					foreach ($$cr as $key => $value) {
						$R = 'R' . $key;
						$$R = 0;
						$$R = $value ;
					}
				}


				$cr1 = 'dpk_core' . ($m1->data_core);

				if(isset($$cr1)){
					foreach ($$cr1 as $key => $value) {
						$R1 = 'R1' . $key;
						$$R1 = 0;
						$$R1 = $value ;
					}
				}
			}

			$field_v = 0;
			for ($i = 1; $i <= 12; $i++) { 
				$v_field  = 'B_' . sprintf("%02d", $i);
				$v_field2 = 'RB_' .  sprintf("%02d", $i);
				$v_field3 = 'R1B_' .  sprintf("%02d", $i);
				
				$gr = 'GR' . sprintf("%02d", $i);
				$$gr = 0;


				if($m1->sumber_data == 5) {
					$$gr = ((($$v_field3) - $$v_field2) / $$v_field2) * 100 ;
				}else{	
					$field_v = custom_format(view_report($m1->$v_field));
				}

				if($m1->sumber_data == 5 || $m1->sumber_data == 4){$field_v = custom_format($$gr,false,2);} 
							    
					echo '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="'.$contentedit.'" contenteditable="true" class="edit-value text-right" data-name="" data-id="'.$m1->id.'" data-value=""'.$m1->$v_field.'"">'.$field_v.'</div></td>';
			}
			?>

		</tr>
	<?php 
	$t_tahun = 0;
	} ?>

			
