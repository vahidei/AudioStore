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
        filter: blur(100px);
        -webkit-filter: blur(100px);
        background-size: cover;
        width:100%;
        height:100%;
        position: fixed;
        z-index:0;"></div>

<div class="single-package-details-box">
    <div class="child">
        <div class="p-4 position-absolute" style="z-index: 1001">
            <a class="btn btn-light bg-white-glass rounded-pill border-0 px-4" href="<?= base_url('package/list'); ?>">
                <i class="bi bi-arrow-left"> </i><?= lang('main.packages_list'); ?>
            </a>
        </div>
        <div class="p-4 position-absolute d-block d-lg-none" style="z-index: 1001; right:0px; left:unset">
            <a class="btn btn-light bg-white-glass rounded-pill trackListToggleBtn <?= lang('main.direction'); ?> border-0 px-4">
                <i class="bi bi-music-note-list"></i> <?= lang('main.tracks_list'); ?>
            </a>
        </div>
        <div class="text-center pt-5 title">
            <h1><?= switch_key($package, 'title', $this->ls); ?></h1>
        </div>
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

        <div class="bg-white-glass border-bottom btns" style="margin-<?= lang('main.reverse_side'); ?>:-17px">
            <?php if (isFree($package['price'], $package['discount'], $package['discount_type'])) { ?>
                <a class="btn btn-primary m-2 btn-lg py-2 px-4 downloadFreeNow" data-type="package"
                   data-id="<?= $package['id']; ?>">
                    <h5 class="m-0"><i class="bi bi-download"></i> <?= lang('main.download'); ?></h5>
                </a>
            <?php } else {

                if ($package['buy_limit'] !== '-1' && intval($package['buy_limit']) - $package['sold'] <= 0) {
                    echo '<a class="btn disabled btn-danger m-2 btn-lg py-2 px-4">
                    <h5 class="m-0">' . lang('main.package_sale_is_over') . '</h5>
                </a>';
                } else {

                    echo '<a class="btn btn-secondary m-2 btn-lg py-2 px-4 addToCartBtn" data-type="package" data-id="' . $package['id'] . '">
                                <h5 class="m-0"><i class="bi bi-basket3"></i> ' . lang('main.add_to_cart') . '</h5>
                            </a>';
                }
            }

                echo '<a class="btn btn-warning saveItemBtn btn-lg py-2 px-4" data-type="package" data-id="'.$package['id'].'">
                                <h5 class="m-0"><i class="bi bi-bookmark"></i> '. lang('main.save_this').'</h5>
                            </a>';

            ?>

        </div>
        <div class="row pb-5 bg-white-glass pt-5" style="margin-<?= lang('main.reverse_side'); ?>:-17px">
            <div class="col-lg-8 ps-5">
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
            <div class="col-lg-4">

                <h5 class="">
                    <?= lang('main.number_of_tracks'); ?>:
                </h5>
                <h6 class="fw-light ps-2">
                    <?= count($tracks) ?> <?= lang('main.in_package'); ?>
                </h6>
            </div>
        </div>

    </div>
</div>


<div class="signle-package-tracks-list-box">
    <div class="position-absolute px-3 pt-3 d-block d-lg-none" style="left:0px; overflow: hidden">
        <a class="px-1 closeTrackListBtn text-white-50 cursor-pointer">
            <i class="bi bi-x display-4"> </i>
        </a>
    </div>
    <h4 class="text-center pt-4 text-white-50"><?= lang('main.tracks_list'); ?></h4>
    <div id="trackPlayer" class="visually-hidden">
        <audio id="audio1" preload controls>Your browser does not support HTML5
            Audio! 😢
        </audio>
    </div>
    <?php

    foreach ($tracks as $track) {
        echo '<div class="trackItem" data-id="' . $track['id'] . '" data-code="' . $track['coded_name'] . '">
                        <div class="d-flex">
                        <i class="bi bi-play-circle text-white-50"></i>
                        
                        <div class="ps-3 pt-1 trackTitle">
                            <h5 class="text-white">' . switch_key($track, 'title', $this->ls) . '</h5>
                            <h6 class="text-white fw-light trackDuration">
                                <i class="bi bi-clock"></i> ' . gmdate("i:s", $track['file_duration']) . '
                            </h6>
                        </div>
                        </div>
                    </div>';
    }

    ?>
</div>

<script src="<?= base_url('public/js/single_package.js'); ?>"></script>
