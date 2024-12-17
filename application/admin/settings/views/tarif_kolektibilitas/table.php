<?php
$item = '<tr><th colspan="11">'.$grup_name.'</th></tr>';
$no = 0;
foreach ($data as $k => $v) {
	if($grup == $v->grup):
		$no++;
		$item .= '<tr>';
		$item .= '<td data-rowid="'.$v->id.'"><div class="custom-checkbox custom-control"><input class="custom-control-input" type="checkbox" id="chk-td-'.$k.'" value="'.$v->id.'"><label class="custom-control-label" for="chk-td-'.$k.'">&nbsp;</label></div></td>';
		$item .= '<td>'.$no.'</td>';
		$item .= '<td>'.$v->coa.'</td>';
		$item .= '<td>'.remove_spaces($v->nama).'</td>';
		$item .= '<td>'.$v->kol_1.'</td>';
		$item .= '<td>'.$v->kol_2.'</td>';
		$item .= '<td>'.$v->kol_3.'</td>';
		$item .= '<td>'.$v->kol_4.'</td>';
		$item .= '<td>'.$v->kol_5.'</td>';
		if($v->is_active):
			$is_active = '<span class="badge badge-success">TRUE</span>';
		else:
			$is_active = '<span class="badge badge-danger">FALSE</span>';
		endif;
		$item .= '<td class="text-center">'.$is_active.'</td>';
		$item .= '<td class="button">';
		if($akses['access_edit'] != 0):
			$item .= '<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="'.$v->id.'" title="'.lang('ubah').'"><i class="fa-edit"></i></button>';
		endif;
		if($akses['access_delete'] != 0):
			$item .= '<button type="button" class="btn btn-danger btn-delete" data-key="delete" data-id="'.$v->id.'" title="'.lang('hapus').'"><i class="fa-trash-alt"></i></button>';
		endif;
		$item .= '</td>';
		$item .= '</tr>';
	endif;
}
echo $item;
?>