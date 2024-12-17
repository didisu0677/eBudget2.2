<h5 class="mt-3"><?= $title_[0].$title[0] ?></h5>

<div class="card mt-3">
	<div class="card-header"><?= $title[0] ?></div>
	<div class="card-body">
		<div class="table-responsive tab-pane fade active show" id="result2">
			<table class="table table-striped table-bordered table-app table-hover">
				<thead>
					<tr>
						<th colspan="10"><?= get_view_report() ?></th>
					</tr>
					<tr>
						<th rowspan="2" width="30" class="text-center align-middle"><?= lang('no') ?></th>
						<th rowspan="2" class="text-center align-middle"><?= lang('bulan') ?></th>
						<th rowspan="2" class="text-center align-middle"><?= lang('total_krd') ?></th>
						<th colspan="5" class="text-center align-middle"><?= lang('kolektabilitas') ?></th>
						<th rowspan="2" width="150px" class="text-center align-middle">KRD BERMASALAH</th>
						<th rowspan="2" width="150px" class="text-center align-middle"><?= lang('npl').' (%)' ?></th>
					</tr>
					<tr>
						<th width="150px" class="text-center align-middle">1</th>
						<th width="150px" class="text-center align-middle">2</th>
						<th width="150px" class="text-center align-middle">3</th>
						<th width="150px" class="text-center align-middle">4</th>
						<th width="150px" class="text-center align-middle">5</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$arrTotalNpl = [];
				foreach ($detail_tahun as $k2 => $v2) {
					$v_field  = 'B_' . sprintf("%02d", $v2->bulan);
					$column = month_lang($v2->bulan).' '.$v2->tahun;
					$column .= '('.$v2->singkatan.')';

					$kredit = 0;
					$col_1 	= 0;
					$col_2 	= 0;
					$col_3 	= 0;
					$col_4 	= 0;
					$col_5 	= 0;
					$npl 	= 0;
					foreach ($listTotalKredit as $k => $v) {
						if($v->tahun_core == $v2->tahun):
							$kredit += $v->{$v_field};
							$col_1  += $v->{$v_field.'_1'};
							$col_2  += $v->{$v_field.'_2'};
							$col_3  += $v->{$v_field.'_3'};
							$col_4  += $v->{$v_field.'_4'};
							$col_5  += $v->{$v_field.'_5'};
							$npl    += $v->{$v_field.'_bermasalah'};
						endif;
					}
					
					$total_npl = ($kredit!=0)?($npl/$kredit)*100:0;

					$item = '<tr>';
					$item .= '<td>'.($k2+1).'</td>';
					$item .= '<td>'.$column.'</td>';
					$item .= '<td class="text-right">'.check_value($kredit).'</td>';
					$item .= '<td class="text-right">'.check_value($col_1).'</td>';
					$item .= '<td class="text-right">'.check_value($col_2).'</td>';
					$item .= '<td class="text-right">'.check_value($col_3).'</td>';
					$item .= '<td class="text-right">'.check_value($col_4).'</td>';
					$item .= '<td class="text-right">'.check_value($col_5).'</td>';
					$item .= '<td class="text-right">'.check_value($npl).'</td>';
					$item .= '<td class="text-right">'.custom_format($total_npl,false,2).'</td>';
					$item .= '</tr>';

					if($v2->tahun == $anggaran->tahun_anggaran):
						$column = month_lang($v2->bulan);
						$h['npl'][$column] = $total_npl;
						$this->session->set_userdata($h);
					endif;

					$z['npl2'][$v2->tahun.'-'.$v_field] = $total_npl;
					$this->session->set_userdata($z);

					$arrTotalNpl[$v2->tahun][$v_field] = $total_npl;

					echo $item;
				}
				$table_npl = 'tbl_kolektibilitas_npl';
				foreach ($arrTotalNpl as $k => $data_npl) {
					$x = explode('-', $k);
					$tahun_core  = $x[0];

					$ck_data = get_data($table_npl,[
						'select' => 'id',
						'where'	 => "kode_cabang = '$current_cabang' and tahun_core = '$tahun_core' and kode_anggaran = '$anggaran->kode_anggaran' and tahun = '$anggaran->tahun_anggaran' and tipe = '3'"
					])->row();
					if($ck_data):
						update_data($table_npl,$data_npl,'id',$ck_data->id);
					else:
						$h = $data_npl;
						$h['kode_cabang'] = $current_cabang;
						$h['kode_anggaran'] = $anggaran->kode_anggaran;
						$h['keterangan_anggaran'] = $anggaran->keterangan;
						$h['tahun'] = $anggaran->tahun_anggaran;
						$h['tahun_core'] = $tahun_core;
						$h['tipe'] = 3;
						insert_data($table_npl,$h);
					endif;
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>