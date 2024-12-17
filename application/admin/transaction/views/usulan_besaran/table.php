<?php
	$bgedit ="";
	$contentedit ="false" ;
	$id = 'keterangan';
	if($access_edit) {
		$bgedit =bgEdit();
		$contentedit ="true" ;
		$id = 'id' ;
	}
	$dataSaved = [];
	function table($title){
		$bulan = '';
		for ($i=1; $i <= 12 ; $i++) { 
			$bulan .= '<th class="text-center" style="min-width:150px">'.month_lang($i).'</th>';
		}
		$content = '<div class="row mt-2">
					<div class="col-sm-12">
						<div class="card">
							<div class="card-header">'.$title.'</div>
			    			<div class="card-body">
			    				<div class="table-responsive tab-pane fade active show">
			    				<table class="table table-striped table-bordered table-app table-hover">
			    				<thead>
			    					<tr><th colspan="14">'.get_view_report().'</th></tr>
			    					<tr>
			    						<th class="text-center" width="15">'.lang('no').'</th>
			    						<th class="text-center" style="min-width:350px">'.lang('keterangan').'</th>
			    						'.$bulan.'
			    					</tr>
			    				</thead>
			    				<tbody>
			    			';
			    			
	   	$content2 = '			</tbody></table>
	   							</div>
	   						</div>
	   					</div>
	   				</div>
	   			</div>';
	   	return [
	   		'content' 	=> $content,
	   		'content2' 	=> $content2,
	   	];
	}

	function saved_data($data,$anggaran,$cabang){
		$table = 'tbl_bottom_up_form1';
		foreach($data as $k => $v){
			$x 		= explode('-', $k);
            $coa 	= $x[0];
            $tahun 	= $x[1];

            $where = [
            	'kode_anggaran'	=> $anggaran->kode_anggaran,
            	'kode_cabang'	=> $cabang->kode_cabang,
            	'data_core'		=> $tahun,
            	'coa'			=> $coa
            ];

            $ck = get_data($table,['select' => 'id', 'where' => $where])->row();
            $dataSave = [];
            if($ck):
            	$dataSave = $v;
            	$dataSave['id'] = $ck->id;
            else:
            	$dataSave = $where;
            	$dataSave = array_merge($dataSave,$v);
            	$dataSave['keterangan_anggaran'] = $anggaran->keterangan;
            	$dataSave['tahun'] = $anggaran->tahun_anggaran;
            	$dataSave['cabang'] = $cabang->nama_cabang;
            	$dataSave['username'] = user('username');
            endif;
            save_data($table,$dataSave,[],true);
		}
	}

	// GIRO
	$arr_total_dpk = [];
	if(in_array('2100000',$arr_group_giro) && count($arr_group_giro) == 1):
		$coa = '2100000';
		$coa_key = multidimensional_search($dt_coa, array(
			'glwnco' => $coa,
		));
		$kali_minus = 0;
		$title = 'GIRO';
		if(strlen($coa_key)>0):
			$kali_minus = $dt_coa[$coa_key]['kali_minus'];
			$title 		= remove_spaces($dt_coa[$coa_key]['glwdes']);
		endif;

		$d_content = table($coa.' - '.$title);
		$item 	 = '';
		$no 	 = 0;
		$arrData = [];
		for ($thn=3; $thn >= 0 ; $thn--) { 
			$no++;
			$tahun = ($anggaran->tahun_anggaran-$thn);

			$core_key = '';
			if(isset($data_core[$tahun])):
				$core_key = multidimensional_search($data_core[$tahun], array(
					'glwnco' => $coa,
				));
			endif;

			$list_key = multidimensional_search($list, array(
				'data_core' => $tahun,
				'coa'		=> $coa,
			));

			$item .= '<tr>';
			$item .= '<td>'.($no).'</td>';
			$item .= '<td>'.$title.' '.$tahun.'</td>';
			for ($i=1; $i <= 12 ; $i++) { 
				$field 	= 'B_' . sprintf("%02d", $i);
				$tahun_key = multidimensional_search($detail_tahun, array(
					'tahun' => $tahun,
					'bulan'	=> $i,
				));
				if(strlen($tahun_key)>0 && $detail_tahun[$tahun_key]['singkatan'] != arrSumberData()['real'])://terdaftar di detail tahun anggaran
					$val 	= 0;

					if(strlen($list_key)>0):
						$val = $list[$list_key][$field];
					endif;

					$value 	= number_format(view_report($val),0,',','.');
					$name 	= $coa.'-'.$tahun;
					$item .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right money" data-name="'.$field.'" data-id="'.$name.'" data-value="'.$value.'">'.view_report($val).'</div></td>';
				elseif(strlen($core_key)>0):
					$val = $data_core[$tahun][$core_key][$field];
					$val = kali_minus($val,$kali_minus);
					$item .= '<td class="text-right">'.custom_format(view_report($val)).'</td>';
					if(strlen($tahun_key)>0)://realisasi tapi terdaftar di detail tahun anggaran
						$dataSaved[$coa.'-'.$tahun][$field] = $val;
					endif;
				else:
					$val = 0;
					$item .= '<td class="text-right">'.custom_format($val).'</td>';
				endif;
				$arrData[$tahun][$i] = $val;
				if(isset($arr_total_dpk[$tahun][$field])) $arr_total_dpk[$tahun][$field] += $val; else $arr_total_dpk[$tahun][$field] = $val;

			}
			$item .= '</tr>';

			if($no>1):
				$item .= '<tr>';
				$item .= '<td></td>';
				$item .= '<td>Pert '.$title.' '.$tahun.' (%)</td>';
				for ($i=1; $i <= 12 ; $i++) { 
					$pertumbuhan 	= 0;
                    $pembagi 		= $arrData[($tahun-1)][$i];
                    if($pembagi):
                        $pertumbuhan = (($arrData[$tahun][$i]-$pembagi)/$pembagi)*100;
                    endif;
                    $item .= '<td class="text-right">'.custom_format($pertumbuhan,false,2).'</td>';
				}
				$item .= '</tr>';
			endif;

		}

		echo $d_content['content'].$item.$d_content['content2'];
	elseif(count($arr_group_giro)>0):
		$arr_total_giro = [];
		foreach($arr_group_giro as $coa){ if($coa != '2100000'):
			$coa_key = multidimensional_search($dt_coa, array(
				'glwnco' => $coa,
			));
			$title = '';
			$kali_minus = 0;
			if(strlen($coa_key)>0):
				$title = remove_spaces($dt_coa[$coa_key]['glwdes']);
				$kali_minus = $dt_coa[$coa_key]['kali_minus'];
			endif;
			$d_content = table($coa.' - '.$title);

			$item 	 = '';
			$no 	 = 0;
			$arrData = [];
			for ($thn=3; $thn >= 0 ; $thn--) {
				$no++;
				$tahun = ($anggaran->tahun_anggaran-$thn);

				$core_key = '';
				if(isset($data_core[$tahun])):
					$core_key = multidimensional_search($data_core[$tahun], array(
						'glwnco' => $coa,
					));
				endif;

				$list_key = multidimensional_search($list, array(
					'data_core' => $tahun,
					'coa'		=> $coa,
				));

				$item .= '<tr>';
				$item .= '<td>'.($no).'</td>';
				$item .= '<td>'.$title.' '.$tahun.'</td>';
				for ($i=1; $i <= 12 ; $i++) { 
					$field 	= 'B_' . sprintf("%02d", $i);
					$tahun_key = multidimensional_search($detail_tahun, array(
						'tahun' => $tahun,
						'bulan'	=> $i,
					));
					if(strlen($tahun_key)>0 && $detail_tahun[$tahun_key]['singkatan'] != arrSumberData()['real'])://terdaftar di detail tahun anggaran
						$val 	= 0;

						if(strlen($list_key)>0):
							$val = $list[$list_key][$field];
						endif;

						$value 	= number_format(view_report($val),0,',','.');
						$name 	= $coa.'-'.$tahun;
						$item .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right money" data-name="'.$field.'" data-id="'.$name.'" data-value="'.$value.'">'.view_report($val).'</div></td>';
					elseif(strlen($core_key)>0):
						$val = $data_core[$tahun][$core_key][$field];
						$val = kali_minus($val,$kali_minus);
						$item .= '<td class="text-right">'.custom_format(view_report($val)).'</td>';
						if(strlen($tahun_key)>0)://realisasi tapi terdaftar di detail tahun anggaran
							$dataSaved[$coa.'-'.$tahun][$field] = $val;
						endif;
					else:
						$val = 0;
						$item .= '<td class="text-right">'.custom_format($val).'</td>';
					endif;
					$arrData[$tahun][$i] = $val;
					if(isset($arr_total_giro[$tahun][$field])) $arr_total_giro[$tahun][$field] += $val; else $arr_total_giro[$tahun][$field] = $val;
					if(isset($arr_total_dpk[$tahun][$field])) $arr_total_dpk[$tahun][$field] += $val; else $arr_total_dpk[$tahun][$field] = $val;

				}
				$item .= '</tr>';

				if($no>1):
					$item .= '<tr>';
					$item .= '<td></td>';
					$item .= '<td>Pert '.$title.' '.$tahun.' (%)</td>';
					for ($i=1; $i <= 12 ; $i++) { 
						$pertumbuhan 	= 0;
	                    $pembagi 		= $arrData[($tahun-1)][$i];
	                    if($pembagi):
	                        $pertumbuhan = (($arrData[$tahun][$i]-$pembagi)/$pembagi)*100;
	                    endif;
	                    $item .= '<td class="text-right">'.custom_format($pertumbuhan,false,2).'</td>';
					}
					$item .= '</tr>';
				endif;
			}

			echo $d_content['content'].$item.$d_content['content2'];
		endif;}

		$coa = '2100000';
		$coa_key = multidimensional_search($dt_coa, array(
			'glwnco' => $coa,
		));
		$title = 'GIRO';
		if(strlen($coa_key)>0):
			$title 		= remove_spaces($dt_coa[$coa_key]['glwdes']);
		endif;
		$d_content = table($coa.' - '.$title);
		$item 	 = '';
		$no 	 = 0;
		for ($thn=3; $thn >= 0 ; $thn--) { 
			$no++;
			$tahun = ($anggaran->tahun_anggaran-$thn);
			$item .= '<tr>';
			$item .= '<td>'.($no).'</td>';
			$item .= '<td>'.$title.' '.$tahun.'</td>';
			for ($i=1; $i <=12 ; $i++) { 
				$field 	= 'B_' . sprintf("%02d", $i);
				$val = $arr_total_giro[$tahun][$field];
				$item .= '<td class="text-right">'.custom_format(view_report($val)).'</td>';
			}
			$item .= '</tr>';

			if($no>1):
				$item .= '<tr>';
				$item .= '<td></td>';
				$item .= '<td>Pert '.$title.' '.$tahun.' (%)</td>';
				for ($i=1; $i <= 12 ; $i++) {
					$field 	= 'B_' . sprintf("%02d", $i);
					$pertumbuhan 	= 0;
                    $pembagi 		= $arr_total_giro[($tahun-1)][$field];
                    if($pembagi):
                        $pertumbuhan = (($arr_total_giro[$tahun][$field]-$pembagi)/$pembagi)*100;
                    endif;
                    $item .= '<td class="text-right">'.custom_format($pertumbuhan,false,2).'</td>';
				}
				$item .= '</tr>';
			endif;
		}
		echo $d_content['content'].$item.$d_content['content2'];
	endif;

	// tabungan dan simpanan berjangka
	foreach($arr_dpk as $coa){ 
		$coa_key = multidimensional_search($dt_coa, array(
			'glwnco' => $coa,
		));
		$title = '';
		$kali_minus = 0;
		if(strlen($coa_key)>0):
			$title = remove_spaces($dt_coa[$coa_key]['glwdes']);
			$kali_minus = $dt_coa[$coa_key]['kali_minus'];
		endif;
		$d_content = table($coa.' - '.$title);

		$item 	 = '';
		$no 	 = 0;
		$arrData = [];
		for ($thn=3; $thn >= 0 ; $thn--) {
			$no++;
			$tahun = ($anggaran->tahun_anggaran-$thn);

			$core_key = '';
			if(isset($data_core[$tahun])):
				$core_key = multidimensional_search($data_core[$tahun], array(
					'glwnco' => $coa,
				));
			endif;

			$list_key = multidimensional_search($list, array(
				'data_core' => $tahun,
				'coa'		=> $coa,
			));

			$item .= '<tr>';
			$item .= '<td>'.($no).'</td>';
			$item .= '<td>'.$title.' '.$tahun.'</td>';
			for ($i=1; $i <= 12 ; $i++) { 
				$field 	= 'B_' . sprintf("%02d", $i);
				$tahun_key = multidimensional_search($detail_tahun, array(
					'tahun' => $tahun,
					'bulan'	=> $i,
				));
				if(strlen($tahun_key)>0 && $detail_tahun[$tahun_key]['singkatan'] != arrSumberData()['real'])://terdaftar di detail tahun anggaran
					$val 	= 0;

					if(strlen($list_key)>0):
						$val = $list[$list_key][$field];
					endif;

					$value 	= number_format(view_report($val),0,',','.');
					$name 	= $coa.'-'.$tahun;
					$item .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right money" data-name="'.$field.'" data-id="'.$name.'" data-value="'.$value.'">'.view_report($val).'</div></td>';
				elseif(strlen($core_key)>0):
					$val = $data_core[$tahun][$core_key][$field];
					$val = kali_minus($val,$kali_minus);
					$item .= '<td class="text-right">'.custom_format(view_report($val)).'</td>';
					if(strlen($tahun_key)>0)://realisasi tapi terdaftar di detail tahun anggaran
						$dataSaved[$coa.'-'.$tahun][$field] = $val;
					endif;
				else:
					$val = 0;
					$item .= '<td class="text-right">'.custom_format($val).'</td>';
				endif;
				$arrData[$tahun][$i] = $val;
				if(isset($arr_total_dpk[$tahun][$field])) $arr_total_dpk[$tahun][$field] += $val; else $arr_total_dpk[$tahun][$field] = $val;

			}
			$item .= '</tr>';

			if($no>1):
				$item .= '<tr>';
				$item .= '<td></td>';
				$item .= '<td>Pert '.$title.' '.$tahun.' (%)</td>';
				for ($i=1; $i <= 12 ; $i++) { 
					$pertumbuhan 	= 0;
                    $pembagi 		= $arrData[($tahun-1)][$i];
                    if($pembagi):
                        $pertumbuhan = (($arrData[$tahun][$i]-$pembagi)/$pembagi)*100;
                    endif;
                    $item .= '<td class="text-right">'.custom_format($pertumbuhan,false,2).'</td>';
				}
				$item .= '</tr>';
			endif;
		}

		echo $d_content['content'].$item.$d_content['content2'];
	}

	// DPK
	if(count($arr_total_dpk)>0):
		$title = 'DPK';
		$d_content = table('DPK (DANA PIHAK KETIGA)');
		$item 	 = '';
		$no 	 = 0;
		for ($thn=3; $thn >= 0 ; $thn--) { 
			$no++;
			$tahun = ($anggaran->tahun_anggaran-$thn);
			$item .= '<tr>';
			$item .= '<td>'.($no).'</td>';
			$item .= '<td>'.$title.' '.$tahun.'</td>';
			for ($i=1; $i <=12 ; $i++) { 
				$field 	= 'B_' . sprintf("%02d", $i);
				$val = $arr_total_dpk[$tahun][$field];
				$item .= '<td class="text-right">'.custom_format(view_report($val)).'</td>';
			}
			$item .= '</tr>';

			if($no>1):
				$item .= '<tr>';
				$item .= '<td></td>';
				$item .= '<td>Pert '.$title.' '.$tahun.' (%)</td>';
				for ($i=1; $i <= 12 ; $i++) {
					$field 	= 'B_' . sprintf("%02d", $i);
					$pertumbuhan 	= 0;
                    $pembagi 		= $arr_total_dpk[($tahun-1)][$field];
                    if($pembagi):
                        $pertumbuhan = (($arr_total_dpk[$tahun][$field]-$pembagi)/$pembagi)*100;
                    endif;
                    $item .= '<td class="text-right">'.custom_format($pertumbuhan,false,2).'</td>';
				}
				$item .= '</tr>';
			endif;
		}
		echo $d_content['content'].$item.$d_content['content2'];
	endif;

	// KREDIT
	$arr_total_kredit = [];
	foreach($arr_kredit as $coa){ 
		$coa_key = multidimensional_search($dt_coa, array(
			'glwnco' => $coa,
		));
		$title = '';
		$kali_minus = 0;
		if(strlen($coa_key)>0):
			$title = remove_spaces($dt_coa[$coa_key]['glwdes']);
			$kali_minus = $dt_coa[$coa_key]['kali_minus'];
		endif;
		$d_content = table($coa.' - '.$title);

		$item 	 = '';
		$no 	 = 0;
		$arrData = [];
		for ($thn=3; $thn >= 0 ; $thn--) {
			$no++;
			$tahun = ($anggaran->tahun_anggaran-$thn);

			$core_key = '';
			if(isset($data_core[$tahun])):
				$core_key = multidimensional_search($data_core[$tahun], array(
					'glwnco' => $coa,
				));
			endif;

			$list_key = multidimensional_search($list, array(
				'data_core' => $tahun,
				'coa'		=> $coa,
			));

			$item .= '<tr>';
			$item .= '<td>'.($no).'</td>';
			$item .= '<td>'.$title.' '.$tahun.'</td>';
			for ($i=1; $i <= 12 ; $i++) { 
				$field 	= 'B_' . sprintf("%02d", $i);
				$tahun_key = multidimensional_search($detail_tahun, array(
					'tahun' => $tahun,
					'bulan'	=> $i,
				));
				if(strlen($tahun_key)>0 && $detail_tahun[$tahun_key]['singkatan'] != arrSumberData()['real'])://terdaftar di detail tahun anggaran
					$val 	= 0;

					if(strlen($list_key)>0):
						$val = $list[$list_key][$field];
					endif;

					$value 	= number_format(view_report($val),0,',','.');
					$name 	= $coa.'-'.$tahun;
					$item .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right money" data-name="'.$field.'" data-id="'.$name.'" data-value="'.$value.'">'.view_report($val).'</div></td>';
				elseif(strlen($core_key)>0):
					$val = $data_core[$tahun][$core_key][$field];
					$val = kali_minus($val,$kali_minus);
					$item .= '<td class="text-right">'.custom_format(view_report($val)).'</td>';
					if(strlen($tahun_key)>0)://realisasi tapi terdaftar di detail tahun anggaran
						$dataSaved[$coa.'-'.$tahun][$field] = $val;
					endif;
				else:
					$val = 0;
					$item .= '<td class="text-right">'.custom_format($val).'</td>';
				endif;
				$arrData[$tahun][$i] = $val;
				if(isset($arr_total_kredit[$tahun][$field])) $arr_total_kredit[$tahun][$field] += $val; else $arr_total_kredit[$tahun][$field] = $val;

			}
			$item .= '</tr>';

			if($no>1):
				$item .= '<tr>';
				$item .= '<td></td>';
				$item .= '<td>Pert '.$title.' '.$tahun.' (%)</td>';
				for ($i=1; $i <= 12 ; $i++) { 
					$pertumbuhan 	= 0;
                    $pembagi 		= $arrData[($tahun-1)][$i];
                    if($pembagi):
                        $pertumbuhan = (($arrData[$tahun][$i]-$pembagi)/$pembagi)*100;
                    endif;
                    $item .= '<td class="text-right">'.custom_format($pertumbuhan,false,2).'</td>';
				}
				$item .= '</tr>';
			endif;
		}

		echo $d_content['content'].$item.$d_content['content2'];
	}
	if(count($arr_total_kredit)>0):
		$title = 'TOTAL KREDIT';
		$d_content = table($title);
		$item 	 = '';
		$no 	 = 0;
		for ($thn=3; $thn >= 0 ; $thn--) { 
			$no++;
			$tahun = ($anggaran->tahun_anggaran-$thn);
			$item .= '<tr>';
			$item .= '<td>'.($no).'</td>';
			$item .= '<td>'.$title.' '.$tahun.'</td>';
			for ($i=1; $i <=12 ; $i++) { 
				$field 	= 'B_' . sprintf("%02d", $i);
				$val = $arr_total_kredit[$tahun][$field];
				$item .= '<td class="text-right">'.custom_format(view_report($val)).'</td>';
			}
			$item .= '</tr>';

			if($no>1):
				$item .= '<tr>';
				$item .= '<td></td>';
				$item .= '<td>Pert '.$title.' '.$tahun.' (%)</td>';
				for ($i=1; $i <= 12 ; $i++) {
					$field 	= 'B_' . sprintf("%02d", $i);
					$pertumbuhan 	= 0;
                    $pembagi 		= $arr_total_kredit[($tahun-1)][$field];
                    if($pembagi):
                        $pertumbuhan = (($arr_total_kredit[$tahun][$field]-$pembagi)/$pembagi)*100;
                    endif;
                    $item .= '<td class="text-right">'.custom_format($pertumbuhan,false,2).'</td>';
				}
				$item .= '</tr>';
			endif;
		}
		echo $d_content['content'].$item.$d_content['content2'];
	endif;

	// LABA
	if(count($arr_laba)>0):
		$title = 'Laba';
		$d_content = table($title);
		$item 	 = '';
		$no 	 = 0;
		$arrData = [];
		for ($thn=3; $thn >= 0 ; $thn--) {
			$no++;
			$tahun = ($anggaran->tahun_anggaran-$thn);
			foreach ($arr_laba as $k => $coa) {
				$coa_key = multidimensional_search($dt_coa, array(
					'glwnco' => $coa,
				));

				$core_key = '';
				if(isset($data_core[$tahun])):
					$core_key = multidimensional_search($data_core[$tahun], array(
						'glwnco' => $coa,
					));
				endif;

				$list_key = multidimensional_search($list, array(
					'data_core' => $tahun,
					'coa'		=> $coa,
				));

				$kali_minus = 0;
				$glwdes = '';
				if(strlen($coa_key)>0):
					$kali_minus = $dt_coa[$coa_key]['kali_minus'];
					$glwdes = remove_spaces($dt_coa[$coa_key]['glwdes']);
				endif;

				$item .= '<tr>';
				if($k == 0): 
					$item .= '<td>'.$no.'</td>';
					$item .= '<td>'.$glwdes.' '.$tahun.'</td>';
				else: 
					$item .= '<td></td>';
					$item .= '<td>--| '.$glwdes.' '.'</td>';
				endif;
				for ($i=1; $i <= 12 ; $i++) { 
					$field 	= 'B_' . sprintf("%02d", $i);
					$tahun_key = multidimensional_search($detail_tahun, array(
						'tahun' => $tahun,
						'bulan'	=> $i,
					));
					if(strlen($tahun_key)>0 && $detail_tahun[$tahun_key]['singkatan'] != arrSumberData()['real'])://terdaftar di detail tahun anggaran
						$val 	= 0;

						if(strlen($list_key)>0):
							$val = $list[$list_key][$field];
						endif;

						$value 	= number_format(view_report($val),0,',','.');
						$name 	= $coa.'-'.$tahun;
						$item .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right money" data-name="'.$field.'" data-id="'.$name.'" data-value="'.$value.'">'.view_report($val).'</div></td>';
					elseif(strlen($core_key)>0):
						$val = $data_core[$tahun][$core_key][$field];
						$val = kali_minus($val,$kali_minus);
						$item .= '<td class="text-right">'.custom_format(view_report($val)).'</td>';
						if(strlen($tahun_key)>0)://realisasi tapi terdaftar di detail tahun anggaran
							$dataSaved[$coa.'-'.$tahun][$field] = $val;
						endif;
					else:
						$val = 0;
						$item .= '<td class="text-right">'.custom_format($val).'</td>';
					endif;

					if($coa == '59999'):
						$arrData[$tahun][$i] = $val;
					endif;

				}
				$item .= '</tr>';
			}

			if($no>1):
				$item .= '<tr>';
				$item .= '<td></td>';
				$item .= '<td>Pert '.$title.' '.$tahun.' (%)</td>';
				for ($i=1; $i <= 12 ; $i++) { 
					$pertumbuhan 	= 0;
                    $pembagi 		= $arrData[($tahun-1)][$i];
                    if($pembagi):
                        $pertumbuhan = (($arrData[$tahun][$i]-$pembagi)/$pembagi)*100;
                    endif;
                    $item .= '<td class="text-right">'.custom_format($pertumbuhan,false,2).'</td>';
				}
				$item .= '</tr>';
			endif;
		}
		echo $d_content['content'].$item.$d_content['content2'];
	endif;

	// COA LAIN
	foreach($arr_other as $coa){ 
		$coa_key = multidimensional_search($dt_coa, array(
			'glwnco' => $coa,
		));
		$title = '';
		$kali_minus = 0;
		if(strlen($coa_key)>0):
			$title = remove_spaces($dt_coa[$coa_key]['glwdes']);
			$kali_minus = $dt_coa[$coa_key]['kali_minus'];
		endif;
		$d_content = table($coa.' - '.$title);

		$item 	 = '';
		$no 	 = 0;
		$arrData = [];
		for ($thn=3; $thn >= 0 ; $thn--) {
			$no++;
			$tahun = ($anggaran->tahun_anggaran-$thn);

			$core_key = '';
			if(isset($data_core[$tahun])):
				$core_key = multidimensional_search($data_core[$tahun], array(
					'glwnco' => $coa,
				));
			endif;

			$list_key = multidimensional_search($list, array(
				'data_core' => $tahun,
				'coa'		=> $coa,
			));

			$item .= '<tr>';
			$item .= '<td>'.($no).'</td>';
			$item .= '<td>'.$title.' '.$tahun.'</td>';
			for ($i=1; $i <= 12 ; $i++) { 
				$field 	= 'B_' . sprintf("%02d", $i);
				$tahun_key = multidimensional_search($detail_tahun, array(
					'tahun' => $tahun,
					'bulan'	=> $i,
				));
				if(strlen($tahun_key)>0 && $detail_tahun[$tahun_key]['singkatan'] != arrSumberData()['real'])://terdaftar di detail tahun anggaran
					$val 	= 0;

					if(strlen($list_key)>0):
						$val = $list[$list_key][$field];
					endif;

					$value 	= number_format(view_report($val),0,',','.');
					$name 	= $coa.'-'.$tahun;
					$item .= '<td style="background:'.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;"  contenteditable="'.$contentedit.'" class="edit-value text-right money" data-name="'.$field.'" data-id="'.$name.'" data-value="'.$value.'">'.view_report($val).'</div></td>';
				elseif(strlen($core_key)>0):
					$val = $data_core[$tahun][$core_key][$field];
					$val = kali_minus($val,$kali_minus);
					$item .= '<td class="text-right">'.custom_format(view_report($val)).'</td>';
					if(strlen($tahun_key)>0)://realisasi tapi terdaftar di detail tahun anggaran
						$dataSaved[$coa.'-'.$tahun][$field] = $val;
					endif;
				else:
					$val = 0;
					$item .= '<td class="text-right">'.custom_format($val).'</td>';
				endif;
				$arrData[$tahun][$i] = $val;

			}
			$item .= '</tr>';

			if($no>1):
				$item .= '<tr>';
				$item .= '<td></td>';
				$item .= '<td>Pert '.$title.' '.$tahun.' (%)</td>';
				for ($i=1; $i <= 12 ; $i++) { 
					$pertumbuhan 	= 0;
                    $pembagi 		= $arrData[($tahun-1)][$i];
                    if($pembagi):
                        $pertumbuhan = (($arrData[$tahun][$i]-$pembagi)/$pembagi)*100;
                    endif;
                    $item .= '<td class="text-right">'.custom_format($pertumbuhan,false,2).'</td>';
				}
				$item .= '</tr>';
			endif;
		}

		echo $d_content['content'].$item.$d_content['content2'];
	}

	saved_data($dataSaved,$anggaran,$cabang);
?>