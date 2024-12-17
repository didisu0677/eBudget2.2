<?php
	$item = '';
	$no = 0;
	foreach ($listAll as $k => $v) {
		if($v->tipe == $tipe){
			$no ++;
			$bgedit ="";
			$contentedit ="false" ;
			$id = 'keterangan';
			if($akses_ubah == 1) {
				$bgedit ="#ffbb33";
				$contentedit ="true" ;
				$id = 'id' ;
			}
			$item .= '<tr>';
			$item .= '<td>'.$no.'</td>';
			$item .= '<td>'.remove_spaces($v->nama_produk_kredit).'</td>';
			$item .= '<td>'.$v->coa.'</td>';
			if($v->default != 1):
				$item .= '<td class="button"><button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="'.$v->id.'" title="'.lang('ubah').'"><i class="fa-edit"></i></button></td>';
			else:
				$item .= '<td></td>';
			endif;
			$item .= '</tr>';
		}
	}
	if($no<=0):
		$item .= '<tr><th colspan="'.(4).'" class="text-center">Data Not Found</th></tr>';
	endif;
	echo $item;
?>