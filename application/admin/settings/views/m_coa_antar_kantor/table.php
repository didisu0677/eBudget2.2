<?php
	$item = '';
	if(count($header)<=0):
		$item = '<tr><td class="text-center" colspan="5"><h5>'.lang('data_not_found').'</h5></td></tr>';
	else:
		foreach ($header as $no => $i) {
			$coa = $i['coa'];
			foreach ($data[$coa] as $k => $v) {
				$item .= '<tr>';
				if($k == 0):
					$item .= '<td rowspan="'.count($data[$coa]).'">'.($no+1).'</td>';
					$item .= '<td rowspan="'.count($data[$coa]).'">'.$v->coa.' - '.remove_spaces($v->coa_name).'</td>';
				endif;
				$item .= '<td>'.$v->coa_lawan.' - '.remove_spaces($v->coa_lawan_name).'</td>';
				$item .= '<td class="button">';
					if($access['access_edit']):
						$item .= '<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="'.$v->id.'" title="'.lang('ubah').'"><i class="fa-edit"></i></button>';
					endif;
					if($access['access_delete']):
						$item .= '<button type="button" class="btn btn-danger btn-delete" data-key="delete" data-id="'.$v->id.'" title="'.lang('hapus').'"><i class="fa-trash-alt"></i></button>';
					endif;
				$item .= '</td>';
				$item .= '</tr>';
			}
		}
	endif;
	echo $item;
?>