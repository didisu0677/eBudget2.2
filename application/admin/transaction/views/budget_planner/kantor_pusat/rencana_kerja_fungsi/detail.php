<?php
if($produk == 1) $produk = 'Ya'; else $produk = 'Tidak';
if($anggaran == 1) $anggaran = 'Ya'; else $anggaran = 'Tidak';

$s_div = false;
$divisi = '';
if($divisi_terkait):
	$divisi_terkait = json_decode($divisi_terkait,true);
	if(count($divisi_terkait)>0):
		$s_div = true;
		$kode_cabang_divisi = $kode_cabang;
        if($level4):
            $dt_cabang = get_data('tbl_m_cabang','id',$parent_id)->row();
            $kode_cabang_divisi = $dt_cabang->kode_cabang;
        endif;
        $divisi_terkait[] = $kode_cabang_divisi;

		$ls = get_data('tbl_m_cabang','kode_cabang',$divisi_terkait)->result();
		foreach ($ls as $kk => $vv) {
			$divisi .= '- '.$vv->nama_cabang.'<br>';
		}
	endif;
endif;

?>
<div class="table-responsive data-general" data-id="<?= encode_id($id) ?>">
	<table class="table table-bordered table-app table-detail table-normal">
		<tr>
			<th width="200">Kode Anggaran</th><td><?= $kode_anggaran ?></td>
		</tr>

		<tr>
			<th width="200">Cabang</th><td><?= $nama_cabang ?></td>
		</tr>
		<tr>
			<th width="200">KEBIJAKAN UMUM DIREKSI</th><td><?= $kebijakan_umum ?></td>
		</tr>
		<tr>
			<th width="200">PROGRAM KERJA</th><td><?= $program_kerja ?></td>
		</tr>
		<tr>
			<th width="200">PRODUK / AKTIVITAS BARU</th><td><?= $produk ?></td>
		</tr>
		<tr>
			<th width="200">PERSPEKTIF</th><td><?= $perspektif ?></td>
		</tr>
		<tr>
			<th width="200">STATUS PROGRAM</th><td><?= $status_program ?></td>
		</tr>
		<tr>
			<th width="200">SKALA PROGRAM</th><td><?= $skala_program ?></td>
		</tr>
		<tr>
			<th width="200">TUJUAN</th><td><?= $tujuan ?></td>
		</tr>
		<tr>
			<th width="200">OUTPUT</th><td><?= $output ?></td>
		</tr>
		<tr>
			<th width="200">Anggaran</th><td><?= $anggaran ?></td>
		</tr>
		<?php if($s_div): ?>
		<tr>
			<th width="200">Divisi Terkait</th><td><?= $divisi ?></td>
		</tr>
		<?php endif; ?>
	</table>

	<?php
		if(count($detail)>0):
			echo '<table class="table table-bordered table-app table-detail table-normal">';
			echo '<thead>
				<tr>
					<th class="text-center">Jadwal Bulan</th>
					<th class="text-center">Uraian</th>
					<th class="text-center">Bobot</th>
				</tr>
				</thead><tbody>';
			foreach ($detail as $k => $v) {
				$bobot = custom_format($v->bobot);
				if(!$v->bobot) $bobot = '';
				$item = '<tr>';
				$item .= '<td class="text-center">'.month_lang($v->bulan).'</td>';
				$item .= '<td>'.$v->uraian.'</td>';
				$item .= '<td class="text-right">'.$bobot.'</td>';
				$item .= '</tr>';
				echo $item;
			}
			echo '</tbody></table>';
		endif;
	?>
</div>