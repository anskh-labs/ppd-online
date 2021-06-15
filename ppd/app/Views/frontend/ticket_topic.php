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
                        <li class="breadcrumb-item text-light current"><a href="#"><?= lang('Client.submitTicket.menu'); ?></a></li>
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
<div class="row mt-5">
    <div class="col">
        <h1 class="heading mb-5">
            <?php echo lang('Client.submitTicket.title'); ?>
        </h1>
        <p><?php echo lang('Client.submitTicket.selectTopic'); ?></p>
        <?php
        if (isset($error_msg)) {
            echo '<div class="alert alert-danger">' . $error_msg . '</div>';
        }
        ?>
    </div>
</div>
<div class="row mb-5">
    <?php
    echo form_open('', [], [
        'do' => 'submit'
    ]);
    ?>
    <div class="col-md-12">
        <div class="form-group">
            <div class="form-group">
                <div class="form-group">
                    <label>
                        <?php echo lang('Client.form.topics'); ?>
                    </label>

                    <?php
                    if ($topics = getTopics()) {
                        foreach ($topics as $item) {
                    ?>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="topic<?php echo $item->id; ?>" name="topic" value="<?php echo $item->id; ?>" class="custom-control-input">
                                <label class="custom-control-label" for="topic<?php echo $item->id; ?>"><?php echo $item->name; ?></label>
                            </div>
                    <?php
                        }
                    }
                    ?>

                </div>
            </div>
        </div>

        <div class="form-group">
            <button class="btn btn-primary"><?php echo lang('Client.form.next'); ?></button>
        </div>
    </div>

    <?php
    echo form_close();
    ?>
</div>
<?php
$this->endSection();
$this->section('script_block');
?>
<script type="text/javascript" src="<?php echo base_url('assets/components/bs-custom-file-input/bs-custom-file-input-min.js'); ?>"></script>
<script>
    $(function() {
        $(document).ready(function() {
            bsCustomFileInput.init();
        });
    })
</script>
<?php
$this->endSection();
