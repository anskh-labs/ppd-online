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
            <span class="text-uppercase page-subtitle"><?php echo lang('Admin.settings.menu');?></span>
            <h3 class="page-title"><?php echo lang('Admin.settings.security');?></h3>
        </div>
    </div>
    <!-- End Page Header -->


<?php
if(isset($error_msg)){
    echo '<div class="alert alert-danger">'.$error_msg.'</div>';
}
if(isset($success_msg)){
    echo '<div class="alert alert-success">'.$success_msg.'</div>';
}
?>
    <div class="card">
        <div class="card-body">
            <?php
            echo form_open('',[],['do' => 'submit']);
            ?>
            <div class="form-group">
                <label><?php echo lang('Admin.settings.maxLoginAttempts');?></label>
                <input type="number" step="1" min="1" name="login_attempt" class="form-control" value="<?php echo set_value('login_attempt', site_config('login_attempt'));?>">
            </div>
            <div class="form-group">
                <label><?php echo lang('Admin.settings.minutesIpLocking');?></label>
                <input type="number" step="1" min="1" name="login_attempt_minutes" class="form-control" value="<?php echo set_value('login_attempt_minutes', site_config('login_attempt_minutes'));?>">
            </div>
            <div class="form-group">
                <button class="btn btn-primary"><?php echo lang('Admin.form.save');?></button>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
    </div>
<?php
$this->endSection();
?>
