<?php
	$CI        	 = get_instance();
	$total_nilai = [];
	$no = 0;
	$item_header = '<tr>';
	$item_header .= '<th></th>';
	$item_header .= '<th></th>';
	$item_header .= '<th></th>';
	
	$item_header2 = '<tr>';
	$item_header2 .= '<th>'.lang('no').'</th>';
	$item_header2 .= '<th class="text-center mw-250">'.lang('nama_cabang').'</th>';
	$item_header2 .= '<th class="text-center mw-150">'.lang('kantor').'</th>';

	$item_header3 = '<tr>';
	$item_header3 .= '<th></th>';
	$item_header3 .= '<th></th>';
	$item_header3 .= '<th></th>';

	foreach ($group as $k => $v) {
		foreach ($v as $k2 => $v2) {
			$no ++;
			if($no != 1) $item_header .= '<th class="border-none bg-white mw-100"></th>';
			$item_header .= '<th class="text-center" colspan="6">'.$v2->coa.' - '.remove_spaces($v2->glwdes).'</th>';
			
			if($no != 1) $item_header2 .= '<th class="border-none bg-white mw-100"></th>';
			$item_header2 .= '<th class="text-center">'.month_lang($bulan).'-'.($anggaran->tahun_anggaran - 1).'</th>';
			$item_header2 .= '<th class="text-center" colspan="3">'.month_lang($bulan).'-'.($anggaran->tahun_anggaran).'</th>';
			$item_header2 .= '<th class="text-center">Pert</th>';
			$item_header2 .= '<th class="text-center mw-100">Nilai</th>';
			
			if($no != 1) $item_header3 .= '<th class="border-none bg-white mw-100"></th>';
			$item_header3 .= '<th class="text-center mw-150">Real</th>';
			$item_header3 .= '<th class="text-center mw-150">Renc</th>';
			$item_header3 .= '<th class="text-center mw-150">Real</th>';
			$item_header3 .= '<th class="text-center mw-100">Penc (%)</th>';
			$item_header3 .= '<th class="text-center mw-100">YOY (%)</th>';
			$item_header3 .= '<th></th>';
		}
		if(count($v)>1):
			$item_header .= '<th class="border-none bg-white mw-100"></th>';
			$item_header .= '<th class="text-center" colspan="6">Total '.$k.'</th>';

			$item_header2 .= '<th class="border-none bg-white mw-100"></th>';
			$item_header2 .= '<th class="text-center">'.month_lang($bulan).'-'.($anggaran->tahun_anggaran - 1).'</th>';
			$item_header2 .= '<th class="text-center" colspan="3">'.month_lang($bulan).'-'.($anggaran->tahun_anggaran).'</th>';
			$item_header2 .= '<th class="text-center">Pert</th>';
			$item_header2 .= '<th class="text-center mw-100">Nilai</th>';

			$item_header3 .= '<th class="border-none bg-white mw-100"></th>';
			$item_header3 .= '<th class="text-center mw-150">Real</th>';
			$item_header3 .= '<th class="text-center mw-150">Renc</th>';
			$item_header3 .= '<th class="text-center mw-150">Real</th>';
			$item_header3 .= '<th class="text-center mw-100">Penc (%)</th>';
			$item_header3 .= '<th class="text-center mw-100">YOY (%)</th>';
			$item_header3 .= '<th></th>';
			$no ++;
		endif;
	}
	$item_header .= '<tr>';
	$item_header2 .= '<tr>';
	$item_header3 .= '<tr>';

	$item = '';

	$no = 0;
	$data = [
		'group' => $group,
		'bulan'	=> $bulan,
		'nilai'	=> $nilai,
		'history_current' 	=> $history_current,
		'history' 		 	=> $history,
	];
	foreach ($cab['cabang'] as $k => $v) {
		
		$dt_more = more($v['kode_cabang'],$cab,$no,0,$data);
		if($dt_more['status']):
			$no 	= $dt_more['no'];
			$dt 	= $dt_more['dt'];
			$item 	.= $dt_more['item'];
		endif;

		$no++;
		$item .= '<tr>';
		$item .= '<td>'.$no.'</td>';
		$item .= '<td>'.$v['nama_cabang'].'</td>';
		$item .= '<td>'.$v['struktur_cabang'].'</td>';
		$no_coa = 0;
		foreach ($group as $k2 => $v2) {
			$renc_total = 0;
			$real_total = 0;
			$real2_total= 0;
			foreach ($v2 as $k3 => $v3) {
				$no_coa++;
				$renc = 0;
				$real = 0;
				$real2 = 0;

				if($no_coa != 1) $item .= '<td class="border-none bg-white"></td>';

				if($dt_more['status']):
					$renc = $dt[$v3->coa]['renc'];
					$real = $dt[$v3->coa]['real'];
					$real2 = $dt[$v3->coa]['real2'];
				else:
					$coa_key = multidimensional_search($v['data'], array(
						'coa' => $v3->coa,
					));
					if(strlen($coa_key)>0):
						$field  = 'B_' . sprintf("%02d", $bulan);
						$renc = $v['data'][$coa_key][$field];
					endif;

					$tot = 'TOT_'.$v['kode_cabang'];
					$real_key = multidimensional_search($history_current, array(
						'glwnco' => $v3->coa,
					));
					if(strlen($real_key)>0):
						if(isset($history_current[$real_key][$tot])){
							$minus = $history_current[$real_key]['kali_minus'];
							$real = $history_current[$real_key][$tot];
							$real = kali_minus($real,$minus);
						}
					endif;

					$real_key2 = multidimensional_search($history, array(
						'glwnco' => $v3->coa,
					));
					if(strlen($real_key2)>0):
						if(isset($history[$real_key2][$tot])){
							$minus = $history[$real_key2]['kali_minus'];
							$real2 = $history[$real_key2][$tot];
							$real2 = kali_minus($real2,$minus);
						}
					endif;
				endif;

				$renc_total 	+= $renc;
				$real_total 	+= $real;
				$real2_total 	+= $real2;
				$penc = 0;
				if($real) $penc = ($renc/$real)*100;
				$pert = 0;
				if($real2) $pert = (($real-$real2)/$real2)*100;

				$dt_nilai = get_nilai($penc,$nilai,$v['struktur_cabang'],$v3->coa);

				$item .= '<td class="text-right">'.custom_format(view_report($real2)).'</td>';
				$item .= '<td class="text-right">'.custom_format(view_report($renc)).'</td>';
				$item .= '<td class="text-right">'.custom_format(view_report($real)).'</td>';
				$item .= '<td class="text-right">'.custom_format($penc,false,2).'</td>';
				$item .= '<td class="text-right">'.custom_format($pert,false,2).'</td>';
				$item .= '<td class="text-center">'.$dt_nilai['nilai'].'</td>';
			}
			if(count($v2)>1):
				$no_coa++;
				if($no_coa != 1) $item .= '<td class="border-none bg-white"></td>';
				$penc = 0;
				if($real_total) $penc = ($renc_total/$real_total)*100;
				$pert = 0;
				if($real2_total) $pert = (($real_total-$real2_total)/$real2_total)*100;

				$dt_nilai = get_nilai($penc,$nilai,$v['struktur_cabang'],$k2);

				$item .= '<td class="text-right">'.custom_format(view_report($real2_total)).'</td>';
				$item .= '<td class="text-right">'.custom_format(view_report($renc_total)).'</td>';
				$item .= '<td class="text-right">'.custom_format(view_report($real_total)).'</td>';
				$item .= '<td class="text-right">'.custom_format($penc,false,2).'</td>';
				$item .= '<td class="text-right">'.custom_format($pert,false,2).'</td>';
				$item .= '<td class="text-center">'.$dt_nilai['nilai'].'</td>';
			endif;
		}
		$item .= '</tr>';
	}

	function more($id,$cab,$no,$count,$data){
		$group = $data['group'];
		$bulan = $data['bulan'];
		$nilai = $data['nilai'];
		$history_current = $data['history_current'];
		$history 		 = $data['history'];

		$status = false;
		$item 	= '';
		$dt 	= [];

		if(isset($cab[$id])):
			$status = true;
			$count2 = ($count+1);
			foreach ($cab[$id] as $k => $v) {
				$dt_more = more($v['kode_cabang'],$cab,$no,$count2,$data);
				if($dt_more['status']):
					$no 	= $dt_more['no'];
					$dt2 	= $dt_more['dt'];
					$item 	.= $dt_more['item'];
				endif;

				$nama_cabang = $v['nama_cabang'];
				$no++;
				$item .= '<tr>';
				$item .= '<td>'.$no.'</td>';
				$item .= '<td class="sb-'.$count2.'">'.$nama_cabang.'</td>';
				$item .= '<td>'.$v['struktur_cabang'].'</td>';
				$no_coa = 0;
				foreach ($group as $k2 => $v2) {
					$renc_total = 0;
					$real_total = 0;
					$real2_total= 0;
					foreach ($v2 as $k3 => $v3) {
						$no_coa++;
						$renc = 0;
						$real = 0;
						$real2= 0;

						if($no_coa != 1) $item .= '<td class="border-none bg-white"></td>';

						if($dt_more['status']):
							$renc = $dt2[$v3->coa]['renc'];
							$real = $dt2[$v3->coa]['real'];
							$real2 = $dt2[$v3->coa]['real2'];
						else:
							$coa_key = multidimensional_search($v['data'], array(
								'coa' => $v3->coa,
							));
							if(strlen($coa_key)>0):
								$field  = 'B_' . sprintf("%02d", $bulan);
								$renc = $v['data'][$coa_key][$field];
							endif;

							$tot = 'TOT_'.$v['kode_cabang'];
							$real_key = multidimensional_search($history_current, array(
								'glwnco' => $v3->coa,
							));
							if(strlen($real_key)>0):
								if(isset($history_current[$real_key][$tot])){
									$minus = $history_current[$real_key]['kali_minus'];
									$real = $history_current[$real_key][$tot];
									$real = kali_minus($real,$minus);
								}
							endif;

							$real_key2 = multidimensional_search($history, array(
								'glwnco' => $v3->coa,
							));
							if(strlen($real_key2)>0):
								if(isset($history[$real_key2][$tot])){
									$minus = $history[$real_key2]['kali_minus'];
									$real2 = $history[$real_key2][$tot];
									$real2 = kali_minus($real2,$minus);
								}
							endif;

						endif;
						if(isset($dt[$v3->coa]['renc'])) $dt[$v3->coa]['renc'] += $renc; else $dt[$v3->coa]['renc'] = $renc;
						if(isset($dt[$v3->coa]['real'])) $dt[$v3->coa]['real'] += $real; else $dt[$v3->coa]['real'] = $real;
						if(isset($dt[$v3->coa]['real2'])) $dt[$v3->coa]['real2'] += $real2; else $dt[$v3->coa]['real2'] = $real2;

						$renc_total 	+= $renc;
						$real_total 	+= $real;
						$real2_total 	+= $real2;
						$penc = 0;
						if($real) $penc = ($renc/$real)*100;
						$pert = 0;
						if($real2) $pert = (($real-$real2)/$real2)*100;

						$dt_nilai = get_nilai($penc,$nilai,$v['struktur_cabang'],$v3->coa);

						$item .= '<td class="text-right">'.custom_format(view_report($real2)).'</td>';
						$item .= '<td class="text-right">'.custom_format(view_report($renc)).'</td>';
						$item .= '<td class="text-right">'.custom_format(view_report($real)).'</td>';
						$item .= '<td class="text-right">'.custom_format($penc,false,2).'</td>';
						$item .= '<td class="text-right">'.custom_format($pert,false,2).'</td>';
						$item .= '<td class="text-center">'.$dt_nilai['nilai'].'</td>';
					}
					if(count($v2)>1):
						$no_coa++;
						if($no_coa != 1) $item .= '<td class="border-none bg-white"></td>';
						$penc = 0;
						if($real_total) $penc = ($renc_total/$real_total)*100;
						$pert = 0;
						if($real2_total) $pert = (($real_total-$real2_total)/$real2_total)*100;

						$dt_nilai = get_nilai($penc,$nilai,$v['struktur_cabang'],$k2);

						$item .= '<td class="text-right">'.custom_format(view_report($real2_total)).'</td>';
						$item .= '<td class="text-right">'.custom_format(view_report($renc_total)).'</td>';
						$item .= '<td class="text-right">'.custom_format(view_report($real_total)).'</td>';
						$item .= '<td class="text-right">'.custom_format($penc,false,2).'</td>';
						$item .= '<td class="text-right">'.custom_format($pert,false,2).'</td>';
						$item .= '<td class="text-center">'.$dt_nilai['nilai'].'</td>';
					endif;
				}
				$item .= '</tr>';
			}
		endif;

		return [
			'status' => $status,
			'item'	 => $item,
			'no'	 => $no,
			'dt'	 => $dt,
		];
	}
	function get_nilai($p1,$nilai,$p2,$p3){
		// get_nilai($penc,$nilai,$v['struktur_cabang'],$v3->coa);
		$CI        	   = get_instance();
		$data['nilai'] = '';
		$data['warna'] = '';
		foreach ($nilai as $k => $v) {
			$formula = $v['formula'];
			$formula = str_replace('$$value', $p1, $formula);
			$condition = "return ".$formula.";";
			$res = eval($condition);
			if($res):
				$warna = '#ccc';
				if($v['warna']) $warna = $v['warna'];
				$data['nilai'] = $v['nama'].' <div class="float-left"><span class="color" style="background-color:'.$warna.'"></span></div>';
				$id = $v['id'];
				if(isset($CI->total_nilai[$p2][$p3][$id])){ $CI->total_nilai[$p2][$p3][$id] += 1; }else{ $CI->total_nilai[$p2][$p3][$id] = 1; }
			endif;
		}
		return $data;
	}

	$item_header_nilai = '<tr>';
	$item_header_nilai .= '<th>'.lang('no').'</th>';
	$item_header_nilai .= '<th class="mw-150">'.lang('kantor').'</th>';

	$item_header_nilai2 = '<tr>';
	$item_header_nilai2 .= '<th></th>';
	$item_header_nilai2 .= '<th></th>';
	$no = 0;
	foreach ($group as $k => $v) {
		foreach ($v as $k2 => $v2) {
			$no++;
			if($no != 1) $item_header_nilai .= '<th class="border-none bg-white mw-100"></th>';
			if($no != 1) $item_header_nilai2 .= '<th class="border-none bg-white mw-100"></th>';
			$item_header_nilai .= '<th class="text-center" colspan="'.count($nilai).'">'.$v2->coa.' - '.remove_spaces($v2->glwdes).'</th>';
			foreach ($nilai as $k3 => $v3) {
				$item_header_nilai2 .= '<th class="text-center mw-100">'.$v3['nama'].'</tg>';
			}
		}
		if(count($v)>1):
			$no++;
			if($no != 1) $item_header_nilai .= '<th class="border-none bg-white mw-100"></th>';
			if($no != 1) $item_header_nilai2 .= '<th class="border-none bg-white mw-100"></th>';
			$item_header_nilai .= '<th class="text-center" colspan="'.count($nilai).'">Total '.$k.'</th>';
			foreach ($nilai as $k3 => $v3) {
				$item_header_nilai2 .= '<th class="text-center mw-100">'.$v3['nama'].'</tg>';
			}
		endif;
	}
	$item_header_nilai .= '</tr>';
	$item_header_nilai2 .= '</tr>';

	$item_nilai = '';
	$no = 0;
	$total_nilai = $CI->total_nilai;
	foreach ($total_nilai as $k => $v) {
		$no++;
		$item_nilai .= '<tr>';
		$item_nilai .= '<td>'.$no.'</td>';
		$item_nilai .= '<td>'.$k.'</td>';
		$no_coa = 0;
		foreach ($group as $k2 => $v2) {
			foreach ($v2 as $k3 => $v3) {
				$no_coa++;
				if($no_coa != 1) $item_nilai .= '<td class="border-none bg-white"></td>';
				foreach ($nilai as $k4 => $v4) {
					$n = 0;
					$coa = $v3->coa;
					if(isset($v[$coa][$v4['id']])) $n = $v[$coa][$v4['id']]; $item_nilai .= '<td class="text-center">'.custom_format($n).'</td>';
				}
			}
			if(count($v2)>1):
				$no_coa++;
				if($no_coa != 1) $item_nilai .= '<td class="border-none bg-white"></td>';
				foreach ($nilai as $k4 => $v4) {
					$n = 0;
					$coa = $k2;
					if(isset($v[$coa][$v4['id']])) $n = $v[$coa][$v4['id']]; $item_nilai .= '<td class="text-center">'.custom_format($n).'</td>';
				}
			endif;
		}
		$item_nilai .= '</tr>';
	}
?>
<div class="col-sm-12 col-12 d-content" id="d-<?= $cabang->kode_cabang.'-'.$bulan ?>">
	<div class="card">
		<div class="card-header text-center">
			KINERJA OPERASIONAL <?= $cabang->nama_cabang ?> Bank Jateng <br>
			<?= $anggaran->keterangan ?><br>
			Bulan <?= month_lang($bulan) ?><br>
			(<?= get_view_report() ?>)
		</div>
		<div class="card-body">
			<div class="table-responsive tab-pane fade active show height-window" data-height="100">
				<table class="table table-striped table-bordered table-app table-hover">
					<thead>
					<?= $item_header.$item_header2.$item_header3 ?>
					</thead>
					<tbody><?= $item ?></tbody>
				</table>
			</div>
		</div>	
	</div>
	<div class="card mt-3">
		<div class="card-header text-center">
			PENCAPAIAN DARI RENCANA BERDASARKAN KANTOR CABANG
		</div>
		<div class="card-body">
			<div class="table-responsive tab-pane fade active show">
				<table class="table table-striped table-bordered table-app table-hover">
					<thead>
					<?= $item_header_nilai.$item_header_nilai2 ?>
					</thead>
					<tbody><?= $item_nilai ?></tbody>
				</table>
			</div>
		</div>
	</div>
</div>