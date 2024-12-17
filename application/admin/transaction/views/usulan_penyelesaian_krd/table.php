<?php 
	$no=0;
	$total_outst = 0;
    ?>
	<?php foreach($produk as $m1) { $no++;
    	$total_outst += $m1->sisa_outstanding;

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
			<td><?php echo $m1->nama_debitur; ?></td>

			<td><div style="overflow: hidden;" contenteditable="false" class="edit-text text-left" data-name="produk_kredit" data-id="<?php echo $m1->id; ?>" data-value="'.$m1->produk_kredit.'"><?php echo $m1->nama_produk_kredit; ?></div></td>

			<td style="background:"><div style="background:" style="overflow: hidden;" contenteditable="<?php echo $contentedit; ?>" class="edit-value text-right" data-name="sisa_outstanding" data-id="<?php echo $m1->id; ?>" data-value="'.number_format($m1->sisa_outstanding).'"><?php echo number_format($m1->sisa_outstanding); ?></div></td>

			<td><div style="overflow: hidden;" contenteditable="false" class="edit-text text-left" data-name="posisi_kolek" data-id="<?php echo $m1->id; ?>" data-value="'.$m1->posisi_kolek.'"><?php echo $m1->posisi_kolek; ?></div></td>

			<td><div style="overflow: hidden;" contenteditable="false" class="edit-text text-left" data-name="tgl_jatuh_tempo" data-id="<?php echo $m1->id; ?>" data-value="'.$m1->tgl_jatuh_tempo.'"><?php echo date_indo($m1->tgl_jatuh_tempo); ?></div></td>	

			<td><div style="overflow: hidden;" contenteditable="false" class="edit-text text-left" data-name="deskripsi_penyelesaian" data-id="<?php echo $m1->id; ?>"  data-value="'.$m1->deskripsi_penyelesaian.'"><?php echo $m1->deskripsi_penyelesaian; ?></div></td>	

			<td><div style="overflow: hidden;" contenteditable="false" class="edit-value text-right" data-name="target_waktu_penyelesaian" data-id="<?php echo $m1->id; ?>"  data-value="'.$m1->target_waktu_penyelesaian.'"><?php echo date_indo($m1->target_waktu_penyelesaian); ?></div></td>								
			<td class="button">
			<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="<?php echo $m1->id; ?>" title="<?php echo lang('ubah'); ?>"><i class="fa-edit"></i></button>
			<!--
			<button type="button" class="btn btn-danger btn-delete" data-key="delete" data-id="<?php echo $m1->id; ?>" title="<?php echo lang('hapus'); ?>"><i class="fa-trash-alt"></i></button>
			-->
		</td>
		</tr>
	<?php } ?>

	<tr> ;
		<th style="background: #757575;" style="overflow: hidden;"></th>
		<th style="background: #757575;" style="overflow: hidden;">TOTAL</th>
		<th class="text-right" style="background: #757575; overflow: hidden;"></th>
		<th class="text-right" style="background: #757575; overflow: hidden;"><?php echo number_format($total_outst) ;?></th>
		<th class="text-right" style="background: #757575; overflow: hidden;"></th>
		<th class="text-right" style="background: #757575; overflow: hidden;"></th>
		<th class="text-right" style="background: #757575; overflow: hidden;"></th>							
		<th class="text-right" style="background: #757575; overflow: hidden;"></th>
		<th class="text-right" style="background: #757575; overflow: hidden;"></th>
	</tr>	
		
	