<?php
	$this->session->unset_userdata('control_total');
	$data = array();
	function hitung($p1,$p2,$data,$keterangan,$path_file){
		$data['icon'] = '';	
		foreach ($keterangan as $k => $v) {
			$formula = $v['formula'];
			$formula = str_replace('$$value', $p2, $formula);
			$condition = "return ".$formula.";";
			$res = eval($condition);
			if($res):
				$id = $v['id'];
				if(isset($data[$p1][$id])){ $data[$p1][$id] += 1; }else{ $data[$p1][$id] = 1; }
				$data['icon'] = '<div class="float-left"><img height="11" src="'.$path_file.$v['image'].'"/></div>';
			endif;
		}

		return $data;
	}
	$no = 0;
	$item = '';
	$arrType = [];
	foreach ($cabang['l1'] as $k => $v) {

		if(isset($cabang['l2'][$v['level1']]) and count($cabang['l2'][$v['level1']])>0):
			$val_bulan1 	= 0;
			$val_des1 		= 0;
			$val_o1 		= 0;
			$val_bln_real1 	= 0;
			$val_bln_renc1 	= 0;
			foreach ($cabang['l2'][$v['level1']] as $k2 => $v2) {
				if(isset($cabang['l3'][$v2['level2']]) and count($cabang['l3'][$v2['level2']])>0):
					$val_bulan2 	= 0;
					$val_des2 		= 0;
					$val_o2 		= 0;
					$val_bln_real2 	= 0;
					$val_bln_renc2 	= 0;
					foreach ($cabang['l3'][$v2['level2']] as $k3 => $v3) {
						if(isset($cabang['l4'][$v3['level3']]) and count($cabang['l4'][$v3['level3']])>0):
							$val_bulan3 	= 0;
							$val_des3 		= 0;
							$val_o3 		= 0;
							$val_bln_real3 	= 0;
							$val_bln_renc3 	= 0;
							foreach ($cabang['l4'][$v3['level3']] as $k4 => $v4) {
								$col_name = 'TOT_'.$v4['kode_cabang'];
								$val_bulan = 0; if(isset($dt_bulan[$col_name])) $val_bulan = $dt_bulan[$col_name];
								$val_des = 0; if(isset($dt_des[$col_name])) $val_des = $dt_des[$col_name];
								$val_o = 0; 
								if(isset($dt_before[$col_name])) $val_o = $dt_before[$col_name];
								if(isset($v4[$month_before])) $val_o = $v4[$month_before];

								$val_bln_real = 0;
								if(isset($dt_bulan_current[$col_name])):
									$val_bln_real = $dt_bulan_current[$col_name];
								endif;
								$val_bln_renc = 0;
								if($v4[$bulan]):
									$val_bln_renc = $v4[$bulan];
								endif;

								$x = 0;
								if($val_bln_real != 0): 
									$x = ($val_bln_renc/$val_bln_real)*100;
								endif;
								$y = 0;
								if($val_bulan != 0):
									$y = (($val_bln_real-$val_bulan)/$val_bulan)*100;
								endif;

								$val_bulan3 	+= $val_bulan;
								$val_des3 		+= $val_des;
								$val_o3 		+= $val_o;
								$val_bln_real3 	+= $val_bln_real;
								$val_bln_renc3 	+= $val_bln_renc;

								$data = hitung($v4['struktur_cabang'],$x,$data,$keterangan,$path_file);
								$icon = $data['icon'];
								
								$no++;
								$item .= '<tr>';
								$item .= '<td>'.$no.'</td>';
								$item .= '<td class="sb-3">'.remove_spaces($v4['nama_cabang']).'</td>';
								$item .= '<td class="text-right">'.check_value($val_bulan).'</td>';
								$item .= '<td class="text-right">'.check_value($val_des).'</td>';
								$item .= '<td class="text-right">'.check_value($val_o).'</td>';
								$item .= '<td class="text-right">'.check_value($val_bln_renc).'</td>';
								$item .= '<td class="text-right">'.check_value($val_bln_real).'</td>';
								$item .= '<td class="text-right">'.custom_format($x,false,2).$icon.'</td>';
								$item .= '<td class="text-right">'.custom_format($y,false,2).'</td>';
								$item .= '</tr>';
							}
						else:

							$col_name = 'TOT_'.$v3['kode_cabang'];
							$val_bulan3 = 0; if(isset($dt_bulan[$col_name])) $val_bulan3 = $dt_bulan[$col_name];
							$val_des3 = 0; if(isset($dt_des[$col_name])) $val_des3 = $dt_des[$col_name];
							$val_o3 = 0; if(isset($dt_before[$col_name])) $val_o3 = $dt_des[$col_name];

							$val_bln_real3 	= 0;
							if(isset($dt_bulan_current[$col_name])):
								$val_bln_real3 = $dt_bulan_current[$col_name];
							endif;
							$val_bln_renc3 	= 0;
							if($v3[$bulan]):
								$val_bln_renc3 = $v3[$bulan];
							endif;

						endif;

						$x3 = 0;
						if($val_bln_real3 != 0): 
							$x3 = ($val_bln_renc3/$val_bln_real3)*100;
						endif;
						$y3 = 0;
						if($val_bulan3 != 0):
							$y3 = (($val_bln_real3-$val_bulan3)/$val_bulan3)*100;
						endif;

						$val_bulan2 	+= $val_bulan3;
						$val_des2 		+= $val_des3;
						$val_o2 		+= $val_o3;
						$val_bln_real2 	+= $val_bln_real3;
						$val_bln_renc2 	+= $val_bln_renc3;

						$data = hitung($v3['struktur_cabang'],$x3,$data,$keterangan,$path_file);
						$icon = $data['icon'];

						$no++;
						$item .= '<tr>';
						$item .= '<td>'.$no.'</td>';
						$item .= '<th class="sb-2">'.remove_spaces($v3['nama_cabang']).'</th>';
						$item .= '<td class="text-right">'.check_value($val_bulan3).'</td>';
						$item .= '<td class="text-right">'.check_value($val_des3).'</td>';
						$item .= '<td class="text-right">'.check_value($val_o3).'</td>';
						$item .= '<td class="text-right">'.check_value($val_bln_renc3).'</td>';
						$item .= '<td class="text-right">'.check_value($val_bln_real3).'</td>';
						$item .= '<td class="text-right">'.custom_format($x3,false,2).$icon.'</td>';
						$item .= '<td class="text-right">'.custom_format($y3,false,2).'</td>';
						$item .= '</tr>';
					}
				else:
					$col_name = 'TOT_'.$v2['kode_cabang'];
					$val_bulan2 = 0; if(isset($dt_bulan[$col_name])) $val_bulan2 = $dt_bulan[$col_name];
					$val_des2 = 0; if(isset($dt_des[$col_name])) $val_des2 = $dt_des[$col_name];
					$val_o2 = 0; if(isset($dt_before[$col_name])) $val_o2 = $dt_des[$col_name];

					$val_bln_real2 	= 0;
					if(isset($dt_bulan_current[$col_name])):
						$val_bln_real2 = $dt_bulan_current[$col_name];
					endif;
					$val_bln_renc2 	= 0;
					if($v2[$bulan]):
						$val_bln_renc2 = $v2[$bulan];
					endif;
				endif;

				$x2 = 0;
				if($val_bln_real2 != 0): 
					$x2 = ($val_bln_renc2/$val_bln_real2)*100;
				endif;
				$y2 = 0;
				if($val_bulan2 != 0):
					$y2 = (($val_bln_real2-$val_bulan2)/$val_bulan2)*100;
				endif;

				$data = hitung($v2['struktur_cabang'],$x2,$data,$keterangan,$path_file);
				$icon = $data['icon'];

				$val_bulan1 	+= $val_bulan2;
				$val_des1 		+= $val_des2;
				$val_o1 		+= $val_o2;
				$val_bln_real1 	+= $val_bln_real2;
				$val_bln_renc1 	+= $val_bln_renc2;

				$no++;
				$item .= '<tr class="t-sb-1">';
				$item .= '<td>'.$no.'</td>';
				$item .= '<td class="sb-1">'.remove_spaces($v2['nama_cabang']).'</td>';
				$item .= '<td class="text-right">'.check_value($val_bulan2).'</td>';
				$item .= '<td class="text-right">'.check_value($val_des2).'</td>';
				$item .= '<td class="text-right">'.check_value($val_o2).'</td>';
				$item .= '<td class="text-right">'.check_value($val_bln_renc2).'</td>';
				$item .= '<td class="text-right">'.check_value($val_bln_real2).'</td>';
				$item .= '<td class="text-right">'.custom_format($x2,false,2).$icon.'</td>';
				$item .= '<td class="text-right">'.custom_format($y2,false,2).'</td>';
				$item .= '</tr>';
			}
		else:
			$col_name = 'TOT_'.$v['kode_cabang'];
			$val_bulan1 = 0; if(isset($dt_bulan[$col_name])) $val_bulan1 = $dt_bulan[$col_name];
			$val_des1 = 0; if(isset($dt_des[$col_name])) $val_des1 = $dt_des[$col_name];
			$val_o1 = 0; if(isset($dt_before[$col_name])) $val_o1 = $dt_des[$col_name];

			$val_bln_real1 	= 0;
			if(isset($dt_bulan_current[$col_name])):
				$val_bln_real1 = $dt_bulan_current[$col_name];
			endif;
			$val_bln_renc1 	= 0;
			if($v[$bulan]):
				$val_bln_renc1 = $v[$bulan];
			endif;
		endif;

		$x1 = 0;
		if($val_bln_real1 != 0): 
			$x1 = ($val_bln_renc1/$val_bln_real1)*100;
		endif;
		$y1 = 0;
		if($val_bulan1 != 0):
			$y1 = (($val_bln_real1-$val_bulan1)/$val_bulan1)*100;
		endif;

		$data = hitung($v['struktur_cabang'],$x1,$data,$keterangan,$path_file);
		$icon = $data['icon'];
		
		$no++;
		$item .= '<tr>';
		$item .= '<td>'.$no.'</td>';
		$item .= '<th>'.remove_spaces($v['nama_cabang']).'</th>';
		$item .= '<td class="text-right">'.check_value($val_bulan1).'</td>';
		$item .= '<td class="text-right">'.check_value($val_des1).'</td>';
		$item .= '<td class="text-right">'.check_value($val_o1).'</td>';
		$item .= '<td class="text-right">'.check_value($val_bln_renc1).'</td>';
		$item .= '<td class="text-right">'.check_value($val_bln_real1).'</td>';
		$item .= '<td class="text-right">'.custom_format($x1,false,2).$icon.'</td>';
		$item .= '<td class="text-right">'.custom_format($y1,false,2).'</td>';
		$item .= '</tr>';
	}
	$this->session->set_userdata(array('control_total' => $data));
	echo $item;
?>