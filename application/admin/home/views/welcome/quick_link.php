<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<div class="card-header text-center">
		<?php echo setting('deskripsi'); ?>		
	</div>
    <div class="card-body p-1">
	<div class="main-container p-4 mb-sm-4 text-center">

		
		<?php if(setting('running_text') && uri_segment(1) == 'transaction' && !uri_segment(2)): ?>
		<marquee class="mb-3"><span style="font-size: 33px"><?= setting('running_text') ?></span></marquee>
		<?php endif; ?>

		<?php if (count($quick_link) > 0) { foreach($quick_link as $q) { ?>
		<a href="<?php echo base_url(uri_segment(1).'/'.$q->target); ?>" class="quick-link" title="<?php echo $q->nama; ?>">
			<span class="icon"><i class="<?php echo $q->icon ? $q->icon : 'fa-database'; ?>"></i></span>
			<span class="text"><?php echo lang($q->target,$q->nama); ?></span>
		</a>
		<?php }} else { ?>
		<div class="alert alert-warning">
			<div class="alert-icon"><i class="fa-exclamation"></i></div>
			<div class="alert-description">Tidak ditemukan sub-menu, dikarenakan akun ini tidak mempunyai akses untuk membuka sub-menu dari menu ini. Silahkan hubungi administrator untuk keterangan lebih lanjut.</div>
		</div>
		<?php } ?>

		<?php if(setting('logo_first') && uri_segment(1) == 'transaction' && !uri_segment(2)): ?>
		<div class="mt-2">
			<img style="max-width: 100%;width: 100%;height: 450px" class="img" src="<?= base_url(dir_upload('setting').setting('logo_first')) ?>">
		</div>
		<?php endif; ?>
	</div>
</div>
<div class="text-center card-footer pr-3 pl-4 pb-2 pt-2">
	<a href="<?php echo base_url('info/version'); ?>" class="app-version cInfo pt-2 d-inline-block" data-smallmodal aria-label="<?php echo lang('histori_versi'); ?>"><?php echo setting('title').' v'.APP_VERSION; ?></a>
	<br>
	<span class="text"><?php echo setting('company'); ?></span>
	<br>
	<span class="text"><?php echo nl2br(setting('alamat_perusahaan')); ?></span>
</div>