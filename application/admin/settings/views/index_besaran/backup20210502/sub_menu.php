<div class="main-container p-0">
	<div class="tab-app">
		<ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
			<?php
			$menu = array('ori','','hasil');
			$page = $this->input->get('page');
			foreach ($menu as $v) {
				$link = base_url('settings/index_besaran?page='.$v);
				$active = '';
				if($v == $page): $active = ' active'; endif;
				echo '<li class="nav-item">
					<a class="h-100-per nav-link'.$active.'" href="'.$link.'"><center>'.lang('index_besaran_'.$v).'</center></a>
				</li>';
			}
			?>
		</ul>
		
	</div>
</div>