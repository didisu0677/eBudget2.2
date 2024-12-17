<div class="table-responsive data-general" data-id="<?= encode_id($id) ?>">
	<table class="table table-bordered table-app table-detail table-normal">
		<tr>
			<th width="200"><?= lang('anggaran') ?></th><td><?= $anggaran_keterangan ?></td>
		</tr>
		<tr>
			<th width="200"><?= lang('cabang').' (input)' ?></th><td><?= $nama_cabang ?></td>
		</tr>

		<tr><td>.</td><td></td></tr>
		<tr>
			<th width="200"><?= lang('cabang_induk') ?></th><td><?= $cabang_induk ?></td>
		</tr>
		<tr>
			<th width="200"><?= lang('rencana') ?></th><td><?= $rencana_jarkan ?></td>
		</tr>
		<tr>
			<th><?= lang('tahapan') ?></th><td><?= strtoupper($tahapan_pengembangan) ?></td>
		</tr>
		<tr>
			<th><?= lang('jenis_kantor') ?></th><td><?= $kategori_kantor ?></td>
		</tr>
		<tr>
			<th><?= lang('biaya_perkiraan').' ('.get_view_report().')' ?></th><td><?= custom_format(view_report($harga)) ?></td>
		</tr>
		<tr>
			<th><?= lang('nama_kantor') ?></th><td><?= $nama_kantor ?></td>
		</tr>
		<tr>
			<th><?= lang('jadwal') ?></th><td><?= month_lang($jadwal) ?></td>
		</tr>
		<tr>
			<th><?= lang('status') ?></th><td><?= $status_ket_kantor ?></td>
		</tr>
		<tr>
			<th><?= lang('kecamatan') ?></th><td><?= $kecamatan.', '.$kota ?></td>
		</tr>
		<tr>
			<th><?= lang('penjelasan') ?></th><td><?= $penjelasan ?></td>
		</tr>
		<tr>
			<th><?= lang('keterangan') ?></th><td><?= $nama_keterangan ?></td>
		</tr>
		<tr>
			<th><?= lang('warna_keterangan') ?></th><td><span class="color" style="height: 15px;width: 15px;border: 1px solid #6c757d;background-color:<?= $warna_keterangan ?>"></span></td>
		</tr>
	</table>
</div>