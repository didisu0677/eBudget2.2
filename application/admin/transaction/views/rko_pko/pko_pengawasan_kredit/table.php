<?php
	$item = '';
	foreach ($list as $k => $v) {
		$checkbox = json_decode($v->checkbox,true);
		$item .= '<tr>';
		$item .= '<td>'.($k+1).'</td>';
		$item .= '<td>'.$v->keterangan.'</td>';
		$item .= '<td>'.$v->skala_program_name.'</td>';
		$item .= '<td class="text-right">'.custom_format(view_report($v->target)).'</td>';
		$item .= '<td>'.$v->tujuan.'</td>';
		$item .= '<td>'.$v->output.'</td>';
		$item .= '<td>'.$v->pic.'</td>';
		foreach ($arrWeekOfMonth['week'] as $k2 => $v2) {
			$d = $arrWeekOfMonth['detail'][$v2];
			$x = explode("-", $d);
			$key = $x[0];
			$disabled = '';
			if(!$access_edit){ $disabled = ' disabled'; }
			if(isset($checkbox[$key]) && $checkbox[$key] == 1):
				$item .= '<td><div class="custom-checkbox custom-control">
					<input class="custom-control-input d-checkbox" type="checkbox" id="ck-'.$v->id.'-'.$key.'" value="1" checked'.$disabled.'><label class="custom-control-label" for="ck-'.$v->id.'-'.$key.'">&nbsp;</label>
					</div></td>';
			else:
				$item .= '<td><div class="custom-checkbox custom-control">
					<input class="custom-control-input d-checkbox" type="checkbox" id="ck-'.$v->id.'-'.$key.'" value="1"'.$disabled.'><label class="custom-control-label" for="ck-'.$v->id.'-'.$key.'">&nbsp;</label>
					</div></td>';
			endif;
		}
		$item .= '<td class="button">';
		if($access_edit):
			$item .= '<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="'.$v->id.'" title="'.lang('ubah').'"><i class="fa-edit"></i></button>';
		endif;
		if($access_delete):
			$item .= '<button type="button" class="btn btn-danger btn-delete" data-key="delete" data-id="'.$v->id.'" title="'.lang('hapus').'"><i class="fa-trash-alt"></i></button>';
		endif;
		$item .= '</td>';
		$item .= '</tr>';
	}
	if(count($list)<=0):
		$item .= '<tr><th colspan="'.(count($arrWeekOfMonth['week'])+10).'">'.lang('data_not_found').'</th></tr>';
	endif;
	echo $item;
?>