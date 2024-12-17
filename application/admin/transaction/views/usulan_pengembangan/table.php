<?php 
	$no=0;
	$total_bgaji = 0;
	$total_bpromosi = 0;
	$total_b_adm_umum = 0;
	$total_b_non_opr = 0;
	$total_inv_gedung = 0;
	$total_inst_bangunan = 0;
	$total_akt_kel1 = 0;
	$total_akt_kel2 = 0;
    ?>
	<?php foreach($produk as $m1) { $no++;

		$total_bgaji += $m1->b_gaji;
		$total_bpromosi += $m1->b_promosi;
		$total_b_adm_umum += $m1->b_adm_umum;
		$total_b_non_opr += $m1->b_non_opr;
		$total_inv_gedung += $m1->inv_gedung;
		$total_inst_bangunan += $m1->inst_bangunan;
		$total_akt_kel1 += $m1->akt_kel1;
		$total_akt_kel2 += $m1->akt_kel2;
		?>
		<tr>
			<td><?php echo $no; ?></td>
			<td><?php echo $m1->status_jaringan_kantor; ?></td>
			<td><?php echo $m1->kategori_kantor; ?></td>
			<td><?php echo $m1->nama_lokasi; ?></td>
			<td><?php echo $m1->bulan; ?></td>
			<td><?php echo $m1->status_ket_kantor; ?></td>
			<td style="background: #ffbb33;"><div style="background: #ffbb33;" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="true" contenteditable="true" class="edit-value text-right" data-name="b_gaji" data-id="<?php echo $m1->id; ?>" data-produk ="'.$m1->status_jaringan_kantor.'" data-value="'.number_format($m1->b_gaji).'"><?php echo number_format($m1->b_gaji); ?></div></td>
			<td style="background: #ffbb33;"><div style="background: #ffbb33;" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="true" contenteditable="true" class="edit-value text-right" data-name="b_promosi" data-id="<?php echo $m1->id; ?>" data-produk ="'.$m1->status_jaringan_kantor.'" data-value="'.number_format($m1->b_promosi).'"><?php echo number_format($m1->b_promosi); ?></div></td>
			<td style="background: #ffbb33;"><div style="background: #ffbb33;" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="true" contenteditable="true" class="edit-value text-right" data-name="b_adm_umum" data-id="<?php echo $m1->id; ?>" data-produk ="'.$m1->status_jaringan_kantor.'" data-value="'.number_format($m1->b_adm_umum).'"><?php echo number_format($m1->b_adm_umum); ?></div></td>
			<td style="background: #ffbb33;"><div style="background: #ffbb33;" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="true" contenteditable="true" class="edit-value text-right" data-name="b_non_opr" data-id="<?php echo $m1->id; ?>" data-produk ="'.$m1->status_jaringan_kantor.'" data-value="'.number_format($m1->b_non_opr).'"><?php echo number_format($m1->b_non_opr); ?></div></td>			
			<td style="background: #ffbb33;"><div style="background: #ffbb33;" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="true" contenteditable="true" class="edit-value text-right" data-name="inv_gedung" data-id="<?php echo $m1->id; ?>" data-produk ="'.$m1->status_jaringan_kantor.'" data-value="'.number_format($m1->inv_gedung).'"><?php echo number_format($m1->inv_gedung); ?></div></td>
			<td style="background: #ffbb33;"><div style="background: #ffbb33;" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="true" contenteditable="true" class="edit-value text-right" data-name="inst_bangunan" data-id="<?php echo $m1->id; ?>" data-produk ="'.$m1->status_jaringan_kantor.'" data-value="'.number_format($m1->inst_bangunan).'"><?php echo number_format($m1->inst_bangunan); ?></div></td>
			<td style="background: #ffbb33;"><div style="background: #ffbb33;" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="true" contenteditable="true" class="edit-value text-right" data-name="akt_kel1" data-id="<?php echo $m1->id; ?>" data-produk ="'.$m1->status_jaringan_kantor.'" data-value="'.number_format($m1->akt_kel1).'"><?php echo number_format($m1->akt_kel1); ?></div></td>	
			<td style="background: #ffbb33;"><div style="background: #ffbb33;" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="true" contenteditable="true" class="edit-value text-right" data-name="akt_kel2" data-id="<?php echo $m1->id; ?>" data-produk ="'.$m1->status_jaringan_kantor.'" data-value="'.number_format($m1->akt_kel2).'"><?php echo number_format($m1->akt_kel2); ?></div></td>			
			<td class="button">
			<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="<?php echo $m1->id; ?>" title="<?php echo lang('ubah'); ?>"><i class="fa-edit"></i></button>
			<!--
			<button type="button" class="btn btn-danger btn-delete" data-key="delete" data-id="<?php echo $m1->id; ?>" title="<?php echo lang('hapus'); ?>"><i class="fa-trash-alt"></i></button>
			-->
		</td>
		</tr>
	<?php 
	$t_tahun = 0;
	} 
	?>	
		<tr>
		<th style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;"></th>
		<th class = "text-center" colspan = "5" style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden; text-align: center">TOTAL</th>s
		<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($total_bgaji);?></th>
		<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($total_bpromosi);?></th>
		<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($total_b_adm_umum);?></th>
		<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($total_b_non_opr);?></th>
		<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($total_inv_gedung);?></th>
		<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($total_inst_bangunan);?></th>
		<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($total_akt_kel1);?></th>	
		<th class="text-right" style="background: #757575; min-height: 10px; width: 50px; overflow: hidden;"><?php echo number_format($total_akt_kel2);?></th>		
	</tr>	