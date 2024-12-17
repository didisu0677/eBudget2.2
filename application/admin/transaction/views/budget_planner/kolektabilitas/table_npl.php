<?php
	$item = '';
	if(count($list)>0):
		foreach ($list as $k => $v) {
			$bgedit ="";
			$contentedit ="false" ;
			$id = 'keterangan';
			if($akses_ubah == 1) {
				$bgedit = bgEdit();
				$contentedit ="true" ;
				$id = 'id' ;
			}

			$item .= '<tr>';
			$item .= '<td>'.arrNpl()[$v->tipe].'</td>';
			foreach ($detail_tahun as $k2 => $v2) {
				$v_field  = 'B_' . sprintf("%02d", $v2->bulan);
				$key = multidimensional_search($all, array(
					'kode_cabang'	=>$v->kode_cabang,
					'tahun_core'	=> $v2->tahun,
					'tipe'			=> $v->tipe,
				));
				$d = $all[$key];
				if($d):
					$value 	= $d[$v_field];
					$id  	= $d['id']."-tbl_kolektibilitas_npl";
					$item .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="'.$v_field.'" data-id="'.$id.'" data-value="'.$value.'">'.custom_format($value,false,2).'</div></td>';
				endif;
			}
			$item .= '</tr>';
		}
	else:
		$item .= '<tr><th colspan="'.(12+1).'" class="text-center">Data Not Found</th></tr>';
	endif;
	echo $item;
?>