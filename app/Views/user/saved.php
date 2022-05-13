<div class="bg-light p-2">
    <div style="height:300px">
        <div class="text-center pt-5">
            <i class="bi bi-bookmark-heart text-danger p-0 m-0 lh-sm" style="font-size:100px;"></i>
            <h4 class="text-danger"
                style="font-size:40px"><?= number_format($data['tracks_count'] + $data['packages_count']); ?> <?= lang('main.items_saved'); ?></h4>
            <h5 class="py-2 fw-light"><?= lang('main.they_always_stay_here'); ?></h5>
        </div>
    </div>
</div>
<div class="container-xxl px-lg-5 px-md-3  mt-2">
    <ul class="nav nav-tabs">
        <li class="nav-item" id="saved_tracks">
            <a class="nav-link text-danger px-5 rounded-0 <?= ($data['type'] == 'track') ? 'active' : ''; ?>"
               aria-current="page" href="<?= base_url('user/saved/track/1#saved_tracks'); ?>">
                <?= lang('main.tracks'); ?>
            </a>
        </li>
        <li class="nav-item" id="saved_packages">
            <a class="nav-link px-5 text-primary rounded-0 <?= ($data['type'] == 'package') ? 'active' : ''; ?>"
               href="<?= base_url('user/saved/package/1#saved_packages'); ?>">
                <?= lang('main.packages'); ?>
            </a>
        </li>
    </ul>
</div>
<div class="light-box-shadow bg-white">
    <div class="container-xxl px-lg-5 px-md-3 mb-5 mb-5">
        <div class="py-5 ">
            <?php
            if (empty($data['items'])) {
                echo '<div class="bg-light text-center py-4 h3 mt-4 text-danger"><i class="bi bi-bookmark display-2"></i><br>' . lang('main.not_found') . '</div>';

            } else {
                if ($data['type'] == 'package') { ?>
                    <div class="row pb-5">
                        <?php
                        foreach ($data['items'] as $item) {

                            $buy_limit_progress = '';

                            if ($item['buy_limit'] !== '-1' && !isFree($item['price'], $item['discount'], $item['discount_type'])) {
                                $count = intval($item['buy_limit']) - $item['sold'];
                                $percent = (intval($item['sold']) * 100) / intval($item['buy_limit']);
                                $buy_limit_progress = '<div class="progress mt-2 ltr" style="height:5px">
                                  <div class="progress-bar progre bg-success" role="progressbar" style="width: ' . $percent . '%" aria-valuenow="' . $percent . '" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>';
                                if ($count == 0) {
                                    $buy_limit_progress .= '<div class="mt-1 text-center text-danger small">' . lang('main.package_sale_is_over') . '</div>';
                                } else {
                                    $buy_limit_progress .= '<div class="mt-1 text-start text-success small">' . $count . ' ' . lang('main.purchase_left_to_run_off_stock') . '</div>';
                                }
                            }

                            if ($item['discount'] !== null) {
                                $discount_label = '<span class="badge bg-warning float-left mr-1 px-2 py-1 fs-6 m-0 rounded-pill">';
                                if ($item['discount_type'] == 'money') {
                                    $discount_label .= number_format($item['discount']) . ' ' . $this->currency;
                                } else {
                                    $discount_label .= $item['discount'] . '%';
                                }
                                $discount_label .= '</span>';
                                $pprice = '<div class="clearfix">' . $discount_label . '<del class="rounded-pill text-reset badge bg-white-glass fs-6 m-0 px-1 py-1 float-left">' . number_format($item['price']) . '</del></div><div class="mt-2 text-success">' . number_format(calcDiscount($item['price'], $item['discount'], $item['discount_type'])) . ' ' . $this->currency . '</div>';
                            } else {
                                $discount_label = '';
                                $pprice = '<div class="clearfix"><span class="badge float-left mr-1 px-2 py-1 fs-6 m-0 rounded-pill">&nbsp;</span></div><div class="mt-2 text-success">' . number_format($item['price']) . ' ' . $this->currency . '</div>';
                            }

                            echo '<div class="col-lg-3 mt-4 col-md-4 col-sm-6 col-12">
                            <div class="card packItem border-0 light-box-shadow rounded">
                                <a target="_blank" href="' . base_url('package/single/' . $item['id']) . '" class="text-reset">
                                <img class="card-img" style="background:' . colors_to_gradient($item['most_used_colors']) . '" src="' . base_url('public/demos/packages/covers/' . $item['coded_name']) . '"/>
                                <div class="price card-body text-left ' . ((!empty($buy_limit_progress)) ? 'haveProgress' : '') . '">
                                    <a class="text-decoration-none text-reset" href="' . base_url('package/single/' . $item['id']) . '">
                                        <span><h6 class="py-0 my-0"> ' . $pprice . '</h6></span>
                                    </a>
                                </div>
                                <div class="details">
                                    <a class="btn btn-danger removeSaveBtn" data-type="package" data-id="' . $item['id'] . '"><i class="bi bi-bookmark-x"></i> ' . lang('main.remove_this') . '</a>
                                    <hr/>
                                    <a target="_blank" href="' . base_url('package/single/' . $item['id']) . '" class="text-decoration-none text-reset">
                                        <h5>' . switch_key($item, 'title', $this->ls) . '</h5>
                                        <h6 class="fw-light pt-2">' . switch_key($item, 'short_desc', $this->ls) . '</h6>
                                        <div class="position-absolute" style="bottom:10px; width:calc(100% - 40px)">' . $buy_limit_progress . '</div>
                                    </a>
                                </div>
                                </a>
                            </div>
                        </div>';


                        }
                        ?>
                    </div>
                <?php } elseif ($data['type'] == 'track') { ?>
                    <div class="track-box" style="height:auto !important;">
                        <div class="pb-3 pt-2">
                            <div id="trackmainwrap">
                                <div id="trackplayerbox" class="visually-hidden">
                                    <div class="clearfix"></div>
                                    <div class="py-3 border-bottom d-flex px-3">
                                        <a class="btn closeTrackTlist flex-fill  me-1 btn-light" data-type="track"
                                           data-id="">
                                            <i class="bi bi-x me-1 d-none d-md-inline"></i> <?= lang('main.close_track'); ?>
                                        </a>
                                        <a class="btn removeSaveBtn flex-fill me-1 btn-danger" data-type="track"
                                           data-id="">
                                            <i class="bi-bookmark-x me-1 d-none d-md-inline"></i> <?= lang('main.remove_this'); ?>
                                        </a>
                                        <a class="btn addToCartBtn flex-fill btn-secondary" data-type="track"
                                           data-id="">
                                            <i class="bi bi-cart me-1 d-none d-md-inline"></i> <?= lang('main.add_to_cart'); ?>
                                        </a>
                                        <a class="btn downloadFreeNow d-none flex-fill btn-primary" data-type="track"
                                           data-id="">
                                            <i class="bi bi-download me-1 d-none d-md-inline"></i> <?= lang('main.download'); ?>
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
                                <div id="plwrap"
                                     style="border:0px;max-height:none; overflow: hidden !important;padding-bottom:250px">
                                    <div class="row m-0" id="plList">

                                        <?php

                                        foreach ($data['items'] as $item) {

                                            $fs = '';
                                            if (strlen(number_format($item['price']) . ' ' . $this->currency) > 11) {
                                                $fs = 'style="font-size:16px"';
                                            }

                                            $finalPrice = '<div class="plPrice px-3 text-success border-start rounded-right fw-bold bg-light" data-value="' . $item['price'] . '" ' . $fs . '>' . number_format($item['price']) . ' ' . $this->currency . '</div>';

                                            $newPrice = $item['price'];
                                            if (!empty($item['discount'])) {
                                                $newPrice = calcDiscount($item['price'], $item['discount'], $item['discount_type']);
                                                if ($item['discount_type'] == 'money') {
                                                    $disoff = number_format($item['discount']) . ' ' . $this->currency . ' ' . lang('main.off');
                                                } else {
                                                    $disoff = $item['discount'] . '% ' . lang('main.off');
                                                }

                                                $fs = '';
                                                if (strlen(number_format($newPrice) . ' ' . $this->currency) > 11) {
                                                    $fs = 'style="font-size:16px"';
                                                }

                                                $finalPrice = '<div class="plPrice px-3 discounted text-success border-start rounded-right fw-bold bg-light" data-value="' . $newPrice . '">
                                                <div><del class="text-grey">' . number_format($item['price']) . '</del></div>
                                                <div ' . $fs . '>' . number_format($newPrice) . ' ' . $this->currency . '</div>
                                                <div class="off text-warning">' . $disoff . '</div>
                                                </div>';

                                            }

                                            echo '<div class="col-lg-6"><div class="tk mt-3 border rounded" data-id="' . $item['id'] . '" data-is-free="' . (($newPrice == '0') ? 'true' : 'false') . '" data-file="' . $item['coded_name'] . '">
                                            <div class="plItem">
                                                <div class="plNum"><i class="bi bi-play-circle text-grey"></i></div>
                                                <div class="plTitle">' . switch_key($item, 'title', $this->ls) . '</div>
                                                ' . $finalPrice . '<div class="plLength border-start d-none px-3 pt-2 d-md-inline "><i class="bi text-grey bi-clock"></i><div class="text-grey">' . toHHMMSS($item['file_duration']) . '</div></div>
                                            </div> 
                                        </div></div>';


                                        } ?>
                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>
                <?php }

                $bcolor = ($data['type'] == 'track') ? 'bg-danger border-danger' : '';
                $tcolor = ($data['type'] == 'track') ? 'text-danger' : '';
                $hashtag = ($data['type'] == 'track') ? '#saved_tracks' : '#saved_packages';

                $links = 5;

                $last = ceil($data['items_count'] / $data['limit']);

                $start = (($data['page'] - $links) > 0) ? $data['page'] - $links : 1;
                $end = (($data['page'] + $links) < $last) ? $data['page'] + $links : $last;

                $html = '<ul class="pagination justify-content-center">';

                $class = ($data['page'] == 1) ? "disabled" : "";
                $class2 = ($data['page'] == 1) ? "" : $tcolor;
                $html .= '<li class="page-item ' . $class . '"><a class="page-link cursor-pointer ' . $class2 . '" href="' . base_url('user/saved/' . $data['type'] . '/' . ($data['page'] - 1) . $hashtag) . '">&laquo;</a></li>';

                if ($start > 1) {
                    $html .= '<li class="page-item"><a class="page-link" href="' . base_url('user/saved/' . $data['type'] . '/1' . $hashtag) . '">1</a></li>';
                    $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }

                for ($i = $start; $i <= $end; $i++) {
                    $class = ($data['page'] == $i) ? "active" : "";
                    $class2 = ($data['page'] == $i) ? $bcolor : $tcolor;
                    $html .= '<li class="page-item ' . $class . '"><a class="page-link cursor-pointer ' . $class2 . '" href="' . base_url('user/saved/' . $data['type'] . '/' . $i . $hashtag) . '">' . $i . '</a></li>';
                }

                if ($end < $last) {
                    $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    $html .= '<li class="page-item"><a class="page-link" href="' . base_url('user/saved/' . $data['type'] . '/' . $last . $hashtag) . '">' . $last . '</a></li>';
                }

                $class = ($data['page'] == $last) ? "disabled" : "";
                $class2 = ($data['page'] == $last) ? "" : $tcolor;
                $html .= '<li class="page-item ' . $class . '"><a class="page-link cursor-pointer ' . $class2 . '" href="' . base_url('user/saved/' . $data['type'] . '/' . ($data['page'] + 1) . $hashtag) . '">&raquo;</a></li>';

                $html .= '</ul>';


                ?>
                <div>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <?= $html; ?>
                        </ul>
                    </nav>
                </div>
            <?php } ?>

        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        <?php if($data['type'] == 'track'){ ?>
        var trackCount = <?=intval($data['items_count']);?>;
        var player = new Plyr('#audio1', {
            controls: [
                'restart',
                'play',
                'progress',
                'current-time',
                'duration',
                'mute',
                'volume'
            ]
        });

        audio = $('#audio1').on('ended', function () {
            if ((index + 1) >= trackCount) {
                audio.pause();
            }
        }).get(0);

        $(document).on('click', '.closeTrackTlist', function () {
            closeTrackTlist();
        });

        $('#plList .tk').on('click', function () {
            if ($(this).hasClass('plSel')) {
                return;
            }
            var id = $(this).attr('data-id');
            var isFree = $(this).attr('data-is-free');
            var node = document.getElementById('trackplayerbox');
            $(this).append(node);
            $(this).find('.addToCartBtn').attr('data-id', id);
            $(this).find('.removeSaveBtn').attr('data-id', id);
            $(this).find('.buyTrackBtn').attr('data-id', id);
            $(this).find('.downloadFreeNow').attr('data-id', id);

            if (isFree == 'true') {
                $(this).find('.downloadFreeNow').removeClass('d-none');
                $(this).find('.addToCartBtn').addClass('d-none');
            } else {
                $(this).find('.addToCartBtn').removeClass('d-none');
                $(this).find('.downloadFreeNow').addClass('d-none');
            }

            $('#trackplayerbox').removeClass('visually-hidden');

            playTrack(id);

        });

        function playTrack(id) {
            loadTrack(id);
            audio.play();
        }

        function loadTrack(id) {
            $('.plSel').removeClass('plSel');
            $('#plList .tk[data-id=' + id + ']').addClass('plSel');
            // $('body').addClass('trackSelect');
            audio.src = base_url + '/public/demos/tracks/' + $('#plList .tk[data-id=' + id + ']').attr('data-file');
            updateDownload(id, audio.src);
        }

        function updateDownload(id, source) {
            player.on('loadedmetadata', function () {
                $('a[data-plyr="download"]').attr('href', source);
            });
        }

        function closeTrackTlist() {
            var node = document.getElementById('trackplayerbox');
            $('body').append(node);
            audio = $('#audio1').get(0);
            audio.pause();
            $('#trackplayerbox').addClass('visually-hidden');
            $('#plwrap .plSel').removeClass('plSel');
            // $('body').removeClass('trackSelect');
        }

        <?php } ?>
    });
</script>
