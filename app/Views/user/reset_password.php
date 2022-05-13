<div class="container vh-100 <?=($action == 'reset_success') ? 'mt-5' : ''; ?>">
    <div class="bg-white my-5 py-5">
        <?php
        if ($action == 'reset_success') {

            echo '<h4 class="text-center text-success">'.lang('main.password_reset_success').'</h4>';
            echo '<h5 class="text-center mt-3 mb-5"><a href="'.base_url('user/signin').'">'.lang('main.back_to_signin').'</a></h5>';

        } else { ?>

            <h4 class="text-center"><?= lang('main.email'); ?>: <?= $action; ?></h4>
            <h5 class="text-center text-secondary mt-3 mb-5"><?= lang('main.reset_your_password'); ?></h5>
            <hr/>
            <?php
            if(!empty($alert_message)){
                echo '<div class="alert alert-danger mx-3"><ul>';

                foreach($alert_message as $alert){
                    echo '<li>'.$alert.'</li>';
                }

                echo '</ul></div>';
            }
            ?>
            <div class="<?=(!empty($alert_message)) ? 'pb-5 pt-3' : 'py-5';?> mx-auto" style="max-width:300px; width:100%;">
                <form action="" method="post">
                    <div>
                        <h6><?= lang('main.password'); ?>:</h6>
                        <input type="password" name="password" class="form-control" placeholder="<?= lang('main.password'); ?>"/>
                    </div>
                    <div class="mt-4">
                        <h6><?= lang('main.repeat_password'); ?>:</h6>
                        <input type="password" name="repeat_password" class="form-control" placeholder="<?= lang('main.repeat_password'); ?>"/>
                    </div>
                    <div class="mt-4">
                        <input type="submit" name="submit" class="btn btn-success"
                               value="<?= lang('main.reset_password'); ?>"/>
                    </div>
                </form>
            </div>

        <?php } ?>
    </div>
</div>