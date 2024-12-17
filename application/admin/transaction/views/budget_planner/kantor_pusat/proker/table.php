<?php
	$item = '';
	if(count($list)>0):
		foreach ($list as $k => $v) {
			$bgedit ="";
			$contentedit ="false" ;
			$id = 'keterangan';
			if($akses_ubah) {
				$bgedit =bgEdit();
				$contentedit ="true" ;
				$id = 'id' ;
			}
			$coa_name = '-';
			if($contentedit == "true" && $v->anggaran == 1):
				$item_coa = '<option></option>';
				foreach ($coa_list as $k2 => $v2) {
					$selected_coa = '';
					if($v2->glwnco == $v->coa){ $selected_coa = ' selected'; }
					$item_coa .= '<option'.$selected_coa.' value="'.$v2->glwnco.'">'.$v2->glwnco.' - '.remove_spaces($v2->glwdes).'</option>';
				}
				$coa_name = '<select data-id="'.$v->id.'" data-selected="'.$v->coa.'" data-name="coa" class="form-control select2 custom-select item-coa">'.$item_coa.'</select>';
			elseif($v->glwnco):
				$coa_name = $v->glwnco.' - '.$v->glwdes;
			endif;

			$item .= '<tr>';
			$item .= '<td>'.($k+1).'</td>';
			$item .= '<td>'.$v->kebijakan_umum.'</td>';
			$item .= '<td>'.$v->program_kerja.'</td>';
			$item .= '<td>'.$coa_name.'</td>';
			if($v->anggaran == 1):
				for ($i = 1; $i <= 12; $i++) { 
					$v_field 	= 'T_'.sprintf("%02d", $i);
					$value 		= $v->{$v_field};
					
					$item .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="'.$v_field.'" data-id="'.$v->id.'" data-value="'.$value.'">'.custom_format(view_report($value)).'</div></td>';		
				}
			else:
				$item .= '<td colspan="12"></td>';
			endif;
			$item .= '</tr>';
		}
	else:
		$item .= '<tr><th colspan="'.(12+4).'">Data Not Found</th></tr>';
	endif;
	echo $item;
?>