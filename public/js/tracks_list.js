$(document).ready(function () {
    var cat_id = 0;
    var page_selected = 1;
    var orderby = 0;
    if ($('.owl-3').length > 0) {
        $('.owl-3').owlCarousel({
            center: false,
            items: 1,
            loop: false,
            stagePadding: 0,
            margin: 10,
            autoWidth: true,
            smartSpeed: 500,
            autoplay: false,
            nav: true,
            navClass: ['owl-prev border', 'owl-next border'],
            navText: ['<i class="bi bi-arrow-left" aria-hidden="true"></i>', '<i class="bi bi-arrow-right" aria-hidden="true"></i>'],
            dots: false,
            pauseOnHover: false

        });
    }

    $('#trackplayerbox #nowPlay').prepend(miniEqualizer('#ccc', ''));

    loadTracksList(cat_id, page_selected, orderby);

    $(document).on('click', '#sortTrackMenu .dropdown-item', function () {
        if ($(this).hasClass('bg-light')) return false;
        closeTrackTlist();
        orderby = $(this).data('key');
        loadTracksList(cat_id, page_selected, orderby);
        $('#sortTrackMenuBtn span').text($(this).text());
        $('#sortTrackMenu .dropdown-item.bg-light').removeClass('bg-light');
        $(this).addClass('bg-light');
    });

    $(document).on('click', '.closeTrackTlist', function () {
        closeTrackTlist();
    });

    $(document).on('click', '.catBtnTlist[data-id]', function () {
        var id = $(this).attr('data-id');
        if (id == undefined) return;
        cat_id = id;
        loadTracksList(id, page_selected, orderby);
        $('html, body').animate({
            scrollTop: $("#plList").offset().top - 150
        });
    });

    $(document).on('click', '.page-item .page-link[data-id]', function () {
        var id = $(this).attr('data-id');
        if (id == undefined) return;
        page_selected = id;
        loadTracksList(cat_id, id, orderby);
    });

    function loadTracksList(cat_id, page, orderBy) {
        closeTrackTlist();

        $('#plList').html('<div class="spinner-grow text-danger my-5 mx-auto" style="width:50px; height:50px" role="status"></div>');

        $.ajax({
            type: 'POST',
            url: base_url + '/api/get_tracks',
            data: {cat_id: cat_id, page: page, orderBy: orderBy},
            dataType: 'json',
            success: function (data) {

                var tracks = [];

                $.each(data.tracks, function (index, value) {
                    tracks.push({
                        track: index + 1,
                        id: value['id'],
                        title: value['title'],
                        title_fa: value['title_fa'],
                        duration: value['file_duration'].toHHMMSS(),
                        price: value['price'],
                        discount: value['discount'],
                        discount_type: value['discount_type'],
                        isFree: isFree(value['price'], value['discount'], value['discount_type']),
                        file: value['coded_name']
                    })
                });

                if (tracks.length < 1) {
                    $('#plList').html('<div class="bg-light text-center py-4 h3 mt-4 text-danger"><i class="bi bi-music-note-list display-2"></i><br/>' + lang.track_not_found + '</div>');
                } else {
                    createPlayList(tracks);
                }

                if (data.tracks_count > data.limit) {
                    $('.paginationBox').html(pagination(data.links, data.page, data.limit, data.tracks_count));
                }


            }
        });
    }

    function createPlayList(tracks_data) {
        var supportsAudio = !!document.createElement('audio').canPlayType;
        if (supportsAudio) {
            // initialize plyr
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

            $('#plList').html('');

            // initialize playlist and controls

            var index = 0,
                playing = false,
                mediaPath = base_url + '/public/demos/tracks/',
                extension = '',
                tracks = tracks_data,
                buildPlaylist = $.each(tracks, function (key, value) {
                    var trackNumber = value.track,
                        trackName = switch_key(value, 'title'),
                        trackDuration = value.duration,
                        trackPrice = value.price;

                    var fs = '';
                    if ((number_format(trackPrice) + ' ' + currency).toString().length > 11) {
                        fs = 'style="font-size:16px"';
                    }

                    var finalPrice = '<div class="plPrice px-3 text-success border-start rounded-right fw-bold bg-light" data-value="' + trackPrice + '" ' + fs + '>' + number_format(trackPrice) + ' ' + currency + '</div>';

                    if (value.discount !== null) {
                        newPrice = calcDiscount(trackPrice, value.discount, value.discount_type);
                        if (value.discount_type == 'money') {
                            disoff = number_format(value.discount) + ' ' + currency + ' ' + lang.off;
                        } else {
                            disoff = value.discount + '% ' + lang.off;
                        }

                        var fs = '';
                        if ((number_format(newPrice) + ' ' + currency).toString().length > 11) {
                            fs = 'style="font-size:16px"';
                        }

                        finalPrice = '<div class="plPrice px-3 discounted text-success border-start rounded-right fw-bold bg-light" data-value="' + newPrice + '">' +
                            '<div><del class="text-grey">' + number_format(trackPrice) + '</del></div>' +
                            '<div ' + fs + '>' + number_format(newPrice) + ' ' + currency + '</div>' +
                            '<div class="off text-warning">' + disoff + '</div>' +
                            '</div>';
                    }

                    if (trackNumber.toString().length === 1) {
                        trackNumber = '0' + trackNumber;
                    }
                    $('#plList').append('<div class="col-lg-6"><div class="tk mt-3 border rounded"> \
                    <div class="plItem"> \
                        <div class="plNum"><i class="bi bi-play-circle text-grey"></i></div> \
                        <div class="plTitle">' + trackName + '</div> \
                        ' + finalPrice + '<div class="plLength border-start d-none px-3 pt-2 d-md-inline "><i class="bi text-grey bi-clock"></i><div class="text-grey">' + trackDuration + '</div></div> \
                    </div> \
                </div></div>');

                }),
                trackCount = tracks.length,
                npAction = $('#npAction'),
                npTitle = $('#npTitle'),
                audio = $('#audio1').on('play', function () {
                    playing = true;
                    npAction.text('Now Playing...');
                }).on('pause', function () {
                    playing = false;
                    npAction.text('Paused...');
                }).on('ended', function () {
                    npAction.text('Paused...');
                    if ((index + 1) < trackCount) {
                        // index++;
                        // loadTrack(index);
                        // audio.play();
                    } else {
                        audio.pause();
                        // index = 0;
                        //loadTrack(index);
                    }
                }).get(0),
                btnPrev = $('#btnPrev').on('click', function () {
                    if ((index - 1) > -1) {
                        index--;
                        loadTrack(index);
                        if (playing) {
                            audio.play();
                        }
                    } else {
                        audio.pause();
                        index = 0;
                        loadTrack(index);
                    }
                }),
                btnNext = $('#btnNext').on('click', function () {
                    if ((index + 1) < trackCount) {
                        index++;
                        loadTrack(index);
                        if (playing) {
                            audio.play();
                        }
                    } else {
                        audio.pause();
                        index = 0;
                        loadTrack(index);
                    }
                }),
                li = $('#plList .tk').on('click', function () {
                    if ($(this).hasClass('plSel')) {
                        return;
                    }
                    var id = parseInt($(this).parent().index());
                    var node = document.getElementById('trackplayerbox');
                    $(this).append(node);
                    $(this).find('.addToCartBtn').attr('data-id', tracks[id].id);
                    $(this).find('.saveItemBtn').attr('data-id', tracks[id].id);
                    $(this).find('.buyTrackBtn').attr('data-id', tracks[id].id);
                    $(this).find('.downloadFreeNow').attr('data-id', tracks[id].id);

                    if (tracks[id].isFree) {
                        $(this).find('.downloadFreeNow').removeClass('d-none');
                        $(this).find('.addToCartBtn').addClass('d-none');
                    } else {
                        $(this).find('.addToCartBtn').removeClass('d-none');
                        $(this).find('.downloadFreeNow').addClass('d-none');
                    }

                    $('#trackplayerbox').removeClass('visually-hidden');

                    playTrack(id);

                }),
                loadTrack = function (id) {
                    $('.plSel').removeClass('plSel');
                    $('#plList > div:eq(' + id + ') .tk').addClass('plSel');
                    // $('body').addClass('trackSelect');
                    npTitle.text(switch_key(tracks[id], 'title'));
                    index = id;
                    audio.src = mediaPath + tracks[id].file + extension;
                    updateDownload(id, audio.src);
                },
                updateDownload = function (id, source) {
                    player.on('loadedmetadata', function () {
                        $('a[data-plyr="download"]').attr('href', source);
                    });
                },
                playTrack = function (id) {
                    loadTrack(id);
                    audio.play();
                };
            // extension = audio.canPlayType('audio/mpeg') ? '.mp3' : audio.canPlayType('audio/ogg') ? '.ogg' : '';
            extension = '';
        } else {
            // no audio support
            $('.column').addClass('hidden');
            var noSupport = $('#audio1').text();
            $('.container').append('<p class="no-support">' + noSupport + '</p>');
        }

        new Plyr("#vplayer", {
            autoplay: false,
            controls: [
                'play',
                'progress',
                'current-time',
                'duration',
                'mute',
                'volume'
            ],
            fullscreen: {
                enabeled: false
            },
            addClass: "rounded",
            loop: {
                active: true
            }
        });
    }

    function pagination($links, $page, $limit, $tracks_count) {
        var $last = Math.ceil($tracks_count / $limit);

        var $start = (($page - $links) > 0) ? $page - $links : 1;
        var $end = (($page + $links) < $last) ? $page + $links : $last;

        var $html = '<ul class="pagination justify-content-center">';

        var $class = ($page == 1) ? "disabled" : "";
        var $class2 = ($page == 1) ? "" : "text-danger";
        $html += '<li class="page-item ' + $class + '"><a class="page-link cursor-pointer ' + $class2 + '" data-id="' + (parseInt($page) - 1) + '">&laquo;</a></li>';

        if ($start > 1) {
            $html += '<li class="page-item"><a class="page-link cursor-pointer" data-id="1">1</a></li>';
            $html += '<li class="page-item disabled"><span class="page-link cursor-pointer">...</span></li>';
        }

        for ($i = $start; $i <= $end; $i++) {
            $class = ($page == $i) ? "active" : "";
            $class2 = ($page == $i) ? "bg-danger border-danger" : "text-danger";
            $html += '<li class="page-item ' + $class + '"><a class="page-link cursor-pointer ' + $class2 + '" data-id="' + $i + '">' + $i + '</a></li>';
        }

        if ($end < $last) {
            $html += '<li class="page-item disabled"><span class="page-link cursor-pointer">...</span></li>';
            $html += '<li class="page-item"><a class="page-link cursor-pointer" data-id="' + $last + '">' + $last + '</a></li>';
        }

        $class = ($page == $last) ? "disabled" : "";
        $class2 = ($page == $last) ? "" : "text-danger";
        $html += '<li class="page-item ' + $class + '"><a class="page-link cursor-pointer ' + $class2 + '" data-id="' + (parseInt($page) + 1) + '">&raquo;</a></li>';
        $html += '</ul>';

        return $html;
    }

});