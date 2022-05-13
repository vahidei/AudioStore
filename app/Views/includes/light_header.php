<?php $cookie = get_cookie('lang');
        $this->ls = lang('main.lang_suffix');
        $this->currency = setting('currency' . $this->ls);
        $this->title = setting('site_title' . lang('main.lang_suffix'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= lang('home.title'); ?></title>
    <meta name="site-language" content="<?= (isset($cookie)) ? $cookie : 'en'; ?>">
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= base_url(lang('home.bootstrap.css')); ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/css/app.css'); ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/css/app_dark.css'); ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/css/bootstrap-icons.css'); ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/css/owl.carousel.min.css'); ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/plugin/home-player/home-player.css'); ?>" rel="stylesheet"/>
    <link href="<?= base_url('public/plugin/home-player/plyr.css'); ?>" rel="stylesheet"/>
    <script src="<?= base_url('public/js/jquery-3.6.0.min.js'); ?>"></script>
    <script src="<?= base_url('public/js/popper.min.js'); ?>"></script>
    <script src="<?= base_url('public/js/bootstrap.min.js'); ?>"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=6LdZpxYcAAAAABsRrAN2To890s6_YzDdJd1RBQkf"></script>
    <?php
    $custom_css = lang('main.custom_css');
    if(!empty($custom_css)) echo '<link href="'.base_url($custom_css).'" rel="stylesheet"/>'."\n";

    $lang_js = lang('main.lang_js');
    if(!empty($lang_js)) echo "\t".'<script src="'.base_url($lang_js).'"></script>'."\n";
    ?>
    <link rel="shortcut icon" type="image/png" href="/favicon.ico"/>
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
    </script>
</head>
<body class="bg-light">


