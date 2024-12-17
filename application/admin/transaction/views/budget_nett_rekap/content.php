<div class="main-container">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header text-center">
					REKAP BUDGET NETT BANK JATENG <?= $tahun->keterangan ?><br>
					BERDASAR KANTOR CABANG DAN CAPEM <br>
					<red><?=  str_replace('_', '.', $coa->glwnco).' - '.$coa->glwdes ?></red><br>
					<?= $title ?>
				</div>
				<div class="card-body">
					<div class="table-responsive tab-pane fade active show height-window" data-height="50">
						<table class="table table-striped table-bordered table-app table-hover">
							<thead>
								<tr>
									<th colspan="<?= (2+count($detail_tahun)) ?>"><?= get_view_report() ?></th>
								</tr>
								<tr>
									<th width="30" class="text-center align-middle"><?= lang('no') ?></th>
									<th class="mw-250 text-center align-middle"><?= lang('nama_kantor') ?></th>
									<?php
									foreach ($detail_tahun as $k => $v) {
										$column = month_lang($v->bulan).' '.$v->tahun;
										$column .= '<br> ('.$v->singkatan.')';
										echo '<th style="min-width:150px" class="text-center">'.$column.'</th>';
									}
									?>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>