<?php
	$item 			= '';
	$arr_laba 		= ['4152128','4195011','5132012','5195011'];
	$arr_add_laba 	= ['4100000','4500000','4800000'];
	$arr_min_laba 	= ['5100000','5500000','5800000'];
	$arr_total_laba = [];

	$arrData = [
		'bulan_terakhir' 	=> $bulan_terakhir,
		'stored'			=> $stored,
		'cabang'			=> $cabang,
		'anggaran'			=> $anggaran,
		'akses_ubah'		=> $akses_ubah,
		'access_additional'		=> $access_additional,
		'arr_laba'			=> $arr_laba,
	];
	$getAdjSel = array_search('selisih', array_column($adj, 'type'));
	if(strlen($getAdjSel)>0):
		$arrData['selisih'] = $adj[$getAdjSel];
	endif;

	foreach ($coa as $k => $v) {

		$dt_more = More($detail,$v->glwnco,1,$arrData);
		$item2 	 = '';
		if($dt_more['status']):
			$item2 .= $dt_more['item'];
		endif;

		$minus = $v->kali_minus;
		$bln_trakhir = (float) $v->{'TOT_'.$cabang};
		$bln_trakhir = kali_minus($bln_trakhir,$minus);
		$value 		 = (float) $bln_trakhir/$bulan_terakhir;
		$changed 	 = json_decode($v->changed);

		$keyStored = array_search($v->glwnco, array_column($stored, 'glwnco'));

		$item .= '<tr>';
		$item .= '<td>'.$v->glwsbi.'</td>';
		$item .= '<td>'.$v->glwnco.'</td>';
		$item .= '<td>'.remove_spaces($v->glwdes).'</td>';

		$dataSaved = [
			'kode_anggaran' => $anggaran->kode_anggaran,
			'kode_cabang'	=> $cabang,
			'glwnco'		=> $v->glwnco,
		];
		$tambah = 0;
		$status_warning = false;
		$bln_sebelumnya = 0;
		for($i=1;$i<=12;$i++){
			$bulan = "bulan_".$i;
			if($v->glwnco != '59999'):
				if($dt_more['status']):
					$val = $dt_more['res'][$bulan];
				elseif(in_array($bulan, $changed)):
					$val = $v->{$bulan};
					$tambah = $val;
				elseif(strlen($keyStored)>0):
					if($stored[$keyStored]['tipe'] == 'biaya'):// pd save biaya di kali minus, makanya disini di kali minus sesuai coa
						$val = $tambah += kali_minus($stored[$keyStored][$bulan],$minus);
					else:
						$val = $tambah = $stored[$keyStored][$bulan];
					endif;
				else:
					$val = $tambah += $value;
				endif;

				if(in_array($v->glwnco, $arr_add_laba)):
					if(isset($arr_total_laba[$bulan])): $arr_total_laba[$bulan] += $val; else: $arr_total_laba[$bulan] = $val; endif;
				elseif(in_array($v->glwnco, $arr_min_laba)):
					if(isset($arr_total_laba[$bulan])): $arr_total_laba[$bulan] -= $val; else: $arr_total_laba[$bulan] = $val*-1; endif;
				endif;
			else:
				$val = $arr_total_laba[$bulan];
			endif;
			
			$result = round(view_report($val),-2);
			if($i != 0 and $bln_sebelumnya>$result):
				$status_warning = true;
			endif;
			$bln_sebelumnya = $result;
			if($akses_ubah && !$dt_more['status'] && $v->glwnco != '59999' && $access_additional):
				$item .= '<td class="text-right"><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right" data-name="bulan_'.$i.'" data-id="'.$v->glwnco.'" data-value="'.$result.'">'.custom_format($result).'</div></td>';
			else:
				$idnya = ''; if($v->glwnco == '59999') $idnya = ' id="labarugi_'.$i.'"';
				$item .= '<td class="text-right"'.$idnya.'>'.custom_format($result).'</td>';
			endif;
			$dataSaved[$bulan] = $val;
		}
		$btn_warning = '';
		if($status_warning):
			$btn_warning = '<button type="button" class="btn btn-danger btn-warning" title="Terdapat Nilai yang lebih rendah dari bulan sebelumnya. silahkan cek"><i class="fa-exclamation"></i></button>';
		endif;
		$item .= '<td class="border-none text-center button">'.$btn_warning.'</td>';	
		$item .= '<td class="bg-grey text-right">'.custom_format(round(view_report($value),-2)).'</td>';
		$item .= '<td class="bg-grey text-right">'.custom_format(round(view_report($bln_trakhir),-2)).'</td>';
		$item .= '</tr>';
		$item .= $item2;
		checkForSave($dataSaved);
	}

	// adjusment
	$item .= '<tr><td class="border-none">.</td></tr><tr><td class="border-none">.</td></tr>';
	$item .= '<tr><td class="border-none"></td><td class="border-none"></td><td class="border-none"><strong>Laba rugi yang di inginkan
</strong></td></tr>';
	$item .= '<tr><td class="border-none">.</td></tr>';

	$getAdjPd = array_search('pdbulan', array_column($adj, 'type'));
	$btn = '';
	if($access_additional && $akses_ubah):
		$btn = '<button type="button" class="btn btn-danger btn-remove" data-id="'.$cabang.'" title="Hapus"><i class="fa-times"></i></button>';
	endif;
	$item .= '<tr>';
	$item .= '<td></td>';
	$item .= '<td class="button">'.$btn.'</td>';
	$item .= '<td>Laba rugi pd bulan</td>';
	for ($i=1; $i <= 12 ; $i++) {
		$nilaiAdjPd = 0;
		if(strlen($getAdjPd)>0){ $bulan = 'bulan_'.$i; $nilaiAdjPd = $adj[$getAdjPd][$bulan]; }
		$result = round(view_report($nilaiAdjPd),-2);
		if($akses_ubah):
			$item .= '<td style="background:'.bgEdit().'"><div id="input'.$i.'" style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value edited text-right cuan pdbulan" data-name="bulan_'.$i.'" data-id="pdbulan">'.custom_format($result).'</div></td>';
		else:
			$item .= '<td class="text-right">'.custom_format($result).'</td>';
		endif;
	}
	$item .= '<td class="border-none"></td><td class="border-none"></td><td class="border-none"></td>';
	$item .= '</tr>';

	$getAdjSel = array_search('sdbulan', array_column($adj, 'type'));
	$item .= '<tr>';
	$item .= '<td></td>';
	$item .= '<td class="button"></td>';
	$item .= '<td>Laba rugi s.d bulan</td>';
	for ($i=1; $i <= 12 ; $i++) {
		$nilaiAdjSel = 0;
		if(strlen($getAdjPd)>0){ $bulan = 'bulan_'.$i; $nilaiAdjSel = $adj[$getAdjPd][$bulan]; }
		$result = round(view_report($nilaiAdjSel),-2);
		if($akses_ubah):
			$item .= '<td><div id="hasil'.$i.'" style="min-height: 10px; width: 100%; overflow: hidden;" class="edit-value edited text-right sdbulan" data-name="bulan_'.$i.'" data-id="sdbulan" data-value="">'.custom_format($result).'</div></td>';
		else:
			$item .= '<td class="text-right">'.custom_format($result).'</td>';
		endif;
	}
	$item .= '<td class="border-none"></td><td class="border-none"></td><td class="border-none"></td>';
	$item .= '</tr>';

	if($access_additional):
		$item .= "<tr><td class = border-none>.</td></tr>";
		$item .= '<tr style = "background: #FFF;">';
		$item .= '<td></td>';
		$item .= '<td></td>';
		$item .= '<td>Selisih</td>';
		// $hasil2 = $val->hasil2 * -1;
		for ($i=1; $i <= 12 ; $i++) { 
		$item .= '<td><div  id="selisih'.$i.'" style="min-height: 10px; width: 100%; overflow: hidden;"  class="edit-value edited text-right adj" data-name="bulan_'.$i.'" data-id="selisih" data-value=""></div></td>';
		}
		$item .= '</tr>';
		$item .= '</tr>';

		$item .= '<tr style = "background: #FFF;hover:none">';
		$item .= '<td class = border-none></td>';
		$item .= '<td class = border-none></td>';
		$item .= '<td class = border-none>Di :
					<select id = "di">
						<option value = "t"> + </option>
						<option value = "k"> - </option>
					</select>
					Ke : 
					<select id = "ke">
						<option value = "4152128"> 4152128 </option>
						<option value = "4195011"> 4195011 </option>
						<option value = "5132012"> 5132012 </option>
						<option value = "5195011"> 5195011 </option>
					</select>
				</td>';
		$item .= '<td class = border-none><button class = "btn btn-primary btn-adj" style="width:100%;max-height:20px;padding: 0px;">Lakukan</button></td>';
		$item .= '</tr>';
	else:
		$item .= '<tr style = "background: #FFF;hover:none">';
		$item .= '<td class = border-none></td>';
		$item .= '<td class = border-none></td>';
		$item .= '<td class = border-none></td>';
		$item .= '<td class = border-none><button class = "btn btn-primary btn-adj" style="width:100%;max-height:20px;padding: 0px;">Lakukan</button></td>';
		$item .= '</tr>';
	endif;
	$item .= '<tr><td class="border-none">.</td></tr>';
	$item .= '<tr><td class="border-none">.</td></tr>';

	// end adjusment

	$this->db->query("call stored_budget_nett('labarugi','".$cabang."','".$anggaran->kode_anggaran."')");
	echo $item;

	function More($data,$id,$count,$arrData){
		$item 			= '';
		$status 		= false;
		$bulan_terakhir = (float) $arrData['bulan_terakhir'];
		$anggaran 		= $arrData['anggaran'];
		$cabang 		= $arrData['cabang'];
		$stored 		= $arrData['stored'];
		$akses_ubah 	= $arrData['akses_ubah'];
		$access_additional 	= $arrData['access_additional'];
		$arr_laba 		= $arrData['arr_laba'];
		$res 			= [];
		if(isset($data[$count][$id])):
			$status = true;
			$sub = ($count);

			foreach ($data[$count][$id] as $k => $v) {

				$dt_more = More($data,$v->glwnco,($count+1),$arrData);
				$item2   = '';
				if($dt_more['status']):
					$item2 .= $dt_more['item'];
				endif;

				$minus = $v->kali_minus;
				$bln_trakhir = (float) $v->{'TOT_'.$cabang};
				$bln_trakhir = kali_minus($bln_trakhir,$minus);
				$value 		 = (float) $bln_trakhir/$bulan_terakhir;
				$changed 	 = json_decode($v->changed);

				$keyStored = array_search($v->glwnco, array_column($stored, 'glwnco'));

				$item .= '<tr>';
				$item .= '<td>'.$v->glwsbi.'</td>';
				$item .= '<td>'.$v->glwnco.'</td>';
				$item .= '<td class="sb-'.$sub.'">'.remove_spaces($v->glwdes).'</td>';

				$dataSaved = [
					'kode_anggaran' => $anggaran->kode_anggaran,
					'kode_cabang'	=> $cabang,
					'glwnco'		=> $v->glwnco,
				];
				
				$tambah = 0;
				$status_warning = false;
				$bln_sebelumnya = 0;
				$status_edit 	= false;
				for($i=1;$i<=12;$i++){
					$bulan = "bulan_".$i;
					if($dt_more['status']):
						$val = $dt_more['res'][$bulan];
					elseif(isset($arrData['selisih']) && $arrData['selisih']['glwnco'] == $v->glwnco):
						$val = $arrData['selisih'][$bulan];
					elseif(in_array($bulan, $changed)):
						$status_edit = true;
						$val = $v->{$bulan};
						$tambah = $val;
					elseif(strlen($keyStored)>0):
						if($stored[$keyStored]['tipe'] == 'biaya'):// pd save biaya di kali minus, makanya disini di kali minus sesuai coa
							$val = $tambah += kali_minus($stored[$keyStored][$bulan],$minus);
						else:
							$val = $tambah = $stored[$keyStored][$bulan];
						endif;
					else:
						$val = $tambah += $value;
					endif;
					$result = round(view_report($val),-2);
					if($i != 0 and $bln_sebelumnya>$result):
						$status_warning = true;
					endif;
					$bln_sebelumnya = $result;

					if($akses_ubah && !$dt_more['status'] && $access_additional):
						$item .= '<td class="text-right"><div style="min-height: 10px; width: 100%; overflow: hidden;" contenteditable="true" class="edit-value text-right" data-name="bulan_'.$i.'" data-id="'.$v->glwnco.'" data-value="'.$result.'">'.custom_format($result).'</div></td>';
					else:
						$item .= '<td class="text-right">'.custom_format($result).'</td>';
					endif;
					if(isset($res[$bulan])): $res[$bulan] += $val; else: $res[$bulan] = $val; endif;
					$dataSaved[$bulan] = $val;
				}

				$btn_warning = '';
				if($status_warning):
					$btn_warning = '<button type="button" class="btn btn-danger btn-warning" title="Terdapat Nilai yang lebih rendah dari bulan sebelumnya. silahkan cek"><i class="fa-exclamation"></i></button>';
				endif;

				if(in_array($v->glwnco, $arr_laba) && $status_edit && isset($data['selisih']['glwnco'])):
					$btn_warning .= '<button type="button" class="btn btn-danger btn-info-coa" title="Terdapat Aksi edit dan aksi selisih pada coa yang sama. silahkan cek"><i class="fa-exclamation"></i></button>';
				endif;

				$item .= '<td class="border-none text-center button">'.$btn_warning.'</td>';	
				$item .= '<td class="bg-grey text-right">'.custom_format(round(view_report($value),-2)).'</td>';
				$item .= '<td class="bg-grey text-right">'.custom_format(round(view_report($bln_trakhir),-2)).'</td>';
				$item .= '</tr>';
				$item .= $item2;
				checkForSave($dataSaved);
			}
		endif;
		return [
			'status' => $status,
			'item'	 => $item,
			'res'	 => $res,
		];
	}

	function checkForSave($data){
		$ck = get_data('tbl_labarugi',[
			'select' => 'id',
			'where'	 => [
				'kode_anggaran' => $data['kode_anggaran'],
				'kode_cabang' 	=> $data['kode_cabang'],
				'glwnco' 		=> $data['glwnco'],
			]
		])->row();
		if($ck):
			update_data('tbl_labarugi',$data,'id',$ck->id);
		else:
			$data['changed'] = '[]';
			insert_data('tbl_labarugi',$data);
		endif;
	}
?>