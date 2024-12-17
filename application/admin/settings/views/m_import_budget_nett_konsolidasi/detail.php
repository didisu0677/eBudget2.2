<div class="table-responsive data-general" data-id="<?= encode_id($id) ?>">
	<table class="table table-bordered table-app table-detail table-normal">
		<tr>
			<th><?php echo lang('update_terakhir'); ?></th>
			<td><?php echo c_date($create_at); ?></td>
		</tr>
		<tr>
			<th><?php echo lang('kode_cabang'); ?></th>
			<td><?php echo $kode_cabang; ?></td>
		</tr>
		<tr>
			<th><?php echo lang('kode_anggaran'); ?></th>
			<td><?php echo $kode_anggaran; ?></td>
		</tr>
		<tr>
			<th><?php echo lang('dalam_bentuk'); ?></th>
			<td><?php echo $currency.' (x '.custom_format($currency_value).')' ?></td>
		</tr>
		<tr>
			<th><?php echo lang('import_oleh'); ?></th>
			<td><?php echo $update_by; ?></td>
		</tr>
		<tr>
			<th>File</th>
			<td><a href="<?php echo base_url('download/file/'.encode_string(dir_upload('m_import_budget_nett_konsolidasi').$file)); ?>" class="btn btn-info btn-sm"><i class="fa-download"></i> <?php echo lang('unduh'); ?></a></td>
		</tr>

	</table>
</div>