<?php
	$item = '';
	$i  = 0;
	if(count($header)>0):
		foreach ($header as $h) {
			$rowspan = 0;
			$no = 0;
			$i += 1;
			foreach ($list as $k => $v) {
				
				if($v->name == $h):
					$no += 1;
					$item .= '<tr>';
					if($no == 1):
						$name = str_replace(' ', '_', $h);
						$item .= '<td rowspan='.${'count_'.$name}.'>'.$i.'</td>';
						$item .= '<td rowspan='.${'count_'.$name}.'>'.$v->name.'</td>';
					endif;
					$item .= '<td>'.$v->aktivitas.'</td>';
					$item .= '<td>'.$v->target.'</td>';
					$item .= '<td>'.$v->keterangan.'</td>';
					$item .= '<td>'.$v->tanggal_target.'</td>';
					$item .= '<td>'.$v->goal.'</td>';
					$item .= '<td class="button"><button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="'.$v->id.'" title="'.lang('ubah').'"><i class="fa-edit"></i></button></td>';
					$item .= '</tr>';
				endif;
			}

		}
	else:
		$item = '<tr><th colspan="7"><div class="text-center">Data Not Found<div></th></tr>';
	endif;
	echo $item;
?>