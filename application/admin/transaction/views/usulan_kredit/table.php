<?php 
	foreach($grup[0] as $m0) { 

	$no=0;
	$total_T01 = 0;
	$total_T02 = 0;
	$total_T03 = 0;
	$total_T04 = 0;
	$total_T05 = 0;
	$total_T06 = 0;
	$total_T07 = 0;
	$total_T08 = 0;
	$total_T09 = 0;
	$total_T10 = 0;
	$total_T11 = 0;
	$total_T12 = 0;	
    ?>
	<tr>
		<td></td>
		<td><?php echo $m0->keterangan; ?></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<?php foreach($produk[$m0->keterangan] as $m1) { 
		$no++;

		$bgedit ="";
		$contentedit ="false" ;
		$id = 'keterangan';
		if($current_cabang == user('kode_cabang') && $akses_ubah == 1) {
			$bgedit ="#ffbb33";
			$contentedit ="true" ;
			$id = 'id' ;
		}
	
		$total = 0;
		for ($i = 1; $i <= 12; $i++) { 
    		$v_field  = 'T_' . sprintf("%02d", $i);
    		$t_tahun += $m1->$v_field;
			
			$_fieldtotal = 'total_T' . sprintf("%02d", $i); 

    		$$_fieldtotal += $m1->$v_field;
		}
		?> 
		<tr>
			<td><?php echo $no; ?></td>
			<td><?php echo $m1->keterangan; ?></td>

			<td style="background: <?php echo $bgedit; ?>"><div style="background: <?php echo $bgedit; ?>" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="<?php echo $contentedit; ?>" class="edit-value text-right" data-name="T_01" data-id="<?php echo $m1->id; ?>" data-value="'.number_format($m1->T_01).'"><?php echo number_format($m1->T_01); ?></div></td>

			<td style="background: <?php echo $bgedit; ?>"><div style="background: <?php echo $bgedit; ?>" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="<?php echo $contentedit; ?>" class="edit-value text-right" data-name="T_02" data-id="<?php echo $m1->id; ?>" data-value="'.number_format($m1->T_02).'"><?php echo number_format($m1->T_02); ?></div></td>

			<td style="background: <?php echo $bgedit; ?>"><div style="background: <?php echo $bgedit; ?>" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="<?php echo $contentedit; ?>" class="edit-value text-right" data-name="T_03" data-id="<?php echo $m1->id; ?>" data-value="'.number_format($m1->T_03).'"><?php echo number_format($m1->T_03); ?></div></td>

			<td style="background: <?php echo $bgedit; ?>"><div style="background: <?php echo $bgedit; ?>" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="<?php echo $contentedit; ?>" class="edit-value text-right" data-name="T_04" data-id="<?php echo $m1->id; ?>" data-value="'.number_format($m1->T_04).'"><?php echo number_format($m1->T_04); ?></div></td>

			<td style="background: <?php echo $bgedit; ?>"><div style="background: <?php echo $bgedit; ?>" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="<?php echo $contentedit; ?>" class="edit-value text-right" data-name="T_05" data-id="<?php echo $m1->id; ?>" data-value="'.number_format($m1->T_05).'"><?php echo number_format($m1->T_05); ?></div></td>

			<td style="background: <?php echo $bgedit; ?>"><div style="background: <?php echo $bgedit; ?>" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="<?php echo $contentedit; ?>" class="edit-value text-right" data-name="T_06" data-id="<?php echo $m1->id; ?>" data-value="'.number_format($m1->T_06).'"><?php echo number_format($m1->T_06); ?></div></td>

			<td style="background: <?php echo $bgedit; ?>"><div style="background: <?php echo $bgedit; ?>" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="<?php echo $contentedit; ?>" class="edit-value text-right" data-name="T_07" data-id="<?php echo $m1->id; ?>" data-value="'.number_format($m1->T_07).'"><?php echo number_format($m1->T_07); ?></div></td>

			<td style="background: <?php echo $bgedit; ?>"><div style="background: <?php echo $bgedit; ?>" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="<?php echo $contentedit; ?>" class="edit-value text-right" data-name="T_08" data-id="<?php echo $m1->id; ?>" data-value="'.number_format($m1->T_08).'"><?php echo number_format($m1->T_08); ?></div></td>

			<td style="background: <?php echo $bgedit; ?>"><div style="background: <?php echo $bgedit; ?>" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="<?php echo $contentedit; ?>" class="edit-value text-right" data-name="T_09" data-id="<?php echo $m1->id; ?>" data-value="'.number_format($m1->T_09).'"><?php echo number_format($m1->T_09); ?></div></td>																				
			<td style="background: <?php echo $bgedit; ?>"><div style="background: <?php echo $bgedit; ?>" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="<?php echo $contentedit; ?>" class="edit-value text-right" data-name="T_10" data-id="<?php echo $m1->id; ?>" data-value="'.number_format($m1->T_10).'"><?php echo number_format($m1->T_10); ?></div></td>	

			<td style="background: <?php echo $bgedit; ?>"><div style="background: <?php echo $bgedit; ?>" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="<?php echo $contentedit; ?>" class="edit-value text-right" data-name="T_11" data-id="<?php echo $m1->id; ?>" data-value="'.number_format($m1->T_11).'"><?php echo number_format($m1->T_11); ?></div></td>	

			<td style="background: <?php echo $bgedit; ?>"><div style="background: <?php echo $bgedit; ?>" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="<?php echo $contentedit; ?>" class="edit-value text-right" data-name="T_12" data-id="<?php echo $m1->id; ?>" data-value="'.number_format($m1->T_12).'"><?php echo number_format($m1->T_12); ?></div></td>											

			<td class="button">
			<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="<?php echo $m1->id; ?>" title="<?php echo lang('ubah'); ?>"><i class="fa-edit"></i></button>
			<!--
			<button type="button" class="btn btn-danger btn-delete" data-key="delete" data-id="<?php echo $m1->id; ?>" title="<?php echo lang('hapus'); ?>"><i class="fa-trash-alt"></i></button>
			-->
		</td>
		</tr>
	<?php } 
	?>
		<tr>
			<th style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;"></th>
			<th style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;">TOTAL <?php echo $m0->keterangan; ?></th>
			<th class="text-right" style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($total_T01); ?></th>
			<th class="text-right" style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($total_T02); ?></th>
			<th class="text-right" style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($total_T03); ?></th>
			<th class="text-right" style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($total_T04); ?></th>	
			<th class="text-right" style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($total_T05); ?></th>
			<th class="text-right" style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($total_T06); ?></th>
			<th class="text-right" style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($total_T07); ?></th>
			<th class="text-right" style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($total_T08); ?></th>
			<th class="text-right" style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($total_T09); ?></th>
			<th class="text-right" style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($total_T10); ?></th>
			<th class="text-right" style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($total_T11); ?></th>		
			<th class="text-right" style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($total_T12); ?></th>							
		</tr>	

<?php 
$t_jumlah = 0;
} ?>
		
	