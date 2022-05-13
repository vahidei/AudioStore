<div class="bg-light p-2" >
    <div style="height:300px">
        <div class="text-center pt-5">
            <i class="bi bi-file-music-fill text-danger p-0 m-0 lh-sm" style="font-size:100px"></i>
            <h4 class="text-danger" style="font-size:40px"><?=number_format($data['tracks_count']);?> <?=lang('main.tracks_available');?></h4>
            <h5 class="py-2 fw-light"><?=lang('main.enjoy_listening');?></h5>
        </div>
    </div>
</div>
<div class="light-box-shadow bg-white mt-5 pt-5">

    <div class="container-xxl px-lg-5 px-md-3  mb-5">
        <h3><?=lang('main.tracks_list');?></h3>

        <div class="track-box" style="height:auto !important;">
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
                        <div class="py-3 border-bottom d-flex px-3">
                            <a class="btn closeTrackTlist flex-fill  me-1 btn-light">
                                <i class="bi bi-x me-1 d-none d-md-inline"></i> <?=lang('main.close_track');?>
                            </a>
                            <a class="btn addToCartBtn flex-fill me-1 btn-secondary" data-type="track" data-id="">
                                <i class="bi bi-cart me-1 d-none d-md-inline"></i> <?=lang('main.add_to_cart');?>
                            </a>
                            <a class="btn downloadFreeNow d-none me-1 flex-fill btn-primary" data-type="track" data-id="">
                                <i class="bi bi-download me-1 d-none d-md-inline"></i> <?=lang('main.download');?>
                            </a>
                            <a class="btn saveItemBtn flex-fill btn-warning" data-type="track" data-id="">
                                <i class="bi-bookmark me-1 d-none d-md-inline"></i> <?=lang('main.save_this');?>
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
                    <div id="plwrap" style="max-height:none; overflow: hidden !important;padding-bottom:250px">
                        <div class="row m-0" id="plList"></div>
                    </div>
                </div>


            </div>

        </div>

        <div class="paginationBox py-5">

        </div>

    </div>
</div>