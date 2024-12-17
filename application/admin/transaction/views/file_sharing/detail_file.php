<div class="card mb-2">
    <div class="card-header"><?php echo lang('dokumen_file'); ?></div>
    <div class="card-body p-1">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-app table-detail table-normal">

                <thead>
                    <tr>
                        <th width ="60px"><?php echo lang('no'); ?></th>
                        <th width ="400px"><?php echo lang('keterangan'); ?></th>
                        <th><?php echo lang('file'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=0;?>
                    <?php foreach(json_decode($file,true) as $k => $v) { ?>
                    <?php $no++;?>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $k; ?></td>
                        <td><?php echo '<a href="'.base_url('assets/uploads/dokumen_file/'.$v).'" target="_blank">'.$v.'</a></li>'; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>         
</div>

