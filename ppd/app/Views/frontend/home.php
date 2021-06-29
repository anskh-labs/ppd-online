<?php

/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('frontend/template');

$this->section('window_title');
echo site_config('windows_title');
$this->endSection();

$this->section('content');
?>
<div class="row py-3">
    <div class="col-md-12">
        <div id="slideCarousel" class="carousel slide" data-ride="carousel">

            <ol class="carousel-indicators">
                <li data-target="#slideCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#slideCarousel" data-slide-to="1" class=""></li>
                <li data-target="#slideCarousel" data-slide-to="2" class=""></li>
                <li data-target="#slideCarousel" data-slide-to="3" class=""></li>
                <li data-target="#slideCarousel" data-slide-to="4" class=""></li>
            </ol>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <a href="<?= base_url('assets/helpdeskz/images/slider/silastik_permintaan_low.jpg') ?>">
                        <img class="d-block w-100" src="<?= base_url('assets/helpdeskz/images/slider/1. Apa itu PPD-online.png') ?>" alt="slide-1">
                    </a>
                </div>
                <div class="carousel-item">
                    <a href="<?= base_url('assets/helpdeskz/images/slider/keuntungan_silastik_low.jpg') ?>">
                        <img class="d-block w-100" src="<?= base_url('assets/helpdeskz/images/slider/2. pelayanan di PPD-Online.png') ?>" alt="slide-2">
                    </a>
                </div>
                <div class="carousel-item">
                    <a href="<?= base_url('assets/helpdeskz/images/slider/layanan_permintaan_low.jpg') ?>">
                        <img class="d-block w-100" src="<?= base_url('assets/helpdeskz/images/slider/3. alur konsultasi.png') ?>" alt="slide-3">
                    </a>
                </div>
                <div class="carousel-item">
                    <a href="<?= base_url('assets/helpdeskz/images/slider/alur_permintaan_low.jpg') ?>">
                        <img class="d-block w-100" src="<?= base_url('assets/helpdeskz/images/slider/4. alur permintaan.png') ?>" alt="slide-4">
                    </a>
                </div>
                <div class="carousel-item">
                    <a href="<?= base_url('assets/helpdeskz/images/slider/alur_konsultasi_low.jpg') ?>">
                        <img class="d-block w-100" src="<?= base_url('assets/helpdeskz/images/slider/5. keuntungan.png') ?>" alt="slide-5">
                    </a>
                </div>
            </div>
            <a class="carousel-control-prev" href="#slideCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#slideCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>
<div class="row py-3 text-center">
    <div class="col-md-12">
        <?php echo form_open(route_to('search'), ['method' => 'get']); ?>
        <h1 class="heading mb-5"><?php echo lang('Client.kb.howCanWeHelpYou1');
                                    echo "<br>";
                                    echo lang('Client.kb.howCanWeHelpYou2'); ?>
        </h1>
        <div class="input-group input-group-lg col-auto align-items-center mb-3">
            <input type="text" name="keyword" value="<?php echo set_value('keyword'); ?>" placeholder="<?php echo lang('Client.kb.search'); ?>" class="form-control">
            <div class="input-group-append">
                <button class="btn btn-primary btn"><i class="fa fa-search"></i></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<?php
$this->endSection();
