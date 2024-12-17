<div class="main-container">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header text-center">
					MONITORING BANK JATENG TAHUN <?= $tahun->keterangan ?><br>
					<?= $cabang->nama_cabang ?><br>
					<red><?= $coa->glwnco.' - '.remove_spaces($coa->glwdes) ?></red>
				</div>
				<div class="card-body">
					<div class="table-responsive tab-pane fade active show height-window" data-height="100">
						<table class="table table-striped table-bordered table-app table-hover">
							<thead>
								<tr>
									<th colspan="7"><?= get_view_report() ?></th>
								</tr>
								<tr>
									<th width="30" class="text-center align-middle"><?= lang('no') ?></th>
									<th class="mw-150 text-center align-middle"><?= lang('coa') ?></th>
									<th class="mw-250 text-center align-middle"><?= lang('nama_akun') ?></th>
									<th class="mw-150 text-center align-middle"><?= month_lang($bulan).' '.($tahun->tahun_anggaran) ?> <br>RENC</th>
									<th class="mw-150 text-center align-middle"><?= month_lang($bulan).' '.($tahun->tahun_anggaran-1) ?> <br>REAL</th>
									<th class="mw-150 text-center align-middle">PENC (%)</th>
									<th class="mw-150 text-center align-middle">+/-</th>
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