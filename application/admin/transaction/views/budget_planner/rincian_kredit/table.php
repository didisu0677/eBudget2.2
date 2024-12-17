<?php
	$item = '';
	foreach ($detail_tahun as $k2 => $v2) {
		$no = 1;
		$field = 'B_' . sprintf("%02d", $v2->bulan);

		$class = '';
		if($k2 != 0){ $class = ' mt-3'; }
		$column = month_lang($v2->bulan).' '.$v2->tahun;
		$item .= 
			'<div class="col-sm-12'.$class.'">
				<div class="card">
		    		<div class="card-header">'.$column.'</div>
		    		<div class="card-body">
		    			<div class="table-responsive tab-pane fade active show">';

		$item .= '<table class="table table-striped table-bordered table-app table-hover">';
		$item .= '<thead>
		<tr><th colspan="11">'.get_view_report().'</th></tr>
		<tr>';
		$item .= '<th class="text-center">'.lang('no').'</th>';
		$item .= '<th class="text-center">'.lang('kode').'</th>';
		$item .= '<th class="text-center">'.lang('keterangan').'</th>';
		$item .= '<th class="text-center">'.'%'.'</th>';
		$item .= '<th class="text-center">'.lang('total_kredit').'</th>';
		$item .= '<th class="text-center">'.lang('kol_1').'</th>';
		$item .= '<th class="text-center">'.lang('kol_2').'</th>';
		$item .= '<th class="text-center">'.lang('kol_3').'</th>';
		$item .= '<th class="text-center">'.lang('kol_4').'</th>';
		$item .= '<th class="text-center">'.lang('kol_5').'</th>';
		$item .= '<th class="text-center bg-blue" style="color:#fff !important">'.lang('selisih').'</th>';
		$item .= '</tr></thead>';
		$item .= '<tbody>';
		$arrTotal['kredit'] = 0;
		$arrTotal['kol_1'] = 0;
		$arrTotal['kol_2'] = 0;
		$arrTotal['kol_3'] = 0;
		$arrTotal['kol_4'] = 0;
		$arrTotal['kol_5'] = 0;
		$arrTotal['selisih'] = 0;
		foreach ($listKredit as $k => $v) {
			$item2 = '<tr>';
			$item2 .= '<td></td>';
			$item2 .= '<th>'.$v->coa.'</th>';
			$item2 .= '<th>'.$v->nama.'</th>';
			$item2 .= '<td></td>';
			
			$key = multidimensional_search($listDetail, array(
				'tahun_core' => $v2->tahun,
				'tipe'	=> $v->tipe,
				'id_kolektibilitas'	=> $v->id,
			));

			$d = $listDetail[$key];
			$totSelisih = $d[$field] - ($d[$field.'_1'] + $d[$field.'_2'] + $d[$field.'_3'] + $d[$field.'_4'] + $d[$field.'_5']);
			$item2 .= '<td class="text-right">'.checkVal($d[$field]).'</td>';
			$item2 .= '<td class="text-right">'.checkVal($d[$field.'_1']).'</td>';
			$item2 .= '<td class="text-right">'.checkVal($d[$field.'_2']).'</td>';
			$item2 .= '<td class="text-right">'.checkVal($d[$field.'_3']).'</td>';
			$item2 .= '<td class="text-right">'.checkVal($d[$field.'_4']).'</td>';
			$item2 .= '<td class="text-right">'.checkVal($d[$field.'_5']).'</td>';
			$item2 .= '<td class="text-right">'.checkVal($totSelisih).'</td>';
			$item2 .= '<td></td>';
			$item2 .= '</tr>';
			
			if($v->tipe == 1):
				if(isset($arrTotal['kredit'])){ $arrTotal['kredit'] += $d[$field]; }else{ $arrTotal['kredit'] = $d[$field]; }
				if(isset($arrTotal['kol_1'])){ $arrTotal['kol_1'] += $d[$field.'_1']; }else{ $arrTotal['kol_1'] = $d[$field.'_1']; }
				if(isset($arrTotal['kol_2'])){ $arrTotal['kol_2'] += $d[$field.'_2']; }else{ $arrTotal['kol_2'] = $d[$field.'_2']; }
				if(isset($arrTotal['kol_3'])){ $arrTotal['kol_3'] += $d[$field.'_3']; }else{ $arrTotal['kol_3'] = $d[$field.'_3']; }
				if(isset($arrTotal['kol_4'])){ $arrTotal['kol_4'] += $d[$field.'_4']; }else{ $arrTotal['kol_4'] = $d[$field.'_4']; }
				if(isset($arrTotal['kol_5'])){ $arrTotal['kol_5'] += $d[$field.'_5']; }else{ $arrTotal['kol_5'] = $d[$field.'_5']; }
				if(isset($arrTotal['selisih'])){ $arrTotal['selisih'] += $totSelisih; }else{ $arrTotal['selisih'] = $totSelisih; }
			else:
				if(isset($arrTotal['kredit'])){ $arrTotal['kredit'] += $d[$field]; }else{ $arrTotal['kredit'] = $d[$field]; }
				if(isset($arrTotal['kol_1'])){ $arrTotal['kol_1'] += $d[$field.'_1']; }else{ $arrTotal['kol_1'] = $d[$field.'_1']; }
				if(isset($arrTotal['kol_2'])){ $arrTotal['kol_2'] += $d[$field.'_2']; }else{ $arrTotal['kol_2'] = $d[$field.'_2']; }
				if(isset($arrTotal['kol_3'])){ $arrTotal['kol_3'] += $d[$field.'_3']; }else{ $arrTotal['kol_3'] = $d[$field.'_3']; }
				if(isset($arrTotal['kol_4'])){ $arrTotal['kol_4'] += $d[$field.'_4']; }else{ $arrTotal['kol_4'] = $d[$field.'_4']; }
				if(isset($arrTotal['kol_5'])){ $arrTotal['kol_5'] += $d[$field.'_5']; }else{ $arrTotal['kol_5'] = $d[$field.'_5']; }
				if(isset($arrTotal['selisih'])){ $arrTotal['selisih'] += $totSelisih; }else{ $arrTotal['selisih'] = $totSelisih; }
			endif;

			$after = false;
			$arrData = [];
			$item_up = '';
			$item_down = '';
			foreach ($list as $k3 => $v3) {
				if($v3->grup == $v->coa):
					$no ++;
					$tot = (float) number_format($v3->{$totTxt},2);
					$kol_kredit = $tot * $d[$field];
					$kol_2 = $tot * $d[$field.'_2'];
					$kol_3 = $tot * $d[$field.'_3'];
					$kol_4 = $tot * $d[$field.'_4'];
					$kol_5 = $tot * $d[$field.'_5'];
					$kol_1 = $kol_kredit - ($kol_2+$kol_3+$kol_4+$kol_5);
					$kol_selisih = $kol_kredit - ($kol_1+$kol_2+$kol_3+$kol_4+$kol_5);
					$name = strtolower(remove_spaces($v3->keterangan));
					$temp_name = 'perdagangan besar dan eceran';
					$item_temp = '<tr>';
					$item_temp .= '<td>'.$no.'</td>';
					$item_temp .= '<td>'.$v3->kode.'</td>';
					$item_temp .= '<td>'.remove_spaces($v3->keterangan).'</td>';
					$item_temp .= '<td class="text-right">'.checkRate($tot).'</td>';
					$item_temp .= '<td class="text-right">'.checkVal($kol_kredit).'</td>';
					$item_temp .= '<td class="text-right">'.checkVal($kol_1).'</td>';
					$item_temp .= '<td class="text-right">'.checkVal($kol_2).'</td>';
					$item_temp .= '<td class="text-right">'.checkVal($kol_3).'</td>';
					$item_temp .= '<td class="text-right">'.checkVal($kol_4).'</td>';
					$item_temp .= '<td class="text-right">'.checkVal($kol_5).'</td>';
					$item_temp .= '<td class="text-right">'.checkVal($kol_selisih).'</td>';
					$item_temp .= '</tr>';
					if($after && $name != $temp_name):
						$item_down .= $item_temp;
						if(isset($arrData['kredit'])){ $arrData['kredit'] -= $kol_kredit; }else{ $arrData['kredit'] = $kol_kredit; }
						if(isset($arrData['kol_1'])){ $arrData['kol_1'] -= $kol_1; }else{ $arrData['kol_1'] = $kol_1; }
						if(isset($arrData['kol_2'])){ $arrData['kol_2'] -= $kol_2; }else{ $arrData['kol_2'] = $kol_2; }
						if(isset($arrData['kol_3'])){ $arrData['kol_3'] -= $kol_3; }else{ $arrData['kol_3'] = $kol_3; }
						if(isset($arrData['kol_4'])){ $arrData['kol_4'] -= $kol_4; }else{ $arrData['kol_4'] = $kol_4; }
						if(isset($arrData['kol_5'])){ $arrData['kol_5'] -= $kol_5; }else{ $arrData['kol_5'] = $kol_5; }
						if(isset($arrData['selisih'])){ $arrData['selisih'] -= $kol_selisih; }else{ $arrData['selisih'] = $kol_selisih; }
					elseif(!$after && $name != $temp_name):
						$item_up .= $item_temp;
						if(isset($arrData['kredit'])){ $arrData['kredit'] += $kol_kredit; }else{ $arrData['kredit'] = $kol_kredit; }
						if(isset($arrData['kol_1'])){ $arrData['kol_1'] += $kol_1; }else{ $arrData['kol_1'] = $kol_1; }
						if(isset($arrData['kol_2'])){ $arrData['kol_2'] += $kol_2; }else{ $arrData['kol_2'] = $kol_2; }
						if(isset($arrData['kol_3'])){ $arrData['kol_3'] += $kol_3; }else{ $arrData['kol_3'] = $kol_3; }
						if(isset($arrData['kol_4'])){ $arrData['kol_4'] += $kol_4; }else{ $arrData['kol_4'] = $kol_4; }
						if(isset($arrData['kol_5'])){ $arrData['kol_5'] += $kol_5; }else{ $arrData['kol_5'] = $kol_5; }
						if(isset($arrData['selisih'])){ $arrData['selisih'] += $kol_selisih; }else{ $arrData['selisih'] = $kol_selisih; }
					endif;

					if($name == 'perdagangan besar dan eceran'):
						$after = true;
						$arrData['no'] 		= $no;
						$arrData['kode']	= $v3->kode;
						$arrData['keterangan'] = $v3->keterangan;
						$arrData['tot'] 	= $tot;
					endif;
				endif;
			}
			$item_after = '';
			if($after):
				$item_after = '<tr>';
				$item_after .= '<td>'.$arrData['no'].'</td>';
				$item_after .= '<td>'.$arrData['kode'].'</td>';
				$item_after .= '<th>'.$arrData['keterangan'].'</th>';
				$item_after .= '<td class="text-right">'.checkRate($arrData['tot']).'</td>';
				$item_after .= '<td class="text-right">'.checkVal(($d[$field] - $arrData['kredit'])).'</td>';
				$item_after .= '<td class="text-right">'.checkVal($d[$field.'_1'] - $arrData['kol_1']).'</td>';
				$item_after .= '<td class="text-right">'.checkVal($d[$field.'_2'] - $arrData['kol_2']).'</td>';
				$item_after .= '<td class="text-right">'.checkVal($d[$field.'_3'] - $arrData['kol_3']).'</td>';
				$item_after .= '<td class="text-right">'.checkVal($d[$field.'_4'] - $arrData['kol_4']).'</td>';
				$item_after .= '<td class="text-right">'.checkVal($d[$field.'_5'] - $arrData['kol_5']).'</td>';
				$item_after .= '<td class="text-right">'.checkVal($totSelisih - $arrData['selisih']).'</td>';
				$item_after .= '</tr>';
			endif;
			$item .= $item_up;
			$item .= $item_after;
			$item .= $item_down;
			$item .= $item2;

		}
		if(count($listKredit)<=0):
			$item .= '<tr>';
			$item .= '<th class="text-center" colspan="11">'.lang('data_not_found').'</th>';
			$item .= '</tr>';
		endif;
		$item .= '<tr>';
		$item .= '<td></td>';
		$item .= '<td></td>';
		$item .= '<td>'.lang('total_kredit').'</td>';
		$item .= '<td></td>';
		$item .= '<td class="text-right">'.checkVal($arrTotal['kredit']).'</td>';
		$item .= '<td class="text-right">'.checkVal($arrTotal['kol_1']).'</td>';
		$item .= '<td class="text-right">'.checkVal($arrTotal['kol_2']).'</td>';
		$item .= '<td class="text-right">'.checkVal($arrTotal['kol_3']).'</td>';
		$item .= '<td class="text-right">'.checkVal($arrTotal['kol_4']).'</td>';
		$item .= '<td class="text-right">'.checkVal($arrTotal['kol_5']).'</td>';
		$item .= '<td class="text-right">'.checkVal($arrTotal['selisih']).'</td>';
		$item .= '</tr>';
		$item .= '</tbody>';
		$item .= '</table>';

		$item .= 		'</div>
					</div>
				</div>
			</div>';
	}
	echo $item;

	function checkVal($val){
		return custom_format(view_report($val),false,2);
	}
	function checkRate($val){
		return custom_format($val,false,2);
	}
?>