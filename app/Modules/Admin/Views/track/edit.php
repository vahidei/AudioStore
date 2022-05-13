<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit track</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a class="btn btn-secondary" href="../list">
            <span data-feather="list"></span>
            Manage tracks
        </a>
    </div>
</div>

<div class="alert <?=$data['alert_class'];?>">
    <?=$data['alert_message'];?>
</div>
<form action="" method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">
            <h6>Title:</h6>
            <input type="text" name="title" class="form-control" value="<?=$item['title'];?>"/>
        </div>
        <div class="col-md-6 mt-3 mt-md-0">
            <h6>Persian title:</h6>
            <input type="text" name="title_fa" class="rtl form-control" placeholder="Leave blank, if it is the same" value="<?=$item['title_fa'];?>"/>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <h6>Demo file:</h6>
                    <input type="file" name="demo_file" id="demo_file" class="form-control" value=""/>
                    <input type="hidden" name="demo_file_duration" id="demo_file_duration" class="form-control"
                           value=""/>
                </div>
                <div class="col-md-6 mt-3 mt-md-0">
                    <h6>Original file:</h6>
                    <input type="file" name="original_file" id="original_file" class="form-control" value=""/>
                    <input type="hidden" name="original_file_duration" id="original_file_duration" class="form-control"
                           value=""/>
                </div>
            </div>
        </div>
        <div class="col-md-6 mt-3 mt-md-0">
            <div class="row">
                <div class="col-sm-6">
                    <h6>Price (Rials):</h6>
                    <input type="number" name="price" class="form-control" min="0" value="<?=$item['price'];?>"/>
                </div>
                <div class="col-sm-6 mt-3 mt-sm-0">
                    <h6 class="cursor-pointer">
                        <input class="cursor-pointer" <?=($item['buy_limit'] !== '-1') ? 'checked' : '';?> type="checkbox" id="buylimitdisabled">
                        <label class="cursor-pointer" for="buylimitdisabled">Buy limit:</label>
                    </h6>
                    <input type="number" id="buy_limit" name="buy_limit" class="form-control" <?=($item['buy_limit'] == '-1') ? 'disabled' : '';?> min="0" value="<?=($item['buy_limit'] !== '-1') ? $item['buy_limit'] : '';?>"/>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6">
            <h6>Category:</h6>
            <select name="category" class="form-control">
                <option disabled <?=(empty($item['category'])) ? 'seleceted' : '';?>>Select a category--</option>
                <?php
                foreach($data['category_list'] as $key => $value){
                    echo '<option '.(($item['category'] == $value['id']) ? 'seleceted' : '').' value="'.$value['id'].'" >'.$value['title'].'</option>';
                }
                ?>
            </select>
        </div>
        <div class="col-md-6">
            <div class="row statusBox">
                <div class="col-lg-<?= ($item['scheduled'] == '1') ? '6' : '12'; ?> selectCol">
                    <h6>Status:</h6>
                    <select name="status" class="form-control">
                        <option value="publish" <?=($item['status'] == 'publish') ? 'selected' : '';?>>Publish</option>
                        <option value="draft" <?=($item['status'] == 'draft') ? 'selected' : '';?>>Draft</option>
                        <option value="scheduling" <?=($item['scheduled'] == '1') ? 'selected' : '';?>>Scheduling</option>
                        <option value="only_package" <?=($item['status'] == 'only_package') ? 'selected' : '';?>>Only use in packages</option>
                    </select>
                </div>
                <div class="col-lg-6 schedulingCol <?= ($item['scheduled'] == '1') ? '' : 'd-none'; ?>">
                    <h6>Choose a day:</h6>
                    <input type="datetime-local" class="form-control" name="action_time" value="<?=date('Y-m-d\TH:i', strtotime($item['action_time']));?>">
                    <div class="mt-1">
                        <label>
                            <input type="radio" name="action_type" <?= ($item['action_type'] == 'publish') ? 'checked' : ''; ?> value="publish"/> Publish on that day
                        </label>
                    </div>
                    <label>
                        <input type="radio" name="action_type" <?= ($item['action_type'] == 'draft') ? 'checked' : ''; ?> value="draft"/> Draft on that day
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-3 mb-5">
        <input type="submit" name="submit" class="btn btn-success mt-4" value="Submit">
    </div>
</form>
<script>
    $(document).ready(function () {
        var audio = document.createElement('audio');
        var audio1 = document.createElement('audio');

        document.getElementById("original_file").addEventListener('change', function (event) {
            var target = event.currentTarget;
            var file = target.files[0];
            var reader = new FileReader();

            if (target.files && file) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    audio.src = e.target.result;
                    audio.addEventListener('loadedmetadata', function () {
                        $('#original_file_duration').val(audio.duration);
                    }, false);
                };

                reader.readAsDataURL(file);
            }
        }, false);
        document.getElementById("demo_file").addEventListener('change', function (event) {
            var target = event.currentTarget;
            var file = target.files[0];
            var reader = new FileReader();

            if (target.files && file) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    audio1.src = e.target.result;
                    audio1.addEventListener('loadedmetadata', function () {
                        $('#demo_file_duration').val(audio1.duration);
                    }, false);
                };

                reader.readAsDataURL(file);
            }
        }, false);

        $('.statusBox select').on('change', function () {
            if ($(this).val() == 'scheduling') {
                $('.statusBox .selectCol').removeClass('col-lg-12');
                $('.statusBox .selectCol').addClass('col-lg-6');
                $('.statusBox .schedulingCol').removeClass('d-none');
            }else{
                $('.statusBox .selectCol').addClass('col-lg-12');
                $('.statusBox .selectCol').removeClass('col-lg-6');
                $('.statusBox .schedulingCol').addClass('d-none');
            }
        });
    });
</script>
