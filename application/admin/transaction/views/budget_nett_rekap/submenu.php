<div class="main-container p-0">
	<div class="tab-app">
		<ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
			<li class="nav-item">
				<a class="h-100-per nav-link <?php if($page == 'neraca') echo 'active'; ?>" href="<?= base_url($path) ?>"><center>Neraca</center></a>
			</li>
			<li class="nav-item">
				<a class="h-100-per nav-link <?php if($page == 'labarugi') echo 'active'; ?>" href="<?= base_url($path.'/labarugi') ?>"><center>Laba Rugi</center></a>
			</li>
			<li class="nav-item">
				<a class="h-100-per nav-link <?php if($page == 'rekaprasio') echo 'active'; ?>" href="<?= base_url($path.'/rekaprasio') ?>"><center>Rekap Rasio</center></a>
			</li>
		</ul>
		
	</div>
</div>