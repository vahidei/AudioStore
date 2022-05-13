<div class="container-fluid h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-lg-12 col-xl-11">
            <div class="card text-black border-0 rounded-0 light-box-shadow">
                <div class="card-body p-md-5">
                    <div class="row justify-content-center">
                        <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                            <a class="text-reset text-decoration-none" href="javascript:history.go(-1)">
                                <i class="bi bi-arrow-<?=lang('main.side');?>"></i> <?=lang('main.back');?>
                            </a>
                            <div class="text-center">
                                <a class="btn btn-danger mx-auto" href="google_signin">
                                    <i class="bi bi-google"></i>
                                   <?=lang('main.signup_with_google');?>
                                </a>
                            </div>
                            <p class="text-center h1 fw-bold mb-4 mx-1 mx-md-4 mt-4"><?=lang('main.signup');?>
                                <br/><span class="h6 lh-sm"><?=lang('main.have_an_account?');?> <a class="text-reset"
                                                                                href="<?= base_url('user/signin'); ?>"><?=lang('main.signin');?></a></a></span>
                            </p>

                            <form action="" method="post" class="mx-1 mx-md-4">

                                <?php if(!empty($alert_message)){ ?>
                                <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">

                                    <div class="toast text-danger" role="alert" data-bs-autohide="false" aria-live="assertive" aria-atomic="true">
                                        <div class="toast-header text-danger">
                                            <strong class="me-auto"><?=lang('main.error_occurred');?></strong>
                                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
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
                                    <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                    <div class="form-outline flex-fill mb-0">
                                        <label class="form-label" for="form3Example1c"><?=lang('main.name');?></label>
                                        <input name="name" value="<?=$post['name'];?>" type="text" id="form3Example1c" class="form-control"/>
                                    </div>
                                </div>

                                <div class="d-flex flex-row align-items-center mb-4">
                                    <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                    <div class="form-outline flex-fill mb-0">
                                        <label class="form-label" for="form3Example3c"><?=lang('main.email');?></label>
                                        <input name="email" value="<?=$post['email'];?>" type="email" id="form3Example3c" class="form-control"/>
                                    </div>
                                </div>

                                <div class="d-flex flex-row align-items-center mb-4">
                                    <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                    <div class="form-outline flex-fill mb-0">
                                        <label class="form-label" for="form3Example4c"><?=lang('main.password');?></label>
                                        <input name="password" value="<?=$post['password'];?>" type="password" id="form3Example4c" class="form-control"/>
                                    </div>
                                </div>

                                <div class="d-flex flex-row align-items-center mb-4">
                                    <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                                    <div class="form-outline flex-fill mb-0">
                                        <label class="form-label" for="form3Example4cd"><?=lang('main.repeat_password');?></label>
                                        <input name="repeat_password" value="<?=$post['repeat_password'];?>" type="password" id="form3Example4cd" class="form-control"/>
                                    </div>
                                </div>

                                <div class="d-flex flex-row align-items-center mb-4 form-group required">
                                    <div class="g-recaptcha required" id="recaptcha"
                                         data-sitekey="6LdZpxYcAAAAABsRrAN2To890s6_YzDdJd1RBQkf" style="padding-<?=lang('main.side');?>:calc(50% - 151.5px)"  >
                                    </div>
                                </div>

                                <div class="form-check d-flex justify-content-center mb-5">
                                    <input
                                            class="form-check-input me-2"
                                            type="checkbox"
                                            name="terms"
                                            id="form2Example3c"
                                            <?=($post['terms'] == 'on') ? 'checked' : '';?>
                                    />
                                    <label class="form-check-label" for="form2Example3">
                                         <?=lang('main.agree_all_statements');?><br/><a href="#!"><?=lang('main.term_of_service');?></a>
                                    </label>
                                </div>

                                <div class="d-flex flex-row align-items-center mb-4 form-group required">
                                    <?php
                                    //helper(['form', 'reCaptcha']);
                                    // echo reCaptcha3('reCaptcha3', ['id' => 'recaptcha_v3'], ['action' => 'contactForm']);
                                    ?>
                                </div>

                                <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                    <input name="submit" type="submit" class="btn px-5 btn-primary btn-lg" value="<?=lang('main.signup');?>"/>
                                </div>

                            </form>

                        </div>
                        <div class="col-md-10 col-lg-6 col-xl-7 align-items-center d-none d-lg-flex order-1 order-lg-2">

                            <img src="<?=base_url('public/img/img.webp');?>"
                                 class="img-fluid" alt="Sample image">

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
    })
</script>