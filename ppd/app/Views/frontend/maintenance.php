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
        </div>
    </nav>
    <div class="clearfix"></div>
    <!-- END OF NAVBAR -->

    <section>
        <div class="container py-5">
            <div class="row text-center">
                <div class="col-lg-12">
                    <h1 style="text-transform: uppercase;" class="display-4"><?php echo $body; ?></h1>
                </div>
            </div>
        </div>
    </section>
    <div class="copyright bg-accent text-white text-center py-3">
        <div class="container">Copyright &copy; <?= date('Y') ?> - <?= lang('Client.site.company') ?></div>
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