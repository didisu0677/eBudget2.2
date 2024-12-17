<?php 
	foreach($grup[0] as $m0) { 

	$no=0;
	$total_T01 = 0;
	$total_T02 = 0;
	$total_T03 = 0;
	$total_T04 = 0;
	$total_T05 = 0;
	$total_T06 = 0;
	$total_T07 = 0;
	$total_T08 = 0;
	$total_T09 = 0;
	$total_T10 = 0;
	$total_T11 = 0;
	$total_T12 = 0;	

	$item = '<tr>';
	$item .= '<td></td>';
	$item .= '<td colspan="'.(count($detail_tahun)+2).'">'.$m0->keterangan.'</td>';
	$item .= '</tr>';
	echo $item;

    foreach($produk[$m0->keterangan] as $m1) { 
		$no++;

		$bgedit ="";
		$contentedit ="false" ;
		$id = 'keterangan';
		if($current_cabang == user('kode_cabang') && $akses_ubah == 1) {
			$bgedit ="#ffbb33";
			$contentedit ="true" ;
			$id = 'id' ;
		}

		$item = '<tr>';
		$item .= '<td>'.$no.'</td>';
		$item .= '<td>'.$m1->keterangan.'</td>';
		foreach ($detail_tahun as $v) {
			$v_field  = 'T_' . sprintf("%02d", $v->bulan);
			$key = multidimensional_search($produk[$m0->keterangan.'all'], array(
				'kode_cabang'=>$m1->kode_cabang,
				'sumber_data'=> $v->sumber_data,
				'keterangan' => $m1->keterangan,
			));
			$d = $produk[$m0->keterangan.'all'][$key];
			$value = $d[$v_field];

			if(!isset(${$m0->id.$v_field.'_'.$v->sumber_data})):
				${$m0->id.$v_field.'_'.$v->sumber_data} = $value;
			else:
				${$m0->id.$v_field.'_'.$v->sumber_data} += $value;
			endif;

			$item .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right" data-name="'.$v_field.'" data-id="'.$d['id'].'" data-value="'.number_format($value).'">'.number_format($value).'</div></td>';
		}
		$item .= '<td class="button">
			<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="'.$m1->id.'" title="'.lang('ubah').'"><i class="fa-edit"></i></button></td>';
		$item .= '</tr>';

		echo $item;
	}
	$item = '<tr>';
	$item .= '<th style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;"></th>';
	$item .= '<th style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;">Jumlah '.$m0->keterangan.'</th>';
	foreach ($detail_tahun as $v) {
		$v_field  = 'T_' . sprintf("%02d", $v->bulan);
		if(!isset(${$m0->id.$v_field.'_'.$v->sumber_data})):
			$value = 0;
		else:
			$value = ${$m0->id.$v_field.'_'.$v->sumber_data};
		endif;
		$item .= '<th class="text-right" style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;">'.number_format($value).'</th>';
	}
	$item .= '<th style="background: #757575;" style="min-height: 10px; width: 50px; overflow: hidden;"></th>';
	$item .= '</tr>';
	echo $item;
}	
?>
		
	