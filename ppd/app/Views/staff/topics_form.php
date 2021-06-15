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
        <h3 class="page-title"><?php echo isset($topic) ? lang('Admin.top.editTopic') : lang('Admin.top.newTopic'); ?></h3>
    </div>
</div>
<!-- End Page Header -->



<div class="card">
    <div class="card-body">
        <?php
        if (isset($error_msg)) {
            echo '<div class="alert alert-danger">' . $error_msg . '</div>';
        }
        if (isset($success_msg)) {
            echo '<div class="alert alert-success">' . $success_msg . '</div>';
        }
        echo form_open('', ['id' => 'manageForm'], ['do' => 'submit']);
        ?>
        <div class="form-group">
            <label><?php echo lang('Admin.form.topicName'); ?></label>
            <input type="text" class="form-control" name="name" value="<?php echo set_value('name', (isset($topic) ? $topic->name : '')); ?>">
        </div>
        <div class="form-group">
            <label><?php echo lang('Admin.form.type'); ?></label>
            <select name="public" class="form-control custom-select">
                <?php
                $default = set_value('public', (isset($topic) ? $topic->public : 1));
                foreach (['1' => lang('Admin.form.public'), '0' => lang('Admin.form.private')] as $k => $v) {
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
            <button class="btn btn-primary"><?php echo lang('Admin.form.submit'); ?></button>
            <a href="<?php echo site_url(route_to('staff_topics')); ?>" class="btn btn-secondary"><?php echo lang('Admin.form.goBack'); ?></a>
        </div>
        <?php
        echo form_close();
        ?>
    </div>
</div>
<?php
$this->endSection();
