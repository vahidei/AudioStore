<?php $cookie = get_cookie('lang');
$this->currency = setting('currency' . lang('main.lang_suffix'));
$this->ls = lang('main.lang_suffix');
$this->title = setting('site_title' . lang('main.lang_suffix'));
$counts = products_count();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $this->title; ?></title>
    <meta name="site-language" content="<?= (isset($cookie)) ? $cookie : 'en'; ?>">
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= base_url(lang('main.bootstrap.css')); ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/css/app.css'); ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/css/app_dark.css'); ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/css/bootstrap-icons.css'); ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/css/owl.carousel.min.css'); ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/plugin/home-player/home-player.css'); ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/plugin/home-player/plyr.css'); ?>" rel="stylesheet"/>
    <?php
    $custom_css = lang('main.custom_css');
    if (!empty($custom_css)) echo '<link href="' . base_url($custom_css) . '" rel="stylesheet"/>' . "\n";

    $lang_js = lang('main.lang_js');
    if (!empty($lang_js)) echo "\t" . '<script src="' . base_url($lang_js) . '"></script>' . "\n";
    ?>
    <link rel="shortcut icon" type="image/png" href="/favicon.ico"/>
    <script src="<?= base_url('public/js/jquery-3.6.0.min.js'); ?>"></script>
    <script src="<?= base_url('public/js/popper.min.js'); ?>"></script>
    <script src="<?= base_url('public/js/bootstrap.min.js'); ?>"></script>
    <style>
        .selector-for-some-widget {
            box-sizing: content-box;
        }

        .owl-nav {
        <?=lang('main.reverse_side');?>: 15 px;
        }
    </style>
    <script>
        const base_url = '<?=base_url();?>';
        const currency = '<?=$this->currency;?>';
    </script>
</head>
<body class="bg-light <?= (isset($cookie)) ? $cookie : 'en'; ?>">
<div class="cartBox">
    <div class="card-header bg-white d-flex">
        <a class="closeCartBtn position-absolute start-0 px-3" onclick="closeCart()">
            <i class="bi bi-x"></i>
        </a>
        <h3 class="text-center flex-fill">
            <i class="bi bi-cart"></i> <?= lang('main.cart'); ?>
        </h3>
    </div>
    <div class="card-body">

        <ul>

        </ul>

    </div>
    <div class="card-footer d-flex  bg-white bottom-0 position-absolute w-100 py-3">
        <div class="pe-3">
            <h6 class="fw-light"><?= lang('main.total_amount'); ?>: </h6>
            <h6><span class="totalCartAmount">0</span> <?= $this->currency; ?></h6>
        </div>
        <a class="btn btn-lg flex-fill cartBuyBtn btn-primary px-0">
            <?= lang('main.buy_now'); ?> <i class="bi ms-1 bi-arrow-bar-<?= lang('main.reverse_side'); ?>"></i>
        </a>
    </div>
</div>
<div class="body-container">
    <?php if (isset($_SESSION['user']['isNew'])) { ?>
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="toast welcome-toast bg-success text-white" role="alert" data-bs-autohide="false"
                 aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?= lang('main.welcome_new_user'); ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                </div>
            </div>
        </div>
        <?php unset($_SESSION['user']['isNew']);
    } ?>

    <div class="px-2 px-lg-5 py-4">
        <nav class="rounded light-box-shadow navbar navbar-expand-lg sticky-top navbar-light bg-white bg-opacity-75">

            <div class="container-fluid d-block">
                <a class="navbar-brand float-start" href="<?= base_url(); ?>"><b><span class="text-primary">L</span>OGO
                        <span
                                class="text-danger">H</span>ERE</b></a>

                <button class="navbar-toggler border-0 lh-sm float-end" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <a class="navbar-toggler border-0 text-warning float-end me-2 lh-sm mt-1 px-2 nav-link btn bg-light"
                   href="#">
                    <i class="bi small bi-bookmark-fill"></i>
                </a>
                <a class="navbar-toggler text-success small border-0 float-end me-2 lh-sm mt-1 px-2 nav-link btn bg-light"
                   href="#">
                    <i class="bi small bi-cart"></i> <span class="small"><span
                                class="hdrCartAmount">0</span> <?= $this->currency; ?></span>
                </a>

                <div class="clearfix d-sm-none d-block"></div>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                        <li class="nav-item ps-lg-2 pe-lg-3">
                            <a class="nav-link" href="<?= base_url('track/list'); ?>">
                                <span class="badge rounded-pill bg-danger"><?= $counts['tracks']; ?></span>
                                <?= lang('main.tracks'); ?>
                            </a>
                        </li>
                        <li class="nav-item pe-lg-3">
                            <a class="nav-link" href="<?= base_url('package/list'); ?>">
                                <span class="badge rounded-pill bg-primary"><?= $counts['packages']; ?></span>
                                <?= lang('main.packages'); ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('discount/list'); ?>">
                                <span class="badge rounded-pill bg-warning"><?= $counts['discounts']; ?></span>
                                <?= lang('main.discounts'); ?>
                            </a>
                        </li>
                    </ul>
                    <div class="dropdown-divider"></div>
                    <div>
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0"
                        ">
                        <?php
                        echo '<li class="nav-item d-none ">
                                    <a class="nav-link btn py-1 bg-light me-2 text-reset" href="' . base_url('user/saved') . '"><i class="bi bi-search"></i>
                                    </a>
                                </li>';
                        echo '<li class="nav-item d-none d-sm-inline">
                                    <a class="nav-link btn btnCartBox py-1 bg-light text-success me-2" href="#">
                                        <i class="bi bi-cart me-1"></i>
                                        <span class="small"><span class="hdrCartAmount">0</span> ' . $this->currency . '</span>
                                    </a>
                                </li>';
                        echo '<li class="nav-item d-none d-sm-inline">
                                    <a class="nav-link btn py-1 bg-light me-2 text-danger" href="' . base_url('user/saved') . '"><i class="bi bi-bookmark-heart"></i>
                                    </a>
                                </li>';
                        echo '<li class="nav-item">
                                    <div class="btn-group">
                                    <a class="nav-link btn py-1 bg-light me-2 dropdown-toggle" href="#" data-bs-toggle="dropdown"><i class="bi bi-person"></i>
                                    ' . (isset($_SESSION['user']) ? $_SESSION['user']['name'] : '') . '
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">';

                        if (isset($_SESSION['user'])) {
                            echo '<li><a class="dropdown-item" href="' . base_url('user/purchases/1') . '"><i class="bi bi-download me-2 align-middle" style="font-size:18px"></i> ' . lang('main.purchases') . '</a></li>
                                    <li><a class="dropdown-item" href="' . base_url('user/settings') . '"><i class="bi bi-gear me-2 align-middle" style="font-size:18px"></i> ' . lang('main.settings') . '</a></li>
                                    <li><a class="dropdown-item" href="' . base_url('user/logout') . '"><i class="bi bi-box-arrow-right me-2 align-middle" style="font-size:18px"></i> ' . lang('main.logout') . '</a></li>';
                        } else {
                            echo '<li><a class="dropdown-item" href="' . base_url('user/signin') . '"><i class="bi bi-box-arrow-in-left me-2 align-middle" style="font-size:18px"></i> ' . lang('main.signin') . '</a></li>
                                    <li><a class="dropdown-item" href="' . base_url('user/signup') . '"><i class="bi bi-person-plus me-2 align-middle" style="font-size:18px"></i> ' . lang('main.signup') . '</a></li>';
                        }


                        echo '              </ul>
                                    
                                    </div>
                                    

                                </li>';

                        ?>

                        </ul>
                    </div>

                </div>

            </div>

        </nav>
    </div>

    <div id="audio2" class="bg-transparent">
        <audio preload controls>Your browser does not support HTML5
            Audio! 😢
        </audio>
    </div>

    <?php /*  <div draggable="true" class="float-audio-player loading" id="float-audio-player">
        <div class="icon">
            <div class="spinner-border text-white" role="status"></div>
        </div>
        <div class="audio">
            <div class="title"><h6 class="text-light">Track title here...</h6></div>

        </div>
    </div> */ ?>


