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
			$bgedit ="";
			$contentedit ="true" ;
			$id = 'id' ;
		}
		if(!$m1->penjelasan):
			$m1->penjelasan = '.........................';
		endif;
		?>
		<tr>
			<td><?php echo $no; ?></td>
			<td><?php echo $m1->cabang_induk; ?></td>
			<td><?php echo $m1->rencana_jarkan; ?></td>
			<td><?php echo strtoupper($m1->tahapan_pengembangan); ?></td>
			<td><?php echo $m1->kategori_kantor; ?></td>
			<td class="text-right"><?php echo custom_format(view_report($m1->harga)); ?></td>

			<td><?= $m1->nama_kantor ?></td>

			<td><?php echo month_lang($m1->jadwal); ?></td>
			<td><?php echo $m1->status_ket_kantor; ?></td>
			
			<td><?= $m1->kecamatan.',</br>'.$m1->kota ?></td>

			<td><div style="overflow: hidden;" contenteditable="<?php echo $contentedit; ?>" class="edit-text text-left w-150" data-name="penjelasan" data-id="<?php echo $m1->id; ?>" data-value="<?= $m1->penjelasan ?>"><?php echo $m1->penjelasan; ?></div></td>

			<td><?= $m1->nama_keterangan?></td>
			<td class="text-center align-middle"><span class="color" style="height: 15px;width: 15px;border: 1px solid #6c757d;background-color:<?= $m1->warna_keterangan ?>"></span></td>

			<td class="button">
				<button type="button" class="btn btn-info btn-detail" data-key="act-detil" data-id="<?= $m1->id ?>" title="<?= lang('detil') ?>"><i class="fa-search"></i></button>
				
				<?php if($akses_ubah): ?>
				<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="<?php echo $m1->id; ?>" title="<?php echo lang('ubah'); ?>"><i class="fa-edit"></i></button>
				<?php endif; ?>
			</td>
		</tr>
	<?php 
	$t_tahun = 0;
	}
	if(count($produk)<=0):
		$item = '<tr>';
		$item .= '<td colspan="14">'.lang('data_not_found').'</td>';
		$item .= '</tr>';
		echo $item;
	endif; 
	?>