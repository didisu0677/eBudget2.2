<?php
	$item = '';
	foreach ($data as $k => $v) {
		$item .= '<tr>';
		$item .= '<td>'.($k+1).'</td>';
		$item .= '<td>'.$v->cabang_induk.'</td>';
		$item .= '<td>'.$v->nama_cabang.'</td>';
		$item .= '<td>'.$v->rencana_jarkan.'</td>';
		$item .= '<td>'.$v->tahapan_pengembangan.'</td>';
		$item .= '<td>'.$v->kategori_kantor.'</td>';
		$item .= '<td class="text-right">'.custom_format(view_report($v->harga)).'</td>';
		$item .= '<td>'.$v->nama_kantor.'</td>';
		$item .= '<td>'.month_lang($v->jadwal).'</td>';
		$item .= '<td>'.$v->status_ket_kantor.'</td>';
		$item .= '<td>'.$v->kecamatan.',</br>'.$v->kota.'</td>';
		$item .= '<td>'.$v->penjelasan.'</td>';
		$item .= '<td>'.$v->nama_keterangan.'</td>';
		$item .= '<td class="text-center align-middle"><span class="color" style="height: 15px;width: 15px;border: 1px solid #6c757d;background-color:'.$v->warna_keterangan.'"></span></td>';
		$item .= '</tr>';
	}
	if(count($data)<=0):
		$item .= '<tr><td colspan="15">'.lang('data_not_found').'</td></tr>';
	endif;
	echo $item;
?>