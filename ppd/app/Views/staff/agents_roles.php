<?php

/**
 * @var $this \CodeIgniter\View\View
 * 
 * {locale}/staff/agents/roles
 */
$this->extend('staff/template');
$this->section('content');
?>
<!-- Page Header -->
<div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
        <span class="text-uppercase page-subtitle">PPD Online</span>
        <h3 class="page-title"><?php echo lang('Admin.agents.roles'); ?></h3>
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
    '<input type="hidden" name="role_id" id="role_id">' .
    form_close();
?>
<div class="card mb-3">
    <div class="card-header">
        <div class="row">
            <div class="col d-none d-sm-block">
                <h6 class="m-0"><?php echo lang('Admin.form.manage'); ?></h6>
            </div>
            <?php if (staff_data('create_roles') == 1) : ?>
                <div class="col text-md-right">
                    <a href="<?php echo site_url(route_to('staff_agents_roles_new')); ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo lang('Admin.agents.rolesNew'); ?></a>
                </div>
            <?php endif; ?>
        </div>

    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="titles">
                <tr>
                    <th><?php echo lang('Admin.agents.rolesName'); ?></th>
                    <th><?php echo lang('Admin.agents.rolesAccess'); ?></th>
                    <th></th>
                </tr>
            </thead>
            <?php if (!isset($roles)) : ?>
                <tr>
                    <td colspan="3">
                        <i><?php echo lang('Admin.error.recordsNotFound'); ?></i>
                    </td>
                </tr>
            <?php else : ?>
                <?php foreach ($roles as $role) : ?>
                    <tr>
                        <td>
                            <?php echo $role->role_name; ?>
                        </td>
                        <td>
                            <?php foreach($list_access as $access): ?>
                                <?php echo ($role->{$access} ? '<small class="badge badge-secondary">' . $access . '</small> ' : ''); ?>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <?php if (staff_data('change_roles') == 1) : ?>
                                <div class="btn-group">
                                    <a href="<?php echo site_url(route_to('staff_agents_roles_edit', $role->role_id)); ?>" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                    <button type="button" onclick="removeRole(<?php echo $role->role_id; ?>)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</div>
<?php
echo $pager->links();
$this->endSection();
