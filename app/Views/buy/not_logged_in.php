<div class="container bg-white mb-5">
    <div class="py-5 ">
        <h3><?=lang('main.not_logged_in');?></h3>
        <h5 class="fw-light pt-2">
            <?=lang('main.to_continue_login_enter_email');?>
        </h5>

    </div>
    <hr/>
    <div class="alert alert-info">
        <ul>
            <li><?=lang('main.if_you_login');?></li>
            <li><?=lang('main.if_enter_email');?></li>
        </ul>


    </div>
    <div class="row pb-3">
        <div class="col-lg-6 border-end">
            <form action="" method="post">
                <div class="form-outline flex-fill mb-2">
                    <label class="form-label" for="form3Example1c"><?=lang('main.email');?></label>
                    <input name="email" value="" required type="email" id="form3Example1c" class="form-control-lg form-control">
                </div>
                <input type="submit" name="submit" class="btn px-5 btn-primary mb-3" value="<?=lang('main.continue');?>"/>
            </form>

        </div>
        <div class="col-lg-6">
            <div class="d-flex pb-3 pt-5">
                <a href="<?=base_url('user/signin?shopping=true');?>" class="btn btn-lg btn-success flex-grow-1 mx-1"><?=lang('main.signin');?></a>
                <a href="<?=base_url('user/signup?shopping=true');?>" class="btn btn-lg btn-light border flex-grow-1 mx-1"><?=lang('main.signup');?></a>
            </div>

        </div>

    </div>
</div>