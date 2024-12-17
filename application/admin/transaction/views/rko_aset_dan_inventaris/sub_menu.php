<div class="main-container p-0">
	<div class="tab-app">
		<ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
			<?php
			foreach ($menu as $k => $v) {
				$active = '';
				if($k == 0): $active = ' active'; endif;
				echo '<li class="nav-item">
					<a class="dt-sub-menu h-100-per nav-link'.$active.'" data-id="'.$v->id.'" href="javascript:;"><center>'.$v->keterangan.'</center></a>
				</li>';
			}
			?>
		</ul>
		
	</div>
</div>