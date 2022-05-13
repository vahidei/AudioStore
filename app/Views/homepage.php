<div class="bg-light p-2 homePageSlider">
    <div style="height:300px"></div>
</div>
<br>
<br>
<br/>

<?php /*
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none">
        <path fill="#02a2a2" class="elementor-shape-fill" d="M0,6V0h1000v100L0,6z"></path>
    </svg>
*/ ?>

<div class="loadingBox visually-hidden">
    <div class="box d-flex">
        <div class="cdloading"></div>
        <div class="p-3 pt-4 text-danger fw-bold">
            <?=lang('main.loading');?>
        </div>
        <div class="cancel text-end d-none">
            <?=lang('main.cancel_request');?>
        </div>
    </div>
</div>

<div class="container-xxl position-relative px-lg-5 px-md-3">
    <div>
        <h3><?= lang('home.packages'); ?></h3>
    </div>

    <div class="bg-white-glass mt-4 owl2loading" style="position:relative;height:489.58px; width:100%">
        <div class="loadingBox" style="position: absolute !important;">
            <div class="box d-flex" style="position: absolute !important; top:calc(50% - 150px)">
                <div class="cdloading"></div>
                <div class="p-3 pt-4 text-danger fw-bold">
                    <?=lang('main.loading');?>
                </div>
                <div class="cancel text-end d-none">
                    <?=lang('main.cancel_request');?>
                </div>
            </div>
        </div>
    </div>
    <div class="owl-carousel ltr owl-2 mt-4">
        <?php
        foreach ($data['packages'] as $key => $item) {

            $buy_limit_progress = '';

            if($item['buy_limit'] !== '-1' && !isFree($item['price'], $item['discount'], $item['discount_type'])){
                $count = intval($item['buy_limit']) - $item['sold'];
                $percent = (intval($item['sold']) * 100) / intval($item['buy_limit']);
                $buy_limit_progress = '<div class="progress mt-2 ltr" style="height:5px">
                                  <div class="progress-bar progre bg-success" role="progressbar" style="width: '.$percent.'%" aria-valuenow="'.$percent.'" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>';
                if($count == 0){
                    $buy_limit_progress .= '<div class="mt-1 text-center text-danger small">'.lang('main.package_sale_is_over').'</div>';
                }else{
                    $buy_limit_progress .= '<div class="mt-1 text-start text-success small">'.$count.' '.lang('main.purchase_left_to_run_off_stock').'</div>';
                }
            }

            if($item['discount'] !== null){
                $discount_label = '<span class="badge bg-warning float-left mr-1 px-2 py-1 fs-6 m-0 rounded-pill">';
                if($item['discount_type'] == 'money'){
                    $discount_label .= number_format($item['discount']).' '.$this->currency;
                }else{
                    $discount_label .= $item['discount'].'%';
                }
                $discount_label .= '</span>';
                $pprice = '<div class="clearfix">'.$discount_label.'<del class="text-grey badge fs-6 m-0 px-1 py-1 float-left">'.number_format($item['price']).'</del></div><div class="mt-2 text-success">'.number_format(calcDiscount($item['price'], $item['discount'], $item['discount_type'])). ' '. $this->currency.'</div>';
            }else{
                $discount_label = '';
                $pprice = '<div class="clearfix"><span class="badge float-left mr-1 px-2 py-1 fs-6 m-0 rounded-pill">&nbsp;</span></div><div class="mt-2 text-success">'.number_format($item['price']). ' '. $this->currency.'</div>';
            }

            echo '<div class="media-29101">
                        <div class="card" style="width: 18rem;">
                            <a class="position-relative" href="' . base_url('package/single/' . $item['id']) . '">
                            <img style="background:' . colors_to_gradient($item['most_used_colors']) . '" src="' . base_url('public/demos/packages/covers/' . $item['coded_name']) . '" class="card-img-top" alt="...">
                            </a>
                            <div class="card-body" style="height:100px; overflow: hidden">
                                <a class="text-decoration-none text-reset" href="' . base_url('package/single/' . $item['id']) . '">
                                    <h5 class="card-title">' . switch_key($item, 'title', $this->ls) . '</h5>
                                    <p class="card-text">' . nl2br(switch_key($item, 'short_desc', $this->ls)) . '</p>
                                </a>
                            </div>
                            <div class="card-body text-left" style="height:115px; overflow: hidden">
                                <a class="text-decoration-none text-reset" href="' . base_url('package/single/' . $item['id']) . '">
                                    <span><h6 class="py-0 my-0"> ' . $pprice  . '</h6></span>
                                </a>
                                '.$buy_limit_progress.'
                            </div>
                        </div>
                    </div>';
        }

        ?>
    </div>
    <div class=" mt-5 px-0 pb-5 text-center">
        <a href="package/list" class="btn morePackageBtn bg-white border-1 border-dark text-reset">
            <h6 class="m-1"><?=lang('main.click_to_see_more_packages');?></h6>
        </a>
        <div class=""></div>
    </div>
</div>

<div class="homePageTracksBox light-box-shadow bg-white mt-5 pt-5">
    <div class="container-xxl px-lg-5 px-md-3  mb-5">
        <h3><?=lang('main.tracks_list');?></h3>

        <div class="track-box">
            <div class="fs-5 mb-2 pt-3"><?=lang('main.choose_your_category');?>:</div>
            <div class=" position-sticky sticky-top bg-white">
                    <div class="owl-carousel owl-3">
                        <div class="media-29101">
                            <a data-id="0" class="btn catBtnTlist btn-danger rounded-0">
                                <h5 class="m-0 py-1 px-3"><i class="bi bi-music-note"></i> <?=lang('main.all_tracks');?></h5>
                            </a>
                        </div>
                        <?php
                        foreach ($data['categories'] as $key => $item) {
                            echo '<div class="media-29101">
                                            <a data-id="' . $item['id'] . '" style="color:'.$item['color'].'" class="btn catBtnTlist border rounded-0"><h5 class="m-0 py-1 px-2">' . switch_key($item, 'title', $this->ls) . '</h5></a>
                                        </div>';
                        }
                        ?>
                    </div>

            </div>
            <div class="pb-3 pt-2">
                <div class="py-4">
                    <div class="dropdown float-end">
                        <button class="btn btn-sm border-dark rounded-pill btn-light dropdown-toggle" type="button"
                                id="sortTrackMenuBtn" data-bs-toggle="dropdown" aria-expanded="false">
                            <?=lang('main.sort_by');?>: <span class="ms-1"><?=lang('main.newest');?></span>
                        </button>
                        <ul class="dropdown-menu" id="sortTrackMenu" aria-labelledby="sortTrackMenu">
                            <li><a class="dropdown-item small cursor-pointer bg-light" data-key="0"><?=lang('main.newest');?></a></li>
                            <li><a class="dropdown-item small cursor-pointer" data-key="1"><?=lang('main.price:low_to_high');?></a></li>
                            <li><a class="dropdown-item small cursor-pointer" data-key="2"><?=lang('main.price:high_to_low');?></a></li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="trackmainwrap">
                    <div id="trackplayerbox" class="visually-hidden">
                        <div class="clearfix"></div>
                        <div class="py-3 border-bottom d-flex px-3" style="padding-top:8px !important;">
                            <a class="btn closeTrackTlist flex-fill  me-1 btn-light">
                                <i class="bi bi-x me-1 d-none d-md-inline"></i> <?=lang('main.close_demo');?>
                            </a>
                            <a class="btn addToCartBtn flex-fill me-1 btn-secondary" data-type="track" data-id="">
                                <i class="bi bi-cart me-1 d-none d-md-inline"></i> <?=lang('main.add_to_cart');?>
                            </a>
                            <a class="btn downloadFreeNow d-none me-1 flex-fill btn-primary" data-type="track" data-id="">
                                <i class="bi bi-download me-1 d-none d-md-inline"></i> <?=lang('main.download');?>
                            </a>
                            <a class="btn saveItemBtn flex-nowrap flex-md-fill btn-warning" data-type="track" data-id="">
                                <i class="bi-bookmark me-0 me-md-1"></i> <span class="d-none d-md-inline"><?=lang('main.save_this');?></span>
                            </a>
                            <a class="btn saveItemBtn flex-grow-0 me-1 d-none border btn-light" data-type="track" data-id="">
                                <i class="bi-share"></i>
                            </a>

                        </div>
                        <div class="px-3">
                            <div id="audiowrap" class="h-100">
                                <div id="audio0" class="bg-transparent h-100">
                                    <audio id="audio1" preload controls>Your browser does not support HTML5
                                        Audio! 😢
                                    </audio>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div id="plwrap">
                        <div class="row m-0" id="plList"></div>
                    </div>
                </div>


            </div>

        </div>
        <div class="tracksListPage  py-5 text-center">
            <a href="track/list" class="btn mb-5 bg-white moreTracksBtn border-1 border-dark text-reset">
                <h6 class="m-1"><?=lang('main.click_to_see_more_tracks');?></h6>
            </a>
        </div>
    </div>
</div>

<section class="homePagevideoBox bg-light py-5 my-5 <?= lang('home.direction'); ?>">
    <div class="container px-5 py-5">
        <div class="row gx-5 align-items-center justify-content-center justify-content-lg-between">
            <div class="col-12 col-lg-5">
                <h2 class="display-4 lh-1 mb-4">Enter a new age of web design</h2>
                <p class="lead fw-normal text-muted mb-5 mb-lg-0">This section is perfect for featuring some information
                    about your application, why it was built, the problem it solves, or anything else! There's plenty of
                    space for text here, so don't worry about writing too much.</p>
            </div>
            <div class="col-sm-8 col-md-6">
                <div class="px-sm-0">
                    <video id="vplayer" controls crossorigin playsinline
                           poster="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.jpg">
                        <source src=""
                                type="video/mp4" size="576">

                    </video>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="subscribe-box call-to-action text-white text-center py-5 bg-dark bg-opacity-25">
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <h2 class="mb-4 text-dark"><?=lang('main.subscribe_to_our_newsletter');?></h2>
                <form class="form-subscribe">
                    <div class="step1">
                        <div class="row">
                            <div class="col">
                                <input class="form-control subscribeEmail form-control-lg" required type="email"
                                       placeholder="Email Address">
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-primary subscribeBtn btn-lg" type="submit"><?=lang('main.subscribe');?></button>
                            </div>
                        </div>
                    </div>
                    <div class="step2 d-none">
                        <div class="recaptchaBox bg-white-glass mt-3 py-2 justify-content-center">
                            <h5 class="text-dark"><?=lang('main.check_the_checkbox');?></h5>
                            <div class="g-recaptcha required" id="recaptcha"
                                 data-sitekey="6LdZpxYcAAAAABsRrAN2To890s6_YzDdJd1RBQkf" style="padding-left:calc(50% - 151.5px)" data-callback="recaptchaCallback" >
                            </div>
                        </div>
                    </div>
                    <div class="step3 d-none text-dark">
                        <h4>
                            <span class="me-2 spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            <?=lang('main.please_wait');?>...
                        </h4>
                    </div>
                    <div class="step4 d-none">
                        <h5 class="text-center text-dark"><?=lang('main.subscribe_enter_code');?>:</h5>
                        <div class="row">
                            <div class="col">
                                <input class="form-control subscribeVerifyCode form-control-lg" required type="text"
                                       placeholder="<?=lang('main.verification_code');?>">
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-success sendCodeBtn btn-lg" type="submit"><?=lang('main.send');?></button>
                            </div>
                        </div>
                    </div>
                    <div class="alert-danger alert d-none"></div>
                    <div class="alert-success alert d-none"></div>
                </form>
            </div>
        </div>
    </div>
</section>
<script src="<?=base_url('public/js/subscribe.js');?>"></script>
<script src="https://www.google.com/recaptcha/api.js"></script>


