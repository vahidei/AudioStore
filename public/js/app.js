$(document).ready(function(){
    updateCartAmount();

    if(localStorage.getItem('dark_mode') == 'true'){
        $('body').addClass('dark');
        $('#nightModeSwitch').prop('checked', true);
    }

    $(document).on('click', '#nightModeSwitch', function(){
        if($(this).is(':checked')){
            $('body').addClass('dark');
        } else{
            $('body').removeClass('dark');
        }
        localStorage.setItem("dark_mode", $(this).is(':checked'));
    });

    $(document).on('click', '.cartBuyBtn', function(){

        $.ajax({
            type: 'POST',
            url: base_url+'/api/buy',
            dataType: 'json',
            success: function(data){
                if(data.success == false){
                    runToast(data.error, 'bg-warning');
                }

                if(data.success == true){
                    if(data.error == 2){
                        window.location.href = base_url+'/buy/not_logged_in';
                    }else{
                        window.location.href = base_url+'/buy/final';
                    }

                }

            }
        });

    });

    $(document).on('click', '.cartTrack[data-id][data-code]', function(){
        if($(this).hasClass('playing')) return false;

        var node = document.getElementById('audio2');
        $(this).append(node);
        var audio = $('#audio2 audio').get(0);
        if(!audio.paused){
            audio.pause();
        }
        $('#audio2 audio source').remove();

        $(this).find('.bi-play-circle').replaceWith(miniEqualizer('#ccc'));

        audio.src = base_url+'/public/demos/tracks/'+$(this).data('code');

        $('#audio2').removeClass('visually-hidden');
        var player = new Plyr('#audio2 audio', {
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


        $('.cartTrack.playing .equalizer').replaceWith('<i class="bi bi-play-circle"></i>');
        $('.cartTrack.playing').removeClass('playing');

        $(this).addClass('playing');
        audio.play();
    });

    $(document).on('click', '.deleteCartItem[data-id]', function(){
        var id = $(this).attr('data-id');
        if(id == undefined) return;
        if($(this).parent().parent().hasClass('playing')){
            saveCartAudioTag();
        }
        $.ajax({
            type: 'POST',
            url: base_url+'/api/delete_cart_item',
            data: { index: id },
            dataType: 'json',
            success: function (data) {
                $('body .cartBox .card-body ul li[data-index="'+id+'"]').fadeOut().delay(1500).remove();
                if($('.cartBox .card-body ul li')[0] == undefined){
                    $('.cartBox .card-body').html('<div class="bgCart position-absolute h-100 justify-content-center top-0 bottom-0 start-0 end-0 w-100" style="z-index:1000">\n' +
                        '            <i class="bi bi-cart text-light mx-auto position-absolute" style="font-size: 150px;left: calc(50% - 75px);top: calc(50% - 150px);"></i>\n' +
                        '        </div><ul></ul>');
                }
                updateCartAmount();
            }
        });

    });

    $(document).on('click', '.addToCartBtn[data-id]', function(){

        var id = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        if(id == undefined || id < 1 || (type !== 'track' && type !== 'package')) return;
        var that = this;
        btnLoading(that);
        $.ajax({
            type: 'POST',
            url: base_url+'/api/add_to_cart',
            data: { item_id: id , type: type},
            dataType: 'json',
            success: function (data) {
                if(data.success == true){
                    btnLoadingEnd(that);
                    alert('added to cart');
                    updateCartAmount();

                }
            }
        });

    });

    $(document).on('click', '.downloadFreeNow[data-type][data-id]', function(){

        var id = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        if(id == undefined || id < 1 || (type !== 'track' && type !== 'package')) return;
        var that = this;
        btnLoading(that);
        window.location.href = base_url+'/file/free_download/'+type+'/'+id;
        btnLoadingEnd(that);
    });

    $('.btnCartBox').on('click', function(){
        loadCart();
    })

    $(document).on('click', '.saveItemBtn[data-id][data-type]', function(){
        var id = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        if(id == undefined || type == undefined){
            return false;
        }
        var that = this;
        btnLoading(that);
        $.ajax({
            type: 'POST',
            url: base_url+'/api/save_item',
            dataType: 'json',
            data: {item_id: id, type: type},
            success: function(data){
                btnLoadingEnd(that);
                if(data.success == false){
                    runToast(data.error, 'bg-warning');
                }

                if(data.success == true){
                    alert('saved successfuly');

                }

            }
        });

    });

    $(document).on('click', '.removeSaveBtn[data-id][data-type]', function(){
        var id = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        if(id == undefined || type == undefined){
            return false;
        }
        var that = this;
        btnLoading(that);
        $.ajax({
            type: 'POST',
            url: base_url+'/api/remove_saved_item',
            dataType: 'json',
            data: {item_id: id, type: type},
            success: function(data){
                btnLoadingEnd(that);
                if(data.success == false){
                    runToast(data.error, 'bg-warning');
                }

                if(data.success == true){

                    if(type == 'track'){
                        closeTrackTlist();
                        $('.tk[data-id="'+id+'"]').parent().fadeOut(300, function() { $(this).remove(); });
                    }
                    if(type == 'package'){
                        $(that).parentsUntil('.card').parent().parent().fadeOut(300, function() { $(this).remove(); });
                    }
                }

            }
        });

    });

});
String.prototype.toHHMMSS = function () {
    var sec_num = parseInt(this, 10); // don't forget the second param
    var hours   = Math.floor(sec_num / 3600);
    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    var seconds = sec_num - (hours * 3600) - (minutes * 60);

    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    if(hours == '00'){
        return minutes+':'+seconds;
    }
    return hours+':'+minutes+':'+seconds;
}
function number_format(number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function loadCart(){
    $('.cartBox .card-body').html('<div class="bgCart position-absolute h-100 justify-content-center top-0 bottom-0 start-0 end-0 w-100" style="z-index:1000">\n' +
        '            <i class="bi bi-cart text-light mx-auto position-absolute" style="font-size: 150px;left: calc(50% - 75px);top: calc(50% - 150px);"></i>\n' +
        '        </div><ul></ul>');
    $.ajax({
        type: 'POST',
        url: base_url+'/api/display_cart',
        dataType: 'json',
        success: function (data) {
            var price = 0;
            $.each(data, function(index, item){

                if(item.type == 'track'){
                    var html = '<li class="cartTrack" data-id="'+item.id+'" data-code="'+item.code+'" data-index="'+index+'">\n'+
                        '               <div class="container1 d-flex">\n'+
                        '               <i class="bi bi-play-circle"></i>\n'+
                        '                <div class="ps-2 middle">\n' +
                        '                    <h6 class="trackTitle">'+switch_key(item, 'title', lang.suffix)+'</h6>\n' +
                        '                    <h6 class="small text-secondary fw-light">\n'+
                        '                        <span class="trackDuration"><i class="me-1 bi bi-clock small"></i>\n'+
                        '                        '+(item.duration).toHHMMSS()+'</span>\n'+
                        '                        <span class="trackPrice float-end pe-2"><i class="ms-3 bi bi-currency-dollar small"></i>\n' +
                        '                        '+number_format(item.price)+' '+currency+'</span>\n' +
                        '                    </h6>\n' +
                        '                </div>\n' +
                        '                <a class="deleteCartItem" data-id="'+index+'">\n' +
                        '                    <i class="bi bi-trash"></i>\n' +
                        '                </a>\n' +
                        '                </div>\n' +
                        '            </li>';
                }else{
                    var html = '<li class="d-flex cartPackage" data-id="'+item.id+'" data-index="'+index+'">\n'+
                        '               <i class="bi bi-collection-play-fill"></i>\n'+
                        '                <div class="middle">\n' +
                        '                    <h6><a target="_blank" href="'+base_url+'/package/single/'+item.id+'" class="text-reset text-decoration-none">'+item.title+'</a></h6>\n' +
                        '                    <h6 class="small text-secondary fw-light">\n'+
                        '                        <i class="me-1 bi bi-music-note-list small"></i>\n'+
                        '                        '+item.tracksCount+' tracks\n'+
                        '                        <span class="float-end pe-2"><i class="ms-3 bi bi-currency-dollar small"></i>\n' +
                        '                        '+number_format(item.price)+' '+currency+'</span>\n' +
                        '                    </h6>\n' +
                        '                </div>\n' +
                        '                <a class="deleteCartItem" data-id="'+index+'">\n' +
                        '                    <i class="bi bi-trash"></i>\n' +
                        '                </a>\n' +
                        '            </li>';
                }


                $('.cartBox .card-body ul').append(html);
                price += parseInt(item.price);
            });
            displayCart(price);
        }
    });
}

function displayCart(price){
    if($('.cartBox .card-body ul li')[0] == undefined){
        $('.cartBox .card-body').html('<div class="bgCart position-absolute h-100 justify-content-center top-0 bottom-0 start-0 end-0 w-100" style="z-index:1000">\n' +
            '            <i class="bi bi-cart text-light mx-auto position-absolute" style="font-size: 150px;left: calc(50% - 75px);top: calc(50% - 150px);"></i>\n' +
            '        </div><ul></ul>');
    }else{
        $('.cartBox .card-body .bgCart').remove();
        $('.cartBox .card-body ul li').each(function(i){
            var t = $(this);
            setTimeout(function(){ t.css('visibility','visible').hide().fadeIn('slow'); }, (i+1) * 120);
        });
    }
    $('body .cartBox .totalCartAmount, .hdrCartAmount').html(number_format(price));
    $('body').addClass('showCartBox');
}

function updateCartAmount(){
    $.ajax({
        type: 'POST',
        url: base_url+'/api/update_cart_amount',
        dataType: 'json',
        success: function (data) {
            $('body .cartBox .totalCartAmount, .hdrCartAmount').html(number_format(data.value));
        }
    });
}

function closeCart(){
    $('body').removeClass('showCartBox');
    saveCartAudioTag();
}

function miniEqualizer(color, pt){
    if(color == undefined){
        color = 'rgba(255,255,255,.7)';
    }
    if(pt == undefined){
        pt = 'pt-3';
    }
    return '<div class="equalizer '+pt+'" style="width:40px"><svg xmlns="http://www.w3.org/2000/svg"'+
        '        xmlns:xlink="http://www.w3.org/1999/xlink" width="34px" height="34px"'+
        '        viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">'+
        '            <g transform="rotate(180 50 50)">'+
        '            <rect x="12.166666666666668" y="12.5" width="9" height="40"'+
        '       fill="'+color+'">'+
        '            <animate attributeName="height" calcMode="spline"'+
        '        values="50;75;10;50" times="0;0.33;0.66;1" dur="1s"'+
        '        keySplines="0.5 0 0.5 1;0.5 0 0.5 1;0.5 0 0.5 1"'+
        '        repeatCount="indefinite" begin="-0.8s"></animate>'+
        '    </rect>'+
        '    <rect x="28.833333333333336" y="12.5" width="9" height="40"'+
        '    fill="'+color+'">'+
        '    <animate attributeName="height" calcMode="spline"'+
        '    values="50;75;10;50" times="0;0.33;0.66;1" dur="1s"'+
        '    keySplines="0.5 0 0.5 1;0.5 0 0.5 1;0.5 0 0.5 1"'+
        '    repeatCount="indefinite" begin="-0.6s"></animate>'+
        '    </rect>'+
        '    <rect x="45.5" y="12.5" width="9" height="40" fill="'+color+'">'+
        '    <animate attributeName="height" calcMode="spline"'+
        '    values="50;75;10;50" times="0;0.33;0.66;1" dur="1s"'+
        '    keySplines="0.5 0 0.5 1;0.5 0 0.5 1;0.5 0 0.5 1"'+
        '    repeatCount="indefinite" begin="-0.4s"></animate>'+
        '    </rect>'+
        '    <rect x="62.16666666666667" y="12.5" width="9" height="40"'+
        '    fill="'+color+'">'+
        '    <animate attributeName="height" calcMode="spline"'+
        '    values="50;75;10;50" times="0;0.33;0.66;1" dur="1s"'+
        '    keySplines="0.5 0 0.5 1;0.5 0 0.5 1;0.5 0 0.5 1"'+
        '    repeatCount="indefinite" begin="-0.2s"></animate>'+
        '    </rect>'+
        '    <rect x="78.83333333333333" y="12.5" width="9" height="40"'+
        '    fill="'+color+'">'+
        '    <animate attributeName="height" calcMode="spline"'+
        '    values="50;75;10;50" times="0;0.33;0.66;1" dur="1s"'+
        '    keySplines="0.5 0 0.5 1;0.5 0 0.5 1;0.5 0 0.5 1"'+
        '    repeatCount="indefinite" begin="0s"></animate>'+
        '    </rect>'+
        '    </g>'+
        '    </svg></div>';
}

function saveCartAudioTag(){
    var node = $('.cartTrack #audio2').get(0);
    var audio = $('.cartTrack #audio2 audio').get(0);
    if(!audio.paused){
        audio.pause();
    }
    $('#audio2 audio source').remove();
    $('body').append(node);
}

function calcDiscount(price, discount, type){
    if(discount !== null){
        if(type == 'money'){
            $value = price - discount;
            if($value < 0){
                $value = 0;
            }
            return $value;
        }
        return price - (price * (discount / 100));
    }else{
        return price;
    }
}

function runToast(msg, bg){
    if(msg.length < 1) return false;
    if(bg == undefined){
        bg = 'bg-success';
    }
    $('.toast-msg').fadeOut().delay(200).remove();
    $('body').append(
        '<div class="toast-msg position-fixed bottom-0 end-0 p-3" style="z-index: 10001">\n' +
        '            <div class="toast welcome-toast '+bg+' text-white" role="alert" data-bs-autohide="false"\n' +
        '                 aria-live="assertive" aria-atomic="true">\n' +
        '                <div class="d-flex">\n' +
        '                    <div class="toast-body">\n' +
        '                        '+msg+'\n' +
        '                    </div>\n' +
        '                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"\n' +
        '                            aria-label="Close"></button>\n' +
        '                </div>\n' +
        '            </div>\n' +
        '        </div>'
    );
    $('.toast').toast('show');
}

function isFree(price, discount, type){
    $value = price;
    if(type == 'money'){
        $value = price - discount;
    }else{
        $value = price - (price * (discount / 100));
    }
    if($value <= 0){
        return true;
    }
    return false;
}

function switch_key(item, key){
    console.log(item);
    if(item[key + lang.suffix] == ''){
        return item[key];
    }else {
        return item[key + lang.suffix];
    }
}

function closeTrackTlist(){
    var node = document.getElementById('trackplayerbox');
    $('body').append(node);
    audio = $('#audio1').get(0);
    audio.pause();
    $('#trackplayerbox').addClass('visually-hidden');
    $('#plwrap .plSel').removeClass('plSel');
}

function btnLoading(that){
    $(that).addClass('disabled').prepend('<span class="me-2 spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>');
}

function btnLoadingEnd(that){
    $(that).removeClass('disabled').find('.spinner-grow').remove();
}

