$(document).ready(function(){

    $(document).on('click', '.trackListToggleBtn', function(){
        $('.signle-package-tracks-list-box').addClass('open');
    });

    $(document).on('click', '.closeTrackListBtn', function(){
        $('.signle-package-tracks-list-box').removeClass('open');
    });

    $('.trackItem[data-id]').on('click', function(){
        if($(this).hasClass('playing')) return false;

        var node = document.getElementById('trackPlayer');
        $(this).append(node);
        var audio = $('#trackPlayer audio').get(0);
        if(!audio.paused){
            audio.pause();
        }
        $('#trackPlayer audio source').remove();

        $(this).find('.bi-play-circle').replaceWith(miniEqualizer('#666'));

        audio.src = base_url+'/public/demos/tracks/'+$(this).data('code');

        $('#trackPlayer').removeClass('visually-hidden');
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


        $('.trackItem.playing .equalizer').replaceWith('<i class="bi bi-play-circle text-white-50"></i>');
        $('.trackItem.playing').removeClass('playing');

        $(this).addClass('playing');
        audio.play();
    });
});