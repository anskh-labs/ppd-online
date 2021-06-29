<?php

/**
 * @var $this \CodeIgniter\View\View
 */
$page_controller = isset($page_controller) ? $page_controller : '';
?>
<!DOCTYPE html>
<html lang="<?= $locale ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    echo link_tag('favicon.ico', 'icon', 'image/x-icon') .
        link_tag('assets/components/bootstrap/css/bootstrap.min.css') .
        link_tag('assets/components/fontawesome/css/all.min.css') .
        link_tag('assets/components/feather-icons/feather.css') .
        link_tag('assets/components/select2/css/select2.min.css') .
        link_tag('assets/components/select2/css/select2-bootstrap.min.css') .
        link_tag('assets/helpdeskz/css/frontend.css');
    ?>
    <title><?php $this->renderSection('window_title'); ?></title>
    <style>
        :root {
            --accent-color: #043277;
            --action-color: #327804;
        }
    </style>
    <?php $this->renderSection('css_block'); ?>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-sm navbar-dark" id="navbar">
        <div class="container">
            <a class="navbar-brand d-flex" href="<?= site_url(route_to('home')) ?>">
                <div class="image mr-3"><img src="<?= base_url('assets/helpdeskz/images/logo/logo.png') ?>" alt="LOGO" height="40"></div>
                <div class="text align-self-center">
                    <h3 class="font-weight-bold m-0">PPD-ONLINE</h3>
                </div>
            </a>
        </div>
    </nav>
    <nav class="navbar navbar-expand-lg navbar-dark bg-accent" id="topnav">
        <div class="container">
            <div class="collapse navbar-collapse order-3 order-lg-0" id="navigation">
                <ul class="navbar-nav">
                    <?php if (client_online()) : ?>
                        <li class="nav-item<?php echo (empty(uri_page()) ? ' active' : '') ?>">
                            <a class="nav-link" href="<?= site_url(route_to('home')) ?>"><?= lang('Client.menu.home') ?></a>
                        </li>
                        <li class="nav-item<?php echo (uri_page() === 'kb' ? ' active' : ''); ?>">
                            <a class="nav-link" href="<?php echo site_url(route_to('kb')); ?>"><?php echo lang('Client.kb.menu'); ?></a>
                        </li>
                        <li class="nav-item<?php echo (uri_page() === 'tickets' ? ' active' : ''); ?>">
                            <a class="nav-link" href="<?php echo site_url(route_to('view_tickets')); ?>"><?php echo lang('Client.viewTickets.menu'); ?></a>
                        </li>
                        <li class="nav-item<?php echo (uri_page() === 'submit-ticket' ? ' active' : ''); ?>">
                            <a class="nav-link" href="<?php echo site_url(route_to('submit_ticket')); ?>"><?php echo lang('Client.submitTicket.menu'); ?></a>
                        </li>
                        <li class="nav-item dropdown<?php echo (uri_page() === 'account' ? ' active' : ''); ?>">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php echo lang('Client.account.menu'); ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="<?php echo site_url(route_to('profile')); ?>"><?php echo lang('Client.account.editProfile'); ?></a>
                                <a class="dropdown-item" href="<?php echo site_url(route_to('logout')); ?>"><?php echo lang('Client.account.logout'); ?></a>
                            </div>
                        </li>
                    <?php else : ?>
                        <li class="nav-item dropdown<?php echo (uri_page() === 'auth' ? ' active' : ''); ?>">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"><?= lang('Client.menu.login') ?></a>
                            <div class="dropdown-menu">
                                <a href="<?= base_url(route_to('login')) ?>" class="dropdown-item"><?= lang('Client.menu.newUser') ?></a>
                                <a href="<?= base_url(route_to('getlogin')) ?>" class="dropdown-item"><?= lang('Client.menu.registeredUser') ?></a>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="ml-lg-auto col-12 col-lg-auto mb-2 mb-lg-0 px-0 px-lg-3" style="padding: 0px 10px;">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                            <img src="<?= base_url('assets/helpdeskz/images/lang/' . $locale . '.png') ?>" alt="" height="11">
                            <span class="ml-1"><?= lang('Client.menu.' . $locale) ?></span></a>
                        <div class="dropdown-menu">
                            <a href="<?= change_locale_url($locale) ?>" class="dropdown-item">
                                <img src="<?= base_url('assets/helpdeskz/images/lang/' . change_lang($locale) . '.png') ?>" alt="" height="11">
                                <span class="ml-1"><?= lang('Client.menu.' . change_lang($locale)) ?></span></a>
                        </div>
                    </li>
                </ul>
            </div>
            <button class="navbar-toggler order-2 d-block d-lg-none" data-toggle="collapse" data-target="#navigation">
                <i class="feather-menu"></i>
            </button>
        </div>
    </nav>
    <?php $this->renderSection('breadcrumb'); ?>
    <div class="clearfix"></div>
    <!-- END OF NAVBAR -->

    <section>
        <div class="container">
            <?php $this->renderSection('content'); ?>
        </div>
    </section>
    <footer class="bg-accent py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="d-flex mb-4">
                        <div class="image mr-3"><img src="<?= base_url('assets/helpdeskz/images/logo/bps.png') ?>" alt="Logo BPS" height="40"></div>
                        <div class="text">
                            <h6 class="m-0 text-white">BADAN PUSAT STATISTIK</h6>
                            <div class="m-0 text-white">KABUPATEN ROKAN HULU</div>
                            <div></div>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col"><a href="https://www.facebook.com/bpskabrohul" target="_blank"><i class="fab fa-facebook-f"></i><span class="pl-1">bpskabrohul</span></a></div>
                    </div>
                    <div class="row mb-1">
                        <div class="col"><a href="https://www.instagram.com/bpskabrohul/" target="_blank"><i class="fab fa-instagram"></i><span class="pl-1">bpskabrohul</span></a></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col"><a href="https://rohulkab.bps.go.id" target="_blank"><i class="fab fa-chrome"></i><span class="pl-1">rohulkab.bps.go.id</span></a></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <h6 class="mb-3 text-white" style="text-transform:uppercase;">Pelayanan Statistik Terpadu (PST)</h6>
                    <p>
                        Jl. Kelompok Tani No. 7 Pasir Pengaraian
                    </p>
                    <div class="row mb-1">
                        <div class="col-auto pr-0"><i class="fa fa-phone"></i></div>
                        <div class="col">(62-762) 7392150</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-auto pr-0"><i class="fa fa-envelope"></i></div>
                        <div class="col">pst1407@bps.go.id</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-auto pr-0"><i class="fa fa-map-marked"></i></div>
                        <div class="col"><a href="https://plus.codes/6PG2V8R3+27" target="_blank">V8R3+27 Pematang Berangan, Kabupaten Rokan Hulu, Riau</a></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <h6 class="mb-3 text-white" style="text-transform: uppercase;">INFORMASI PELAYANAN</h6>
                    <ul class="nav flex-column">
                        <li class="nav-item"><a href="#" data-toggle="modal" data-target="#modal-maklumat" class="nav-link px-0 py-1">Maklumat Pelayanan</a></li>
                        <li class="nav-item"><a href="#" data-toggle="modal" data-target="#modal-biaya" class="nav-link px-0 py-1">Informasi Biaya</a></li>
                        <li class="nav-item"><a href="#" data-toggle="modal" data-target="#modal-alur" class="nav-link px-0 py-1">Alur Pelayanan</a></li>
                        <li class="nav-item"><a href="#" data-toggle="modal" data-target="#modal-standar" class="nav-link px-0 py-1">Standar Pelayanan</a></li>
                        <li class="nav-item"><a href="#" data-toggle="modal" data-target="#modal-pengaduan" class="nav-link px-0 py-1">Pengaduan</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <div class="copyright bg-accent text-white text-center py-3">
        <div class="container">Copyright &copy; <?= date('Y') ?> - <?= lang('Client.site.company') ?></div>
    </div>
    <!-- modal -->
    <div class="modal fade" id="modal-maklumat">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content form-modal-content scroll">
                <div class="modal-header">
                    <h5 class="modal-title text-dark col text-center">Maklumat Pelayanan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <img src="<?= base_url('assets/helpdeskz/images/info/maklumat.png') ?>" width="100%" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-biaya">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content form-modal-content scroll">
                <div class="modal-header">
                    <h5 class="modal-title text-dark col text-center">Informasi Biaya</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <img src="<?= base_url('assets/helpdeskz/images/info/biaya.png') ?>" width="100%" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-alur">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content form-modal-content scroll">
                <div class="modal-header">
                    <h5 class="modal-title text-dark col text-center">Alur Pelayanan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <img src="<?= base_url('assets/helpdeskz/images/info/alur-layanan.jpg') ?>" width="100%" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-standar">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content form-modal-content scroll">
                <div class="modal-header">
                    <h5 class="modal-title text-dark col text-center">Standar Pelayanan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <h3 class="display-6 pl-0 ml-0 text-center">Persyaratan Pelayanan</h3>
                    <ol type="A">
                        <li>Layanan <i>Offline</i>
                            <ol type="1" class="ml-3">
                                <li>Pengguna layanan datang langsung ke Unit Pelayanan Statistik Terpadu (PST) BPS Kab. Rokan Hulu</li>
                                <li>Pengguna layanan mengisi bukutamu</li>
                            </ol>
                        </li>
                        <li>Layanan <i>Online</i>
                            <ol type="1" class="ml-3">
                                <li>Pengguna layanan mengunjungi layanan secara <i>online</i> dengan alamat https://webapps.bps.go.id/rokanhulukab/ppd-online/</li>
                                <li>Pengguna layanan memiliki nomor HP dan alamat email</li>
                                <li>Pengguna layanan mengisi bukutamu elektronik</li>
                            </ol>
                        </li>
                    </ol>
                    <h3 class="display-6 pl-0 ml-0 text-center">Sistem, mekanisme, Prosedur</h3>
                    <ol type="A">
                        <li>Layanan <i>Offline</i>
                            <ol type="1" class="ml-3">
                                <li>Pengguna layanan datang langsung ke Unit Pelayanan Statistik Terpadu (PST) BPS Kab. Rokan Hulu</li>
                                <li>Pengguna layanan menemui petugas PST</li>
                                <li>Pengguna layanan mengisi bukutamu</li>
                                <li>Pengguna layanan menjelaskan data yang dibutuhkan</li>
                                <li>Petugas memberikan penjelasan dan/atau data yang dibutuhkan</li>
                                <li>Pengguna layanan pulang</li>
                            </ol>
                        </li>
                        <li>Layanan <i>Online</i>
                            <ol type="1" class="ml-3">
                                <li>Pengguna layanan mengunjungi layanan secara <i>online</i> dengan alamat https://webapps.bps.go.id/rokanhulukab/ppd-online/</li>
                                <li>Pengguna layanan mengisi bukutamu</li>
                                <li>Pengguna layanan membuat tiket permintaan data</li>
                                <li>Petugas memproses permintaan data</li>
                                <li>Pengguna layanan unduh dan/atau memberikan feedback</li>
                            </ol>
                        </li>
                    </ol>
                    <h3 class="display-6 pl-0 ml-0 text-center">Jangka Waktu Pelayanan</h3>
                    <ol type="1">
                        <li>Pengguna layanan <i>offline</i> akan segera dilayani oleh petugas, maksimal 5 menit setelah mengisi bukutamu.</li>
                        <li>Pengguna layanan <i>online</i> akan diproses secara langsung oleh sistem, jika data yang dibutuhkan sudah tersedia di
                            <i>knowledge base</i> maka pengguna layanan akan dapat langsung mengunduh data tersebut, jika tidak maka petugas akan menyelesaikan
                            permintaan data tersebut maksimal 5 hari kerja.
                        </li>
                    </ol>
                    <h3 class="display-6 pl-0 ml-0 text-center">Biaya/Tarif</h3>
                    <p>Pengguna layanan tidak dipungut biaya.</p>
                    <h3 class="display-6 pl-0 ml-0 text-center">Produk Layanan</h3>
                    <p>Layanan permintaan data dan konsultasi data/statistik.</p>
                    <h3 class="display-6 pl-0 ml-0 text-center">Penanganan Pengaduan, Saran, Kritik</h3>
                    <p>Pengguna layanan dapat menyampaikan pengaduan, saran, kritik secara <i>offline</i> melalui kotak saran atau penyampaian
                        langsung ke petugas, atau secara <i>online</i> dengak mengakses alamat https://pengaduan.bps.go.id.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-pengaduan">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content form-modal-content scroll">
                <div class="modal-header">
                    <h5 class="modal-title text-dark col text-center">Pengaduan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <h3 class="display-6 pl-0 ml-0 text-center">Penanganan Pengaduan, Saran, Kritik</h3>
                    <p>Pengguna layanan dapat menyampaikan pengaduan, saran, kritik secara <i>offline</i> melalui kotak saran atau penyampaian
                        langsung ke petugas, atau secara <i>online</i> dengak mengakses alamat https://pengaduan.bps.go.id.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Javascript -->
    <?php
    echo script_tag('assets/components/jquery/jquery.min.js') .
        script_tag('assets/components/bootstrap/js/bootstrap.bundle.min.js') .
        script_tag('assets/components/select2/js/select2.min.js') .
        script_tag('assets/helpdeskz/js/helpdesk.js');
    $this->renderSection('script_block');
    ?>
</body>

</html>