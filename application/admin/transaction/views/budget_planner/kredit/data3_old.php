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
                        			$item .= '<td class="sub-1">'.$v->coa . $v->account_name.'</td>';
                        		
                              $bgedit ="#ffbb33";
                              $contentedit ="true"; 
                              $id = "id";     
                              $contentedit ="true" ;
                            //	$item .= '<td>'.custom_format($v->rate,false,2).'</td>';
                              $item .= '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="'.$contentedit.'" contenteditable="true" class="edit-value text-right edited" data-name="'.str_replace(' ', "_", $v->account_name).'_'.$v->coa.'-table1'.'-'.'2020'.'-'.$v->coa.'-'.user('kode_anggaran').'-'.$cabang.'" data-id="'.$v->id.'" data-value="'.$v->rate.'">'.custom_format($v->rate,false,2).'</div></td>';
                                         
                              $coa_formula = ["1454321","1454327"];
                              if (in_array($v->coa, $coa_formula)) {
                                    $bgedit ="";
                                    $contentedit ="true"; 
                                    $id = "id";     
                                    $contentedit ="true" ;
                              }else{
                                    $bgedit ="#ffbb33";
                                    $contentedit ="true"; 
                                    $id = "id";     
                                    $contentedit ="true" ;
                              }



                      //    debug($detail_tahun);die;
                          $i_bln=0 ; 
                          foreach ($detail_tahun as $d => $value) {	
                              $i_bln ++;

                              $vfield = 'P_'. sprintf("%02d", $value['bulan']);
                              $vfield1 = 'hasil'. $value['bulan'];

                  				    $j1 = 'JML_'.   $value['tahun'] . sprintf("%02d", $value['bulan']);
                  				    $$j1 = 0;
                              $j0 = '';

                              	foreach ($list_kd as $kd => $vd) {     
                                  $jakhir = 0;      		
                              		if($value['tahun'] == $vd->tahun_core && $v->coa == $vd->coa){	
                                      // cari netto untuk ditampilkan sebagai default
                                      foreach ($B as $val) {
                                        $vdefault = 0;
                                        $vnetto_1 = 0;
                                        $vnetto = 0;        
                                        if($val->glwnco == $v->coa ){ 
                                          
                                          $vnetto1  = (($val->hasil9 * -1) - ($val->hasil10 * -1));
                                          if($vnetto1 > 0 ) {
                                            $vnetto = $vnetto1;
                                            $vdefault = $vnetto + ($val->hasil9 * -1) ;
                                          }else{
                                            $vdefault=($val->hasil9 * -1) ;
                                          }  
                                       //   
                                       //   if($vdefault != 0 && $i_bln == 1) {
                                       //     $$j1 = ($vdefault + ($val->hasil9 * -1)) + $val->hasil9 * -1 ;
                                       //   }

                                          $j0 = 'JML_'. $value['tahun'] . sprintf("%02d", $value['bulan']);  

                                          if($value['bulan'] > 1) {
                                            $j0 = 'JML_'. $value['tahun'] . sprintf("%02d", $value['bulan']-1);                                              
                                          }    

                                          if($value['bulan'] == 1) {
                                              $$j0 = 'JML_' . $awal_anggaran . '12' ;
                                          }
                                            

                                          if(!isset($$j0))  $$j0 = 0;
                                          if($awal_anggaran == substr($j0,4) && $vnetto !=0) {  
                                              $$j1 = $vdefault + $vdefault;
                                          }elseif ($vnetto==0) {
                                              $$j1 = $vdefault ;
                                          }else{
                                              $$j1 = (float) $vdefault + (float) $$j0;
                                          }

                                        
                                        }

                                      }

                                      if($vd->$vfield != 0) $$j1 = $vd->$vfield;
                                        
                                      if($v->coa =='1454321' && $value['tahun'] == user('tahun_anggaran')) {
                                              $$j1 = $jum_produktif1[$vfield1] - $jml_nonkup1[$vfield]; 
                                        }

                                        if($v->coa =='1454321' && $value['tahun'] == user('tahun_anggaran') - 1) {
                                              $$j1 = $jum_produktif0[$vfield1] - $jml_nonkup1[$vfield]; 
                                        }

                                        if($v->coa =='1454327' && $value['tahun'] == user('tahun_anggaran')) {
                                              $$j1 = $jum_konsumtif0[$vfield1] - $jml_nonloan1[$vfield]; 
                                        }

                                        if($v->coa =='1454327' && $value['tahun'] == user('tahun_anggaran') - 1) {
                                              $$j1 = $jum_konsumtif0[$vfield1] - $jml_nonloan0[$vfield]; 
                                        }
                              		}
                              	}


                                  $item .= '<td style="background: '.$bgedit.'"><div style="background:'.$bgedit.'" style="min-height: 10px; width: 50px; overflow: hidden;" contenteditable="'.$contentedit.'" contenteditable="true" class="edit-value text-right edited" data-name="'.str_replace(' ', "_", $v->account_name).'_'.$v->coa.'-table3'.'-'.substr($j1,4).'-'.$v->coa.'-'.$value['id_tahun_anggaran'].'-'.$cabang.'" data-id="'.$v->id.'" data-value="'.$$j1.'">'.custom_format($$j1).'</div></td>';


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

                              $item .= '<td class="text-right">'.custom_format($vnetto).'</td>' ;
                              $item .= '<td class="border-none"></td>' ;	
                              $item .= '<td class="text-right">'.custom_format($bulan0).'</td>' ;
                              $item .= '<td class="text-right">'.custom_format($bulan01).'</td>' ;
                  		        $item .= '</tr>';
                  	}
                  }
                  $item .= '</tr>';    
                              $item .= '<tr>';
                              $item .= '<td></td>';                            
                              $item .= '<td>TOTAL '.strtoupper($vgr['account_name']).'</td>'; 
                              foreach ($detail_tahun as $d => $value) {      
                                  $item .= '<td></td>';
                              }       
                              $item .= '</tr>';     
            }       
      //      die;              
	else:
		$item .= '<tr><td>Data tidak ditemukan</td></tr>';
	endif;
	echo $item;
?>