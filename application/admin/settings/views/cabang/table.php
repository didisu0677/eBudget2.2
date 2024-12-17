<?php foreach($cabang[0] as $m0) { ?>

	<tr>
	?>
		<td><?php echo $m0->nama_cabang; ?></td>
		<td><?php echo $m0->kode_cabang; ?></td>
		<td><?php echo $m0->struktur_cabang; ?></td>
		<td class="text-center"><?php echo $m0->is_active ? '<span class="badge badge-success">TRUE</span>' : '<span class="badge badge-danger">FALSE</span>' ; ?></td>
		<td class="text-center"><?php echo $m0->list_kanpus ? '<span class="badge badge-success">TRUE</span>' : '<span class="badge badge-danger">FALSE</span>' ; ?></td>
		<td class="text-center"><?php echo $m0->status_group ? '<span class="badge badge-success">TRUE</span>' : '<span class="badge badge-danger">FALSE</span>' ; ?></td>
		<td class="button">

			<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="<?php echo $m0->id; ?>" title="<?php echo lang('ubah'); ?>"><i class="fa-edit"></i></button>
			<button type="button" class="btn btn-danger btn-delete" data-key="delete" data-id="<?php echo $m0->id; ?>" title="<?php echo lang('hapus'); ?>"><i class="fa-trash-alt"></i></button>
		</td>
	</tr>
	<?php foreach($cabang[$m0->id] as $m1) { ?>
		<tr>
			<td class="sub-1"><?php echo $m1->nama_cabang; ?></td>
			<td><?php echo $m1->kode_cabang; ?></td>
			<td><?php echo $m1->struktur_cabang; ?></td>
			<td class="text-center"><?php echo $m1->is_active ? '<span class="badge badge-success">TRUE</span>' : '<span class="badge badge-danger">FALSE</span>' ; ?></td>
			<td class="text-center"><?php echo $m1->list_kanpus ? '<span class="badge badge-success">TRUE</span>' : '<span class="badge badge-danger">FALSE</span>' ; ?></td>
			<td class="text-center"><?php echo $m1->status_group ? '<span class="badge badge-success">TRUE</span>' : '<span class="badge badge-danger">FALSE</span>' ; ?></td>
			<td class="button">
				<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="<?php echo $m1->id; ?>" title="<?php echo lang('ubah'); ?>"><i class="fa-edit"></i></button>
				<button type="button" class="btn btn-danger btn-delete" data-key="delete" data-id="<?php echo $m1->id; ?>" title="<?php echo lang('hapus'); ?>"><i class="fa-trash-alt"></i></button>
			</td>
		</tr>
		<?php foreach($cabang[$m1->id] as $m2) { ?>
			<tr>
				<td class="sub-2"><?php echo $m2->nama_cabang; ?></td>
				<td><?php echo $m2->kode_cabang; ?></td>
				<td><?php echo $m2->struktur_cabang; ?></td>
				<td class="text-center"><?php echo $m2->is_active ? '<span class="badge badge-success">TRUE</span>' : '<span class="badge badge-danger">FALSE</span>' ; ?></td>
				<td class="text-center"><?php echo $m2->list_kanpus ? '<span class="badge badge-success">TRUE</span>' : '<span class="badge badge-danger">FALSE</span>' ; ?></td>
				<td class="text-center"><?php echo $m2->status_group ? '<span class="badge badge-success">TRUE</span>' : '<span class="badge badge-danger">FALSE</span>' ; ?></td>
				<td class="button">
					<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="<?php echo $m2->id; ?>" title="<?php echo lang('ubah'); ?>"><i class="fa-edit"></i></button>
					<button type="button" class="btn btn-danger btn-delete" data-key="delete" data-id="<?php echo $m2->id; ?>" title="<?php echo lang('hapus'); ?>"><i class="fa-trash-alt"></i></button>
				</td>
			</tr>
			<?php foreach($cabang[$m2->id] as $m3) { ?>
				<tr>
					<td class="sub-3"><?php echo $m3->nama_cabang; ?></td>
					<td><?php echo $m3->kode_cabang; ?></td>
					<td><?php echo $m3->struktur_cabang; ?></td>
					<td class="text-center"><?php echo $m3->is_active ? '<span class="badge badge-success">TRUE</span>' : '<span class="badge badge-danger">FALSE</span>' ; ?></td>
					<td class="text-center"><?php echo $m3->list_kanpus ? '<span class="badge badge-success">TRUE</span>' : '<span class="badge badge-danger">FALSE</span>' ; ?></td>
					<td class="text-center"><?php echo $m3->status_group ? '<span class="badge badge-success">TRUE</span>' : '<span class="badge badge-danger">FALSE</span>' ; ?></td>
					<td class="button">

						<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="<?php echo $m3->id; ?>" title="<?php echo lang('ubah'); ?>"><i class="fa-edit"></i></button>
						<button type="button" class="btn btn-danger btn-delete" data-key="delete" data-id="<?php echo $m3->id; ?>" title="<?php echo lang('hapus'); ?>"><i class="fa-trash-alt"></i></button>
					</td>
				</tr>
			<?php } ?>
		<?php } ?>
	<?php } ?>
<?php } ?>