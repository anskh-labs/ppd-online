<?php

/**
 * @var $this \CodeIgniter\View\View
 * 
 * {locale}/staff/agents/roles/new
 * {locale}/staff/agents/roles/edit/{id}
 */
$this->extend('staff/template');
$this->section('content');
?>
<!-- Page Header -->
<div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
        <span class="text-uppercase page-subtitle"><?php echo lang('Admin.agents.rolesMenu'); ?></span>
        <h3 class="page-title"><?php echo isset($role) ? lang('Admin.agents.rolesEdit') : lang('Admin.agents.rolesNew'); ?></h3>
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
if (isset($role)) {
    echo form_open('', ['id' => 'roleForm']);
?>
    <input type="hidden" name="action" value="change_status">
    <input type="hidden" name="role_id" id="role_id" value="<?= $role->role_id ?>">
    <input type="hidden" name="role_access" id="role_access">
<?php
    echo form_close();
}
?>
<div class="card">
    <div class="card-body">
        <?php
        echo form_open('', [], ['do' => 'submit']);
        ?>
        <div class="form-group">
            <label><?php echo lang('Admin.agents.rolesName'); ?></label>
            <input type="text" name="rolename" class="form-control" value="<?php echo set_value('rolename', (isset($role) ? $role->role_name : '')); ?>">
        </div>
        <?php if (isset($role)) : ?>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th><?php echo lang('Admin.agents.rolesAccess'); ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($list_access as $access) : ?>
                            <tr>
                                <td><?php echo $access; ?></td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="<?= $access ?>" <?php echo ($role->{$access} > 0 ? 'checked' : ''); ?> onchange="changeRoleStatus('<?= $access ?>')" <?php echo ($role->{$access} > 1 ? 'disabled' : ''); ?>>
                                        <label class="custom-control-label" for="<?= $access ?>"><?php echo ($role->{$access} > 0 ? lang('Admin.form.enable') : lang('Admin.form.disable')); ?></label>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?> 
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <div class="form-group">
            <button class="btn btn-primary"><?php echo lang('Admin.form.submit'); ?></button>
            <a href="<?php echo site_url(route_to('staff_agents_roles')); ?>" class="btn btn-secondary"><?php echo lang('Admin.form.goBack'); ?></a>
        </div>
        <?php
        echo form_close();
        ?>
    </div>
</div>
<?php
$this->endSection();
