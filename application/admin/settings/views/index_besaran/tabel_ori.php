<?php
	$item = '';
	$data = [
		'dSum'	=> $dSum,
		'kolom'	=> $kolom,
		'anggaran'	=> $anggaran,
		'coa'		=> $coa,
	];
	foreach ($cabang[0] as $v) {
		$item .= '<tr>';
		$item .= '<td class="bg-c1">'.$v->nama_cabang.'</td>';
		
		$dt_loop = loop($v->id,$cabang,1,$data);
		$dtSaved = [];
		foreach ($kolom as $k2 => $v2) {
			$field = 'B_' . sprintf("%02d", $v2->bulan);
			$field1= 'ori'.$v2->bulan;
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
			$dtSaved[$v2->tahun][$field1] = $val;
		}
		$where = [
			'kode_cabang' 	=> $v->kode_cabang,
			'kode_anggaran'	=> $anggaran->kode_anggaran,
			'tahun'			=> $anggaran->tahun_anggaran,
			'coa'			=> $coa,

		];
		if(!$dt_loop['status']):
			checkForSaved($dtSaved,$where);
		endif;

		$item .= '</tr>';
		$item .= $dt_loop['item'];

	}
	echo $item;

	function loop($id,$cabang,$count,$data){
		$dSum 	= $data['dSum'];
		$kolom	= $data['kolom'];
		$anggaran	= $data['anggaran'];
		$coa		= $data['coa'];

		$status = false;
		$item 	= '';
		$dt 	= [];
		if(isset($cabang[$id]) && count($cabang[$id])>0):
			$status = true;
			foreach ($cabang[$id] as $k => $v) {
				$item .= '<tr>';
				$item .= '<td class="sub-'.$count.' bg-c'.($count+1).'">'.$v->nama_cabang.'</td>';
				
				$dt_loop = loop($v->id,$cabang,($count+1),$data);
				$dtSaved = [];
				foreach ($kolom as $k2 => $v2) {
					$field = 'B_' . sprintf("%02d", $v2->bulan);
					$field1= 'ori'.$v2->bulan;
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
					$dtSaved[$v2->tahun][$field1] = $val;
				}
				$where = [
					'kode_cabang' 	=> $v->kode_cabang,
					'kode_anggaran'	=> $anggaran->kode_anggaran,
					'tahun'			=> $anggaran->tahun_anggaran,
					'coa'			=> $coa,

				];
				if(!$dt_loop['status']):
					checkForSaved($dtSaved,$where);
				endif;

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

	function checkForSaved($data,$p1){
		foreach($data as $k => $v){
			$ck = get_data('tbl_indek_besaran',[
				'select' => 'id',
				'where'	 => [
					'kode_cabang'	=> $p1['kode_cabang'],
					'kode_anggaran'	=> $p1['kode_anggaran'],
					'coa'			=> $p1['coa'],
					'tahun_core'	=> $k
				]
			])->row();
			if($ck):
				update_data('tbl_indek_besaran',$v,'id',$ck->id);
			else:
				$v['kode_anggaran'] = $p1['kode_anggaran'];
				$v['kode_cabang'] 	= $p1['kode_cabang'];
				$v['coa']			= $p1['coa'];
				$v['tahun_core']	= $k;
				$parent_id = '0';
				if($k != $p1['tahun']):
					$parent_id = $p1['kode_cabang'];
				endif;
				$v['parent_id'] = $parent_id;
				$v['is_active']	= 1;
				insert_data('tbl_indek_besaran',$v);
			endif;
		}
	}
?>