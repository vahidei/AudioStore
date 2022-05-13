<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Signin Admin</title>


    <!-- Bootstrap core CSS -->
    <link href="<?=base_url('public/css/bootstrap.min.css');?>" rel="stylesheet" >
    <link href="<?= base_url('public/css/bootstrap-icons.css'); ?>" rel="stylesheet"/>

    <meta name="theme-color" content="#7952b3">


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


    <!-- Custom styles for this template -->
    <link href="<?=base_url('public/admin/css/signin.css');?>" rel="stylesheet">
</head>
<body class="text-center">

<main class="form-signin">
    <form action="" method="post">
        <i style="font-size:50px" class="bi bi-music-note-beamed"></i>
        <h1 class="h3 mb-3 fw-normal">Signin Admin</h1>
        <?php
            if(!empty($data['alert-message'])){
                echo '<div class="alert '.$data['alert-class'].'">'.$data['alert-message'].'</div>';
            }
        ?>
        <div class="form-floating">
            <input type="text" name="username" class="form-control" id="floatingInput" placeholder="Username / Email">
            <label for="floatingInput">Username / Email:</label>
        </div>
        <div class="form-floating">
            <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
            <label for="floatingPassword">Password:</label>
        </div>

        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" name="remember"> Remember me
            </label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" name="submit" type="submit">Sign in</button>
        <p class="mt-5 mb-3 text-muted">&copy; 2021 - 2022</p>
    </form>
</main>



</body>
</html>
