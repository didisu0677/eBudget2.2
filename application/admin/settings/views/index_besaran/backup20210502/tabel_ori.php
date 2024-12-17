<?php
	$item = '';
	$data = [
		'dSum'	=> $dSum,
		'kolom'	=> $kolom,
	];
	foreach ($cabang[0] as $v) {
		$item .= '<tr>';
		$item .= '<td class="bg-c1">'.$v->nama_cabang.'</td>';
		
		$dt_loop = loop($v->id,$cabang,1,$data);
		foreach ($kolom as $k2 => $v2) {
			$field = 'B_' . sprintf("%02d", $v2->bulan);
			$val   = 0;
			if($dt_loop['status']):
				$val = $dt_loop['dt'][$v2->tahun][$field];
			else:
				$key = multidimensional_search($dSum, array(
					'kode_cabang'=>$v->kode_cabang,
					'sumber_data'=> $v2->sumber_data,
					'data_core'	=> $v2->tahun
				));
				if($v2->sumber_data == 2):
					if(strlen($key)<=0):
						$key = multidimensional_search($dSum, array(
							'kode_cabang'=>$v->kode_cabang,
							'sumber_data'=> 1,
							'data_core'	=> $v2->tahun
						));
					endif;
				endif;
				if(strlen($key)>0):
					$val = $dSum[$key][$field];
				endif;
			endif;
			$item .= '<td class="text-right">'.custom_format(view_report($val)).'</td>';
		}

		$item .= '</tr>';
		$item .= $dt_loop['item'];

	}
	echo $item;

	function loop($id,$cabang,$count,$data){
		$dSum 	= $data['dSum'];
		$kolom	= $data['kolom'];

		$status = false;
		$item 	= '';
		$dt 	= [];
		if(isset($cabang[$id]) && count($cabang[$id])>0):
			$status = true;
			foreach ($cabang[$id] as $k => $v) {
				$item .= '<tr>';
				$item .= '<td class="sub-'.$count.' bg-c'.($count+1).'">'.$v->nama_cabang.'</td>';
				
				$dt_loop = loop($v->id,$cabang,($count+1),$data);
				foreach ($kolom as $k2 => $v2) {
					$field = 'B_' . sprintf("%02d", $v2->bulan);
					$val   = 0;
					if($dt_loop['status']):
						$val = $dt_loop['dt'][$v2->tahun][$field];
					else:
						$key = multidimensional_search($dSum, array(
							'kode_cabang'=>$v->kode_cabang,
							'sumber_data'=> $v2->sumber_data,
							'data_core'	=> $v2->tahun
						));
						if($v2->sumber_data == 2):
							if(strlen($key)<=0):
								$key = multidimensional_search($dSum, array(
									'kode_cabang'=>$v->kode_cabang,
									'sumber_data'=> 1,
									'data_core'	=> $v2->tahun
								));
							endif;
						endif;
						if(strlen($key)>0):
							$val = $dSum[$key][$field];
						endif;
					endif;
					$item .= '<td class="text-right">'.custom_format(view_report($val)).'</td>';
					if(isset($dt[$v2->tahun][$field])): $dt[$v2->tahun][$field] += $val; else: $dt[$v2->tahun][$field] = $val; endif;
				}

				$item .= '</tr>';
				$item .= $dt_loop['item'];
			}
		endif;

		return [
			'status' => $status,
			'item'	 => $item,
			'dt'	 => $dt,
		];
	}
?>