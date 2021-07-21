<?php

/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('staff/template');
$this->section('content');
?>
<!-- Page Header -->
<div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
        <span class="text-uppercase page-subtitle"><?php echo lang('Admin.attachments.menu'); ?></span>
        <h3 class="page-title"><?php echo lang('Admin.attachments.attachments'); ?></h3>
    </div>
</div>
<!-- End Page Header -->

<?php
if (isset($error_msg)) {
    echo '<div class="alert alert-danger">' . $error_msg . '</div>';
}
if (isset($success_msg)) {
    echo '<div class="alert alert-success">' . $success_msg . '</div>';
}

echo form_open('', ['id' => 'manageForm'], ['do' => 'remove']) .
    '<input type="hidden" name="file_id" id="file_id">' .
    form_close();
?>
<div class="card mb-3">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-5">
                <h6 class="mb-0"><?php echo lang('Admin.form.manage'); ?></h6>
            </div>
            <?php if (staff_data('create_attachments')) : ?>
            <div class="col-sm-7">
                <a href="<?php echo site_url(route_to('staff_new_attachments')); ?>" class="btn btn-primary btn-sm float-right"><i class="fa fa-plus"></i> <?php echo lang('Admin.attachments.newFile'); ?></a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th><?php echo lang('Admin.attachments.name'); ?></th>
                    <th><?php echo lang('Admin.attachments.type'); ?></th>
                    <th><?php echo lang('Admin.attachments.size'); ?></th>
                    <th><?php echo lang('Admin.attachments.article'); ?></th>
                    <th><?php echo lang('Admin.attachments.ticket'); ?></th>
                    <th><?php echo lang('Admin.attachments.message'); ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($file_list)) {
                    foreach ($file_list as $file) {
                ?>
                        <tr>
                            <td>
                                <span class="knowledgebaseattachmenticon"></span>
                                <i class="fa fa-file-archive-o"></i> <a href="<?php echo current_url() . '?download=' . $file->id; ?>" target="_blank"><?php echo $file->name; ?></a>
                            </td>
                            <td>
                                <?php echo $file->filetype; ?>
                            </td>
                            <td><?php echo number_to_size($file->filesize, 2); ?></td>
                            <td>
                                <?php if($file->article_id>0) : ?>
                                    <a href="<?php echo site_url(route_to('staff_kb_edit_article', $file->article_id));?>"><?php echo $file->article_id;?></a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                            <?php if($file->ticket_id>0) : ?>
                                    <a href="<?php echo site_url(route_to('staff_ticket_view', $file->ticket_id));?>"><?php echo $file->ticket_id;?></a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                            <?php if($file->msg_id>0) : ?>
                                    <a href="<?php echo site_url(route_to('staff_ticket_view', $file->msg_id));?>"><?php echo $file->msg_id;?></a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="text-right">
                            <?php if (staff_data('change_attachments')) : ?>
                                <div class="btn-group">
                                    <a href="<?php echo site_url(route_to('staff_attachments_edit', $file->id));?>" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                    <button type="button" onclick="removeFile(<?php echo $file->id;?>)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                </div>
                            <?php endif; ?>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="7"><?php echo lang('Admin.error.recordsNotFound'); ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
echo $pager->links();
$this->endSection();
