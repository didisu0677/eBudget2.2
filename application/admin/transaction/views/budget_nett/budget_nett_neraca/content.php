<div class="ContenedorTabla table-responsive tab-pane fade active show height-window" id="div-<?= $kode_cabang ?>" data-height="0">
	<table class="table table-striped table-bordered table-app table-hover">
		<thead>
			<tr class="d-cabang-neraca d-bg-header">
				<th colspan="4"><red>-</red></th>
				<th class="d-head" colspan="12"><red>-</red></th>
			</tr>
			<tr class="d-neraca d-bg-header">
				<th width="60" class="text-center align-middle wd-100"><span><?= lang('sandi bi') ?></span></th>
				<th width="60" class="text-center align-middle wd-100"><span><?= lang('coa 5') ?></span></th>
				<th width="60" class="text-center align-middle wd-100"><span><?= lang('coa 7') ?></span></th>
				<th class="text-center align-middle wd-230"><span><?= lang('keterangan') ?></span></th>
				<?php
				for ($i=1; $i <=12 ; $i++) { 
					echo '<th class="d-head"><red>-</red></th>';
				}
				?>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
</div>