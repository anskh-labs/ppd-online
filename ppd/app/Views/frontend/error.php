<?php

/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('frontend/page_template');
$this->section('window_title');
echo $title;
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
                        <li class="breadcrumb-item text-light current"><a href="#"><?= $title; ?></a></li>
                    </ol>
                </div>
            </div>
        </nav>
    </div>
</section>
<?php
$this->endSection();
$this->section('page_content');
?>
<div class="container">
    <h1 class="heading mb-5">
        <?php echo $title; ?>
    </h1>
    <hr>
    <div class="my-5">
        <p><?php echo $body; ?></p>
    </div>
</div>
<?php
$this->endSection();
