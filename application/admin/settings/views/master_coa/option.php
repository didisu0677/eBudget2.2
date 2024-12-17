<?php 
option();
foreach ($neraca['coa'] as $k => $v) {
	option($v->id,$v->glwnco.' - '.remove_spaces($v->glwdes));
	more_option($v->glwnco,0,$neraca);
}

foreach ($labarugi['coa'] as $k => $v) {
	option($v->id,$v->glwnco.' - '.remove_spaces($v->glwdes));
	more_option($v->glwnco,0,$labarugi);
}

?>