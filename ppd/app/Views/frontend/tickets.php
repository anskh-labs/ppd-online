<?php

/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('frontend/template');

$this->section('window_title');
echo lang('Client.viewTickets.menu');
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
                        <li class="breadcrumb-item text-light current"><a href="#"><?= lang('Client.viewTickets.menu'); ?></a></li>
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
<h1 class="heading py-5">
    <?php echo lang('Client.viewTickets.title'); ?>
</h1>
<div class="mb-3">
    <?php echo lang('Client.viewTickets.description'); ?>
</div>
<div class="row mb-3">
    <div class="col-lg-5">
        <?php echo form_open('', ['method' => 'get'], ['do' => 'search']); ?>
        <div class="input-group">
            <input type="text" name="code" value="<?php echo esc(\Config\Services::request()->getGet('code')); ?>" class="form-control" placeholder="<?php echo lang('Client.viewTickets.search'); ?>" />
            <div class="input-group-append">
                <button class="btn btn-primary"><i class="fa fa-search"></i></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<?php
if (isset($error_msg)) {
    echo '<div class="alert alert-danger">' . $error_msg . '</div>';
}
if ($pager->getPageCount() > 1) {
    echo $pager->links();
}
?>
<div class="table-responsive mb-3">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th><?php echo lang('Client.form.ticketID'); ?></th>
                <th><?php echo lang('Client.form.lastUpdate'); ?></th>
                <th><?php echo lang('Client.form.topic'); ?></th>
                <th><?php echo lang('Client.form.status'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_data) {
                foreach ($result_data as $item) {
            ?>
                    <tr>
                        <td>
                            <strong>[#<?php echo $item->id; ?>]</strong>
                            <?php echo anchor(site_url(route_to('show_ticket', $item->id)), resume_content($item->subject, 50)); ?>
                        </td>
                        <td><?php echo dateFormat($item->last_update); ?></td>
                        <td><?php echo $item->topic_name; ?></td>
                        <td>
                            <?php
                            switch ($item->status) {
                                case 1:
                                    echo '<span class="badge badge-success">' . lang('Client.form.open') . '</span>';
                                    break;
                                case 2:
                                    echo '<span class="badge badge-dark">' . lang('Client.form.answered') . '</span>';
                                    break;
                                case 3:
                                    echo '<span class="badge badge-warning">' . lang('Client.form.awaiting_reply') . '</span>';
                                    break;
                                case 4:
                                    echo '<span class="badge badge-info">' . lang('Client.form.in_progress') . '</span>';
                                    break;
                                case 5:
                                    echo '<span class="badge badge-danger">' . lang('Client.form.closed') . '</span>';
                                    break;
                            }
                            ?>
                        </td>
                    </tr>
                <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="4"><?php echo lang('Client.error.recordsNotFound'); ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>

    </table>
</div>
<?php
if ($pager->getPageCount() > 1) {
    echo $pager->links();
}
?>

<?php
$this->endSection();
