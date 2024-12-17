<?php 
option();
foreach($cabang[0] as $m0) {
	option($m0->id,$m0->nama_cabang);
	foreach($cabang[$m0->id] as $m1) {
		option($m1->id,'&nbsp; |-----'.$m1->nama_cabang);
		foreach($cabang[$m1->id] as $m2) {
			option($m2->id,'&nbsp; &nbsp; &nbsp; |-----'.$m2->nama_cabang);

		}
	}
}
?>