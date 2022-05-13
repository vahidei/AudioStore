<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit package</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a class="btn btn-secondary" href="../list">
            <span data-feather="list"></span>
            Manage packages
        </a>
    </div>
</div>
<div class="alert <?=$data['alert_class'];?>">
    <?=$data['alert_message'];?>
</div>

<form action="" method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-12">
            <h6>Tracks:</h6>
            <select name="tracks[]" data-live-search="true" id="track_select" class="selectpicker form-control" multiple>
                <?php
                foreach($data['selected_tracks'] as $key => $track){
                    echo '<option selected value="'.$track['id'].'">'.$track['title'].'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row mt-3">
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
            <h6>Short description:</h6>
            <textarea name="short_desc" class="form-control" rows="5"><?=$item['short_desc'];?></textarea>
        </div>
        <div class="col-md-6 mt-3 mt-md-0">
            <h6>Persian short description:</h6>
            <textarea name="short_desc_fa" class="rtl form-control" rows="5"><?=$item['short_desc_fa'];?></textarea>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6">
            <h6>More description:</h6>
            <textarea name="more_desc" class="form-control" rows="5"><?=$item['more_desc'];?></textarea>
        </div>
        <div class="col-md-6 mt-3 mt-md-0">
            <h6>Persian more description:</h6>
            <textarea name="more_desc_fa" class="rtl form-control" rows="5"><?=$item['more_desc_fa'];?></textarea>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6">
            <h6>Cover file:</h6>
            <input type="file" class="form-control" name="cover"/>
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
    <div class="mt-3 mb-5">
        <h6>
            <input type="checkbox" <?=($item['status'] == 'draft') ? 'checked' : '';?> name="saveindrafts" id="saveindrafts">
            <label class="small" for="saveindrafts">Save in drafts</label>
        </h6>
        <input type="submit" name="submit" class="btn btn-success mt-4" value="Submit">
    </div>
</form>


<script>
    $(document).ready(function(){

        $('#track_select').select2({
            theme: 'bootstrap-5',
            liveSearch:true,
            minimumInputLength: 1,
            ajax: {
                url: base_url+'/track/ajaxItems',
                dataType: 'json',
                contentType: 'application/json',
                delay: 250,
                data: function (params) {
                    return {
                        value: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function(obj){
                            return {id: obj.id, text: obj.title};
                        })
                    };
                }
            }
        });

    });
</script>