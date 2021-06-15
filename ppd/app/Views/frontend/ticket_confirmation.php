<?php

/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('frontend/template');

$this->section('window_title');
echo lang('Client.submitTicket.menu');
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
                        <li class="breadcrumb-item text-light"><a href="<?= site_url(route_to('submit_ticket')) ?>"><?= lang('Client.submitTicket.menu') ?></a></li>
                        <li class="breadcrumb-item text-light current"><a href="#"><?= lang('Client.submitTicket.requestReceived') ?></a></li>
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
<div class="row py-5">
    <div class="col-lg-12">
        <h1 class="heading mb-5">
            <?php echo lang('Client.submitTicket.requestReceived'); ?>
        </h1>
    </div>
    <div class="col-lg-12">
        <div class="mb-3">
            <?php echo lang('Client.submitTicket.requestReceivedDescription'); ?>
        </div>
    </div>
    <div class="col-lg-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th colspan="2"><?php echo $ticket->subject; ?></th>
                </tr>
            </thead>
            <tr>
                <td width="140"><?php echo lang('Client.form.ticketID'); ?>: </td>
                <td><?php echo $ticket->id; ?></td>
            </tr>
            <tr>
                <td><?php echo lang('Client.form.fullName'); ?>:</td>
                <td><?php echo $ticket->fullname; ?></td>
            </tr>
            <tr>
                <td><?php echo lang('Client.form.email'); ?>:</td>
                <td><?php echo $ticket->email; ?></td>
            </tr>
            <tr>
                <td><?php echo lang('Client.form.topic'); ?>:</td>
                <td><?php echo $ticket->topic_name; ?></td>
            </tr>
        </table>
    </div>
</div>
<?php
$this->endSection();
