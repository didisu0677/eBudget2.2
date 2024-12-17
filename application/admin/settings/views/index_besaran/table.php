<?php
	$item = '';
	$data = [
		'dSum'	=> $dSum,
		'kolom'	=> $kolom,
		'edit'	=> $edit,
		'coa'	=> $coa,
		'anggaran' => $anggaran,
	];
	foreach ($cabang[0] as $v) {
		$item .= '<tr>';
		$item .= '<td class="bg-c1">'.$v->nama_cabang.'</td>';
		
		$dt_loop = loop($v->id,$cabang,1,$data);
		$dtSaved = [];
		foreach ($kolom as $k2 => $v2) {
			$field = 'bulan'.$v2->bulan;
			if(!$dt_loop['status']):
				$val = 1;
				$key = multidimensional_search($dSum, array(
					'kode_cabang'	=>$v->kode_cabang,
					'tahun_core'	=> $v2->tahun
				));
				if(strlen($key)>0):
					$val = $dSum[$key][$field];
				endif;
				if($edit):
					$item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right" data-name="'.$field.'" data-id="'.$v->kode_cabang.'-'.$v2->tahun.'" data-value="'.$val.'">'.$val.'</div></td>';
				else:
					$item .= '<td class="text-right">'.$val.'</td>';
				endif;
				$dtSaved[$v2->tahun][$field] = $val;
			endif;
		}

		$item .= '</tr>';
		$item .= $dt_loop['item'];
		$where = [
			'kode_cabang' 	=> $v->kode_cabang,
			'kode_anggaran'	=> $anggaran->kode_anggaran,
			'tahun'			=> $anggaran->tahun_anggaran,
			'coa'			=> $coa,

		];
		if(!$dt_loop['status']):
			checkForSaved($dtSaved,$where);
		endif;

	}
	echo $item;

	function loop($id,$cabang,$count,$data){
		$dSum 	= $data['dSum'];
		$kolom	= $data['kolom'];
		$edit	= $data['edit'];
		$coa 	= $data['coa'];
		$anggaran 	= $data['anggaran'];

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
					$field = 'bulan'.$v2->bulan;
					if(!$dt_loop['status']):
						$val = 1;
						$key = multidimensional_search($dSum, array(
							'kode_cabang'	=>$v->kode_cabang,
							'tahun_core'	=> $v2->tahun
						));
						if(strlen($key)>0):
							$val = $dSum[$key][$field];
						endif;
						if($edit):
							$item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right" data-name="'.$field.'" data-id="'.$v->kode_cabang.'-'.$v2->tahun.'" data-value="'.$val.'">'.$val.'</div></td>';
						else:
							$item .= '<td class="text-right">'.$val.'</td>';
						endif;
						$dtSaved[$v2->tahun][$field] = $val;
					endif;
				}

				$item .= '</tr>';
				$item .= $dt_loop['item'];
				$where = [
					'kode_cabang' 	=> $v->kode_cabang,
					'kode_anggaran'	=> $anggaran->kode_anggaran,
					'tahun'			=> $anggaran->tahun_anggaran,
					'coa'			=> $coa,

				];
				if(!$dt_loop['status']):
					checkForSaved($dtSaved,$where);
				endif;
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