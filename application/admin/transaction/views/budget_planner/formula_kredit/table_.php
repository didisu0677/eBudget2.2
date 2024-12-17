<?php
	$dd[] = 'AMORT PENDAPATAN DAN BEBAN KRD--1454399';
	$dd[] = 'AMORT PENDAPATAN DAN BEBAN KRD PD BULAN';
	$dd[] = 'RATA-RATA AKUMULASI';
	$dd[] = 'AMORT PENDAPATAN DAN BEBAN KRD PD BULAN';

	$item = '';

	foreach ($dd as $k => $v) {
		$dt = explode('--', $v);
		$name = $dt[0];
		$code = '';
		if(isset($dt[1])){ $code = $dt[1]; }
		$item .= '<tr>';
		$item .= '<td>'.$code.'</td>';
		$item .= '<td>'.$name.'</td>';

		$hasil = '';
		$hasil0 = '';
		$n = 0;
		$x = 0;
		$n1 =0;
		$default = 0;
		for ($i = $tahun[0]['bulan_terakhir_realisasi'] -1; $i <= $tahun[0]['bulan_terakhir_realisasi']; $i++) { 
			$hasil = 'hasil' . $i;

			switch ($k) {
			  case 0:
			   	$n = custom_format(view_report($real_akhir[$hasil]));
			    break;
			  case 1:
			  	if($i == $tahun[0]['bulan_terakhir_realisasi']) {
			  		$hasil0 = 'hasil' . ($i-1) ;
			   		$n = custom_format(view_report($real_akhir[$hasil] - $real_akhir[$hasil0]));
			   	}else{
			   		$n ="";
			   	}
			    break;  
			  case 2:
			  	if($i == $tahun[0]['bulan_terakhir_realisasi']) {
			   		$n = custom_format(0.5,false,2);
			   	}else{
			   		$n ="";
			   	}
			    break;  
			 case 3:
			  	if($i == $tahun[0]['bulan_terakhir_realisasi']) {
			   		$hasil0 = 'hasil' . ($i-1) ;
			   		$x = $real_akhir[$hasil] - $real_akhir[$hasil0];
			   		$n1 = $x * 0.5 ;
			   		$n = custom_format(view_report($n1));
			   	}else{
			   		$n ="";
			   	}
			    break;    
			} 

			$item .= '<td class="text-right">'.$n.'</td>';
		}

	   	$hasil0 = 'hasil' . ($tahun[0]['bulan_terakhir_realisasi'] -1) ;
	   	$hasil1 = 'hasil' . $tahun[0]['bulan_terakhir_realisasi'];
   		$x = $real_akhir[$hasil1] - $real_akhir[$hasil0];
   		$n1 = $x * 0.5 ;
   		$default = $n1 + $x ;
   		$default0 = $real_akhir[$hasil1];

   		$n = 0 ;
   		$v = '' ;
   		$where = [
   			'kode_anggaran' => $anggaran->kode_anggaran,
   			'kode_cabang'	=> $kode_cabang,
   			'tahun'			=> $anggaran->tahun_anggaran,
   			'glwnco'		=> $code,
   		];
   		$dataSaved = [];
		foreach ($detail_tahun as $k2 => $v2) {
			$n++;
			$v = 'hasil' . $n;
			$$v = 0;
			if($k==0) {
				if($n==1){
					$$v = $default0 + $n1 ;  
					$nv = custom_format(view_report($$v));
				}else{
					$v0 = 'hasil' . ($n-1);
					$$v = $$v0 + $n1 ;
					$nv = custom_format(view_report($$v));
				}
			}else{
				$nv ='';
			}

			if($code):
				$dataSaved[$v2->tahun]['bulan_'.$v2->bulan] = $$v;
			endif;

			$item .= '<td><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right edited" data-name="bulan_'.$v2->bulan.'" data-id="'.$v2->tahun.'-'.$code.'" data-value="'.$nv.'">'.$nv.'</div></td>';
		}
		$item .= '</tr>';
		if($code):
			ckForSaved($where,$dataSaved);
		endif;
	}
	echo $item;

	function ckForSaved($where,$data){
		foreach ($data as $k => $v) {
			$parent_id = "0";
			if($k != $where['tahun']):
				$parent_id = $where['kode_cabang'];
			endif;
			$ck = get_data('tbl_formula_kredit',[
				'select' => 'id',
				'where'	 => [
					'kode_cabang' 	=> $where['kode_cabang'],
					'kode_anggaran'	=> $where['kode_anggaran'],
					'glwnco'		=> $where['glwnco'],
					'parent_id'		=> $parent_id,
				]
			])->row();

			if($ck):
				update_data('tbl_formula_kredit',$v,'id',$ck->id);
			else:
				$dt = $v;
				$dt['kode_anggaran'] = $where['kode_anggaran'];
				$dt['kode_cabang'] 	 = $where['kode_cabang'];
				$dt['glwnco']		 = $where['glwnco'];
				$dt['parent_id']	 = $parent_id;
				insert_data('tbl_formula_kredit',$dt);
			endif;
		}
	}
?> 