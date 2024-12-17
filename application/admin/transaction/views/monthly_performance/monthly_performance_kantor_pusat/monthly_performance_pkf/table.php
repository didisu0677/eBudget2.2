<?php
	if(count($list)>0):
     	$item = '';
     	foreach ($list as $k => $v) {
			$item .= '<tr>';
			$item .= '<td>'.($k+1).'</td>';
			$item .= '<td>'.$v->id.'</td>';
			$item .= '<td>'.$v->program_kerja.'</td>';
			$item .= '<td></td>';
			$item .= '<td></td>';
			$item .= '<td></td>';

		}
		echo $item;
	else:
		echo '<tr><th colspan="6" class="text-center">Data Not Found</th></tr>';
	endif;
?>