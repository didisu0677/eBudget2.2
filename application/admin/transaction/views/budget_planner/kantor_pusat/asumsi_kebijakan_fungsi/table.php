<?php
	$item = '';
	foreach ($kebijakan_fungsi as $a) {
		$item .= '<tr>';
		$item .= '<th colspan="7" class="bg-1">'.$a->nama.'</th>';
		$item .= '</tt>';
		$no = 0;
		foreach ($list as $k => $v) {
			if($a->id == $v->id_kebijakan_fungsi):
				$no ++;
				$item .= '<tr>';
				$item .= '<td>'.$no.'</td>';
				$item .= '<td>'.$v->kebijakan_fungsi.'</td>';
				$item .= '<td>'.$v->uraian.'</td>';
				$item .= '<td>'.custom_format(view_report($v->anggaran)).'</td>';
				$item .= '<td>'.$v->kantor_cabang.'</td>';
				$item .= '<td>'.$v->pelaksanaan.'</td>';
				if($akses_ubah):
					$item .= '<td class="button"><button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="'.$v->id.'" title="'.lang('ubah').'"><i class="fa-edit"></i></button></td>';
				else:
					$item .= '<td></td>';
				endif;
				$item .= '</tr>';
			endif;
		}
	}
	echo $item;
?>