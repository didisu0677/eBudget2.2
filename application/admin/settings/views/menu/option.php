<?php 
option();
foreach($menu[0] as $m0) {
	if($m0->is_active !=0) {
		option($m0->id,$m0->nama);	
		foreach($menu[$m0->id] as $m1) {
			if($m1->is_active !=0) {
				option($m1->id,'&nbsp; |-----'.$m1->nama);
				foreach($menu[$m1->id] as $m2) {
					if($m2->is_active !=0) {
						option($m2->id,'&nbsp; &nbsp; &nbsp; |-----'.$m2->nama);
					}	
				}
			}	
		}
	} 
}
?>