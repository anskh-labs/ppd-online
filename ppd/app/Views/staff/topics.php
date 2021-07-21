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
        <span class="text-uppercase page-subtitle"><?php echo lang('Admin.top.menu'); ?></span>
        <h3 class="page-title"><?php echo lang('Admin.top.topics'); ?></h3>
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
    '<input type="hidden" name="topic_id" id="topic_id">' .
    form_close();
?>
<div class="card mb-3">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-5">
                <h6 class="mb-0"><?php echo lang('Admin.form.manage'); ?></h6>
            </div>
            <div class="col-sm-7">
                <a href="<?php echo site_url(route_to('staff_new_topic')); ?>" class="btn btn-primary btn-sm float-right"><i class="fa fa-plus"></i> <?php echo lang('Admin.top.newTopic'); ?></a>
            </div>
        </div>
    </div>
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th><?php echo lang('Admin.top.topic'); ?></th>
                <th><?php echo lang('Admin.top.tickets'); ?></th>
                <th><?php echo lang('Admin.form.type'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($top_list)) {
                foreach ($top_list as $topic) {
            ?>
                    <tr>
                        <td>
                            <?php echo $topic->name; ?>
                        </td>
                        <td>
                            <?php echo count_tickets_topic($topic->id); ?>
                        </td>
                        <td><?php echo ($topic->public ? lang('Admin.form.public') : lang('Admin.form.private')); ?></td>
                        <td class="text-right">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('Admin.form.action'); ?>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="<?php echo site_url(route_to('staff_edit_topic', $topic->id)); ?>"><i class="far fa-edit"></i> <?php echo lang('Admin.top.editTopic'); ?></a>
                                    <button class="dropdown-item" onclick="removeTopic(<?php echo $topic->id; ?>);"><i class="far fa-trash-alt"></i> <?php echo lang('Admin.top.removeTopic'); ?></button>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="4"><?php echo lang('Admin.error.recordsNotFound'); ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>

<?php
echo $pager->links();
$this->endSection();
