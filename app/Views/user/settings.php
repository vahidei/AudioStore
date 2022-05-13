<?php
    if(isset($_POST['language']) && in_array($_POST['language'], ['fa', 'en'])){
        $cookie = $_POST['language'];
    }else{
        $cookie = get_cookie('lang');
    }
?>
<div class="bg-light p-2">
    <div style="height:300px">
        <div class="text-center pt-5">
            <i class="bi bi-gear text-secondary p-0 m-0 lh-sm" style="font-size:100px"></i>
            <h4 class="text-secondary" style="font-size:40px"><?= lang('main.settings'); ?></h4>
            <h5 class="py-2 fw-light"><i class="bi bi-person"></i> <?= ucfirst($_SESSION['user']['name']); ?></h5>
        </div>
    </div>
</div>
<div class="light-box-shadow bg-white mt-5 pt-5">

    <div class="container-xxl px-lg-5 px-md-3  mb-5">
        <?php
            if(!empty($data['alert_message'])){
                echo '<div class="alert '.$data['alert_class'].'">';
                if($data['alert_class'] == 'alert-danger'){
                    echo '<h6>'.lang('main.error_occurred').'</h6>';
                }
                if(is_array($data['alert_message'])){
                    echo '<ul>';
                    foreach($data['alert_message'] as $msg){
                        echo '<li class="pt-2"><h6>'.$msg.'</h6></li>';
                    }
                    echo '</ul>';
                }else{
                    echo $data['alert_message'];
                }
                echo '</div>';
            }

        ?>
        <form action="" method="post" autocomplete="off">
            <div class="row">
                <div class="col-lg-6">
                    <label for="email"><?=lang('main.email');?></label>
                    <input class="form-control mt-1 mb-4" type="email" autocomplete="off" disabled name="email" id="email" value="<?=$_SESSION['user']['email'];?>"/>

                    <label for="name"><?=lang('main.name');?></label>
                    <input class="form-control mt-1 mb-4" type="name" autocomplete="off" name="name" id="name" value="<?=$_SESSION['user']['name'];?>"/>

                    <label for="mobile"><?=lang('main.mobile_number');?></label>
                    <input class="form-control mt-1" type="mobile" autocomplete="off" name="mobile" id="mobile" value="<?=$_SESSION['user']['mobile'];?>"/>

                    <hr class="my-5"/>

                    <label for="password"><?=lang('main.password');?></label>
                    <input class="form-control mt-1 mb-4" type="password" autocomplete="new-password" name="password" id="password" value=""/>

                    <label for="repeat_password"><?=lang('main.repeat_password');?></label>
                    <input class="form-control mt-1" type="password" autocomplete="new-password" name="repeat_password" id="repeat_password" value=""/>

                </div>
                <div class="col-lg-6 mt-5 mt-lg-0 position-relative">
                    <hr class="mb-4 mt-0 d-block d-lg-none"/>
                    <label></label>
                    <div class="alert alert-info mt-1">
                        <h6>
                            <?=lang('settings.language_sentence');?>
                        </h6>

                        <div class="d-flex">
                            <div class="flex-grow-1 pt-2"><?=lang('main.change_language');?>:</div>
                            <div class="flex-grow-1">
                                <select name="language" class="form-control mt-1">
                                    <option <?= (isset($cookie)) ? (($cookie == 'fa') ? 'selected' : '') : '';?> value="fa">فارسی</option>
                                    <option <?= (isset($cookie)) ? (($cookie == 'en') ? 'selected' : '') : '';?> value="en">English</option>
                                </select>
                            </div>
                        </div>



                    </div>

                    <div class="mt-4 alert alert-primary">
                        <h6>
                            <?=lang('settings.subscribe_sentence');?>
                        </h6>
                        <input <?=($_SESSION['user']['subscribe'] == '1') ? 'checked' : '';?> name="subscribe" type="checkbox" id="subscribe"/>
                        <label for="subscribe"><?=lang('main.subscribe');?></label>
                    </div>


                    <div class="mt-4 alert alert-primary">
                        <h6 class="float-start pt-2">
                            <?=lang('settings.dark_light_sentence');?>:
                        </h6>
                        <label class="float-end nav-link py-0 bg-light my-0 btn me-2" style="height:34px" for="nightModeSwitch">
                            <div class="form-check form-switch px-0 ms-5 mx-0 my-0 py-0 pt-1">
                                <label class="form-check-label cursor-pointer" for="nightModeSwitch"><i class="bi bi-moon"></i></label>
                                <input class="form-check-input cursor-pointer" type="checkbox" id="nightModeSwitch">
                            </div>
                        </label>
                        <div class="clearfix"></div>
                    </div>


                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="py-5">
                        <input type="submit" name="submit" class="btn btn-primary px-5" value="<?=lang('main.save');?>"/>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>