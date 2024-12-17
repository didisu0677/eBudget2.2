<?php
	$item = '';
	$no = 1;
	if($detail):
		$item .= '<tr>';
		$item .= '<td>'.$no.'</td>';

		$item2 = '';
		if(isset($dt['l'.($level+1)][$detail->glwnco])):
			foreach ($dt['l'.($level+1)][$detail->glwnco] as $k => $v) { //level 1
				$no += 1;
				$item2 .= '<tr>';
				$item2 .= '<td>'.$no.'</td>';
				$item3 = '';
				if(isset($dt['l'.($level+2)][$v->glwnco])):
					foreach ($dt['l'.($level+2)][$v->glwnco] as $k2 => $v2) { //level 2
						$no += 1;
						$item3 .= '<tr>';
						$item3 .= '<td>'.$no.'</td>';
						$item4 = '';
						if(isset($dt['l'.($level+3)][$v2->glwnco])):
							foreach ($dt['l'.($level+3)][$v2->glwnco] as $k3 => $v3) { //level 3
								$no += 1;
								$item4 .= '<tr>';
								$item4 .= '<td>'.$no.'</td>';
								$item5 = '';
								if(isset($dt['l'.($level+4)][$v3->glwnco])):
									foreach ($dt['l'.($level+4)][$v3->glwnco] as $k4 => $v4) { //level 4
										$no += 1;
										$item5 .= '<tr>';
										$item5 .= '<td>'.$no.'</td>';
										$item6 = '';
										if(isset($dt['l'.($level+5)][$v4->glwnco])):
											foreach ($dt['l'.($level+5)][$v4->glwnco] as $k5 => $v5) {
												$no += 1;
												$item6 .= '<tr>';
												$item6 .= '<td>'.$no.'</td>';

												$key6 = multidimensional_search($dt_bulan, array(
								                    'glwnco' => $v5->glwnco,
								                ));

												$val   = $v5->{$bulan};
								                $real6 = 0;
								                $penc6 = 0;
								                $plus6 = 0;
								                if(strlen($key6)>0):
								                	$dd6 	= $dt_bulan[$key6];
								                	$real6 	= $dd6[$tot];
								                endif;
								                if($val != 0):
													$penc6 = ($real6/$val);
												endif;
												$plus6 = ($real6-$val);

												$item6 .= '<td>'.$v5->glwnco.'</td>';
												$item6 .= '<td class="sb-3">'.remove_spaces($v5->glwdes).'</td>';
												$item6 .= '<td class="text-right">'.check_value($val).'</td>';
												$item6 .= '<td class="text-right">'.check_value($real6).'</td>';
												$item6 .= '<td class="text-right">'.custom_format($penc6,false,2).'</td>';
												$item6 .= '<td class="text-right">'.check_value($plus6).'</td>';
												$item6 .= '</tr>';
											}
										else:

										endif;

										$key5 = multidimensional_search($dt_bulan, array(
						                    'glwnco' => $v4->glwnco,
						                ));

										$val   = $v4->{$bulan};
						                $real5 = 0;
						                $penc5 = 0;
						                $plus5 = 0;
						                if(strlen($key5)>0):
						                	$dd5 	= $dt_bulan[$key5];
						                	$real5 	= $dd5[$tot];
						                endif;
						                if($val != 0):
											$penc5 = ($real5/$val);
										endif;
										$plus5 = ($real5-$val);

										$item5 .= '<td>'.$v4->glwnco.'</td>';
										$item5 .= '<td class="sb-">'.remove_spaces($v4->glwdes).'</td>';
										$item5 .= '<td class="text-right">'.check_value($val).'</td>';
										$item5 .= '<td class="text-right">'.check_value($real5).'</td>';
										$item5 .= '<td class="text-right">'.custom_format($penc5,false,2).'</td>';
										$item5 .= '<td class="text-right">'.check_value($plus5).'</td>';
										$item5 .= '</tr>';
										$item5 .= $item6;
									}
								else:

								endif;

								$key4 = multidimensional_search($dt_bulan, array(
				                    'glwnco' => $v3->glwnco,
				                ));

								$val   = $v3->{$bulan};
				                $real4 = 0;
				                $penc4 = 0;
				                $plus4 = 0;
				                if(strlen($key4)>0):
				                	$dd4 	= $dt_bulan[$key4];
				                	$real4 	= $dd4[$tot];
				                endif;
				                if($val != 0):
									$penc4 = ($real4/$val);
								endif;
								$plus4 = ($real4-$val);

								$item4 .= '<td>'.$v3->glwnco.'</td>';
								$item4 .= '<td class="sb-3">'.remove_spaces($v3->glwdes).'</td>';
								$item4 .= '<td class="text-right">'.check_value($val).'</td>';
								$item4 .= '<td class="text-right">'.check_value($real4).'</td>';
								$item4 .= '<td class="text-right">'.custom_format($penc4,false,2).'</td>';
								$item4 .= '<td class="text-right">'.check_value($plus4).'</td>';
								$item4 .= '</tr>';
								$item4 .= $item5;
							}
						else:

						endif;

						$key3 = multidimensional_search($dt_bulan, array(
		                    'glwnco' => $v2->glwnco,
		                ));

						$val   = $v2->{$bulan};
		                $real3 = 0;
		                $penc3 = 0;
		                $plus3 = 0;
		                if(strlen($key3)>0):
		                	$dd3 	= $dt_bulan[$key3];
		                	$real3 	= $dd3[$tot];
		                endif;
		                if($val != 0):
							$penc3 = ($real3/$val);
						endif;
						$plus3 = ($real3-$val);
						
						$item3 .= '<td>'.$v2->glwnco.'</td>';
						$item3 .= '<td class="sb-2">'.remove_spaces($v2->glwdes).'</td>';
						$item3 .= '<td class="text-right">'.check_value($val).'</td>';
						$item3 .= '<td class="text-right">'.check_value($real3).'</td>';
						$item3 .= '<td class="text-right">'.custom_format($penc3,false,2).'</td>';
						$item3 .= '<td class="text-right">'.check_value($plus3).'</td>';
						$item3 .= '</tr>';
						$item3 .= $item4;
					}
				else:

				endif;

				$key2 = multidimensional_search($dt_bulan, array(
                    'glwnco' => $v->glwnco,
                ));

				$val   = $v->{$bulan};
                $real2 = 0;
                $penc2 = 0;
                $plus2 = 0;
                if(strlen($key2)>0):
                	$dd2 	= $dt_bulan[$key2];
                	$real2 	= $dd2[$tot];
                endif;
                if($val != 0):
					$penc2 = ($real2/$val);
				endif;
				$plus2 = ($real2-$val);

				$item2 .= '<td>'.$v->glwnco.'</td>';
				$item2 .= '<td class="sb-1">'.remove_spaces($v->glwdes).'</td>';
				$item2 .= '<td class="text-right">'.check_value($val).'</td>';
				$item2 .= '<td class="text-right">'.check_value($real2).'</td>';
				$item2 .= '<td class="text-right">'.custom_format($penc2,false,2).'</td>';
				$item2 .= '<td class="text-right">'.check_value($plus2).'</td>';
				$item2 .= '</tr>';
				$item2 .= $item3;
			}
		else:

		endif;

		$key1 = multidimensional_search($dt_bulan, array(
            'glwnco' => $detail->glwnco,
        ));

		$val   = $detail->{$bulan};
        $real1 = 0;
        $penc1 = 0;
        $plus1 = 0;
        if(strlen($key1)>0):
        	$dd1 	= $dt_bulan[$key1];
        	$real1 	= $dd1[$tot];
        endif;
        if($val != 0):
			$penc1 = ($real1/$val);
		endif;
		$plus1 = ($real1-$val);

		$item .= '<td>'.$detail->glwnco.'</td>';
		$item .= '<td>'.remove_spaces($detail->glwdes).'</td>';
		$item .= '<td class="text-right">'.check_value($val).'</td>';
		$item .= '<td class="text-right">'.check_value($real1).'</td>';
		$item .= '<td class="text-right">'.custom_format($penc1,false,2).'</td>';
		$item .= '<td class="text-right">'.check_value($plus1).'</td>';
		$item .= '</tr>';
		$item .= $item2;
	endif;
	echo $item;
?>