<?php
	$item = '';
	// header
	$item_header = '<thead>';
	$header1 	 = '<tr>';
	$header1 .= '<th class="align-middle text-center wd-100"></th>';
	$header1 .= '<th class="align-middle text-center wd-100"></th>';
	$header1 .= '<th class="align-middle text-center wd-100"></th>';
	$header1 .= '<th class="align-middle text-center wd-100"></th>';

	$header2 = '<tr>';
	$header2 .= '<th class="align-middle text-center wd-100">SANDI BI</th>';
	$header2 .= '<th class="align-middle text-center wd-100">COA 5</th>';
	$header2 .= '<th class="align-middle text-center wd-100">COA 7</th>';
	$header2 .= '<th class="align-middle text-center wd-230">Keterangan</th>';
	foreach ($ls_cabang as $k => $v) {
		$count = 0;
		if($k != 0):
			$header1 .= '<th class="border-none bg-white wd-100"></th>';
			$header2 .= '<th class="border-none bg-white wd-100"></th>';
		endif;
		foreach ($detail_tahun as $k2 => $v2) {
			if($v2->tahun == $anggaran->tahun_anggaran):
				$count++;
				$column = month_lang($v2->bulan).' '.$v2->tahun;
				$column .= '<br> ('.$v2->singkatan.')';
				$header2 .= '<th class="wd-150 text-center">'.$column.'</th>';
			endif;
		}
		$header1 .= '<th colspan="'.$count.'">'.$v['nama_cabang'].'</th>';
	}
	$header1 .= '</tr>';
	$header2 .= '<tr>';

	$item_header .= $header1;
	$item_header .= $header2;
	$item_header .= '</thead>';

	$item = '';
	$data = [
		'ls_data' 	=> $ls_data,
		'anggaran'	=> $anggaran,
		'detail_tahun' => $detail_tahun,
		'ls_cabang' => $ls_cabang,
	];
	foreach ($coa['coa'] as $k => $v) {
		$item2 = '';
		$dt_loop = loop($v->glwnco,$coa,$data,0);
		if($dt_loop['status']):
			$item2 .= $dt_loop['item'];
		endif;

		$item .= '<tr>';
		$item .= '<td>'.$v->glwsbi.'</td>';
		$item .= '<td>'.$v->glwnob.'</td>';
		$item .= '<td>'.$v->glwnco.'</td>';
		$item .= '<td>'.remove_spaces($v->glwdes).'</td>';
			
		foreach ($data['ls_cabang'] as $k2 => $v2) {
			$x = $v2['kode_cabang'];
			if($k2 != 0):
				$item .= '<td class="border-none bg-white"></td>';
			endif;
			if(!$dt_loop['status']):
				$key = multidimensional_search($ls_data[$v2['kode_cabang']], array(
                    'coa' => $v->glwnco,
                ));
			endif;
			foreach ($detail_tahun as $k3 => $v3) {
				if($v3->tahun == $anggaran->tahun_anggaran):
					$field = 'B_'.sprintf("%02d", $v3->bulan);
					if($dt_loop['status']):
						$val = $dt_loop['dt'][$x][$field];
					else:
						if(strlen($key)>0):
							$val = $ls_data[$x][$key][$field];
						else:
							$val = 0;
						endif;
					endif;
					$item .= '<td class="text-right">'.custom_format(view_report($val)).'</td>';
					if(isset($dt[$x][$field])): $dt[$x][$field] += $val; else: $dt[$x][$field] = $val; endif;
				endif;
			}
		}

		$item .= '</tr>';
		$item .= $item2;
	}

	echo $item_header;
	echo $item;

	function loop($id,$ls_coa,$data,$count){
		$anggaran 		= $data['anggaran'];
		$detail_tahun 	= $data['detail_tahun'];
		$ls_data 		= $data['ls_data'];
		$status = false;
		$item 	= '';
		$dt 	= [];
		if(isset($ls_coa['coa'.$count][$id])):
			$status = true;
			foreach ($ls_coa['coa'.$count][$id] as $k => $v) {
				$item2 = '';
				$dt_loop = loop($v->glwnco,$ls_coa,$data,($count+1));
				if($dt_loop['status']):
					$item2 .= $dt_loop['item'];
				endif;

				$item .= '<tr>';
				$item .= '<td>'.$v->glwsbi.'</td>';
				$item .= '<td>'.$v->glwnob.'</td>';
				$item .= '<td>'.$v->glwnco.'</td>';
				$item .= '<td class="sb-'.($count+1).'">'.remove_spaces($v->glwdes).'</td>';

				foreach ($data['ls_cabang'] as $k2 => $v2) {
					$x = $v2['kode_cabang'];
					if($k2 != 0):
						$item .= '<td class="border-none bg-white"></td>';
					endif;
					if(!$dt_loop['status']):
						$key = multidimensional_search($ls_data[$x], array(
		                    'coa' => $v->glwnco,
		                ));
					endif;
					foreach ($detail_tahun as $k3 => $v3) {
						if($v3->tahun == $anggaran->tahun_anggaran):
							$field = 'B_'.sprintf("%02d", $v3->bulan);
							if($dt_loop['status']):
								$val = $dt_loop['dt'][$x][$field];
							else:
								if(strlen($key)>0):
									$val = $ls_data[$x][$key][$field];
								else:
									$val = 0;
								endif;
							endif;
							$item .= '<td class="text-right">'.custom_format(view_report($val)).'</td>';
							if(isset($dt[$x][$field])): $dt[$x][$field] += $val; else: $dt[$x][$field] = $val; endif;
						endif;
					}
				}

				$item .= '</tr>';
				$item .= $item2;
			}
		endif;

		return [
			'status' => $status,
			'item'	 => $item,
			'dt'     => $dt,
		];
	}
?>