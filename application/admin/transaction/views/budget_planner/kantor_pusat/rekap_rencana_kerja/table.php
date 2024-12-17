<?php
	if(count($list)>0):
		$item 	= '';
		$tempID = 0;
		$no 	= 0;
		foreach ($list as $k => $v) {
			if($v->id_kebijakan_umum != $tempID):
				$tempID = $v->id_kebijakan_umum;
				$no 	= 0;
				$item .= '<tr>';
				$item .= '<th class="bg-1" colspan="8">'.$v->kebijakan_umum.'</th>';
				$item .= '</tr>';
			endif;

			$anggaran = '-';
			if($v->glwdes) $anggaran = 'POS: '.$v->glwnco.' - '.remove_spaces($v->glwdes);
			$bulan 	  = '';
			$total 	  = 0;
			for ($i=1; $i <=12 ; $i++) { 
				$v_field 	= 'T_'.sprintf("%02d", $i);
				$value 		= $v->{$v_field};
				$total 		+= $value;
				if($value>0):
					$bulan .= month_lang($i).', ';
				endif;
			}
			if($total>0):
				$anggaran .= '<br>Total : '.custom_format(view_report($total));
				$bulan 	= rtrim($bulan, ", ");
			endif;

			$no++;
			$item .= '<tr>';
			$item .= '<td>'.$no.'</td>';
			$item .= '<td>'.$v->id_kebijakan_umum.'</td>';
			$item .= '<td>'.$v->program_kerja.'</td>';
			$item .= '<td>'.$v->tujuan.'</td>';
			$item .= '<td>'.$v->output.'</td>';
			$item .= '<td></td>';
			$item .= '<td>'.$anggaran.'</td>';
			$item .= '<td>'.$bulan.'</td>';
			$item .= '</tr>';
		}
		echo $item;
	else:
		echo '<tr><th colspan="11" class="text-center">Data Not Found</th></tr>';
	endif;
?>