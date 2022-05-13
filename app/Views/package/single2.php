<div style="
    background:<?= colors_to_gradient($package['most_used_colors']); ?>;
    background-size: cover;
    width:100%;
    height:100%;
    position: fixed;
    z-index:0;"></div>
<div style="
    background:url('<?= base_url('public/demos/packages/covers/' . $package['coded_name']); ?>')
    no-repeat 100% 100%;
    filter: blur(20px);
    -webkit-filter: blur(20px);
    background-size: cover;
    width:100%;
    height:100%;
    position: fixed;
    z-index:0;"></div>

<?php
    if($package['discount'] !== null){
        $discount_label = '<span class="badge bg-warning float-none float-md-start mr-1 px-2 py-1 fs-6 m-0 rounded-pill">';
        if($package['discount_type'] == 'money'){
            $discount_label .= number_format($package['discount']).' '.$this->currency;
        }else{
            $discount_label .= $package['discount'].'%';
        }
        $discount_label .= '</span>';
        $pprice = '<div class="clearfix">'.$discount_label.'<del class="text-grey badge fs-6 m-0 px-1 py-1 float-none float-md-start">'.number_format($package['price']).'</del></div><div class="mt-2 text-success">'.number_format(calcDiscount($package['price'], $package['discount'], $package['discount_type'])). ' '. $this->currency.'</div>';
    }else{
        $discount_label = '';
        $pprice = '<div class="clearfix"></div><div class="mt-2 text-success">'.number_format($package['price']). ' '. $this->currency.'</div>';
    }
?>

<div class="container-xl position-relative px-lg-5 px-md-3">
    <div class="pt-5 bg-white px-lg-4 px-2">
        <h1 class="text-center text-md-start"><?=strtoupper($package['title']);?></h1>

        <div class="row pt-4">
            <div class="col-md-4 ">
                <div class="text-center text-md-start text-start">
                    <h3><?=$pprice;?></h3>
                    <hr class="d-block d-md-none"/>
                </div>

            </div>
            <div class="col-md-8 text-center text-md-end">
                <div class="btns">
                    <?php

                    echo '<a class="btn btn-warning saveItemBtn m-2 btn-lg py-2 px-4" data-type="package" data-id="'.$package['id'].'">
                                <h5 class="m-0 d-inline"><i class="bi bi-bookmark"></i> '. lang('main.save_this').'</h5>
                            </a>';

                    if (isFree($package['price'], $package['discount'], $package['discount_type'])) { ?>
                        <a class="btn btn-primary btn-lg py-2 px-4 downloadFreeNow" data-type="package"
                           data-id="<?= $package['id']; ?>">
                            <h5 class="m-0 d-inline"><i class="bi bi-download"></i> <?= lang('main.download'); ?></h5>
                        </a>
                    <?php } else {

                        if ($package['buy_limit'] !== '-1' && intval($package['buy_limit']) - $package['sold'] <= 0) {
                            echo '<a class="btn disabled btn-danger btn-lg py-2 px-4">
                                    <h5 class="m-0 d-inline">' . lang('main.package_sale_is_over') . '</h5>
                                </a>';
                        } else {

                            echo '<a class="btn btn-secondary btn-lg py-2 px-4 addToCartBtn" data-type="package" data-id="' . $package['id'] . '">
                                <h5 class="m-0 d-inline"><i class="bi bi-basket3"></i> ' . lang('main.add_to_cart') . '</h5>
                            </a>';
                        }
                    }


                    ?>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 order-1">
                <nav class="flex-fill">
                    <div class="mt-3 nav border-bottom-0 justify-content-center justify-content-md-start nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link text-secondary ms-0 active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-details" type="button" role="tab" aria-controls="nav-details" aria-selected="true"><?=lang('main.details');?></button>
                        <button class="nav-link text-danger" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-tracks" type="button" role="tab" aria-controls="nav-tracks" aria-selected="false"><?=lang('main.list_of_tracks');?></button>
                    </div>

                </nav>
            </div>

            <div class="col-md-4 order-0 order-md-2 justify-content-center">
                <?php
                if($package['buy_limit'] !== '-1'){
                    $count = intval($package['buy_limit']) - $package['sold'];
                    $percent = (intval($package['sold']) * 100) / intval($package['buy_limit']);

                    if($count !== 0 && !isFree($package['price'], $package['discount'], $package['discount_type'])){
                        echo '<div class="progress mt-2 mx-auto ltr float-none float-md-end" style="width:178.13px;height:5px">
                                  <div class="progress-bar progre bg-success" role="progressbar" style="width: '.$percent.'%" aria-valuenow="'.$percent.'" aria-valuemin="0" aria-valuemax="100"></div>
                                </div><div class="clearfix"></div>';
                        echo '<div class="mt-1 text-center text-md-end text-success small">'.$count.' '.lang('main.purchase_left_to_run_off_stock').'</div>';
                    }
                }
                ?>
            </div>
        </div>

    </div>
    <div class="bg-white-glass py-5 mh-100">

        <div class="row">
            <div class="col-md-6 order-2 text-center pt-5 pt-md-0 order-md-0">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane px-0 px-md-4 text-center text-md-start fade show active" id="nav-details" role="tabpanel" aria-labelledby="nav-details-tab">
                        <h5>
                            <?= lang('main.details'); ?>:
                        </h5>
                        <h5 class="fw-light ps-2">
                            <?= switch_key($package, 'short_desc', $this->ls); ?>
                        </h5>

                        <h6 class="fw-light ps-2">
                            <?= switch_key($package, 'more_desc', $this->ls); ?>
                        </h6>
                    </div>
                    <div class="tab-pane fade signle-package-tracks-list-box2" id="nav-tracks" role="tabpanel" aria-labelledby="nav-tracks-tab">
                        <div id="trackPlayer" class="visually-hidden">
        <audio id="audio1" preload controls>Your browser does not support HTML5
            Audio! 😢
        </audio>
    </div>
    <?php

    foreach ($tracks as $track) {
        echo '<div class="mx-0 mx-md-4 rounded trackItem" data-id="' . $track['id'] . '" data-code="' . $track['coded_name'] . '">
                        <div class="d-flex">
                        <i class="bi bi-play-circle" style="color:#666"></i>
                        
                        <div class="ps-3 pt-1 trackTitle">
                            <h5 class="">' . switch_key($track, 'title', $this->ls) . '</h5>
                            <h6 class="text-start fw-light trackDuration">
                                <i class="bi bi-clock"></i> ' . gmdate("i:s", $track['file_duration']) . '
                            </h6>
                        </div>
                        </div>
                    </div>';
    } ?>
                    </div>
                </div>

            </div>
            <div class="col-md-6 order-1">
                <div class="wrapper mt-5">
                    <div class="book">
                        <div class="inner-book mx-auto">
                            <div class="img" style="padding-top: calc(1.07 * 100%)">
                                <img src="<?= base_url('public/demos/packages/covers/' . $package['coded_name']); ?>"/>
                            </div>
                            <div class="page"></div>
                            <div class="page page-2"></div>
                            <div class="page page-3"></div>
                            <div class="page page-4"></div>
                            <div class="page page-5"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
<script src="<?= base_url('public/js/single_package.js'); ?>"></script>