<div class="card-body table-responsive">
    <table class="table table-bordered table-app table-detail table-normal">
        <tr>
            <th width="200"><?php echo lang('nama_cabang'); ?></th>
            <td colspan="3"><?php echo $nama_cabang; ?></td>
        </tr>
        <tr>
            <th><?php echo lang('upload_by'); ?></th>
            <td colspan="3"><?php echo date_indo($create_at) . ($create_by !="" ? ' oleh : ' : "") . $create_by; ?></td>
        </tr>
        <tr>
            <th><?php echo lang('link'); ?></th>
            <td colspan="3"><?php  echo '<a href="'.$link.'" target="_blank">'.$link.'</a>';
                    ?>
            </td>
        </tr>
    </table>
</div>
