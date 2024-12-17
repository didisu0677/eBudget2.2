<?php
	$item = '';
	if(count($list_k)>0):
		$vnetto = 0;
            $no = 0 ;
            foreach ($grup_kr as $gr => $vgr) {  
                        $item .= '<tr>'; 
                        $item .= '<td></td>';    
                        $item .= '<td>'.strtoupper($vgr['account_name']).'</td>';  
      		 
                  foreach ($list_k as $k => $v) {
                        if($vgr['grup'] == $v->grup) {
                              $no++;      
                        			$item .= '<tr>';
                        			$item .= '<td>'.$no.'</td>';
                        			$item .= '<td class="sub-1">'.$v->coa . str_repeat('&nbsp;', 10). remove_spaces($v->account_name).'</td>';
                        		
                              $bgedit = bgEdit();
                              $contentedit ="true"; 
                              $id = "id";     
                              $contentedit ="true" ;
                            //	$item .= '<td>'.custom_format($v->rate,false,2).'</td>';
                              $item .= '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="'.$contentedit.'" contenteditable="true" class="edit-value text-right" data-name="'.str_replace(' ', "_", $v->account_name).'_'.$v->coa.'-table1'.'-'.'000000'.'-'.$v->coa.'-'.$tahun[0]['id'].'-'.$cabang.'" data-id="'.$v->id.'" data-value="'.$v->rate.'">'.custom_format($v->rate,false,2).'</div></td>';
                                         
                              $coa_formula = ["1454321","1454327"];
                              if (in_array($v->coa, $coa_formula)) {
                                    $bgedit ="";
                                    $contentedit ="true"; 
                                    $id = "id";     
                                    $contentedit ="true" ;
                              }else{
                                    $bgedit = bgEdit();
                                    $contentedit ="true"; 
                                    $id = "id";     
                                    $contentedit ="true" ;
                              }                         

                          $i_bln=0 ; 
                          foreach ($detail_tahun as $d => $value) {	
                              $i_bln ++;

                              $vfield = 'P_'. sprintf("%02d", $value['bulan']);
                              $vfield1 = 'hasil'. $value['bulan'];

                  				    $j1 = 'JML_'.   $value['tahun'] . sprintf("%02d", $value['bulan']);
                              $tot = 'total_'. $value['tahun'] . sprintf("%02d", $value['bulan']);
                  				    $$j1 = 0;
                              $j0 = '';
                              $$tot =0;
                              	foreach ($list_kd as $kd => $vd) {     
                                  $jakhir = 0;      		
                              		if($value['tahun'] == $vd->tahun_core && $v->coa == $vd->coa){	

                                      $$j1 = $vd->$vfield;
                              		}
                              	}

                                  $item .= '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="'.$contentedit.'" contenteditable="true" class="edit-value text-right" data-name="'.str_replace(' ', "_", $v->account_name).'_'.$v->coa.'-table3'.'-'.substr($j1,4).'-'.$v->coa.'-'.$value['id_tahun_anggaran'].'-'.$cabang.'" data-id="'.$v->id.'" data-value="'.$$j1.'">'.custom_format(view_report($$j1)).'</div></td>';


                              }
                       
                              $item .= '<td class="border-none"></td>' ;	
                              $vnetto = 0;
                              $bulan0 = 0;
                              $bulan01 = 0;
                      		    
                              foreach ($B as $val) {
                              	if($val->glwnco == $v->coa){
                              		$vnetto = ($val->hasil9 * -1) - ($val->hasil10 * -1) ;
                              		if($vnetto < 0) $vnetto = 0;
                                          $bulan0 = $val->hasil9 * -1;
                                          $bulan01 = $val->hasil10 * -1;
                              	}
                              }

                              foreach ($list_kd as $k => $val_kd) {
                                if($val_kd->coa == $v->coa){
                                  $vnetto = $val_kd->netto;
                                }  
                              }


                              $item .= '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="'.$contentedit.'" contenteditable="true" class="edit-value text-right" data-name="'.str_replace(' ', "_", $v->account_name).'_'.$v->coa.'-table7'.'-'.substr($j1,4).'-'.$v->coa.'-'.$value['id_tahun_anggaran'].'-'.$cabang.'" data-id="'.$v->id.'" data-value="'.$vnetto.'">'.custom_format(view_report($vnetto)).'</div></td>';

                              $item .= '<td class="border-none"></td>' ;	
                              $item .= '<td class="text-right">'.custom_format(view_report($bulan0)).'</td>' ;
                              $item .= '<td class="text-right">'.custom_format(view_report($bulan01)).'</td>' ;
                  		        $item .= '</tr>';


                  	}
                  }

            $item .= '</tr>';    
            $item .= '<tr>';
            $item .= '<td></td>';                            
            $item .= '<td><font><B>TOTAL '.strtoupper($vgr['account_name']).'</B></font></td>'; 
            $item .= '<td></td>'; 
            foreach ($detail_tahun as $d => $value) {     
                $tot = 'total_'. $value['tahun'] . sprintf("%02d", $value['bulan']);
                $field = 'P_' . sprintf("%02d", $value['bulan']);
                $$tot = 0;
                foreach ($list_sum as $vsum) {
                  if($vgr['grup'] == $vsum->grup && $value['tahun'] == $vsum->tahun_core){
                      $$tot = $vsum->$field ;
                  }
                }
                $item .= '<th class="text-right">'.custom_format(view_report($$tot)).'</th>';
            }       
            $item .= '</tr>';     
            }       
      //      die;              
	else:
		$item .= '<tr><td>Data tidak ditemukan</td></tr>';
	endif;
	echo $item;
?>