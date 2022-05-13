<?php $cookie = get_cookie('lang'); ?>
<!-- Footer -->
<footer class="text-center text-lg-start bg-white-glass text-muted">
    <!-- Section: Social media -->
    <section
            class="justify-content-center justify-content-lg-between p-4 border-bottom"
    >
        <!-- Left -->
        <div class="me-5 d-none d-lg-block float-lg-start">
            <span><?= lang('main.footer_social_content'); ?></span>
        </div>
        <!-- Left -->

        <!-- Right -->
        <div class="float-lg-end">
            <a href="#" class="me-4 text-reset text-decoration-none">
                <i class="bi bi-facebook"></i>
            </a>
            <a href="" class="me-4 text-reset text-decoration-none">
                <i class="bi bi-twitter"></i>
            </a>
            <a href="" class="me-4 text-reset text-decoration-none">
                <i class="bi bi-google"></i>
            </a>
            <a href="" class="me-4 text-reset text-decoration-none">
                <i class="bi bi-instagram"></i>
            </a>
            <a href="" class="me-4 text-reset text-decoration-none">
                <i class="bi bi-linkedin"></i>
            </a>
            <a href="" class="me-4 text-reset text-decoration-none">
                <i class="bi bi-github"></i>
            </a>
        </div>
        <!-- Right -->
        <div class="clearfix"></div>
    </section>
    <!-- Section: Social media -->

    <!-- Section: Links  -->
    <section class="">
        <div class="container text-center text-md-start pt-5">
            <!-- Grid row -->
            <div class="row mt-3">
                <!-- Grid column -->
                <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4 d-none d-lg-block">
                    <!-- Content -->
                    <h6 class="text-uppercase fw-bold mb-4">
                        <i class="bi bi-gem me-3"></i> <?=$this->title;?> <i class="bi bi-dot"></i> <a href="<?=base_url();?>" class="text-reset"><?=lang('main.home');?></a>
                    </h6>
                    <p>
                        <img style="height:150px; width:auto" class="border rounded" src="<?= base_url('public/img/namad.jpg'); ?>">
                    </p>
                </div>
                <!-- Grid column -->

                <!-- Grid column -->
                <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                    <!-- Links -->
                    <h6 class="text-uppercase fw-bold mb-4">

                    </h6>
                    <p>
                        <a href="<?=base_url('package/list');?>" class="text-reset"><?=lang('main.packages');?></a>
                    </p>
                    <p>
                        <a href="<?=base_url('track/list');?>" class="text-reset"><?=lang('main.tracks');?></a>
                    </p>
                    <p>
                        <a href="<?=base_url('faq');?>" class="text-reset"><?=lang('main.faq');?></a>
                    </p>

                    <p>
                        <a href="<?=base_url('about');?>" class="text-reset"><?=lang('main.about');?></a>
                    </p>

                </div>
                <!-- Grid column -->

                <!-- Grid column -->
                <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                    <!-- Links -->
                    <h6 class="text-uppercase fw-bold mb-4">

                    </h6>
                    <p>
                        <a href="<?=base_url('user/signin');?>" class="text-reset"><?=lang('main.signin');?></a>
                    </p>
                    <p>
                        <a href="<?=base_url('user/signup');?>" class="text-reset"><?=lang('main.signup');?></a>
                    </p>
                    <p>
                        <a href="<?=base_url('terms');?>" class="text-reset"><?=lang('main.terms');?></a>
                    </p>


                    <div class="dropdown text-reset <?= lang('main.direction'); ?>">
                        <a class="dropdown-toggle text-reset" href="#" role="button" id="ssss" data-bs-toggle="dropdown"
                           aria-expanded="false">
                            <?= (isset($cookie)) ? (($cookie == 'en') ? 'English' : 'فارسی') : 'English'; ?>
                        </a>

                        <ul class="dropdown-menu" style="border-radius:0px;min-width:60px !important;"
                            aria-labelledby="ssss">
                            <?php
                            if (isset($cookie) && $cookie == 'fa') {
                                echo '<li style="width:60px"><a class="dropdown-item p-1" href="' . base_url('lang/en') . '">English</a></li>';
                            } else {
                                echo '<li style="width:60px"><a class="dropdown-item" href="' . base_url('lang/fa') . '">فارسی</a></li>';
                            }

                            ?>
                        </ul>
                    </div>

                </div>
                <!-- Grid column -->

                <!-- Grid column -->
                <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                    <!-- Links -->
                    <h6 class="text-uppercase fw-bold mb-4">
                      <a href="<?=base_url('contact');?>" class="text-reset"><?=lang('main.contact');?></a>
                    </h6>
                    <p><i class="bi bi-house me-3"></i> Guilan, Rasht, Moalem Boulevard 10012, IR</p>
                    <p>
                        <i class="bi bi-envelope me-3"></i>
                        info@example.com
                    </p>
                    <p><i class="bi bi-phone me-3"></i> + 01 234 567 88</p>
                </div>
                <!-- Grid column -->
            </div>
            <!-- Grid row -->
        </div>
    </section>
    <!-- Section: Links  -->

    <!-- Copyright -->
    <div class="bg-light text-center p-4">
        © 2021 Copyright:
        <a class="text-reset fw-bold" href="https://duet-studio.com/">Duet-Studio.com</a>
    </div>
    <!-- Copyright -->
</footer>
<!-- Footer -->

<div class="modal fade" id="setLangModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Choose your language
                </h5></div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a class="btn btn-light langBtn w-100 py-3" href="<?= base_url('lang/fa'); ?>"><b>فارسی</b></a>
                    </div>
                    <div class="col-sm-6">
                        <a class=" btn btn-light langBtn w-100 py-3 mt-3 mt-sm-0 mt-lg-0"
                           href="<?= base_url('lang/en'); ?>">
                            <b>English</b>

                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

</div>
<script src="<?= base_url('public/js/owl.carousel.min.js'); ?>"></script>
<script src="<?= base_url('public/plugin/home-player/home-player.js'); ?>"></script>
<script src="<?= base_url('public/plugin/home-player/plyr.min.js'); ?>"></script>
<script src="<?= base_url('public/js/app.js'); ?>"></script>
<?php
    if(isset($scripts)){
        foreach($scripts as $script){
            echo '<script src="'.$script.'"></script>';
        }
    }
?>

<script>
    $(document).ready(function () {
        $('.toast').toast('show');
        <?=(!isset($cookie)) ? "$('#setLangModal').modal('show');" : '';?>
    })
</script>


</body>
</html>
