<?php
	$item = '<ol class="sortable">';
	if($tipe == 1):
		foreach ($neraca['coa'] as $k => $v) {
			$item .= '<li id="menuItem_'.$v->glwnco.'" class="module" data-module="'.$v->glwnco.'">';
				$item .= '<div class="sort-item">
					<span class="item-title">'.$v->glwnco.' - '.remove_spaces($v->glwdes).'</span>
				</div>';
				$item .= more($v->glwnco,0,$neraca,$v->glwnco);
			$item .= '</li>';
		}
	elseif($tipe == 2):
		foreach ($labarugi['coa'] as $k => $v) {
			$item .= '<li id="menuItem_'.$v->glwnco.'" class="module" data-module="'.$v->glwnco.'">';
				$item .= '<div class="sort-item">
					<span class="item-title">'.$v->glwnco.' - '.remove_spaces($v->glwdes).'</span>
				</div>';
				$item .= more($v->glwnco,0,$labarugi,$v->glwnco);
			$item .= '</li>';
		}
	endif;
	
	$item .= '</ol>';

	echo $item;

	function more($id,$count,$coa,$glwnco){
		$item = '';
		if(isset($coa['coa'.$count][$id])):
			$count2 = $count + 1;
			$item .= '<ol>';
			foreach ($coa['coa'.$count][$id] as $k => $v) {
				$item .= '<li id="menuItem_'.$v->glwnco.'" data-module="'.$id.'">';
					$item .= '<div class="sort-item">
						<span class="item-title">'.$v->glwnco.' - '.remove_spaces($v->glwdes).'</span>
					</div>';
					$item .= more($v->glwnco,$count2,$coa,$v->glwnco);
				$item .= '</li>';
			}
			$item .= '</ol>';
		endif;
		return $item;
	}
?>