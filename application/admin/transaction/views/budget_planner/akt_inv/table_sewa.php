<?php 
	foreach($grup[0] as $m0) { 

	$no=0;
	$total = 0;
	$jumlah = 0;

	if($m0->kode == 'E.7'):

    ?>
	<tr>
		<td><?php echo $m0->kode; ?></td>
		<td><?php echo $m0->keterangan; ?></td>
		<!-- <td><?php echo $m0->catatan; ?></td> -->
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<?php foreach($produk[$m0->kode] as $m1) { 
		$no++;
		$total += ($m1->harga * $m1->jumlah);
		$jumlah += $m1->jumlah ;

		$bgedit ="";
		$contentedit ="false" ;
		if(($m1->grup !='E.4' && $m1->grup !='E.5') || $m1->kode_inventaris =='') {
			// $bgedit = bgEdit();
			$bgedit = "";
			$contentedit ="true" ;
		}	

		$id = 'keterangan';
		if($akses_ubah) {
			// $bgedit = bgEdit();
			$bgedit = "";
			$contentedit ="true" ;
			$id = 'id' ;
		}else{
			$bgedit ="";
			$contentedit ="false" ;
			$id = 'id' ;
		}


		?> 
		<tr>
			<td><?php echo $no; ?></td>
			<td><?php echo $m1->nama_inventaris; ?></td>
			<td><?php echo $m1->catatan; ?></td>
			
			<td style="background: <?php echo $bgedit; ?>"><div style="background: <?php echo $bgedit; ?>" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="<?php echo $contentedit; ?>" class="edit-value text-right" data-name="harga" data-id="<?php echo $m1->id; ?>" data-value="<?= $m1->harga ?>"><?php echo number_format(view_report($m1->harga)) ?></div></td>

			<td style="background: <?php echo $bgedit; ?>"><div style="background: <?php echo $bgedit; ?>" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="<?php echo $contentedit; ?>" class="edit-value text-right" data-name="jumlah" data-id="<?php echo $m1->$id; ?>" data-value="<?= $m1->jumlah ?>"><?php echo number_format($m1->jumlah); ?></div></td>

			<td style="background: <?php echo $bgedit; ?>"><div style="background: <?php echo $bgedit; ?>" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="<?php echo $contentedit; ?>" class="edit-value text-right" data-name="bulan" data-id="<?php echo $m1->$id; ?>" data-value="<?= $m1->bulan ?>"><?php echo number_format($m1->bulan); ?></div></td>

			<th style="background: <?php echo $bgedit; ?>"><div style="background: <?php echo $bgedit; ?>" style="min-height: 10px; width: 50px; overflow: hidden;" class="text-right" data-name="bulan" data-id="<?php echo $m1->$id; ?>" data-value="<?= ($m1->harga * $m1->jumlah) ?>"><?php echo number_format(view_report($m1->harga * $m1->jumlah)); ?></div></th>

			<?php if($akses_ubah): ?>
			<td class="button">
			<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="<?php echo $m1->id; ?>" title="<?php echo lang('ubah'); ?>"><i class="fa-edit"></i></button>
			<?php else: echo '<td></td>'; endif; ?>
			<!--
			<button type="button" class="btn btn-danger btn-delete" data-key="delete" data-id="<?php echo $m1->id; ?>" title="<?php echo lang('hapus'); ?>"><i class="fa-trash-alt"></i></button>
			-->
		</td>
		</tr>
	<?php } 
	?>
		<tr>
			<th style="background: #f0f0f0;" style="min-height: 10px; width: 50px; overflow: hidden;"></th>
			<th style="background: #f0f0f0;" style="min-height: 10px; width: 50px; overflow: hidden;">TOTAL <?php echo $m0->kode; ?></th>
			<th style="background: #f0f0f0;" style="min-height: 10px; width: 50px; overflow: hidden;"></th>
			<th style="background: #f0f0f0;" style="min-height: 10px; width: 50px; overflow: hidden;"></th>
			<th class="text-right" style="background: #f0f0f0;" style="min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($jumlah); ?></th>
			<th style="background: #f0f0f0;" style="min-height: 10px; width: 50px; overflow: hidden;"></th>
			<th class="text-right" style="background: #f0f0f0;" style="min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format(view_report($total)); ?></th>
			<th style="background: #f0f0f0;" style="min-height: 10px; width: 50px; overflow: hidden;"></th>
			
		</tr>	

<?php
break;
endif;
$t_jumlah = 0;
} ?>
		
	