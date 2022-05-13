<?php
    if(isset($action) && $action == 'email_sent'){
        echo '<div class="bg-white my-5 py-5 text-center">
                    <h4>'.lang('main.reset_password_email_sent').'</h4>
                </div>';
        goto end;
    }

?>
<div class="container-fluid vh-100" >
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-lg-12 col-xl-11">
            <div class="card text-black border-0 rounded-0 vh-100 light-box-shadow">
                <div class="card-body p-md-5">
                    <div class="row justify-content-center">
                        <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                            <a class="text-reset text-decoration-none" href="javascript:history.go(-1)">
                                <i class="bi bi-arrow-<?=lang('main.side');?>"></i> <?=lang('main.back');?>
                            </a>
                            <?php if(!isset($_SESSION['email_force'])) { ?>
                            <div class="text-center">
                                <a class="btn btn-danger mx-auto" href="google_signin">
                                    <i class="bi bi-google"></i>
                                    <?=lang('main.signin_with_google');?>
                                </a>
                            </div>
                            <?php } ?>
                            <p class="text-center h1 fw-bold mb-4 mx-1 mx-md-4 mt-4"><?=lang('main.signin');?>
                                <br/><span class="h6 lh-sm"><?=lang('main.new_member');?>? <a class="text-reset" href="<?=base_url('user/signup');?>"><?=lang('main.signup');?></a></a></span>
                            </p>

                            <form action="<?=base_url('user/signin');?>" method="post" class="mx-1 mx-md-4">
                                <?php if(!empty($alert_message)){ ?>
                                    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">

                                        <div class="toast text-danger" role="alert" data-bs-autohide="false" aria-live="assertive" aria-atomic="true">
                                            <div class="toast-header text-danger">
                                                <strong class=" me-auto"><?=lang('main.error_occurred');?></strong>
                                                <button type="button" class="btn-close text-white" data-bs-dismiss="toast" aria-label="Close"></button>
                                            </div>
                                            <div class="toast-body">
                                                <ul>
                                                    <?php
                                                    foreach ($alert_message as $key=>$value){
                                                        echo '<li>'.$value.'</li>';
                                                    }

                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="d-flex flex-row align-items-center mb-4">
                                    <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                    <div class="form-outline flex-fill mb-0">
                                        <label class="form-label" for="formem"><?=lang('main.email');?></label>
                                        <?php if(!isset($_SESSION['email_force'])){ ?>
                                            <input type="email" name="email" id="formem" class="form-control" value="<?=(isset($_POST['email'])) ? $_POST['email'] : ''; ?>" />
                                        <?php }else{ ?>
                                            <h4><?=$_SESSION['email_force'];?></h4>
                                            <input type="hidden" name="email" id="formem" value="<?=$_SESSION['email_force'];?>" class="form-control" />
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php if($action !== 'reset_password'){ ?>
                                <div class="d-flex flex-row align-items-center mb-4 passBox">
                                    <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                    <div class="form-outline flex-fill mb-0">
                                        <label class="form-label float-start" for="formpw"><?=lang('main.password');?>
                                        </label>
                                        <input type="password" name="password" id="formpw" class="form-control" />
                                        <a class="float-end text-reset forgotPwBtn cursor-pointer"><?=lang('main.forgot_password?');?></a>
                                    </div>
                                </div>
                                <?php }else{
                                    echo '<input type="hidden" name="forgot_password" value="true">';
                                } ?>
                                <div class="d-flex flex-row align-items-center mb-4 form-group required">
                                    <div class="g-recaptcha required" id="recaptcha"
                                         data-sitekey="6LdZpxYcAAAAABsRrAN2To890s6_YzDdJd1RBQkf" style="padding-<?=lang('main.side');?>:calc(50% - 151.5px)"  >
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                    <input type="submit" name="submit" class="btn submitBtn px-5 btn-primary btn-lg" value="<?=($action !== 'reset_password') ? lang('main.signin') : lang('main.reset_password');?>">
                                </div>

                            </form>

                        </div>
                        <div class="col-md-10 col-lg-6 col-xl-7 align-items-center d-none d-lg-flex order-1 order-lg-2">

                            <img src="<?=base_url('public/img/img.webp');?>" class="img-fluid" alt="Sample image">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.toast').toast('show');

        $('.forgotPwBtn').click(function(){
            $('.passBox').remove();
            $('form').prepend(
                '<input type="hidden" name="forgot_password" value="true">'
            );
            $('.submitBtn').val(lang.reset_password);
        });

    })
</script>
<?php unset($_SESSION['email_force']); end: ?>