<?php

/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('frontend/template');

$this->section('window_title');
echo site_config('windows_title');
$this->endSection();

$this->section('breadcrumb');
?>
<section class="pathway">
    <div class="container">
        <nav aria-label="breadcrumb" class="navbar-dark">
            <div class="row">
                <div class="col-12">
                    <ol class="nav py-2" style="background-color: transparent!important;">
                        <li class="breadcrumb-item text-light"><a href="<?= site_url(route_to('home')) ?>"><?= lang('Client.menu.home') ?></a></li>
                        <li class="breadcrumb-item text-light current"><a href="#"><?= lang('Client.account.editProfile'); ?></a></li>
                    </ol>
                </div>
            </div>
        </nav>
    </div>
</section>
<?php
$this->endSection();

$this->section('content');
?>
<h1 class="heading my-5">
    <?php echo lang('Client.account.editProfile'); ?>
</h1>
<div class="row mb-5">
    <div class="col-lg-7">
        <?php
        echo form_open('', [], ['do' => 'submit']);
        if (isset($error_msg)) {
            echo '<div class="alert alert-danger">' . $error_msg . '</div>';
        }
        if (\Config\Services::session()->has('form_success')) {
            echo '<div class="alert alert-success">' . \Config\Services::session()->getFlashdata('form_success') . '</div>';
        }
        ?>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                </div>
                <input type="text" name="fullname" placeholder="<?php echo lang('Client.form.fullName'); ?>" class="form-control" value="<?php echo set_value('fullname', client_data('fullname')); ?>">
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                </div>
                <input type="email" name="email" placeholder="<?php echo lang('Client.form.email'); ?>" class="form-control" value="<?php echo set_value('email', client_data('email')); ?>">
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-phone"></i></span>
                </div>
                <input type="tel" name="phone" placeholder="<?php echo lang('Client.form.phone'); ?>" class="form-control" value="<?php echo set_value('phone', client_data('phone')); ?>">
            </div>
        </div>
        <div class="form-group mb-5">
            <label><?php echo lang('Client.form.location'); ?></label>
            <select name="location" class="form-group custom-select" id="location">
                <?php
                $default = set_value('location', client_data('in_rokanhulu'));
                foreach ([0 => lang('Client.form.rokanhulu'), 1 => lang('Client.form.other')] as $k => $v) {
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
            <button class="btn btn-primary"><?php echo lang('Client.form.save'); ?></button>
        </div>
        <?php
        echo form_close();
        ?>
    </div>
</div>
<?php
$this->endSection();
