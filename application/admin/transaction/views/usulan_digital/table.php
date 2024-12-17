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

		$bgedit ="";
		$contentedit ="false" ;
		$id = 'keterangan';
		if($akses_ubah) {
			$bgedit ="#ffbb33";
			$contentedit ="true" ;
			$id = 'id' ;
		}

		?>
		<tr>
			<td><?php echo $no; ?></td>
			<td><?php echo $m1->customer; ?></td>
			<td><?php echo $m1->kategori_layanan; ?></td>
			<td><?php echo $m1->nama_lokasi; ?></td>
			<td><?php echo $m1->bulan; ?></td>
			<td><?php echo $m1->status_ket_kantor; ?></td>

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
