<div class="bg-light p-2">
    <div style="height:300px">
        <div class="text-center pt-5">
            <i class="bi bi-percent text-warning p-0 m-0 lh-sm" style="font-size:100px"></i>
            <h4 class="text-warning "
                style="font-size:40px"><?= number_format(count($data['list'])); ?> <?= lang('main.discount_available'); ?></h4>
            <h5 class="py-2 fw-light"><?= lang('main.discount_list_sentence'); ?></h5>
        </div>
    </div>
</div>
<div class="light-box-shadow bg-white">

    <div class="px-lg-5 px-md-3  my-5 pb-5 pt-3">
        <div class="row mx-0 discounts">
            <?php
            foreach ($data['list'] as $item) {
                if($item['tracksCount'] !== '0'){
                    $number = '<span class="badge bg-danger">'.number_format($item['tracksCount']).' '.lang('main.track').'</span>';
                }else{
                    $number = '<span class="badge bg-primary">'.number_format($item['packagesCount']).' '.lang('main.package').'</span>';
                }
                echo '<div class="col-lg-6 mt-5"><a class="text-reset text-decoration-none" href="single/'.$item['id'].'">
                            <div class="item rounded-3 light-box-shadow" style="background:url('.base_url('public/demos/discounts/photos/'.$item['coded_name']).')">
                                <div class="title fw-bold p-4 float-start">'.switch_key($item, 'title', $this->ls).'</div>
                                <div class="percent p-4 text-end float-end"><span class="badge bg-warning">'.(($item['discount_type'] == 'money') ? number_format($item['discount']).' '.$this->currency : $item['discount'].'%').'</span></div>
                                <div class="clearfix"></div>
                                <div class="number float-end pe-4">'.$number.'</div>
                                <div class="clearfix"></div>
                                <div class="timer float-start '.lang('main.direction').' text-center p-4 d-flex" data-expire-date="'.$item['expire_date'].'">
                                    <span class="days"></span>
                                    <span class="mt-0 px-2">:</span>
                                    <span class="hours"></span>
                                    <span class="mt-0 px-2">:</span>
                                    <span class="minutes"></span>
                                    <span class="mt-0 px-2">:</span>
                                    <span class="seconds"></span>
                                </div>
                            </div></a>
                        </div>';
            }

            ?>

            <div class="col-lg-6"></div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){


        function makeTimer(ele, expire_date, n) {

            setInterval(function() {
                var endTime = new Date(expire_date);

                endTime = (Date.parse(endTime) / 1000);
                var now = new Date("<?=date('Y-m-d H:i:s');?>");
                now = (Date.parse(now))  / 1000;

                now = now + n;

                var timeLeft = endTime - now;

                var days = Math.floor(timeLeft / 86400);
                var hours = Math.floor((timeLeft - (days * 86400)) / 3600);
                var minutes = Math.floor((timeLeft - (days * 86400) - (hours * 3600 )) / 60);
                var seconds = Math.floor((timeLeft - (days * 86400) - (hours * 3600) - (minutes * 60)));

                if (hours < "10") { hours = "0" + hours; }
                if (minutes < "10") { minutes = "0" + minutes; }
                if (seconds < "10") { seconds = "0" + seconds; }

                $(ele).find(".days").html(days + '<br/><span>'+lang.days+'</span>');
                $(ele).find(".hours").html(hours + '<br/><span>'+lang.hours+'</span>');
                $(ele).find(".minutes").html(minutes + '<br/><span>'+lang.minutes+'</span>');
                $(ele).find(".seconds").html(seconds + '<br/><span>'+lang.seconds+'</span>');
                n = n + 1;
            }, 1000)
        }

        $('.discounts .item').each(function(index){
            makeTimer(this, $(this).find('.timer').attr('data-expire-date'), 0);
        });

    });
</script>