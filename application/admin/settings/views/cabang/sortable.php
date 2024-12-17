<ol class="sortable">
	<?php foreach($cabang[0] as $m0) { ?>
	<li id="menuItem_<?php echo $m0->id; ?>" class="module" data-module="<?php echo $m0->kode_cabang; ?>">
		<div class="sort-item">
			<span class="item-title"><?php echo $m0->nama_cabang; ?></span>
		</div>
		<?php if(isset($cabang[$m0->id]) && count($cabang[$m0->id]) > 0) { ?>
		<ol>
			<?php foreach($cabang[$m0->id] as $m1) { ?>
			<li id="menuItem_<?php echo $m1->id; ?>" data-module="<?php echo $m0->kode_cabang; ?>">
				<div class="sort-item">
					<span class="item-title"><?php echo $m1->nama_cabang; ?></span>
				</div>
				<?php if(isset($cabang[$m1->id]) && count($cabang[$m1->id]) > 0) { ?>
				<ol>
					<?php foreach($cabang[$m1->id] as $m2) { ?>
					<li id="menuItem_<?php echo $m2->id; ?>" data-module="<?php echo $m0->kode_cabang; ?>">
						<div class="sort-item">
							<span class="item-title"><?php echo $m2->nama_cabang; ?></span>
						</div>
						<?php if(isset($cabang[$m2->id]) && count($cabang[$m2->id]) > 0) { ?>
						<ol>
							<?php foreach($cabang[$m2->id] as $m3) { ?>
							<li id="menuItem_<?php echo $m3->id; ?>" data-module="<?php echo $m0->kode_cabang; ?>">
								<div class="sort-item">
									<span class="item-title"><?php echo $m3->nama_cabang; ?></span>
								</div>
							</li>
							<?php } ?>
						</ol>
						<?php } ?>
					</li>
					<?php } ?>
				</ol>
				<?php } ?>
			</li>
			<?php } ?>
		</ol>
		<?php } ?>
	</li>
	<?php } ?>
</ol>