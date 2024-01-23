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
        <span class="text-uppercase page-subtitle"><?php echo lang('Admin.users.menu'); ?></span>
        <h3 class="page-title"><?php echo isset($user) ? lang('Admin.users.editUser') : lang('Admin.users.newUser'); ?></h3>
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
?>
<div class="card">
    <div class="card-body">
        <?php
        echo form_open('', [], ['do' => 'submit']);
        ?>
        <div class="form-group">
            <label><?php echo lang('Admin.form.fullName'); ?></label>
            <input type="text" name="fullname" class="form-control" value="<?php echo set_value('fullname', (isset($user) ? $user->fullname : '')); ?>">
        </div>
        <div class="form-group">
            <label><?php echo lang('Admin.form.email'); ?></label>
            <input type="text" name="email" class="form-control" value="<?php echo set_value('email', (isset($user) ? $user->email : '')); ?>">
        </div>
        <div class="form-group">
            <label><?php echo lang('Admin.form.phone'); ?></label>
            <input type="tel" name="phone" class="form-control" value="<?php echo set_value('phone', (isset($user) ? $user->phone : '')); ?>">
        </div>
        <div class="form-group">
            <label><?php echo lang('Admin.form.location'); ?></label>
            <select name="location" class="form-group custom-select" id="location">
                <?php
                $default = set_value('location', (isset($user) ? $user->in_satker : 0));
                foreach ([0 => lang('Admin.form.kampar'), 1 => lang('Admin.form.other')] as $k => $v) {
                    if ($default == $k) {
                        echo '<option value="' . $k . '" selected>' . $v . '</option>';
                    } else {
                        echo '<option value="' . $k . '">' . $v . '</option>';
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label><?php echo lang('Admin.form.status'); ?></label>
            <select name="status" class="form-control custom-select">
                <?php
                $default = set_value('active', (isset($user) ? $user->status : 1));
                foreach (['1' => lang('Admin.form.active'), '0' => lang('Admin.form.locked')] as $k => $v) {
                    if ($k == $default) {
                        echo '<option value="' . $k . '" selected>' . $v . '</option>';
                    } else {
                        echo '<option value="' . $k . '">' . $v . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <?php
        if (!isset($user)) {
        ?>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="notify" class="custom-control-input" id="notifyUser">
                    <label class="custom-control-label" for="notifyUser"><?php echo lang('Admin.users.notifyNewAccount'); ?></label>
                </div>
            </div>
        <?php
        }
        ?>

        <div class="form-group">
            <button class="btn btn-primary"><?php echo lang('Admin.form.submit'); ?></button>
            <a href="<?php echo site_url(route_to('staff_users')); ?>" class="btn btn-secondary"><?php echo lang('Admin.form.goBack'); ?></a>
        </div>
        <?php
        echo form_close();
        ?>
    </div>
</div>
<?php
$this->endSection();
