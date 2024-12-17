<?php
	$item = '';
	$data = [
		'access'	=> $access,
	];
	foreach ($neraca['coa'] as $k => $v) {
		$minus = '<span class="badge badge-success">TRUE</span>';
		if(!$v->kali_minus) $minus = '<span class="badge badge-danger">FALSE</span>';
		$active = '<span class="badge badge-success">TRUE</span>';
		if(!$v->is_active) $active = '<span class="badge badge-danger">FALSE</span>';

		$item .= '<tr>';
		$item .= '<td>'.remove_spaces($v->glwsbi).'</td>';
		$item .= '<td>'.remove_spaces($v->glwnob).'</td>';
		// $item .= '<td>'.remove_spaces($v->glwcoa).'</td>';
		$item .= '<td>'.remove_spaces($v->glwnco).'</td>';
		$item .= '<td>'.remove_spaces($v->glwdes).'</td>';
		$item .= '<td class="text-center">'.$minus.'</td>';
		$item .= '<td class="text-center">'.$active.'</td>';
		$item .= '<td class="button">';
		$item .= '<button type="button" class="btn btn-info btn-view" data-key="view" data-id="'.$v->id.'" title="'.lang('detil').'"><i class="fa-search"></i></button>';
		if($access['access_edit']) $item .= '<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="'.$v->id.'" title="'.lang('ubah').'"><i class="fa-edit"></i></button>';
		if($access['access_delete']) $item .= '<button type="button" class="btn btn-danger btn-delete" data-key="delete" data-id="'.$v->id.'" title="'.lang('hapus').'"><i class="fa-trash-alt"></i></button>';
		$item .= '</td>';
		$item .= '</tr>';

		$dt_more = more($v->glwnco,0,$neraca,$data);
		if($dt_more['status']):
			$item .= $dt_more['item'];
		endif;

	}

	foreach ($labarugi['coa'] as $k => $v) {
		$minus = '<span class="badge badge-success">TRUE</span>';
		if(!$v->kali_minus) $minus = '<span class="badge badge-danger">FALSE</span>';
		$active = '<span class="badge badge-success">TRUE</span>';
		if(!$v->is_active) $active = '<span class="badge badge-danger">FALSE</span>';

		$item .= '<tr>';
		$item .= '<td>'.remove_spaces($v->glwsbi).'</td>';
		$item .= '<td>'.remove_spaces($v->glwnob).'</td>';
		// $item .= '<td>'.remove_spaces($v->glwcoa).'</td>';
		$item .= '<td>'.remove_spaces($v->glwnco).'</td>';
		$item .= '<td>'.remove_spaces($v->glwdes).'</td>';
		$item .= '<td class="text-center">'.$minus.'</td>';
		$item .= '<td class="text-center">'.$active.'</td>';
		$item .= '<td class="button">';
		$item .= '<button type="button" class="btn btn-info btn-view" data-key="view" data-id="'.$v->id.'" title="'.lang('detil').'"><i class="fa-search"></i></button>';
		if($access['access_edit']) $item .= '<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="'.$v->id.'" title="'.lang('ubah').'"><i class="fa-edit"></i></button>';
		if($access['access_delete']) $item .= '<button type="button" class="btn btn-danger btn-delete" data-key="delete" data-id="'.$v->id.'" title="'.lang('hapus').'"><i class="fa-trash-alt"></i></button>';
		$item .= '</td>';
		$item .= '</tr>';

		$dt_more = more($v->glwnco,0,$labarugi,$data);
		if($dt_more['status']):
			$item .= $dt_more['item'];
		endif;

	}

	echo $item;

	function more($id,$count,$coa,$data){
		$access = $data['access'];

		$item 		= '';
		$status		= false;
		if(isset($coa['coa'.$count][$id])):
			$status = true;
			$count2 = $count + 1;
			foreach ($coa['coa'.$count][$id] as $k => $v) {
				$minus = '<span class="badge badge-success">TRUE</span>';
				if(!$v->kali_minus) $minus = '<span class="badge badge-danger">FALSE</span>';
				$active = '<span class="badge badge-success">TRUE</span>';
				if(!$v->is_active) $active = '<span class="badge badge-danger">FALSE</span>';

				$item .= '<tr>';
				$item .= '<td>'.remove_spaces($v->glwsbi).'</td>';
				$item .= '<td>'.remove_spaces($v->glwnob).'</td>';
				// $item .= '<td>'.remove_spaces($v->glwcoa).'</td>';
				$item .= '<td>'.remove_spaces($v->glwnco).'</td>';
				$item .= '<td class="sb-'.$count2.'">'.remove_spaces($v->glwdes).'</td>';
				$item .= '<td class="text-center">'.$minus.'</td>';
				$item .= '<td class="text-center">'.$active.'</td>';
				$item .= '<td class="button">';
				$item .= '<button type="button" class="btn btn-info btn-view" data-key="view" data-id="'.$v->id.'" title="'.lang('detil').'"><i class="fa-search"></i></button>';
				if($access['access_edit']) $item .= '<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="'.$v->id.'" title="'.lang('ubah').'"><i class="fa-edit"></i></button>';
				if($access['access_delete']) $item .= '<button type="button" class="btn btn-danger btn-delete" data-key="delete" data-id="'.$v->id.'" title="'.lang('hapus').'"><i class="fa-trash-alt"></i></button>';
				$item .= '</td>';
				$item .= '</tr>';

				$dt_more = more($v->glwnco,$count2,$coa,$data);
				if($dt_more['status']):
					$item .= $dt_more['item'];
				endif;
			}
		endif;

		return [
			'status' => $status,
			'item'	 => $item,
		];
	}

	function more_option($id,$count,$coa){
		if(isset($coa['coa'.$count][$id])):
			$count2 = $count + 1;
			$sb = '';
			for ($i=1; $i <= $count; $i++) { 
				$sb .= '&nbsp; ';
			}
			$sb .= '|-----';
			foreach ($coa['coa'.$count][$id] as $k => $v) {
				option($v->id,$sb.$v->glwnco.' - '.remove_spaces($v->glwdes));
				more_option($v->glwnco,$count2,$coa);
			}
		endif;
	}
?>