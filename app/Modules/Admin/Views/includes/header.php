<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Admin Panel</title>

    <!-- Bootstrap core CSS -->
    <link href="<?=base_url('public/css/bootstrap.min.css');?>" rel="stylesheet">
    <link href="<?=base_url('public/admin/css/app.css');?>" rel="stylesheet">
    <link href="<?=base_url('public/css/select2.min.css');?>" rel="stylesheet">
    <link href="<?=base_url('public/css/select2-bootstrap-5-theme.css');?>" rel="stylesheet">

    <script src="<?=base_url('public/js/jquery-3.6.0.min.js');?>"></script>

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>

<script>
    const base_url = '<?=admin_base_url();?>';
</script>
    <!-- Custom styles for this template -->
    <link href="<?=base_url('public/admin/css/dashboard.css');?>" rel="stylesheet">
</head>
<body>

<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Duet Studio</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

</header>

<div class="container-fluid">
    <div class="row">
